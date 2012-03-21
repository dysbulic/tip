<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:set="http://exslt.org/sets"
 xmlns:fo="http://www.w3.org/1999/XSL/Format"
 xmlns:rml="http://www.formatdata.com/recipeml/">
  <xsl:output
   standalone="no"
   indent="yes"/>

  <xsl:template match="/">
<!--
    <xsl:processing-instruction name="xml-stylesheet">
      href="fo_page.css" type="text/css"
    </xsl:processing-instruction>
-->
    <fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
      <fo:layout-master-set>
        <fo:simple-page-master master-name="simple"
         page-height="29.7cm" page-width="21cm"
         margin-top="1cm" margin-bottom="2cm" 
         margin-left="2.5cm" margin-right="2.5cm">
          <fo:region-body margin-top="3cm" margin-bottom="1.5cm"/>
          <fo:region-before extent="3cm"/>
          <fo:region-after extent="1.5cm"/>
        </fo:simple-page-master>
      </fo:layout-master-set>

      <fo:page-sequence master-reference="simple">
        <fo:flow flow-name="xsl-region-body"
                 font-family="sans-serif" font-size="13pt">
          <xsl:apply-templates select="//rml:recipe"/>
        </fo:flow>
      </fo:page-sequence>
    </fo:root>
  </xsl:template>

  <xsl:template match="rml:recipe">
    <fo:block font-size="18pt" line-height="24pt"
              space-after.optimum="15pt" background-color="blue"
              color="white" text-align="center"
              padding-top="3pt" id="{generate-id()}">
      <xsl:apply-templates select="rml:head/rml:title"/>
    </fo:block>
    <xsl:apply-templates select="rml:ingredients"/>
    <fo:block text-align="justify" space-after.optimum="15pt">
      <xsl:apply-templates select="rml:directions"/>
    </fo:block>
  </xsl:template>

  <!-- I want the ingredients in a two column list that seems
     -  to be most easily represented with a four column table
     -  broken into two major columns i and i + n / 2.
     - Where 0 <= i <= n - 1.
     - Since we have no variable assignment it is a little tricky...
    -->
  <xsl:template match="rml:ingredients">
    <fo:table table-layout="fixed"
     space-after.optimum="15pt" width="100%">
      <fo:table-column column-width="proportional-column-width(1.5)"/>
      <fo:table-column column-width="proportional-column-width(4)"/>
      <fo:table-column column-width="proportional-column-width(1.5)"/>
      <fo:table-column column-width="proportional-column-width(4)"/>
      <fo:table-body>
        <xsl:call-template name="ingredients-row">
          <xsl:with-param name="count" select="count(rml:ing)"/>
          <xsl:with-param name="index" select="1"/>
        </xsl:call-template>
      </fo:table-body>
    </fo:table>
  </xsl:template>

  <xsl:template name="ingredients-row">
    <xsl:param name="count"/>
    <xsl:param name="index"/>

    <fo:table-row>
      <fo:table-cell><fo:block>
        <xsl:value-of select="rml:ing[position() = $index]/rml:amt/rml:qty"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="rml:ing[position() = $index]/rml:amt/rml:unit"/>
      </fo:block></fo:table-cell>
      <fo:table-cell><fo:block>
        <xsl:value-of select="rml:ing[position() = $index]/rml:item"/>
      </fo:block></fo:table-cell>
      <xsl:variable name="nextindex" select="ceiling($index + $count div 2)"/>
      <fo:table-cell><fo:block>
        <xsl:value-of select="rml:ing[position() = $nextindex]/rml:amt/rml:qty"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="rml:ing[position() = $nextindex]/rml:amt/rml:unit"/>
      </fo:block></fo:table-cell>
      <fo:table-cell><fo:block>
        <xsl:value-of select="rml:ing[position() = $nextindex]/rml:item"/>
      </fo:block></fo:table-cell>
    </fo:table-row>
    <xsl:if test="$index &lt; $count div 2">
      <xsl:call-template name="ingredients-row">
        <xsl:with-param name="count" select="$count"/>
        <xsl:with-param name="index" select="$index + 1"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>

  <xsl:template match="rml:step">
    <fo:inline keep-together="always">
      <fo:inline font-weight="bold">
        <xsl:number format="1."/>
      </fo:inline>
      <xsl:text> </xsl:text>
      <xsl:apply-templates/>
    </fo:inline>
  </xsl:template>

  <xsl:template match="rml:dir-div">
    <xsl:element name="fo:block">
      <xsl:if test="count(preceding-sibling::*) &gt; 0">
        <xsl:attribute name="space-before.optimum">12pt</xsl:attribute>
      </xsl:if>
      <xsl:apply-templates/>
    </xsl:element>
  </xsl:template>
</xsl:stylesheet>
