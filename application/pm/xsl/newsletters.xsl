<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xlink="http://www.w3.org/1999/xlink" version="2.0">
	<xsl:include href="sci_article_variables.xsl"/>
    <xsl:template match="/">
        <html>
            <head>
                <meta charset="UTF-8"/>
                <meta name="format-detection" content="telephone=no"/>
                <link href="../css/article.css" rel="stylesheet" type="text/css"/>
                <title>
                    <xsl:value-of select="$articleTitle"/>
                </title>
            </head>
            <body>
                <article class="human-conflict">
                    <header>
                        <div class="overline">((OVERLINE))</div>
                        <h1>
                            <xsl:value-of select="$articleTitle"/>
                        </h1>
                        <div class="byline"><xsl:value-of select="$articleFirstAuthorGivenName"/>&#xa0;<xsl:value-of select="$articleFirstAuthorSurname"/></div>
                        <div class="abstract">
                            <xsl:value-of select="$abstract"/>
                        </div>
                    </header>
                    <section>
                        <xsl:apply-templates select="/article/body/*[not(self::sec)]"/>
                    </section>
                    <xsl:apply-templates select="/article/body/sec"/>
                    <footer>
                        <xsl:apply-templates select="/article/back/ref-list"/>
                        <xsl:apply-templates select="/article/back/ack"/>
                    </footer>
                </article>
            </body>
        </html>
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

    <xsl:template match="table-wrap">
        <div class="full pos-anchor" id="{@id}">
            <xsl:apply-templates select="table"/>
            <xsl:apply-templates select="caption"/>
        </div>
    </xsl:template>

    <xsl:template match="table-wrap/caption">
        <figcaption>
            <p> <xsl:value-of select="../label"/>.&#xa0; <xsl:apply-templates/> </p>
        </figcaption>
        <div class="sb-div caption-clear"/>
    </xsl:template>

    <xsl:template match="table">
        <div class="table-inline">
            <table>
                <xsl:apply-templates select="col"/>
                <xsl:apply-templates select="thead"/>
                <xsl:apply-templates select="tbody"/>
            </table>
        </div>
    </xsl:template>

    <xsl:template match="table/col">
        <col width="{@width}"/>
        <xsl:text>
</xsl:text>
    </xsl:template>

    <xsl:template match="table/thead">
        <thead>
            <xsl:apply-templates/>
        </thead>
    </xsl:template>

    <xsl:template match="table/tbody">
        <tbody>
            <xsl:apply-templates/>
        </tbody>
    </xsl:template>

    <xsl:template match="tr">
        <tr>
            <xsl:apply-templates/>
        </tr>
    </xsl:template>

    <xsl:template match="td">
        <td valign="{@valign}" align="{@align}" scope="{@scope}" rowspan="{@rowspan}" colspan="{@colspan}" class="table-{@align} table-v{@valign}">
            <xsl:apply-templates/>
        </td>
    </xsl:template>

    <!-- 
        // Reference Templates
    -->

    <xsl:template match="ref-list">
        <h2>
            <xsl:value-of select="title"/>
        </h2>
        <ul class="ref-list">
            <xsl:apply-templates select="ref"/>
        </ul>
    </xsl:template>

    <xsl:template match="ref-list/ref">
        <li id="{@id}"><xsl:apply-templates/> (<a href="#xref-{@id}" class="ref-back">&#x2191;</a>)</li>
    </xsl:template>

    <xsl:template match="ref-list/ref//label"> </xsl:template>

    <xsl:template match="article/back/ref-list/ref/citation[@citation-type='web']">

        <span class="cit-metadata unstructured">
            <xsl:apply-templates/>
        </span>

    </xsl:template>


    <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']">
        <xsl:apply-templates select="person-group[@person-group-type='author']"/>
        <cite>
            <xsl:text>, </xsl:text>
            <xsl:apply-templates select="article-title"/>
            <xsl:apply-templates select="source"/>
            <xsl:apply-templates select="volume"/>
            <xsl:apply-templates select="fpage"/>
            <xsl:apply-templates select="year"/>
            <xsl:apply-templates select="pub-id"/>
        </cite>

    </xsl:template>

    <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']">
        <xsl:apply-templates select="name"/>
        <xsl:apply-templates select="etal"/>
    </xsl:template>




    <xsl:template match="article/back/ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']/name">
        <span class="given-name">
            <span class="cit-name-given-names">
                <xsl:apply-templates select="given-names"/>
            </span>
            <xsl:text> </xsl:text>
            <span class="surname">
                <xsl:apply-templates select="surname"/>
            </span>
        </span>
        <xsl:if test="position() != last()">
            <xsl:text>, </xsl:text>
        </xsl:if>

    </xsl:template>

    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/person-group[@person-group-type='author']/etal">
        <xsl:text>&#xa0;</xsl:text>
        <em>et al.</em>
    </xsl:template>

    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/article-title">
        <span class="article-title">
            <xsl:apply-templates/>
        </span>
        <xsl:text>. </xsl:text>
    </xsl:template>

    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/source">
        <abbr class="source">
            <xsl:apply-templates/>
        </abbr>
        <xsl:text> </xsl:text>
    </xsl:template>

    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/volume">
        <span class="volume">
            <xsl:apply-templates/>
        </span>
        <xsl:text>, </xsl:text>
    </xsl:template>

    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/fpage">
        <span class="page">
            <xsl:apply-templates/>
        </span>
        <xsl:text> </xsl:text>
    </xsl:template>


    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/year"> (<span class="year"><xsl:apply-templates/></span>). </xsl:template>
    <xsl:template match="ref-list/ref/citation[@citation-type='journal']/pub-id">

        <span class="cit-pub-id cit-pub-id-{@pub-id-type}">
            <span class="cit-pub-id-scheme"><xsl:value-of select="@pub-id-type"/>:</span>
            <xsl:apply-templates/>
        </span>
    </xsl:template>

    <xsl:template match="/article/body/descendant::fig">
        <aside>
            <xsl:choose>
                <xsl:when test="position() mod 2=1">
                    <xsl:attribute name="class">even</xsl:attribute>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="class">odd</xsl:attribute>
                </xsl:otherwise>
            </xsl:choose>            
            <figure>
                <xsl:choose>
                    <xsl:when test="@position eq 'float'">
                        <img alt="{label}" src="../img/{graphic/@xlink:href}.png"/>
                    </xsl:when>
                    <xsl:when test="@position eq 'anchor'">
                        <img alt="{label}" src="../img/{graphic/@xlink:href}.png"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <img alt="{label}" src=""/>
                    </xsl:otherwise>
                </xsl:choose>
            </figure>
            <figcaption>
                <xsl:apply-templates select="label"/>
                <xsl:apply-templates select="caption"/>
                <xsl:apply-templates select="attrib"/>
                <s class="sb-div caption-clear"/>
            </figcaption>
        </aside>
    </xsl:template>

    <xsl:template match="fig/label"/>

    <xsl:template match="fig/caption">
        <p>
            <xsl:if test="../label">
                <xsl:value-of select="../label"/>
                <xsl:text>: </xsl:text>
            </xsl:if>
            <xsl:apply-templates/>
        </p>
    </xsl:template>
    <xsl:template match="caption/title"> <xsl:value-of select="."/>&#xa0;</xsl:template>
    <xsl:template match="fig/attrib">
        <span class="credit">
            <xsl:apply-templates/>
        </span>
    </xsl:template>

    <xsl:template match="inline-graphic">
        <img class="inline-graphic" src="../img/{graphic/@xlink:href}.png"/>
    </xsl:template>

    <xsl:template match="ack">
        <p>
            <strong><xsl:value-of select="title"/>:&#xa0;</strong>
            <xsl:apply-templates select="p"/>
        </p>
    </xsl:template>
</xsl:stylesheet>
