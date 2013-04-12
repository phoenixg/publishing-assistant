<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" exclude-result-prefixes="xs" version="2.0">
    <xd:doc xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet">
        <xd:desc>
            <xd:p><xd:b>Created on:</xd:b> Nov 21, 2010</xd:p>
            <xd:p><xd:b>Author:</xd:b> editor</xd:p>
            <xd:p></xd:p>
        </xd:desc>
    </xd:doc>

    <xsl:template match="p">
<p class="{@content-type}"><xsl:apply-templates /></p>
    </xsl:template>

    <xsl:template match="italic">
<em><xsl:apply-templates /></em>
    </xsl:template>

    <xsl:template match="bold">
<strong><xsl:apply-templates /></strong>
    </xsl:template>

    <xsl:template match="sup">
<sup><xsl:apply-templates /></sup>
    </xsl:template>

    <xsl:template match="sub">
<sub><xsl:apply-templates /></sub>
    </xsl:template>

<!--    <xsl:template match="boxed-text">
 <div class="{@content-type}" id="boxed-text-{position()}">
<xsl:apply-templates />
</div>
    </xsl:template> -->


<xsl:template match="boxed-text">
   <xsl:element name="div">
      <xsl:attribute name="class">
         <xsl:value-of select="@content-type"/>
      </xsl:attribute>
    <xsl:attribute name="id">
    <xsl:text>boxed-text-</xsl:text><xsl:number level="any" />
</xsl:attribute>
<xsl:apply-templates />
   </xsl:element>
</xsl:template>



    <xsl:template match="boxed-text/title">
<h3><xsl:apply-templates /></h3>
    </xsl:template>

    <xsl:template match="def-list">
<dl><xsl:apply-templates /></dl>
    </xsl:template>

    <xsl:template match="def-item/term">
<dt><xsl:apply-templates /></dt>
    </xsl:template>

    <xsl:template match="def/p">
<dd><xsl:apply-templates /></dd>
    </xsl:template>

    <xsl:template match="list[@list-type='bullet']">
<ul class="list-unord">
<xsl:apply-templates />
</ul>
    </xsl:template>

    <xsl:template match="list[@list-type='simple']">
<ul class="list-{@list-type} {@list-content}" id="@id">
    <xsl:apply-templates />
</ul>
    </xsl:template>

    <xsl:template match="list/list-item">
<li><xsl:apply-templates /></li>
    </xsl:template>

    <xsl:template match="sec">
<div class="section {@sec-type}"><xsl:apply-templates /></div>
    </xsl:template>

    <xsl:template match="sec/title">
<h2><xsl:apply-templates /></h2>
    </xsl:template>

    <xsl:template match="sec/sec" priority="1">
<div class="subsection {@sec-type}"><xsl:apply-templates /></div>
    </xsl:template>

    <xsl:template match="sec/sec/title" priority="1">
<h3><xsl:apply-templates /></h3>
    </xsl:template>

    <xsl:template match="sec/title/sec/title">
<h3><xsl:apply-templates /></h3>
    </xsl:template>

    <xsl:template match="xref">
  <a id="xref-{@rid}" class="xref-{@ref-type}" href="#{@rid}"><xsl:apply-templates /></a>
    </xsl:template>

    <xsl:template match="speech">
<blockquote id="" class="speech">
        <xsl:apply-templates select="./p" />
</blockquote>
    </xsl:template>

    <xsl:template match="speech/p">
        <xsl:choose>
            <xsl:when test="position() eq 1"><p><xsl:apply-templates select="../speaker" /><xsl:apply-templates /></p></xsl:when>
            <xsl:otherwise><p><xsl:apply-templates /></p></xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="speech/speaker">
        <cite><xsl:apply-templates /></cite>
    </xsl:template>

    <xsl:template match="font">
        <font color="{@color}"><xsl:apply-templates /></font>
    </xsl:template>

</xsl:stylesheet>
