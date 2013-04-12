<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publish extends Controller {

	public $mappings = array();
	public $header = "";
	public $footer = "";
	
	public function __construct()
	{
		parent::__construct();
		$this->load->config('test_app');
		$this->mappings = $this->config->item('mappings');
		$this->header = file_get_contents(APPPATH . "includes\publish-header.htm");
		$this->footer = file_get_contents(APPPATH . "includes\publish-footer.htm");
		$this->date_epoq = 1930;
		$this->alpha_sections = "abcd|efg|hijk|lmn|opqr|stuvwxyz";

	}

	/**
	 * index
	 *
	 * @return void
	 * @author MGreen
	 **/
	function index()
	{
		redirect('publish/main');
	}

	/**
	 * View Main
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function main()
	{
		$this->load->model('collection_model');	
		
		$data['tags'] = $this->collection_model->get_tags();
		$data['content'] = $this->load->view('publish/main', $data, true);
		$this->load->view('template', $data);
	}
	
	/**
	 * Publishes sections chosen in the FORM as new HTML files, directly to the *LIVE* site.
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_proc()
	{	
		
		$data['msg'] = array();
		
		if ($this->input->post('submit')) {
			// PAPERS
			if ($this->input->post('papers')) {
				$data['msg'][] = $this->publish_date_section($this->input->post('papers'), "decade");
			}
			// PHOTOS
			if ($this->input->post('photos')) {
				$data['msg'][] = $this->publish_date_section($this->input->post('photos'), "decade");
			}
			// ORAL HISTORIES
			if ($this->input->post('oral')) {
				$data['msg'][] = $this->publish_alpha_section($this->input->post('oral'));
			}
			// PROGRAMS
			if ($this->input->post('programs')) {
				$data['msg'][] = $this->publish_date_section($this->input->post('programs'), "single");
			}
			// FILM RADIO AND TELEVISION
			if ($this->input->post('filmrt')) {
				$data['msg'][] = $this->publish_date_section_unit($this->input->post('filmrt'), "unit");
			}
			// WHATS NEW
			if ($this->input->post('whats_new')) {
				$data['msg'][] = $this->publish_whats_new($this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day'));
			}
			//PUBLISH BY TAGS
			if ($this->input->post('tags')) {
				$data['msg'][] = $this->publish_tags($this->input->post('tag-list'));
			}
		}
		$data['content'] = $this->load->view('publish/report', $data, true);
		$this->load->view('template', $data);
	}

	/**
	 * Publishes sections chosen in the FORM as new HTML files, directly to the *LIVE* site.
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_ref()
	{	
		
		$data['msg'] = array();
		
		// PAPERS
		$data['msg'][] = $this->publish_date_section(1, "decade", true);

		// PHOTOS
		$data['msg'][] = $this->publish_date_section(2, "decade", true);

		// ORAL HISTORIES
		$data['msg'][] = $this->publish_alpha_section(3, true);

		// PROGRAMS
		$data['msg'][] = $this->publish_date_section(4, "single", true);

		// FILM RADIO AND TELEVISION
		$data['msg'][] = $this->publish_date_section_unit(5, "unit", true);

		$data['content'] = $this->load->view('publish/report', $data, true);
		$this->load->view('template', $data);
	}


	/**
	 * Publishes a file for each tag in a supplied array. Each file is not a complete web page, but is formatted correctly, so can be used as an include on a page.
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function reset_is_new()
	{

		$this->load->model('collection_model');	
		
		$i = 0;
		
		// get record group for this tag
		$records = $this->collection_model->get_is_new();		
		
		// loop through each type in the group, build the body
		if ($records) {
			foreach ($records as $record) {
				
				$this->collection_model->update_is_new($record["id"], "0");
				$i++;
			}	
			$data['msg'][] = $i . ' flags were reset.';
		} else {
			$data['msg'][] = 'There were no flags to reset.';
		}
		
		$data['content'] = $this->load->view('publish/report', $data, true);
		$this->load->view('template', $data);		
		
	}
	
	/**
	 * Publishes a file for each tag in a supplied array. Each file is not a complete web page, but is formatted correctly, so can be used as an include on a page.
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_tags($tags)
	{

		$this->load->model('collection_model');	

		$fc = 0;
		
		foreach($tags as $tag) {
		
			$body = "";
			$i = 1;
			
			// get record group for this tag
			$record_group = $this->collection_model->get_records_by_tagname($tag);			
			
			// loop through each type in the group, build the body
			foreach ($record_group as $records) {
				
				if (count($records) > 0) {
					//build the body content
					$body .= "<h6>" . ucwords($this->mappings[0][$i]['name']) . "</h6>\n";
					$body .= $this->build_body($records, $this->mappings[0][$i]['format'], $i);
				}
				
				$i++;
			}

			//write the file
			$filename = PUBLISHROOT . "resources/" . $tag . ".php";
			$this -> write_page($filename, $body, false);
			
			$fc++;
			
		}

		return $fc . ' files were written to the museum resources folder.';
	}

	/**
	 * Publishes indicated section, with all records found in collection
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_alpha_section($record_type, $ref=false)
	{

		$this->load->model('collection_model');	
	
		$refdir = "";
		$msg = "";
		$section = $this->mappings[0][$record_type]['name'];
		$section_format = $this->mappings[0][$record_type]['format'];
		$record_count = 0;
		$file_count = 0;

		$groups = explode("|", $this->alpha_sections);	//create array of  strings, to identify which bin to put the items in.
		$bins = array();								//array of all the 'bins' of records, one for each group.
		$labels = array();								//labels for the navigation tabs
		
		// add a bin and calculate the labels for each of the alphabet groups
		foreach ($groups as $group) {
			array_push($bins, array());
			array_push($labels, array('name' => strtoupper(substr($group, 0, 1))." - ".strtoupper(substr($group, -1)), 'ref' => substr($group, 0, 1)."-".substr($group, -1)));
		}
				
		// get all records of the given type, we don't care about the date range, just get everything.
		//because we know we're dealing with alphabetical lists, we will sort the results by the 'sort string" column in the database
		$records = $this->collection_model->get_records_by_type($record_type, "sort_string");
		
		// go through all the records and put them in the right 'bin'
		foreach ($records as $row) {
			$idx = strtolower(substr($row['sort_string'], 0, 1)); 
			for ($i=0; $i<count($groups); $i++) {
				if (strpos($groups[$i], $idx) !== false) {
					array_push($bins[$i], $row);
					break;
				}
			}
		}
		
		//for each of the bins,  write the page.
		for($i=0; $i<count($bins); $i++) {
			
 			$body = $this->write_header($section);
			
			//build the navigation
			if (!$ref) {
				$body .= $this -> get_nav($labels, $labels[$i]['ref'], $section);
			}else{
				$body .= $this -> get_nav($labels, $labels[$i]['ref'], $section, "ref/");
			}
			
			//record count
			$body .=  "<p>" . count($bins[$i]) . " records in this section.</p>";
			
			// write the indivcidual records
			if (!$ref) {
				$body .= $this->build_body($bins[$i], $section_format, $record_type);
			} else {
				$body .= $this->build_ref_body($bins[$i], $section_format, $record_type);
				$refdir = "ref\\";
			}
								
			//write the file
			$filename = PUBLISHROOT . $refdir . strtolower(str_replace(" ", "-", $section)) . '\\' . str_replace(" ", "", $labels[$i]['ref']) . "\\index.php";
			$this -> write_page($filename, $body, true);
			
			//keept track of how many records are written
			$record_count += count($bins[$i]);
			$file_count++;			
			
		}
		
		// output the summary report
		return $file_count . " <b>" . $section . "</b> files were processed with a total of " . $record_count . " records.";
		
	}

	/**
	 * Publishes  complete section, with all records  of specified type
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_date_section($record_type, $list_type, $ref=false)
	{
		$this->load->model('collection_model');
		
		$msg = "";
		$refdir = "";
		$section = $this->mappings[0][$record_type]['name'];
		$section_format = $this->mappings[0][$record_type]['format'];
		$sort_order = $this->mappings[0][$record_type]['sort-order'];
		$record_count = 0;
		$file_count = 0;		

		$labels = array();
		
		// get range for data we're dealing with.	
		$range = $this->collection_model->get_date_range($record_type);
		
		//work out the limits for the pages we're going to write, based on the the type of list we're making.
		if ($list_type == "decade") {
			$year_start = $this->date_epoq - 10;
			$year_end = $range["max_decade"];
			$incr = 10;
			$suffix = "s";
		} else {
			$year_start = $range["min_year"];
			$year_end = $range["max_year"];
			$incr = 1;
			$suffix = "";
		}
		
		//create an array of all the labels for the pages. This is used for the navigation
		for ($i=$year_start; $i<=$year_end; $i+=$incr) {
			if ($i < $this->date_epoq) {
				array_push($labels, array('name' => "pre" . $this->date_epoq . $suffix, 'ref' => "pre" . $this->date_epoq));
			} else {
				array_push($labels, array('name' => $i . $suffix, 'ref' => $i));
			}
		}
		
		// loop over each section
		for ($i=$year_start; $i<=$year_end; $i+=$incr) {
			
 			$body = $this->write_header($section);
			
			//work out the start and end dates for this group, so we can grab them from the db.
			if ($i < $this->date_epoq ) {
				$start = "1700-01-01";
				$selected = "pre" . $this->date_epoq;
			} else {
				$start = $i . "-01-01";
				$selected = $i;
			}
			$end = $i+($incr-1) . "-12-31";
			
			// get records for current decade
			$records = $this->collection_model->get_records_by_date($record_type, $start, $end, $sort_order);
			

			//build the navigation
			if (!$ref) {
				$body .= $this -> get_nav($labels, $selected, $section);
			}else{
				$body .= $this -> get_nav($labels, $selected, $section, "ref/");
			}
			
			if (count($records) > 1)  {
				$append = "s";
			} else {
				$append = "";
			}
			
			// add the record count
			$body .=  "<p>" . count($records) . " record" . $append . " in this section.</p>";
			
			// write the indivcidual records
			if (!$ref) {
				$body .= $this -> build_body($records, $section_format, $record_type);
			} else {
				$body .= $this -> build_ref_body($records, $section_format, $record_type);
				$refdir = "ref\\";
			}
					
			//write the file
			if ($i < $this->date_epoq) {
				$filename = PUBLISHROOT . $refdir . strtolower($section) . '\\pre' . $this->date_epoq . '\\index.php';
			} else {
				$filename = PUBLISHROOT . $refdir . strtolower($section) . '\\' . $i . "\\index.php";
			}
			$this -> write_page($filename, $body, true);
			
			//keept track of how many records are written in total
			$record_count += count($records);
			$file_count++;
			
		}
		
		// output the summary report
		return $file_count . " <b>" . $section . "</b> files were processed with a total of " . $record_count . " records.";
		
	}

	/**
	 * Publishes  complete section, with all records  of specified type
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_date_section_unit($record_type, $list_type, $ref=false)
	{
		$this->load->model('collection_model');
		
		$msg = "";
		$refdir = "";
		$section = $this->mappings[0][$record_type]['name'];
		$path = $this->mappings[0][$record_type]['path'];
		$section_format = $this->mappings[0][$record_type]['format'];
		$sort_order = $this->mappings[0][$record_type]['sort-order'];
		$record_count = 0;
		$file_count = 0;		

		// loop over each section
			
 			$body = $this->write_header($section);
			
			//work out the start and end dates for this group, so we can grab them from the db.
			$start = "1900-01-01";
			$end = "2050-12-31";
			
			// get records for current decade
			$records = $this->collection_model->get_records_by_date($record_type, $start, $end, $sort_order);
			
			if (count($records) > 1)  {
				$append = "s";
			} else {
				$append = "";
			}
			
			// add the record count
			$body .=  "<p>" . count($records) . " record" . $append . " in this section.</p>";
			
			// write the indivcidual records
			if (!$ref) {
				$body .= $this -> build_body($records, $section_format, $record_type);
			} else {
				$body .= $this -> build_ref_body($records, $section_format, $record_type);
				$refdir = "ref\\";
			}
					
			//write the file
			$filename = PUBLISHROOT . $refdir . strtolower($path) . '\\index.php';
			$this -> write_page($filename, $body, true);
			
			//keept track of how many records are written in total
			$record_count += count($records);
			$file_count++;
					
		// output the summary report
		return $file_count . " <b>" . $section . "</b> files were processed with a total of " . $record_count . " records.";
		
	}	
	
	/**
	 * Publishes grouped list of all items that are new in the collection this month.
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function publish_whats_new($date = false)
	{	

		$this->load->model('collection_model');	

		$date .= " 00:00:00";
		
		$pound = "<ul class=\"section-list\">\n";
		$body = "";

		$i = 1;
		
		// get record group for this tag
		//$record_group = $this->collection_model->get_records_by_create_date($date);			
		$record_group = $this->collection_model->get_records_by_new($date);			
		
		// loop through each type in the group, build the body
		foreach ($record_group as $records) {
			
			if (count($records) > 0) {
				
				$section = ucwords($this->mappings[0][$i]['name']);
				
				//build the navigation
				$pound .= "<li><a href=\"#" . $section ."\">" . $section . "</a> (" . count($records) . ")</li>\n";
				
				//build the body content
				$body .= "<h6 id=\"" . $section . "\">" . $section . "</h6>\n";
				$body .= $this->build_body($records, $this->mappings[0][$i]['format'], $i);
			}
			
			$i++;
		}

		$pound .= "</ul>\n\n<hr />\n\n";
		
		//write the file
		$filename = PUBLISHROOT . "resources/whatsnew.php";
		$this -> write_page($filename, $pound . $body, false);
			
		return "What's New file for " . $date . " was written.";	
	
	}
	
	/**
	 * Utility Function
	 *Creates a formatted list of all records
	 *
	 * @returns a string of formatted HTML for all records provided.
	 * @author MGreen
	 **/
	public function build_body($records, $format, $record_type)
	{

		$content = "";
		
		if ($records && count($records > 0)) {

			// Write the records list
			$content .= "<dl class=\"" . $format . "\">\n";
			foreach ($records as $row) {
				$content .= $this->formatter($row, $format, $record_type);
			}
			$content .= "</dl>\n\n";	

		} else {

			$content .= "<p>There are no records for this period.</p>\n\n";

		}
		
		return $content;

	}

	/**
	 * Utility Function
	 *Creates a formatted reference list of all records
	 *
	 * @returns a string of formatted HTML for all records provided.
	 * @author MGreen
	 **/
	public function build_ref_body($records, $format, $record_type)
	{

		$content = "";
		
		if ($records && count($records > 0)) {

			// Write the records list
			$content .= "\n\n<dl class=\"ref-list\">\n";
			foreach ($records as $row) {
				$content .= "<dt><strong>[" . $row['id'] . "]</strong>  " . $row['sort_date'] . "</dt>\n";
				$content .=  "<dd>" . $row['title'] . "</dd>\n";
			}
			$content .= "</dl>\n\n";	

		} else {

			$content .= "<p>There are no records for this period.</p>\n\n";

		}
		
		return $content;

	}

	/**
	 * Utility Function **
	 * Builds a link address - decideds whether it's just a filename, or a path.
	 *
	 * @returns string of the HTML formatted ROW according to the type of list specified.
	 * @author MGreen
	 **/	
	function get_link($str, $type, $extra = "") {

		if ($extra != "") $extra = $extra . "/";
	
		if ((strpos($str, "/") || strpos($str, "/") === 0)) {
			return $str;
		} else {
			$link = "/collection/" . $this->mappings[0][$type]['path'] . "/" . $extra . $str;
			return $link;
		}

	}

	/**
	 * Utility Function **
	 * Format individual rows
	 *
	 * @returns string of the HTML formatted ROW according to the type of list specified.
	 * @author MGreen
	 **/	
	function formatter($row, $format, $type) {

		$this->load->model('collection_model');
		$record_types = $this->collection_model->get_record_types();
		$media_types = $this->collection_model->get_media_types();

		$content = "";
		
		if ($row['date_string'] != "" ) {
			$date_string = htmlspecialchars($row['date_string']);
		} else {
			$date_string = date('d F Y', strtotime($row['sort_date']) );
		}

		$decade = substr($row['sort_date'], 0, 3) . "0";
		
		switch ($type)
		{

			case 1:
				//PAPERS
				$content .= "<dt id=\"ref-" . $row["id"] . "\">" . $date_string ."</dt>\n";
				$content .= "<dd>\n";
				if (count($row['media']) == 1) {
					$content .= "\t<div class=\"item\"><a href=\"". $this->get_link($row['media'][0]['filename'], $type, $decade) . "\" >" . stripslashes($row['title']) . "</a></div>\n";
					if (trim($row['media'][0]['type']) != "") {
						$content .= "\t<span class=\"item-type\">[" . $media_types[$row['media'][0]['type']] . "]</span> \n";
						$content .= "\t<span class=\"item-type\">(" . substr($row['media'][0]['filename'], -3, 3) . ")</span> \n";
					}
					if (trim($row['provenance']) != "") {
						$content .= "\t<span class=\"prov\">(" . $row['provenance'] . ")</span>\n";
					}
				} else {
					$content .= "\t<div class=\"item\"><b>" . stripslashes($row['title']) . "</b></div>\n";
					if (trim($row['provenance']) != "") {
						$content .= "\t<p class=\"prov\">(" . $row['provenance'] . ")</p>\n";
					}
				}
				if (count($row['media']) > 1) {
					$content .= "\t<ul>\n";
					foreach ($row['media'] as $media) {
						if (trim($media['notes'])!= "") {
							$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\"><a href=\"". $this->get_link($media['filename'], $type, $decade) ."\">" . trim($media['notes']) . "</a> [" . $media_types[$media['type']] . "] (" . substr($media['filename'], -3, 3) . ")</li>\n";
						} else {
							$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\"><a href=\"". $this->get_link($media['filename'], $type, $decade) ."\">" . $media_types[$media['type']] . "</a> (" . substr($media['filename'], -3, 3) . ")</li>\n";
						}
					}
					$content .= "\t</ul>\n";
				}				
				$content .= "</dd>\n";
				break;

			case 2:
				//PHOTOS
				// If there's a dsescription, append it to the title in the TITLE tag for the thumbnail image - so that the jQuery title will pick it up for the display.
				if ($row['description'] != "") {
					$img_title = htmlspecialchars($row['title']) . "|" . htmlspecialchars($row['description']);
				} else {
					$img_title = htmlspecialchars($row['title']);
				}
				$content .= "<dt id=\"ref-" . $row["id"] . "\">" . $date_string . "</dt>\n";
				$content .= "<dd>\n";
				$content .= "\t<img src=\"/collection/photos/thumb/" . $row['media'][0]['filename'] . "\" alt=\"\" />\n";
				$content .= "\t<div class=\"content\">\n";
				$content .= "\t<div class=\"item\"><a class=\"nyroModal\" href=\"" . $this->get_link($row['media'][0]['filename'], $type, "full") . "\" title=\"" . $img_title . "\" rel=\"gal\" >" . $row['title'] . "</a></div>\n"; 
				if (trim($row['description']) != "") {
					$content .= "\t<p>" . htmlspecialchars($row['description']) . "</p>\n";
				}
				if (trim($row['provenance']) != "") {
					$content .= "\t<span class=\"prov\">(" . $row['provenance'] ." )</span>\n";
				}
				$content .= "\t</div>\n";
				$content .= "</dd>\n";
				break;
				
			case 3:
				//ORAL HISTORIES
				$content .= "<dt id=\"" . $row['sort_string'] . "\">" . $date_string ."</dt>\n";
				$content .= "<dd>\n";
				if (count($row['media']) == 0) {
					$content .= "\t<h4><a href=\"" . $this->get_link($row['media'][0]['filename'], $type) . "\">" . htmlspecialchars($row['title']) . "</a></h4>\n";
				} else {
					$content .= "\t<h4>" . htmlspecialchars($row['title']) . "</h4>\n";
				}
				$content .= "\t<p>\n";
				if (trim($row['author']) != "") {
					$content .= "\t<span class=\"byline\">With " . $row['author'] . "</span>\n";
				}
				if (trim($row['provenance']) != "") {
					$content .= "\t <span class=\"prov\">(" . $row['provenance'] . ")</span>\n";
				}
				$content .= "\t</p>\n";
				if (count($row['media']) >= 1) {
					$content .= "\t<ul>\n";
					foreach ($row['media'] as $media) {
						$link_string = $media_types[$media['type']];
						$part = trim($media['notes']);
						$ext = substr($media['filename'], -3, 3);
						if ($part != "") {
							$part = " - " . $part;
							$link_string .= $part;
						}
						if ($media_types[$media['type']] == "audio") {
							$ltitle = urlencode(str_replace("\"", "-", $row['title'])) . $part;
							$htitle = htmlspecialchars($row['title']) . $part;
							$typeloc = urlencode($this->mappings[0][$type]['path']);
							$linkstr =  "<a href=\"/museum/extras/audio.php?file=" . urlencode($media['filename']) . "&amp;title=" . $ltitle . "&amp;type=" . $typeloc . "\"  title=\"" . $htitle . "\"  target=\"_blank\" class=\"nyroModal\" >audio" . $part . "</a> (" . $ext . ")";
							} else {
							$linkstr =  "<a href=\"". $this->get_link($media['filename'], $type) . "\">" . $link_string . "</a> (" . $ext . ")";
						}	

						$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\">";
						$content .= $linkstr;
						$content .= "</li>\n";
						
					}
					$content .= "\t</ul>\n";
				}
				$content .= "</dd>\n";
				break;				
				
			case 4:
				//PROGRAMS
				$content .= "<dt id=\"ref-" . $row["id"] . "\">" . $date_string ."</dt>\n";
				$content .= "<dd>\n";
				$content .= "\t<h4>" . $row['title'] . "</h4>\n";
				if (trim($row['author']) != "") {
					$content .= "\t<p>Moderator: " . $row['author'] . "</p>\n";
				}
				if (trim($row['description']) != "") {
					$content .= "\t<p>Presenter(s): " . $row['description'] . "</p>\n";
				}
				if (trim($row['provenance']) != "") {
					$content .= "\t <p class=\"prov\">" . $row['provenance'] . "</p>\n";
				}
				$content .= "\t<ul>\n";
				foreach ($row['media'] as $media) {
					$link_string = $media_types[$media['type']];
					$part = trim($media['notes']);
					$ext = substr($media['filename'], -3, 3);
					if ($part != "") {
						$part = " - " . $part;
						$link_string .= $part;
					}
					if ($media_types[$media['type']] == "audio") {
						$ltitle = urlencode(str_replace("\"", "-", $row['title'])) . $part;
						$htitle = htmlspecialchars($row['title']) . $part;
						$typeloc = urlencode($this->mappings[0][$type]['path']);
						$linkstr =  "<a href=\"/museum/extras/audio.php?file=" . urlencode($media['filename']) . "&amp;title=" . $ltitle . "&amp;type=" . $typeloc . "\"  title=\"" . $htitle . "\"  target=\"_blank\" class=\"nyroModal\" >audio" . $part . "</a> (" . $ext . ")";
					} else {
						$linkstr =  "<a href=\"". $this->get_link($media['filename'], $type) . "\">" . $link_string . "</a> (" . $ext . ")";
					}	

					$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\">";
					$content .= $linkstr;
					$content .= "</li>\n";
					
				}
				$content .= "\t</ul>\n";
				$content .= "</dd>\n";
				break;
				
			case 5:
				//Film Radio and Television
				$content .= "<dt id=\"ref-" . $row["id"] . "\">" . $date_string ."</dt>\n";
				$content .= "<dd>\n";
				if (count($row['media']) == 1) {
					$content .= "\t<div class=\"item\"><a href=\"". $this->get_link($row['media'][0]['filename'], $type) . "\" >" . stripslashes($row['title']) . "</a></div>\n";
					if (trim($row['description']) != "") {
						$content .= "\t<p>" . $row['description'] . "</p>\n";
					}					
					if (trim($row['media'][0]['type']) != "") {
						$content .= "\t<span class=\"item-type\">[" . $media_types[$row['media'][0]['type']] . "]</span> \n";
						$content .= "\t<span class=\"item-type\">(" . substr($row['media'][0]['filename'], -3, 3) . ")</span> \n";
					}
					if (trim($row['provenance']) != "") {
						$content .= "\t<span class=\"prov\">(" . $row['provenance'] . ")</span>\n";
					}
				} else {
					$content .= "\t<div class=\"item\"><b>" . stripslashes($row['title']) . "</b></div>\n";
					if (trim($row['description']) != "") {
						$content .= "\t<p>" . $row['description'] . ")</p>\n";
					}					
					if (trim($row['provenance']) != "") {
						$content .= "\t<p class=\"prov\">(" . $row['provenance'] . ")</p>\n";
					}
				}
				if (count($row['media']) > 1) {
					$content .= "\t<ul>\n";
					foreach ($row['media'] as $media) {
						if (trim($media['notes'])!= "") {
							$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\"><a href=\"". $this->get_link($media['filename'], $type) ."\">" . trim($media['notes']) . "</a> [" . $media_types[$media['type']] . "] (" . substr($media['filename'], -3, 3) . ")</li>\n";
						} else {
							$content .= "\t\t<li class=\"" . $media_types[$media['type']] . "\"><a href=\"". $this->get_link($media['filename'], $type) ."\">" . $media_types[$media['type']] . "</a> (" . substr($media['filename'], -3, 3) . ")</li>\n";
						}
					}
					$content .= "\t</ul>\n";
				}				
				$content .= "</dd>\n";
				break;				

				
			default:
				$content .= "NO FORMAT SPECIFIED FOR OUTPUT: \"" . $format . "\" DUMPING OUTPUT\n";
				break;
		}	
		return $content;
	}
	
	/**
	 * Utility Function **
	 * BuildsTabbed Navigation from an array of labels
	 *
	 * @returns generated HTML for  an unordered list of  links to different years of results. CSS can be used  to style this as tabs.
	 * @author MGreen
	 **/
	function get_nav($labels, $selected, $section, $extra="") {

		$nav = "";
		$section = strtolower(str_replace(" ", "-", $section));

		if ($section == "programs") {
			$nav .= "<ul class=\"tab-list\">\n";
			$nav .= "<li class=\"tab-item on\"><a href=\"/museum/programs/\"><span>By Year</span></a></li>";
			$nav .= "<li class=\"tab-item\"><a href=\"/museum/programs/nera/\">The Best of NERA</a></li>";
			$nav .= "<li class=\"tab-item\"><a href=\"/museum/programs/bingham/\">Bingham Presents</a></li>";
			$nav .= "<li class=\"tab-item\"><a href=\"/museum/programs/deloitte/\">Deloitte Fireside Chats</a></li>";
			$nav .= "</ul>\n\n";
			$nav .= "<ul class=\"inline-list\">\n";
			foreach ($labels as $label) {
				if ($label['ref'] == $selected) {
					$nav = $nav . "<li class=\"item on\"><a href=\"/museum/". $extra . $section ."/" . $label['ref'] . "/\"><span>" . $label['name'] . "</span></a></li>\n";
				} else {
					$nav = $nav . "<li class=\"item\"><a href=\"/museum/" . $extra . $section . "/" . $label['ref'] . "/\">" . $label['name'] . "</a></li>\n";
				}
			}
			$nav .= "</ul>\n\n";
		} else {
			$nav .= "<ul class=\"tab-list\">\n";
			foreach ($labels as $label) {
				if ($label['ref'] == $selected) {
					$nav = $nav . "<li class=\"tab-item on\"><a href=\"/museum/". $extra . $section ."/" . $label['ref'] . "/\"><span>" . $label['name'] . "</span></a></li>\n";
				} else {
					$nav = $nav . "<li class=\"tab-item\"><a href=\"/museum/" . $extra . $section . "/" . $label['ref'] . "/\">" . $label['name'] . "</a></li>\n";
				}
			}
			$nav .= "</ul>\n\n";
		}
		return $nav;
	}
	
	/**
	 * Utility Function **
	 * Retrieves value for a specified mapping from the master config collection
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
			// IF THIS THIS A "TYPE" Field we only need the first character to ID the Field in the database.
			if ($field == "type") {
				$val = $val[0];
			}
			return $val;
		}
	}
	
	/**
	 * Utility Function **
	 * formats the page header correctly
	 *
	 * @return HTML formatting for a page.
	 * @author MGreen
	 **/
	public function write_header($name)
	{

		$content = "<div class=\"breadcrumbs\"><a href=\"/\">Home</a> &gt; " . $name . "</div>\n";
		$content .= "<h3>" . $name ."</h3>\n";

		return $content;
	}

	/**
	 * Utility Function **
	 * Writes Page to the filesystem
	 *
	 * @creates output file to the fiel system, using a global header and footer and the provided body content. Assumed to be HTML
	 * @author MGreen
	 **/	
	function write_page($output_name, $body, $headers=false) {

		$header = "";
		$footer = "";
		
		// check we can open the file.
		if (!$handle = fopen($output_name, "w+")) {
			return "Cannot open file for writing: " . $output_name;
		}
		
		// add the headers if we need them
		if ($headers) {
			$content = $this->header . $body . $this->footer;
		} else {
			$content = $body;
		}

		// write the file
		if (fwrite($handle, $content)) {
			return "Written to: " . $output_name;
		}
	}
	
}