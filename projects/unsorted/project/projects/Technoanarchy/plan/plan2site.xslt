<?xml version="1.0"?>
<!DOCTYPE stylesheet [
          <!ENTITY mdash "&#8212;">
]>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
                xmlns:lxslt="http://xml.apache.org/xslt"
                xmlns:redirect="http://xml.apache.org/xalan/redirect"
                xmlns:doc="http://docbook.org/ns/docbook"
                xmlns="http://www.w3.org/1999/xhtml"
                extension-element-prefixes="redirect">

<xsl:output method="xml"
            standalone="yes"
            indent="yes"
            doctype-public="-//W3C//DTD XHTML 1.1//EN"
            doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:template match="doc:info"></xsl:template>

<xsl:template match="doc:preface">
  <html xmlns="http://www.w3.org/1999/xhtml">
    <xsl:call-template name="head">
      <xsl:with-param name="title">Technoanarchy</xsl:with-param>
    </xsl:call-template>
    <body>
      <xsl:call-template name="header" />
      <div id="body">
        <xsl:apply-templates mode="copy"/>
      </div>
    </body>
  </html>
</xsl:template>

<xsl:template match="doc:section">
  <xsl:variable name="filename"><xsl:call-template name="filename_base"/>.html</xsl:variable>
  <xsl:document method="xml" href="{$filename}">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <xsl:call-template name="head">
        <xsl:with-param name="title">
          <xsl:value-of select="//doc:info/doc:title" /> &mdash; Step #<xsl:value-of select="count(preceding-sibling::doc:section) + 1"/>
        </xsl:with-param>
      </xsl:call-template>
      <body>
        <xsl:call-template name="header" />
        <div id="body">
          <h1>
            <xsl:text>Step #</xsl:text><xsl:value-of select="count(preceding-sibling::doc:section) + 1"/>
            <xsl:text>: </xsl:text><xsl:value-of select="doc:title" />
          </h1>
          <xsl:apply-templates select="doc:title/following-sibling::*" mode="copy"/>
        </div>
        <div id="footer">
          <ol>
            <xsl:apply-templates select="preceding-sibling::doc:section[position() = 1]" mode="nav-item">
              <xsl:with-param name="class">previous</xsl:with-param>
            </xsl:apply-templates>
            <xsl:apply-templates select="following-sibling::doc:section[position() = 1]" mode="nav-item">
              <xsl:with-param name="class">next</xsl:with-param>
            </xsl:apply-templates>
          </ol>
        </div>
      </body>
    </html>
  </xsl:document>
</xsl:template>

<xsl:template name="header">
  <div id="header">
    <h3><a href="."><span><xsl:value-of select="//doc:info/doc:title" /></span></a></h3>
    <xsl:call-template name="navigation"/>
  </div>
</xsl:template>

<xsl:template name="head">
  <xsl:param name="title" />
  <head>
    <title><xsl:value-of select="$title" /></title>
    <script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"><xsl:text> </xsl:text></script>
    <script type="text/javascript" src="preloadCssImages.jquery.js"><xsl:text> </xsl:text></script>
    <script type="text/javascript" src="nav_hover.js"><xsl:text> </xsl:text></script>
    <script type="text/javascript">
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      try {
        var pageTracker = _gat._getTracker("UA-2592249-6");
        pageTracker._trackPageview();
      } catch(err) {}
    </script>
    <style type="text/css"><xsl:text> </xsl:text></style>
    <link rel="stylesheet" href="revolution.css" type="text/css"/>
    <link rel="stylesheet" href="header.css" type="text/css"/>
  </head>
</xsl:template>

<!-- Default is to pass elements through -->
<xsl:template match="node() | @*" mode="copy">
  <xsl:copy>
    <xsl:apply-templates select="node() | @*" mode="copy"/>
  </xsl:copy>
</xsl:template>

<xsl:template match="doc:para" mode="copy">
  <p><xsl:apply-templates mode="copy"/></p>
</xsl:template>

<xsl:template match="doc:orderedlist" mode="copy">
  <ol><xsl:apply-templates mode="copy"/></ol>
</xsl:template>

<xsl:template match="doc:listitem" mode="copy">
  <li><xsl:apply-templates mode="copy"/></li>
</xsl:template>

<xsl:template match="doc:ulink" mode="copy">
  <xsl:element name="a">
    <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
    <xsl:apply-templates mode="copy"/>
  </xsl:element>
</xsl:template>

<xsl:template name="filename_base">
  <xsl:variable name="lcletters">abcdefghijklmnopqrstuvwxyz</xsl:variable>
  <xsl:variable name="ucletters">ABCDEFGHIJKLMNOPQRSTUVWXYZ</xsl:variable>
  <xsl:variable name="t_filename">
    <xsl:apply-templates select="doc:title"/>
  </xsl:variable>
  <xsl:variable name="lc_filename">
    <xsl:value-of select="translate($t_filename, $ucletters, $lcletters)"/>
  </xsl:variable>
  <xsl:value-of select="translate($lc_filename, ' ', '_')"/>
</xsl:template>

<xsl:template name="navigation">
  <xsl:variable name="filename_base"><xsl:call-template name="filename_base"/></xsl:variable>
  <ol id="navigation">
    <xsl:apply-templates select="preceding-sibling::doc:section" mode="nav-item" />
    <xsl:if test="name() = 'section'">
      <li id="{$filename_base}" class="active"><xsl:call-template name="step-name" /></li>
    </xsl:if>
    <xsl:apply-templates select="following-sibling::doc:section" mode="nav-item" />
  </ol>
</xsl:template>

<xsl:template match="doc:section" mode="nav-item">
  <xsl:param name="class" />
  <xsl:variable name="filename_base"><xsl:call-template name="filename_base"/></xsl:variable>
  <li id="{$filename_base}" class="{$class}"><a href="{$filename_base}.html">
    <xsl:call-template name="step-name" />
  </a></li>
</xsl:template>

<xsl:template name="step-name">
  <span>
    <xsl:text>Step #</xsl:text><xsl:value-of select="count(preceding-sibling::doc:section) + 1"/>
    <xsl:text>: </xsl:text><xsl:value-of select="doc:title" />
  </span>
</xsl:template>

</xsl:stylesheet>
