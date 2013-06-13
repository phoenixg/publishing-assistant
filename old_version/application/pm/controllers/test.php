
<?php
error_reporting(E_ALL);
class test extends CI_Controller {


  //constructor =======================================================================
  function __construct() {
    //set up the controll construct
    parent::__construct();
    //load our helper files
    //the required helpers are actually being called by the library, so this line could come out, but depending on how
    //functions get shifted, I'm leaving this in here (commented out) for the time being.
    $this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));
    $this->load->config('pm_config');
    $this->load->model('Issue');
    $this->load->model('Article');

    $this->publications = $this->config->item('journals');
  }

  /**
   * index
   *
   * List the available fragments for generation
   *
   * @author ccohn@aaas.org
   * @return void
   **/
  function index() {
    $issue = $this->Issue;

    $issue->init('sci','04/05/2013');
    $publication = $issue->get_publication();
    $data['content'] = "Publication: " . $publication . "<br>";

    $name = $issue->get_name();
    $data['content'] .= "Name: " . $name . "<br>";

    $id = $issue->get_id();
    $data['content'] .= "ID: " . $id. "<br>";

    $doi_id = $issue->get_doi_id();
    $data['content'] .= "DOI ID: " . $doi_id. "<br>";

    $publish_date = $issue->get_publish_date();
    $data['content'] .= "publish_date: " . $publish_date . "<br>";

    $issue_cover = $issue->get_issue_cover();
    $data['content'] .= "issue_cover: <img src='{$issue_cover}' /><br>";


    $base_url = $issue->get_base_url();
    $data['content'] .= "base_url: " . $base_url . "<br>";

    $xml_path = $issue->get_xml_path('ScienceXpress');
    // $xml_path = $issue->get_xml_path('Articles');
    $data['content'] .= "xml_path: for articles " . $xml_path . "<br>";

    $get_article_list = $issue->get_article_list('Articles');

    $articles = $issue->get_articles('Articles');
    $issue->filter_articles($articles, 'title', 'bees');
    $issue->sort_by_fpage($articles);


    foreach ($articles as $article) {
      $data['content'] .= "page ({$article->get_first_page()}) {$article->get_article_type()}<br/>";
      $data['content'] .= "<a href=".$article->get_url().">".$article->get_title() . "</a><br>";
    }

    $Article = $issue->new_article($get_article_list[25]);

    $data['content'] .= "<h3>Article model</h3>";
    $data['content'] .= "title: " . $Article->get_title() . "<br/>";
    $data['content'] .= "publication: " . $Article->get_publication() . "<br/>";
    $data['content'] .= "volume_num: " . $Article->get_volume_num() . "<br/>";
    $data['content'] .= "issue_num: " . $Article->get_issue_num() . "<br/>";
    $data['content'] .= "doi: " . $Article->get_doi() . "<br/>";
    $data['content'] .= "publisher id: " . $Article->get_publisher_id() . "<br/>";
    $data['content'] .= "first_page: " . $Article->get_first_page() . "<br/>";
    $data['content'] .= "ppub: " . $Article->get_ppub_date() . "<br/>";
    $data['content'] .= "epub: " . $Article->get_epub_date() . "<br/>";
    $data['content'] .= "pub_date: " . $Article->get_pub_date() . "<br/>";
    $data['content'] .= "fpage: " . $Article->get_fpage() . "<br/>";
    $data['content'] .= "lpage: " . $Article->get_lpage() . "<br/>";
    $data['content'] .= "elocation_id: " . $Article->get_elocation_id() . "<br/>";
    $data['content'] .= "seq alpha: " . $Article->get_seq_alpha() . "<br/>";
    $data['content'] .= "seq numeric: " . $Article->get_seq_num() . "<br/>";
    $data['content'] .= "article_type : " . $Article->get_article_type() . "<br/>";
    $data['content'] .= "heading : " . $Article->get_heading() . "<br/>";
    $data['content'] .= "overline : " . $Article->get_overline() . "<br/>";
    $data['content'] .= "fields : " . join(',',$Article->get_fields()) . "<br/>";
    $data['content'] .= "editor: " . $Article->get_editor() . "<br/>";
    $data['content'] .= "authors: " . $Article->get_authors() . "<br/>";
    $data['content'] .= "authors (formatted): " . $Article->get_authors_toString() . "<br/>";
    $data['content'] .= "abstract (editor): " . $Article->get_abstracts('editor') . "<br/><hr>";
    $data['content'] .= "abstract (teaser): " . $Article->get_abstracts('teaser') . "<br/><hr>";
    $data['content'] .= "abstract (default): " . $Article->get_abstracts(true) . "<br/><hr>";

    $data['content'] .= "syndication?: " . $Article->get_syndication() . "<br/>";

    $data['content'] .= $Article->as_html();

    $data['content'] .= "<h3>Article</h3><hr><br>";
    //var_dump($Article);


    $data['content'] .= "<hr><br>";
		$this->load->view('pagewrapper-tb', $data);
    //var_dump ($issue);
    
  }
  /**
   * toc
   *  basic table of contents
   *
   * @access public
   * @return void
   */
  function toc() {
    $issue = $this->Issue;
    $issue->init('sci','04/05/13');
    $articles = $issue->get_articles('Articles');
    $issue->sort_by_article_type($articles);
    // $issue->filter_articles($articles,'overline','a');
		$data['content'] = $this->load->view('templates/table_of_contents', array('issue' => $issue,'articles' => $articles), true);
		$this->load->view('pagewrapper-tb', $data);
  }
  /**
   * grouped
   *
   * @access public
   * @return void
   */
  function grouped() {
    $issue = $this->Issue; // create a new issue
    $issue->init('sci','04/05/2013'); // Initialize it for Science
    $articles = $issue->get_articles('Articles');
    $issue->sort_by_article_type($articles);
    $grouped = array();
    // build the grouped array
    foreach ($articles as $article) {
      $article_type = $article->get_article_type();
      $grouped[$article_type][] = $article;
    }
    $current = null;
    $data['content'] = "<h2>{$issue->get_name()} by group</h2>";
    foreach ($grouped as $group => $articles) {
      // make the group title
      if ($current == $group) {
        $data['content'] .= "<br/>";
      } else {
        $data['content'] .= "<h3>{$group}</h3>";
        $current = $group;
      }
      // add the list of articles
      foreach ($articles as $article) {
        $data['content'] .= "{$article->get_title()} ({$article->get_first_page()})<br/>";
      }
    }
		$this->load->view('pagewrapper-tb', $data);
  }
 }
?>
