<?php
error_reporting(1);
/**
 * AdMaster controller
 */

/**
 * AdMaster
 *
 * @version 
 * @author Corinna Cohn
 * @license 
 */
class AdMaster extends CI_Controller {

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
    $data['title'] = "Ad Content Manager";
    $output = '';
    // Parse config file for ads
    $all_ads = array();
    foreach ($this->alerts as $journal => $alerts) {
      foreach ($alerts as $alert => $alert_ad) {
        // setup the default header/footer ads
        // $current_alert = key($alerts);
        $all_ads[$journal][$alert]['header'] = "${journal}_${alert}_header.html";
        $all_ads[$journal][$alert]['footer'] = "${journal}_${alert}_footer.html";
        if (is_array($alert_ad['ads'])) {
          foreach ($alert_ad['ads'] as $position => $path) {
            $all_ads[$journal][$alert][$position] = "${journal}_${alert}_${path}";
          }
        }
      }
    } 
    
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublishAds'), TRUE); 
    $data['content'] = buildEditableAlertTabs($all_ads, $this->journals, $this->ad_outpath);
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
