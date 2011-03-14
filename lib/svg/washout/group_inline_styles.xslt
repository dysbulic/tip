<?xml version="1.0"?>
<!-- XSLT stylesheet to take a svg and pull it's inline styles into
   -  a block at the top
  -->
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:lxslt="http://xml.apache.org/xslt"
 xmlns:redirect="http://xml.apache.org/xalan/redirect"
 xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 extension-element-prefixes="redirect">
  <xsl:output method="xml" standalone="no" indent="yes"/>
  <xsl:param name="style_filename"/>
  <xsl:param name="washout"/>

  <xsl:template match="/">
    <xsl:if test="$style_filename != ''">
      <xsl:processing-instruction name="xml-stylesheet">
        <xsl:text>type="text/css" href="</xsl:text>
        <xsl:value-of select="$style_filename"/>
        <xsl:text>"</xsl:text>
      </xsl:processing-instruction>
    </xsl:if>
    <xsl:if test="$washout != ''">
      <xsl:processing-instruction name="xml-stylesheet">
        <xsl:text>type="text/css" href="washout.css"</xsl:text>
      </xsl:processing-instruction>
    </xsl:if>
    <xsl:apply-templates/>
  </xsl:template>

  <!-- can't use copy-of because individual elements need to be processed -->
  <xsl:template match="*|@*">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="svg:defs">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:if test="$washout = ''">
        <xsl:element name="style">
          <xsl:attribute name="type">text/css</xsl:attribute>
          <xsl:choose>
            <xsl:when test="$style_filename != ''">
              <xsl:attribute name="xlink:href">
                <xsl:value-of select="$style_filename" />
              </xsl:attribute>
              <redirect:write file="{$style_filename}" method="text">
                <xsl:apply-templates select="/" mode="print-styles" />
              </redirect:write>
            </xsl:when>
            <xsl:otherwise>
              <xsl:apply-templates select="/" mode="print-styles" />
            </xsl:otherwise>
          </xsl:choose>
        </xsl:element>
      </xsl:if>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

  <!-- In addition to pulling out the styles, it also makes it 100%
     - and sets the viewBox so it will scale -->
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

  <xsl:template match="@style"></xsl:template>

  <xsl:template match="*" mode="print-styles">
    <xsl:if test="@style != ''">
      <xsl:text>#</xsl:text>
      <xsl:value-of select="@id"/>
      <xsl:text> { </xsl:text>
      <xsl:value-of select="@style"/>
      <xsl:text> }
</xsl:text>
    </xsl:if>
    <xsl:apply-templates mode="print-styles" />
  </xsl:template>

  <xsl:template match="text()" mode="print-styles"></xsl:template>
</xsl:stylesheet>
