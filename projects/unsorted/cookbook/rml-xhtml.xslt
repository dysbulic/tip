<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:exslt="http://exslt.org/common"
 xmlns:set="http://exslt.org/sets"
 xmlns:string="http://exslt.org/strings"
 xmlns:math="http://exslt.org/math"
 xmlns:xi="http://www.w3.org/2001/XInclude"
 xmlns:dyn="http://exslt.org/dynamic"
 xmlns:rml="http://www.formatdata.com/recipeml/">
  <xsl:output method="xml"
              standalone="no"
              indent="yes"
              doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
              doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
  
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>Recipe for <xsl:value-of select="//rml:head/rml:title"/></title>
        <link rel="stylesheet" type="text/css" href="http://mr.pcvs.org/styles/page.css"/>
        <link rel="stylesheet" type="text/css" href="http://mr.pcvs.org/styles/table.css"/>
        <style type="text/css">
          .units, .item {
            display: inline;
          }
        </style>
      </head>
      <body>
        <xsl:apply-templates select="//rml:recipe"/>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="rml:recipe">
    <h1><xsl:value-of select="rml:head/rml:title"/></h1>
    <xsl:apply-templates select="rml:ingredients"/>
    <ol><xsl:apply-templates select="rml:directions"/></ol>
  </xsl:template>

  <!-- To get side by side lists of ingredients I need two
     - floated divs with half the ingredients in each
    -->
  <xsl:template match="rml:ingredients">
    <xsl:variable name="midpoint" select="ceiling(count(rml:ing) div 2)"/>
    <div>
      <div style="float: left; width: 45%;">
        <ul>
          <xsl:apply-templates select="rml:ing[position() &lt; $midpoint] | rml:ing[position() = $midpoint]"/>
        </ul>
      </div>
      <div style="float: right; width: 45%;">
        <ul>
          <xsl:apply-templates select="rml:ing[position() &gt; $midpoint]"/>
        </ul>
      </div>
      <div style="clear: both;"></div>
    </div>
  </xsl:template>

  <xsl:template match="rml:ing">
    <li>
      <div class="units">
        <xsl:value-of select="rml:amt/rml:qty"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="rml:amt/rml:unit"/>
      </div>
      <div class="item">
        <xsl:value-of select="rml:item"/>
      </div>
    </li>
  </xsl:template>

  <xsl:template match="rml:step">
    <li><xsl:apply-templates/></li>
  </xsl:template>

  <xsl:template match="rml:dir-div">
    <div><xsl:apply-templates/></div>
  </xsl:template>
</xsl:stylesheet>
