<?php 
/**
*  Pagewrapper that incorporates the Twitter Bootstrap framework 
*  and Growl notifications.
*/
// Use '0' for production, '1' for testing
error_reporting(1);
$base_url = base_url(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php print $title ?> - Science Publishing Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="environment" content="<?php echo ENVIRONMENT ?>" />

    <!-- Le styles -->
		<link href="<?php echo $base_url?>application/pm/assets/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo $base_url?>application/pm/assets/css/app.css" rel="stylesheet">
		<link href="<?php echo $base_url?>application/pm/assets/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo $base_url?>application/pm/assets/css/jquery.jgrowl.css" rel="stylesheet">
		<link href="<?php echo $base_url?>application/pm/assets/css/le-frog/jquery-ui-1.7.2.custom.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="<?php echo $base_url?>application/pm/assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $base_url?>application/pm/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $base_url?>application/pm/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $base_url?>application/pm/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo $base_url?>application/pm/assets/ico/apple-touch-icon-57-precomposed.png">
    
    <!-- Le scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="<?php echo $base_url?>application/pm/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo $base_url?>application/pm/assets/js/jquery-cookie.js"></script>
		<script src="<?php echo $base_url?>application/pm/assets/js/jgrowl/jquery.jgrowl_minimized.js"></script>
		<script src="<?php echo $base_url?>application/pm/assets/js/app.js"></script>
    <?php echo $controller_scripts; ?>
  </head>

  <body>

  <div class="container">

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./"><em>Science</em> Publishing Assistant</a>
          <div class="nav-collapse">
            <ul class="nav">
            <li><a href="<?php echo $base_url?>index.php/alertMaster"> Home</a></li>
            <li><a href="<?php echo $base_url?>index.php/issueMaster"><i class="icon-comment icon-white"></i> Issue Manager</a></li>
            <li class="active"><a href="<?php echo $base_url?>index.php/alertMaster"><i class="icon-envelope icon-white"></i> eAlerts</a></li>
            </ul>
            <ul class="nav pull-right">
              <li><a href="#about">About This App</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <ul class="breadcrumb">
      <li>
      <a href="<?php echo $base_url?>index.php/alertMaster"><i class="icon-home"></i> Home</a> <span class="divider">/</span>
      </li>
      <li class="active">
			<a href="#"><?php print $title ?></a>
      </li>
    </ul>
    
    <div class="row page-body">
        <div class="span3 navigation">
              <ul class="nav nav-list">
              <li class="nav-header">Science Issue Manager</li>
              <li><a href="<?php echo base_url(); ?>index.php/issueMaster"><i class="icon-envelope"></i>Science Issue Manager</a></li>
              <li class="nav-header">Electronic Alerts Management</li>
                <li><a href="<?php echo base_url(); ?>index.php/alertMaster"><i class="icon-envelope"></i> Review and Send eAlerts</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/adMaster"><i class="icon-edit"></i> Ad Content Manager</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/editorNotes"><i class="icon-edit"></i> Editorial Content Manager</a></li>
              </ul>
              <br />
	      <div class="hidden-phone"><a href="http://eddev01.aaas.org/archive/alert-output/">View eAlert examples</a></div>
              <hr  class="hidden-phone" />
              <div class="hidden-phone">Built with <a href="http://twitter.github.com/bootstrap/index.html">Bootstrap</a> 2.0.4</div>
              
        </div>
        <div class="span9 main-conent">

        <h1><?php echo $title; ?></h1>

          <!--div class="alert alert-warning">
            <button class="close" data-dismiss="alert">×</button>
            <strong>Note:</strong> System is in TEST mode. Article XML data may not be accurate.
          </div-->

          <?php print $content ?>
          
        </div>
    </div>
    
    <footer>
      <p>&copy; <em>Science</em> | AAAS 2012</p>
    </footer>
    
  </div><!-- /container -->

  </body>
</html>
