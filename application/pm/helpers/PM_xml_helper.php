<?php 
// loading xml files for whatever reason are not in the xml helper, so i wrote my own.
// takes a url of a valid xml file, opens it, parses it, and returns the final object.
	function xmlLoader($file){
		$dom = new domDocument;
		$dom->loadXML(file_get_contents($file));
			if (!$dom) {
			   echo 'Error while parsing the document';
			   exit;
			 }
		
		$s = simplexml_import_dom($dom);
		return $s;
		
		}


?>