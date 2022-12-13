<?xml version="1.0"?>
<!--
  Converts the XML from http://heinous.org/files/scripts/wordpress-lj/jbackup.pl.txt
  to a format appropriate for use with WordPress 2.5's LiveJournal import function.
  
  Doesn't properly handle comments yet...

  WordPress can't handle the privacy options properly in general.
-->  
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="xml" standalone="no" indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="*|@*">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

  <!-- import script expects entrys to have no attributes -->
  <xsl:template match="entry/@*">
  </xsl:template>

  <!-- import script uses eventtime rather than date -->
  <xsl:template match="date">
    <eventtime><xsl:apply-templates/></eventtime>
  </xsl:template>

  <xsl:template match="comment">
    <comment>
      <name><xsl:value-of select="@poster" /></name>
      <xsl:apply-templates/>
    </comment>
  </xsl:template>

  <xsl:template match="comment/body">
    <event><xsl:apply-templates/></event>
  </xsl:template>
  
</xsl:stylesheet>
