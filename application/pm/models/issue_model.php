<?php
class Issue_model extends CI_Model {

  var $title   = '';
  var $content = '';
  var $date    = '';

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  /**
   * Parse selected required details from article XML file
   * @author MGreen
   *
   * @param $path
   *	Path for XML (NLM Green) for this article
   *
   * @return  array of selected data extracted from the XML
   **/	
  function getArticle($path) {
    $article = array();		
    $subPageIndex = array("a" => 1,"b" => 2,"c" => 3,"d" => 4,"e" => 5,"f" => 6,"g" => 7,"h" => 8,"i" => 9,"j" => 10,"k" => 11,"l" => 12,"m" => 13,"n" => 14,"o" => 15,"p" =>16);		

    if (strpos($path, ".xml")>=1) {

      //Create a container for the XML
      $doc = new DOMDocument('1.0', 'utf-8');
      //Load data from the file
      $doc->load($path, LIBXML_DTDLOAD|LIBXML_DTDATTR);

      $xpath = new DOMXPath($doc);
      $xsl = new DOMDocument;
      // $xsl->load('application/pm/xsl/sci_article_main.xsl');
      $xsl->load('application/pm/xsl/simple.xsl');

      $proc = new XSLTProcessor();
      $proc->importStyleSheet($xsl);

      $processed_xml = $proc->transformToXML($doc);

      //Extract data from the <FRONT> section of the XML (where all the metadata is)
      //This prevents possible collisions with duplicate tags in the body text, e.g. in the references
      $backup_body = $doc;
      $doc = $doc->getElementsByTagName('front')->item(0);

      //**************************************************************
      //** EXTRACT REQUIRED DATA **
      //**************************************************************

      //Issue ID
      $id = $this->lookup($doc, 'journal-id');

      //Volume
      $article['volume'] = $this->lookup($doc, 'volume');

      //Issue
      $article['issue'] = $this->lookup($doc, 'issue');

      //Page Number and Sub-page, e-location
      $article['page'] = $this->lookup($doc, 'fpage');
      // TODO: the elocation-id is NOT a page, but for purposes of the permanent location is serves a similar purpose. 
      // At some point there may be both a valid page and elocation-id, and the logic for deciding which to use should 
      // be closer to the controller
      $article['subPage'] = $this->lookup($doc, 'fpage', 'seq');
      $article['subPageNumeric'] = $subPageIndex[$article['subPage']];
      $article['lastPage'] = $this->lookup($doc, 'lpage');
      $article['elocation-id'] = $this->lookup($doc, 'elocation-id');
      if (empty($article['page'])) { $article['page'] = $article['elocation-id']; }

      //Syndication
      $syndication_query = $xpath->query("/article/front/article-meta/custom-meta-wrap/custom-meta/meta-value[preceding-sibling::meta-name='syndication']");
      $article['syndication'] = $syndication_query->item(0)->nodeValue; // by default, all articles are syndicated

      //Publish Date
      //**TODO Add Loop for case where there are both epub and ppub dates.
      $m = $d = $y = '';
      $pubs = $doc->getElementsByTagName('pub-date');
      if ($pubs) {
        foreach($pubs as $pub)
        {
          $d = $this->lookup($pub, 'day');
          $m = $this->lookup($pub, 'month');
          $y = $this->lookup($pub, 'year');	
        }
        $article['pubDate'] = $y . "-" . $m . "-" . $d;
      }

      //$article['title'] = $this->lookup($doc, 'article-title');
      $title_query = $xpath->query("/article/front/article-meta/title-group/article-title");
      $article['title'] = $this->replace_nlm_markup($this->getNodeInnerHTML($title_query->item(0)));

      //Article Categories 
      $article['fields'] = array();
      $article['articleType'] = "";
      $article['overline'] = "";
      $article['heading'] = "";
      $article['special'] = false;

      $subgroups = $doc->getElementsByTagName('subj-group');
      if ($subgroups){
        foreach($subgroups as $subgroup)
        {
          $type = (strtolower(trim($subgroup->getAttribute('subj-group-type'))));
          $value = $this->lookup($subgroup, 'subject');

          switch($type) {
          case 'article-type':
            $article['articleType'] = $value;
            if (strrpos($value, 'Special Issue')!== false) {
              $article['special'] = true;
            }
            break;
          case 'heading':
            $article['heading'] = $value;
            break;
          case 'overline':
            $article['overline'] = $value;
            break;						
          case 'field':
            $article['fields'][] = $value;
            break;					
          }
        }
      }

      //Summary
      $article['teaser'] = "";
      $article['web-summary'] = "";
      $article['excerpt'] = "";
      $article['full'] = "";
      $abstracts = $doc->getElementsByTagName('abstract');

      // setup the XSL processor
      $axsl = new DOMDocument;
      $axsl->load('application/pm/xsl/abstracts.xsl');
      $aproc = new XSLTProcessor();

      foreach($abstracts as $abstract) {
        $atype = $abstract->getAttribute('abstract-type');

        // convert abstract node to XML and make a create a new DOMDocument from it
        $abstract_XML = $abstract->ownerDocument->saveXML($abstract);
        $aXML = new DOMDocument('1.0', 'utf-8');
        $aXML->loadXML($abstract_XML);

        // Add the issue attribute to the related article tags if it isn't there
        $related_article_nodes = $aXML->getElementsByTagName('related-article');
        foreach ($related_article_nodes as $ra_node) {
          if (!$ra_node->hasAttribute('issue')) {
            $ra_node->setAttribute('issue',$article['issue']);
          }
        }
        // Apply the XSL 
        $aproc->importStyleSheet($axsl);
        $processed_abstract = $aproc->transformToXML($aXML);

        // set the abstract variable(s)
        if(strtolower(trim($atype)) == 'teaser') {
          $article['teaser'] = $processed_abstract;
        }elseif(strtolower(trim($atype)) == 'web-summary'){
          $article['web-summary'] = $processed_abstract;
        }elseif(strtolower(trim($atype)) == 'excerpt'){
          $article['excerpt'] = $processed_abstract;
        }
      }

      //foreach($backup_body as $node){			   
      $article['full'] = $processed_xml;
      //}


      //DOI
      $article['doi'] = "";			
      $aids = $doc->getElementsByTagName('article-id');
      if ($aids) {
        foreach($aids as $aid)
        {
          $aidType = $aid->getAttribute('pub-id-type');
          if(strtolower(trim($aidType)) == 'doi'){
            $article['doi'] = $aid->nodeValue;
          }
        }
        //If we haven't found a DOI in the data, construct a default.
        if ($article['doi'] == "") {
          $article['doi'] = "10.1126/science." . $article['volume'] . "." . $article['issue'] . "." . $article['page'];

          //If we have a sub-page append this, to keep the doi unique.
          if ($article['subPage']) {
            $article['doi'] .= "-" . $article['subPage'];
          }

        }
      }

      //Authors (XML)
      $author_metadata = array();
      $authors = $doc->getElementsByTagName('contrib');
      foreach($authors as $author) {
        $author_metadata[] = array(
          'type' => $author->getAttribute('contrib-type'),
          'name' => array(
            'surname' => $this->lookup($author, 'surname'),
            'given-names' => $this->lookup($author, 'given-names')
          )
        );
      }

      if (count($author_metadata) > 0) {
        $article['author'] = json_encode($author_metadata);
      } else {
        $article['author'] = " ";
      }

      //Editor (XML)
      $article['editor'] = "";
      $metas = $doc->getElementsByTagName('custom-meta');
      foreach($metas as $meta) {
        $metaval = $this->lookup($meta, 'meta-name');
        if ($metaval == "Editor") {
          $article['editor'] = $metaval;
        }
      }

/*
      //TODO: 20130221: Test all eAlerts to make sure this function is safe to remove
      if ($id == "SCIENCE")
      {
									if(strlen($article['page']) > 2){
												$article['url'] = "http://www.sciencemag.org/content/" . $article['volume'] . "/" . $article['issue'] . "/" . $article['page'] ;
									}else{
												$article['url'] ="http://www.sciencemag.org/lookup/doi/".$article['doi'];
									}
      }
      else if ($id == "sigtrans")
      {
        $article['url'] = "http://stke.sciencemag.org/cgi/content/abstract/sigtrans;" . $article['volume'] . "/" . $article['issue'] . "/" . $article['page'] ;
      }
      else if ($id == "STM")
      {
        $article['url'] = "http://".$id.".sciencemag.org/content/" . $article['volume'] . "/" . $article['issue'] . "/" . $article['page'] ;
      }
      else {
        log_message("error","Journal ID could not be found in {$article['issue']}\n");
        //$article['url'] = 'error: Id of '.$id.' not found';
      }
      if ($article['subPage']) {
        $article['url'] .= "." . $subPageIndex[$article['subPage']];
      }
      // add '.full' extention onto URLs
      // $article['url'] .= ".full";

       */
      return $article;

    }
    else { 
      return false; 
    }
  }

  /**
   * Parse required details from all article XML into a single collection
   * @author MGreen
   *
   * @param $path
   *	Base path for XML articles
   *
   * @param $articles
   *	Array of article XML file locations 
   *
   * @return  toc object for this issue
   **/	
  function buildTOC ($path, $articles) {
    $i=0;
    $sections = array();

    foreach ($articles as $articleFile) {

      if (strpos($articleFile, ".xml")>=1) {
        $doc = new DOMDocument();
        $doc->load($path . $articleFile);
        $article = array();

        if($i == 0) {
          $ID = $doc->getElementsByTagName('journal-id')->item(0)->nodeValue;
          $Title = 'Science Online';
          $Vol = $doc->getElementsByTagName('volume')->item(0)->nodeValue;;
          $IssueNum = $doc->getElementsByTagName('issue')->item(0)->nodeValue;
        }

        $pageNum = $doc->getElementsByTagName('fpage')->item(0)->nodeValue;
        $seq = "-" . $doc->getElementsByTagName('fpage')->item(0)->getAttribute('seq');
        if ($seq != "-") {
          $this->pageNum .= $seq;
        }

        $subgroups = $doc->getElementsByTagName('subj-group');
        foreach($subgroups as $subgroup) {
          $type = $subgroup->getAttribute('subj-group-type');
          $subject = $subgroup->getElementsByTagName('subject')->item(0)->nodeValue;
          if(strtolower(trim($type)) == 'article-type') {
            $entry->Section=$subject;
            if (strrpos($subject, 'Special Issue')!== false) {
              $this->IsSpecialIssue = true;
            }
            break;
          }
        }

        $article['page'] = $pageNum;
        $article['subject'] = $subject;

        $article['title'] = $doc->getElementsByTagName('article-title')->item(0)->nodeValue;
        $article['author'] = "[Author]";
        $article['url'] = "http://www.sciencemag.org/cgi/content/full/" . $Vol . "/" . $IssueNum . "/" . $pageNum;
        $article['webSummary'] = "";

        $abstracts = $doc->getElementsByTagName('abstract');
        foreach($abstracts as $abstract)
        {
          $atype = $abstract->getAttribute('abstract-type');
          if(strtolower(trim($atype)) == 'teaser')
          {
            $article['webSummary'] = $abstract->nodeValue;
          }
        }

        $sections[$subject][] = $article;

        $i++;
      }

    }
    return $sections;
  }
  /**
    * lookup
    *  Lookup a node value from a DOM document
    *
    * @param mixed $d
    *  DOM element
    * @param mixed $n
    *  tag name
    * @param mixed $a
    *  Attribute
    * @access public
    * @return void
    */
  function lookup($d, $n, $a=false) {

    $data = ''; // default

    //Get node list
    $nl = $d->getElementsByTagName($n)->item(0);

    if (gettype($nl)=='object') {

      //If an attribute was specified, look for that
      if ($a) {
        $data = $nl->getAttribute($a);
        //Otherwise just look for the node value
      } else {
        $data = $nl->nodeValue;
      }
    }

    return $data;

  }

  function get_last_ten_entries()
  {
    $query = $this->db->get('entries', 10);
    return $query->result();
  }
    
  /**
   *
   * Returns the node as a fragment of XML while preserving children (markup tags)
   *
   * @param DOMNode $node
   *   a node from a nodelist (can be from an xpath query)
   * @return string
   *   a string representation of the XML node, preserving markup
   *
   */
  function getNodeInnerHTML($node) { 
      $innerHTML= ''; 
      $children = $node->childNodes; 
      foreach ($children as $child) { 
          $innerHTML .= $child->ownerDocument->saveXML( $child ); 
      } 
      return $innerHTML; 
  } 
  /**
   * replace_nlm_markup
   *  replaces markup like <italic> with the correct html markup
   *
   * @param mixed $str
   * @access public
   * @return void
   */
  function replace_nlm_markup( $str ) {
    $str = str_replace('<italic>', '<em>', $str);
    $str = str_replace('</italic>', '</em>', $str);
    return $str;
  }
  

}
