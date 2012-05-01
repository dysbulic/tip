<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
 xmlns:lxslt="http://xml.apache.org/xslt"
 xmlns:redirect="http://xml.apache.org/xalan/redirect"
 extension-element-prefixes="redirect">

<xsl:output method="xml"
            standalone="yes"
            indent="yes"
            doctype-public="-//W3C//DTD XHTML 1.1//EN"
            doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:variable name="stylesheet">../styles/hpeo.css</xsl:variable>
<xsl:variable name="default_width">600</xsl:variable>
<xsl:variable name="default_height">500</xsl:variable>
<xsl:variable name="type">application/x-java-applet</xsl:variable>
<xsl:param name="output_dir"/>

<xsl:template match="programlist">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
      <title><xsl:value-of select="@name"/></title>
      <link rel="stylesheet" href="{$stylesheet}" type="text/css"/>
    </head>
    <body>
      <xsl:apply-templates select="intro/*" mode="copy"/>
      <table border="1">
        <xsl:for-each select="program">
          <tr>
            <td>
              <xsl:element name="a">
                <xsl:attribute name="href">
                  <xsl:call-template name="filename"/>
                </xsl:attribute>
                <xsl:apply-templates select="name"/>
              </xsl:element>
            </td>
            <td><xsl:apply-templates select="description/*" mode="copy"/></td>
          </tr>
          <xsl:for-each select="archive">
            <tr>
              <td colspan="2" style="text-align: center;">
                <xsl:element name="a">
                  <xsl:attribute name="href">
                    <xsl:apply-templates/>
                  </xsl:attribute>
                  <xsl:apply-templates/>
                </xsl:element>
              </td>
            </tr>
          </xsl:for-each>
          <xsl:apply-templates select="." mode="file"/>
        </xsl:for-each>
      </table>
      <xsl:apply-templates select="outro/*" mode="copy"/>
    </body>
  </html>
</xsl:template>

<xsl:template match="node() | @*" mode="copy">
  <xsl:copy>
    <xsl:apply-templates select="node() | @*" mode="copy"/>
  </xsl:copy>
</xsl:template>

<xsl:template name="filename">
  <xsl:variable name="t_filename">
    <xsl:apply-templates select="name"/>
    <xsl:text>.html</xsl:text>
  </xsl:variable>
  <xsl:value-of select="translate($t_filename, ' ', '_')"/>
</xsl:template>

<xsl:template match="program" mode="file">
  <xsl:variable name="filename"><xsl:call-template name="filename"/></xsl:variable>
  <xsl:variable name="width">
    <xsl:choose>
      <xsl:when test="count(size/width) != 0"><xsl:apply-templates select="size/width"/></xsl:when>
      <xsl:otherwise><xsl:value-of select="$default_width"/></xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:variable name="height">
    <xsl:choose>
      <xsl:when test="count(size/height) != 0"><xsl:apply-templates select="size/height"/></xsl:when>
      <xsl:otherwise><xsl:value-of select="$default_height"/></xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <redirect:write file="{$filename}">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
        <title><xsl:apply-templates select="name"/></title>
        <link rel="stylesheet" href="{$stylesheet}" type="text/css"/>
      </head>
      <body>
        <xsl:if test="count(usage) != 0">
          <div class="usage_border">
            <xsl:apply-templates select="usage/*" mode="copy"/>
          </div>
        </xsl:if>

        <xsl:comment>[if !IE]></xsl:comment>
        <xsl:element name="object">
          <xsl:attribute name="width"><xsl:value-of select="$width"/></xsl:attribute>
          <xsl:attribute name="height"><xsl:value-of select="$height"/></xsl:attribute>
          <xsl:attribute name="classid">
            <xsl:text>java:</xsl:text>
            <xsl:apply-templates select="run"/>
          </xsl:attribute>
          <xsl:attribute name="archive"><xsl:apply-templates select="archive"/></xsl:attribute>
          <xsl:for-each select="param">
            <xsl:element name="param">
              <xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
              <xsl:attribute name="value"><xsl:apply-templates/></xsl:attribute>
            </xsl:element> 
          </xsl:for-each>
          <xsl:comment> Konqueror browser needs the following param </xsl:comment>
          <xsl:for-each select="archive">
            <xsl:element name="param">
              <xsl:attribute name="name">archive</xsl:attribute>
              <xsl:attribute name="value"><xsl:apply-templates/></xsl:attribute>
            </xsl:element>
          </xsl:for-each>
          <xsl:comment>&lt;![endif]</xsl:comment>
          <xsl:element name="object">
            <xsl:attribute name="classid">clsid:8AD9C840-044E-11D1-B3E9-00805F499D93</xsl:attribute>
            <xsl:attribute name="width"><xsl:value-of select="$width"/></xsl:attribute>
            <xsl:attribute name="height"><xsl:value-of select="$height"/></xsl:attribute>
            <xsl:element name="param">
              <xsl:attribute name="name">code</xsl:attribute>
              <xsl:attribute name="value"><xsl:apply-templates select="run"/></xsl:attribute>
            </xsl:element> 
            <xsl:for-each select="archive">
              <xsl:element name="param">
                <xsl:attribute name="name">archive</xsl:attribute>
                <xsl:attribute name="value"><xsl:apply-templates/></xsl:attribute>
              </xsl:element> 
            </xsl:for-each>
            <xsl:for-each select="param">
              <xsl:element name="param">
                <xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
                <xsl:attribute name="value"><xsl:apply-templates/></xsl:attribute>
              </xsl:element> 
            </xsl:for-each>
          </xsl:element>
          <xsl:comment>[if !IE]></xsl:comment>
        </xsl:element>
        <xsl:comment>&lt;![endif]</xsl:comment>
        
        <!--
          <xsl:element name="param">
            <xsl:attribute name="name">type</xsl:attribute>
            <xsl:attribute name="value"><xsl:value-of select="$type"/></xsl:attribute>
          </xsl:element>
        -->
        <hr/>
        <p>
          <xsl:text>If this isn't working, you can also </xsl:text>
          <xsl:element name="a">
            <xsl:attribute name="href">
              <xsl:value-of select="substring($filename, 0, string-length($filename) - 3)"/>
              <xsl:text>jnlp</xsl:text>
            </xsl:attribute>
            <xsl:text>launch using web start</xsl:text>
          </xsl:element>
          <xsl:text>.</xsl:text>
        </p>
        <hr/>
        <xsl:apply-templates select="description" mode="copy"/>
        <hr/>
        <ul>
          <xsl:for-each select="archive">
            <li>
              <xsl:element name="a">
                <xsl:attribute name="href">
                  <xsl:apply-templates/>
                </xsl:attribute>
                <xsl:apply-templates/>
              </xsl:element>
            </li>
          </xsl:for-each>
        </ul>
        <hr/>
        <p>See <a href=".">more programs</a> or <a href="/">go home</a>.</p>
      </body>
    </html>
  </redirect:write>
</xsl:template>

</xsl:stylesheet>
