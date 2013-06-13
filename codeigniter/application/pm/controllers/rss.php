<?php
error_reporting(E_ALL);
class RSS extends CI_Controller {

  function __construct() {
    include(APPPATH."/third_party/FeedWriter/FeedTypes.php");
    parent::__construct();
    $this->load->model("Issue");
    $this->load->model("Article");
    $this->load->config("pm_config");
    $this->load->config("rss_feeds");
    $this->load->driver('cache', array('adapter' => 'file'));
  }
  function index() {
    $pm_config = $this->config->item("journals");
    $data['title'] = "RSS Generator";
    $data['content'] = "Choose feeds to produce:";
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciRSS'), TRUE); 
    $rss_feeds = $this->config->item("rss_feeds");
    $current_pub = null;
    $data['content'] = form_open('rss/make', array('id' => 'rss-form',));  
    
    foreach ($rss_feeds as $rss_feed) {
      if ($rss_feed["publication"] !== $current_pub) {
        $current_pub = $rss_feed["publication"];
        $pub_name = $pm_config[$current_pub]['name'];
        $data['content'] .= "<h3>" . $pub_name . "</h3>";
      }
      $data['content'] .= $this->load->view('shared/checkbox', array(
        'id' => $rss_feed["id"], 
        'value' => $rss_feed["id"], 
        'label' => $rss_feed["name"],
      ), TRUE);
    }
    $data['content'] .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Generate RSS','type'=>'submit'),'');
    
    $data['content'] .= form_close();

    $this->load->view('pagewrapper-tb', $data);
  }
  /**
   * mobilenews
   *  This is for the mobilenews application. It does not produce an RSS feed.
   *
   * @param mixed $category
   * @param mixed $count
   * @access public
   * @return void
   */
  function mobilenews($category = null, $count = null) {



    $this->load->library('rssparser', array($this, '_parseRssMedia'));
    $this->rssparser->set_feed_url("http://news.sciencemag.org/rss/current.xml");
    $article_count = ($count > 0) ? $count : 5;

    $rss = $this->rssparser->getFeed($article_count);        // collect x items from feed
    $news_headlines = array();
    foreach ($rss as $item) {
      if (is_null($category) || $category == "all" ||  preg_match("/{$category}/", smartcase($item['category'],'strtolower'))) {
        if ($cached_item =  $this->cache->get(urlencode($item['link']))) { 
          array_push($news_headlines, $cached_item);
        }
        else {
           $doc = new DOMDocument('1.0', 'utf-8');
           @$doc->loadHTML(file_get_contents($item['link']));
           $xpath = new DOMXPath($doc);
           $article_content = "<div id='content-main'";
           if ($image = $xpath->query("//img[contains(@class, 'sci-inline-feature-image')]")->item(0)) {
             $article_content = $doc->saveHTML($image);
           } elseif ($image = $xpath->query("//div[contains(@class, 'sci-shot-image')]//img")->item(0)) {
             $article_content = $doc->saveHTML($image);
           }
           $article_content .= $doc->saveHTML( 
             $xpath->query("//div[contains(@class, 'content-main')]")->item(0)
           );
           if ($xq = $xpath->query("//div[contains(@class, 'sci-content')]/div[contains(@class, 'sci-related')]//a[not(img)]")->item(0) ) {
             $related = $doc->saveHTML($xq);
           }
           $article_content .= "</div>";

           $news_item = array(
             'title'       => $item['title'], 
             'link'        => $item['link'],
             'date'        => date('M d',strtotime($item['pubDate'])),
             'description'   => $item['description'], 
             'thumbnail'   => $item['thumbnail'], 
             'authors'   => $item['authors'], 
             'category'    => smartcase($item['category'],'strtoupper'), 
             'body'        => $article_content,
             'related'        => $related,
           );
           array_push($news_headlines, $news_item);
           $this->cache->save(urlencode($item['link']), $news_item, 7200);
        }
      }
    }
    echo json_encode($news_headlines);
  }
  /**
   * save
   *
   * @param mixed $config
   *  RSS configuration array
   * @param mixed $rss_feed
   *  RSS item
   * @access public
   * @return void
   */
  function _parseRssMedia($data, $item) {
    foreach ($item->xpath("media:thumbnail/@url") as $media) {
      $data['thumbnail'] = (string)$media->url;
    }
    foreach ($item->xpath("media:category") as $category) {
      !$data['category'] = (string)$category;
    }
    foreach ($item->xpath("media:credit") as $credit) {
      $data['authors'] = (string)$credit;
    }
    return $data;
  }
  /**
   * make
   *  builds an RSS feed adding one RSS item per article in an issue
   *
   * @access public
   * @return void
   */
  function make() {
    $form = $this->input->post();
    $response = array();
    foreach ($form as $rss_feed_id) {
      if ($rss_feed_config = $this->_find_feed($rss_feed_id)) { 
        // storing this as a class variable so its visible to class functions
        $this->rss_config = $rss_feed_config;
        $pub = $rss_feed_config["publication"];
        $issue = $this->Issue;
        $issue->init($pub);
        $rss_feed = $this->_create_feed($pub);
        // if get_articles fails it will throw an exception
        try {
          $articles = $issue->get_articles($rss_feed_config["data_source"]);
          if (count($rss_feed_config['filters']) > 0) {
            foreach ($rss_feed_config['filters'] as $el => $pattern) {
              $issue->filter_articles($articles, $el, $pattern);
            }
          }
          // use our custom article building function if it exists, otherwise add articles normally
          if (! (method_exists($this, "_" . $rss_feed_id) && $this->{"_" . $rss_feed_id}($articles)) ) {
            foreach ($articles as $article) {
              $this->_feed_add_article($article);
            }
          }
          $response[$rss_feed_id] = $this->_save($rss_feed_config, $rss_feed);
        } catch(Exception $e) {
          $response[$rss_feed_id] = false;
        }
      }
    }
    // override the RSS library's header call
    header("Content-Type: application/json");
    echo json_encode($response);

  }
  private function _create_feed($pub) {
    $this->RSS = new RSS1FeedWriter();
    $rss_feed = $this->RSS;
    $rss_feed->setTitle($this->rss_config['name']);
    $rss_feed->setLink("http://www.sciencemag.org");
    $rss_feed->setDescription($this->rss_config['description']);
    $rss_feed->setChannelAbout("http://www.sciencemag.org/about");
    $rss_feed->setChannelElement("dc:publisher", "American Association for the Advancement of Science");
    $rss_feed->setChannelElement("dc:creator", "Stewart Wills (mailto:swills@aaas.org)");
    $rss_feed->setChannelElement("dc:rights", "Copyright 2013 American Association for the Advancement of Science");
    $rss_feed->setChannelElement("dc:date", date('Y-m-d\TH:i:sO')); // ISO-8601
    $rss_feed->setChannelElement("dc:language", "en");
    // Add dublin core items
    return $rss_feed;
  }
  /**
   * _feed_add_article
   *  adds an RSS item based on an Article object
   *
   * @param mixed $article
   * @param mixed $overrides
   *  An array of possible overrides for use with feed-specific logic
   * @return void
   */
  private function _feed_add_article($article, $overrides=null) {
    $abstract = (!empty($this->rss_config['abstract'])) ? $this->rss_config['abstract'] :null;
    $abstract = $article->get_abstracts($abstract);
    $item = $this->RSS->createNewItem();
    $title = (isset($overrides['title'])) ? $overrides['title'] : "[{$article->get_article_type()}] {$article->get_title()}";
    $item->setTitle($title);
    $link = (isset($overrides['link'])) ? $overrides['link'] : $article->get_url();
    $item->setLink($link . "?rss=1");
    $item->setDate($this->Issue->get_publish_date('Y-m-d\TH:i:sO'));
    if ($authors = $article->get_authors_list()) {
      $all_authors = join(", ", $authors);
      $all_authors = (count($authors) === 1) ? "Author: " . $all_authors : "Authors: " . $all_authors;
      //$item->addElement("dc:creator", $authors );
      foreach ($authors as $author) {
        $item->addElement("dc:creator", $author);
      }
    } else {
      $all_authors = "";
    }
    if (isset($overrides['description'])) {
      $item->setDescription($overrides['description']);
    }
    elseif (!empty($abstract)) {
      $item->setDescription(strip_tags($abstract) . $all_authors);
    } else {
      $item->setDescription($all_authors);
    }
    $subject = $article->get_overline();
    if (!empty($subject)) {
      $item->addElement("dc:subject", $article->get_overline());
    }
    $this->RSS->addItem($item);
  }
  /**
   * _find_feed
   *  Returns a config array based on the RSS feed id
   *
   * @param mixed $rss_feed_id
   * @return void
   */
  private function _find_feed($rss_feed_id) {
    $feeds = $this->config->item('rss_feeds');
    foreach ($feeds as $feed) {
      if ($feed['id'] === $rss_feed_id) { return $feed; }
    }
    return false; // id not found
  }
  /**
   * _save
   *  Outputs the RSS feed
   *
   * @param mixed $config
   * @param mixed $rss_feed
   * @return void
   */
  private function _save($config, $rss_feed) {
    $outfile = $config['output'];
    ob_start();
    $rss_feed->generateFeed();
    $rss_file = ob_get_contents();
    ob_end_clean();
    return (file_put_contents($outfile, $rss_file) > 0); 
  }

  /**
   * Returns a summary of articles based on article titles
   */
  private function _summarize($article_array) {
    $descriptions = array();
    foreach($article_array as $article) {
      $descriptions[] = $article->get_title();
    }
    return join(" | ", $descriptions);
  }
  /**
   * _sci_toc
   *  business logic for current.xml feed
   *
   * @param mixed $articles
   * @return void
   */
  private function _sci_toc($articles) {

    $base_url = "http://www.sciencemag.org/{$this->Issue->get_volume_num()}/{$this->Issue->get_issue_num()}/";

    // This Week in Science//
    $twis_description = $this->_summarize(array_filter($articles, function($article) { 
      return (preg_match("/this week in science/i", $article->get_article_type()));
    }));
    $twis_processed = false;

    // Editors' Choice //
    $twil_description = $this->_summarize(array_filter($articles, function($article) { 
      return (preg_match("/editors' choice/i", $article->get_article_type()));
    }));
    $twil_processed = false;

    // Findings //
    $findings_description = $this->_summarize(array_filter($articles, function($article) { 
      return (preg_match("/findings/i", $article->get_article_type()));
    }));
    $findings_processed = false;

    foreach ($articles as $article) {
      $article_type = strtolower($article->get_article_type());
      
      switch ($article_type) {
      case "this week in science" :
        if (!$twis_processed) {
          $twis_processed = true;
          $this->_feed_add_article($article, array(
            'title' => "This Week in Science",
            'link' => $base_url . "twis.full",
            'description' => $twis_description,
          ));
        }
        break;
      case "editors' choice" :
        if (!$twil_processed) {
          $twil_processed = true;
          $this->_feed_add_article($article, array(
            'title' => "Editors' Choice",
            'link' => $base_url . "twil.full",
            'description' => $twil_description,
          ));
        }
        break;
      case "findings" :
        if (!$findings_processed) {
          $findings_processed = true;
          $this->_feed_add_article($article, array(
            'title' => "Findings",
            'link' => $base_url . "findings.full",
            'description' => $findings_description,
          ));
        }
        break;
      case "business office feature" : 
        $authors = $article->get_authors_list();
        $this->_feed_add_article($article, array('description' =>$authors[0]));
        break;
      default: 
        $this->_feed_add_article($article);
      }
    }
    return true;
  }
}
