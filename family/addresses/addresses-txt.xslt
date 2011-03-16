<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output
   method="text"
   standalone="yes"
   indent="no"/>
  <xsl:strip-space elements="*"/>
<!--  <xsl:preserve-space elements="para"/> -->

  <xsl:template match="household">
    <xsl:apply-templates/>
    <xsl:if test="count(following-sibling::household) > 0">
      <xsl:text>
</xsl:text>
    </xsl:if>
  </xsl:template>

  <xsl:template match="member">
    <xsl:value-of select="name"/>
    <xsl:if test="count(phone) > 0"><xsl:text>  </xsl:text>[<xsl:value-of select="phone"/>]</xsl:if>
    <xsl:if test="count(email) > 0"><xsl:text>  </xsl:text>(<xsl:value-of select="email"/>)</xsl:if><xsl:text>
</xsl:text>
  </xsl:template>

  <xsl:template match="address">
    <xsl:for-each select="street">
      <xsl:text>  </xsl:text>
      <xsl:value-of select="."/><xsl:text>
</xsl:text>
    </xsl:for-each>
    <xsl:text>  </xsl:text>
    <xsl:value-of select="city"/>
    <xsl:text>, </xsl:text>
    <xsl:value-of select="state"/>
    <xsl:text>  </xsl:text>
    <xsl:value-of select="zip"/><xsl:text>
</xsl:text>
  </xsl:template>

  <xsl:template match="phone">
    <xsl:text>  </xsl:text>
    <xsl:apply-templates/><xsl:text>
</xsl:text>
  </xsl:template>

</xsl:stylesheet>