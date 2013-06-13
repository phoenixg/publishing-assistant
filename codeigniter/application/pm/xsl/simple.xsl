<xsl:stylesheet version="1.0" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
>
<xsl:namespace-alias stylesheet-prefix="php" result-prefix="xsl" />
<xsl:variable name="articleTitle" select="article/front/article-meta/title-group/article-title" as="xs:string" />
<xsl:variable name="articleVol" select="article/front/article-meta/volume" as="xs:string" />
<xsl:variable name="articleIssue" select="article/front/article-meta/issue" as="xs:string" />
<xsl:variable name="articleFpage" select="article/front/article-meta/fpage" as="xs:string" />
<xsl:variable name="href" select="''" as="xs:string" />
<xsl:template match="/">

	<section>
		<xsl:apply-templates select="/article/body/*[not(self::sec)]"/>
	</section>
        <xsl:apply-templates select="/article/body/sec"/>

</xsl:template>
<xsl:template match="body/p">
		<p class="{@content-type}">
		    <xsl:apply-templates/>
		</p>
	</xsl:template>

 <xsl:template match="italic">
        <em>
            <xsl:apply-templates/>
        </em>
    </xsl:template>

    <xsl:template match="bold">
        <strong>
            <xsl:apply-templates/>
        </strong>
    </xsl:template>

    <xsl:template match="sup">
        <sup>
            <xsl:apply-templates/>
        </sup>
    </xsl:template>

    <xsl:template match="sub">
        <sub>
            <xsl:apply-templates/>
        </sub>
    </xsl:template>

    <xsl:template match="boxed-text">
        <div class="{@content-type}" id="boxed-text-{position()}">
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="boxed-text/title">
        <h3>
            <xsl:apply-templates/>
        </h3>
    </xsl:template>

    <xsl:template match="def-list">
        <dl>
            <xsl:apply-templates/>
        </dl>
    </xsl:template>

    <xsl:template match="def-item/term">
        <dt>
            <xsl:apply-templates/>
        </dt>
    </xsl:template>

    <xsl:template match="def/p">
        <dd>
            <xsl:apply-templates/>
        </dd>
    </xsl:template>

    <xsl:template match="list[@list-type='bullet']">
        <ul class="list-unord">
            <xsl:apply-templates/>
        </ul>
    </xsl:template>

    <xsl:template match="list[@list-type='simple']">
        <ul class="list-{@list-type} {@list-content}" id="@id">
            <xsl:apply-templates/>
        </ul>
    </xsl:template>

    <xsl:template match="list/list-item">
        <li>
            <xsl:apply-templates/>
        </li>
    </xsl:template>

    <xsl:template match="body/sec">
        <section>
            <xsl:apply-templates/>
        </section>
    </xsl:template>
    <xsl:template match="body/sec/p">
        <p>
            <xsl:apply-templates/>
        </p>
    </xsl:template>

    <xsl:template match="sec/title">
        <h2>
            <xsl:apply-templates/>
        </h2>
    </xsl:template>
    <xsl:template match="sec/sec" priority="1">
        <div class="subsection {@sec-type}">
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="sec/sec/title" priority="1">
        <h3>
            <xsl:apply-templates/>
        </h3>
    </xsl:template>

    <xsl:template match="sec/title/sec/title">
        <h3>
            <xsl:apply-templates/>
        </h3>
    </xsl:template>

    <xsl:template match="xref">
        <a id="xref-{@rid}" class="xref-{@ref-type}" href="#{@rid}">
            <xsl:apply-templates/>
        </a>
    </xsl:template>
    <xsl:template match="ext-link">
	<a href="{@*[namespace-uri()='http://www.w3.org/1999/xlink' and local-name()='href']}">
            <xsl:apply-templates/>
        </a>
    </xsl:template>
    
    <xsl:template match="fig">
	
    </xsl:template>
    <xsl:template match="boxed-text">
	
    </xsl:template>

    <xsl:template match="related-article">
      <xsl:choose>
        <xsl:when test="$publication = 'science'">
          <a class="related" href="http://www.sciencemag.org/content/{@vol}/{@issue}/{@page}">
            <xsl:apply-templates/>
          </a>
        </xsl:when>
        <xsl:when test="$publication = 'scienceexpress'">
          <a class="related" href="http://www.sciencemag.org/lookup/doi/{@doi}">
            <xsl:apply-templates/>
          </a>
        </xsl:when>
        <xsl:when test="$publication = 'stm'">
          <a class="related" href="http://stm.sciencemag.org/content/{@vol}/{@issue}/{@page}">
            <xsl:apply-templates/>
          </a>
        </xsl:when>
        <xsl:when test="$publication = 'sigtrans'">
          <a class="related" href="http://stke.sciencemag.org/cgi/content/short/sigtrans;{@vol}/{@issue}/{@page}">
            <xsl:apply-templates/>
          </a>
        </xsl:when>
        <xsl:otherwise>
          <a class="related" href="http://www.sciencemag.org/">
            <xsl:apply-templates/>
          </a>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:template>

  </xsl:stylesheet>
