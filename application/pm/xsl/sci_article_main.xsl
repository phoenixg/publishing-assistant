<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:xhtml="http://www.w3.org/1999/xhtml" exclude-result-prefixes="xs" version="2.0">
    <xd:doc xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet">
        <xd:desc>
            <xd:p><xd:b>Created on:</xd:b> Nov 20, 2010</xd:p>
            <xd:p><xd:b>Author:</xd:b> Stewart Wills</xd:p>
            <xd:p></xd:p>
        </xd:desc>
    </xd:doc>

   <xsl:output method="xhtml" indent="no" />
   <xsl:include href="sci_article_variables.xsl" />
   <xsl:include href="sci_article_meta_templates.xsl" />
   <xsl:include href="sci_article_pres_templates.xsl" />

    <xsl:template match="/">

<html
      xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="en"
      lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title><xsl:value-of select="$articleTitle" /> | Science/AAAS</title>
      <meta name="DC.Format" content="text/html" />
      <meta name="DC.Language" content="en" />
      <xsl:choose>
         <xsl:when test="$articleFpageSeq ne 'none'"><meta content="{$articleVol}/{$articleIssue}/{$articleFpage}-{$articleFpageSeq}" name="citation_id" /></xsl:when>
          <xsl:otherwise><meta content="{$articleVol}/{$articleIssue}/{$articleFpage}" name="citation_id" /></xsl:otherwise>
       </xsl:choose>
<xsl:choose>
<xsl:when test="$articleDOI ne 'none'"><meta content="{$articleDOI}" name="citation_doi" /></xsl:when>
<xsl:otherwise>
<xsl:choose>
<xsl:when test="$articleFpageSeq ne 'none'"><meta content="10.1126/science.{$articleVol}.{$articleIssue}.{$articleFpage}-{$articleFpageSeq}" name="citation_doi" /></xsl:when>
<xsl:otherwise><meta content="10.1126/science.{$articleVol}.{$articleIssue}.{$articleFpage}" name="citation_doi"/></xsl:otherwise>
</xsl:choose>
</xsl:otherwise>
</xsl:choose>

      <link rel="stylesheet" type="text/css" media="all" href="http://www.sciencemag.org/shared/css/hw-global.css" />
      <link rel="stylesheet" type="text/css" media="print" href="http://www.sciencemag.org/shared/css/hw-print.css" />
      <link rel="stylesheet" type="text/css" media="all"
            href="http://www.sciencemag.org/publisher/css/hw-publisher-global.css" />
      <link rel="stylesheet" type="text/css" media="all"
            href="http://www.sciencemag.org/publisher/css/hw-publisher-sidebars.css" />
      <link rel="stylesheet" type="text/css" media="all" href="http://www.sciencemag.org/local/css/hw-local-all.css" />
      <link rel="stylesheet" type="text/css" media="all"
            href="http://www.sciencemag.org/shared/css/hw-page-content.css" />
      <link rel="stylesheet" type="text/css" media="all"
            href="http://www.sciencemag.org/shared/css/jquery.fancybox-1.3.4.css" />
      <link rel="stylesheet" type="text/css" media="all"
            href="http://www.sciencemag.org/publisher/css/hw-publisher-page-content.css" />
      <link rel="stylesheet" type="text/css" media="all" href="http://www.sciencemag.org/site/global_styles.css" /><link rel="stylesheet" type="text/css" media="all" href="./local_tests.css" /><script type="text/javascript" id="session-d130589937e1">var callbackToken='6282BA957E8F62F';</script><script type="text/javascript" id="session-d130589937e3">
			var OAS_country_code = 'US';
			var OAS_taxonomy = 'subject=SOCIOLOGY';
			
			
			
				
        var OAS_sitepage = window.location.hostname + window.location.pathname;
    
		
			var OAS_usertype = 'INST';
		</script><script type="text/javascript"
              src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script><script type="text/javascript"
              src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script><script type="text/javascript" src="http://www.sciencemag.org/publisher/js/publisher-library.js"></script><script type="text/javascript" src="http://www.sciencemag.org/shared/js/hw-shared.js"></script><script type="text/javascript" src="http://www.sciencemag.org/publisher/js/publisher-shared.js"></script><script type="text/javascript"
              src="http://oascentral.sciencemag.org/Scripts/oas_analytics.js"></script><script type="text/javascript" src="http://www.sciencemag.org/shared/js/pages/hw-content.js"></script><script type="text/javascript" src="http://www.sciencemag.org/shared/js/fancybox/jquery.fancybox-1.3.4.js"></script><script type="text/javascript" src="http://www.sciencemag.org/shared/js/fancybox/jquery.easing-1.3.pack.js"></script><script type="text/javascript"
              src="http://www.sciencemag.org/shared/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script><script type="text/javascript" src="http://www.sciencemag.org/publisher/js/publisher-rightslink.js"></script><script type="text/javascript" src="http://www.sciencemag.org/local/js/hw-local-rightslink.js"></script><script type="text/javascript" src="http://www.sciencemag.org/publisher/js/oas_ads.js"></script><script type="text/javascript" src="http://www.sciencemag.org/site/includefiles/google-analytics-include.js"></script><script type="text/javascript" src="./global_js.js"></script>
</head>
<body>
     <div class="hw-gen-page sci pagetype-content hw-pub-id-article hw-pub-section-{$pubSection}"
           id="pageid-content">
         <div id="hd">		
            			
            <div id="hd-main">
               				
               <div id="hd-logo"><a href="http://www.sciencemag.org/" title="*Science*/AAAS" tabindex="2"><em>Science</em>/AAAS</a></div>
               				
               				
               <ul id="nav-util">
                  <li><a href="http://aaas.org/" tabindex="3">AAAS.ORG</a></li>
                  <li><a href="http://www.sciencemag.org/feedback" tabindex="4">Feedback</a></li>

                  <li><a href="http://www.sciencemag.org/site/help/" tabindex="5">Help</a></li>
                  <li class="last"><a href="http://www.sciencemag.org/help/librarians/" tabindex="6">Librarians</a></li>
               </ul>
               				
               <div id="hd-search">
                  		
                  <form id="global_search_form" name="global_search_form" action="http://www.sciencemag.org/switch">
                     <fieldset><select id="site_area" name="site_area" size="1" tabindex="7">
                           <option value="sci" selected="selected">Science Magazine</option>

                           <option value="sciencenow">Daily News</option>
                           <option value="sigtrans">Science Signaling</option>
                           <option value="scitransmed">Science Translational Medicine</option>
                           <option value="sageke">SAGE KE</option>
                           <option value="scirecruit">Science Careers</option></select><input type="text" id="search-terms" name="fulltext" value="Enter Search Term"
                               tabindex="8" /><input type="image" src="http://www.sciencemag.org/publisher/img/spacer.gif" class="search-submit"
                               value="submit"
                               tabindex="9" /><a href="http://www.sciencemag.org/search" tabindex="10">Advanced</a></fieldset>
                  </form>

                  	
               </div>
               				
               <div id="authstring" class="suppress-header-login">
                  
                  <ul>
                     <li class="subscr-ref">ARTICLE PREVIEW</li>
                     <li class="no-left-border">
                        <a href="http://www.sciencemag.org/cgi/alerts/main">
                           Alerts
                           </a>
                        
                     </li>

                     <li>
                        <a href="http://www.sciencemag.org/site/my_account/access_rights.xhtml">
                           Access Rights
                           </a>
                        
                     </li>
                     <li>
                        <a href="http://www.sciencemag.org/site/my_account/">
                           My Account
                           </a>
                        
                     </li>

                     <li class="signout">
                        <a href="http://www.sciencemag.org/logout">
                           
                           
                           <strong xmlns="">Sign Out</strong>
                           
                           </a>
                        
                     </li>
                  </ul>           
                  
               </div>
               				
               <ul id="nav-main" class="nav-main">
                  <li class="item" id="nav_news"><a href="http://news.sciencemag.org/" id="nav-main-news" tabindex="14">News</a></li>

                  <li id="nav_magazine" class="item Active"><a href="http://www.sciencemag.org/journals" id="nav-main-journals" tabindex="15">Science Journals</a></li>
                  <li class="item" id="nav_careers"><a id="nav-main-careers" href="http://sciencecareers.sciencemag.org/"
                        tabindex="16">Careers</a></li>
                  <li class="item" id="nav_communities"><a id="nav-main-communities" href="http://blogs.sciencemag.org" tabindex="17">Communities</a></li>
                  <li class="item" id="nav_multimedia"><a href="http://www.sciencemag.org/multimedia/" id="nav-main-multimedia"
                        tabindex="18">Multimedia</a></li>
                  <li class="item" id="nav_collections"><a href="http://www.sciencemag.org/cgi/collection" id="nav-main-topics"
                        tabindex="19">Topics</a></li>
               </ul>

               				<a href="http://www.sciencemag.org/subscriptions/indiv_sub.xhtml" id="hd-subscribe" tabindex="20">Subscribe</a>
               				<a name="SectionNavigation" id="SectionNavigation"></a>
               
               <div id="hd-sub">
                  
                  <ul id="nav-sub" class="tab-list">
                     <li id="subnav_science_home"><a href="http://www.sciencemag.org/magazine"><em>Science</em> Home</a></li>
                     <li id="subnav_current_issue"><a href="http://www.sciencemag.org/content/current">Current Issue</a></li>

                     <li id="subnav_prev_issues" class="Active"><a href="http://www.sciencemag.org/content">Previous Issues</a></li>
                     <li id="subnav_science_express"><a href="http://www.sciencemag.org/content/early/recent"><em>Science</em> Express</a></li>
                     <li id="subnav_science_products"><a href="http://www.sciencemag.org/products/"><em>Science</em> Products</a></li>
                     <li id="subnav_my_science"><a href="http://www.sciencemag.org/my_science/">My <em>Science</em></a></li>

                     <li id="subnav_about_journal"><a href="http://www.sciencemag.org/site/about/index.xhtml">About the Journal</a></li>
                  </ul>
                  
               </div>
               
               			
            </div>		
            			
            <div id="site-breadcrumbs-wrapper">
               		
               <ul id="site-breadcrumbs">
                  <li class="firstcrumb"><a href="http://www.sciencemag.org/">Home</a></li>
                  <li><span class="bc-sep"> &gt; </span><a href="http://www.sciencemag.org/magazine"><em>Science</em> Magazine</a></li>

                  <li><span class="bc-sep"> &gt; </span><a href="http://www.sciencemag.org/content/{$articleVol}/{$articleIssue}.toc"><xsl:value-of select="$articleDay" /><xsl:text> </xsl:text><xsl:value-of select="$articleMonthSpelled" /><xsl:text> </xsl:text><xsl:value-of select="$articleYear" /></a></li>
                  <li class="lastcrumb"><span class="bc-sep"> &gt; </span><span class="quick-ref"><xsl:if test="$articleFirstAuthorSurname ne 'None'"><span class="cit-auth-list truncated"><span class="first-item"><span><xsl:value-of select="$articleFirstAuthorSurname" /></span><xsl:if test="$articleNumOfAuthors > 1"><span class="cit-sep cit-sep-truncation-suffix-article-author"><xsl:text> </xsl:text>et al.</span></xsl:if></span></span><span class="cit-sep cit-sep-after-article-author"><xsl:text>, </xsl:text></span> </xsl:if><cite><span class="cit-vol"><xsl:value-of select="$articleVol" /><xsl:text> </xsl:text></span><span class="cit-issue"><span class="cit-sep cit-sep-before-article-issue">(</span><xsl:value-of select="$articleIssue" /><span class="cit-sep cit-sep-after-article-issue">): </span> </span><span class="cit-pages"><span class="cit-first-page"><xsl:value-of select="$articleFpage" /></span><span class="cit-sep">-</span><span class="cit-last-page"><xsl:value-of select="$articleLpage" /></span></span></cite>  </span></li>

               </ul>
               	
            </div>
            		
         </div>
         <div id="leaderboard" class="leaderboard-ads">
            			<script type="text/javascript">
				OAS_AD('Top');
			</script>
            		</div>
         <div id="BodyWrapper">
            <div id="content-block">

               <div class="article-nav">
                  <a href="" title="Go to previous article"
                     class="page-nav_prev">Prev</a>
                  <span class="article-nav-sep"> | </span><span class="toc-link"><a href="http://www.sciencemag.org/content/{$articleVol}/{$articleIssue}.toc" title="Table of Contents">Table of Contents</a></span><span class="article-nav-sep"> | </span><a href="" title="Go to next article"
                     class="page-nav_next">Next</a>
                  
               </div>


<div id="slugline">
<cite>
<abbr title="{$journalTitle}" class="slug-jnl-abbrev"><xsl:value-of select="$journalTitle" /></abbr><xsl:text>
</xsl:text>
<span class="slug-pub-date"><xsl:value-of select="$articleDay" /><xsl:text> </xsl:text><xsl:value-of select="$articleMonthSpelled" /><xsl:text> </xsl:text><xsl:value-of select="$articleYear" />:</span><br /><xsl:text>
</xsl:text>
<span class="slug-vol"><xsl:text>Vol. </xsl:text><xsl:value-of select="$articleVol" /> </span><xsl:text>
</xsl:text>
<span class="slug-issue"><xsl:text>no. </xsl:text><xsl:value-of select="$articleIssue" /></span><xsl:text>
</xsl:text>
<span class="slug-pages"><xsl:text>pp. </xsl:text><xsl:value-of select="$articleFpage" />-<xsl:value-of select="$articleLpage" /></span><br /><xsl:text>
DOI: </xsl:text>
<xsl:choose>
<xsl:when test="$articleDOI ne 'none'"><span title="{$articleDOI}" class="slug-doi"><xsl:value-of select="$articleDOI" /></span></xsl:when>
<xsl:otherwise>
<xsl:choose>
<xsl:when test="$articleFpageSeq ne 'none'"><span title="10.1126/science.{$articleVol}.{$articleIssue}.{$articleFpage}-{$articleFpageSeq}" class="slug-doi">10.1126/science.<xsl:value-of select="$articleVol" />.<xsl:value-of select="$articleIssue" />.<xsl:value-of select="$articleFpage" />-<xsl:value-of select="$articleFpageSeq" /></span></xsl:when>
<xsl:otherwise><span title="10.1126/science.{$articleVol}.{$articleIssue}.{$articleFpage}" class="slug-doi">10.1126/science.<xsl:value-of select="$articleVol" />.<xsl:value-of select="$articleIssue" />.<xsl:value-of select="$articleFpage" /></span></xsl:otherwise>
</xsl:choose>
</xsl:otherwise>
</xsl:choose>
</cite>
</div>

<ul class="subject-headings last-child">
<li><xsl:value-of select="$articleHeading" /></li>
</ul>
<xsl:if test="article/front/article-meta/article-categories/subj-group[@subj-group-type='overline']">
<span class="article-overline"><xsl:apply-templates select="article/front/article-meta/article-categories/subj-group[@subj-group-type='overline']/subject" /></span>
</xsl:if>
<div class="article fulltext-view {$figTreatment}">
<h1 id="article-title-1"><xsl:apply-templates select="article/front/article-meta/title-group/article-title" /></h1>
<div class="contributors">
<xsl:apply-templates select="article/front/article-meta/contrib-group" />
<xsl:apply-templates select="article/front/article-meta/author-notes" />
</div>

<xsl:apply-templates select="article/front/article-meta/abstract" />

<xsl:apply-templates select="article/body" />

<xsl:apply-templates select="article/back" />

</div>
</div>
            <div id="col-2">

               
               
               <div class="content-box" id="article-cb-main">
                  <div class="cb-contents">
                     <h3 class="cb-contents-header"><span>Article Views</span></h3>
                     <div class="cb-section">
                        <ol>
                           <li><a href="" rel="view-abstract">Abstract</a><span class="free"></span></li>
                           <li class="notice"><span class="variant-indicator"><span>Full Text</span></span></li>

                           <li class="notice"><a href="" rel="view-full-text.pdf">Full Text (PDF)</a></li>
                           <li><a href="" rel="view-figures-only">Figures Only</a></li>
                           <li><a href="" rel="supplemental-data"
                                 class="dslink-expanded-figure">Supporting Online Material</a></li>
                        </ol>
                     </div>
                     <div class="cb-section" id="cb-art-svcs">

                        <h4 class="cb-section-header"><span>Article Tools</span></h4>
                        <ol>
                           <li><a href="">Save to My Folders</a></li>
                           <li><a href="">Download Citation</a></li>
                           <li><a href="">Alert Me When Article is Cited</a></li>
                           <li>
                              <div class="social-bookmarking">

                                 
                                 <ul class="social-bookmark-links">
                                    <li><a href=""><img src="http://www.sciencemag.org/shared/img/common/social-bookmarking/citeulike.gif"
                                               alt="Add to Post to CiteULike"
                                               title="Post to CiteULike" /><span class="soc-bm-link-text">Post to CiteULike</span></a></li>
                                 </ul>
                                 
                                 <p class="social-bookmarking-help"><a href="http://www.sciencemag.org/help/social_bookmarks.dtl">What's this?</a></p>
                                 
                              </div>
                           </li>
                           <li><a href="">E-mail This Page</a></li>

                           <li><a href="">Submit an E-Letter</a></li>
                           <li><a class="request-permissions" href="">Commercial Reprints and Permissions</a></li>
                           <li><a href="">View PubMed Citation</a></li>
                        </ol>
                     </div>
                     <div class="cb-section" id="cb-art-cit">
                        <h4 class="cb-section-header"><span>Related Content</span></h4>

                        <ol>
                           <div class="cb-section cb-subhead" id="cb-art-cit">
                              <h4 class="cb-section-header"><span>Similar Articles In:</span></h4>
                              <ol>
                                 <li><a href=""><em>Science</em> Magazine</a></li>
                                 <li><a  href="">Web of Science</a></li>
                                 <li><a href="">PubMed</a></li>
                              </ol>
                           </div>
                           <div class="cb-section cb-subhead" id="cb-art-gs">
                              <h4 class="cb-section-header"><span>Search Google Scholar for:</span></h4>
                              <ol>
                                 <li><a href="">Articles by  First Author</a></li>

                                 <li><a href="">Articles by  Last Author</a></li>
                              </ol>
                           </div>
                           <div class="cb-section cb-subhead" id="cb-art-pm">
                              <h4 class="cb-section-header"><span>Search PubMed for:</span></h4>
                              <ol>
                                 <li><a href="">Articles by  First Author</a></li>

                                 <li><a href="">Articles by  Last Author</a></li>
                              </ol>
                           </div>
                           <div class="cb-section cb-subhead" id="cb-art-gs">
                              <h4 class="cb-section-header"><span>Find Citing Articles in:</span></h4>
                              <ol>
                                 <li><a href="">Web of Science</a></li>
                                 <li><a href="">HighWire Press</a></li>
                                 <li><a href="">CrossRef</a></li>
                                 <li><a href="">Google Scholar</a></li>
                                 <li><a href="">Scopus</a></li>
                              </ol>
                           </div>
                        </ol>

                     </div>
                  </div>
               </div>
               
               
               <div class="sidebar" id="lnav-dynamic-block-my-science">
                  
                  
                  <h4><span>My Science</span></h4>
                  
                  <ol>
                     <li id="lnav-my-folders"><a href="http://www.sciencemag.org/cgi/folders">My Folders</a></li>
                     <li id="lnav-my-alerts"><a href="http://www.sciencemag.org/cgi/alerts/main">My Alerts</a></li>

                     <li id="lnav-my-saved-searches"><a href="http://www.sciencemag.org/cgi/folders#savedsearch">My Saved Searches</a></li>
                     <li id="lnav-smart-signin-signout"><a href="http://www.sciencemag.org/logout">Sign Out</a></li>
                  </ol>
                  
               </div>
               
               
               <div class="sidebar" id="cm_info">
                  
                  
                  <h4><span>More Information</span></h4>
                  
                  <h4 class="cb-section-header">More in Collections</h4>

                  
                  <ul>
                     <li><a href="">Field Code Name</a></li>
                  </ul>
                  
                  <h4 class="cb-section-header">Related Jobs from ScienceCareers</h4>
                  
                  <ul></ul>
                  
               </div>
               
               
               
               
            </div>
            <div id="col-3">

               
               <div class="Promo" id="Promo160x120">
                  		<script language="javascript">
			OAS_AD('Right1');
		</script>
                  	</div>
               <div class="Ad" id="Ad160x600">
                  		<script language="javascript">
			OAS_AD('Right2');
		</script>
                  	</div>
               <div class="Ad" id="Ad160x600">
                  		
                  <p class="AdInfo">

                     			<a href="http://www.sciencemag.org/site/help/advertisers/index.xhtml">To Advertise</a> <span class="Invisible">|</span> <a href="http://www.sciencemag.org/site/products/">Find Products</a>
                     		
                  </p>
                  	
               </div>
               
               
               
            </div>
         </div>
         <div id="footer">

            		
            <div id="ISSNLine">
               		
               <p>
                  			<em>Science</em>. ISSN 0036-8075 (print), 1095-9203 (online)
                  		
               </p>
               	
            </div>
            		
            <div id="FooterWrapper">
               			<a href="http://www.aaas.org/" title="Go to AAAS website"><img src="http://www.sciencemag.org/publisher/icons/logo.aaas.footer.gif" alt="AAAS Logo" width="52"
                       height="16"
                       border="0"
                       id="logo_aaas" /></a><a href="http://highwire.stanford.edu/" title="Go to High Wire Press website"><img src="http://www.sciencemag.org/publisher/icons/logo.hwp.footer.gif" alt="HWP Logo" width="115"
                       height="19"
                       border="0"
                       id="logo_hwp" /></a><div class="footer-links">
                  			<a href="http://news.sciencemag.org/">News</a>  |  
                  			<a href="http://www.sciencemag.org/journals/">Science Journals</a>  |  
                  			<a href="http://sciencecareers.sciencemag.org">Careers</a>  |  
                  			<a href="http://blogs.sciencemag.org/">Blogs and Communities</a>  | 
                  			<a href="http://www.sciencemag.org/site/multimedia/">Multimedia</a>  |  
                  			<a href="http://www.sciencemag.org/cgi/collection">Collections</a>  |  
                  			<a href="http://www.sciencemag.org/help">Help</a>  |  
                  			<a href="http://www.sciencemag.org/help/about/site_map.dtl">Site Map</a>  |  
                  			<a href="http://www.sciencemag.org/rss">RSS</a> 
                  			
                  		
               </div>

               <div class="footer-links">
                  			<a href="http://www.sciencemag.org/site/subscriptions">Subscribe</a>  |  
                  			<a href="http://www.sciencemag.org/cgi/feedback">Feedback</a>  |  
                  			<a href="http://www.sciencemag.org/site/help/about/privacy.xhtml">Privacy / Legal</a>  | 
                  			<a href="http://www.sciencemag.org/site/help/about/index.xhtml">About Us</a>  | 
                  			<a href="http://www.sciencemag.org/site/help/advertisers/index.xhtml">Advertise With Us</a>  |  
                  			<a href="http://www.sciencemag.org/site/help/about/contact.xhtml">Contact Us</a> 
                  		
               </div>

               			
               <div id="copyright">
                  		
                  <p><a href="http://www.sciencemag.org/site/help/about/copyright.xhtml">© <xsl:value-of select="$articleYear" /> American Association for the Advancement of Science. All Rights Reserved</a>.<br />AAAS is a partner of <a target="_blank" href="http://www.who.int/hinari/en/">HINARI</a>, <a target="_blank" href="http://www.aginternetwork.org/en/">AGORA</a>, <a target="_blank" href="http://www.oaresciences.org/en">OARE</a>, <a target="_blank" href="http://www.eifl.net/cps/sections/home">eIFL</a>, <a target="_blank" href="http://www.patientinform.org/">PatientInform</a>, <a target="_blank" href="http://www.crossref.org/">CrossRef</a>, and <a target="_blank" href="http://www.projectcounter.org/">COUNTER</a>.
                  </p>

                  	
               </div>
               		
            </div>

            	</div>
      </div>

</body>
</html>
    </xsl:template>

    <xsl:template match="article/body">
<xsl:apply-templates />
    </xsl:template>

    <xsl:template match="fig">
<div id="{@id}" class="fig pos-{@position} type-{@fig-type} {$figTreatment} odd">
<div class="fig-inline"><xsl:choose>
<xsl:when test="$articleWorkflow eq 'desktop' and @position eq 'float'"><a href=""><img alt="{label}" src="file:///k|/MAC/WWW/HIGHWIRE/{$articleVol}/{$articleMonthSpelled}/{$articleDay}%20{$articleMonthSpelled}--{$articleIssue}/images/small/{graphic/@xlink:href}.gif" /></a></xsl:when>
<xsl:when test="$articleWorkflow eq 'desktop' and @position eq 'anchor'"><a href=""><img alt="{label}" src="file:///k|/MAC/WWW/HIGHWIRE/{$articleVol}/{$articleMonthSpelled}/{$articleDay}%20{$articleMonthSpelled}--{$articleIssue}/images/medium/{graphic/@xlink:href}.gif" /></a></xsl:when>
<xsl:otherwise><a href=""><img alt="{label}" src="" /></a></xsl:otherwise>
</xsl:choose>
<xsl:if test="@position eq 'float'"><div class="callout"><span>View larger version:</span>
<ul class="callout-links">
<li><a href="{$articleFpage}/{@id}.html">In this window</a></li>
<li><a class="in-nw" href="{$articleFpage}/{@id}.expansion.html">In a new window</a></li>
</ul>
<ul class="fig-services">
<xsl:if test="$figTreatment eq 'type-figure'"><li class="ppt-link"><a href="/powerpoint/{$articleVol}/{$articleIssue}/{$articleFpage}/{@id}">Download PowerPoint Slide for Teaching</a></li></xsl:if>
</ul>
</div>
</xsl:if>
</div>
<div class="fig-caption attrib">
   <xsl:apply-templates select="label" />
   <xsl:apply-templates select="caption" />
   <xsl:apply-templates select="attrib" />
<div class="sb-div caption-clear"></div>
</div>
</div> 
    </xsl:template>

   <xsl:template match="fig/label">
<span class="fig-label"><xsl:apply-templates /></span>
   </xsl:template>

   <xsl:template match="fig/caption/title">
<span class="caption-title"><xsl:apply-templates /></span><xsl:text> </xsl:text>
   </xsl:template>

   <xsl:template match="fig/caption/p">
      <p class="first-child"><xsl:apply-templates /></p>
   </xsl:template>

   <xsl:template match="fig/attrib">
<q class="attrib" id=""><xsl:apply-templates /></q>
   </xsl:template>

   <xsl:template match="inline-graphic">
<img class="inline-graphic" src="file:///k|/MAC/WWW/HIGHWIRE/{$articleVol}/{$articleMonthSpelled}/{$articleDay}%20{$articleMonthSpelled}--{$articleIssue}/images/medium/{@xlink:href}.gif" />
   </xsl:template>



    <xsl:template match="ext-link[@ext-link-type='uri']">
<a href="{@xlink:href}"><xsl:apply-templates /></a>
    </xsl:template>


   <xsl:template match="table-wrap">
<div class="table pos-anchor" id="{@id}">
      <xsl:apply-templates select="table" />
<div class="table-caption">
      <xsl:apply-templates select="label" />
      <xsl:apply-templates select="caption" />
<div class="sb-div caption-clear"></div>
</div>
</div>
   </xsl:template>

   <xsl:template match="table-wrap/label">
<span class="table-label"><xsl:apply-templates /></span>
   </xsl:template>

   <xsl:template match="table-wrap/caption">
      <xsl:apply-templates />
   </xsl:template>

   <xsl:template match="table">
<div class="table-inline">
      <table>
      <xsl:apply-templates select="col" />
      <xsl:apply-templates select="thead" />
      <xsl:apply-templates select="tbody" />
      </table>
</div>
   </xsl:template>

   <xsl:template match="table/col">
<col width="{@width}" /><xsl:text>
</xsl:text>
   </xsl:template>

   <xsl:template match="table/thead">
<thead>
   <xsl:apply-templates />
</thead>
   </xsl:template>

   <xsl:template match="table/tbody">
<tbody>
   <xsl:apply-templates />
</tbody>
   </xsl:template>

   <xsl:template match="tr">
<tr>
   <xsl:apply-templates />
</tr>
   </xsl:template>

   <xsl:template match="td">
<td valign="{@valign}" align="{@align}" scope="{@scope}" rowspan="{@rowspan}" colspan="{@colspan}" class="table-{@align} table-v{@valign}">
   <xsl:apply-templates />
</td>
   </xsl:template>


   <xsl:template match="disp-formula">
<span class="disp-formula" id="{@id}">
    <xsl:apply-templates select="mml:math"/>
   <xsl:apply-templates select="./label" />
   <xsl:apply-templates /></span>
    </xsl:template>

   <xsl:template match="disp-formula/label">
<span class="disp-formula-label"><xsl:apply-templates /></span>
   </xsl:template>

   <xsl:template match="mml:math">
<math xmlns="http://www.w3.org/1998/Math/MathML"><xsl:apply-templates /></math>
   </xsl:template>

   <xsl:template match="mml:*">
      <xsl:element name="{local-name()}"><xsl:apply-templates /></xsl:element>
   </xsl:template>


    <xsl:template match="supplementary-material[@xlink:href='http://www.sciencemag.org/multimedia/components/audio/audioPlayer.swf']">
                           <div id="{media/@id}" class="supplementary-material video-content">
                              <div class="supplementary-material-inline video-inline"><script type="text/javascript" src="http://www.sciencemag.org/multimedia/swfobject.js"></script><div id="{@id}">
                                    <p>This item requires the Flash plug-in (version 8 or higher). JavaScript must be enabled in your browser.</p>
                                    <p><a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Download</a> the latest version of the free Flash plug-in.
                                    </p>
                                 </div><script type="text/javascript">var so = new SWFObject("http://www.sciencemag.org/multimedia/components/audio/audioPlayer.swf", "<xsl:value-of select="@id" />", "320", "92", "8", "#ffffff");
                    so.addVariable("playlist_file", "<xsl:value-of select="media/@xlink:href" />");
                    
                    so.addVariable("title_text", "<xsl:value-of select="media/alt-text" />");
                        
                        so.addVariable("image_file", "<xsl:value-of select="media/graphic/@xlink:href" />");
                        so.write("<xsl:value-of select="@id" />");</script></div>

                              <div class="supplementary-material-caption"></div>
                           </div>
    </xsl:template>


    <xsl:template match="supplementary-material[@xlink:href='http://www.sciencemag.org/multimedia/components/video/simplePlayer.swf']">
                           <div id="{media/@id}" class="supplementary-material video-content">
                              <div class="supplementary-material-inline video-inline"><script type="text/javascript" src="http://www.sciencemag.org/multimedia/swfobject.js"></script><div id="{@id}">
                                    <p>This item requires the Flash plug-in (version 8 or higher). JavaScript must be enabled in your browser.</p>
                                    <p><a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Download</a> the latest version of the free Flash plug-in.
                                    </p>
                                 </div><script type="text/javascript">var so = new SWFObject("http://www.sciencemag.org/multimedia/components/video/simplePlayer.swf", "<xsl:value-of select="@id" />", "320", "307", "8", "#ffffff");
                    so.addVariable("playlist_file", "<xsl:value-of select="p/supplementary-material[@content-type='playlist-file']/@xlink:href" />");
                        so.write("<xsl:value-of select="@id" />");</script></div>

                              <div class="supplementary-material-caption"></div>
                           </div>
    </xsl:template>



    <xsl:template match="article/back">
   <xsl:if test="/article/front/article-meta/history">
      <xsl:apply-templates select="/article/front/article-meta/history" />
   </xsl:if>
   <xsl:if test="app-group">
      <xsl:apply-templates select="app-group/app" />
   </xsl:if>
   <xsl:if test="ref-list">
<div class="section ref-list">
<h2><xsl:value-of select="ref-list/title" /></h2>
<ol class="cit-list">
   <xsl:apply-templates select="ref-list/ref" />
   <xsl:apply-templates select="ack" />
</ol>
</div>
   </xsl:if>
   <xsl:if test="fn-group">
      <xsl:apply-templates select="fn-group" />
   </xsl:if>
    </xsl:template>

   <xsl:template match="/article/front/article-meta/history">
<ul class="history-list">
<li xmlns:hwp="http://schema.highwire.org/Journal" class="received" hwp:start="{$articleRecYear}-{$articleRecMonth}-{$articleRecDay}"><span class="received-label">Received for publication </span><xsl:value-of select="$articleRecDay" /><xsl:text> </xsl:text><xsl:value-of select="$articleRecMonthSpelled" /><xsl:text> </xsl:text><xsl:value-of select="$articleRecYear" />.</li>
<li xmlns:hwp="http://schema.highwire.org/Journal" class="accepted" hwp:start="{$articleAccYear}-{$articleAccMonth}-{$articleAccDay}"><span class="accepted-label">Accepted for publication </span><xsl:value-of select="$articleAccDay" /><xsl:text> </xsl:text><xsl:value-of select="$articleAccMonthSpelled" /><xsl:text> </xsl:text><xsl:value-of select="$articleAccYear" />.</li>
</ul>
   </xsl:template>

   <xsl:template match="article/back/app-group/app">
<div class="section app" id="">
<h2><xsl:value-of select="title" /></h2>
<xsl:apply-templates />
</div>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref">
<li><a class="rev-xref-ref" href="#xref-{@id}-1" title="View reference {label} in text" id="{@id}">↵</a><xsl:text> </xsl:text>
   <xsl:apply-templates select="citation" />
  <xsl:apply-templates select="note/p" />
</li>
   </xsl:template>

   <xsl:template match="article/back/ack">
<li><xsl:apply-templates select="p" /></li>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']">
<div class="cit ref-cit ref-journal" id="cit-{$articleVol}.{$articleIssue}.{$articleFpage}.{../label}">
<div class="cit-metadata">
   <xsl:apply-templates select="person-group[@person-group-type='author']" />
<cite><xsl:text>, </xsl:text>
   <xsl:apply-templates select="article-title" />
   <xsl:apply-templates select="source" />
   <xsl:apply-templates select="volume" />
  <xsl:apply-templates select="fpage" />
  <xsl:apply-templates select="year" />
  <xsl:apply-templates select="pub-id" />
</cite>
</div>
</div>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='web']">
<div class="cit ref-cit ref-web" id="cit-{$articleVol}.{$articleIssue}.{$articleFpage}.{../label}">
<div class="cit-metadata unstructured">
   <xsl:apply-templates />
</div>
</div>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='book']">
<div class="cit ref-cit ref-book" id="cit-{$articleVol}.{$articleIssue}.{$articleFpage}.{../label}">
<div class="cit-metadata unstructured">
   <xsl:apply-templates />
</div>
</div>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='other']">
<div class="cit ref-cit ref-other" id="cit-{$articleVol}.{$articleIssue}.{$articleFpage}.{../label}">
<div class="cit-metadata unstructured">
   <xsl:apply-templates />
</div>
</div>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']">
<ol class="cit-auth-list">
   <xsl:apply-templates select="name" />
   <xsl:apply-templates select="etal" />
</ol>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']/name">
<li><span class="cit-auth"><span  class="cit-name-given-names">
   <xsl:apply-templates select="given-names" />
</span>
   <xsl:text> </xsl:text>
<span class="cit-name-surname">
   <xsl:apply-templates select="surname" />
</span>
</span>
   <xsl:if test="position() != last()">
        <xsl:text>, </xsl:text>
    </xsl:if>
</li>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']/etal">
<xsl:text>&#xa0;</xsl:text><em>et al.</em>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/article-title">
<span class="cit-article-title"><xsl:apply-templates /></span><xsl:text>. </xsl:text>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/source">
<abbr class="cit-jnl-abbrev"><xsl:apply-templates /></abbr><xsl:text> </xsl:text>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/volume">
<span class="cit-vol"><xsl:apply-templates /></span><xsl:text>, </xsl:text>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/fpage">
<span class="cit-fpage"><xsl:apply-templates /></span><xsl:text> </xsl:text>
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/year">
(<span class="cit-pub-date"><xsl:apply-templates /></span>).
   </xsl:template>

   <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/pub-id">
<span class="cit-pub-id-sep cit-pub-id-{@pub-id-type}-sep"><xsl:text> </xsl:text></span><span class="cit-pub-id cit-pub-id-{@pub-id-type}"><span class="cit-pub-id-scheme"><xsl:value-of select="@pub-id-type" />:</span><xsl:apply-templates /></span>
   </xsl:template>


   <xsl:template match="article/back/ref-list/ref/note/p">
<p><xsl:apply-templates /></p>
   </xsl:template>

   <xsl:template match="article/back/fn-group">
<div class="section fn-group" id="">
<ul>
   <xsl:apply-templates select="fn" />
</ul>
</div>
   </xsl:template>

   <xsl:template match="article/back/fn-group/fn">
<li class="fn-{@fn-type}" id="{@id}"><p><a class="rev-xref" href="#xref-{@id}">↵</a><span class="fn-label"><xsl:apply-templates select="label" /></span><xsl:apply-templates select="p" /></p></li>
   </xsl:template>

   <xsl:template match="article/back/fn-group/fn/p">
      <xsl:apply-templates />
   </xsl:template>


</xsl:stylesheet>
