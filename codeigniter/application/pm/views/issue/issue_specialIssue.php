<?php

	print("<img class='thumb left' src='http://www.sciencemag.org/content/vol" . $issueData["VOLUME_NUM"] . "/issue". $issueData["ISSUE_NUM"] . "/cover.gif' />");
	print("<p>Vol: " . $issueData["VOLUME_NUM"] . ", Issue: ". $issueData["ISSUE_NUM"]."</p>");
	print("<div><a href='" . site_url("issueMaster/") . "'>ISSUE MANAGER</a></div>");
	print("<div><a href='" . site_url("issueMaster/") ."/viewToc/". $issueData["ISSUE_MONTH"] . "/" . $issueData["ISSUE_DAY"] ."/". $issueData["ISSUE_YEAR"] . "'>View Full TOC</a></div>");
	
	
	print("<div style='clear:both'></div><br /><br />\n\n<!-- START OF CONTENT-->\n\n");
	print("<h2>Index Page Content, From Science</h2>\n\n");
	
	//Only interested in 'special' content sections.
	$ref="";
	
	//Set initial markup for index page
	$html = "<h2>From <em>Science</em>\n\n";
	
	//Set initial markup for mini-toc page
	$tocHtml = "<div class=\"mini-toc-wrapper\">\n";
	$tocHtml .=	"\t<div class=\"mini-toc\">\n";
	$tocHtml .=	"\t<div class=\"mini-toc-inner\" style=\"background: black url(/site/special/[directory]/mini-toc-head.jpg) no-repeat top left; padding-top: 104px;\">\n";
	$tocHtml .=	"\t\t<h2 class=\"hidden\">[TITLE]</h2>\n";
	$tocHtml .=	"\t\t<p>Articles in this collection:</p>\n";
	$tocHtml .=	"\t\t<ul>\n";	

	foreach ($sections as $name => $items) {
		$pos = strpos(strtolower($name),$ref);
		print($name);
		if ($pos !== false || $ref==""){
		
		
			$html .= ("<h3 class=\"sci-header\">" . str_replace('Special Issue ', '', $name) . "</h3>\n");
			$html .= ("<br />\n\n");

			
			foreach ($items as $item) {
			
				$teaser = (isset($item['teaser']) && trim($item['teaser'])!="")? $item['teaser'] : "[Teaser]";
				$author = '';
			
				$a = json_decode($item['author']);
				$len = count($a) -1;
				$i=0;
				
				foreach ($a as $at) {
					$author .= $at->name->{'given-names'}[0] . ". ";
					$author .= $at->name->surname;
					if ($i < $len) {
						$author .= ", ";
					}
					$i++;
				}
			
				$html .= ("<div class=\"sci-item\">\n");
				$html .= ("\t<h3><a href=\"" . $item['url'] . "\">" . $item['title'] . "</a></h3>\n");
				//$html .= ("\t<div class=\"byline\">" . $item['author'] . "</div>\n");
				$html .= ("\t<div class=\"byline\">" . $author . "</div>\n");
				$html .= ("\t<div class=\"teaser\">" . $teaser . "</div>\n");
				$html .= ("</div>\n\n");
				
				$id = (isset($item['subPage']) && $item['subPage'] != "") ? $item['page'] . "." . $item['subPage'] : $item['page'];
				
				$tocHtml .= "\t\t\t<li id=\"sci-nav-".$id."\"><a href=\"".$item['url'].".full\">".$item['title']."</a></li>\n";
				
			}
		}
	}
	
	$tocHtml .= "\t\t</ul>\n\t</div>\n\t</div>\n</div>";
	
	print ("<a href=\"javascript:document.getElementById('htmlcontent').select()\">[ Select All ]</a>\n\n");
	print ("<textarea id='htmlcontent' style='width:100%;' rows='25'>".$html."</textarea>\n");
	
	print ("<br /><br /><h2>Mini-TOC Content, From Science</h2>\n\n");
	print ("<a href=\"javascript:document.getElementById('htmlcontent2').select()\">[ Select All ]</a>\n\n");
	print ("<textarea id='htmlcontent2' style='width:100%;' rows='25'>".$tocHtml."</textarea>\n");
	
	
?>

<div class="clearing"></div>


