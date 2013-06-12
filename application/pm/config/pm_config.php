<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Date Format Constants
|--------------------------------------------------------------------------
|
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

    'article_xml_root' => (defined('ENVIRONMENT') && ENVIRONMENT == 'development') ?
      'application/pm/issue_xml/VOLUME_NUM/ISSUE_FULL_MONTH/ISSUE_DAY ISSUE_FULL_MONTH--ISSUE_NUM/' 
      : 'application/pm/issue_xml/VOLUME_NUM/ISSUE_FULL_MONTH/ISSUE_DAY ISSUE_FULL_MONTH--ISSUE_NUM/',

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

  ),
  'sig' => array(
    'id' => 1,
    'doi_id' => '10.1126/scisignal',
    'name' => 'Science Signaling',
    'article_xml_root' => '/home/highwire/Stke/',
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
		'article_base_path' => 'http://stke.sciencemag.org/cgi/content/VARIANT/sigtrans;VOLUME_NUM/ISSUE_NUM/PAGE',

  ),
  'stm' => array(
    'id' => 2,
    'doi_id' => '10.1126/scitranslmed',
    'name' => 'Science Translational Medicine',
  //  'article_xml_root' => '../xml/SciTM/',
    'article_xml_root' => '/home/highwire/SciTM/',
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

  ),
  'news' => array(
    'id' => 3,
    'name' => 'ScienceNOW',
    'publish_day' => 'Today',
  )


);

// Points to the next greater access level for an article 
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


//config array for alerts, output and ad placements.
// The alertMaster uses this array in order to build the list of alerts avalible to the user, and once an alert is chosen
//to be compiled, it will again use this array to place ads, order the alert, etc
include 'application/pm/config/sci_alert_config.php';
include 'application/pm/config/stm_alert_config.php';
include 'application/pm/config/sig_alert_config.php';
include 'application/pm/config/news_alert_config.php';


