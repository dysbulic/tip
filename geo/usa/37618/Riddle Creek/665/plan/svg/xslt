<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:exsl="http://exslt.org/common"
 extension-element-prefixes="exsl">
  <xsl:output method="xml" standalone="no" indent="yes"/>
  <xsl:param name="outdir"/>

  <xsl:template match="/">
    <xsl:for-each select="/*/svg:g">
      <exsl:document href="{$outdir}/{@id}.svg" method="xml">
        <svg><xsl:copy-of select="."/></svg>
      </exsl:document>
    </xsl:for-each>
  </xsl:template>
</xsl:stylesheet>
