<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
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
      <a class="related" href="http://www.sciencemag.org/content/{@vol}/{@issue}/{@page}">
				<xsl:apply-templates/>
			</a>
		</xsl:template>

</xsl:stylesheet>
