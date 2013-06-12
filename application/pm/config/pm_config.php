<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * 
 * Date Format Constants
 *
 * ----------------------------------------------------------------------------
 */

 define('SCI_ISSUE_DATE','d F Y');

/*
 * ----------------------------------------------------------------------------
 * 
 * Configuration Data for the Publishing Master Application 
 *
 * ----------------------------------------------------------------------------
 */

$config['journals'] = array(
  'sci' => array(
    'id' => 0,
    'doi_id' => '10.1126/science',
    'name' => 'Science Magazine',
   //'article_xml_root' => '../xml/HIGHWIRE/',
   
   
	  'article_xml_root' => (defined('ENVIRONMENT') && ENVIRONMENT == 'development') ?
    //  'application/pm/issue_xml/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/'
    '../xml/HIGHWIRE/340/ISSUE_FULL_MONTH/ISSUE_DAY ISSUE_FULL_MONTH--ISSUE_NUM/'
   : '/xml/HIGHWIRE/340/ISSUE_FULL_MONTH/ISSUE_DAY ISSUE_FULL_MONTH--ISSUE_NUM/',
	
    'publish_day' => 'Friday',
    'data_source' => array(
      array(
        'name' => 'Articles',
        'path' => 'final_xml/'
      ),
      array(
        'name' => 'ScienceXpress',
        'path' => 'express/xml/'
      ),
    ),			
    'ignore_list' => array(
      'go.xml'
    ), 
    'ext' => ".xml",
    'data_path' => "application/pm/data/issueData.txt",
    'cover_base' => 'http://www.sciencemag.org/content/VOLUME_NUM/ISSUE_NUM/local/cover-enclosure.gif',
    'article_base_path' => 'http://www.sciencemag.org/content/VOLUME_NUM/ISSUE_NUM/PAGE.VARIANT',
    'cover_caption' => 'peripherals/covercap.htslp',
  ),
  'sig' => array(
    'id' => 1,
    'doi_id' => '10.1126/scisignal',
    'name' => 'Science Signaling',
    //'article_xml_root' => '/home/highwire/Stke/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DATE__ISSUE_NUM/',
    
	  'article_xml_root' => (defined('ENVIRONMENT') && ENVIRONMENT == 'development') ?
    //  'application/pm/issue_xml/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/'
    '../xml/HIGHWIRE/Stke/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY__ISSUE_NUM/'
   : '/home/highwire/Stke/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY__ISSUE_NUM/',
	
	
    'publish_day' => 'Tuesday',
    'data_source' => array(
      array(
        'name' => 'Articles',
        'path' => 'e_media/xml/'
      ),
    ),
    'ext' => ".xml",
    'data_path' => "application/pm/data/issueData-sig.txt",
    'cover_base' => 'http://stke.sciencemag.org/content/volVOLUME_NUM/issueISSUE_NUM/cover_toc.gif',
		'article_base_path' => 'http://stke.sciencemag.org/cgi/content/VARIANT/sigtrans;VOLUME_NUM/ISSUE_NUM/PAGE',
    'cover_caption' => 'VOLUME_NUMISSUE_NUM.covercaption.html',
  ),
  'stm' => array(
    'id' => 2,
    'doi_id' => '10.1126/scitranslmed',
    'name' => 'Science Translational Medicine',
    //'article_xml_root' => '../xml/SciTM/',
   'article_xml_root' => (defined('ENVIRONMENT') && ENVIRONMENT == 'development') ?
    //  'application/pm/issue_xml/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/'
    '../xml/HIGHWIRE/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/'
   : '/home/highwire/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/',
    'publish_day' => 'Wednesday',
    'data_source' => array(
      array(
        'name' => 'Articles',
        'path' => 'e-media/xml/'
      ),
    ),
    'ext' => ".xml",
    'data_path' => "application/pm/data/issueData-stm.txt",
    'cover_base' => 'http://stm.sciencemag.org/content/VOLUME_NUM/ISSUE_NUM.cover.gif',
    'article_base_path' => 'http://stm.sciencemag.org/content/VOLUME_NUM/ISSUE_NUM/PAGE.VARIANT',
    'cover_caption' => 'VOLUME_NUM_ISSUE_NUM.covercaption.html',
  ),
  'news' => array(
    'id' => 3,
    'name' => 'ScienceNOW',
    'publish_day' => 'Today',
  )
);

// Indicates the next VARIANT view state
$config['next_access_level']  = array(
	'teaser' => 'abstract',
	'web-summary' => 'abstract',
	'excerpt' => 'full',
	'full' => 'full',
);
// Points to the next greater access level for an article 
$config['next_access_level']  = array(
  'teaser' => 'abstract',
  'web-summary' => 'abstract',
  'excerpt' => 'full',
  'full' => 'full',
);
// Content-display priority definitions
$config['teaser_options'] = array(
	'A' => array ("teaser"),
	'B' => array ("teaser", "full"),
	'C' => array ("full"),
	'D' => array ("web-summary", "excerpt", "full"),
);

// include other individual config files
include 'application/pm/config/sci_alert_config.php';
include 'application/pm/config/stm_alert_config.php';
include 'application/pm/config/sig_alert_config.php';
include 'application/pm/config/news_alert_config.php';
