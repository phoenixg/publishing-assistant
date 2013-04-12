<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * 
 * Configuration Data for the Publishing Master Application 
 *
 * ----------------------------------------------------------------------------
 */


//config array for alerts, output and ad placements.
// The alertMaster uses this array in order to build the list of alerts avalible to the user, and once an alert is chosen
//to be compiled, it will again use this array to place ads, order the alert, etc
//config array for alerts, output and ad placements.
// The alertMaster uses this array in order to build the list of alerts avalible to the user, and once an alert is chosen
//to be compiled, it will again use this array to place ads, order the alert, etc



$config['alerts']['sig'] = array(
  'etoc' => array(						  
    'eloqua' => array(
      'subject' => 'Science Signaling Table of Contents for ISSUE_DATE_SCIENCE; Volume VOLUME_NUM, Issue ISSUE_NUM',
      'eloqua_short_name' => 'Signaling TOC - ',
      'eloqua_group_id' => '42',
      'eloqua_from' => 'Science Signaling Table of Contents',
    ),
    'editors_notes' => array(
      'Alert_Box' => 'Sig_Alert_Box.html',
						//this corrisponds to the _editorialBookends, if front is set, this will show up
						//right after the subheading, end will anchor it to the bottom. 
      'end' => 'Sig_KE_Content.html',
    ),
    'publication' => 'Science Signaling',
    'name' => 'Table of Contents',
    'sub_heading' => "In this week's issue:",
    'data_source' => 'Articles',
    'template' => 'templates/alert/main_two_column',
    'outpath' => "application/pm/output/alerts/sig/etoc",
    'teaser_option' => 'A',
    'content' => array(
						array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
						array('type' => 'file', 'location' => './application/pm/output/ads/sig_etoc_Sig_Alert_Box.html', 'target' => 'main'),
      array('type' =>'var', 'location' => 'body', 'target' => 'main'),
      array('type' => 'view', 'location' => 'templates/menu/submenu_sig', 'target' => 'side'),
    ),
    'order' => array(
      //section
      'Subheading' => array(
        'Subheading' => array(
          'display' => 'full',
        ),
      ),
      'ST NetWatch' => array(
        'ST NetWatch' => array(
          'display' => 'full',
        ),
      ),
						'Editorial Guides' => array(
        'Editorial Guide' => array(
          'display' => 'full',
        ),
      ),
						'Research Articles' => array(
        'Research Article' => array(
          'display' => 'full',
        ),
      ),
						'Research Resources' => array(
        'Research Resource' => array(
          'display' => 'full',
        ),
      ),
						'Perspectives' => array(
        'Perspective' => array(
          'display' => 'full',
        ),
      ),
						'Reviews' => array(
        'Review' => array(
          'display' => 'full',
        ),
      ),
						'Connections Maps Overviews' => array(
        'Connections Maps Overview' => array(
          'display' => 'full',
        ),
      ),
						'Protocols' => array(
        'Protocols' => array(
          'display' => 'full',
        ),
      ),
						'Letters' => array(
        'Letter' => array(
          'display' => 'full',
        ),
      ),
						'Journal Club' => array(
        'Journal Club' => array(
          'display' => 'full',
        ),
      ),
						'Meeting Reports' => array(
        'Meeting Report' => array(
          'display' => 'full',
        ),
      ),
						'Presentations' => array(
        'Presentation' => array(
          'display' => 'full',
        ),
      ),
						'Teaching Resources' => array(
        'Teaching Resource' => array(
          'display' => 'full',
        ),
      ),
						'Podcasts' => array(
        'Podcast' => array(
          'display' => 'full',
        ),
      ),
						'Editors\' Choice' => array(
        'Editors\' Choice' => array(
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

