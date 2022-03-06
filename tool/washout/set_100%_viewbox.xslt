<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:lxslt="http://xml.apache.org/xslt"
 xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:str="http://exslt.org/strings">
  <xsl:output method="xml" standalone="no" indent="yes"/>

  <xsl:template match="node()|@*" name="copy">
    <xsl:copy>
      <xsl:apply-templates select="node()|@*"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="svg:svg">
    <xsl:copy>
      <xsl:choose>
        <xsl:when test="@viewBox">
          <xsl:copy-of select="@viewBox"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:attribute name="viewBox">
            <xsl:text>0 </xsl:text>
            <xsl:text>0 </xsl:text>
            <xsl:value-of select="str:replace(@width, 'px', '')"/>
            <xsl:text> </xsl:text>
            <xsl:value-of select="str:replace(@height, 'px', '')"/>
          </xsl:attribute>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:apply-templates select="node()"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="svg:svg/@width|svg:svg/@height|svg:svg/@viewBox"/>
</xsl:stylesheet>
