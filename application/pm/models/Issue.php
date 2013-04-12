<?php 
class Issue extends CI_Model {
  private $publication;
  private $id;
  private $doi_id;
  private $name;
  private $publish_date;
  private $volume_num;
  private $issue_num;
  private $cover;
  private $is_special_issue;
  private $special_issue_title;
  private $base_url;
  private $xml_path;
  private $issue_cover;
  private $data_source; // data source for current issue
  private $data_sources = array();
  private $initialized = false;

  function __construct() {
    parent::__construct();
    $this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));
    $this->load->config('pm_config');
    $this->publications = $this->config->item('journals');
    
  }
  /**
   * init 
   *
   * @param mixed $pub
   * @param mixed $date 
   *  grabs issue data for week specified, or this week if null
   * @access public
   * @return void
   */
  function init($pub, $date=null) {
    (isset($this->publications[$pub])) ?  $this->publication = $pub 
      : show_error('Publication ' . $pub . ' not defined in pm_config'); 

    $p = $this->publications[$pub];

    $this->name = $p['name'];
    $this->id = $p['id'];
    $this->doi_id = $p['doi_id'];

    // Issue Dates
    $date = (is_null($date)) ? date("Y-m-d H:i:s") : $date;  // use today if date not set
    if (date('l', strtotime($date)) == $p['publish_day'] || $p['publish_day'] == 'Today') {
      $this->publish_date = date('m/d/y', strtotime($date));
    } else {
      $this->publish_date = date('m/d/y', strtotime( "next ".$p['publish_day'], strtotime($date)));
    }

    // stop initialization here for news
    if ($this->publication == 'news') { 
      $this->initialized = true;
      return;
    } 
    
    $this->base_url = $p['article_base_path'];
    $this->issue_cover = $p['cover_base'];
    $this->xml_path = $p['article_xml_root'];

    $this->issue_datafile = $p['data_path'];
    $this->data_sources = $p['data_source'];

    if (file_exists($this->issue_datafile)) {
      $line = preg_grep('|'.preg_quote($this->publish_date).'|', file($this->issue_datafile));
      $line = array_values($line); // reorder the array so the first element is our match
      preg_match('/.*[|](\d+)[|](\d+)/',$line[0],$matches, PREG_OFFSET_CAPTURE);
      // matches: [0] whole line [1][0] issue [2][0] volume
      $this->issue_num  = $matches[1][0];
      $this->volume_num = $matches[2][0];
    } else {
      show_error('issue data file ' . $this->issue_datafile . ' could not be found');
    }

    $this->initialized = true;

  }
  function get_publication() {
    return $this->publication;
  }
  function get_id() {
    return $this->id;
  }
  function get_doi_id() {
    return $this->doi_id;
  }
  function get_name() {
    return $this->name;
  }
  function get_publish_date() {
    return $this->publish_date;
  }
  function get_volume_num() {
    if (is_null($this->volume_num)) { return false; }
    return $this->volume_num;
  }
  function get_issue_num() {
    if (is_null($this->issue_num)) { return false; }
    return $this->issue_num;
  }
  function get_issue_cover() {
     $trans_array = array(
      'VOLUME_NUM' => $this->volume_num,
      'ISSUE_NUM' => $this->issue_num,
    );
    return strtr($this->issue_cover, $trans_array);
  }
  function get_is_special_issue() {
    if (is_null($this->is_special_issue)) { return false; }
    return $this->is_special_issue;
  }
  function get_special_issue_title() {
    if (is_null($this->is_special_issue)) { return false; }
    return $this->special_issue_title;
  }
  function get_base_url() {
    return $this->base_url;
  }
  /**
   * get_xml_path
   *
   * @param mixed $name
   * @access public
   * @return void
   */
  function get_xml_path($name) {
    if (is_null($this->xml_path)) { 
      return false;
    }

    $trans_array = array(
      'VOLUME_NUM' => $this->volume_num,
      'ISSUE_NUM' => $this->issue_num,
      'ISSUE_FULL_YEAR' => date('Y', strtotime($this->publish_date)),
      'ISSUE_FULL_MONTH' => date('F', strtotime($this->publish_date)),
      'ISSUE_MONTH' => date('m', strtotime($this->publish_date)),
      'ISSUE_DAY' => date('d', strtotime($this->publish_date)),
    );


    $path = null;
    foreach ($this->data_sources as $ds) {
      if ($ds['name'] == $name) { $path = $ds['path']; }
    }
    if (is_null($path)) { 
      show_error('Path for datasource ' . $ds . ' not found in pm_config');
    } 
	
	  var_dump(strtr($path, $trans_array));
    return strtr($this->xml_path . $path, $trans_array);
  }
  

  
  /**
   * get_articles
   *
   * @param mixed $name
   *   Name of the article type, ie., Articles, ScienceXpress
   * @access public
   * @return array of Article
   */
  function get_articles($name) {
    $this->data_source = $name;
    $articles = array();
    $article_list = $this->get_article_list($name);
    foreach ($article_list as $article) {
      $articles[] = $this->new_article($article);
    }
    return $articles;
  }
  /**
   * get_article_list
   *
   * @param mixed $name
   * @access public
   * @return void
   */
  function get_article_list($name) {
    $files = getDirectoryList($this->get_xml_path($name));
    $finfo = finfo_open();
    $xml_files = array();
    foreach ($files as $file) {
      $mimetype = finfo_file($finfo, $file, FILEINFO_MIME);
      if ($mimetype == 'application/xml; charset=us-ascii') {
        $xml_files[] = $file;
      }
    }
    return $xml_files;
  }
  /**
   * get_data_source
   *
   * @access public
   * @return void
   */
  function get_data_source() {
    return $this->data_source;
  }
  /**
   * new_article
   *
   * @param mixed $file
   * @access public
   * @return void
   */
  function new_article($file) {
    $A = new Article();
    $A->init($file);
    $A->set_publication($this->publication);
    $A->set_volume_num($this->volume_num);
    $A->set_issue_num($this->issue_num);
    $A->set_publisher_doi($this->doi_id);
    $A->set_base_url($this->base_url);
    return $A;
  }
  /**
   * sort_by_authors
   *
   * @param mixed $arr_array
   * @access public
   * @return void
   */
  function sort_by_authors(&$arr_array) {
    function authors($a, $b) {
      $auth_a = json_decode($a->get_authors(), true);
      $auth_a = $auth_a[0]['name']['surname'];
      $auth_b = json_decode($b->get_authors(), true);
      $auth_b = $auth_b[0]['name']['surname'];
      if ($auth_a == $auth_b) {
        return 0;
      }
      return ($auth_a < $auth_b) ? -1 : 1;
    }
    usort($arr_array, "authors");
  }
  /**
   * sort_by_article_type
   *  sorts by article type, then page number, then sequence if necessary
   *
   * @param mixed $arr_array
   * @access public
   * @return void
   */
  function sort_by_article_type(&$arr_array) {
    function article_type($a, $b) {
      if ($a->get_article_type() == $b->get_article_type()) {
        if ($a->get_fpage() == $b->get_fpage()) {
          if ($a->get_seq_alpha() == $b->get_seq_alpha()) {
            return 0;
          } else {
            return ($a->get_seq_alpha() < $b->get_seq_alpha()) ? -1 : 1;
          }
        }
        return ($a->get_fpage() < $b->get_fpage()) ? -1 : 1;
      }
      return ($a->get_article_type() < $b->get_article_type()) ? -1 : 1;
    }
    usort($arr_array, "article_type");
  }
  /**
   * sort_by_title
   *
   * @param mixed $arr_array
   * @access public
   * @return void
   */
  function sort_by_title(&$arr_array) {
    function title($a, $b) {
      if ($a->get_title() == $b->get_title()) {
        return 0;
      }
      return ($a->get_title() < $b->get_title()) ? -1 : 1;
    }
    usort($arr_array, "title");
  }
  /**
   * sort_by_fpage
   *
   * @param mixed $arr_array
   * @access public
   * @return void
   */
  function sort_by_fpage(&$arr_array) {
    function fpage($a, $b) {
      if ($a->get_fpage() == $b->get_fpage()) {
        if ($a->get_seq_alpha() == $b->get_seq_alpha()) {
          return 0;
        } else {
          return ($a->get_seq_alpha() < $b->get_seq_alpha()) ? -1 : 1;
        }
      }
      return ($a->get_fpage() < $b->get_fpage()) ? -1 : 1;
    }

    usort($arr_array, "fpage");
  }
  /**
   * group_articles_by_article_type
   *
   * @param mixed $arr_array
   * @param mixed $type
   * @access public
   * @return void
   */
  function group_articles_by_article_type($arr_array, $type) {
    $articles = array();

    return $articles;
  }
  /**
   * filter_articles
   *
   * @param mixed $el - must correspond to one of the get_ functions
   * @param mixed $regex
   * @access public
   * @return void
   */
  function filter_articles(&$arr_array, $el, $pattern) {
    $articles = array(); 
    foreach ($arr_array as $article) {
      $func = "get_" . $el;
      //@TODO add better handling of exceptions here
      $haystack = $article->{$func}();
      if (empty($haystack)) { 
        break;
      }
      if ( stristr($haystack, $pattern) ) {
        $articles[] = $article;
      }
    }
    $arr_array = $articles;
  }
}
