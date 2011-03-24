<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output
   method="xml"
   standalone="yes"
   indent="yes"
   doctype-public="-//W3C//DTD XHTML 1.1//EN"
   doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

  <xsl:template match="addresses">
    <html>
      <head>
        <title><xsl:value-of select="@title"/></title>
        <link rel="stylesheet" type="text/css" href="addresses.css"/>
      </head>
      <body>
        <h1><xsl:value-of select="@title"/>:</h1>
        <hr/>
        <xsl:apply-templates/>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="household">
    <div class="household">
      <table class="member">
        <xsl:apply-templates select="member"/>
      </table>
	<xsl:apply-templates select="address|phone"/>
    </div>
  </xsl:template>

  <xsl:template match="member">
    <tr>
      <td><xsl:apply-templates select="name"/></td>
      <td><xsl:apply-templates select="phone" mode="inline"/></td>
      <td><xsl:apply-templates select="email"/></td>
    </tr>
  </xsl:template>

  <xsl:template match="email">
    (<a href="mailto:{.}"><xsl:apply-templates/></a>)
  </xsl:template>

  <xsl:template match="phone" mode="inline">
    [<xsl:apply-templates/>]
  </xsl:template>

  <xsl:template match="city">
    <div class="{name()}"><xsl:apply-templates/></div>,
  </xsl:template>

  <xsl:template match="address|street|state|zip|phone">
    <div class="{name()}"><xsl:apply-templates/></div>
  </xsl:template>

<!--
    <xsl:variable name="linkend">
      <xsl:value-of select="@linkend"/>
    </xsl:variable>
    <a href="#{$linkend}">
      <xsl:apply-templates select="//*[@id = $linkend]" mode="name"/>
    </a>
-->
</xsl:stylesheet>