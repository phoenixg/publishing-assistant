<?php

class Sitefeed extends CI_Controller {


  function __construct() {
    parent::__construct();
    $this->load->model("Issue");
    $this->load->model("Article");
    $this->load->config("pm_config");
    $this->load->config("sitefeed");
    $this->load->driver('cache', array('adapter' => 'file'));
    $this->xmlns_news = "http://www.google.com/schemas/sitemap-news/0.9";
  }
  function index() {
    $pm_config = $this->config->item("journals");
    $data['title'] = "Google Sitefeed Generator";
    $data['content'] = "Choose feeds to produce:";
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciRSS'), TRUE); 
    $sitefeeds = $this->config->item("sitefeeds");
    $current_pub = null;
    $data['content'] = form_open('sitefeed/make', array('id' => 'rss-form',));  
    
    foreach ($sitefeeds as $sitefeed) {
      if ($sitefeed["publication"] !== $current_pub) {
        $current_pub = $sitefeed["publication"];
        $pub_name = $pm_config[$current_pub]['name'];
        $data['content'] .= "<h3>" . $pub_name . "</h3>";
      }
      $data['content'] .= $this->load->view('shared/checkbox', array(
        'id' => $sitefeed["id"], 
        'value' => $sitefeed["id"], 
        'label' => $sitefeed["name"],
      ), TRUE);
    }
    $data['content'] .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Generate Feed','type'=>'submit'),'');
    
    $data['content'] .= form_close();

    $this->load->view('pagewrapper-tb', $data);
  }
  /**
   * make
   *
   * @access public
   * @return void
   */
  function make() {
    $form = $this->input->post();
    $response = array();
    foreach ($form as $sitefeed_id) {
      if ($sitefeed_config = $this->_find_feed($sitefeed_id)) { 
        $this->sitefeed_config = $sitefeed_config;
        $pub = $sitefeed_config["publication"];
        $issue = $this->Issue;
        $issue->init($pub);
        try {
          $articles = $issue->get_articles($sitefeed_config["data_source"]);
          if (count($sitefeed_config['filters']) > 0) {
            foreach ($sitefeed_config['filters'] as $el => $pattern) {
              $issue->filter_articles($articles, $el, $pattern);
            }
          }
          $sitefeed = $this->_create_feed($pub);
          foreach ($articles as $article) {
            $this->_feed_add_item($article);
          }
          $response[$sitefeed_id] = $this->_save($sitefeed_config, $sitefeed);
        } catch(Exception $e) {
          $response[$sitefeed_id] = false;
        }
      }
    }
    // override the RSS library's header call
    header("Content-Type: application/json");
    echo json_encode($response);

  }
  /**
   * _create_feed
   *
   * @param mixed $pub
   * @return void
   */
  private function _create_feed($pub) {
    $this->doc = new DOMDocument('1.0', 'utf-8');
    $this->doc->formatOutput = true;
    $this->urlset = $this->doc->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
    $this->urlset->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:news', $this->xmlns_news);
    $this->doc->appendChild($this->urlset);
  }
  /**
   * _feed_add_item
   *
   * @param mixed $article
   * @return void
   */
  private function _feed_add_item($article) {
    $url = $this->doc->createElement('url');
    $loc = $this->doc->createElement('loc', $article->get_url());
    $url->appendChild($loc);
    $news = $this->doc->createElementNS($this->xmlns_news, 'news:news');
    $publication = $this->doc->createElementNS($this->xmlns_news, 'news:publication');
    $name = $this->doc->createElementNS($this->xmlns_news, 'news:name', $this->Issue->get_name());
    $language = $this->doc->createElementNS($this->xmlns_news, 'news:language', 'en');
    $publication->appendChild($name);
    $publication->appendChild($language);
    $news->appendChild($publication);
    $access_value = ($article->get_custom_meta('access')) ? $article->get_custom_meta('access') : $this->sitefeed_config['access'];
    $access = $this->doc->createElementNS($this->xmlns_news, 'news:access', $access_value);
    $genres = $this->doc->createElementNS($this->xmlns_news, 'news:genres');
    $publication_date = $this->doc->createElementNS($this->xmlns_news, 'news:publication_date', $article->get_pub_date());
    $title = $this->doc->createElementNS($this->xmlns_news, 'news:title', $article->get_title());
    $keywords = $this->doc->createElementNS($this->xmlns_news, 'news:keywords', 
      join(",", array_merge(array('science'),$article->get_fields())) );
    $news->appendChild($access);
    $news->appendChild($genres);
    $news->appendChild($publication_date);
    $news->appendChild($title);
    $news->appendChild($keywords);
    $url->appendChild($news);
    $this->urlset->appendChild($url);
  }
  private function _find_feed($sitefeed_id) {
    $feeds = $this->config->item('sitefeeds');
    foreach ($feeds as $feed) {
      if ($feed['id'] === $sitefeed_id) { return $feed; }
    }
    return false; // id not found
  }
  /**
   * _save
   *  the namespaces are printed again, which is redundant. 
   *  See: http://stackoverflow.com/questions/3073631/making-the-nodes-to-ignore-namespaces-prefixes-after-changing-xml-structure-p
   *
   * @param mixed $config
   * @param mixed $sitefeed
   * @return void
   */
  private function _save($config, $sitefeed) {
    $outfile = $config['output'];
    return (file_put_contents($outfile, $this->doc->saveXML()) > 0); 
  }
}

