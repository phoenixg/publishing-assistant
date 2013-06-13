<?php
error_reporting(0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php print $title ?> | Publishing Master</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/sciPublish.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/datePicker.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.2.5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/date.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datePicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jqModal.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sciPublish.js"></script>

</head>

<body>

<div class="header"> 
 	<h1><a href="index.php"><span>*Science* Publishing Assistant</span></a></h1> 
 </div> 

<div id="wrapper">

	<div class="rightContent">

		<div class="rightContentWrapper">

			<h2><?php print $title ?></h2>

            <?php print $content ?>
		
			<div class="clearing"></div>
			
			<p align="right"><a href="#">Back to top</a><br />(script memory usage:<?php print (round(memory_get_usage()/1024,2)); ?>k bytes)</p>

		</div>

	</div>

	<div class="leftContent">

			<p><a href="<?php echo site_url(); ?>" class="button">Back to Main Page</a></p>

			<hr />
			<h3>Science Template Sets (H20)</h3>
			<br />
			<ul class="menu">
				<li><a href="<?php echo site_url(); ?>/templatemaster/templatelist/sci-weekly">SCI: Weekly Updates</a></li>
				<li><a href="<?php echo site_url(); ?>/templatemaster/templatelist/special-issue">SCI: Special Issues</a></li>
			</ul>
			<br />
			<h3>Signaling Template Sets:</h3>
			<ul class="menu">
				<li><a href="/pa/sciPublishForm.php?input=templates/sig-weekly&amp;output=output/sig&amp;title=Signaling%20Weekly%20Updates">SIG: Weekly Updates</a></li>
			</ul>
			<br />
			<h3>Sci Trans Med Template Sets:</h3>
			<ul class="menu">
				<li><a href="/pa/sciPublishForm.php?input=templates/stm-weekly&amp;output=output/stm&amp;title=STM%20Weekly%20Updates">STM: Weekly Updates</a></li>
			</ul>
			<br />
			<h3>Utilities</h3>
			<ul class="menu">
				<li><a href="http://www.sciencemag.org/preview_site/misc/util/global_homepage_preview.xhtml?fm_preview=1">GHP Preview Page (H20)</a></li>
				<li><a href="http://news.sciencemag.org/export/news_featured.html">Daily News Features - Helper Page</a></li>
				<li><a href="http://news.sciencemag.org/process/scrape-weekly-analysis.php")">Publish Weekly News to Movable Type</a></li>
				<li><a href="/pa/mobile/">Publish Mobile App Feeds</a></li>
			</ul>
			<br />
			<h3>Processes</h3>
			<ul class="menu">
				<li><a href="/pa/sciFilePublisher.php">Weekly File Publisher</a></li>
				<li><a href="/pa/sciPublishSOM.php">SOM Creator</a></li>
				<li><a href="/pa/teaserPublish.php">Teaser Creator</a></li>
			</ul>
			<br />
			<p>
				<a href="/pa/sciPublishInstructions.php">instructions</a> |
				<a href="/pa/sciPublishTemplates.php">template help</a> |
				<a href="/pa/sciPublishVersion.php">version 1.3</a> | 
				<a href="mailto:mgreen@aaas.org?subject=Bug%20in%20Publishing%20Assistant">bugs?</a>
			</p>
			<br />

		<hr />
		

		<a href="#" id="builderHead" class="builderClosed">Data Injector Tool</a>
		<div id="builderPanel">
			<p>Paste [name|value] data below: [<a href="#" onclick="document.forms['builder'].source.value=''; return false;">Reset</a>] <span id="bcount"></span></p>
			<form name="builder" action="#">
				<textarea name="source" id="source" rows="8" cols="25"></textarea>
				<input type="button" value="Populate Templates" class="button" onclick="parseVals(document.forms['builder'].source.value); return false" />
			</form>
			<div class="clearing"></div>
		</div>

	</div>

</div>

</body>

</html>

