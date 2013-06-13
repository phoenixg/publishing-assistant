<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" />
  
<xsl:template match="/">
<p>
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
