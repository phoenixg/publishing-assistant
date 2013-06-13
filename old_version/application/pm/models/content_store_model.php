<?php
class Content_store_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	  * Inserts a new article into the content store
	  * @author MGreen
	  *
	  *@param $publication
	  *	Numberic ID for the target publication
	  *
	  *@param $details
	  *	Array of details for the article
	  *
	  * @return id of newly created record.
	  **/
	function addArticle($publication, $details)
	{
		$data = array(
			'PublicationId' => (int)$publication,
			'Vol' => $details['volume'],
			'Issue' => $details['issue'],
			'Page' => $details['page'],
			'SubPage' => $details['subPage'],
			'LastPage' => $details['lastPage'],
			'Doi' => $details['doi'],
			'ArticleType' => $details['articleType'],
			'Heading' => $details['heading'],
			'Title' => $details['title'],
			'Teaser' => $details['teaser'],
			'Overline' => $details['overline'],
			'Authors' => $details['author'],
			'Editor' => $details['editor'],
			'PubDate' => $details['pubDate'],
			'CreateDate' => date("Y-m-d H:i:s")
		);		
		
		//Check that we have a doi before proceeding.
		if ($details['doi'] != null) {
			$this->db->insert('articles', $data);
		} else {
			return false;
		}

		//Get the ID of the record just created.
		$aId = $this->db->insert_id();

		//Get the field data
		$fieldData = $this->getFields();
		
		if (count($details['fields'])) {
			foreach ($details['fields'] as $fieldCode) {
			
				$fId = $fieldData[strtoupper($fieldCode)]['id'];
		
				if (isset($fId)) {
		
					//print ("Set: " . $fieldCode . " " . $fId . "\n");
					
					$data = array(
						'ArticleId' => $aId,
						'FieldId' => $fId
					);			
					$this->db->insert('articlefields', $data);
					
				} else {
				
					print ("Not Set: [" . $fieldCode . "] " . $fId . "\n");

				}
			}
		}
		
		return $aId;
		
	}


	/**
	  * Updates an existing article with new content.
	  * @author MGreen
	  *
	  *@param $publication
	  *	Numeric ID for the target publication
	  *
	  *@param $id
	  *	Numeric ID for article to update
	  *
	  *@param $details
	  *	Array of details to be updated in the article.
	  *
	  * @return 
	  * doi of record that was updated.
	  *
	  **/
	function updateArticle($publication, $details, $id)
	{
		$data = array(
			'PublicationId' => (int)$publication,
			'Vol' => $details['volume'],
			'Issue' => $details['issue'],
			'Page' => $details['page'],
			'SubPage' => $details['subPage'],
			'LastPage' => $details['lastPage'],
			'Doi' => $details['doi'],
			'ArticleType' => $details['articleType'],
			'Heading' => $details['heading'],
			'Title' => $details['title'],
			'Teaser' => $details['teaser'],
			'Overline' => $details['overline'],
			'Authors' => $details['author'],
			'Editor' => $details['editor'],
			'PubDate' => $details['pubDate'],
			'CreateDate' => date("Y-m-d H:i:s")
		);		
		
		//Check that a doi has been specified.
		if ($details['doi'] != null) {
		
			//Update the existing record
			$this->db->where('doi', $details['doi']);
			$this->db->update('articles', $data);
			
			//Look up all of the fields
			$fieldData = $this->getFields();
			
			//Erase all currently stored fields and replace with those provided.
			$this->db->where('ArticleId', $id);
			$this->db->delete('articlefields'); 

			//Store references to Field Codes in lookup table.		
			if (count($details['fields'])) {
				foreach ($details['fields'] as $fieldCode) {
				
					$fId = $fieldData[strtoupper($fieldCode)]['id'];
			
					if (isset($fId)) {
						
						$data = array(
							'ArticleId' => $id,
							'FieldId' => $fId
						);			
						$this->db->insert('articlefields', $data);
						
					} else {
					
						print ("Not Set: [" . $fieldCode . "] " . $fId . "\n");

					}
				}
			}			
			
	
			//Send the DOI back, for reference
			//TODO - might be more useful to return the ID of the article row instead.
			return $details['doi'];
	
		} else {
		
			return false;
			
		}
		
		
		
	}	
	
	/**
	  * Returns an associative array of field data, keyed by the fieldCode.
	  * @author MGreen
	  *
	  * @return array
	  **/
	function getFields()
	{
		$query = $this->db->get('fields');
		
		//Transform the result set to an associative array
		$fields = array();
		foreach ($query->result() as $item) {
			$fields[$item->FieldCode] = array('id'=>$item->FieldId, 'name'=>$item->Name);
		}
		
		return $fields;
	}	

	
	/**
	  * Returns number of Articles for a specified DOI.
	  * @author MGreen
	  *
	  * @param $doi
	  * DOI to look up.
	  *
	  * @return 
	  * array of articles containing the ID and title of each, or false if there are none
	  * Note, this should really be unique.
	  *
	  **/
	function checkDoi($doi)
	{
		
		$results = array();
	
		//Lookup Doi in the database.
		$this->db->select('ArticleId , Title');
		$this->db->where('Doi', $doi);
		$query = $this->db->get('articles');
			
		//Build array of article Ids and titles, with the supplied DOI. There should only be one.
		if ($query->num_rows > 0) {
			
			foreach ($query->result() as $article) {
				$results[] = array('id'=>$article->ArticleId, 'title'=>$article->Title);
			}
			
			return $results;
			
		} else{
		
			return false;
			
		}
		
	}

	
	/**
	  * Returns number of Articles for a given issue. Note this will not include articles published ahead of print.
	  * @author MGreen
	  *
	  * @param $issue
	  * Issue number.
	  *
	  * @return $count
	  * number of articles for this issue
	  *
	  * @return integer
	  **/
	function getIssueArticleCount($issue)
	{
		$this->db->where('issue', $issue);
		$this->db->from('articles');
		$count = $this->db->count_all_results();
		
		return $count;
	}

	
	/**
	  * Returns whether an issue is a special issue or not.
	  * @author MGreen
	  *
	  * @return boolean
	  **/
	function isSpecial($issue_num)
	{
		$this->db->like('ArticleType', 'special');
		$this->db->where('issue', $issue_num);
		$this->db->from('articles');
		$count = $this->db->count_all_results();
		
		return ($count>0)? true:false;
	}

	/**
	* Returns an article specified by publication, volume, issue and page numbers
	* @author MGreen
	*
	* @param $publication
	*	Numberic ID for the selected publication
	*
	* @param $volume
	*	Numeric ID for the volume
	*
	* @param $issue
	*	Numeric ID for the issue number
	*
	* @param $page
	*	Numberic ID for the first page number
	*
	* @return  array  of article data
	**/	
	function getArticle($publication, $volume, $issue, $page)
	{
		//TO DO 
	}	

	
}