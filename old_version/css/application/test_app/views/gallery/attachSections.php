<?php

require("/home/webadmin/webconf/session.php");
require("/home/webadmin/webconf/collectiondb.php");

if ( !empty($HTTP_POST_VARS) ) {
   while(list($key,$val) = each($HTTP_POST_VARS)) {
         $$key = $val;
         $key  = trim($key);
         $key  = stripslashes($key);
         $key  = strip_tags($key);
   }
}

$previewstate = "checked='checked'";
if (isset($preview) && $preview == "") {
	$previewstate = "";
}

if ($type == "photo") {
	$paperstate="";
	$photostate="checked='checked'";	
} else {
	$paperstate="checked='checked'";
	$photostate="";	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/adminPage.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="ROBOTS" content="ALL" />
<link rel="stylesheet" type="text/css" media="all" href="../../css/admin.css" />
<link rel="Shortcut Icon" href="../../favicon.ico" type="image/x-icon" />
<script language="JavaScript" src="/include/admin.js" type="text/javascript"></script>
<!--<link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />-->
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><title>Gallery</title><!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

  <div id="header">
	
		<a name="top"></a><a href="#body" style="display:none"><img src="/images/elements/transdot.gif" alt="skip navigation" border="0" /></a>
	
		<div id="utility">
		
		<form action="/cgi-bin/ksearch.cgi" method="get" name="search" style="display:inline;">
	
		<input type="hidden" name="all" value="1" />
		<input type="hidden" name="sort" value="score" />
		<input type="hidden" name="display" value="25" />
		<input type="hidden" name="c" value="s" />
		<input type="hidden" name="w" value="1" />
		<input type="hidden" name="st" value="" />
		<input type="hidden" name="b" value="1" />
		<input type="hidden" name="t" value="1" />
		<input type="hidden" name="u" value="1" />
		<input type="hidden" name="alt" value="1" />
		<input type="hidden" name="l" value="1" />
		<input type="hidden" name="default" value="1" />
		<input type="hidden" name="d" value="1" />
		<input type="hidden" name="k" value="1" />
		<input type="hidden" name="au" value="1" />
		
		<a href="/">Home</a> | <a href="/sitemap.php">Sitemap</a> | Search:	
		<input type="text" name="terms" class="searchfield" onfocus="select(this);" value="" size="15" maxlength="30" /> 
		<input type="image" src="/images/Homepage_elements/btnGo.gif" alt="go" align="middle" />	
		
		</form>
		
		</div>
	
		<img src="/images/Homepage_elements/siteHeader.gif" alt="Virtual Museum and Archive of the SEC and Securities History" width="565" height="66" /><br />
	
		<div id="mainmenu">
	
			<ul>
	
				<li><a href="../mainAdmin.php">Admin Home</a></li>
	
			    <li><a href="/index.php" >Back to Museum</a></li>
			</ul>
	
		</div>
		
	</div>

	<div id="innercontent">
	
			<!-- InstanceBeginEditable name="main content" -->

			<div id="loginpanel">
				Welcome back <strong><?php print($username)?></strong>. <a href="../logout.php">Log out</a>
			</div>
			
			<h1>Bulk Edit Gallery Categories</h1>

	<?php

	if (!empty($textarea)) {
		$today = date("Y/m/d H:i:s");
		$textarea = stripslashes($textarea);
		$lines = explode(chr(13), $textarea);
		$count = 1;
		$skipped = 0;
		$valid=true;
		$validstr="";
	
		if ($submit=="Submit") {

			print("<h2>Process Summary (". strtoupper($type) .") </h2>");
			if ($preview != "") {
				print "<h3>MODE: PREVIEW</h3>";
			} else {
				print "<h3>MODE: UPDATE IN DATABASE</h3>";
			}
			
			foreach($lines as $line) {
				list($itemId, $galleryCode) = explode("|", $line);

				$sql = "SELECT Category2 from " . ucfirst($type) . "s WHERE Id = '$itemId'";
				$result = mysql_query($sql,$db);
				if (mysql_num_rows($result)<1) {
					print("<div style='color:red; font-weight:bold'>Invalid ID: " . $itemId . "</div>");
					$skipped++;
				} else {
					$mydata = mysql_fetch_array($result);
					$currentValue = $mydata['Category2'];
					$galleryCode = trim($galleryCode);
					
					if (trim($galleryCode)!="" && !strstr($currentValue, $galleryCode)) {
						print ("<div>" . $itemId . ": Old: " . $currentValue . " | New: ". $currentValue ." <span style='color:green'>" . $galleryCode . "</span>");
						
						// if this isn't a preview, add the INSERT code here.
						if  ($preview == "") {

							$sql = "UPDATE ". ucfirst($type) . "s SET Category2='" . $currentValue . " " . $galleryCode . "' WHERE Id = '$itemId'";
							print("<div>>>>: " . $sql . "</div>");
							$result = mysql_query($sql,$db);
							if ($result!=1) {
								print("<div style=\"color:red;\">*** Problem (" . $sql . ")</div>");
								$skipped++;
							}
						
						}

					} else {
						print ("<div style='color:red; font-weight:bold'>" . $itemId . ": Old: " . $currentValue . "| New: [*Ignored* (" . $galleryCode. ") ]");
					}
					
					print (" (<a href='../". $type ."s/edit". ucfirst($type) .".php?". $type ."id=" . $itemId . "' target='_blank'>edit record</a>)</div>");
				}

				/*
				$sql = "UPDATE Papers SET Category2='$galleryCode' WHERE Id = '$itemId'";
				$result = mysql_query($sql,$db);
				if ($result!=1) {
					print("<div>*** Problem with ID: " . mysql_insert_id() . "</div>");
					$skipped++;
				} else {
					print("Wrote: " . $sortDate . " | "  . $filename  . "<br />[") ;
					print($sql . "]<br/>");
				}
				*/

				$count++;
			}
			print("<br /><hr size='1' noshade /><p> Processed at: " . $today . " (local time)<br />");
			print(($count - 1) . " records processed<br />");
			print($skipped . " records had problems</p>");

		}	
 
	}
	
	mysql_close($db);
	
	?>

	  <form id="form1" name="form1" method="post" action="<?php echo $PHP_SELF;?>">
	    <h2>Category Data</h2>
		<p>Paste data into the field below to update multiple records to the database. Each record should be on a separate line, fields should be separated by the "|" character.</p>
        <p><b>Key:</b> Id* | Gallery Section Code*<br />All Id's should ALREADY exist in the database.</p>
		<hr size="1" noshade />

		<div><label for="papersbtn">Add to papers</label><input type="radio" name="type" id="papersbtn" value="paper" <?php echo $paperstate?>/></div>
		<div><label for="photosbtn">Add to photos</label><input type="radio" name="type" id="photosbtn" value="photo" <?php echo $photostate?>/></div>
		<textarea name="textarea" cols="80" rows="8" wrap="off"><?php echo($textarea) ?></textarea>
		<input type="checkbox" id="preview" value="true" name="preview" <?php echo $previewstate?> /> Preview
		
		<br /><br />
        <input type="submit" name="submit" value="Submit" />
		<input type="button" name="cancel" value="Cancel" onclick="document.location='../mainAdmin.php'" />
	  </form>
	  	
	<br /><br />
	<hr size="1" noshade />
	
	<a href="../mainAdmin.php">Admin Home</a> | 
	<a href="/">Back to Museum</a>			<!-- InstanceEndEditable -->

			<br />
			<br />
	
	</div>

</div>

</body>
<!-- InstanceEnd --></html>
