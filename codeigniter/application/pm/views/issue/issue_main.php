
<?php

/*
	print("<img class='thumb left' src='http://www.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover.gif' />");
	print("<div>Vol: " . $issueData["VOLUME_NUM"] . ", Issue: ". $issueData["ISSUE_NUM"]."</div>");
	print("<p>". $issueData["ISSUE_FULL_MONTH"] . " " . $issueData["ISSUE_DAY"] . " " . $issueData["ISSUE_FULL_YEAR"]."</p>");
		
	if ($articleCount) {
		print('<strong>' . $articleCount . ' Articles Found in the Database</strong>');
		print("<div><a href='" . site_url("issueMaster/viewToc/" . $issueData["ISSUE_MONTH"] . "/" . $issueData["ISSUE_DAY"] . "/"  . $issueData["ISSUE_YEAR"]) . "'>View TOC</a></div>");
	} else {
		print('<strong> Issue does not appear to be in the database.</strong>');
		print("<div><a href='" . site_url("issueMaster/importIssue/" . $issueData["ISSUE_MONTH"] . "/" . $issueData["ISSUE_DAY"] . "/"  . $issueData["ISSUE_YEAR"]) . "'>Import from XML</a></div>");		
	}
*/	
	print ('<p>Click a cover to view the TOC and import data:</p>');
	print ('<div style="clear:both">');
	
	foreach ($issues as $issue) {

		list($tMo, $tDay, $tYear) = explode("/",$issue['date']);
		
		print ("<div style='width: 100px; float: left; margin-bottom: 18px;'>");
				
		
		print("<a href='" . site_url("issueMaster/viewToc/".$jname."/" . $issue['date']) . "' title='View TOC - ". $issue['date'] . "'>");
		if ( time() >= strtotime($issue['date'])) {
			//we need to change this image link depending on the journal code.
			switch($jname){
				case "sci":
					print ("<img class='thumb left' width='79' height='101' src='http://www.sciencemag.org/content/vol" . $issue['vol'] . "/issue". $issue['issue'] . "/cover.gif' />");
					break;
				case "sig":
					print ("<img class='thumb left' width='79' height='101' src='http://stke.sciencemag.org/content/vol" . $issue['vol'] . "/issue". $issue['issue'] . "/cover_archive_banner.gif' />");
					break;
				default:
					print ("<img class='thumb left' width='79' height='101' src='/codeigniter-v2.1.2/img/sci-temp-cover.jpg' />");
					break;
			}
			
		} else {
			print ("<img class='thumb left' width='79' height='101' src='/codeigniter-v2.1.2/img/sci-temp-cover.jpg' />");
		}
		print("</a>\n");
		
		
		print($issue['vol'] . " " .$issue['issue'] . " (" . $issue['count'] . ")\n");
		
		if ($issue['isSpecial']) {
			print(" <span>[<a href='" . site_url("issueMaster/") ."/viewSpecialIssue/". $issue['date'] . "'>S</a>]</span>\n");
		}
		
		print ("<br />\n");
		
		
		/*
		if ($issue['count'] > 0) {
			print("<a href='" . site_url("issueMaster/importIssue/" . $issue['date']) . "'>Re-Import</a>\n");		
		} else {
			print("<a href='" . site_url("issueMaster/importIssue/" . $issue['date']) . "'>Import</a>\n");		
		}
		*/
		
		print ("</div>\n\n");

		}
	
	print ("</div>\n\n");
?>

<div class="clearing"></div>

