<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Galleries extends Controller {

	public $mappings = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->config('test_app');
		$this->mappings = $this->config->item('mappings');
	}

	/**
	 * index
	 *
	 * @return void
	 * @author MGreen
	 **/
	function index()
	{
		//redirect('welcome/status');
		redirect('galleries/build_gallery');
	}

	/**
	 * Build New Gallery
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function build_new_gallery()
	{
		if ($this->input->post('submit')) {
			$data['galleryName'] = $this->input->post('galleryName');
			$data['folder'] = $this->input->post('folder');
			$data['textarea'] = $this->input->post('textarea');
			$data['submit'] = $this->input->post('submit');
		} else {
			$data['galleryName'] = "";
			$data['folder'] = "";
			$data['textarea'] = "";
			$data['submit'] = "";
		}

		$data['content'] = $this->load->view('galleries/build_gallery', $data, true);
		$this->load->view('template', $data);
	}	

	/**
	 * Gallery Tools
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function gallery_tools()
	{
		$data['content'] = $this->load->view('galleries/gallery_tools', '', true);
		$this->load->view('template', $data);
	}	


	/**
	 * Update Tag Data
	 *
	 *Add new tag data to a set of multiple records
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function update_tags()
	{
		$this->load->model('collection_model');
		$record_types = $this->collection_model->get_record_types();		
		
		$records = array();
		$msg = array();
		
		$data['textarea'] = "No data specified";
		$data['preview'] = false;		
		
		if ($this->input->post('submit')) {
		
			$textarea = $this->input->post('textarea');
			
			if ($textarea!="") {
			
				$data['textarea'] = $textarea;
				$data['preview'] = $this->input->post('preview');
				$data['lookup'] =  $this->input->post('lookup');
			
				$records = explode("\n", $textarea);
				
				foreach($records as $record) {
					
					if ($data['lookup'] != "") {
						$id = trim($record);
					} else {
						list($id, $tag) = explode ("|", $record);
					}
					
					$record_tmp = $this->collection_model->get_record($id);
					
					if ($record_tmp) {

						if ($data['lookup'] != "") {
						
							$type = $record_tmp['record_type']['value'];
							$extra = "";
							if ($type == 1) {
								$extra = substr($record_tmp['sort_date']['value'], 0, 3) . "0/";
							}
							$msg_string = "{!{" . $id . "}!} \t /collection/" . $extra . $this->mappings[0][$type]['path'] . "/" . $record_tmp['media'][0]['filename'];	
						
							if (count($record_tmp['media']) > 1 ) {
								$msg_string .= " <span style=\"color: red\">(showing first url of " . count($record_tmp['media']) . " parts)</span>";
							}
						
						} else {

							$tag_tmp = $record_tmp['tags']['value'];
							if (strpos($tag_tmp, $tag) !== false) {
								//it is in there already. no need to update it.
								$msg_string = $tag_tmp . " : Already there. (" . $id . ")";
							} else {
								// it's not there, so we add it.
								$msg_string = $tag_tmp . " <span style='font-weight: bold; color: red;'>" . $tag . "</span>: Added. (" . $id . ")";
								$tag_tmp .= " " . $tag;
								$result = $this->collection_model->update_tag($id, $tag_tmp);
							}

						}
						
						array_push($msg, $msg_string);
							
					} else {

						//id used was not valid in the database
						array_push($msg, $id . " - <span style='color: red; font-weight: bold;'>INVALID ID</span>");
					
					}
				}
				
			} else {

				$data['textarea'] = "";
				$data['preview'] = false;
				array_push($msg, "THERE WAS NO VALID DATA");
			
			}
			
			$data['msg'] = $msg;
		
		}
		
		$data['content'] = $this->load->view('galleries/update_tags_report', $data, true);
		$this->load->view('template', $data);
	}		
	
}
