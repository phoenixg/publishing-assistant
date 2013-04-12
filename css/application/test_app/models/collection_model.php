<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
* collection_model
*/
class collection_model extends Model
{
	/**
	 * Holds an array of tables used.
	 *
	 * @var string
	 **/
	public $collection = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->config('test_app');
		$this->collection  = $this->config->item('collection');
	}

	/**
	 * Get Record
	 *
	 * @return array of details for selected, individual record
	 * @author M. Green
	 **/
	public function get_record($id)
	{
	    $records_table	= $this->collection['records'];
	    $media_table	= $this->collection['media'];
        $record_types_table	= $this->collection['record_types'];
        $media_types_table	= $this->collection['media_types'];
	    if ($id == false)
	    {
	        return false;
	    }
		// get basic record
	    $query  = $this->db->select('*')
                    	   ->where('id', $id)
                    	   ->limit(1)
                    	   ->get($records_table);
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			$result = $result[0];
			$details = array();
			
			//build array of name / value pairs  from returned record (useful for form helper)
			foreach ($result as $name => $value) {
				$details[$name] = array('name' => $name, 'value' => $value);
			}
			
			//get all associated media
		    $query  = $this->db->select('*')
	                    	   ->where('source_id', $id)
							   ->orderby ('order')
	                    	   ->get($media_table);
			$media = $query->result_array();
			
			//add media to record array
			$details['media'] = $media;
			
			return $details;
		} else {
			return false;
		}
	}
	
	/**
	 * Get Record Types
	 *
	 * @return array of all types of records
	 * @author M. Green
	 **/
	public function get_record_types()
	{	
		$record_types_table	= $this->collection['record_types'];
	    $query  = $this->db->get($record_types_table);
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			$types = array();
			foreach ($result as $row) {
				$types[$row['id']] = $row['record_type'];
			}
			return $types;
		}
	}
	
	/**
	 * Get Media Types
	 *
	 * @return array of all media types
	 * @author M. Green
	 **/
	public function get_media_types()
	{	
		$media_types_table	= $this->collection['media_types'];
	    $query  = $this->db->get($media_types_table);
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			$types = array();
			foreach ($result as $row) {
				$types[$row['id']] = $row['type'];
			}
			return $types;			
		}
	}	

	/**
	 * Get Records by Type
	 *
	 * @Return array of all records of a given Record Type
	 * @author M. Green
	 * 
	 **/
	public function get_records_by_type($type, $order)
	{
	    $records_table	= $this->collection['records'];
		$media_table	= $this->collection['media'];

		if ($type === false)
	    {
	        return false;
	    }

		$data = array();

		$query  = $this->db->select('*')
						   ->where('record_type', $type)
						   ->orderby($order)
						   ->get($records_table);

						   //loop through all returned ids and look up records
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			//get all associated media for the results
			foreach ($result as $record) {

			    $query  = $this->db->select('*')
		                    	   ->where('source_id', $record['id'])
								   ->orderby ('order')
		                    	   ->get($media_table);
				$media = $query->result_array();
				
				//add media to record array
				$record['media'] = $media;
				array_push($data, $record);
			}			
			
			return $data;
		} else {
			return false;
		}
	}		
	/**
	 * Get Records by Date
	 *
	 * @Return array of all records of a given Record Type for a specified date range.
	 * @author M. Green
	 * 
	 **/
	public function get_records_by_date($type, $start, $end, $sort)
	{
	    $records_table	= $this->collection['records'];
		$media_table	= $this->collection['media'];
		
		$data = array();
		
		if ($type === false)
	    {
	        return false;
	    }
		
		// note that the results are ordered by date, then sort string, then title, to help with multiple items on the same day.
		$query  = $this->db->select('*')
						   ->where('record_type', $type)
						   ->where('sort_date >=', $start)
						   ->where('sort_date <=', $end)
						   ->orderby('sort_date', $sort)
						   ->orderby('sort_string', 'asc')
						   ->orderby('title', 'asc')
						   ->get($records_table);
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
						
			//get all associated media for the results
			foreach ($result as $record) {

			    $query  = $this->db->select('*')
		                    	   ->where('source_id', $record['id'])
								   ->orderby ('order')
		                    	   ->get($media_table);
				$media = $query->result_array();
				
				//add media to record array
				$record['media'] = $media;
				array_push($data, $record);
			}
			
			return $data;
			
		} else {
		
			return false;
			
		}
	}	

	
	/**
	 * Get Records by Tag Name
	 *
	 * @Return array of records which all have the same tag, grouped by record TYPE.
	 * @author M. Green
	 * 
	 **/
	public function get_records_by_tagname($tagname = false)
	{
	    $records_table	= $this->collection['records'];
	    $media_table	= $this->collection['media'];
		
		if ($tagname === false)
	    {
	        return false;
	    }
		
		$record_types = $this->get_record_types();
		
		for ($i=1; $i <= count($record_types); $i++) {
		
			$order = $this->mappings[0][$i]['sort-order'];
		
			if ($i == 3) {
				$order_col = "sort_string";
			} else {
				$order_col = "sort_date";
			}
			
			// set/reset the containing array for the records
			$records = array();
		
			//find ids of all records containing the tag
			$query  = $this->db->select('*')
							   ->where('record_type', $i)
							   ->like('tags', $tagname)
							   ->orderby($order_col, $order)
							   ->orderby('title', 'asc')
							   ->get($records_table);
			//loop through all returned ids and look up records
			if ($query->num_rows() > 0)
			{

				$result = $query->result_array();
							
				//get all associated media for the results
				foreach ($result as $record) {

				    $query  = $this->db->select('*')
			                    	   ->where('source_id', $record['id'])
									   ->orderby ('order')
			                    	   ->get($media_table);
					$media = $query->result_array();
					
					//add media to record array
					$record['media'] = $media;
					array_push($records, $record);
				}
				
			}
			
			$record_group[$i] = $records;
			//$record_group[$i]['name'] = $record_types[$i];
				
		}
		
		return $record_group;
	}
	
	/**
	 * Get Records by CreateDate
	 *
	 * @Return array of records which all have the same tag, grouped by record TYPE.
	 * @author M. Green
	 * 
	 **/
	public function get_records_by_create_date($start_date = false)
	{
	    $records_table	= $this->collection['records'];
	    $media_table	= $this->collection['media'];
		
		if ($start_date === false)
	    {
	        return false;
	    }
		
		$record_types = $this->get_record_types();
		
		for ($i=1; $i <= count($record_types); $i++) {
		
			$order = $this->mappings[0][$i]['sort-order'];
		
			if ($i == 3) {
				$order_col = "sort_string";
			} else {
				$order_col = "sort_date";
			}

			// set/reset the containing array for the records
			$records = array();
		
			//find ids of all records containing the tag
			$query  = $this->db->select('*')
							   ->where('create_date >=', $start_date)
							   ->where('record_type', $i)
							   ->orderby($order_col, $order)
							   ->orderby('sort_string', $order)
							   ->get($records_table);
			//loop through all returned ids and look up records
			if ($query->num_rows() > 0)
			{

				$result = $query->result_array();
							
				//get all associated media for the results
				foreach ($result as $record) {

				    $query  = $this->db->select('*')
			                    	   ->where('source_id', $record['id'])
									   ->orderby ('order')
			                    	   ->get($media_table);
					$media = $query->result_array();
					
					//add media to record array
					$record['media'] = $media;
					array_push($records, $record);
				}
				
			}
			
			$record_group[$i] = $records;
				
		}
		
		return $record_group;
	}

	/**
	 * Get Records that are Flagged as NEW
	 *
	 * @Return array of records which all have the "new" flag set, grouped by record TYPE.
	 * @author M. Green
	 * 
	 **/
	public function get_records_by_new($start_date = false)
	{
	    $records_table	= $this->collection['records'];
	    $media_table	= $this->collection['media'];
		
		if ($start_date === false)
	    {
	        return false;
	    }
		
		$record_types = $this->get_record_types();
		
		for ($i=1; $i <= count($record_types); $i++) {
		
			$order = $this->mappings[0][$i]['sort-order'];
		
			if ($i == 3) {
				$order_col = "sort_string";
			} else {
				$order_col = "sort_date";
			}

			// set/reset the containing array for the records
			$records = array();
		
			//find ids of all records containing the tag
			$query  = $this->db->select('*')
							   ->where('is_new >=', 1)
							   ->where('record_type', $i)
							   ->orderby($order_col, $order)
							   ->orderby('sort_string', $order)
							   ->get($records_table);
			//loop through all returned ids and look up records
			if ($query->num_rows() > 0)
			{

				$result = $query->result_array();
							
				//get all associated media for the results
				foreach ($result as $record) {

				    $query  = $this->db->select('*')
			                    	   ->where('create_date >=', $start_date)
			                    	   ->where('source_id', $record['id'])
									   ->orderby ('order')
			                    	   ->get($media_table);
					$media = $query->result_array();
					
					//add media to record array
					$record['media'] = $media;
					array_push($records, $record);
				}
				
			}
			
			$record_group[$i] = $records;
				
		}
		
		return $record_group;
	}

	
	/**
	 * Get Date Range
	 *
	 * @Return array of minumum and maximum dates *and* decades for the selected record type.
	 * @author M. Green
	 * 
	 **/
	public function get_date_range($type)
	{
	    $records_table	= $this->collection['records'];
		$range = array();
		
		if ($type === false)
	    {
	        return false;
	    }
		
		//get MIN details
		$query  = $this->db->select_min('sort_date')
						   ->where('record_type', $type)
						   ->limit(1)
						   ->get($records_table);
		if ($query->num_rows() > 0)
		{
			$min_year = $query->result_array();
			$range['min_year'] = (int)substr($min_year[0]['sort_date'], 0, 4);
			$range['min_decade']  = floor($range['min_year']/10)*10;
		}

		//get MAX details
		$query  = $this->db->select_max('sort_date')
						   ->where('record_type', $type)
						   ->limit(1)
						   ->get($records_table);
		if ($query->num_rows() > 0)
		{
			$max_year = $query->result_array();
			$range['max_year'] = (int)substr($max_year[0]['sort_date'], 0, 4);
			$range['max_decade']  = floor($range['max_year']/10)*10;
		}
		
		if ($range['max_decade'] >0 && $range['min_decade'] > 0) {
			$range['num'] = ( $range['max_decade'] - $range['min_decade'] ) / 10 + 1;
		} else {
			$range['num'] = -1;
		}
		
		return $range;

	}
	
	
	/**
	 * Get Tags
	 *
	 * @Return array of all tags found in the database.
	 * @author M. Green
	 * 
	 * 
	 **/
	public function get_tags()
	{
	    $records_table	= $this->collection['records'];
		
		$tags = array();
		
		//find ids of all records containing the tag
		$query  = $this->db->select('tags')
						   ->get($records_table);
		//loop through all returned ids and look up records
		if ($query->num_rows() > 0)
		{
		
			$result = $query->result_array();
			
			foreach ($result as $row) {
				if (trim($row['tags'])!="") {
					if (strpos($row['tags'], " ") !== false) {
						$tmp = explode(" ", $row['tags']); 
						foreach ($tmp as $tag) {
							$tags[$tag] = $tag;
						}
					} else {
						$tags[$row['tags']] = $row['tags'];
					}
				}
			}

			array_unique($tags);
			asort($tags, SORT_STRING);
			return $tags;
			
		}
	}


	/**
	 * Add Record
	 *
	 * @return id of newly created record
	 * @author M. Green
	 * 
	 **/
	public function add_new_record($record = false, $media = false)
	{
		if ($record['title']!=''){

			$this->db->insert('records', $record);
			$last_id = $this->db->insert_id();
			
			for ($i = 0; $i < count($media); $i++) {
				if ($media[$i]['filename'] != "") {
					
					$media[$i]['source_id'] = $last_id;
					$this->add_media_item($media[$i]);
					
				}
			}
			
			return $last_id;
			
		} else {
		
			return -1;
		}
	}
	
	/**
	 * Update Record
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function update_record()
	{
		$id = $this->input->post('id', TRUE);
		
		//update record data
		$data = array(
			'date_string' => $this->input->post('date_string', TRUE),
			'sort_date' => $this->input->post('sort_date', TRUE),
			'sort_string' => $this->input->post('sort_string', TRUE),
			'record_type' => $this->input->post('record_type', TRUE),
			'title' => $this->input->post('title', TRUE),
			'description' => $this->input->post('description', TRUE),
			'author' => $this->input->post('author', TRUE),
			'provenance' => $this->input->post('provenance', TRUE),
			'tags' => $this->input->post('tags', TRUE),
			'is_new' => $this->input->post('is_new', TRUE)
		);
		
		$this->db->where('id', $id);
		$this->db->update('records', $data);
		
		//update media data
		for ($i = 0; $i <= $this->input->post('media_num', TRUE); $i++){
		
			$media_id = $this->input->post('item_'.$i, TRUE);
			
			$media = array(
				'source_id' => $id, 
				'filename'  => $this->input->post('filename_'.$i, TRUE), 
				'type'  => $this->input->post('media_type_'.$i, TRUE), 
				'order' => $this->input->post('order_'.$i, TRUE), 
				'notes' => $this->input->post('notes_'.$i, TRUE), 
			);
			
			// check if there is a new one.
			if ($media_id < 0 && $this->input->post('filename_'.$i, TRUE) != "") {
				$this->add_media_item($media);
			} else {
				$this->db->where('id', $media_id);
				$this->db->update('media', $media);
			}
		}
	}	
	/**
	 * Update Tag
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function update_tag($id, $tag_value)
	{
		if ($id !="" && $tag_value!=""){
			//update record data
			$data = array(
				'tags' => $tag_value
			);
			
			$this->db->where('id', $id);
			$this->db->update('records', $data);
		}
		
		return true;

	}	

	/**
	 * Update Tag
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function get_is_new()
	{
		$records_table = $this->collection['records'];
		
		$query  =	$this->db->select('*')
							 ->where('is_new >=', '1')
							 ->get($records_table);
	
		if ($query->num_rows() > 0)
		{	
			$result = $query->result_array();
			return $result;
		} else {
			return false;
		}		

	}

	/**
	 * Update Is New
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function update_is_new($id, $tag_value)
	{
		if ($id !="" && $tag_value!=""){
			//update record data
			$data = array(
				'is_new' => $tag_value
			);
			
			$this->db->where('id', $id);
			$this->db->update('records', $data);
		}
		
		return true;

	}	
	
	/**
	 * Add Media Item
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function add_media_item($data = false)
	{
		$this->db->insert('media', $data);
	}	
	
	/**
	 * Delete Record
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function delete_record($id)
	{

	}
	
	/**
	 * Delete Media Entry
	 *
	 * @return success
	 * @author M. Green
	 * 
	 **/
	public function delete_media_entry($id)
	{

	}	

}
