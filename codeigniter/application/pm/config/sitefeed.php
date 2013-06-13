<?php 
$config['sitefeeds'] = array(
  array(
    'id' => 'sci-news',
    'publication' => 'sci',
    'description' => 'The best in science news, commentary, and research',
    'data_source' => 'Articles',
    'name' => 'Science: Current Issue',
    'access' => 'subscription',
    'genres' => '',
    'abstract' => 'teaser',
    'filters' => array('article_type' => '(news focus|analysis|special issue news|news of the week)'),
    'sort' => 'fpage',
    'output' => APPPATH."output/sitefeeds/news.xml",
  ),
);

