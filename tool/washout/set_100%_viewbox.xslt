<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:lxslt="http://xml.apache.org/xslt"
 xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="xml" standalone="no" indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates/>
  </xsl:template>

  <!-- can't use copy-of because individual elements need to be processed -->
  <xsl:template match="*|@*">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="svg:svg/@width|svg:svg/@height">
    <xsl:attribute name="{name()}">100%</xsl:attribute>
  </xsl:template>

  <xsl:template match="svg:svg/@id">
    <xsl:attribute name="viewBox">
      <xsl:text>0 </xsl:text>
      <xsl:text>0 </xsl:text>
      <xsl:value-of select="../@width"/>
      <xsl:text> </xsl:text>
      <xsl:value-of select="../@height"/>
    </xsl:attribute>
    <xsl:copy-of select="."/>
  </xsl:template>
</xsl:stylesheet>
