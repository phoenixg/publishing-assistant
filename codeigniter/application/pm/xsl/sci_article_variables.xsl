<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" exclude-result-prefixes="xs" version="2.0">
    <xd:doc xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet">
        <xd:desc>
            <xd:p><xd:b>Created on:</xd:b> Nov 21, 2010</xd:p>
            <xd:p><xd:b>Author:</xd:b> Stewart Wills</xd:p>
            <xd:p></xd:p>
        </xd:desc>
    </xd:doc>

<xsl:variable name="journalTitle" select="article/front/journal-meta/abbrev-journal-title[@abbrev-type='pubmed']" as="xs:string" />
<xsl:variable name="articleID" select="article/front/article-meta/article-id[@pub-id-type='publisher-id']" as="xs:string" />
<xsl:variable name="articleHeading" select="article/front/article-meta/article-categories/subj-group[@subj-group-type='article-type']/subject" as="xs:string" />
<xsl:variable name="articleTitle" select="article/front/article-meta/title-group/article-title" as="xs:string" />
<xsl:variable name="articleVol" select="article/front/article-meta/volume" as="xs:string" />
<xsl:variable name="articleIssue" select="article/front/article-meta/issue" as="xs:string" />
<xsl:variable name="articleFpage" select="article/front/article-meta/fpage" as="xs:string" />
<xsl:variable name="articleFpageSeq" as="xs:string">
<xsl:choose>
<xsl:when test="article/front/article-meta/fpage/@seq"><xsl:value-of select="article/front/article-meta/fpage/@seq" /></xsl:when>
<xsl:otherwise>none</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="articleLpage" select="article/front/article-meta/lpage" as="xs:string" />
<xsl:variable name="articleDOI" as="xs:string">
<xsl:choose>
<xsl:when test="article/front/article-meta/article-id[@pub-id-type='doi']"><xsl:value-of select="article/front/article-meta/article-id[@pub-id-type='doi']" /></xsl:when>
<xsl:otherwise>none</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="articleYear" select="article/front/article-meta/pub-date[@pub-type='ppub']/year" as="xs:string" />
<xsl:variable name="articleMonth" select="article/front/article-meta/pub-date[@pub-type='ppub']/month" as="xs:string" />
<xsl:variable name="articleDay" select="article/front/article-meta/pub-date[@pub-type='ppub']/day" as="xs:string" />
<xsl:variable name="articleRecYear" select="article/front/article-meta/history/date[@date-type='received']/year" as="xs:string" />
<xsl:variable name="articleRecMonth" select="article/front/article-meta/history/date[@date-type='received']/month" as="xs:string" />
<xsl:variable name="articleRecDay" select="article/front/article-meta/history/date[@date-type='received']/day" as="xs:string" />
<xsl:variable name="articleAccYear" select="article/front/article-meta/history/date[@date-type='accepted']/year" as="xs:string" />
<xsl:variable name="articleAccMonth" select="article/front/article-meta/history/date[@date-type='accepted']/month" as="xs:string" />
<xsl:variable name="articleAccDay" select="article/front/article-meta/history/date[@date-type='accepted']/day" as="xs:string" />
<xsl:variable name="articleLegArtType" select="article/front/article-meta/article-categories/subj-group[@subj-group-type='legacy-article-type']/subject" as="xs:string" />


<xsl:variable name="articleMonthSpelled" as="xs:string">
<xsl:choose>
<xsl:when test="$articleMonth eq '01'">January</xsl:when>
<xsl:when test="$articleMonth eq '02'">February</xsl:when>
<xsl:when test="$articleMonth eq '03'">March</xsl:when>
<xsl:when test="$articleMonth eq '04'">April</xsl:when>
<xsl:when test="$articleMonth eq '05'">May</xsl:when>
<xsl:when test="$articleMonth eq '06'">June</xsl:when>
<xsl:when test="$articleMonth eq '07'">July</xsl:when>
<xsl:when test="$articleMonth eq '08'">August</xsl:when>
<xsl:when test="$articleMonth eq '09'">September</xsl:when>
<xsl:when test="$articleMonth eq '10'">October</xsl:when>
<xsl:when test="$articleMonth eq '11'">November</xsl:when>
<xsl:when test="$articleMonth eq '12'">December</xsl:when>
<xsl:otherwise>No date</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="articleRecMonthSpelled" as="xs:string">
<xsl:choose>
<xsl:when test="$articleRecMonth eq '01'">January</xsl:when>
<xsl:when test="$articleRecMonth eq '02'">February</xsl:when>
<xsl:when test="$articleRecMonth eq '03'">March</xsl:when>
<xsl:when test="$articleRecMonth eq '04'">April</xsl:when>
<xsl:when test="$articleRecMonth eq '05'">May</xsl:when>
<xsl:when test="$articleRecMonth eq '06'">June</xsl:when>
<xsl:when test="$articleRecMonth eq '07'">July</xsl:when>
<xsl:when test="$articleRecMonth eq '08'">August</xsl:when>
<xsl:when test="$articleRecMonth eq '09'">September</xsl:when>
<xsl:when test="$articleRecMonth eq '10'">October</xsl:when>
<xsl:when test="$articleRecMonth eq '11'">November</xsl:when>
<xsl:when test="$articleRecMonth eq '12'">December</xsl:when>
<xsl:otherwise>No date</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="articleAccMonthSpelled" as="xs:string">
<xsl:choose>
<xsl:when test="$articleAccMonth eq '01'">January</xsl:when>
<xsl:when test="$articleAccMonth eq '02'">February</xsl:when>
<xsl:when test="$articleAccMonth eq '03'">March</xsl:when>
<xsl:when test="$articleAccMonth eq '04'">April</xsl:when>
<xsl:when test="$articleAccMonth eq '05'">May</xsl:when>
<xsl:when test="$articleAccMonth eq '06'">June</xsl:when>
<xsl:when test="$articleAccMonth eq '07'">July</xsl:when>
<xsl:when test="$articleAccMonth eq '08'">August</xsl:when>
<xsl:when test="$articleAccMonth eq '09'">September</xsl:when>
<xsl:when test="$articleAccMonth eq '10'">October</xsl:when>
<xsl:when test="$articleAccMonth eq '11'">November</xsl:when>
<xsl:when test="$articleAccMonth eq '12'">December</xsl:when>
<xsl:otherwise>No date</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="articleNumOfAuthors" as="xs:integer" select="count(article/front/article-meta/contrib-group/contrib[@contrib-type='author'])" />

<xsl:variable name="articleFirstAuthorSurname" as="xs:string">
<xsl:choose>
<xsl:when test="article/front/article-meta/contrib-group/contrib[@contrib-type='author'][1]/name/surname">
<xsl:if test="article/front/article-meta/contrib-group/contrib[@contrib-type='author'][1]/name/surname">
<xsl:value-of select="article/front/article-meta/contrib-group/contrib[@contrib-type='author'][1]/name/surname" />
</xsl:if>
<xsl:if test="article/front/article-meta/contrib-group/contrib[@contrib-type='author'][1]/collab">
<xsl:value-of select="article/front/article-meta/contrib-group/contrib[@contrib-type='author'][1]/collab" />
</xsl:if>
</xsl:when>
<xsl:otherwise>None</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="pubSection" as="xs:string">
<xsl:choose>
<xsl:when test="$articleLegArtType eq 'twis'">thisweekinscience</xsl:when>
<xsl:when test="$articleLegArtType eq 'twil'">editorschoice</xsl:when>
<xsl:when test="$articleLegArtType eq 'r-samples'">randomsamples</xsl:when>
<xsl:when test="$articleLegArtType eq 'findings'">findings</xsl:when>
<xsl:when test="$articleLegArtType eq 'special/intro'">introductiontospecialissue</xsl:when>
<xsl:when test="$articleLegArtType eq 'special/news'">news</xsl:when>
<xsl:when test="$articleLegArtType eq 'special/perspective'">perspective</xsl:when>
<xsl:when test="$articleLegArtType eq 'special/review'">review</xsl:when>
<xsl:when test="$articleLegArtType eq 'special/multimedia'">multimedia</xsl:when>
<xsl:when test="$articleLegArtType eq 'multimedia'">multimedia</xsl:when>
<xsl:when test="$articleLegArtType eq 'editorial'">editorial</xsl:when>
<xsl:when test="$articleLegArtType eq 'n-week'">newsoftheweek</xsl:when>
<xsl:when test="$articleLegArtType eq 'n-analysis'">newsanalysis</xsl:when>
<xsl:when test="$articleLegArtType eq 'n-focus'">newsfocus</xsl:when>
<xsl:when test="$articleLegArtType eq 'gonzo'">thegonzoscientist</xsl:when>
<xsl:when test="$articleLegArtType eq 'letters'">letters</xsl:when>
<xsl:when test="$articleLegArtType eq 'books'">booksetal</xsl:when>
<xsl:when test="$articleLegArtType eq 'essays'">essaysonscienceandsociety</xsl:when>
<xsl:when test="$articleLegArtType eq 'p-forum'">policyforum</xsl:when>
<xsl:when test="$articleLegArtType eq 'ed-forum'">educationforum</xsl:when>
<xsl:when test="$articleLegArtType eq 'perspective'">perspective</xsl:when>
<xsl:when test="$articleLegArtType eq 'a-affairs'">associationaffairs</xsl:when>
<xsl:when test="$articleLegArtType eq 'review'">review</xsl:when>
<xsl:when test="$articleLegArtType eq 'brevia'">brevia</xsl:when>
<xsl:when test="$articleLegArtType eq 'r-articles'">researcharticle</xsl:when>
<xsl:when test="$articleLegArtType eq 'reports'">report</xsl:when>
<xsl:when test="$articleLegArtType eq 't-comment'">technicalcomments</xsl:when>
<xsl:when test="$articleLegArtType eq 'products'">productsmaterials</xsl:when>
<xsl:when test="$articleLegArtType eq 'podcasts'">podcasts</xsl:when>
<xsl:otherwise>none</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="articleWorkflow" as="xs:string">
<xsl:choose>
<xsl:when test="$articleLegArtType eq 'twis' or $articleLegArtType eq 'twil' or $articleLegArtType eq 'r-samples' or $articleLegArtType eq 'findings' or $articleLegArtType eq 'special/intro' or $articleLegArtType eq 'special-feature' or $articleLegArtType eq 'special/news' or $articleLegArtType eq 'editorial' or $articleLegArtType eq 'multimedia' or $articleLegArtType eq 'n-week' or $articleLegArtType eq 'n-focus' or $articleLegArtType eq 'n-analysis' or $articleLegArtType eq 'gonzo' or $articleLegArtType eq 'letters' or $articleLegArtType eq 'books' or $articleLegArtType eq 'essays' or $articleLegArtType eq 'p-forum' or $articleLegArtType eq 'ed-forum' or $articleLegArtType eq 'perspective' or $articleLegArtType eq 'a-affairs' or $articleLegArtType eq 'products' or $articleLegArtType eq 'podcasts'">desktop</xsl:when>
<xsl:otherwise>spi</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="figTreatment" as="xs:string">
<xsl:choose>
<xsl:when test="$pubSection eq 'perspective' or
                              $pubSection eq 'essaysonscienceandsociety' or  
                              $pubSection eq 'policyforum' or
                              $pubSection eq 'educationforum' or
                              $pubSection eq 'review' or
                              $pubSection eq 'brevia' or
                              $pubSection eq 'researcharticle' or
                              $pubSection eq 'report' or
                              $pubSection eq 'technicalcomments'">type-figure</xsl:when>
<xsl:otherwise>nonresearch-content</xsl:otherwise>
</xsl:choose>
</xsl:variable>

</xsl:stylesheet>
