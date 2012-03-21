<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
 xmlns:lxslt="http://xml.apache.org/xslt"
 xmlns:redirect="http://xml.apache.org/xalan/redirect"
 extension-element-prefixes="redirect">

<xsl:output method="xml"
            standalone="yes"
            indent="yes"/>
<!--
  doctype-public="-//W3C//DTD XHTML 1.1//EN"
  doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>
-->

<xsl:variable name="stylesheet">../styles/hpeo.css</xsl:variable>
<xsl:variable name="default_width">600</xsl:variable>
<xsl:variable name="default_height">500</xsl:variable>
<xsl:variable name="base_url">http://odin.himinbi.org/jars/</xsl:variable>
<xsl:param name="output_dir"/>

<xsl:template match="/">
  <xsl:apply-templates select="//program" />
</xsl:template>

<xsl:template name="filename">
  <xsl:variable name="t_filename">
    <xsl:apply-templates select="name"/>
    <xsl:text>.jnlp</xsl:text>
  </xsl:variable>
  <xsl:value-of select="translate($t_filename, ' ', '_')"/>
</xsl:template>

<xsl:template match="program">
  <xsl:variable name="filename"><xsl:call-template name="filename"/></xsl:variable>
  <redirect:write file="{$filename}">
    <jnlp spec="1.0+" codebase="{$base_url}" href="{$filename}">
      
      <information>
        <title><xsl:apply-templates select="name"/></title>
        <vendor>Will Holcomb</vendor>
        <homepage href="http://www.himinbi.org"/>
        <!--
            <description><xsl:apply-templates select="description/*" mode="copy"/></description>
            <description kind="short"></description>
            <icon href="myicon.gif"/>
        -->
        <offline-allowed/>
      </information>
      
      <security>
        <all-permissions/>
      </security>
      
      <resources>
        <j2se version="1.5+"/>
        <xsl:for-each select="archive">
          <xsl:element name="jar">
            <xsl:attribute name="main">true</xsl:attribute>
            <xsl:attribute name="href"><xsl:apply-templates/></xsl:attribute>
          </xsl:element>
        </xsl:for-each>
        <extension href="http://download.java.net/media/java3d/webstart/release/java3d-1.4-latest.jnlp"/>
      </resources>
      <xsl:element name="application-desc">
        <xsl:attribute name="main-class"><xsl:apply-templates select="run"/></xsl:attribute>
    </xsl:element>
    </jnlp>
  </redirect:write>
</xsl:template>
</xsl:stylesheet>
