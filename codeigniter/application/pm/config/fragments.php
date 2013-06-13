
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * 
 * Configuration Data for Fragment Generator
 *
 * ----------------------------------------------------------------------------
 */


//config array for alerts, output and ad placements.
// The alertMaster uses this array in order to build the list of alerts avalible to the user, and once an alert is chosen
//to be compiled, it will again use this array to place ads, order the alert, etc



$config['fragments'] = array(
  array(
    'publication' => 'sci',
    'id' => 'science_iphone',
    'data_source' => 'Articles',
    'name' => 'Science Recent Items',
    'description' => 'Recent items for Science front page',
    'view' => 'sci_iphone',
    'output' => APPPATH."output/fragments",
  ),
  array(
    'publication' => 'sci',
    'id' => 'sci_homepage',
    'data_source' => 'Articles',
    'name' => 'SCIHome Page',
    'filters' => array(
      //'overline' => 'DIAGNOSIS',
      //'title' => 'C'
    ),
    'sort' => 'fpage',
    'description' => 'Generates SCI front page article fragment',
    'view'  => 'sci_home',
    'output' => APPPATH."output/fragments/sci_home.html",
  ),
  array(
    'publication' => 'stm',
    'id' => 'stm_iphone',
    'data_source' => 'Articles',
    'name' => 'iPhone STM App Table of Contents',
    'description' => 'iPhone STM App Table of Contents',
    'view'  => 'stm_iphone',
    'output' => APPPATH."output/fragments",
  ),
  array(
    'publication' => 'stm',
    'id' => 'stm_homepage',
    'data_source' => 'Articles',
    'name' => 'STM Home Page',
    'filters' => array(
      //'overline' => 'DIAGNOSIS',
      //'title' => 'C'
    ),
    'sort' => 'article_type',
    'description' => 'Generates STM front page article fragment',
    'view'  => 'stm_home',
    'output' => APPPATH."output/fragments/stm_home.html",
  ),
  array(
    'publication' => 'sig',
    'id' => 'signaling_iphone',
    'data_source' => 'Articles',
    'name' => 'iPhone SIGNALING App Table of Contents',
    'description' => 'iPhone SIGNALING App Table of Contents',
    'view'  => 'signaling_iphone',
    'output' => APPPATH."output/fragments",
  ),
  array(
    'publication' => 'sig',
    'id' => 'signaling_homepage',
    'data_source' => 'Articles',
    'name' => 'SIGNALING Home Page',
    'filters' => array(
      //'overline' => 'DIAGNOSIS',
      //'title' => 'C'
    ),
    'sort' => 'fpage',
    'description' => 'Generates SIGNALING front page article fragment',
    'view'  => 'signaling_home',
    'output' => APPPATH."output/fragments/signaling_home.html",
  ), 
 array(
    'publication' => 'news',
    'id' => 'newsblank',
    'name' => 'News Dummy Example',
    'description' => 'Placeholder for Science News fragments',
    'view'  => 'news',
    'output' => APPPATH."output/fragments",
  ),
);
