<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * 
 * 
 *
 * ----------------------------------------------------------------------------
 */

	/**
	 * Tables.
	 **/
	$config['collection']['records'] = 'records';
	$config['collection']['media'] = 'media';
	$config['collection']['record_types'] = 'record_types';
	$config['collection']['media_types'] = 'media_types';
	
	/**
	 * Configuration.
	 **/
	$config['mappings'] = array(
		array(
			'0' => 'collection', 
			'1' => array(	'name' => 'Papers', 
							'format' => 'item-list', 
							'path' => 'papers',
							'sort-order' => 'asc'
					),
			'2' => array(	'name' => 'Photos', 
							'format' => 'thumb-list', 
							'path' => 'photos',
							'sort-order' => 'asc'
					),
			'3' => array(	'name' => 'Oral Histories', 
							'format' => 'item-list', 
							'path' => 'oral-histories',
							'sort-order' => 'asc'
					),
			'4' => array(	'name' => 'Programs',
							'format' => 'item-list',
							'path' => 'programs',
							'sort-order' => 'desc'							
					),
			'5' => array(	'name' => 'Film, Radio and Television',
							'format' => 'item-list',
							'path' => 'film-radio-television',
							'sort-order' => 'asc'
					)
		),
		array(
		//Papers [ID =1 ]
			'new' => array('name' => 'new', 'col' => '0', 'default' => ''), 
			'date_string' => array('name' => 'date_string', 'col' => '2', 'default' => ''),
			'sort_date' => array('name' => 'sort_date', 'col' => '1', 'default' => ''),
			'sort_string' => array('name' => 'sort_string', 'col' => '-1', 'default' => ''),
			'title' => array('name' => 'title', 'col' => '3', 'default' => ''),
			'author' => array('name' => 'author', 'col' => '-1', 'default' => ''),
			'description' => array('name' => 'description', 'col' => '4', 'default' => ''),
			'provenance' => array('name' => 'provenance', 'col' => '5', 'default' => ''),
			'tags' => array('name' => 'tags', 'col' => '9', 'default' => ''),
			'filename' => array('name' => 'filename', 'col' => '6', 'default' => ''),
			'type' => array('name' => 'type', 'col' => '7', 'default' => ''),
			'order' => array('name' => 'order', 'col' => '8', 'default' => ''),
			'notes' => array('name' => 'notes', 'col' => '10', 'default' => '')
		),
		array(
		//Photos [ID = 2]
			'new' => array('name' => 'new', 'col' => '0', 'default' => ''), 
			'date_string' => array('name' => 'date_string', 'col' => '2', 'default' => ''),
			'sort_date' => array('name' => 'sort_date', 'col' => '1', 'default' => ''),
			'sort_string' => array('name' => 'sort_string', 'col' => '3', 'default' => ''),
			'title' => array('name' => 'title', 'col' => '4', 'default' => ''),
			'author' => array('name' => 'author', 'col' => '-1', 'default' => ''),
			'description' => array('name' => 'description', 'col' => '5', 'default' => ''),
			'provenance' => array('name' => 'provenance', 'col' => '6', 'default' => ''),
			'tags' => array('name' => 'tags', 'col' => '10', 'default' => ''),
			'filename' => array('name' => 'filename', 'col' => '7', 'default' => ''),
			'type' => array('name' => 'type', 'col' => '8', 'default' => ''),
			'order' => array('name' => 'order', 'col' => '9', 'default' => ''),
			'notes' => array('name' => 'notes', 'col' => '11', 'default' => '')
		),
		array(
		//Oral Histories [ID=3]
			'new' => array('name' => 'new', 'col' => '0', 'default' => ''), 
			'date_string' => array('name' => 'sort_name', 'col' => '-1', 'default' => ''),
			'sort_date' => array('name' => 'date', 'col' => '1', 'default' => ''),
			'sort_string' => array('name' => 'sort_string', 'col' => '2', 'default' => ''),
			'title' => array('name' => 'interviewee', 'col' => '3', 'default' => ''),
			'author' => array('name' => 'interviewer', 'col' => '4', 'default' => ''),
			'description' => array('name' => 'description', 'col' => '-1', 'default' => ''),
			'provenance' => array('name' => 'provenance', 'col' => '5', 'default' => ''),
			'tags' => array('name' => 'tags', 'col' => '9', 'default' => ''),
			'filename' => array('name' => 'filename', 'col' => '6', 'default' => ''),
			'type' => array('name' => 'type', 'col' => '7', 'default' => ''),
			'order' => array('name' => 'order', 'col' => '8', 'default' => ''),
			'notes' => array('name' => 'notes', 'col' => '10', 'default' => '')
		),
		array(
		//Programs [ID = 4]
			'new' => array('name' => 'new', 'col' => '0', 'default' => ''), 
			'date_string' => array('name' => 'date_string', 'col' => '-1', 'default' => ''),
			'sort_date' => array('name' => 'sort_date', 'col' => '1', 'default' => ''),
			'sort_string' => array('name' => 'sort_string', 'col' => '-1', 'default' => ''),
			'title' => array('name' => 'title', 'col' => '2', 'default' => ''),
			'author' => array('name' => 'moderator', 'col' => '3', 'default' => ''),
			'description' => array('name' => 'panelists', 'col' => '4', 'default' => ''),
			'provenance' => array('name' => 'provenance', 'col' => '-1', 'default' => ''),
			'tags' => array('name' => 'tags', 'col' => '8', 'default' => ''),
			'filename' => array('name' => 'filename', 'col' => '5', 'default' => ''),
			'type' => array('name' => 'type', 'col' => '6', 'default' => ''),
			'order' => array('name' => 'order', 'col' => '7', 'default' => ''),
			'notes' => array('name' => 'notes', 'col' => '9', 'default' => '')
		),
		array(
		//Film Radio and Television [ID = 5]
			'new' => array('name' => 'new', 'col' => '0', 'default' => ''), 
			'date_string' => array('name' => 'date_string', 'col' => '2', 'default' => ''),
			'sort_date' => array('name' => 'sort_date', 'col' => '1', 'default' => ''),
			'sort_string' => array('name' => 'sort_string', 'col' => '-1', 'default' => ''),
			'title' => array('name' => 'title', 'col' => '3', 'default' => ''),
			'author' => array('name' => 'author', 'col' => '-1', 'default' => ''),
			'description' => array('name' => 'description', 'col' => '4', 'default' => ''),
			'provenance' => array('name' => 'provenance', 'col' => '5', 'default' => ''),
			'tags' => array('name' => 'tags', 'col' => '9', 'default' => ''),
			'filename' => array('name' => 'filename', 'col' => '6', 'default' => ''),
			'type' => array('name' => 'type', 'col' => '7', 'default' => ''),
			'order' => array('name' => 'order', 'col' => '8', 'default' => ''),
			'notes' => array('name' => 'notes', 'col' => '10', 'default' => '')
		),		
	);
	
?>