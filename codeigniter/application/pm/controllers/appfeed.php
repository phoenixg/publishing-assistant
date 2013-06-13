<?php
error_reporting(E_ALL);

class Appfeed extends CI_Controller {


  function __construct() {
    parent::__construct();
    $this->load->model("Issue");
    $this->load->model("Article");
    $this->load->config("pm_config");
    $this->load->config("appfeed");
    
    $issue = $this->Issue;
	 $issue->init('sci');
	 $this->science_issue=$this->Issue->get_issue_num();
	 
	 $issue->init('stm');
	 $this->stm_issue=$this->Issue->get_issue_num();
	 
	 $issue->init('sig');
	 $this->signaling_issue=$this->Issue->get_issue_num();
	
	
	
  }
  
  
  /**
   * index
   *  Builds the user interface form by reading the config file
   *  and creating a checkbox for each config file item
   *
   * @access public
   * @return void
   */
   
 
  function index() {
    $pm_config = $this->config->item("journals");
    $data['title'] = "Google Appfeed Generator";
    $data['content'] = "Choose feeds to produce:";
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciRSS'), TRUE); 
    $appfeeds = $this->config->item("appfeeds");
    $current_pub = null;
    $data['content'] = form_open('appfeed/make', array('id' => 'rss-form',));  
    

	
	
    foreach ($appfeeds as $appfeed) {
      if ($appfeed["publication"] !== $current_pub) {
        $current_pub = $appfeed["publication"];
        $pub_name = $pm_config[$current_pub]['name'];
        $data['content'] .= "<h3>" . $pub_name . "</h3>";
      }
      $data['content'] .= $this->load->view('shared/checkbox', array(
        'id' => $appfeed["id"], 
        'value' => $appfeed["id"], 
        'label' => $appfeed["name"],
      ), TRUE);
    }
    $data['content'] .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Generate Feed','type'=>'submit'),'');
    
    $data['content'] .= form_close();

    $this->load->view('pagewrapper-tb', $data);
  }
  /**
   * make
   *  For each form item, apply filters specified in config
   *  then run the complete feed build process and return
   *  success for failure
   *
   * @access public
   * @return void
   */
   
   
   function make_config() {
    $form = array('STM'=>'STM');
    $response = array();
    foreach ($form as $appfeed_id) {
      if ($appfeed_config = $this->_find_feed($appfeed_id)) { 
        $this->appfeed_config = $appfeed_config;
     
        $this->Issue->init('stm');
        $this->stm_issue=$this->Issue->get_issue_num();
      
        try {
          $appfeed = $this->_create_test('stm');

          $response[$appfeed_id] = $this->_save($appfeed_config, $appfeed);
        } catch(Exception $e) {
          $response[$appfeed_id] = false;
        }
      }
    }
       
      
    }
   
  private function _create_test() {

    $this->doc = new DOMDocument('1.0', 'utf-8');
    $this->doc->formatOutput = true;
    $this->feeds = $this->doc->createElement('files');
    $file = $this->doc->createElement('file', 'filen');
    $filename = $this->doc->createElement('filename', 'filename');
    $issue = $this->doc->createElement('issue', $this->Issue->get_issue_num());
  
    $this->feeds->appendChild($file);
    $this->feeds->appendChild($filename);
    $this->feeds->appendChild($issue);

    $this->doc->appendChild($this->feeds);
  }
   
   
   
  function make() {
    $form = $this->input->post();
    $response = array();
    foreach ($form as $appfeed_id) {
      if ($appfeed_config = $this->_find_feed($appfeed_id)) { 
        $this->appfeed_config = $appfeed_config;
        $pub = $appfeed_config["publication"];
        $issue = $this->Issue;
        $issue->init($pub);
        try {
          $articles = $issue->get_articles($appfeed_config["data_source"]);
          if (count($appfeed_config['filters']) > 0) {
            foreach ($appfeed_config['filters'] as $el => $pattern) {
              $issue->filter_articles($articles, $el, $pattern);
            }
          }
          $appfeed = $this->_create_feed($pub);
          foreach ($articles as $article) {
            $this->_feed_add_item($article);
          }
          $response[$appfeed_id] = $this->_save($appfeed_config, $appfeed);
        } catch(Exception $e) {
          $response[$appfeed_id] = false;
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
  private function _create_feed() {

    $this->doc = new DOMDocument('1.0', 'utf-8');
    $this->doc->formatOutput = true;
    $this->feed = $this->doc->createElement('feed');
    $title = $this->doc->createElement('title', $this->appfeed_config['name']);
    $vol = $this->doc->createElement('vol', $this->Issue->get_volume_num());
    $issue = $this->doc->createElement('issue', $this->Issue->get_issue_num());
    $covercaption = $this->doc->createElement('covercaption', $this->Issue->get_cover_caption());
    $updated = $this->doc->createElement('updated', $this->Issue->get_publish_date('d F Y'));
    $id = $this->doc->createElement('id', $this->appfeed_config['id']);
    $subtitle = $this->doc->createElement('subtitle', $this->appfeed_config['description']);
	
	
	    $thumbnail= $this->doc->createElement('thumbnail', $this->appfeed_config['thumbnail_path']);
	
	
    $this->feed->appendChild($title);
    $this->feed->appendChild($vol);
    $this->feed->appendChild($issue);
    $this->feed->appendChild($covercaption);
    $this->feed->appendChild($id);
    $this->feed->appendChild($updated);
    $this->feed->appendChild($id);
    $this->feed->appendChild($subtitle);
	
	 $this->feed->appendChild($thumbnail);
	
    $this->doc->appendChild($this->feed);
  }



 

  /**
   * _feed_add_item
   *
   * @param mixed $article
   * @return void
   */
  private function _feed_add_item($article) {
    $entry = $this->doc->createElement('entry');
    $section = $this->doc->createElement('section', htmlentities($article->get_section()));
    $title = $this->doc->createElement('title', $article->get_title());
    $doi = $this->doc->createElement('doi', $article->get_doi());
    $is_special = ($article->get_isSpecialIssue()) ? "Yes" : "No";
    $specialissue = $this->doc->createElement('specialissue', $is_special);
    $fpage = $this->doc->createElement('fpage', $article->get_fpage());
    $fulltext = $this->doc->createElement('fulltext', $article->get_url());
    $pdf = $this->doc->createElement('pdf', $article->get_url());
    $summarylink = $this->doc->createElement('summarylink', $article->get_url());
    $has_som = ($article->get_som()) ? "Yes" : "No";
    $som = $this->doc->createElement('som', $has_som);
    $published = $this->doc->createElement('published', $this->Issue->get_publish_date('d F Y'));
    $summary = $this->doc->createElement('summary', $article->get_abstracts('web-summary'));
    $authors = $this->doc->createElement('authors', $article->get_authors_toString(5));
    $rightslink = $this->doc->createElement('rightslink', $this->_rightslink($article));
    $category = $this->doc->createElement('category', $article->get_fields_category());
	 $thumbnail = $this->doc->createElement('thumbnail', $this->_thumbnail($article));
	 $content = $this->doc->createElement('content', $article->get_abstracts());
    $entry->appendChild($section);
    $entry->appendChild($title);
    $entry->appendChild($doi);
    $entry->appendChild($specialissue);
    $entry->appendChild($fpage);
    $entry->appendChild($fulltext);
    $entry->appendChild($pdf);
    $entry->appendChild($summarylink);
    $entry->appendChild($published);
    $entry->appendChild($som);
    $entry->appendChild($summary);
    $entry->appendChild($authors);
    $entry->appendChild($rightslink);
    $entry->appendChild($category);
	$entry->appendChild($thumbnail);
	$entry->appendChild($content);
    $this->feed->appendChild($entry);
  }

  private function _find_feed($appfeed_id) {
    $feeds = $this->config->item('appfeeds');
    foreach ($feeds as $feed) {
      if ($feed['id'] === $appfeed_id) { return $feed; }
    }
    return false; // id not found
  }
  /**
   * _save
   *  the namespaces are printed again, which is redundant. 
   *  See: http://stackoverflow.com/questions/3073631/making-the-nodes-to-ignore-namespaces-prefixes-after-changing-xml-structure-p
   *
   * @param mixed $config
   * @param mixed $appfeed
   * @return void
   */
  private function _save($config, $appfeed) {
    $outfile = $this->Issue->path_strtr($config['output']) ;
	
	// $outfile_config = $this->Issue->path_strtr($config['output_config']);
    return (file_put_contents($outfile, $this->doc->saveXML()) > 0); 
	//return (file_put_contents($outfile_config, $this->doc->saveXML()) > 0); 
  }


  private function _rightslink($article) {
    $section = $article->get_section();
    switch ($section) {
    case 'articlce':
      $section = 'A';
      break;
    case 'brevia':
      $section = 'B';
      break;
    case 'report':
      $section = 'R';
      break;
    default: 
      $section = 'PC';
    }

    $url = "https://s100.copyright.com/AppDispatchServlet?publisherName=AAAS&publication=%s&title=%s&publicationDate=%s&author=%s&contentID=%s&volumeNum=%s&issueNum=%s&startPage=%s&endPage=%s&section=%s&copyright=%s&orderBeanReset=Yes";

    return urlencode(sprintf($url,
      $this->Issue->get_id(),
      $article->get_title(),
      $article->get_pub_date(),
      $article->get_authors_toString(),
      $article->get_publisher_id(),
      $this->Issue->get_volume_num(),
      $this->Issue->get_issue_num(),
      $article->get_fpage(),
      $article->get_lpage(),
      $section,
      "American Association for the Advancement of Science"
    ));
  }
  
  
  

  
  
   private function _thumbnail($article) {
   	
	//  print($appfeed_config["thumbnail_path"]);
	
	
   if (empty($appfeed_config["thumbnail_path"]) )
   {
   $url1 = 'http://content.aaas.org/images/science/default.png';
   }
   else {
     $url1 = 'http://content.aaas.org/images/stm/'.$this->Issue->get_volume_num(). $article->get_fpage().'_th1'.'.gif';  
   }
  return urlencode(sprintf($url1));
   
  }
  
  
  
  
  
}
