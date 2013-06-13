<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['appfeeds'] = array(
  array(
    'id' => 'SCIENCE',
    'publication' => 'sci',
    'description' => 'Up to the minute news and features from Science.',
    'data_source' => 'Articles',
    'name' => 'Science Online',
    'abstract' => 'teaser',
    'filters' => array('article_type' => '^((?!Letters).)*$'),
    'sort' => 'fpage',
    'output' => APPPATH."output/appfeeds/science_ISSUE_NUM.xml",
     'output_config' => APPPATH."output/appfeeds/vconfig.xml",
  ),
  
  
  array(
    'id' => 'STM',
    'publication' => 'stm',
    'description' => 'Up to the minute news and features from Science.',
    'data_source' => 'Articles',
    'name' => 'Science Online',       
    'filters' => array(
      //'overline' => 'DIAGNOSIS',
      //'title' => 'C'
    ),
    'sort' => 'article_type',
    
    'thumbnail_path'=> APPPATH."../xml/HIGHWIRE/SciTM/ISSUE_FULL_YEAR_ISSUE_MONTH_ISSUE_DAY_ISSUE_NUM/e-media/
    images/thumbnails/VOLUME_NUMfpage_th1.gif",
    
    'output' => APPPATH."output/appfeeds/stm_ISSUE_NUM.xml",
    
	 'output_config' => APPPATH."output/appfeeds/vconfig.xml",
		
	),
  
    array(
    'id' => 'SIGNALING',
    'publication' => 'sig',  
    'data_source' => 'Articles',
    'name' => 'Science Online',
    'description' => 'Up to the minute news and features from Science.', 
    
    'filters' => array(
      //'overline' => 'DIAGNOSIS',
      //'title' => 'C'
    ),
    'sort' => 'fpage', 
    'output' => APPPATH."output/appfeeds/signaling_ISSUE_NUM.xml",	
     'output_config' => APPPATH."output/appfeeds/vconfig.xml",
  ),
  
  
   
  
);

