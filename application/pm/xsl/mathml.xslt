<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:xhtml="http://www.w3.org/1999/xhtml" version="1.0">
<xsl:output method="xml" encoding="utf-8"/>
<xsl:strip-space elements="mml:math"/>
<xsl:template match="xhtml:head">
<xhtml:head>
<xhtml:style type="text/css">
math
	{line-height:1.5em;
	white-space:nowrap;
	text-indent:0;
	display:inline-table;}
math[mode="display"], math[display="block"]
	{display:block;
	text-align:center;
	page-break-inside:avoid}
mfrac
	{display:inline-table;
	border-collapse:collapse;
	white-space:nowrap;
	text-align:center;
	line-height:1em;
	margin:0 2px;
	vertical-align:0.6em;}
mfrac > *
	{line-height:1.5em;}
mfrac > *:first-child
	{display:inline-table;
	vertical-align:text-bottom;}
mfrac[linethickness="0"] >  * + * 
	{border-top:none;}
mfrac[linethickness="2"] >  * + * ,mfrac[linethickness="medium"] >  * + * 
	{border-top:solid medium;}
mfrac[linethickness="3"] >  * + * ,mfrac[linethickness="thick"] >  * + * 
	{border-top:solid thick;}
mfrac > * + *
	{display:table-row;
	border-top:solid thin;}
math[mode="display"] mfrac, math[display="block"] mfrac
	{font-size:1em;}
mfrac mtable, mfrac matrix, mfrac vector, mfrac piecewise
	{margin-bottom:2px;
	margin-top:2px;}
msub mfrac > *, msup mfrac > *, msubsup mfrac > *, mmultiscripts mfrac > *, munder mfrac > *, mover mfrac > *
	{font-size:0.8em;}
msub > * + *, msup > * + *, mrow[SubScript], mrow[SuperScript]
	{font-size:0.7em;
	line-height:1em;
	vertical-align:-0.8ex;}
msup > * + *, mrow[SuperScript]
	{vertical-align:1.4ex;}
mrow[IndiceS]
	{display:inline-table;
	white-space:nowrap;
	font-size:0.7em;
	border-collapse:collapse;
	line-height:1.1em;
	vertical-align:0.7em;}
mrow[IndiceS] > mrow
	{display:inline-table;
	vertical-align:text-bottom;
	text-align:left;}
mrow[IndiceS="PreScripts"] > mrow
	{text-align:right;}
mrow[IndiceS] > mrow + mrow
	{display:table-row;}
mover, munder
	{display:inline-table;
	text-align:center;}
mover
	{display:inline-block;}
mover > *:first-child, munder > *
	{display:block;}
munder > *
	{white-space:nowrap;
	display:table-row;}
mo[OverBrace], mo[UnderBrace], mo[UnderLine]
	{display:block;
	content:"";
	height:3px;
	border-style:solid;
	border-width:1px 1px 0 1px;}
mo[UnderBrace]
	{border-style:solid;
	border-width:0 1px 1px 1px;}
mo[UnderLine]
	{height:0;
	border-width:1px 0 0 0;}
mo[AccentType] + *
	{line-height:1.1em;}
mo[AccentType="\02c9"],mo[AccentType="\02dc"],mo[AccentType="\02c6"],mo[AccentType="\02c7"],mo[AccentType="\02d9"],mo[AccentType="\00a8"]
	{display:block;
	min-width:0.3ex;
	margin:0 0.2ex -0.9ex 0.2ex;}
mo[AccentType="\02c9"]:before, mo[AccentType="\02dc"]:before, mo[AccentType="\02c6"]:before, mo[AccentType="\02c7"]:before, mo[AccentType="\02d9"]:before, mo[AccentType="\00a8"]:before
	{display:block;
	content:"";}
mo[AccentType="\02c9"]:before
	{border-bottom:solid 1px;}
mo[AccentType="\02c6"]:before
	{border-style:solid;
	border-width:1px 1px 0 1px;
	height:1px;}
mo[AccentType="\02c7"]:before
	{border-style:solid;
	border-width:0 1px 1px 1px;
	height:1px;}
mo[AccentType="\02d9"]:before
	{border-top:solid 1px;
	margin:0 auto;
	width:1px;}
mo[AccentType="\00a8"]:before
	{border-left:solid 1px;
	border-right:solid 1px;
	margin:0 auto;
	width:2px;
	height:1px;}
mo[AccentType="\02dc"]:before
	{border-right:solid 1px;
	border-bottom:solid 1px;
	height:1px;}
mo[AccentType="\02dc"]:after
	{display:block;
	content:"";
	border-left:solid 1px;
	height:1px;}
munder > * + *, mover > *:first-child
	{font-size:0.7em;
	line-height:1.2em;}
mroot
	{display:inline-table;
	border-collapse:collapse;
	margin:1px;}
mroot > *
	{display:table-cell;}
mroot > * + *
	{border-top:solid 1px;
	border-left:groove 2px;
	padding:2px 5px 0 3px;}
mroot > *:first-child
	{vertical-align:bottom;
	text-align:right;
	font-size:0.7em;
	line-height:1em;}
mroot > *:first-child:after
	{display:block;
	content:"";
	width:1em;
	border-style:groove;
	border-width:2px 2px 0 0;
	margin:0.2ex -0.2ex 1ex auto;}
msub, msup, msubsup, mmultiscripts, matrix, mfrac, munder, mover, mroot, vector, piecewise, mrow, mfenced
	{white-space:nowrap}
matrix, vector, piecewise, mtable
	{display:inline-table;
	border-collapse:collapse;
	vertical-align:middle;
	text-align:center;
	margin:1px;}
matrixrow, piece, otherwise, vector > *, mtr, mlabeledtr 
	{display:table-row}
matrixrow > *, piece > *, otherwise:after, mtd
	{display:table-cell;
	line-height:1.7em;
	white-space:nowrap;
	padding:1px 8px}
otherwise:after
	{content:"Otherwise"}
matrixrow:before, matrixrow:after,vector > *:before, vector > *:after,mtable[MatriX] > mtr:before, mtable[MatriX] > mtr:after
	{display:table-cell;
	content:"\00A0";
	border-bottom:solid 1px;
	border-left:solid 1px;
	border-top:hidden}
matrixrow:after, vector > *:after, mtable[MatriX] > mtr:after
	{border-left:none;
	border-right:solid 1px}
matrixrow:first-child:before, matrixrow:first-child:after, vector > *:first-child:before, vector > *:first-child:after,mtable[MatriX] > mtr:first-child:before, mtable[MatriX] > mtr:first-child:after
	{border-top:solid 1px}
determinant + matrix > matrixrow:before,determinant + matrix > matrixrow:after
	{content:normal}
determinant + matrix
	{border-right:solid 1px;
	border-left:solid 1px}
mtable[DeterminanT]
	{border-left:solid 1px;}
mtable[DeterminanT]
	{border-right:solid 1px;}
piecewise, mtable[PieceWise]
	{border:dashed 1px gray}
piece > *:first-child, mtable[PieceWise] > mtr > mtd:first-child
	{text-align:right}
piece > * + *,  mtable[PieceWise] > mtr > mtd + mtd
	{text-align:left}
msub[BaseType], msup[BaseType]
	{display:inline-table;
	vertical-align:middle;}
msub[BaseType] > *, msup[BaseType] > *
	{display:table-cell;
	vertical-align:middle;}
msub[BaseType] > * + *
	{vertical-align:bottom;}
msup[BaseType] > * + *
	{vertical-align:top;}
mphantom 
	{visibility:hidden}
merror
	{outline:solid thin red}
merror:before
	{content:"Error: "}
mfenced:before
	{content:"("}
mfenced:after
	{content:")"}
mfenced[open]:before
	{content:attr(open)}
mfenced[close]:after
	{content:attr(close)}
mfenced[FenceSize]:before,mfenced[FenceSize]:after
	{font-size:1.8em;
	vertical-align:-0.2ex;}
mfenced[TransferTo]:before,mfenced[TransferTo]:after
	{display:none;}
maction[actiontype="highlight"]:hover
	{background-color:yellow;
	color:black;}
maction[actiontype="toggle"] > * + *,maction[actiontype="toggle"]:hover > *:first-child
	{display:none}
maction[actiontype="toggle"]:hover > *:first-child + *
	{display:inline-block;}
maction[actiontype="statusline"] > * + *, maction[actiontype="tooltip"] > * + *
	{display:none}
maction[actiontype="statusline"]:hover > * + *, maction[actiontype="tooltip"]:hover > * + *
	{position:fixed;
	display:block;
	top:0;
	left:0;
	background-color:InfoBackground;
	color:InfoText;
	padding:0.5ex;
	border:solid 1px}
mspace[linebreak="goodbreak"]:before
	{content:"\200B";
	white-space:normal;}
 mspace[linebreak="newline"]:before, mspace[linebreak="indentingnewline"]:before 
	{content:"\000A";
	 white-space:pre;}
mglyph[alt]:before
	{content:attr(alt);}
mi[normal], mi[mathvariant="normal"],mn[normal], mn[mathvariant="normal"],mo[normal], mo[mathvariant="normal"],ms[normal], ms[mathvariant="normal"],mtext[normal], mtext[mathvariant="normal"]
	{font-style:normal;}
mi, mi[mathvariant="italic"], mi[mathvariant="bold-italic"],mi[mathvariant="sans-serif-italic"], mi[mathvariant="sans-serif-bold-italic"],mn[mathvariant="italic"], mn[mathvariant="bold-italic"],mn[mathvariant="sans-serif-italic"], mn[mathvariant="sans-serif-bold-italic"],mo[mathvariant="italic"], mo[mathvariant="bold-italic"],mo[mathvariant="sans-serif-italic"], mo[mathvariant="sans-serif-bold-italic"],ms[mathvariant="italic"], ms[mathvariant="bold-italic"],ms[mathvariant="sans-serif-italic"], ms[mathvariant="sans-serif-bold-italic"],mtext[mathvariant="italic"], mtext[mathvariant="bold-italic"],mtext[mathvariant="sans-serif-italic"], mtext[mathvariant="sans-serif-bold-italic"]
	{font-style:italic;}
mi[mathvariant="bold"], mi[mathvariant="bold-italic"], mi[mathvariant="bold-sans-serif"], mi[mathvariant="sans-serif-bold-italic"],mn[mathvariant="bold"], mn[mathvariant="bold-italic"], mn[mathvariant="bold-sans-serif"], mn[mathvariant="sans-serif-bold-italic"],mo[mathvariant="bold"], mo[mathvariant="bold-italic"], mo[mathvariant="bold-sans-serif"], mo[mathvariant="sans-serif-bold-italic"],ms[mathvariant="bold"], ms[mathvariant="bold-italic"], ms[mathvariant="bold-sans-serif"], ms[mathvariant="sans-serif-bold-italic"],mtext[mathvariant="bold"], mtext[mathvariant="bold-italic"], mtext[mathvariant="bold-sans-serif"], mtext[mathvariant="sans-serif-bold-italic"]
	{font-weight:bold;}
mi[mathvariant="monospace"], mn[mathvariant="monospace"],mo[mathvariant="monospace"], ms[mathvariant="monospace"],mtext[mathvariant="monospace"]
	{font-family:monospace;}
mi[mathvariant="bold-sans-serif"], mi[mathvariant="bold-sans-serif"], mi[mathvariant="sans-serif-italic"], mi[mathvariant="sans-serif-bold-italic"],mn[mathvariant="bold-sans-serif"], mn[mathvariant="bold-sans-serif"], mn[mathvariant="sans-serif-italic"], mn[mathvariant="sans-serif-bold-italic"],mo[mathvariant="bold-sans-serif"], mo[mathvariant="bold-sans-serif"], mo[mathvariant="sans-serif-italic"], mo[mathvariant="sans-serif-bold-italic"],ms[mathvariant="bold-sans-serif"], ms[mathvariant="bold-sans-serif"], ms[mathvariant="sans-serif-italic"], ms[mathvariant="sans-serif-bold-italic"],mtext[mathvariant="bold-sans-serif"], mtext[mathvariant="bold-sans-serif"], mtext[mathvariant="sans-serif-italic"], mtext[mathvariant="sans-serif-bold-italic"]
	{font-family:sans-serif;}
ms:before, ms:after 
	{content:"\0022"}
ms[lquote]:before 
	{content:attr(lquote)}
ms[rquote]:after 
	{content:attr(rquote)}
mo[OperatoR]
	{font-size:1.3em;
	vertical-align:-0.1ex;}
math
	{border-left:solid 1px transparent;}
</xhtml:style>
<xsl:apply-templates/>
</xhtml:head>
</xsl:template>

<xsl:template match="mml:*[self::mml:msub or self::mml:msup][child::*[position()=1][self::mml:matrix or self::mml:mtable or self::mml:*[self::mml:mrow or self::mml:mstyle or self::mml:menclose or self::mml:mpadded or self::mml:mphantom or self::mml:mfenced][child::*[position()=1 and position()=last()][self::mml:matrix or self::mml:mtable]]]]">
<xsl:element name="mml:{name()}">
<xsl:attribute name="BaseType">MatriX</xsl:attribute>
<xsl:apply-templates/>
</xsl:element>
</xsl:template>

<xsl:template match="mml:msubsup">
<mml:msubsup>
<xsl:apply-templates select="*[position()=1]"/>
<mml:mrow IndiceS="PostScripts">
<mml:mrow><xsl:apply-templates select="*[position()=3]"/></mml:mrow>
<mml:mrow><xsl:apply-templates select="*[position()=2]"/></mml:mrow>
</mml:mrow>
</mml:msubsup>
</xsl:template>

<xsl:template match="mml:mmultiscripts">
<mml:mmultiscripts>
<xsl:if test="mml:mprescripts">
<xsl:choose>
<xsl:when test="not(*[position() mod 2 = 1 and preceding-sibling::mml:mprescripts and not(self::mml:none)])">
<mml:mrow SuperScript="PreScript"><xsl:apply-templates select="*[position() mod 2 = 0 and preceding-sibling::mml:mprescripts and not(self::mml:none)]"/></mml:mrow>
</xsl:when>
<xsl:when test="not(*[position() mod 2 = 0 and preceding-sibling::mml:mprescripts and not(self::mml:none)])">
<mml:mrow SubScript="PreScript"><xsl:apply-templates select="*[position() mod 2 = 1 and preceding-sibling::mml:mprescripts and not(self::mml:none)] "/></mml:mrow>
</xsl:when>
<xsl:otherwise>
<mml:mrow IndiceS="PreScripts">
<mml:mrow><xsl:apply-templates select="*[position() mod 2 = 0 and preceding-sibling::mml:mprescripts and not(self::mml:none)]"/></mml:mrow>
<mml:mrow><xsl:apply-templates select="*[position() mod 2 = 1 and preceding-sibling::mml:mprescripts and not(self::mml:none)] "/></mml:mrow>
</mml:mrow>
</xsl:otherwise>
</xsl:choose>
</xsl:if>
<xsl:apply-templates select="*[position()=1]"/>
<xsl:if test="not(*[position()=2 and self::mml:mprescripts])">
<xsl:choose>
<xsl:when test="not(*[position() mod 2 = 1 and position() > 1 and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)])">
<mml:mrow SubScript="NormaL"><xsl:apply-templates select="*[position() mod 2 = 0 and not(self::mml:mprescripts) and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)]"/></mml:mrow>
</xsl:when>
<xsl:when test="not(*[position() mod 2 = 0 and not(self::mml:mprescripts) and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)])">
<mml:mrow SuperScript="NormaL"><xsl:apply-templates select="*[position() mod 2 = 1 and position() > 1 and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)] "/></mml:mrow>
</xsl:when>
<xsl:otherwise>
<mml:mrow IndiceS="PostScripts">
<mml:mrow><xsl:apply-templates select="*[position() mod 2 = 1 and position() > 1 and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)] "/></mml:mrow>
<mml:mrow><xsl:apply-templates select="*[position() mod 2 = 0 and not(self::mml:mprescripts) and not(preceding-sibling::mml:mprescripts) and not(self::mml:none)]"/></mml:mrow>
</mml:mrow>
</xsl:otherwise>
</xsl:choose>
</xsl:if>
</mml:mmultiscripts>
</xsl:template>

<xsl:template match="mml:mover">
<mml:mrow SheLL="InlineTable">
<mml:mover>
<xsl:apply-templates select="*[position()=2]"/>
<xsl:apply-templates select="*[position()=1]"/>
</mml:mover>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:munderover">
<mml:mrow SheLL="InlineTable">
<mml:munder>
<mml:mrow>
<mml:mover>
<xsl:apply-templates select="*[position()=3]"/>
<xsl:apply-templates select="*[position()=1]"/>
</mml:mover>
</mml:mrow>
<xsl:apply-templates select="*[position()=2]"/>
</mml:munder>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:munder">
<mml:mrow SheLL="InlineTable">
<mml:munder>
<xsl:apply-templates/>
</mml:munder>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:mfrac">
<mml:mrow SheLL="InlineTable">
<mml:mfrac>
<xsl:if test="@linethickness">
<xsl:attribute name="linethickness"><xsl:value-of select="@linethickness"/></xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</mml:mfrac>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:mroot">
<mml:mrow SheLL="InlineTable">
<mml:mroot>
<xsl:apply-templates select="*[position()=2]"/>
<xsl:apply-templates select="*[position()=1]"/>
</mml:mroot>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:msqrt">
<mml:mrow SheLL="InlineTable">
<mml:mroot>
<mml:mrow SheLL="RadiX"/>
<mml:mrow SheLL="RadicanD">
<xsl:apply-templates/>
</mml:mrow>
</mml:mroot>
</mml:mrow>
</xsl:template>

<xsl:template match="mml:mfenced">
<mml:mfenced>
<xsl:if test="@open">
<xsl:attribute name="open"><xsl:value-of select="@open"/></xsl:attribute>
</xsl:if>
<xsl:if test="@close">
<xsl:attribute name="close"><xsl:value-of select="@close"/></xsl:attribute>
</xsl:if>
<xsl:if test="child::mml:mtable[position() = 1 and position() = last()]">
<xsl:attribute name="TransferTo">MatriX</xsl:attribute>
</xsl:if>
<xsl:if test="descendant::mml:mfrac or descendant::mml:mover or descendant::mml:munder or descendant::mml:munderover or descendant::mml:matrix or descendant::mml:mtable or descendant::mml:vector or descendant::mml:piecewise">
<xsl:attribute name="FenceSize">MediuM</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</mml:mfenced>
</xsl:template>

<xsl:template match="mml:mfenced/mml:*[following-sibling::mml:*[position() = 1]]">
<xsl:copy-of select="."/>
<xsl:choose>
<xsl:when test="parent::mml:mfenced[@separators] and string-length(parent::mml:mfenced/@separators) >= position()"><xsl:value-of  select="substring(parent::mml:mfenced/@separators,position(),1)"/></xsl:when>
<xsl:when test="parent::mml:mfenced[@separators] and string-length(parent::mml:mfenced/@separators) &lt; position()"><xsl:value-of  select="substring(parent::mml:mfenced/@separators,string-length(parent::mml:mfenced/@separators),1)"/></xsl:when>
<xsl:otherwise>
<xsl:text>,</xsl:text>
</xsl:otherwise>
</xsl:choose>
</xsl:template>

<xsl:template match="mml:mtable">
<mml:mtable>
<xsl:if test="(parent::mml:mfenced[@close=''][@open!=''] and not(preceding-sibling::mml:*) and not(following-sibling::mml:*)) or (preceding-sibling::mml:*[position()=1 and self::mml:mo and (translate(string(.),'[{( ','[[[') = '[')] and (not(following-sibling::mml:*[position()=1 and self::mml:mo]) or following-sibling::mml:*[position()=1 and self::mml:mo and (translate(string(.),'])} ',']]]') != ']')]))">
<xsl:attribute name="PieceWise">NormaL</xsl:attribute>
</xsl:if>
<xsl:if test="(parent::mml:mfenced[@close='|'][@open='|'] and not(preceding-sibling::mml:*) and not(following-sibling::mml:*))  or (preceding-sibling::mml:*[position()=1 and self::mml:mo and translate(string(.),' ','') = '|'] and following-sibling::mml:*[position()=1 and self::mml:mo  and translate(string(.),' ','') = '|'])">
<xsl:attribute name="DeterminanT">NormaL</xsl:attribute>
</xsl:if>
<xsl:if test="(parent::mml:mfenced[@close!=''][@open!='|'] and not(preceding-sibling::mml:*) and not(following-sibling::mml:*)) or (preceding-sibling::mml:*[position()=1 and self::mml:mo and translate(string(.),'[{( ','[[[') = '['] and following-sibling::mml:*[position()=1 and self::mml:mo  and translate(string(.),']}) ',']]]') = ']'])">
<xsl:attribute name="MatriX">NormaL</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</mml:mtable>
</xsl:template>


<xsl:template match="mml:mi">
<mml:mi>
<xsl:if test="@mathvariant">
<xsl:attribute name="mathvariant"><xsl:value-of select="@mathvariant"/></xsl:attribute>
</xsl:if>
<xsl:if test="string-length(.)>1">
<xsl:attribute name="mathvariant">normal</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</mml:mi>
</xsl:template>

<xsl:template match="mml:mo">
<xsl:choose>
<xsl:when test="translate(.,' ([{|','') = '' and following-sibling::*[position() = 1 and self::mml:mtable]"/>
<xsl:when test="translate(.,' )]}|','') = '' and preceding-sibling::*[position() = 1 and self::mml:mtable]"/>
<xsl:otherwise>
<mml:mo>
<xsl:if test="@mathvariant">
<xsl:attribute name="mathvariant"><xsl:value-of select="@mathvariant"/></xsl:attribute>
</xsl:if>
<xsl:choose>
<xsl:when test="translate(.,' &#x220f;&#x2210;&#x2211;&#x222b;&#x222c;&#x222d;&#x222e;&#x222f;&#x2230;&#x2231;&#x2232;&#x2233;&#x22c0;&#x22c1;&#x22c2;&#x22c3;&#x2a00;&#x2a01;&#x2a02;&#x2a03;&#x2a04;&#x2a05;&#x2a06;&#x2a0a;&#x2a0b;&#x2a0c;','') = ''">
<xsl:attribute name="OperatoR"><xsl:value-of select="translate(.,' ','')"/></xsl:attribute>
<xsl:apply-templates/>
</xsl:when>
<xsl:when test="(parent::mml:mover and preceding-sibling::mml:*[position() = 1]) or (parent::mml:munderover and preceding-sibling::mml:*[position() = 2]) or (parent::mml:*[self::mml:mrow or self::mml:mstyle or self::mml:menclose or self::mml:mpadded or self::mml:mphantom][(parent::mml:mover and preceding-sibling::mml:*[position() = 1]) or (parent::mml:munderover and preceding-sibling::mml:*[position() = 2])])">
<xsl:if test="translate(.,' ^~&#x00af;&#x02c6;&#x02c7;&#x02dc;&#x02d8;&#x02c9;&#x02d9;&#x00a8;&#x007e;','') = ''">
<xsl:attribute name="AccentType"><xsl:value-of select="translate(.,'&#x00af;&#x02d8;&#x23dc;&#x23;^~ ','&#x02c9;&#x02c7;&#x23b4;&#x23b4;&#x02c6;&#x02dc;')"/></xsl:attribute>
</xsl:if>
<xsl:if test="translate(.,' &#x23b4;&#x23dc;&#x23de;','') = ''">
<xsl:attribute name="OverBrace"><xsl:value-of select="translate(.,' ','')"/></xsl:attribute>
<xsl:apply-templates/>
</xsl:if>
</xsl:when>
<xsl:when test="(parent::mml:munder and preceding-sibling::mml:*[position() = 1]) or (parent::mml:munderover and preceding-sibling::mml:*[position() = 1] and following-sibling::mml:*[position() = 1]) or (parent::mml:*[self::mml:mrow or self::mml:mstyle or self::mml:menclose or self::mml:mpadded or self::mml:mphantom][(parent::mml:munder and preceding-sibling::mml:*[position() = 1]) or (parent::mml:munderover and preceding-sibling::mml:*[position() = 1] and following-sibling::mml:*[position() = 1])])">
<xsl:if test="translate(.,' &#x23b5;&#x23dd;&#x23df;','') = ''">
<xsl:attribute name="UnderBrace"><xsl:value-of select="translate(.,' ','')"/></xsl:attribute>
</xsl:if>
<xsl:if test="text() = '_' or text() = ' _' or text() = '_ ' or text() = ' _ '">
<xsl:attribute name="UnderLine">_</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</xsl:when>
<xsl:when test="translate(.,'+=>&lt;&#x00B1;&#x2212;&#x2213;&#x2214;&#x22D6;&#x22D7;&#x22D8;&#x22D9;&#x22DA;&#x22DB;&#x22DC;&#x22DD;&#x22DE;&#x22DF;&#x22E0;&#x22E1;&#x2238;&#x2239;&#x223A;&#x223B;&#x223C;&#x223D;&#x223E;&#x223F;&#x2241;&#x2242;&#x2243;&#x2244;&#x2245;&#x2246;&#x2247;&#x2248;&#x2249;&#x224A;&#x224B;&#x224C;&#x224D;&#x224E;&#x224F;&#x2250;&#x2251;&#x2252;&#x2253;&#x2254;&#x2255;&#x2256;&#x2257;&#x2258;&#x2259;&#x225A;&#x225B;&#x225C;&#x225D;&#x225E;&#x225F;&#x2260;&#x2261;&#x2262;&#x2263;&#x2264;&#x2265;&#x2266;&#x2267;&#x2268;&#x2269;&#x226A;&#x226B;&#x226C;&#x226D;&#x226E;&#x226F;&#x2270;&#x2271;&#x2272;&#x2273;&#x2274;&#x2275;&#x2276;&#x2277;&#x2278;&#x2279;&#x227A;&#x227B;&#x227C;&#x227D;&#x227E;&#x227F;&#x2280;&#x2281;&#x2282;&#x2283;&#x2284;&#x2285;&#x2286;&#x2287;&#x2288;&#x2289;&#x228A;&#x228B;&#x2208;&#x2209;&#x220A;&#x220B;&#x220C;&#x220D; ','') = ''">
<xsl:text> </xsl:text><xsl:value-of select="."/><xsl:text> </xsl:text>
</xsl:when>
<xsl:when test="text() = '-' or text() = ' -' or text() = '- ' or text() = ' - '">
<xsl:text> &#x2212; </xsl:text>
</xsl:when>
<xsl:otherwise>
<xsl:apply-templates/>
</xsl:otherwise>
</xsl:choose>
</mml:mo>
</xsl:otherwise>
</xsl:choose>
</xsl:template>

<xsl:template match="*|@*">
<xsl:copy>
<xsl:apply-templates select="node()|@*"/> 
</xsl:copy>
</xsl:template>
</xsl:stylesheet>