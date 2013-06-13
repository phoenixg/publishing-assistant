<?php
error_reporting(E_ALL);
/**
 * fragGen
 *
 * Generates fragments of issue-related HTML as base HTML for further hand customization
 * @version 
 * @copyright Copyright (C) AAAS/Science 2013. All rights reserved.
 * @author ccohn@aaas.org
 */
class fragment extends CI_Controller {

  private $fragment_list;

  //constructor =======================================================================
  function __construct() {
    //set up the controll construct
    parent::__construct();
    //load our helper files
    //the required helpers are actually being called by the library, so this line could come out, but depending on how
    //functions get shifted, I'm leaving this in here (commented out) for the time being.
    $this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));

    //load our library functions, where most of the xml functions are located
    $this->load->model('Issue');
    $this->load->model('Article');
    $this->load->config('pm_config');
    $this->load->config('fragments');
    $this->fragments = $this->config->item('fragments');

    $this->fragment_list = new FragmentList();
    foreach ($this->fragments as $fragment) {
      $this->fragment_list->add($fragment);
    }
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
    $fragmentsList = null;
    $tab_sets = null;
    $tab_data = array();
    $tab_count = 0;

    //foreach ($fragments as $pub_name => $pub_data) {
    foreach ($this->fragment_list->by_publication() as $frag_id => $pub_name) {
      $tab_data[$pub_name]['journal'] = $pub_name;
      if (!array_key_exists('tab', $tab_data[$pub_name])) { // only add the tab once per publication
        $tab_data[$pub_name]['tab'] = "tab" . ++$tab_count; 
      }
      $tab_data[$pub_name]['lines'][] = $this->fragment_list->find($frag_id)->make_checkbox(); 
    } 
    foreach (array_keys($tab_data) as $pub_id) {
      $tab_sets .= $this->load->view('shared/tab_set', $tab_data[$pub_id], TRUE);
    }

    $data['content'] = form_open('fragment/save', array('id' => 'fragment',));  
    $data['content'] .= $this->load->view('shared/tab_contents', array('tabcontents' => $tab_sets), TRUE);
    $data['content'] .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Generate Fragments','type'=>'submit'),'');
    $data['content'] .= form_close();

    $data['title'] = "Fragment Generator";
    // $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublish'), TRUE); 
    $this->load->view('pagewrapper-tb', $data);	
  }
  function save() {
  	

    $data['title'] = "Generated output";
    $data['content'] = "<pre>";
    $form = $this->input->post();
    foreach ($form as $frag_id) {
      $fragment = $this->fragment_list->find($frag_id);
		
			
      if ($fragment) {
        // following line is "production"
        if (ENVIRONMENT == "development") {
          $data['content'] .= htmlentities($fragment->make_fragment()->get_generated_output());
        } else {
          $fragment->make_fragment()->save(); 
        }
      }
    }
    $data['content'] .= "</pre>";
    /*
     * { "file" : x } number of files changed
     */
    $this->load->view('pagewrapper-tb', $data);	
  }
}
class FragmentItem {
  private $publication;
  private $id;
  private $data_source = null;
  private $name;
  private $filters = array();
  private $sort;
  private $description;
  private $view;
  private $output;
  function __construct($frag) {
    $this->id = $frag['id'];
    $this->publication = $frag['publication'];
    if (!empty($frag['data_source'])) {
      $this->data_source = $frag['data_source'];
    } 
    if (!empty($frag['name'])) {
      $this->name = $frag['name'];
    }
    if (array_key_exists('filters', $frag)) {
      foreach ($frag['filters'] as $key => $value) {
        $this->filters[$key] = $value;
      }
    }
    if (!empty($frag['sort'])) {
      $this->sort = $frag['sort'];
    }
    $this->description = $frag['description'];
    $this->view = $frag['view'];
    $this->output = $frag['output'];
  }
  function get_view() {
    return 'templates/fragments/'.$this->view;
  }
  function get_generated_output() {
    if (!empty($this->_generated_output)) {
      return $this->_generated_output;
    }
    return false;
  }
  /**
   * make_checkbox
   *
   * @access public
   * @return string HTML for the checkbox
   */
  function make_checkbox() {
    $CI =& get_instance();
    return $CI->load->view('shared/checkbox', array(
      'id' => $this->id, 
      'value' => $this->id, 
      'label' => $this->description
    ), TRUE);
  }
  /**
   * make_fragment
   *  Builds the view/template associated to the fragment
   *
   * @access public
   * @return self
   */
  function make_fragment() {
    $this->CI =& get_instance();
    $issue = $this->CI->Issue;
    $issue->init($this->publication);
    $articles = $issue->get_articles($this->data_source); 

    if (count($this->filters) > 0) {
      foreach ($this->filters as $el => $pattern) {
        $issue->filter_articles($articles, $el, $pattern);
      }
    }
    if (!empty($this->sort)) {
      $sort = "sort_by_" . $this->sort;
      $issue->{$sort}($articles);
    }
    $this->_generated_output = $this->CI->load->view($this->get_view(), array('issue' => $issue, 'articles' => $articles), TRUE);
    return $this;
  }
  function save() {
    if (!empty($this->_generated_output)) {
      $outfile = $this->output;
      file_put_contents($outfile, $this->_generated_output);
    }
  } 
}
class FragmentList {
  private $fragments = array();
  private $fragments_by_publication = array(); // dictionary of fragment by journal
  function add($fragment) {
    $frag_id = $fragment['id'];
    $frag_pub = $fragment['publication'];
    $this->fragments[$frag_id] = new FragmentItem($fragment);
    $this->fragments_by_publication[$frag_id] = $frag_pub;
  }
  function find($id) {
    if (!empty($this->fragments[$id])) {
      return $this->fragments[$id];
    } else {
      return false;
    }
  }
  function by_publication() {
    return $this->fragments_by_publication;
  }
}
?>
