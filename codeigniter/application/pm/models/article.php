<?php
class Article {
  private $Issue;
  private $publication;
  private $volume_num; // set by Issue
  private $issue_num; // set by Issue
  private $title;
  private $section;
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
  private $publisher_id;
  private $doi;
  private $base_url;
  private $url;
  private $editor;
  private $syndication = true;
  private $is_special_issue = false;
  private $abstracts = array();
  private $summary;
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

    // issue-model uses replaceNLMmarkup function, but should probably do a transform if possible
    // $this->title = node_lookup($doc,'article-title');
    $title_nodes = $doc->getElementsByTagName('article-title');
    $this->title = get_node_innerhtml($title_nodes->item(0));

    $pub_id = $xpath->query("/article/front/article-meta/article-id[@pub-id-type='publisher-id']");
    $this->publisher_id =  ($pub_id->length > 0) ? $pub_id->item(0)->nodeValue : null;
    $journal_id = $xpath->query("/article/front/journal-meta/journal-id[@journal-id-type='publisher-id']");
    $this->journal_id =  ($journal_id->length > 0) ? $journal_id->item(0)->nodeValue : null;

    $doi = $xpath->query("/article/front/article-meta/article-id[@pub-id-type='doi']");	
    $this->doi =  ($doi->length > 0) ? $doi->item(0)->nodeValue : null;


    $section = $xpath->query("/article/front/article-meta/article-categories/subj-group[@subj-group-type='heading']");
    $this->section =   $section->item(0)->nodeValue;

    $editor =  $xpath->query("/article/front/article-meta/custom-meta-wrap/custom-meta/meta-value[preceding-sibling::meta-name='Editor']");
    $custom_meta = $xpath->query("/article/front/article-meta/custom-meta-wrap/custom-meta/meta-value");
    foreach ($custom_meta  as $value) {
      $key = strtolower($value->previousSibling->nodeValue);
      $value = $value->nodeValue;
      $this->custom_meta[$key][] = $value;
    }
    $this->editor = ($editor->length > 0) ? $editor->item(0)->nodeValue : null;

    $this->elocation_id = node_lookup($doc, 'elocation-id');
    if (!empty($this->elocation_id)) {
      $print_abstract_page = $this->get_custom_meta('print abstract page');
      $this->fpage = $this->lpage = $print_abstract_page[0];
    } else {
      $this->fpage = node_lookup($doc,'fpage');
      $this->seq = node_lookup($doc, 'fpage', 'seq');
      $this->lpage = node_lookup($doc,'lpage');
    }

    $pubdates = $doc->getElementsByTagName('pub-date');
    foreach ($pubdates as $pubdate) {
      $pubtype = node_lookup($pubdate->parentNode, 'pub-date', 'pub-type');
      $pubyear = node_lookup($pubdate, 'year');
      $pubmonth = node_lookup($pubdate, 'month');
      $pubday = node_lookup($pubdate, 'day');
      $this->pub_dates[$pubtype] = date(SCI_ISSUE_DATE, strtotime($pubmonth."/".$pubday."/".$pubyear));
    }

    // XSLT for article body and abstracts
    $xsl = new DOMDocument;
    $xsl->load('application/pm/xsl/simple.xsl');
    $proc = new XSLTProcessor();
    $proc->setParameter('', 'publication', $this->get_journal_id());
    $proc->importStyleSheet($xsl);
    $this->as_html = $proc->transformToXML($doc);

    $axsl = new DOMDocument;
    $axsl->load('application/pm/xsl/abstracts.xsl');
    $aproc = new XSLTProcessor();
    $aproc->setParameter('', 'publication', $this->get_journal_id());
    $aproc->importStyleSheet($axsl);

    $abstracts = $doc->getElementsByTagName('abstract');

    foreach ($abstracts as $abstract) {
      $abstype = $abstract->getAttribute('abstract-type');
      $abstype = (($abstype)) ? $abstype : 'full';

      // convert abstract node to XML and make a create a new DOMDocument from it
      $abstract_XML = $abstract->ownerDocument->saveXML($abstract);
      $aXML = new DOMDocument('1.0', 'utf-8');
      @$aXML->loadXML($abstract_XML);
      $this->abstracts[$abstype] = $aproc->transformToXML($aXML);
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

    $this->supplementary_material = $doc->getElementsByTagName('supplementary-material');

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

  function get_section() {
    return $this->section;
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
  function get_authors_toString($and_size=null) {
    return decodeAuthors($this->authors, $and_size);
  }
  /**
   * get_authors_list
   *
   * @access public
   * @return Array of authors as firstname surname
   */


  function get_authors_list() {
    if (empty($this->authors)) {
      return false;
    }
    $authors_json = json_decode($this->authors);
    $authors_list = array();
    foreach ($authors_json as $author) {
      $name = $author->name;
      $authors_list[] = join(" ",array($name->{'given-names'}, $name->surname));
    }
    return $authors_list;
  }



  function get_article_type() {
    return $this->article_type;
  }
  function get_heading() {
    return $this->heading;
  }
  function get_overline() {
    return smartcase($this->overline);
  }
  /**
   * get_fields
   *  returns an array of field codes if no parameter is set,
   *  otherwise returns the value of the field map corresponding
   *  to the first value in the field array
   *
   * @param mixed $category
   * @access public
   * @return void
   */
  function get_fields($category=false) {
    $field_map = array(
      'ANAT/MORP'=>'Anatomy', 
      'ANTHRO'=>'Anthropology',
      'ASIA/PACIFIC NEWS'=>'Asia/Pacific News',
      'ASTRONOMY'=>'Astronomy',
      'ATMOS'=>'Atmospheric Science',
      'BIOCHEM'=>'Biochemistry',
      'BOTANY'=>'Botany',
      'CELL BIOL'=>'Cell Biology',	
      'CHEMISTRY'=>'Chemistry',
      'COMP/MATH'=>'Computers',
      'DEVELOPMENT'=>'Development',
      'ECOLOGY'=>'Ecology',
      'ECONOMICS'=>'Economics',
      'EDITORIAL'=>'Editorial',
      'EDUCATION'=>'Education',
      'ENGINEERING'=>'Engineering',
      'EPIDEMIOLOGY'=>'Epidemiology',
      'EUROPE NEWS'=>'Europe News',
      'EVOLUTION'=>'Evolution',
      'GENETICS'=>'Genetics',
      'GEOCHEM PHYS'=>'Geochemistry',
      'SCI HISTORY PHILO'=>'History and Philosophy of Science',
      'IMMUNOLOGY'=>'Immunology',
      'LAT AMER NEWS'=>'Latin American News',
      'MAT SCI'=>'Materials Science',
      'MEDICINE'=>'Medicine',
      'MICROBIO'=>'Microbiology',
      'MOLECBIOL'=>'Molecular Biology',
      'MOLEC BIOL'=>'Molecular Biology',
      'NETLINK'=>'NetWatch',
      'NEUROSCIENCE'=>'Neuroscience',
      'OCEANS'=>'Oceanography',
      'PALEO'=>'Paleontology',
      'PHARM TOX'=>'Pharmacology',
      'PHYSICS'=>'Physics',
      'APP PHYSICS'=>'Applied Physics',
      'PHYSIOLOGY'=>'Physiology',
      'PLANET SCI'=>'Planetary Science',
      'PSYCHOLOGY'=>'Psychology',
      'SCI BUSINESS'=>'Science and Business',
      'SCI POLICY'=>'Science and Policy',
      'SCI COMMUN'=>'Scientific Community',
      'SOCIOLOGY'=>'Sociology',
      'VIROLOGY'=>'Virology'
    );


    $key_exists = (count($this->fields) > 0 && array_key_exists($this->fields[0], $field_map));
    if ($category && $key_exists) {
      return $field_map[$this->fields[0]];
    } elseif ($key_exists) {
      return $this->fields;
    }
    return false;
  }
  /**
   * get_fields_category
   *  
   * @access public
   * @return void
   */
  function get_fields_category() {
    return $this->get_fields(true);
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
 /* function get_pub_date() {
    // prefer print over electronic
    if (isset($this->pub_dates['ppub'])) {
      return $this->pub_dates['ppub'];
    } 

    elseif (isset($this->pub_dates['epub'])) {
      return $this->pub_dates['epub'];
    }
    else { 
      return false;
    }
 }

  */
  function get_pub_date($format = null) {
    if (empty($format)) {
      if (isset($this->pub_dates['ppub'])) {
        return $this->pub_dates['ppub'];
      } 

      elseif (isset($this->pub_dates['epub'])) {
        return $this->pub_dates['epub'];
      }
      else { 
        return false;
      }
    } 

    else {
      if (isset($this->pub_dates['ppub'])) {
        return date($format, strtotime($this->pub_dates['ppub']));
      } 
      elseif (isset($this->pub_dates['epub'])) {
        return date($format, strtotime($this->pub_dates['epub']));
      }
      else { 
        return false;
      }
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
    if (empty($this->doi)) {
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
   * get_journal_id
   *
   * @access public
   * @return void
   */
  function get_journal_id() {
    // if Science Express
    if ($this->get_epub_date() && $this->get_ppub_date()) {
      return "scienceexpress";
    }
    return strtolower($this->journal_id);
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
      'DOI' => $this->get_publisher_id(),
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
  /**
   * get_custom_meta
   *  With no parameters returns all the keys which can then be used to get the rest of the data
   *
   * @param mixed $key
   * @access public
   * @return void
   */
  function get_custom_meta($key = null) {
    if (is_null($key) && !empty($this->custom_meta)) {
      return array_keys($this->custom_meta);
    }
    elseif (!empty($this->custom_meta[$key])) {
      return $this->custom_meta[$key];
    }
    return false;
  }
  function get_editor() {
    $editor = $this->get_custom_meta('editor');
    if (!empty($editor)) {
      return join(", ", $editor);
    }
    return false;
  }
  /**
   * get_syndication
   *   by default we syndicate all articles unless specified otherwise in the XML
   * @access public
   * @return void
   */
  function get_syndication() {
    $syndication = $this->get_custom_meta('syndication');
    if (empty($syndication)) {
      return true;
    }
    return false;
  }
  function get_isSpecialIssue() {
    if ($this->is_special_issue) {
      return true;
    }
    return false;
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

    $abstracts = $this->abstracts;

    if (!empty($type) && isset($abstracts[$type])) {
      return $abstracts[$type];
    }

    if ( isset($abstracts['excerpt']) ) {
      return $abstracts['excerpt'];
    } 
    elseif ( isset($abstracts['web-summary']) ) {
      return $abstracts['web-summary'];
    } 
    /* elseif ( isset($abstracts['teaser']) ) {
      return $abstracts['teaser'];
    } */

    if ( isset($abstracts['full']) ) {
      return $abstracts['full'];
    }

    return false;


  }



  function get_figures() {
    return $this->figures;
  }
  function get_sections() {
    return $this->sections;
  }
  function get_som() {
    if ($this->supplementary_material->length > 0) {
      return true;
    }
    return false;
  }
  function as_html() {
    return $this->as_html;
  }

}
?>
