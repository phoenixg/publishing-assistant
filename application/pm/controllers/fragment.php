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


  //constructor =======================================================================
  function __construct() {
    //set up the controll construct
    parent::__construct();
    //load our helper files
    //the required helpers are actually being called by the library, so this line could come out, but depending on how
    //functions get shifted, I'm leaving this in here (commented out) for the time being.
    $this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));

    //load our library functions, where most of the xml functions are located
    $this->load->model('Issue_model');
    $this->load->config('pm_config');
    $this->load->config('fragments');
    $this->journals = $this->config->item('journals');
    $this->fragments = $this->config->item('fragments');
    //$this->alerts = $this->config->item('alerts');
    //$this->teaser_options = $this->config->item('teaser_options');
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
    $fragments = $this->fragments;
    $fragmentsList = null;
    $tabSets = null;
    $tabCounter = 0;
    foreach ($fragments as $pubName => $pubData) {
      $tabData = array();
      $tabData['journal'] = $pubName;
      $tabData['tab'] = "tab" . ++$tabCounter;
      $tabData['html_preview'] = null;
      foreach ($pubData as $fragment) {
        $tabData['lines'][] = $this->load->view('shared/checkbox', 
          array('id' => $fragment['id'], 'label' => $fragment['description']), TRUE);
      }
      $tabSets .= $this->load->view('shared/tab_set', $tabData, TRUE);
    } 
    $data['content'] = form_open('fragment/save', array('id' => 'fragGen',));  
    // Setup journal tabs 
    $data['content'] .= $this->load->view('shared/tab_contents', array('tabcontents' => $tabSets), TRUE);
    $data['content'] .= form_submit( array('name'=>'submit', 'class'=>'btn btn-primary', 'value'=>'Generate Fragments','type'=>'submit'),'');
    $data['content'] .= form_close();
    
    //$data['content'] = $tabSets;
    $data['title'] = "Fragment Generator";
    //$data['content'] = $this->load->view('fragments/fragments_main', $fragmentsList, TRUE);
    // $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublish'), TRUE); 
    $this->load->view('pagewrapper-tb', $data);	
  }
}
?>
