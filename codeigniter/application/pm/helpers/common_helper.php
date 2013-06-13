<?php 
//helper to find common data for issues.
//**
//** @PARAM: $pubDay - day of the week as a STRING
//** @PARAM: $datafile - path to data file of look-up data for issue (constant)
//** @PARAM: $date - (optional) date in future or past to look for other issues. Default is next planned issue to publish.
//**
function getIssueDataByDate($pubDay, $dataFile, $date="") {

  $nextPubDay='';

  //check to see if $date is set, if it isn't, then we will assume this is for the next pubDay from today.
  //If  it is set, we will find the next pubDay following the $date specified.
  if($date !=""){
    if (date('l', strtotime($date)) == $pubDay){
      $nextPubDay = date('m/d/y', strtotime($date));
    }else{
      $nextPubDay = date('m/d/y', strtotime( "next ".$pubDay, strtotime($date)));
    }
  }else{
    if (date('l') == $pubDay){
      $nextPubDay = date('m/d/y');
    }else{
      $nextPubDay = date('m/d/y', strtotime( "next ".$pubDay));
    }
  }

  //get the lines from the dataFile.
  if (file_exists($dataFile)) {
    $lines = file($dataFile);
  } else {
    //print ("** Cannot find the issue data file **");
    return array('ISSUE_DATE' => date(SCI_ISSUE_DATE,time()));
  }

  foreach($lines as $line) {  
    //break data item from file into components
    list($tmpShortDate, $tmpIssue, $tmpVol) = explode("|",$line);

    //if we find a match assign the data
    if ($nextPubDay == $tmpShortDate) {
      break;
    }
  }

  $dates = getIssueDates($nextPubDay);
  $lastIssue = $dates[0];
  $nextIssue = $dates[1];
  $issueFullMonth = $dates[2];
  $lastIssueFullMonth = $dates[3];

  $dateArray = explode("/",$nextIssue);
  $issueMonth = $dateArray[0];
  $issueDay = $dateArray[1];
  $issueYear = $dateArray[2];
  $issueFullYear = "20".$dateArray[2];
  $issueShortDay = floor($dateArray[1]);
  $issueShortMonth = floor($dateArray[0]);

  $dateArray = explode("/", $lastIssue);
  $pissueMonth = $dateArray[0];
  $pissueDay = $dateArray[1];
  $pissueYear = $dateArray[2];
  $pissueShortDay = floor($dateArray[1]);  
  $pissueShortMonth = floor($dateArray[0]);

  //return array of all data
  return (array(
    "VOLUME_NUM"=>trim($tmpVol), 
    "ISSUE_NUM"=>trim($tmpIssue),
    "ISSUE_DATE"=>$nextIssue, 
    "ISSUE_DATE_SCIENCE"=>date(SCI_ISSUE_DATE,strtotime($nextIssue)), 
    "ISSUE_YEAR"=>$issueYear, 
    "ISSUE_MONTH"=>$issueMonth, 
    "ISSUE_DAY"=>$issueDay, 
    "ISSUE_FULL_YEAR"=>$issueFullYear, 
    "ISSUE_FULL_MONTH"=>$issueFullMonth, 
    "ISSUE_SHORT_DAY"=>$issueShortDay,
    "ISSUE_SHORT_MONTH"=>$issueShortMonth,     
    "ISSUE_SHORT_MONTH"=>$issueShortMonth,     
    "PREVIOUS_ISSUE_DATE" => $lastIssue, 
    "PREVIOUS_ISSUE_YEAR"=>$pissueYear,
    "PREVIOUS_ISSUE_MONTH"=>$pissueMonth, 
    "PREVIOUS_ISSUE_DAY"=>$pissueDay, 
    "PREVIOUS_ISSUE_FULL_MONTH" => $lastIssueFullMonth ,  
    "PREVIOUS_ISSUE_SHORT_DAY"=>$pissueShortDay,
    "PREVIOUS_ISSUE_SHORT_MONTH"=>$pissueShortMonth,
    "TODAY_SCIENCE"=>date(SCI_ISSUE_DATE,time()),
  ));

} 

/**
 * 
 * Calculate the previous (10)  issues from the current one.
 * 
 * @author MGreen
 *
 * @param
 * @return
 *  Array of  [date]|Vol|Issue details for the previous 10 issues;
 **/
function getLastIssues($pubDay, $dataFile, $date="", $count)
{

  $nextPubDay=getNextPubDay($date, $pubDay);

  //get the lines from the dataFile.
  if (file_exists($dataFile)) {
    $lines = file($dataFile);
  } else {
    print ("getLastIssue: ** Cannot find the issue data file **");
    return;
  }

  $issues = array();

  foreach($lines as $line) {  
    array_push($issues, $line);

    if (count($issues) > $count) {
      array_shift($issues);
    }

    //break data item from file into components
    list($tmpShortDate, $tmpIssue, $tmpVol) = explode("|",$line);

    //if we find a match assign the data
    if ($nextPubDay == $tmpShortDate) {
      break;
    }  

  }

  return $issues;

}

/**
 *  Get the Date of the next issue, after the supplied date.
 *
 * @author MGreen
 *
 * @param
 * @return
 **/
function getNextPubDay($date, $pubDay)
{
  // This will catch cases like News where publication occurs on a daily basis
  if ($pubDay == 'Today') { return date('m/d/y',time());}
  //check to see if $date is set, if it isn't, then we will assume this is for the next pubDay from today.
  //If  it is set, we will find the next pubDay following the $date specified.
  if($date !=""){
    if (date('l', strtotime($date)) == $pubDay){
      $nextPubDay = date('m/d/y', strtotime($date));
    }else{
      $nextPubDay = date('m/d/y', strtotime( "next ".$pubDay, strtotime($date)));
    }
  }else{
    if (date('l') == $pubDay){
      $nextPubDay = date('m/d/y');
    }else{
      $nextPubDay = date('m/d/y', strtotime( "next ".$pubDay));
    }
  }

  return $nextPubDay;

}

function getIssueDates($issueDate) {
  $issueDateArray = explode("/",$issueDate);
  $lastIssueDay = $issueDateArray[1];

  $nextFriday = date("m/d/y" ,mktime(0, 0, 0, $issueDateArray[0] , $issueDateArray[1], $issueDateArray[2]));
  $fullMonth  = date("F" ,strtotime($nextFriday));

  $lastFriday = date("m/d/y" ,mktime(0, 0, 0, $issueDateArray[0] , $issueDateArray[1]-7, $issueDateArray[2]));
  $lastFullMonth  = date("F" ,strtotime($lastFriday));

  return (array($lastFriday, $nextFriday, $fullMonth, $lastFullMonth));

}


/**
 * decodeAuthors
 *
 * @param mixed $author
 * @param mixed $and_size
 *  An alternative formatting that adds 'and' between author names when there are more than 2 authors.
 * @access public
 * @return void
 */
function decodeAuthors ($author, $and_size=null){
  $authors = json_decode($author, true);
  $authorString = "";

  if (empty($and_size) || count($authors) <= 2) {
    if(sizeof($authors) == 1) {
      $authorString = concat_author($authors[0]);  
    }
    else if (sizeof($authors) == 2) {
      $authorString = concat_author($authors[0]);  
      $authorString .= " and " . concat_author($authors[1]);  
    }
    else if (sizeof($authors) > 2) {
      $authorString = concat_author($authors[0]);  
      $authorString .= " <em>et al.</em>";
    }
  }
  else {
    $i = 0;
    $max = (count($authors) > $and_size) ? $and_size -1 : count($authors) -1;
    for (; $i < $max; $i++) {
      $authorString .= concat_author($authors[$i]) . ', ' ;  
    }
      $authorString .= concat_author($authors[$i++]);  
    if (count($authors > $and_size)) {
      $authorString .= " and more ";
    }
  }


  return $authorString;  
}





function concat_author($author) {
  return $author['name']['given-names'] . " " . $author['name']['surname'];
}



/**
 *  Parse and Validate the URI for this action
 * @author MGreen
 *
 * @return string or FALSE
 **/
function parseURI()
{
  // Calling library from inside helper, see http://stackoverflow.com/a/6327423/839257
  $CI =& get_instance();
  $result = "";

  switch ($CI->uri->total_segments()) {
  case ($CI->uri->total_segments() >= 6):
    $mo = str_pad($CI->uri->segment(4,0), 2, "0", STR_PAD_LEFT);
    $day = str_pad($CI->uri->segment(5,0), 2, "0", STR_PAD_LEFT);
    $yr = substr($CI->uri->segment(6,0),-2);

    $result['date'] = $mo . "/" . $day . "/" . $yr ;
    $result['jnl'] = $CI->uri->segment(3,0); //grab the journal name from uri
    break;

  case ($CI->uri->total_segments() >= 5):
    $mo = str_pad($CI->uri->segment(3,0), 2, "0", STR_PAD_LEFT);
    $day = str_pad($CI->uri->segment(4,0), 2, "0", STR_PAD_LEFT);
    $yr = substr($CI->uri->segment(5,0),-2);

    $result['date'] = $mo . "/" . $day . "/" . $yr ;
    $result['jnl'] = 'sci'; //defult to Science
    break;

  case ($CI->uri->total_segments() >= 3):
    $result['jnl'] = $CI->uri->segment(3,0);
    break;

  default:
    $result = $CI->uri->segment_array();
    $result['jnl'] = 'sci'; 
    break;
  }

  return $result;
}

/**
 * strReplaceDates
 *
 * @param mixed $subject
 * @param mixed $common_data 
 *  from getIssueDataByDate
 * @access public
 * @return string :
 */
function strReplaceDates($str, $common_data) {
  if (! isset($common_data)) {
    return $str;
  } 
  return strtr($str, $common_data);
}

/**
 * Builds the XML path for the specified journal on the given date
 * @author MGreen
 *
 * @param $j
 *  three letter journal code
 *
 * @param $d
 *  date (mm/dd/yy)
 *
 * @param $journal
 *   journal datapath
 *   
 * @return string
 *  
 **/
function getXMLPath($j, $d, $journals) {
  $baseXMLPath = $journals[$j]['article_xml_root'];

   $trans_array = array(
      'VOLUME_NUM' => $d['VOLUME_NUM'],
      'ISSUE_NUM' => $d['ISSUE_NUM'],
      'ISSUE_FULL_YEAR' => $d['ISSUE_FULL_YEAR'],
      'ISSUE_FULL_MONTH' => $d['ISSUE_FULL_MONTH'],
      'ISSUE_MONTH' => $d['ISSUE_MONTH'],
      'ISSUE_DAY' => $d['ISSUE_DAY'],
    );

    return strtr($baseXMLPath, $trans_array);

  /*
  switch ($j) {
  case "sci":
    $issueXMLPath = $baseXMLPath . 
      $d['VOLUME_NUM'] . '/' . 
      $d['ISSUE_FULL_MONTH'] . '/' . 
      $d['ISSUE_DAY'] . ' ' . 
      $d['ISSUE_FULL_MONTH'] . '--' . 
      $d['ISSUE_NUM'] . '/';
    break;
  case "sig":
    $issueXMLPath = $baseXMLPath .
      $d['ISSUE_FULL_YEAR'] ."_".
      $d['ISSUE_MONTH']."_".
      $d['ISSUE_DAY']."__".
      $d['ISSUE_NUM'].'/';
    break;
  case "stm":
    $issueXMLPath = $baseXMLPath .
      $d['ISSUE_FULL_YEAR'] ."_".
      $d['ISSUE_MONTH']."_".
      $d['ISSUE_DAY']."_".
      $d['ISSUE_NUM'].'/';
    break;
  }
 */

  // return $issueXMLPath;

}





/**
 * Read all files from a directory, matching a given extension and excluding those specified in an "ignore list"
 * @author Some Bloke on the Web
 *
 * @param $directory
 *  full path to the target directory
 *
 * @param $ext
 *  extension of the files to return
 *
 * @param $ignore
 *  array of filenames that should be ignored
 *  
 * @return array of filenames, prefixed with the full directory path.
 *
 **/
function getDirectoryList ($directory, $ext = null, $ignore = null) {

  // create an array to hold directory list
  $results = array();

  // create a handler for the directory
  $handler = opendir($directory);

  // open directory and walk through the filenames
  while ($file = readdir($handler)) {
   
    // if file isn't this directory or its parent, AND it is an XML file, add it to the results
    if (!empty($ext)){
      $file_ext =  substr($file, -abs(strlen($ext)));
          
      if ($file != "." && $file != ".." && $file_ext == $ext && !strpos($file, ".swp") && !in_array($file,$ignore)) {
        $results[] = $directory . $file;
      }
    
    }else{
      $results[] = $directory . $file; 
    }

  }

  // tidy up: close the handler
  closedir($handler);

  return $results;
}

/** 
 * Flattens an array, or returns FALSE on fail. 
 * http://php.net/manual/en/function.array-values.php
 */ 
function array_flatten($array,&$vals='')
{
  foreach ($array as $key => $value) {

    if (is_array($value)) {

      array_flatten($value,$vals);

    }else{

      $vals[] = $value; 
    }
  }

  return $vals;
}


/**
 *  Returns a teaser from XML, if teaser is available.
 * */
/**
 * get_teaser
 *
 * @param mixed $article
 *  All metadata for a given article
 * @param mixed $order
 *  An ordered array of teaser options. 
 * @access public
 * @return void
 */
function get_teaser($article, $order)
{
  $teaser = null;
  foreach(array_reverse($order) as $option){
    if(!empty($article[$option])){
      $teaser = $article[$option];
    }
  }

  return $teaser;
}

/**
 * loadAd
 *
 * @param mixed $file
 * @param mixed $path
 * @return void
 */
function loadAd($file, $path) {
  // Add html extention if it doesn't exist
  if (! strpos($file, '.html')) {
    $file = $file . ".html";
  }
  $ad_last_modified = get_file_info($path.$file, 'date');
  $ad_last_modified = date('m/d/y', $ad_last_modified['date']);
  $ad_string = read_file($path.$file);
  return array('content' => $ad_string, 'modified' => $ad_last_modified,);
}
/**
 * saveAd
 *
 * File function for saving ads
 *
 * @param mixed $file
 * @param mixed $path
 * @param mixed $data
 * @access public
 * @return void
 */
function saveAd($file, $path, $data) {
  $old_ad = loadAd($file, $path);
  // don't overwrite if the file contents haven't changed
  if ($old_ad['content'] == $data) {
    return false;
  }
  $filename = $path.$file.".html";

  if (! write_file($filename, $data)) {
    echo "Unable to write to {$filename}";
  }
  else {
    return true;
  }
}

/**
 * buildArticleLinks
 * DEPRECATED: Use $article->get_url($variant) for future development
 *
 *@param mixed $pathTemplate
 *  A template of the journal URI. 
 *@param mixed $articleData
 */
function buildArticleLinks($pathTemplate, $articleData){
      if(! empty($articleData['subPageNumeric'])){
        $articleData['page'] .= ".".$articleData[subPageNumeric];
      }
    
      $trans_array = array(
        'VOLUME_NUM' => $articleData['volume'], 
        'ISSUE_NUM' => $articleData['issue'], 
        'PAGE' => $articleData['page'], 
        'DOI' => $articleData['doi'], 
        'VARIANT' => getNextVariant($articleData),
      );
  $link = strtr($pathTemplate, $trans_array);

  return $link;
}

/**
 * findCurrentCover 
 *
 *@param mixed $common_data
 *@param mixed $publication
 *@param mixed $coverBase
 *@param mixed $forceCover forces the coverlink to be displayed, will ignore temporary link
 */
function findCurrentCover($common_data, $publication, $coverBase, $forceCover = false){
  $default_covers = array();
  $todays_date = date("m/d/Y");
  $coverUrl = base_url().'application/pm/css/img/'.$publication.'-temp-cover.jpeg';
  if(!empty($coverBase) && strtotime($todays_date) >= strtotime($common_data['ISSUE_DATE']) || $forceCover == true){
    $coverUrl=strReplaceDates($coverBase, $common_data);
  }
  return $coverUrl;
}

/**
 * buildEditableAlertTabs
 *
 * @param mixed $alert_array
 * @param mixed $output_path
 * @access public
 * @return output
 */
function buildEditableAlertTabs($alert_array, $journals, $output_path) {
  $CI =& get_instance();
  $all_ads_by_journal = array();
  foreach($alert_array as $journal => $arr) {
    $all_ads_by_journal[$journal] = array_flatten($arr);
  }
  $tab_counter = 0;
  // TODO: Save function for editorNote should not be in adMaster controller
  $output .= form_open('adMaster/save', array('id' => 'admasterform',));  
  foreach($all_ads_by_journal as $journal => $lines) {
    // initialize textareas and checkboxes for each journal set
    $textareas = array();
    $checkboxes = array();
    // for each line of ads, display the label and produce the checkbox
    foreach ($lines as $line) {
      $input_element_id = str_replace('.html','',$line);
      $label = ucwords(str_replace(array('.html','_','-'), ' ', $line));
      $ad_info = loadAd($line, $output_path);
      $ad_html = $ad_info['content'];
      $ad_modified = $ad_info['modified'];
      array_push($checkboxes, $CI->load->view('shared/checkbox', array('label' => $label,'id' => $input_element_id, 'value' => NULL), TRUE ) );
      array_push($textareas,  $CI->load->view('shared/html_edit', array('label' => $label, 'id' => $input_element_id, 'content' => $ad_html, 'modified' => $ad_modified, ), TRUE) );
    }
    $tab_attributes = array(
      'journal' => $journals[$journal]['name'], 
      'lines' => $checkboxes, 
      'tab' => "tab".++$tab_counter,
      'html_preview' => $textareas,
    );
    $tabcontents .= $CI->load->view('shared/tab_set',$tab_attributes, TRUE); 
  }
  $output .= $CI->load->view('shared/tab_contents',array('tabcontents' => $tabcontents), TRUE);
  $output .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Save Changes','type'=>'submit'),'');
  $output .= form_close();
  return $output;
}


/**
 * smartcase
 *
 * @param mixed $str
 *  A sentence or word to be transformed
 * @param string $transform
 *  Name of a function that will transform the non-special words
 * @access public
 * @return string
 *  The transformed string
 */
function smartcase($str, $transform = null) {
  $words = explode(' ',$str);
  $transformed_words = array();
  // if there are XML entities, do not transform string
  foreach ($words as $word) {
    if (preg_match('/(?:&#\d{3,4}|[0-9]+|\w{1})/',$word)) {
      $transformed_words[] = $word;
      continue;
    }
    // Convert the word into an array of characters and process
    $word_arr = str_split($word);
    while ($chr = current($word_arr)) {
      if (!$next = next($word_arr)) { break; }
      if ($chr == strtoupper($chr) && $next == strtoupper($next)) {
        $transformed_words[] = $word;
        break; // return to foreach loop
      } 
      else if ($chr == strtolower($chr) && $next == strtoupper($next)) {
        $transformed_words[] = $word;
        break; // return to foreach loop
      }
      else if (isset($transform) && function_exists($transform)) {
        $transformed_words[] = call_user_func($transform, $word); 
        break; // return to foreach loop
      }
      else {
        $transformed_words[] = $word;
        break;
      }
    }
  }
  return join(' ', $transformed_words);
}
/**
 * getNextVariant
 *  returns the "next access level up" for article
 * @param mixed $article
 *  article metadat
 * @access public
 * @return the extention for the next higher level of article on website
 */
function getNextVariant($article) {
  $CI =& get_instance();
  // next access level from pm_config
  $access_levels = $CI->config->config['next_access_level'];
  // full alert configuration from config in CI memory
  $alerts = $CI->config->config['alerts'];
  // Information about this alert from URI segment
  $journal = $CI->uri->segment(3);
  $alert = $CI->uri->segment(4);
  // list of teaser options from pm_config
  $teaser_options = $CI->config->config['teaser_options'];
  // teaser option for the current journal and alert
  $teaser_option = $alerts[$journal][$alert]['teaser_option'];
  // array item from teaser options for this journal and alert
  $teaser_arr = $teaser_options[$teaser_option]; 
  // default extention
  $extention = "abstract"; 

  // looping through teasers from most restrictive to least to find 
  // the appropriate access level given a teaser option
  foreach(array_reverse($teaser_arr) as $to){
    if(!empty($article[$to])){
      $extention = $access_levels[$to];
    }
  }

  return $extention;
}

 /**
  * _view_exists
  *
  * Checks if view file exists
  *
  * @param mixed $view
  * @access protected
  * @return boolean
  */
function template_exists($view) {
  return is_file(base_url()."application/pm/views/templates/" . $view . ".php");
}

function node_lookup($doc, $element, $attr=null) {

    $data = ''; // default

    //Get node list
    $nl = $doc->getElementsByTagName($element)->item(0);

    if (gettype($nl)=='object') {
      //If an attribute was specified, look for that
      if (!is_null($attr)) {
        $data = $nl->getAttribute($attr);
        //Otherwise just look for the node value
      } else {
        $data = $nl->nodeValue;
      }
    }
    return $data;
}
function get_node_innerhtml($node) {
  $innerHTML= ''; 
  $children = $node->childNodes; 
  foreach ($children as $child) { 
    $innerHTML .= $child->ownerDocument->saveXML( $child ); 
  } 
  return $innerHTML; 
}
