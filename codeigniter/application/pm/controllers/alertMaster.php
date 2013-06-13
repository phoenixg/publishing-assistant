<?php
error_reporting(E_ALL);
class alertMaster extends CI_Controller {


  //constructor =======================================================================
  function __construct()
  {
    //set up the controll construct
    parent::__construct();
    //load our helper files
    //the required helpers are actually being called by the library, so this line could come out, but depending on how
    //functions get shifted, I'm leaving this in here (commented out) for the time being.
    $this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));

    //load our library functions, where most of the xml functions are located
    $this->load->model('Issue_model');
    $this->load->config('pm_config');
    $this->journals = $this->config->item('journals');
    $this->alerts = $this->config->item('alerts');
    $this->teaser_options = $this->config->item('teaser_options');
  }

  /**
   * index
   *
   * Default - List avalible etoc alert templates.
   *
   * @author AWhitesell
   * @return void
   **/
  function index(){
    //Set the HTML page title.
    $data['title'] = "Review and Send eAlerts";

    $alert_array = array();
    // Set $alert_array to a key=>$val array where $key = the publication, and $val will = an array of alert data (with the alert title, and link to the output)
    // Example: sci[alert] = array(link=> 'address', name => 'a alert')
    foreach($this->alerts as $publication=>$alert){

      // Store a reference to the common data for each issue
      $common_data = getIssueDataByDate($this->journals[$publication]['publish_day'], $this->journals[$publication]['data_path']);

      // we only want to set this up for publications that have alerts in their alert array, located in pm_config.
      if(!empty($alert)){
        foreach($alert as $key=>$aData ){

          $lastmod;
          if(file_exists($this->alerts[$publication][$key]['outpath'].'/index.html')){
            $lastmod = date('d F H:i', filemtime($this->alerts[$publication][$key]['outpath'].'/index.html'));
          }else{
            $lastmod = 'Never';
          }

          $subject = strReplaceDates($aData['eloqua']['subject'], $common_data);
          $alert_array[$publication]['line'] .= $this->load->view('alerts/alerts_main_line', array('code' => $key, 'lastmod' => $lastmod, 'pub' => $publication, 'title' => $subject, 'link' => "alertMaster/compileSummary/{$publication}/{$key}"), TRUE);
          if(! is_null($aData['publication'])){
            $alert_array[$publication]['publication'] = $aData['publication'];
            $alert_array[$publication]['cover_link'] = findCurrentCover($common_data, $publication, $this->journals[$publication]['cover_base']);
          }

        }
      } 
    }
    //put our alerts array into the $data array for the view to use
    $data['alerts_array']= $alert_array;

    //load the content view
    $tabnumber = 0; // starting tab ID 
    foreach ($data['alerts_array'] as $pub => $alert) {
      $attributes = array(
        'pub' => $pub, 
        'alert' => $alert,
        'tab' => 'tab'.++$tabnumber,
        'alert_title' => $this->journals[$pub]['name'],
      );
      $data['alert-tabs'] .= $this->load->view('alerts/alerts_main', $attributes, TRUE);
    }
    //send the content view to the page view.
    $data['content'] = $this->load->view('shared/tab_contents', array('tabcontents' => $data['alert-tabs']) ,TRUE);	
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublishAlert'), TRUE); 
    $this->load->view('pagewrapper-tb', $data);	

  }	


  /**
   * compileSummary
   *
   * Compile an grouped content-list alert for the specified source directories for a publication.
   *
   * @author AWhitesell
   * @return void
   **/
  function compileSummary($journal_id, $alert_id){
    //$this->output->enable_profiler(TRUE);
    //set up our variables
    $prepare_for_eloqua = FALSE;
    if (isset($journal_id) && isset($alert_id)) {
      $alert_args = array($journal_id => array($alert_id));
      $redirect = base_url().$this->alerts[$journal_id][$alert_id]['outpath']."/index.html";
    }
    else {
      $alert_args = $this->input->post(); 	// get the alerts to compile from the post 
      $prepare_for_eloqua = TRUE;
      $eloqua_results = array('results' => 'no data processed');
    }
    $jname;	            	//the abbreviated journal name.   
    $collection = array();	// array of articles contained in a given issue
    $data['title'] = "Alerts Created"; 	//title of the page
    $data['alertsProcessed'] = array();	// array of alerts that have been processed
    $files_saved = array(); //array of files that have been saved
    $eloqua_emails = array(); // eloqua email configurations for each alert

    //loop through each publication.
    foreach($alert_args as $jname => $alerts){

      //loop through each alert that needs to be compiled
      foreach($alerts as $alert){
        $alert_data = array();
        $alert_data['alert'] = $alert; //make the alert code avalible to the templates 
        $alert_data['jname'] = $jname; //make the jname avalible to templates
        $journal = $this->journals[$jname]; //grab the information related to our journal
        $order = $this->alerts[$jname][$alert]['order'];  // grab the order for the alert
        $notePos = $this->alerts[$jname][$alert]['editors_notes'];	  //grab the editor's notes placement
        $alertBasePath = $this->alerts[$jname][$alert]['outpath']; //find the base path (called outpath) in the $config[alerts] array from pm_config. 
        $nextPubDay = getNextPubDay('', $journal['publish_day']);  //the next publication day.
        $alert_data['alertName'] = $this->alerts[$jname][$alert]['name']; //make the alert name avalibe to the templates
        $alert_data['publicationName'] = $this->alerts[$jname][$alert]['publication']; //make the alert publication name avalibe to the templates
        $alert_data['sub_heading'] = $this->alerts[$jname][$alert]['sub_heading'];

        if(! empty($journal)){
          $alert_data['pubdate'] = date('j F Y', strtotime($nextPubDay));  //next publication date in format of d Month yyyy		
        }else{
          $alert_data['pubdate'] = date('j F Y');
        }

        // If the $journal and $journal xml root are not empty, then we have xml to pull, else, there is no xml to pull
        // likely this would be due to a news alert.
            
        if( !empty($journal) && !empty($journal['article_xml_root']) )  {
        	
          $issueData = getIssueDataByDate($journal['publish_day'], $journal['data_path'], $nextPubDay);
          $alert_data['cover_link'] = findCurrentCover($issueData, $jname, $this->journals[$jname]['cover_base'], true); //the cover of the issue.
          $alert_data['vol'] = $issueData['VOLUME_NUM'];
          $alert_data['issue'] = $issueData['ISSUE_NUM'];
  
          $collection = $this->_getArticleXML($journal, $jname, $issueData, $alert);

          $order = $this->_orderXML(
            $order,
            $collection[$this->alerts[$jname][$alert]['data_source']],
            $this->alerts[$jname][$alert]['ads'],
            $this->teaser_options[$this->alerts[$jname][$alert]['teaser_option']],
            $notePos
          );

          $order = $this->_removeUnusedSections($order);

          //add any header, footer editorial content.
          $order = $this->_editorialBookends($order, $notePos);

          //send the array to the view for processing.
          $alert_data['order'] = $order;

          //create the sections
          $alert_data['sections'] = $this->load->view('templates/alert/body_section', $alert_data, TRUE);

          //create the body section
          $alert_data['body'] = $this->load->view('templates/alert/body_wrapper', $alert_data, TRUE);

        }//end if

        
        
        //Place content into correct variables
        foreach($this->alerts[$jname][$alert]['content'] as $contArray){
          $alert_data[$contArray['target']] .=	$this->_getInclude($contArray, $alert_data);
        }

        //compile final output
        $processed = $this->load->view( $this->alerts[$jname][$alert]['template'], $alert_data, TRUE);

        $this->_saveOutput($processed, $alertBasePath.'/index.html');

        // capture variables for sending Eloqua test messages
        if ($prepare_for_eloqua) {
          array_push( $eloqua_emails, array(
            'name'    => $this->alerts[$jname][$alert]['eloqua']['eloqua_short_name'].date('Ymd'),
            'subject' => strReplaceDates($this->alerts[$jname][$alert]['eloqua']['subject'], $issueData),
            'group'   => $this->alerts[$jname][$alert]['eloqua']['eloqua_group_id'],
            'html'    => $processed,
            'from'    => $this->alerts[$jname][$alert]['eloqua']['eloqua_from'],
          ));
        }

        array_push($files_saved, $alert_data['jname']."_".$alert_data['alert']);

        $data['alertsProcessed'][$jname.' - '.$alert] = '../../'.$alertBasePath.'/'.'index.html';

        unset($alert_data);
        unset($processed);
        $this->load->_ci_cached_vars = array();
      }
    }

    if ($prepare_for_eloqua) {
      require_once(APPPATH.'third_party/email/email_functions.php');

      foreach ($eloqua_emails as $email) {
        $result = createHTMLEmail($email['name'], $email['subject'], $email['group'], $email['html'], $email['from']);
        if (ENVIRONMENT == "development") {
          $result = createHTMLEmail('SCI-TEST '.$email['name'], $email['subject'], 59, $email['html'], $email['from']);
        }
        $eloqua_results = json_encode(array($email['name'], $email['subject'], $email['group'], 'html body excluded', $email['from']));
      }
    }  
    print json_encode(array('files' => $files_saved, 'redirect' => $redirect, 'results' => $eloqua_results));
  }


  /**
   * _getArticleXML
   *		create a collection of xml data for this week's issue, from the given XMLPath
   *		
   * @param mixed $journal
   * @param mixed $jname
   * @param mixed $issueData
   * @param mixed $alert
   * 
   */
  function _getArticleXML($journal, $jname, $issueData, $alert){
    $collection = array();
    foreach ($journal['data_source'] as $data_source) {
      //check to see if the data source from the journal is in our data source for the alert.
      if($data_source['name'] == $this->alerts[$jname][$alert]['data_source']){

        // set the article's base path
        $base_url = (!empty($this->alerts[$jname][$alert]['article_base_path'])
          ? $this->alerts[$jname][$alert]['article_base_path'] : $journal['article_base_path']);

        //Look up the identifier for this collection
        $name = $data_source['name'];

        $articles = getDirectoryList(
          getXMLPath($jname, $issueData, $this->journals) . $data_source['path'],
          $journal['ext'],
          $journal['ignore_list']
        );
  //var_dump($articles);
        // online only materials should display at the top of the list
        // create two temp arrays to store the article data based on 
        // elocation-id then merge them 
        $online_only = array();
        $all_other = array();
        foreach ($articles as $articleFile) {
          $article = $this->_prepareArticleForCollection($articleFile, $base_url);
          if (!empty($article['elocation-id'])) {
            $online_only [] = $article;
          }
          else {
            $all_other[] = $article;
          }
        }
        $prepared_articles = array_merge($online_only, $all_other);
        foreach ($prepared_articles as $article) {
          $collection[$name][$article['articleType']][] = $article;
        }
      }
    }		

    return $collection;
  }


  /**
   * _getInclude
   *		takes the an item from the content array from the alert_configs and returns the content for that item
   *		
   * @param mixed $journal
   * 
   */
  function _getInclude($contArray, $alert_data){

    switch ($contArray['type']) {    
    case 'var':
      return $alert_data[$contArray['location']];
      break;

    case 'file':
      return file_get_contents($contArray['location'], FILE_USE_INCLUDE_PATH);
      break;

    case 'view':
      return  $this->load->view($contArray['location'], $alert_data, TRUE);
      break;

    case 'feed':
      $rss_data = $this->_getRssFeedData($contArray['location'], $contArray['article_count']);
      return $this->load->view($contArray['view'], array('news_headlines' => $rss_data), TRUE);
      break;
      //TODO: add a default condition that throws an error--indicates misconfiguration of the alert
    default:
      return void;
      break;
    }

    //if we make it here, something went horribly wrong.
    return void;

  }

  /**
   * _orderXML
   *    takes the order array and populates it with article xml and any includes that are needed. 
   *
   * @param mixed $order
   * @param mixed $targetPath
   */
  private function _orderXML($order, $articleCollection, $adPos, $teaser_order, $notePos){
    $ad_storage = array();
    foreach($order as $section=>$subsection){
      foreach($subsection as $articleType=>$content){
        $order[$section][$articleType]['content'] = $articleCollection[$articleType];

        //while seting the order, now is a good time to convert the jason author information into a human readable format.
        //function is located in common_helper
        if (is_array($order[$section][$articleType]['content'])) {
          foreach ($order[$section][$articleType]['content'] as &$entry){
            $entry['author'] = decodeAuthors($entry['author']);
            $entry['overline'] = smartcase($entry['overline'],'strtoupper'); //strtoupper, ucwords
            $entry['teaser'] = get_teaser($entry, $teaser_order);
          }
        }

        //check to see if the section is empty, if it is we'll want the ad to show up in the next avalible spot.
        if(! empty($order[$section][$articleType]['content'])){
          if(isset($adPos[$section]) ){
            $order[$section][$articleType]['content']['ad'] = $adPos[$section];

            //we don't have an ad for this section, but, if we have an ad in storage, we'll want to pop that out into this section.
          }elseif(! empty($ad_storage)){
            $order[$section][$articleType]['content']['ad'] = array_pop($ad_storage);
          }

          if(isset($notePos[$section])){
            $order[$section][$articleType]['content']['ad'] = $notePos[$section];
          }
          //if we've made it to here, that means that the sections empty, so we'll set the ad in storage and retrieve it later. 
        }elseif(! empty($adPos[$section])){
          $ad_storage[] = $adPos[$section];
        }

      }
    }	 

    return $order;

  }


  /**
   * _saveOutput
   *    Saves the output into the target directory
   *
   * @param mixed $processed
   * @param mixed $targetPath
   */
  function _saveOutput($processed, $targetPath){
    $inc=fopen($targetPath,'w+');
    fputs($inc,$processed);
    fclose($inc);

    //todo: put in some error checking.

  }


  /**
   * _removeUnusedSections
   *    removes any unused sections and article types from the order variable. Returns the clean order array.
   *
   * @param mixed $order
   */
  function _removeUnusedSections($order){
    foreach($order as $sectionKey => $section){
      //loop through each article type {sections can have multiple article types, so we need to check them all}
      foreach ($section as $articleKey=>$articleTypes){
        if(empty($articleTypes['content'])){
          unset ($order[$sectionKey][$articleKey]);
        }
      }
    }

    //clean out sections that are not in use
    foreach($order as $sectionKey => $section){
      if(empty($section)){
        unset ($order[$sectionKey]);
      }
    }

    return $order;
  }


  /**
   * _getRssFeedData
   *
   * @param mixed $feed
   * @param mixed $article_count
   * @access public
   * @return newsheadlines -- attributes for rss view
   */
  function _getRssFeedData($feed, $article_count) {
    $this->load->library('rssparser', array($this, '_parseRssMedia'));
    $this->rssparser->set_feed_url($feed);
    if (!$article_count > 0) { $article_count=5; }

    $rss = $this->rssparser->getFeed($article_count);        // collect x items from feed
    $news_headlines = array();
    foreach ($rss as $item) {
      array_push($news_headlines, array(
        'title'       => $item['title'], 
        'link'        => $item['link'],
        'date'        => date(SCI_ISSUE_DATE,strtotime($item['pubDate'])),
        'description'   => $item['description'], 
        'thumbnail'   => $item['thumbnail'], 
        'category'    => smartcase($item['category'],'strtoupper'), 
      ));
    }
    return $news_headlines;
  }


  /**
   * _parseRssMedia
   *    the rssparser library doesn't have a built-in way to parse namespaced elements, so 
   *    we have to resort to using a callback that calls SimpleXmlElement to get the media
   *    tag and send it back to rssparser
   *
   * @param mixed $data
   * @param mixed $item
   * @access protected
   * @return void
   */
  function _parseRssMedia($data, $item) {
    foreach ($item->xpath("media:thumbnail/@url") as $media) {
      $data['thumbnail'] = (string)$media->url;
    }
    foreach ($item->xpath("media:category") as $category) {
      $data['category'] = (string)$category;
    }
    return $data;
  }


  /**
   * _editorialBookends
   *    responsible for "bookending" xml body content with editorial includes,
   *    this will ensure that editorial call outs can be put in imediately before the sections, or after
   *
   * @param mixed $order
   * @param mixed $editorial
   */
  function _editorialBookends($order, $editorial){
    foreach($editorial as $section=>$file){
      if($section == 'front'){
        array_unshift($order, array('top' => array('display'=>'full','content'=> array('ad'=>$file) ) ) );
      }elseif($section == 'end'){
        $order[] = array('top' => array('display'=>'full','content'=> array('ad'=>$file) ) );
      }
    }

    return $order;
  }

  /**
   * _prepareArticleForCollection
   *
   * @param mixed $articleFile
   * @param mixed $base_url
   * @access protected
   * @return void
   */
  function _prepareArticleForCollection($articleFile, $base_url) {
    $article = $this->Issue_model->getArticle($articleFile);
    // use article_base_path override if it exists
    $article['url'] = buildArticleLinks($base_url, $article);
    // skip articles missing appropriate syndication
    if ($article['syndication'] == 'none') {
      return false;
    }
    return $article;
  }

}
