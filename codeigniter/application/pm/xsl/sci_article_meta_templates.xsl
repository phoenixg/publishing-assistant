<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" exclude-result-prefixes="xs" version="2.0">
    <xd:doc xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet">
        <xd:desc>
            <xd:p><xd:b>Created on:</xd:b> Nov 21, 2010</xd:p>
            <xd:p><xd:b>Author:</xd:b> Stewart Wills</xd:p>
            <xd:p></xd:p>
        </xd:desc>
    </xd:doc>

    <xsl:template match="article/front/article-meta/contrib-group">
<ol class="contributor-list">
    <xsl:apply-templates select="contrib[@contrib-type='author']" />
</ol>
<ol class="affiliation-list">
    <xsl:apply-templates select="aff" />
</ol>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/contrib[@contrib-type='author']">
<li class="contributor">
        <xsl:apply-templates select="collab" />
        <xsl:apply-templates select="name/given-names" />
        <xsl:apply-templates select="name/surname" />
        <xsl:apply-templates select="xref" />
        <xsl:if test="position() != last()">
            <xsl:text>, </xsl:text>
        </xsl:if>
</li>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/aff">
<li class="aff"><a id="{@id}" name="{@id}"></a><address>
    <xsl:apply-templates />
</address>
</li>
    </xsl:template>

    <xsl:template match="article/front/article-meta/author-notes">
<ol class="corresp-list">
    <xsl:apply-templates select="corresp" />
</ol>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/contrib[@contrib-type='author']/name/given-names">
        <xsl:apply-templates /><xsl:text> </xsl:text>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/contrib[@contrib-type='author']/name/surname">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/contrib[@contrib-type='author']/xref">
<a id="xref-{@rid}" class="xref-{@ref-type}" href="#{@rid}">
        <xsl:apply-templates />
</a>
        <xsl:if test="position() != last()">
            <sup><xsl:text>,</xsl:text></sup>
        </xsl:if>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/aff/label">
 <sup><xsl:apply-templates /></sup>
    </xsl:template>

    <xsl:template match="article/front/article-meta/author-notes/corresp">
<li class="corresp" id="{@id}">
    <xsl:apply-templates />
</li>
    </xsl:template>

    <xsl:template match="article/front/article-meta/contrib-group/author-notes/corresp/label">
        <span class="corresp-label"><xsl:apply-templates /></span>
    </xsl:template>


    <xsl:template match="article/front/article-meta/abstract">
<xsl:if test="not(@*)">
<div class="section abstract">
<h2>Abstract</h2>
<p>
<xsl:apply-templates />
</p>
</div>
</xsl:if>
    </xsl:template>






</xsl:stylesheet>
