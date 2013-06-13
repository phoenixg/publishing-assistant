<?php

	switch($jname){
				case "sci":
					print ("<img class='thumb left' width='79' height='101' src='http://www.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover.gif' />");
					break;
				case "sig":
					print ("<img class='thumb left' width='79' height='101' src='http://stke.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover_archive_banner.gif' />");
					break;
				default:
					print ("<img class='thumb left' width='79' height='101' src='http://aaasphp01.aaas.org/codeigniter/img/sci-temp-cover.jpg' />");
					break;
			}
	
	print("<div style=\"margin-left: 120px;\">");
	
	print("<p>Vol: " . $issueData["VOLUME_NUM"] . ", Issue: ". $issueData["ISSUE_NUM"]."</p>");

	
	//Output summary of items processed.
	print("<h3>Processing Summary</h3>");
	print("<ul>");
	foreach ($articleCount as $name => $c) {
		print("<li>" . $c . " " . $name . " processed.</li>");
	}
	print("</ul>");
	
	//Output any messages that were generated.
	print("<h3>Messages</h3>");
	print("<ul>");
	foreach ($msg as $m) {
		print("<li>" . $m . "</li>");
	}
	print("</ul>");

	
	print("<div><a href='" . site_url("issueMaster/viewToc/" . $issueData["ISSUE_MONTH"] . "/" . $issueData["ISSUE_DAY"] . "/"  . $issueData["ISSUE_YEAR"]) . "'>View Table of Contents</a></div>");
	print("<div><a href='" . site_url("issueMaster/") . "'>Back to the Issue Manager</a></div>");

	print("</div>");
	
?>

<div class="clearing"></div>