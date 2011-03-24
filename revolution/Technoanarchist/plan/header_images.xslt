<?xml version="1.0"?>
<!-- Author: Will Holcomb <will@technoanarchy.org>
   - Date: April 2009
   - Generates CSS style and SVGs to rasterize for header.
  -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
                xmlns:svg="http://www.w3.org/2000/svg">
<xsl:output method="text" standalone="yes" indent="yes" />

<!-- Pad to compensate for part of the line lying outside the viewbox -->
<xsl:variable name="pad">8</xsl:variable>

<xsl:template match="/">
  <xsl:apply-templates select="svg:svg/svg:g" />
  /* Size to use background image. Text hidden by jquery. */
  #navigation li a, #navigation li { display: block; width: 150px; height: 140px; background-repeat: no-repeat; }
</xsl:template>

<xsl:template match="svg:g">
  <xsl:call-template name="doc">
    <xsl:with-param name="filename"><xsl:value-of select="@id"/>_inactive.svg</xsl:with-param>
    <xsl:with-param name="style">svg { opacity: .5; }</xsl:with-param>
    <xsl:with-param name="offset">-.5</xsl:with-param>
  </xsl:call-template>
  <xsl:call-template name="doc">
    <xsl:with-param name="filename"><xsl:value-of select="@id"/>_active.svg</xsl:with-param>
    <xsl:with-param name="style">svg rect { fill: #fbf8bd ! important; }</xsl:with-param>
  </xsl:call-template>
  <xsl:call-template name="doc">
    <xsl:with-param name="filename"><xsl:value-of select="@id"/>.svg</xsl:with-param>
  </xsl:call-template>
  #<xsl:value-of select="@id"/> a { background-image: url('<xsl:value-of select="@id"/>_inactive.png'); }
  #<xsl:value-of select="@id"/> a:hover { background-image: url('<xsl:value-of select="@id"/>.png'); }
  #<xsl:value-of select="@id"/>.active { background-image: url('<xsl:value-of select="@id"/>_active.png'); }
</xsl:template>

<xsl:template name="doc">
  <xsl:param name="filename"/>
  <xsl:param name="style"/>
  <xsl:param name="offset" select="0"/>
  <xsl:document method="xml" href="{$filename}">
    <xsl:variable name="x"><xsl:value-of select="svg:rect[position() = 1]/@x" /></xsl:variable>
    <xsl:variable name="y"><xsl:value-of select="svg:rect[position() = 1]/@y" /></xsl:variable>
    <xsl:variable name="width"><xsl:value-of select="svg:rect[position() = 1]/@width" /></xsl:variable>
    <xsl:variable name="height"><xsl:value-of select="svg:rect[position() = 1]/@height" /></xsl:variable>
    <xsl:element name="svg">
      <xsl:attribute name="xmlns">http://www.w3.org/2000/svg</xsl:attribute>
      <xsl:attribute name="version">1.0</xsl:attribute>
      <xsl:attribute name="width">100%</xsl:attribute>
      <xsl:attribute name="height">100%</xsl:attribute>
      <xsl:attribute name="viewBox">
        <xsl:value-of select="$x - ($pad div 2) + $offset"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="$y - ($pad div 2) + $offset"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="$width + $pad"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="$height + $pad"/>
      </xsl:attribute>
      <style type="text/css">
        <xsl:value-of select="$style" />
      </style>
      <xsl:apply-templates select="//svg:defs" mode="copy"/>
      <xsl:apply-templates mode="copy"/>
    </xsl:element>
  </xsl:document>
</xsl:template>

<!-- Default is to pass elements through -->
<xsl:template match="node() | @*" mode="copy">
  <xsl:copy>
    <xsl:apply-templates select="node() | @*" mode="copy"/>
  </xsl:copy>
</xsl:template>
</xsl:stylesheet>
