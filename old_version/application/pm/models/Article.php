<?php
class Article {
  private $Issue;
  private $publication;
  private $volume_num; // set by Issue
  private $issue_num; // set by Issue
  private $title;
  private $authors = null;
  private $article_type;
  private $heading;
  private $overline;
  private $fields = array();
  private $pub_year;
  private $pub_month;
  private $pub_dates = array();
  private $fpage;
  private $lpage;
  private $seq;
  private $elocation_id;
  private $pub_doi_id; // set by Issue
  private $doi;
  private $base_url;
  private $url;
  private $editor;
  private $syndication = true;
  private $is_special_issue = false;
  private $abstracts = array();
  private $figures = array();
  private $sections = array(); // body text

  private $as_html;

  function __construct() {
    // uses functions in common_helper, but should be loaded through Issue already
    require_once('application/pm/helpers/common_helper.php');
  }
  /**
   * init
   *  Should be called from Issue->new_article($filename) because Issue will supply some of the values
   *  like issue_num, vol_num, url, and other values available at the issue level, inc. a reference
   *  back to itself
   *
   * @param mixed $file
   * @param mixed $issue
   * @access public
   * @return void
   */
  function init($file) {
    $doc = new DOMDocument('1.0', 'utf-8');
    @$doc->load($file, LIBXML_DTDLOAD|LIBXML_DTDATTR);

    $xpath = new DOMXPath($doc);
    $xsl = new DOMDocument;
    $xsl->load('application/pm/xsl/simple.xsl');

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $this->as_html = $proc->transformToXML($doc);

    // issue-model uses replaceNLMmarkup function, but should probably do a transform if possible
    // $this->title = node_lookup($doc,'article-title');
    $title_nodes = $doc->getElementsByTagName('article-title');
    $this->title = get_node_innerhtml($title_nodes->item(0));
    $pub_id = $xpath->query("/article/front/article-meta/article-id[@pub-id-type='publisher-id']");
    $this->pub_id =  ($pub_id->length > 0) ? $pub_id->item(0)->nodeValue : null;
    $doi = $xpath->query("/article/front/article-meta/article-id[@pub-id-type='doi']");
    $this->doi =  ($doi->length > 0) ? $doi->item(0)->nodeValue : null;
    

    $this->fpage = node_lookup($doc,'fpage');
    $this->seq = node_lookup($doc, 'fpage', 'seq');
    $this->lpage = node_lookup($doc,'lpage');
    $this->elocation_id = node_lookup($doc, 'elocation-id');

    $syndication =  $xpath->query("/article/front/article-meta/custom-meta-wrap/custom-meta/meta-value[preceding-sibling::meta-name='syndication']");
    $this->syndication = ($syndication->length > 0) ? false : true;
    $editor =  $xpath->query("/article/front/article-meta/custom-meta-wrap/custom-meta/meta-value[preceding-sibling::meta-name='Editor']");
    $this->editor = ($editor->length > 0) ? $editor->item(0)->nodeValue : null;

    $pubdates = $doc->getElementsByTagName('pub-date');
    foreach ($pubdates as $pubdate) {
      $pubtype = node_lookup($pubdate->parentNode, 'pub-date', 'pub-type');
      $pubyear = node_lookup($pubdate, 'year');
      $pubmonth = node_lookup($pubdate, 'month');
      $pubday = node_lookup($pubdate, 'day');
      $this->pub_dates[$pubtype] = date(SCI_ISSUE_DATE, strtotime($pubmonth."/".$pubday."/".$pubyear));
    }

    $abstracts = $doc->getElementsByTagName('abstract');
    foreach ($abstracts as $abstract) {
      $abstype = $abstract->getAttribute('abstract-type');
      $abstype = (($abstype)) ? $abstype : true;
      //$title_nodes = $doc->getElementsByTagName('article-title');
      //$this->title = get_node_innerhtml($title_nodes->item(0));
      $abs = get_node_innerhtml($abstract);
      $this->abstracts[$abstype] = $abs;
    }

    $subgroups = $doc->getElementsByTagName('subj-group');
    if ($subgroups) {
      foreach($subgroups as $subgroup) {
        $type = (strtolower(trim($subgroup->getAttribute('subj-group-type'))));
        $value = node_lookup($subgroup, 'subject');

        switch($type) {
        case 'article-type':
          $this->article_type = $value;
          if (strrpos($value, 'Special Issue')!== false) {
            $this->is_special_issue = true;
          }
          break;
        case 'heading':
          $this->heading = $value;
          break;
        case 'overline':
          $this->overline = $value;
          break;						
        case 'field':
          $this->fields[] = $value;
          break;					
        }
      }
    }

    $author_metadata = array();
    $authors = $doc->getElementsByTagName('contrib');
    foreach($authors as $author) {
      $author_metadata[] = array(
        'type' => $author->getAttribute('contrib-type'),
        'name' => array(
          'surname' => node_lookup($author, 'surname'),
          'given-names' => node_lookup($author, 'given-names')
        )
      );
    }
    if (count($author_metadata) > 0) {
      $this->authors = json_encode($author_metadata);
    } 

    
  }

  function set_publication($pub) {
    return $this->publication = $pub;
  }
  function get_publication() {
    return $this->publication;
  }
  function set_volume_num($volume) {
    return $this->volume_num = $volume;
  }
  function get_volume_num() {
    return $this->volume_num;
  }
  function set_issue_num($issue) {
    return $this->issue_num = $issue;
  }
  function get_issue_num() {
    return $this->issue_num;
  }
  function get_title() {
    // technically, this is a bit hacky since other NLM markup might appear
    // in the title. This should cover almost all the cases. When it fails
    // we'll need to figure out how to apply our transform rules to only
    // the title, which will also mean a change to the XSL. This hack has
    // been used in production in the issue_model for months, so it should
    // be safe, but YMMV. 
    return str_replace('italic>', 'em>', $this->title);
  }
  /**
   * get_authors
   *
   * @access public
   * @return json_encoded array of authors (for compatibility with earlier code)
   */
  function get_authors() {
    return $this->authors;
  }
  /**
   * get_authors_toString
   *
   * @access public
   * @return void
   */
  function get_authors_toString() {
    return decodeAuthors($this->authors);
  }
  function get_article_type() {
    return $this->article_type;
  }
  function get_heading() {
    return $this->heading;
  }
  function get_overline() {
    return $this->overline;
  }
  function get_fields() {
    return $this->fields;
  }
  function get_pub_year() {
    return $this->pub_year;
  }
  function get_pub_month() {
    return $this->pub_month;
  }
  /**
   * get_ppub_date
   *
   * @access public
   * @return void
   */
  function get_ppub_date() {
    if (isset($this->pub_dates['ppub'])) {
      return $this->pub_dates['ppub'];
    } else {
      return false;
    }
  }
  /**
   * get_epub_date
   *
   * @access public
   * @return void
   */
  function get_epub_date() {
    if (isset($this->pub_dates['epub'])) {
      return $this->pub_dates['epub'];
    } else {
      return false;
    }
  }
  /**
   * get_pub_date
   *  returns ppub if possible, otherwise epub
   *
   * @access public
   * @return void
   */
  function get_pub_date() {
    // prefer print over electronic
    if (isset($this->pub_dates['ppub'])) {
      return $this->pub_dates['ppub'];
    } elseif (isset($this->pub_dates['epub'])) {
      return $this->pub_dates['epub'];
    }
    else { 
      return false;
    }
  }
  function get_first_page() {
    $seq = (empty($this->seq)) ? null : "-" . $this->seq;
    return $this->fpage . $seq;
  }
  function get_fpage() {
    return $this->fpage;
  }
  function get_lpage() {
    return $this->lpage;
  }
  function get_seq_alpha() {
    return (empty($this->seq)) ? false : $this->seq;
  }
  function get_seq_num() {
    return (empty($this->seq)) ? false : ord($this->seq) - 96;
  }
  function get_elocation_id() {
    return $this->elocation_id;
  }
  function set_publisher_doi($pub_doi_id) {
    $this->pub_doi_id = $pub_doi_id;
  }
  function get_doi() {
    if (empty($this->publisher_id)) {
      return join('.', array($this->pub_doi_id, $this->make_publisher_id()));
    }
    return $this->doi;
  }
  /**
   * get_publisher_id
   *
   * @access public
   * @return void
   */
  function get_publisher_id() {
    if (empty($this->publisher_id)) {
      return $this->make_publisher_id();
    }
    return $this->publisher_id;
  }
  /**
   * make_publisher_id
   *  Some articles (i.e., news) have no publisher ids in the XML. This generates one on the fly
   *
   * @access public
   * @return void
   */
  function make_publisher_id() {
    return "{$this->volume_num}.{$this->issue_num}.{$this->get_first_page()}";
  }
  function set_base_url($base_url) {
    $this->base_url = $base_url;
  }
  /**
   * get_url
   *
   * @param mixed $variant
   * @access public
   * @return void
   */
  function get_url($variant = null) {
    $url_page = (empty($this->seq)) ? $this->fpage : join('.',array($this->fpage,$this->get_seq_num()));
    $variant = (empty($variant)) ? null : $variant;
     $trans_array = array(
      'VOLUME_NUM' => $this->volume_num,
      'ISSUE_NUM' => $this->issue_num,
      'PAGE' => $url_page,
      'VARIANT' => $variant,
    );
    $url = strtr($this->base_url, $trans_array);
     // fix to clear trailing period if no variant specified
    if (substr($url, -1) === ".") {
      $url = mb_substr($url, 0, strlen($url) - 1);
    }

    return $url;
  }
  function get_editor() {
    return $this->editor;
  }
  function get_syndication() {
    return $this->syndication;
  }
  function get_isSpecialIssue() {
    return $this->isSpecialIssue;
  }
  /**
   * get_abstracts
   *  get_abstracts() returns array of all abstracts
   *  get_abstracts(true) returns the 'main' abstract
   *  get_abstract('type') returns the abstract matching 'type'
   * @param mixed $type
   * @access public
   * @return void
   */
  function get_abstracts($type=null) {
    if (!empty($type) && isset($this->abstracts[$type])) {
      return $this->abstracts[$type];
    } elseif (!empty($type) && isset($this->abstracts[0])) {
      return $this->abstracts[0];
    } elseif (!empty($type)) { // type set, but no abstract found
      return false;
    }
    return $this->abstracts;
  }
  function get_figures() {
    return $this->figures;
  }
  function get_sections() {
    return $this->sections;
  }
  function as_html() {
    return $this->as_html;
  }

}
?>
