<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * 
 * Configuration Data for the Publishing Master Application 
 *
 * CONTROLLER:  alertMaster.php
 *
 * ----------------------------------------------------------------------------
 */
$config['alerts']['stm'] = array(
  'etoc' => array(
    'publication' => 'Science Translational Medicine',
    'name' => 'Table of Contents',
    'sub_heading' => "In this week's issue:",
    'template' => 'templates/alert/main_two_column',
    'data_source' => 'Articles',
    'teaser_option' => 'A',
    'outpath' => "application/pm/output/alerts/stm/etoc",
    'eloqua' => array(						  					  
      'subject' => 'Sci Transl Med Table of Contents for ISSUE_DATE_SCIENCE; Vol. VOLUME_NUM, No. ISSUE_NUM',
      'eloqua_short_name' => 'STM TOC - ',
      'eloqua_group_id' => '43',
      'eloqua_from' => 'Science Translational Medicine Table of Contents',
    ),
    'editors_notes' => array(
      'no-section' => 'Stm_Editors_Note.html',
    ),
    'content' => array(
			array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
			array('type' => 'file', 'location' => './application/pm/output/ads/stm_etoc_Stm_Editors_Note.html', 'target' => 'main'),
      array('type' => 'var',  'location' => 'body', 'target' => 'main'),
      array('type' => 'view', 'location' => 'templates/menu/submenu_stm', 'target' => 'side'),
    ),
    'order' => array(
      //section
			'Research Articles' => array(
        'Research Article' => array(
          'display' => 'full',
        ),
      ),
			'Reports' => array(
        'Report' => array(
          'display' => 'full',
        ),
      ),
			'Editorial' => array(
        'Editorial' => array(
          'display' => 'full',
        ),
      ),
			'Commentary' => array(
        'Commentary' => array(
          'display' => 'full',
        ),
      ),
			'Focus' => array(
        'Focus' => array(
          'display' => 'full',
        ),
      ),
			'Perspective' => array(
        'Perspective' => array(
          'display' => 'full',
        ),
      ),
			'Review' => array(
        'Review' => array(
          'display' => 'full',
        ),
      ),
			'State of the Art Review' => array(
        'State of the Art Review' => array(
          'display' => 'full',
        ),
      ),
			'Podcast' => array(
        'Podcast' => array(
          'display' => 'full',
        ),
      ),
			'Editors\' Choice' => array(
        'Editors\' Choice' => array(
          'display' => 'full',
        ),
      ),
			'Letter' => array(
        'Letter' => array(
          'display' => 'full',
        ),
				'Letter Response' => array(
          'display' => 'full',
        ),
      ),
			'Meeting Report' => array(
        'Meeting Reports' => array(
          'display' => 'full',
        ),
      ),
			'Errata' => array(
        'Errata' => array(
          'display' => 'full',
        ),
				'Erratum' => array(
          'display' => 'full',
        ),
      ),
    ), 
  ),       
);
