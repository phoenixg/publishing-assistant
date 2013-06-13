<?php
error_reporting(1);
/**
 * editorNotes
 *
 * Similar in function to adMaster, but used for putting editor's notes or
 * house advertisements into newsletters. 
 *
 */

/**
 * EditorNotes
 *
 * @version 
 * @author Corinna Cohn
 * @license 
 */
class EditorNotes extends CI_Controller {

  public function __construct() {
    parent::__construct();
		$this->load->config('pm_config');
    $this->load->library('javascript');
		$this->load->helper(array ('common','form','url', 'file'));
		$this->journals = $this->config->item('journals');
		$this->alerts = $this->config->item('alerts');
		$this->ad_outpath = 'application/pm/output/ads/';
  }

  /**
   * index
   *
   * @access public
   * @return void
   */
  function index() {
    $data['title'] = "Editorial Content Manager";
    $output = '';
    $all_editors_notes = array();
    // Parse config file for ads
    $all_notes = array();
    foreach ($this->alerts as $journal => $alerts) {
      foreach ($alerts as $alert => $alert_editors_note) {
        if (is_array($alert_editors_note['editors_notes'])) {
          // read notes from config file
          foreach ($alert_editors_note['editors_notes'] as $position => $path) {
            $all_editors_notes[$journal][$alert][$position] = "${journal}_${alert}_${path}";
          }
        }
      }
    } 
    
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublishAds'), TRUE); 
    $data['content'] = buildEditableAlertTabs($all_editors_notes, $this->journals, $this->ad_outpath);
    // Load the main page wrapper

    $this->load->view('pagewrapper-tb', $data);
    
  }

  /**
   * save
   *
   * Saves the ad form
   *
   * @access public
   * @return void
   */
  function save() {
    $form = $this->input->post();
    $files_saved = array();
    foreach ($form as $file => $data){
      if (saveAd($file, $this->ad_outpath, $data)) array_push($files_saved, $file);
    }
    /*
     * { "file" : x } number of files changed
     */
    echo json_encode(array('files' => $files_saved));
  }
}
