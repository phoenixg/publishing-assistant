<?php
error_reporting(1);
class issueMaster extends CI_Controller {


	//constructor =======================================================================
	function __construct()
	{
		//set up the controll construct
		parent::__construct();	
		$this->load->helper(array ('directory', 'form', 'url', 'xml', 'common', 'file'));
		$this->load->model('Content_store_model');
		$this->load->model('Issue_model');
		$this->load->database();
		$this->load->config('pm_config');
		$this->journals = $this->config->item('journals');
	}

	/**
	 * index
	 *
	 * Default - List a number of recent issue for a publication.
	 *
	 * @author MGreen
	 * @return void
	 **/
	function index(){
		$uri = parseURI();      //grab the information contained in the uri     
		$data['jname'] = $uri['jnl'];  // set the jname from the uri, setting it into data makes it avalible to the templates.				
		$data_path = $this->journals[$data['jname']]['data_path']; // grab the data path from the config array.
		
		//Look Up Data for the issue published on the specified date
		//$issueData = getIssueDataByDate($this->journals[$jname]['publish_day'], DATA_PATH_SCI, $date);
		//$issueData = getIssueDataByDate($this->journals[$jname]['publish_day'], DATA_PATH_SCI, '06/02/2011');
		
		//Look up the recent issues, and gather some details for each one.
		$pi = array();
		
		//##-- need to change DATA_PATH_SCI to DATA_PATH_(upper of jname) 
		$previssues = getLastIssues($this->journals[$data['jname']]['publish_day'], $data_path, $date, 24);
		foreach ($previssues as $previssue) {
			list($tmpShortDate, $tmpIssue, $tmpVol) = explode("|",$previssue);
			$pi[] = array(
				"date"	=> $tmpShortDate ,
				"vol"	=> $tmpVol, 
				"issue"	=> $tmpIssue, 
				"count"	=>  $this->Content_store_model->getIssueArticleCount($tmpIssue),
				"isSpecial" => $this->Content_store_model->isSpecial($tmpIssue)
			);
		}
		
		//Fix the order they are held in the array, so that the newest is at the front.
		$pi = array_reverse($pi);
		
		$data['title'] = "Science Issue Manager";	
		//$data['issueData'] = $issueData;
		//$data['articleCount'] = $this->Content_store_model->getIssueArticleCount($issueData['ISSUE_NUM']);
		$data['issues'] = $pi;
		
		
		$data['content'] = $this->load->view('issue/issue_main', $data, TRUE);
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublish'), TRUE); 
		$this->load->view('pagewrapper-tb', $data);	
	
	}	
	
	
	/**
	 * viewIssueTOC
	 *
	 * Compile a grouped content-list for the specified source directories for a publication.
	 *
	 * @author MGreen
	 * @return void
	 **/
	function viewToc(){
		$uri = parseURI();
		$date = $uri['date'];
		$jname = $uri['jnl'];
		$data['jname'] = $uri['jnl'];
		$collection = array();
		$data_path = $this->journals[$data['jname']]['data_path']; // grab the data path from the config array.
		
		if ($date) {
		
			//Look Up Data for the issue published on the specified date
			$issueData = getIssueDataByDate($this->journals[$jname]['publish_day'], $data_path, $date);
			
			//Look up the base path to the XML files for articles
			$XMLRoot = getXMLPath($jname, $issueData, $this->journals);
			
			
			//Get the XML source directories from config
			$data_sources = $this->journals[$jname]['data_source'];

			//Get Data filename extension from config
			$ext = $this->journals[$jname]['ext'];	
			
			//Get list of files to ignore from config
			$ignore_list = $this->journals[$jname]['ignore_list'];
			
			//Loop over all data sources
			foreach ($data_sources as $data_source) {
			
				//Look up the identifier for this collection
				$name = $data_source['name'];
				
				//Set directory to look in
				$dir = $XMLRoot . $data_source['path'];
				
				//Look up articles in this section
				$articles = getDirectoryList($dir, $ext, $ignore_list);
				
				//Loop over all articles
				foreach ($articles as $articleFile)
				{
					//Grab the required metadata from the the XML file
					$article = $this->Issue_model->getArticle($articleFile);
					//Assemble the toc, grouping items by both 'source' and 'article type'
					$collection[$name][$article['articleType']][] = $article;	
				}
			
			}
			
			$data['title'] = $this->journals[$jname]['name'] . " Table of Contents ( " . $date . " )" ;
			$data['issueData'] = $issueData;
			$data['path'] = $XMLRoot;
			$data['collections'] = $collection;

		
		} else {
			$data['msg'] = "Invalid input date";
		}
		
		$data['content'] = $this->load->view('issue/issue_toc_list', $data, TRUE);
    $data['controller_scripts'] = $this->load->view('shared/script', array('script' => 'sciPublish'), TRUE); 
		$this->load->view('pagewrapper-tb', $data);
	}


	/**
	 * importIssue
	 *
	 * Import article content from XML for one full issue and store it in the datbase
	 *
	 * @author MGreen
	 * @return void
	 **/
	function importIssue() {
		$uri = parseURI();    
		$date = $uri['date']; 		//Grab date info from the URL
		$data['jname'] = $uri['jnl'];   //Specify a publication TODO: move this to a URL parameter	
		$collection = array();
		$data_path = $this->journals[$data['jname']]['data_path']; // grab the data path from the config array.
		
		
		$c_arr = array();			//Array to count number of files processed
		$m_arr = array();			//Message array for output

		//Read list of files we want to import
		$importDoiList = $this->input->post('doi');		
		
		if ($date && gettype($importDoiList)=='array') {

			//Look Up data for this issue
			$issueData = getIssueDataByDate($this->journals[$data['jname']]['publish_day'], $data_path, $date);			
			
			//REDUNDANT ** Check whether there are already articles in the database for this issue.
			//$ic = $this->Content_store_model->getIssueArticleCount($issueData['ISSUE_NUM']);
			
			//Look up the base path to the XML files for articles
			$XMLRoot = getXMLPath($data['jname'], $issueData, $this->journals);
			

			//Get the XML source directories from config
			$data_sources = $this->journals[$data['jname']]['data_source'];			

			//Get Data filename extension from config
			$ext = $this->journals[$data['jname']]['ext'];	
			
			//Get list of files to ignore from config
			$ignore_list = $this->journals[$data['jname']]['ignore_list'];	

			//Loop over all data sources
			foreach ($data_sources as $data_source) {
			
				//Look up the identifier for this collection
				$name = $data_source['name'];
				
				//Set directory to look in
				$dir = $XMLRoot . $data_source['path'];
				
				//Look up articles in this section
				$articles = getDirectoryList($dir, $ext, $ignore_list);
				
				//Loop over all articles
				foreach ($articles as $articleFile) {
					
					//strip path info from the article file string
					//$tdoi = str_replace($dir, "", $articleFile);
						
					//Grab the article metadata from the source file
					$article = $this->Issue_model->getArticle($articleFile);

					//Check if the article file name is in the list of those we're supposed to get.
					if (in_array($article['doi'], $importDoiList)) {

						//print("IMPORTED: " . $article['doi'] . " from " . $data_source['name'] . "\n" );
						
						//Check whether theres already something in the db with this DOI
						$doiexists = $this->Content_store_model->checkDoi($article['doi']);
						
						if ($doiexists) {
						
							if (count($doiexists)==1) {
						
								if ($article['issue'] > 0) {
						
									//Read the ID of the record we're dealing with
									$id = $doiexists[0]['id'];
							
									//Update the existing record with the new data
									$articleId = $this->Content_store_model->updateArticle(0,$article, $id);
								
									//If something is there already, we have ourselves a 'published ahead of print' situation.
									$m_arr[] = '<strong>Updated : </strong>"' . $article['title'] . '" (' . $article['doi']. ')';

								} else {
								
									//Don't update if the new content doesn't have an issue# defined.
									$m_arr[] = '<strong>Can\'t Overwrite Existing Data if no VIP Present: </strong>"' . $article['title'] . '" (' . $article['doi']. ')';
								
								}
									
									
							} else {

								//There are multiple articles with the same DOI - not good!.
								$m_arr[] = '<strong style="color:red;">PROBLEM: There are multiple articles with the DOI: ' . $article['doi'] . '</strong>';
							
							}
							
						} else {
						
							//Create a new record for this article
							$articleId = $this->Content_store_model->addArticle(0,$article);
						
						}

						//Keep track of how many articles we're processing
						if (isset($c_arr[$name])) {
							$c_arr[$name]++;
						} else {
							$c_arr[$name]=1;
						}
						
					} else {
					
						//print("SKIPPED: " . $article['doi'] . " from " . $data_source['name'] . "\n" );
					
					}
			
				}				
			}				
			
			$m_arr[] = "Import Complete";
				
			
			$data['title'] = $this->journals[$jname]['name'];
			$data['issueData'] = $issueData;			
			$data['msg'] = $m_arr;
			$data['articleCount'] = $c_arr;			
			
		} else {
			$msg = array("Invalid input date");
		}
			
		$data['content'] = $this->load->view('issue/issue_import_summary', $data, TRUE);
		$this->load->view('pagewrapper-tb', $data);
	}

	
	/**
	 * viewSpecialIssue
	 *
	 * Compile and display the TOC for the chosen issue from the XML article data.
	 *
	 * @author MGreen
	 * @return void
	 **/
	function viewSpecialIssue(){
		
		$jname = "sci";
		$date = parseURI();
		
		if ($date) {
		
			//Look Up Data for the issue published on the specified date
			$issueData = getIssueDataByDate($this->journals[$jname]['publish_day'], DATA_PATH_SCI, $date);		
		
			//Look up the base path to the XML files for articles
			$XMLRoot = getXMLPath($jname, $issueData, $this->journals);

			//Get list of all XML files for this issue;s articles
			$articles = directory_map($XMLRoot,1);	
			
			//Assemble TOC 
			$toc = array();
			foreach ($articles as $articleFile)
			{
				if ($articleFile != "go.xml") {
					$article = $this->Issue_model->getArticle($XMLRoot.$articleFile);
					$toc[$article['articleType']][] = $article;
				}
			}
			
			$data['title'] = $this->journals[$jname]['name'] . " Table of Contents ( " . $date . " )" ;
			$data['issueData'] = $issueData;
			$data['path'] = $XMLRoot;
			$data['sections'] = $toc;
		
		} else {
			$data['msg'] = "Invalid input date";
		}
		
		$data['content'] = $this->load->view('issue/issue_specialIssue', $data, TRUE);
		$this->load->view('pagewrapper-tb', $data);
	}	
	
	
  
}
