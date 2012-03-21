<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output
   method="xml"
   standalone="no"
   indent="yes"
   doctype-public="-//W3C//DTD XHTML 1.1//EN"
   doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

  <xsl:template match="bibliography">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>Bibliography</title>
        <style type="text/css">
          html {
            font-size: 13pt;
            padding-left: 3em;
            padding-right: 3em;
          }
          td {
            border: thin solid gray;
            border-bottom: medium solid gray;
            padding-left: 1em;
            padding-top: .2em;
            padding-bottom: .2em;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
          a {
            text-decoration: none;
            color: blue;
          }
          a:visited {
            color: purple;
          }
          a:hover {
            text-decoration: underline;
            color: orange;
          }
          .person {
            text-align: center;
          }
        </style>
      </head>
      <body>
        <xsl:apply-templates select="bibliodiv[@conformance='books']"/>
        <xsl:apply-templates select="bibliodiv[@conformance='films']"/>
        <xsl:apply-templates select="bibliodiv[@conformance='art']"/>
        <xsl:apply-templates select="bibliodiv[not(@conformance)]"/>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="bibliodiv">
    <h1><xsl:value-of select="@role"/></h1>
    <table>
      <xsl:apply-templates select="biblioentry">
        <xsl:sort select="author/surname|editor/surname|personname/surname"/>
        <xsl:sort select="author/othername|editor/othername|personname/othername"/>
      </xsl:apply-templates>
    </table>
  </xsl:template>

  <xsl:template match="biblioentry">
    <tr>
      <td><xsl:apply-templates select="author|editor|personname|publisher"/></td>
      <td>
        <xsl:choose>
          <xsl:when test="count(biblioset) > 0">
            <xsl:apply-templates select="biblioset"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:call-template name="item"/>
          </xsl:otherwise>
        </xsl:choose>
      </td>
    </tr>
  </xsl:template>

  <xsl:template match="author|editor|personname|publisher">
    <xsl:variable name="name">
      <xsl:if test="count(honorific) > 0">
        <xsl:value-of select="honorific"/>
        <xsl:text> </xsl:text>
      </xsl:if>
      <xsl:value-of select="firstname"/>
      <xsl:if test="count(othername) > 0">
        <xsl:text> </xsl:text>
        <xsl:value-of select="othername"/>
      </xsl:if>
      <xsl:if test="count(surname) > 0">
        <xsl:text> </xsl:text>
        <xsl:value-of select="surname"/>
      </xsl:if>
      <xsl:if test="count(lineage) > 0">
        <xsl:text> </xsl:text>
        <xsl:value-of select="lineage"/>
      </xsl:if>
    </xsl:variable>
    <div class="person">
      <xsl:choose>
        <xsl:when test="$name != ''">
          <xsl:value-of select="$name"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:apply-templates/>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </xsl:template>

  <xsl:template match="biblioset">
    <xsl:call-template name="item"/>
  </xsl:template>

  <xsl:template name="item">
    <xsl:variable name="title">
      <xsl:value-of select="title"/>
      <xsl:if test="count(subtitle) > 0">
        <xsl:text>: </xsl:text>
        <i><xsl:value-of select="subtitle"/></i>
      </xsl:if>
      <xsl:if test="count(date) > 0">
        <xsl:text> (</xsl:text>
        <xsl:value-of select="date"/>
        <xsl:text>)</xsl:text>
      </xsl:if>
    </xsl:variable>
    <xsl:variable name="url">
      <xsl:choose>
        <xsl:when test="count(indexterm[@role='url']) > 0">
          <xsl:value-of select="indexterm"/>
        </xsl:when>
        <xsl:when test="count(isbn) > 0 and isbn != ''">
          <xsl:text>http://www.amazon.com/exec/obidos/ASIN/</xsl:text>
          <xsl:value-of select="isbn"/>
          <xsl:text>/mentorreadingsug</xsl:text>
        </xsl:when>
      </xsl:choose>
    </xsl:variable>
    <div>
      <xsl:choose>
        <xsl:when test="$url != ''">
          <a href="{$url}">
            <xsl:copy-of select="$title"/>
          </a>
        </xsl:when>
        <xsl:otherwise>
          <xsl:copy-of select="$title"/>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </xsl:template> 
</xsl:stylesheet>
