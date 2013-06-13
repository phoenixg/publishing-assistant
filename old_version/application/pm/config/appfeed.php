<?php 
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
    'output' => APPPATH."output/appfeeds/stm_ISSUE_NUM.xml",	
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
  ),
  
  
   
  
);

