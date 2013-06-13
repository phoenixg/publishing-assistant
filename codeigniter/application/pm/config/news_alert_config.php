<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
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
$config['alerts']['news'] = array(
  'sci-now-daily' => array(
    'ads' => array(),
    'editors_notes' => array(
      'Editorial' => 'News_Editors_Note.html',
      'Subheading' => 'Sub_Heading.html',
    ),
    'eloqua' => array(
      'subject' => 'ScienceNOW Daily Email Alert',
      'eloqua_short_name' => 'Science News Daily - ',
      'eloqua_group_id' => '40',
      'eloqua_from' => 'Science News Daily Headlines'
    ),
    'publication' => 'Science News',
    'name' => 'Daily Headlines',
    'template' => 'templates/alert/main_one_column',
    'outpath' => "application/pm/output/alerts/news/sci-now-daily",
    'content' => array(
      array('type' => 'view', 'location' => 'templates/header/template_header_daily_news', 'target' => 'header'),
      array('type' => 'file',
            'location' => './application/pm/output/ads/news_sci-now-daily_Sub_Heading.html',
            'target' => 'main'),
      array('type' => 'file',
            'location' => './application/pm/output/ads/news_sci-now-daily_News_Editors_Note.html',
            'target' => 'main'),
      array('type' => 'feed', 
      'location' => 'http://news.sciencemag.org/rss/daily_news_email.xml', 
      'target' => 'main', 
      'article_count' => 20, 
      'view' => 'templates/rss/news_body'),
  ),
  'order' => array()
),
'sci-now-weekly' => array(
  'ads' => array(),
  'editors_notes' => array(
      'Editorial' => 'News_Editors_Note.html',
      'Subheading' => 'Sub_Heading.html',
    ),
   'eloqua' => array(
    'subject' => 'Science News Weekly Alert',
    'eloqua_short_name' => 'Science News Weekly - ',
    'eloqua_group_id' => '41',
    'eloqua_from' => 'Science News Weekly Headlines'
  ),
  'publication' => 'Science News',
  'name' => 'Weekly Headlines',
  'template' => 'templates/alert/main_one_column',
  'outpath' => "application/pm/output/alerts/news/sci-now-weekly",
  'content' => array(
      array('type' => 'view', 'location' => 'templates/header/template_header_daily_news', 'target' => 'header'),
      array('type' => 'file',
            'location' => './application/pm/output/ads/news_sci-now-weekly_Sub_Heading.html',
            'target' => 'main'),
      array('type' => 'file',
            'location' => './application/pm/output/ads/news_sci-now-weekly_News_Editors_Note.html',
            'target' => 'main'),
      array('type' => 'feed', 
        'location' => 'http://news.sciencemag.org/rss/weekly_news_email.xml', 
        'target' => 'main', 
        'article_count' => 20, 
        'view' => 'templates/rss/news_body'),
  ),
  'order' => array()
)

    
);

