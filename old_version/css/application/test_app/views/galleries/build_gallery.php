<?php

// THIS SHOULD ALL BE IN THE CONTROLLER

function buildPages($galleryPath, $galleryArray, $galleryName) {
// create new gallery pages with functional navigation and dummy content.
	
	$today = date("Y/m/d H:i:s");

	//import standard header and footer
	$header = file_get_contents(APPPATH . "includes/galleryHeader.html");
	$footer = file_get_contents(APPPATH . "includes/galleryFooter.html");

	//loop through all sections in the gallery
	foreach ($galleryArray as $gallerySection) {
		$page = "";
		$count = 0;									//index of page in current section
		$skipped = 0;								//index of pages that had errors
		//$itemArray = explode("#",$gallerySection);

		foreach($gallerySection as $galleryItem){
		
			// get title of the current section
			list ($filename, $title, $code) = explode("|", $galleryItem);
			$sectionTitle = $title;
		
			// loop through all items in the current section
			//foreach ($itemArray as $item) {
				//list ($filename, $title, $code) = explode("|", $item);

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

			//}
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

		<h2>Build New Gallery</h2>
		
		<p><strong>THIS PAGE IS NOT FINISHED BEING DEVELOPED - FOR NOW USE <a href="http://localhost/!admin/gallery/buildGallery.php">THIS ONE</a> INSTEAD</strong></p>
		
		<?php
		
	if (!empty($textarea)) {
		$textarea = stripslashes($textarea);
		$lines = explode("\n", $textarea);
		$count = -1;
		$skipped = 0;
		$galleryArray = array();
		$sectionArray = array();
		$tmpCode="null";
						
		if ($submit!="") {
			
			//parse text imput into delimited arrary
			//# - separates items
			//| - separates values
			foreach($lines as $line) {

				list($file, $title, $code) = explode("|", $line);
				$code = substr($code,0,5); // ignore the character subsection on the end.

				/*
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
				//$galleryArray[$count] =  $galleryArray[$count] . $addon . trim($line);
				
				//THIS IS WORKING RIGHT YET
				array_push($galleryArray, $addon . trim($line));
				*/
				
				// detect whether we're in another section or not.
				if ($tmpCode != $code) {
					array_push($sectionArray, trim($line));
					$tmpCode = $code;
				} else {
					array_push($galleryArray, $sectionArray);
					$sectionArray = array();
				}
				
			}

			//set gallery path
			//$galleryPath = $webroot . "/museum/galleries/" . $folder . "/";
			$galleryPath = "C:\\xampp\\htdocs\\museum\\galleries\\" . $folder . "\\";

			//create gallery pages
			buildPages($galleryPath,$galleryArray,$galleryName);
			
			print("<hr />");

		}	
 
	}
	?>

		<p>Generate a new, skeleton set of gallery files. All pages will be created, navigation set up and links for the generated "resource" file are created. The directory should be created before running this feature.</p>

  		<form id="form1" name="form1" method="post" action="<?php echo (WEBROOT)?>galleries/build_new_gallery">
	  			
  			<fieldset>
			<legend>New Gallery Data</legend>
			<div class="formitem">
  				<label for="galleryName">Gallery name</label>
  				<input type="text" name="galleryName" id="galleryName" value="<?php echo $galleryName?>" size="60" />
			</div>
	
			<div class="formitem">
				<label for="folder">Folder name</label>
				<input type="text" name="folder" id="folder" value="<?php echo $folder?>" size="10" />
			</div>
		
			<hr size="1" noshade />

			<p>List section title page first, then follow with the subsections. Program detects when the code changes. See example below.</p>
        	<div>Filename* | Title* | Gallery Section Code (three letter index)*</div>
  			<textarea name="textarea" rows="15" wrap="off"><?php echo($textarea) ?></textarea>
	  		
			</fieldset>
			
  			<input type="submit" name="submit" value="Build Gallery" class="button" />
		</form>
		
		<br />
		<br />
		<br />
		
		<h4>Example</h4>

		<pre>index.php|Section 1 Title|gal01a
gal02a.php|Section 2 Title|gal02a
gal02a.php|Section 2 - First Subsection Title|gal02a
gal02b.php|Section 2 - Second Subsection Title|gal02b
gal02c.php|Section 2 - Third Subsection Title|gal02c
gal03a.php|Section 3 Title|gal03a
gal03a.php|Section 3 - First Subsection Title|gal03a</pre>
		
		<p>Note that the Title of each section is a duplicated record - this page actually contains no content - the navigations directs you to the first sub-section.</p>
		
	</div>
</div>
</body>
</html>
