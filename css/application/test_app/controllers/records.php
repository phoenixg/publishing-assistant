<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Records extends Controller {

	//public $mappings = array();
	
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
		redirect('records/main');
	}

	/**
	 * View Main
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function main()
	{
		$data['content'] = $this->load->view('records/main', '', true);
		$this->load->view('template', $data);
	}
	
	/**
	 * List Records
	 *
	 * @return void
	 * @return void
	 * @author MGreen
	 **/
	public function list_records($type, $start=1700, $end=1899)
	{	
		$this->load->model('collection_model');

		/*
		/ Cluge to sort the oral histories correctly
		if ($type == 3) {
			$order = 'sort_string';
		} else {
			$order = 'sort_date';
		}
		*/

		if ($end > ($start+9)) {
			$end = $start + 9;
		}
		
		if ($start > $end ) {
			$end = $start;
		}

		$data['type'] = $type;
		if ($start == 1700) {
			$data['selected'] = "1900";
			$end = 1929;
		} else {
			$data['selected'] = floor($start/10)*10;
		}
		$data['start'] = $start;
		$data['end'] = $end;
		$data['record_type'] = $type;
		
		$start .= "-01-01 00:00:00";
		$end .= "-12-31 00:00:00";
		$sort_order = $this->mappings[0][$type]['sort-order'];
		
		$data['labels'] = array(1700, 1930, 1940, 1950, 1960, 1970, 1980, 1990, 2000, 2010);
		$data['records'] = $this->collection_model->get_records_by_date($type, $start, $end, $sort_order);
		$data['content'] = $this->load->view('records/list_records', $data, true);
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Update Record
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function update_record($id)
	{
		$this->load->model('collection_model');
		if ($this->input->post('submit')) {
			//Update the Record
			$this->collection_model->update_record();
		}
		$data['record']  = $this->collection_model->get_record($id);
		$data['record_types']  = $this->collection_model->get_record_types();
		$data['media_types']  = $this->collection_model->get_media_types();
		$data['content'] = $this->load->view('records/edit_record', $data, true);
		$this->load->view('template', $data);
	}
	
	/**
	 * Add Multiple Records
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function add_multiple_records()
	{	
		$this->load->model('collection_model');
		if ($this->input->post('submit')) {
		
			$input = explode("\n", $this->input->post('data'));
			$record_type = $this->input->post('record_type');
			$only_new = $this->input->post('only_new');
			
			$media_data = array();
						
			//- loop backwards through the array
			for($i = (count($input)-1); $i >= 0; $i--){
				
				//split the input row into addressable fields
				$row_data = explode("|", $input[$i]);

				if ($this->get_mapping($row_data, $record_type, 'sort_date') != '' && $this->get_mapping($row_data, $record_type, 'title') != '') {

					//THIS IS A RECORD ROW, SO ADD RECORD DATA AND MEDIA DATA
					$record_data = array(
						'record_type' => $record_type,
						'date_string' => $this->get_mapping($row_data, $record_type, 'date_string'),
						'sort_date'   => $this->get_mapping($row_data, $record_type, 'sort_date'),
						'sort_string' => $this->get_mapping($row_data, $record_type, 'sort_string'),
						'title'       => $this->get_mapping($row_data, $record_type, 'title'),
						'description' => $this->get_mapping($row_data, $record_type, 'description'),
						'author'      => $this->get_mapping($row_data, $record_type, 'author'),
						'provenance'  => $this->get_mapping($row_data, $record_type, 'provenance'),
						'tags'        => $this->get_mapping($row_data, $record_type, 'tags'),
						'create_date' => date("Y-m-d H:i:s"),
						'is_new'	  => "1"
					);
					
					$media_data[] = array(
						'filename'	=> $this->get_mapping($row_data, $record_type, 'filename'), 
						'type'		=> $this->get_mapping($row_data, $record_type, 'type'), 
						'order'		=> $this->get_mapping($row_data, $record_type, 'order'), 
						'notes'		=> $this->get_mapping($row_data, $record_type, 'notes') 
					);
					
					// NOW ADD TO DATABASE and RESET
					$rid = $this->collection_model->add_new_record($record_data, $media_data);
					$media_data = array();
					
				} else {
				
					//THIS IS NOT A RECORD ROW, JUST ADD THE MEDIA DATA THEN CONTINUE THE LOOP
					$media_data[] = array(
						'filename'	=> $this->get_mapping($row_data, $record_type, 'filename'), 
						'type'		=> $this->get_mapping($row_data, $record_type, 'type'), 
						'order'		=> $this->get_mapping($row_data, $record_type, 'order'), 
						'notes'		=> $this->get_mapping($row_data, $record_type, 'notes') 
					);
				}
			}
						
			// display the summary of all the hard work we just did.
			$data['num_records']  = count($input);
			$data['type'] = $this->mappings[0][$record_type]['name'];	// get the string description for  the record type from config.
			$data['content'] = $this->load->view('records/add_multiple_records', $data, true);
			$this->load->view('template', $data);

		}
	}

	/**
	 * Utility Function **
	 * Get Mapping Value
	 *
	 * @return value, or default value for a field mapping for the data
	 * @author MGreen
	 **/
	public function get_mapping($row, $id, $field)
	{
		$col = $this->mappings[$id][$field]['col'];
		
		if ($col < 0) {
			return $this->mappings[$id][$field]['default'];
		} else {
			$val = $row[$col];
			// IF THIS THIS A "TYPE" Field we only need the first character, which should be an INTEGER to ID the Field in the database.
			if ($field == "type") {
				$val = $val[0];
			}
			return $val;
		}
	}
	
	/**
	 * Add New Record
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function add_new_record()
	{
		$this->load->model('collection_model');
		if ($this->input->post('submit')) {
		
			$record_data = array(
				'date_string' => $this->input->post('date_string', TRUE),
				'sort_date' => $this->input->post('sort_date', TRUE),
				'sort_string' => $this->input->post('sort_string', TRUE),				
				'record_type' => $this->input->post('record_type', TRUE),
				'title' => $this->input->post('title', TRUE),
				'description' => $this->input->post('description', TRUE),
				'author' => $this->input->post('author', TRUE),
				'provenance' => $this->input->post('provenance', TRUE),
				'tags' => $this->input->post('tags', TRUE),
				'create_date' => date("Y-m-d H:i:s"),
				'is_new' => "1"
			);
			
			$media_data = array(
				array(
					'filename'  => $this->input->post('filename_0', TRUE), 
					'type'  => $this->input->post('media_type_0', TRUE), 
					'order' => $this->input->post('order_0', TRUE), 
					'notes' => $this->input->post('notes_0', TRUE), 
				)
			);
			
			//Update the Record
			$rid = $this->collection_model->add_new_record($record_data, $media_data);
			if ($rid >=0 ){
				redirect('records/update_record/' . $rid);
			}
		} else {
			$data['record_types']  = $this->collection_model->get_record_types();
			$data['media_types']  = $this->collection_model->get_media_types();		
			$data['content'] = $this->load->view('records/add_new_record', $data, true);
			$this->load->view('template', $data);
		}
	}

	/**
	 * Verify Data
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function verify_data()
	{
		
		$this->load->model('collection_model');
	
		//array of missing items
		$result = array();
		
		//array showing which sections we'rve chosen to verify
		$check_section = array();
		
		if ($this->input->post('submit')) {
		
			//Grab the post data and work out which sections we're supposed to verify
			if ($this->input->post('papers')) {
				array_push ($check_section, $this->input->post('papers'));
			}
			if ($this->input->post('photos')) {
				array_push ($check_section, $this->input->post('photos'));
			}
			if ($this->input->post('oral')) {
				array_push ($check_section, $this->input->post('oral'));
			}
			if ($this->input->post('programs')) {
				array_push ($check_section, $this->input->post('programs'));
			}
			if ($this->input->post('filmrt')) {
				array_push ($check_section, $this->input->post('filmrt'));
			}

			if ($this->input->post('check_all')) {
				$date = "1900-00-00 00:00:00";
			} else {
				$date = $this->input->post('year') . "-" . $this->input->post('month') . "-" . $this->input->post('day') . " 00:00:00";
			}
			
			//Check which create date to use.
			
			//root to the collection on the server
			$basepath = COLLECTIONROOT;
			
			// get record group for this tag
			$record_group = $this->collection_model->get_records_by_create_date($date);			
			
			// loop through each type in the group, look for missing files
			foreach ($check_section as $i) {
				
				$records = $record_group[$i];
				
				if (count($records) > 0) {
					
					$section = $this->mappings[0][$i]['path'];
					$name = $this->mappings[0][$i]['name'];
					
					foreach ($records as $record) {
						foreach ($record['media'] as $media) {

							$str = trim($media['filename']); 
							$has_path = false;
							
							if (strpos($str, "/") || strpos($str, "/") === 0) {
							
								if (strpos($str,  "collection")) {
									$testfile = "\\xampp\\htdocs" . str_replace("/", "\\", $str);
								} else {
									$testfile = $str;
									$has_path = true;
								}
								
							} else  {
							
								if ($i == 1) {
									//add the decade if it's a paper
									$testfile = $basepath . $section .  "\\" . substr($record['sort_date'], 0 ,3) . "0\\" . $str;	
								} elseif ($i == 2) {
									//direct to the full sized image folder if it's photos
									$testfile = $basepath . $section .  "\\full\\" . $str;
								} else {
									$testfile = $basepath . $section . "\\" . $str;
								}
							
							}

							if ($has_path) {
							
								array_push($result, array('filepath'=> "REF: " . $testfile, 'id'=> $record['id'] ));
							
							} else {
															
								if (!file_exists($testfile)) {
									array_push($result, array('filepath'=>$testfile, 'id'=> $record['id'] ));
								}
								
							}
								
						}				
					}
					
				}
				
				$i++;
			}

		}	
	
		$data['result'] = $result;
		$data['content'] = $this->load->view('records/verify_data', $data, true);
		$this->load->view('template', $data);
	}	

}