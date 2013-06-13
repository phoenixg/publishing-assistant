<?php
class Templatemaster extends CI_Controller {

	//*********************************************************************************
	//
	//                              CLASS CONSTRUCTOR
	//
	//********************************************************************************
	function __construct()
	{
		//set up the controll construct
		parent::__construct();	
		//call needed helpers
		$this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));		

	}

	//*********************************************************************************
	//
	//                              PAGE ACTIONS
	//
	//********************************************************************************
	
	//landing page
	//INDEX() ===================================================
	// Provides functionality for the index/landing page. 
	//===========================================================
	function index()
	{
		$data['title'] = "Overview";
		$data['content'] = $this->load->view('landing_page','',TRUE);
		$this->load->view('pagewrapper-tb', $data); 
	} // end function


	//TEMPLATELIST() ============================================
	//list of templates and data files to use
	//===========================================================	
	function templatelist(){
		// figure out which template group to load
		$templates = $this->uri->segment(3);
				
		//load the template group, with information on if it contains patterns
		$data['aval_xml'] = directory_map(XML_PATH);
		
		//add data inputs to template list. 
		$data['aval_templ'] = $this->dataInput($templates);
		
		//let the save function know where to grab the templates from
		$data['temp_direct'] = $templates;
		
		//+++++ move this to the View Template.
		$data['title'] = "Science Weekly Updates"; // put this into 'content'
		
		//send to sub-view to process the content for the final view
		$data['content'] = $this->load->view('template/template_list', $data, TRUE);
		
		//output final view. 
		$this->load->view('pagewrapper', $data);
	} // end function
	
	
	//TEMPLATESAVE() ============================================
	// Compiles and publishes templates selected from TEMPLATELIST()
	//===========================================================
	function templatesave()
	{
		$templateList = $_POST['fileArray'];
		$customData = '';
		if(isset($_POST['customData'])){
			$customData = $_POST['customData'];
		}
		$xmlData ="";
		if(file_exists(XML_PATH.$_POST['dataFile'])){
			$xmlData = xmlLoader(XML_PATH.$_POST['dataFile']);
		}
		$data['output'] = array();
		
		// this is probably gonna have to go into a loop of some kind
		foreach( $templateList as $templateFile){
		
			// look up common date for this journal
			$comData = getIssueDataByDate("friday", DATA_PATH_SCI, $_POST['ISSUE_DATE']);
	
			// load contents of current template file
			$templateContent = file_get_contents(TEMPLATE_PATH.$_POST['tempDirectory'].'/'.$templateFile);
			
			// replace patterns with data from input xml file.
			$output = $this->patternReplace($templateContent, $xmlData, $comData);

			// replace singles with data from submitted form.
			// *TODO*
			$finalOutput[0] = $this->singleReplace($output, $comData, $customData);
			
			//remove the "<!--replaced--> tags,these are used to show data that was inserted into the template for easy viewing.
			$ouputFileName = str_replace ("<!--/REPLACED-->", "", str_replace ("<!--REPLACED-->", "", $this->singleReplace($templateFile, $comData, $customData)));
			
			//Check the output path, if there is no output path, use default template.
			$finalOutput = $this->findOutputPath($finalOutput, $ouputFileName);
			
			// save the out to the file system
			write_file($finalOutput[1], str_replace ("<!--/REPLACED-->", "", str_replace ("<!--REPLACED-->", "", $finalOutput[0])));
			
			// write output message to display
			$outDisplay = str_replace("\n","<br/>", str_replace ("<", "&lt;", $finalOutput[0])) ;
			$msg = array ("title" => $finalOutput[1] , "html" => str_replace ("&lt;!--/REPLACED-->", "</span>", str_replace ("&lt;!--REPLACED-->", "<span style='color: red;'>", $outDisplay)));
			array_push($data['output'], $msg);
		}
		
		//print_r ($this->replaceCount);
		$data['replaced']= $this->replaceCount;
		$data['title'] = "Publishing Master - Template Save";
		$data['content'] = $this->load->view('template/template_saved', $data, true);
		$this->load->view('pagewrapper', $data);
		
	}//end function 
	

	//*********************************************************************************
	//
	//                         TOOLS FOR DATA MANIPULATION
	//
	//********************************************************************************
	
	//PATTERNFINDER() ===========================================
	//finds any pattern in the templates that need to be replaced
	// Return an array of all matched patterns in the template file.
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. Template xhtml (in string form)
	//          2. Pattern to search for
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          Unique array of patterns in template.
	//===========================================================
	function patternFinder($contents, $pattern){
		
		preg_match_all($pattern, $contents, $matches);
		$matches = array_unique($matches[0]);	
		
		//return an array of matches to the provided pattern.
		return $matches;
	} // end function.
	
	
	//SINGLEREPLACE() ===========================================
	// Replaces all occurances of single line data, which is denoted
	// by the string {!{ and closes with }!}
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. Template xhtml (in string form)
	//          2. Common Data (Array)
	//          3. Custom Data (Array)
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          String of Template code with all single lines replaced.
	//===========================================================
	function singleReplace($templateMarkup, $comData, $cutsomData){
	
		$repl = $this->patternFinder($templateMarkup, "/{\!{[A-Z0-9a-z_:\/[:punct:]][^}.}]*}\!}/");
		
		foreach($repl as $marker){	
			$subP = str_replace ("}!}", "", str_replace ("{!{", "", $marker));
			if(isset($comData[$subP])){
				$templateMarkup = str_replace ($marker, "<!--REPLACED-->".$comData[$subP]."<!--/REPLACED-->", $templateMarkup);
				$this->replaceCount +=1;
			} else{
				$templateMarkup = str_replace ($marker, "<!--REPLACED-->".$cutsomData[$subP]."<!--/REPLACED-->", $templateMarkup);
				$this->replaceCount +=1;
			}
		}
	 
		return $templateMarkup;
	
	}//end function. 

	
	//REPLACEPATTERNS() ===========================================
	//replacePatterns - replaces code provided from PATTERNPOPULATE()
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. Template xhtml (in string form)
	//          2. XML object
	//          3. Common Data (Array)
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          String of Template code with all patterns replaced.
	//===========================================================
	function patternReplace($temp, $xml, $cData){
	
		$repl = $this->patternFinder($temp, "/{\*{[A-Z0-9a-z_:\/[:punct:]][^}.}]*}\*}/");
		
		foreach($repl as $marker){	
			//print $marker;
			$paternMakerInfo = "/[[:alnum:]][^\|\}]*/";
			preg_match_all($paternMakerInfo, $marker, $patData);
			//print_r($patData);
			$temp = str_replace ($marker, $this->patternPopulate($patData, $xml, $cData), $temp);
		}
		
		return $temp;
	
	}// end function
	
	
	//PATTERNPOPULATE() ===========================================
	// Takes paramaters and creates a populated pattern from the rules
	// provided in $patData.
	// NOTE: NUMBER_OF_ITERATIONS may be in the form of all (all items),
	// 2 (first two), or 2-5 (items 2 through 5).
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. Data on the pattern 
	//             - {*{TEMPLATE|XML_GROUP|NUMBER_OF_ITERATIONS}*}
	//          2. XML data object
	//          3. Common Data (Array)
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          String of code to replace pattern with. 
	//===========================================================
	function patternPopulate($patData, $xml, $cData){
		//print_r ($cData);
		$path = PAT_PATH.strtolower($patData[0][0]).".pat";
		$basePat = file_get_contents($path);
		$placeHolders = $this->patternfinder($basePat, "/{!{[A-Z0-9a-z_:\/[:punct:]][^}.}]*}!}/");
		
		//set up to use the all or limiter option.
		$loopWatch = false;
		$delim = false;
		if(is_numeric($patData[0][2])){ 
			$loopWatch = true;
		}elseif(strpos ( $patData[0][2], "-" )){
			$startStop = explode ("-", $patData[0][2]);
			$delim = true;
			//print_r ($startStop);
		}
		
		$output =''; 
		$loopControll = 0;
		
		//loop through the xml to look for the correct group.
		foreach($xml->group as $group){
		
			$groupString = $group->attributes()->name[0]."";
			$haystack=strtolower($patData[0][1]."");
			
			//check to make sure we are in the right group
			if($groupString == $haystack){	
			
				//loop through each of the groups, as q, to fill in the place holders.
				foreach ($group as $q){
					$loopControll +=1;
					$loopPat = $basePat;
					//for each placeholder, we are going to strip the {!{ and }!}, and then 
					//use the resulting string to get the data from the group item (q).
					//then use a string replace to replace the place holder with the data.
					//We also need to check to see if we are using a limiter, if we are that 
					// the limit hasn't been reached yet, if it has, don't do anything.				
					if($patData[0][2]>=$loopControll && $loopWatch == true && $delim == false || $loopWatch == false && $delim == false || $loopWatch == false && $delim == true && $loopControll>=$startStop[0] && $loopControll<=$startStop[1]){
						//print "Delim = ".$delim." loopcountroll = ".$loopControll;
						foreach($placeHolders as $p){
							$subP = str_replace ("{!{", "", $p);
							$subP = strtolower(str_replace ("}!}", "", $subP));
							if($q->$subP){
								$loopPat = str_replace ($p, "<!--REPLACED-->".$q->$subP."<!--/REPLACED-->", $loopPat);
								$this->replaceCount +=1;
							}else{ // if it's common datta, replace the markers with the common data. 
								$subMark = strtoupper($subP);
								$loopPat = str_replace ($p, "<!--REPLACED-->".$cData[$subMark]."<!--/REPLACED-->", $loopPat);
								$this->replaceCount += 1;
							}
						}
						
						$output = $output.$loopPat;
					}
					//add the resulting patter to the output
					
				}
			
			}
		
		}
		// return output.
		return 	$output;
	}// end function
	
	
	//DATAINPUT() ===========================================
	// returns an array of templates, containing information on  
	// single replacement values, as well as if they contain patterns. 
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. string of folder to in /templates
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          Array containing list of tempaltes and info on them
	//===========================================================
	
	function dataInput($templates){
		$commData= getIssueDataByDate("friday", DATA_PATH_SCI);
		$returnArray = array();
		$templateInfo = array();
		foreach(directory_map(TEMPLATE_PATH.$templates) as $template){
			$templateContent = file_get_contents(TEMPLATE_PATH.$templates.'/'.$template);
			$name = $this->patternFinder($templateContent, "/{\%DESC:\(\)[A-Z0-9a-z_:\/[:punct:][:space:]][^}.}]*\%}/");
			if($this->patternFinder($templateContent, "/{\*{[A-Z0-9a-z_:\/[:punct:]][^}.}]*}\*}/")){
				if($name){
					$templateInfo[] = array ('template' => $template, 'pattern' => true, 'name' =>str_replace ("%}", "", str_replace ("{%DESC:", "",$name[0])));	
				}else{
					$templateInfo[] = array ('template' => $template, 'pattern' => true, 'name' => $template);
				}
			}else{
				if($name){
					$templateInfo[] = array ('template' => $template, 'pattern' => false, 'name' =>str_replace ("%}", "", str_replace ("{%DESC:", "",$name[0])));	
				}else{
					$templateInfo[] = array ('template' => $template, 'pattern' => false, 'name' => $template);
				}
			}
			//unset ($templateContent);
		}
			
			//check for custom data, if it exists, send array of items to subview. 
		foreach($templateInfo as $customCheck){
			$templateContent = file_get_contents(TEMPLATE_PATH.$templates.'/'.$customCheck['template']);
			foreach($this->patternFinder($templateContent, "/{!{[A-Z0-9a-z_:\/[:punct:]][^}.}]*}!}/") as $pat){
				if(array_key_exists(str_replace ("}!}", "", str_replace ("{!{", "", $pat)), $commData) == false){
					$customCheck['customFields'][] = str_replace ("}!}", "", str_replace ("{!{", "", $pat));
				}
			}
			
			//print_r($customCheck);
			$returnArray[] = $customCheck;
		}
		//print_r ($returnArray);
		return $returnArray;
		
	}// end function
	
	
	//FINDOUTPUTPATH() ===========================================
	//  Returns an array that is clean of all output directives it 
	// they exist.
	// REQUIRES ++++++++++++++++++++++++++++++++++++++++++++++++
	//          1. Array, containing a string of code template data
	// RETURNS +++++++++++++++++++++++++++++++++++++++++++++++++
	//          Array containing final template code, as well as 
	//			correct output path.
	//===========================================================
	
	function findOutputPath($finalOutput, $templateFile){
		$OutPattern = $this->patternFinder($finalOutput[0], "/{\%OUTPUT:[A-Z0-9a-z_:\/[:punct:]][^}.}]*\%}/");
		if($OutPattern){
			$customOut = str_replace ("%}", "", str_replace ("{%OUTPUT:", "", $OutPattern[0]));
			$finalOutput[1] = $customOut.$templateFile;
		}else{
			$finalOutput[1] = OUTPUT_PATH.$templateFile;
		}
		
		$EndPattern = $this->patternFinder($finalOutput[0], "/{\%[A-Z0-9a-z_:\/[:punct:]][^}.}]*\%}/");
		if($EndPattern){
			foreach ($EndPattern as $pattern){
				$finalOutput[0] = str_replace ($pattern, "", $finalOutput[0]);
			}
		}
		
		return $finalOutput;
	}//end fucntion

	
}//****************************************************** END CLASS *************************************************

/* End of file templatemaster.php */
/* Location: ./system/application/pa/controllers/templatemaster.php */
