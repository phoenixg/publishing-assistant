<?php 

$idate = $issueData["ISSUE_MONTH"] . "/" . $issueData["ISSUE_DAY"] ."/". $issueData["ISSUE_YEAR"];

echo form_open('issueMaster/importIssue/'.$jname.'/'.  $idate); ?>
<?php

	if ( time() >= strtotime($issueData['date'])) {
		switch($jname){
				case "sci":
					print ("<img class='thumb left' width='79' height='101' src='http://www.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover.gif' />");
					break;
				case "sig":
					print ("<img class='thumb left' width='79' height='101' src='http://stke.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover_archive_banner.gif' />");
					break;
				default:
					print ("<img class='thumb left' width='79' height='101' src='/codeigniter-v2.1.2/img/sci-temp-cover.jpg' />");
					break;
			}
	} else {
		print ("<img class='thumb left' width='79' height='101' src='/codeigniter-v2.1.2/img/sci-temp-cover.jpg' />");
	}

	
	print("<div>Vol: " . $issueData["VOLUME_NUM"] . ", Issue: ". $issueData["ISSUE_NUM"]."</div>");
	print("<div>".$idate."</div>");
	//print("<div><a href='" . site_url("issueMaster/") ."/viewSpecialIssue/". $idate . "'>SPECIAL CONTENT</a></div>");
	
	print("<div style='clear:both'></div><br />");
	
	print("<div>Expand the sections to view individual articles. Choose those items that you wish to import.</div>");

  print("<div class='btn-group'>");
	print('<button class="btn" id="allTemplates">Select All</button>');
	print('<button class="btn" id="noTemplates">Select None</button>');
  print("</div>");
	
	foreach ($collections as $c_name => $sections) {
	
		print("<div id=\"" . strtolower($c_name) . "\">\n");
		echo("<br /><h2>" . $c_name . "</h2>");
		
		$c = 0;
		
		foreach ($sections as $name => $items) {
			if ($name) {

					$ac =  count($items);
					print("<h3 class=\"section\">" . $name . " (". $ac .")</h3>\n");
					print "<div class=\"hidden\">"; 
					foreach ($items as $item) {
						print(
              "<label class='checkbox'>".
              "<input class='" . strtolower($c_name) . "' type='checkbox' name='doi[]'".
              "id='". $item['doi'] ."' value='". $item['doi'] ."'>". $item['title'] ." (".
              $item['doi'] . ") [<a href='http://www.sciencemag.org/content/{$item['volume']}/{$item['issue']}/{$item['page']}'>LINK</a>]</input>".
              "</label>");
					}
					print "</div>";
					
					$c += $ac;
			}
		}
		
		print("<div>[Total: " . $c . "]</div>\n");
		print("</div>\n");
	
	}
	

	
?>

<br / >

<input name="" id="importButton" value="Import Selected Articles" class="btn" type="submit">
</form>		

<div class="clearing"></div>

