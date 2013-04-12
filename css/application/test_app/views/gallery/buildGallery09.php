<?php

if ( !empty($HTTP_POST_VARS) ) {
   while(list($key,$val) = each($HTTP_POST_VARS)) {
         $$key = $val;
         $key  = trim($key);
         $key  = stripslashes($key);
         $key  = strip_tags($key);
   }
}

function buildPages($galleryPath, $galleryArray, $galleryName) {
// create new gallery pages with functional navigation and dummy content.
	
	$today = date("Y/m/d H:i:s");

	//import standard header and footer
	$header = file_get_contents("../resources/galleryHeader.html");
	$footer = file_get_contents("../resources/galleryFooter.html");

	//loop through all sections in the gallery
	foreach ($galleryArray as $gallerySection) {
		$page = "";
		$count = 0;									//index of page in current section
		$skipped = 0;								//index of pages that had errors
		$itemArray = explode("#",$gallerySection);

		// get title of the current section
		list ($filename, $title, $code) = explode("|", $itemArray[0]);
		$sectionTitle = $title;
	
		// loop through all items in the current section
		foreach ($itemArray as $item) {
			list ($filename, $title, $code) = explode("|", $item);

			//Build page source		
			$page = $header;
			
			// set up the page title
			$page = str_replace("{{metatitle}}", $galleryName . " - " . $title, $page);
			
			$page .= "\n\n\t\t\t<div class=\"breadcrumbs\"><a href=\"/\">Home</a> &gt; <a href=\"../\">Galleries</a> &gt; " . substr($galleryName, 0, 26) . "...</div>\n";
			$page .= "\n\t\t\t<h3>Galleries</h3>\n";
			$page .= "\t\t\t<h4>" . $galleryName . "</h4>\n\n";
			$page .= showNav($code,$galleryArray);
			$page .= "\n\n\t\t\t<h5>" . $sectionTitle . "</h5>\n";
			if ($sectionTitle != $title) {
				$page = $page . "\t\t\t<h6>" . $title . "</h6>\n";
			}
			$page = $page . "\n\t\t\t<p>PASTE PAGE CONTENT HERE</p>\n\n";
			$page = $page . "\t\t\t<hr />\n\n";
			$page = $page . "\t\t\t<div class=\"notes\">\n";
			$page = $page . "\t\t\t\t<h6>Footnotes:</h6>\n";
			$page = $page . "\t\t\t\t<p>FOOTNOTE CONTENT HERE</p>\n\n";
			$page = $page . "\t\t\t\t<hr />\n\n";
			$page = $page . "\t\t\t\t<h5><a id=\"resources\"></a>Related Museum Resources</h5>\n";
			$page = $page . "\t\t\t\t<?php include(\$root . \"/museum/resources/" . $filename . "\"); ?>\n";
			$page = $page . "\t\t\t</div>\n";
			$page = $page . $footer;

			//Write to file
			if (!$handle = fopen($galleryPath . $filename, "w")) {
				echo "Cannot open file for writing";
			} else {
				if (fwrite($handle, $page)) {
					echo "<div>Written file: " .  $filename . "</div>";
				}			
			}

			$count++;

		}

	}	

	print("<br /><hr size='1' noshade /><p> Processed at: " . $today . " (local time)<br />");
	print(($count) . " sections processed<br />");
	print($skipped . " pages had problems</p>");

}

function showPageNav() {
	return "\t\t<p align=\"right\"><a href=\"#\">&lt;&lt;Previous</a> | <a href=\"#\">Next &gt;&gt;</a></p>\n";
}

function showNav($sectionId, $galleryArray) {
//	Draws the navigation for the specified section, highlighting the appropriate page

	$navString = "\t\t\t<div id=\"sidemenu\"><ul id=\"gallerymenu\">\n";
	
	foreach ($galleryArray as $gallerySection) {
		$itemArray = explode("#",$gallerySection);

		//take first item in gallery section and remove it from the array of items
		list ($filename, $title, $code) = explode("|", array_shift($itemArray));
		
		//check if section should be highlighted
		if (substr($sectionId,0,5) == substr($code,0,5)) {
			$indicator = " class=\"on\"";
		} else {
			$indicator = "";
		}

		//check whether there are sub level items and if there are draw them
		if (sizeof($itemArray)>0 && substr($sectionId,0,5) == substr($code,0,5)) {
			$navString = $navString . "\t\t\t\t<li><a href=\"". $filename ."\"" . $indicator . ">" . $title . "</a>\n";
			//draw sublevels
			$navString = $navString . showSubLevels($itemArray, $sectionId);
			$navString = $navString . "\t\t\t</li>\n";
		} else {
			$navString = $navString . "\t\t\t<li><a href=\"". $filename ."\"" . $indicator . ">" . $title . "</a></li>\n";
		}
	}
	$navString = $navString . "\t\t\t</ul></div>";
	return $navString;
}

function showSubLevels($itemArray, $sectionId) {
		$subString = "\t\t\t\t<ul>\n";
		foreach ($itemArray as $item) {
			list ($filename, $title, $code) = explode("|", $item);
			if ($sectionId == $code) {
				$subString = $subString . "\t\t\t\t\t<li><a href=\"". $filename ."\" class=\"on\">" . $title . "</a></li>\n";
			} else {
				$subString = $subString . "\t\t\t\t\t<li><a href=\"". $filename ."\">" . $title . "</a></li>\n";
			}
		}
		$subString = $subString . "\t\t\t\t</ul>\n";
		return $subString;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="ROBOTS" content="ALL" />
<link rel="stylesheet" type="text/css" media="all" href="../../css/admin.css" />
<link rel="Shortcut Icon" href="../../favicon.ico" type="image/x-icon" />
<script language="JavaScript" src="/include/admin.js" type="text/javascript"></script>
<!--<link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />-->


<title>Gallery</title>
</head>

<body>

<div id="wrapper">

  <div id="header">
	
		<a name="top"></a><a href="#body" style="display:none"><img src="/images/elements/transdot.gif" alt="skip navigation" border="0" /></a>
	</div>

	<div id="innercontent">
	
		<div id="loginpanel">
			Welcome back <strong><?php print($username)?></strong>. <a href="../logout.php">Log out</a>		</div>
		<h1>Build New Gallery</h1>
		<?php

	if (!empty($textarea)) {
		$textarea = stripslashes($textarea);
		$lines = explode(chr(13), $textarea);
		$count = 0;
		$skipped = 0;
		$galleryArray =  array();
		$tmpCode="null";
						
		if ($submit=="Submit") {

			print("<h2>Process Summary (". strtoupper($type) .") </h2>");
			if ($preview != "") {
				print "<h3>MODE: PREVIEW</h3>";
			} else {
				print "<h3>MODE: UPDATE IN DATABASE</h3>";
			}
			
			//parse text imput into delimited arrary
			//# - separates items
			//| - separates values
			foreach($lines as $line) {

				list($file, $title, $code) = explode("|", $line);
				$code = substr($code,0,5);

				// if code has changed go to next array item
				if ($tmpCode != $code) {
					$count++;
					$addon = "";
				} else {
					$addon = "#";
				}

				// set ready for next line
				$tmpCode = $code;

				// append new value to array string			
				$galleryArray[$count] =  $galleryArray[$count] . $addon . trim($line);

			}

			//set gallery path
			//$galleryPath = $webroot . "/museum/galleries/" . $folder . "/";
			$galleryPath = "C:\\xampp\\htdocs\\museum\\galleries\\" . $folder . "\\";

			//create gallery pages
			buildPages($galleryPath,$galleryArray,$galleryName);

		}	
 
	}
	?>

  		<form id="form1" name="form1" method="post" action="<?php echo $PHP_SELF;?>">
  			<h2>Gallery Data</h2>
			<p>Paste data into the field below to create a new, empty set of gallery files.</p>
        	<p>Filename* | Title* | Gallery Section Code*</p>
			<hr size="1" noshade />
	  			
  			<div class="formitem">
  				<label for="galleryName">Gallery name</label>
  				<input type="text" name="galleryName" id="galleryName" value="<?php echo $galleryName?>" size="40" />
			</div>
	
		<div class="formitem">
			<label for="folder">Folder name</label>
			<input type="text" name="folder" id="folder" value="<?php echo $folder?>" size="10" />
			</div>
	
		<br />
  			<textarea name="textarea" cols="80" rows="8" wrap="off"><?php echo($textarea) ?></textarea>
	  			
  			<br /><br />
  			<input type="submit" name="submit" value="Submit" />
  			<input type="button" name="cancel" value="Cancel" onclick="document.location='../mainAdmin.php'" />
				</form>
  		<br />
  		<br />
		<hr size="1" noshade />
	
		<a href="/admin/mainAdmin.php">Admin Home</a> | 
		<a href="/">Back to Museum</a>
		
		<br />
			<br />
	</div>
</div>
</body>
</html>
