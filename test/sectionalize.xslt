<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:exsl="http://exslt.org/common"
 extension-element-prefixes="exsl">
  <xsl:output
   method="xml"
   standalone="no"
   indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates select="//main"/>
  </xsl:template>

  <xsl:template match="section">
    <title>test</title>
    <xsl:document href="section-{position()}.html">
      <html xmlns="http://www.w3.org/1999/xhtml">
        <head><title><xsl:value-of select="./h2"/></title></head>
        <body>
          <xsl:copy-of select="current-group()"/>
        </body>
      </html>
    </xsl:document>
  </xsl:template>
</xsl:stylesheet>
