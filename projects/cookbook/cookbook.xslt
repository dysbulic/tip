<?xml version="1.0" encoding="utf-8"?>

<!-- I want to make a cookbook which is a combination or docbook and
   -  recipeML. This stylesheet needs to take that cookbook, import
   -  all the recipes, generate a table of content and generate an
   -  index by ingredient.
  -->

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:set="http://exslt.org/sets"
 xmlns:fo="http://www.w3.org/1999/XSL/Format"
 xmlns:rml="http://www.formatdata.com/recipeml/">
  <xsl:import href="rml-fo.xslt"/>
  <xsl:output
   standalone="no"
   indent="yes"
   />

  <xsl:template match="/">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="book">
<!--
    <xsl:processing-instruction name="xml-stylesheet">
      href="fo_page.css" type="text/css"
    </xsl:processing-instruction>
-->
    <fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">

      <fo:layout-master-set>
        <fo:simple-page-master master-name="first"
                               page-height="297mm" page-width="210mm"
                               margin-top="20mm" margin-bottom="20mm"
                               margin-left="25mm" margin-right="25mm">
          <fo:region-body margin-bottom="20mm"/>
          <fo:region-after region-name="footer-first" extent="20mm"/>
        </fo:simple-page-master>
        <fo:simple-page-master master-name="rest"
                               page-height="297mm" page-width="210mm"
                               margin-top="20mm" margin-bottom="20mm"
                               margin-left="25mm" margin-right="25mm">
          <fo:region-body margin-bottom="20mm"/>
          <fo:region-after region-name="footer-rest" extent="20mm"/>
        </fo:simple-page-master>
        <fo:page-sequence-master master-name="document">
          <fo:repeatable-page-master-alternatives>
            <fo:conditional-page-master-reference page-position="first"
                                                  master-reference="first"/>
            <fo:conditional-page-master-reference page-position="rest"
                                                  master-reference="rest"/>
          </fo:repeatable-page-master-alternatives>
        </fo:page-sequence-master>
      </fo:layout-master-set>
      
      <fo:page-sequence master-reference="document">
        <fo:static-content flow-name="footer-first">
          <fo:block text-align="center"></fo:block>
        </fo:static-content>
        <fo:static-content flow-name="footer-rest">
          <fo:block text-align-last="center"><fo:page-number/></fo:block>
        </fo:static-content>
        <fo:flow flow-name="xsl-region-body"
                 font-family="sans-serif" font-size="13pt">
          <xsl:apply-templates select="bookinfo"/>
          <fo:block break-before="page">
            <xsl:apply-templates select="preface"/>
          </fo:block>
          <xsl:call-template name="toc"/>
          <xsl:apply-templates select="chapter"/>
        </fo:flow>
      </fo:page-sequence>
    </fo:root>
  </xsl:template>

  <xsl:template match="chapter">
    <fo:block break-before="page" id="{generate-id()}">
      <xsl:apply-templates/>
    </fo:block>
  </xsl:template>

  <xsl:template match="bookinfo/title|chapter/title">
    <fo:block font-size="30pt" font-weight="bold"
              space-after.optimum="15pt" text-align="center">
      <xsl:apply-templates/>
    </fo:block>
  </xsl:template>

  <xsl:template match="preface/title">
    <fo:block font-size="20pt" space-after.optimum="15pt" text-align="center">
      <xsl:apply-templates/>
    </fo:block>
  </xsl:template>

  <xsl:template match="para">
    <fo:block><xsl:apply-templates/></fo:block>
  </xsl:template>

  <xsl:template name="toc">
    <fo:block break-before="page">
      <fo:block font-size="20pt" text-align-last="center" font-weight="bold">
        Table of Contents
      </fo:block>
      <fo:block text-align-last="justify"><fo:leader leader-pattern="rule"/></fo:block>
      <xsl:apply-templates select="//chapter|//rml:recipe" mode="toc"/>
    </fo:block>
  </xsl:template>

  <xsl:template match="chapter" mode="toc">
    <fo:block space-before="10pt" text-align-last="justify" font-weight="bold">
      <fo:basic-link internal-destination="{generate-id()}" color="blue">
        <xsl:value-of select="title"/>
      </fo:basic-link>
      <fo:leader leader-pattern="dots"/>
      <fo:page-number-citation ref-id="{generate-id()}"/>
    </fo:block>
  </xsl:template>

  <xsl:template match="rml:recipe" mode="toc">
    <fo:block margin-left="5pt" text-align-last="justify">
      <fo:basic-link internal-destination="{generate-id()}">
        <xsl:value-of select="rml:head/rml:title"/>
      </fo:basic-link>
      <fo:leader leader-pattern="dots"/>
      <fo:page-number-citation ref-id="{generate-id()}"/>
    </fo:block>
  </xsl:template>
</xsl:stylesheet>
