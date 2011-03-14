<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:date="http://exslt.org/dates-and-times"
 xmlns:xnl="urn:oasis:names:tc:ciq:xsdschema:xNL:2.0"
 xmlns:cil="urn:oasis:names:tc:ciq:xsdschema:xCIL:2.0"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:vol="http://pcvs.org/2004/05/volunteerML">
  <xsl:import href="roster/convert_date.xslt"/>
  <xsl:import href="roster/print_name.xslt"/>

  <xsl:output method="xml"
              standalone="no"
              indent="yes"
              doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
              doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

  <xsl:variable name="extra_links" select="document('nonvolunteer_links.xhtml')/html:html/html:body/*"/>

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>Volunteer Links from <xsl:value-of select="vol:volunteers/@country"/></title>
        <link rel="stylesheet" type="text/css" href="styles/table.css" />
        <link rel="stylesheet" type="text/css" href="styles/page.css" />
      </head>
      <body>
        <h1>Links from Peace Corps Volunteers in <xsl:value-of select="vol:volunteers/@country"/></h1>
        <hr />
        <ul>
          <li>Volunteer Links
          <ul>
            <xsl:apply-templates/>
          </ul>
          </li>
        </ul>
        <xsl:copy-of select="$extra_links"/>
      </body>
    </html>
  </xsl:template>
  
  <xsl:template match="vol:volunteers">
    <xsl:choose>
      <xsl:when test="count(vol:volunteers) = 0 and count(.//vol:url) &gt; 0">
        <li>
          <xsl:variable name="padded_date">
            <xsl:call-template name="padded_date">
              <xsl:with-param name="date" select="@swearing"/>
            </xsl:call-template>
          </xsl:variable>
          <xsl:value-of select="date:year($padded_date)"/>
          <xsl:text> Volunteers</xsl:text>
        <ul>
          <xsl:apply-templates>
            <xsl:sort select="xnl:PersonName/xnl:LastName"/>
            <xsl:sort select="xnl:PersonName/xnl:FirstName"/>
          </xsl:apply-templates>
        </ul>
        </li>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates>
          <xsl:sort select="@swearing" order="descending"/>
        </xsl:apply-templates>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
          
  <xsl:template match="vol:volunteer">
    <xsl:if test="count(vol:url[@visibility='public'] | vol:url[not(@visibility)]) &gt; 0">
      <li>
        <xsl:apply-templates select="xnl:PersonName" mode="familiar"/>
        <ul>
          <xsl:apply-templates select="vol:url"/>
        </ul>
      </li>
    </xsl:if>
  </xsl:template>

  <xsl:template match="vol:url">
    <li>
      <xsl:value-of select="concat(translate(substring(@type, 1, 1),
                            'abcdefghijklmnopqrstuvwxyz',
                            'ABCDEFGHIJKLMNOPQRSTUVWXYZ'), substring(@type, 2))"/>
      <xsl:text>: </xsl:text>
      <a href="{@href}"><xsl:value-of select="@href"/></a>
    </li>
  </xsl:template>
</xsl:stylesheet>
