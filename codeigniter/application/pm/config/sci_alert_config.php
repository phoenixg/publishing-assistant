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



$config['alerts']['sci'] = array(
  'etoc' => array(
    'eloqua' => array(
      'subject' => 'Science Table of Contents for ISSUE_DATE_SCIENCE; Vol. VOLUME_NUM, No. ISSUE_NUM',
      'eloqua_short_name' => 'Science TOC - ',
      'eloqua_group_id' => '38',
      'eloqua_from' => 'Science Table of Contents',
    ),
    'ads'=> array(
      'Policy Forum' => "body_ad.html",
    ),
    'editors_notes' => array(
      'void' => 'Video_portal.html',
      'void2' => 'Editors_Note',
      'void3' => 'House_Ad',
    ),
    'sub_heading' => "In this week's issue:",
    'publication' => 'Science',
    'name' => 'Table of Contents',
    'teaser_option' => 'A',
    'data_source' => 'Articles',
    'template' => 'templates/alert/main_two_column',
    'outpath' => "application/pm/output/alerts/sci/etoc",
    'content' => array(
			array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
      array('type' => 'file', 'location' => './application/pm/output/ads/sci_etoc_Editors_Note.html', 'target' => 'main'),
      array('type' =>'var', 'location' => 'body', 'target' => 'main'),
      array('type' => 'view', 'location' => 'templates/menu/submenu_science', 'target' => 'side'),
      array('type' => 'view', 'location' => 'templates/template_podcast_include', 'target' => 'side'),
      array('type' => 'file', 'location' => './application/pm/output/ads/sci_etoc_Video_portal.html', 'target' => 'side'),
      array('type' => 'feed', 'location' => 'http://news.sciencemag.org/rss/etoc_feed.xml', 'target' => 'side', 'article_count' => 5, 'view' => 'templates/rss/news_right'),
      array('type' => 'file', 'location' => './application/pm/output/ads/sci_etoc_House_Ad.html', 'target' => 'side')
    ),
    'order' => array(
      //section
      //
      'Special Section' => array(
        'Introduction to Special Issue' => array(
          'display' => 'full',
        ),
        'Special Issue News' => array(
          'display' => 'full',
        ),
								'Special Issue Letter' => array(
									'display' => 'full',
								),
								'Special Issue Book Review' => array(
          'display' => 'full',
        ),
								'Special Issue Books et al.' => array(
          'display' => 'full',
        ),
								'Special Issue Education Forum' => array(
          'display' => 'full',
        ),
								'Special Issue Policy Forum' => array(
          'display' => 'full',
        ),
								'Special Issue Essay' => array(
          'display' => 'full',
        ),
								'Special Issue Review' => array(
          'display' => 'full',
        ),
								'Special Issue Viewpoint' => array(
          'display' => 'full',
        ),
								'Special Issue Perspective' => array(
          'display' => 'full',
        ),
								'Special Issue Brevia' => array(
          'display' => 'full',
        ),
								'Special Issue Research Article' => array(
          'display' => 'full',
        ),
								'Special Issue Report' => array(
          'display' => 'full',
        ), 
								'Special Issue Podcast' => array(
          'display' => 'full',
        ),
								'Special Issue Multimedia' => array(
          'display' => 'full',
        ),
								'Special Issue Technical Comment' => array(
									'display' => 'full',
								),
								'Special Issue Technical Response' => array(
          'display' => 'full',
        ),
						  'Special Feature' => array(
          'display' => 'full',
         ),
								'Special Issue Viewpoint' => array(
          'display' => 'full',
         ),
								
      ),
      'Research Summaries' => array(
        //article type
        'This Week in Science' => array(
          //description is used in place of the teaser if display is set to collapsed.
          'description' => 'Editor summaries of this week\'s papers.',
          //link override is used for the link when the display is set to collapsed.
          'link-override' => 'http://www.sciencemag.org/content/%$vol/%$issue/twis.full',
          //display is used by everything. it can be set to collapsed, full, or hidden, full will show all articles,
          //collapsed will display the article type as the name, and use the overrides, hidden will display nothing.
          'display' => 'collapsed',
        ),
        'Editors\' Choice' => array(
          'description' => 'Highlights of the recent literature.',
          'link-override' =>'http://www.sciencemag.org/content/%$vol/%$issue/twil.full',
          'display' => 'collapsed',
        ),
      ),
      'Editorial' => array(
        'Editorial' => array(
          'display' => 'full',
        ),
      ),
      'News of The Week' => array(
        'News of the Week' => array(
          'display' => 'full',
        ),
        'Findings' => array(
          'link-override' => 'http://www.sciencemag.org/content/%$vol/%$issue/findings.full',
          'display' => 'collapsed',
        )
      ),
      'News & Analysis' => array(
        'News & Analysis' => array(
          'display' => 'full',
        ),
      ),
      'News Focus' => array(
        'News Focus' => array(
          'display' => 'full',
        ),
      ),
      'Letters' => array(
        'Letter' => array(
          'display' => 'full',
        ),
								'Correction' => array(
          'display' => 'full',
        ),
      ),
      'Books et al.' => array(
        'Book Review' => array(
          'display' => 'full',
        ),
        'Books et al.' => array(
          'display' => 'full',
        ),
      ),
						'Essays' => array(
        'Essay' => array(
          'display' => 'full',
        ),
      ),
      'Education Forum' => array(
        'Education Forum' => array(
          'display' => 'full',
        ),
      ),
      'Policy Forum' => array(
        'Policy Forum' => array(
          'display' => 'full',
        ),
      ),
      'Perspectives' => array(
        'Perspective' => array(
          'display' => 'full',
        ),
								'Retrospective' => array(
          'display' => 'full',
        ),
      ),
						'Association Affairs' => array(
        'Association Affairs' => array(
          'display' => 'full',
        ),
      ),
      'Reviews' => array(
        'Review' => array(
          'display' => 'full',
        ),
      ),
						'Viewpoints' => array(
        'Viewpoint' => array(
          'display' => 'full',
        ),
      ),
						'Articles' => array(
        'Article' => array(
          'display' => 'full',
        ),
      ),
      'Brevia' => array(
        'Brevia' => array(
          'display' => 'full',
        ),
      ),
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
						'Technical Comments' => array(
        'Technical Comment' => array(
          'display' => 'full',
        ),
								'Technical Response' => array(
          'display' => 'full',
        ),
      ),
      'Podcast' => array(
        'Podcast' => array(
          'display' => 'full',
        ),
      ),
						'Multimedia' => array(
        'Multimedia' => array(
          'display' => 'full',
        ),
      ),
						'New Products' => array(
        'New Products' => array(
          'display' => 'full',
        ),
      ),
						'From the AAAS Office of Publishing and Member Services' => array(
        'Business Office Feature' => array(
          'display' => 'full',
        ),
      ),
						

    ),
  ),
  'express'   => array(
    'eloqua' => array(
      'subject' => 'Science ScienceExpress Notification for TODAY_SCIENCE',
      'eloqua_short_name' => 'Science Express - ',
      'eloqua_group_id' => '37',
      'eloqua_from' => 'Science Express Notification',
    ),
    // overrides the journal settings
    'article_base_path' => 'http://www.sciencemag.org/lookup/doi/DOI', 
    'publication' => 'Science',
    'name' => 'Express',
    'sub_heading' => "New <em>Science<em> Express articles have been made available:",
    'data_source' => 'ScienceXpress',
    'template' => 'templates/alert/main_one_column',
    'outpath' => "application/pm/output/alerts/sci/express",
    'content' => array(
						array('type' => 'view', 'location' => 'templates/header/template_header_daily_news', 'target' => 'header'),
      array('type' =>'var', 'location' => 'body', 'target' => 'main'),  
    ),
    'order' => array(
      'Perspectives' => array(
        'Perspective' => array(
          'display' => 'full',
        ),
								'Retrospective' => array(
          'display' => 'full',
        ),
      ),
						'Essays' => array(
        'Essay' => array(
          'display' => 'full',
        ),
      ),
      'Reviews' => array(
        'Review' => array(
          'display' => 'full',
        ),
      ),
      'Brevia' => array(
        'Brevia' => array(
          'display' => 'full',
        ),
      ),
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
						'Articles' => array(
        'Article' => array(
          'display' => 'full',
        ),
      ), 
    ),
  ),

  'ed-choice' => array('ads'=> array(),
  'editors_notes' => array(
    'void' => 'Editors_Note',
  ),
  'eloqua' => array(
    'subject' => ' Editors\' Choice: Highlights of the recent literature',
    'eloqua_short_name' => 'Editors Choice - ',
    'eloqua_group_id' => '36',
    'eloqua_from' => 'Science Editors\'s Choice',
  ),
  'publication' => 'Science',
  'name' => 'Editors\' Choice',
  'sub_heading' => "Summaries of recent literature by <em>Science</em> editors.",
  'teaser_option' => 'C',
  'data_source' => 'Articles',
  'template' => 'templates/alert/main_one_column',
  'outpath' => "application/pm/output/alerts/sci/ed-choice",
  'content' => array(
			array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
			array('type' => 'file', 'location' => './application/pm/output/ads/sci_ed-choice_Editors_Note.html', 'target' => 'main'),
    array('type' =>'var', 'location' => 'body', 'target' => 'main'),
    //array('type' =>'var', 'location' => 'title', 'target' => 'side'),
  ),
  'order' => array(
    'Research Highlights' => array(
      'Editors\' Choice' => array(

        'link-override' => 'http://www.sciencemag.org/content/%$vol/%$issue/',
        'display' => 'full',
      ),
    ),                                      
  ),
),
'sci-twis'  => array(
  'ads'=> array(),
  'editors_notes' => array(
    'void' => 'Editors_Note',
  ),
  'eloqua' => array(
    'subject' => ' This Week In Science', 
    'eloqua_short_name' => 'TWIS - ',
    'eloqua_group_id' => '44',
    'eloqua_from' => 'This Week In Science',
  ),
  'publication' => 'Science',
  'name' => 'This Week in Science',
  'sub_heading' => "Editor summaries of this week's research papers.",
  'teaser_option' => 'B',
  'data_source' => 'Articles',
  'template' => 'templates/alert/main_one_column',
  'outpath' => "application/pm/output/alerts/sci/sci-twis",


  'content' => array(
		array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
		array('type' => 'file', 'location' => './application/pm/output/ads/sci_twis_Editors_Note.html', 'target' => 'main'),
    array('type' =>'var', 'location' => 'body', 'target' => 'main'),
  ),
  'order' => array(
    'This Week in Science' => array(
      'This Week in Science' => array(
        'display' => 'full',
      ),
    ),
  ),
),

'news-this-week'  => array(
  'ads'=> array(),
  'editors_notes' => array(
    'void' => 'Editors_Note',
  ),
  'eloqua' => array(
    'subject' => ' Science News This Week', 
    'eloqua_short_name' => 'Science News This Week - ',
    'eloqua_group_id' => '39',
    'eloqua_from' => 'Science News This Week',
  ),
  'publication' => 'Science',
  'name' => 'News This Week',
  'sub_heading' => "A roundup of the week's top stories in <em>Science</em>:",
  'teaser_option' => 'D',
  'data_source' => 'Articles',
  'template' => 'templates/alert/main_one_column',
  'outpath' => "application/pm/output/alerts/sci/this-week",
  
  'content' => array(
			array('type' => 'view', 'location' => 'templates/header/template_header_standard', 'target' => 'header'),
			array('type' => 'file', 'location' => './application/pm/output/ads/sci_news-this-week_Editors_Note.html', 'target' => 'main'),
    array('type' =>'var', 'location' => 'body', 'target' => 'main'),
  ),
  'order' => array(
    'News of The Week' => array(
      'News of the Week' => array(
        'display' => 'full',
      ),
						'Findings' => array(
									'link-override' => 'http://www.sciencemag.org/content/%$vol/%$issue/findings.full',
									'display' => 'collapsed',

      ),

    ),


    'News & Analysis' => array(
      'News & Analysis' => array(
        'display' => 'full',
      ),
    ),				

    'News Focus' => array(
      'News Focus' => array(
        'display' => 'full',
      ),
    ),

  ),
),

);
