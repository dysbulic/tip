<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="html" standalone="yes" indent="yes"/>

<xsl:template match="page">
  <html>
    <head>
      <base href="http://odin.himinbi.org"/>
      <title>Users</title>
      <link rel="stylesheet" href="/styles/resume.css" type="text/css"/>
    </head>
    <body>
       <table width="100%">
         <xsl:apply-templates/>
       </table>
    </body>
  </html>
</xsl:template>

<xsl:template match="user">
  <tr>
    <th colspan="2"><xsl:value-of select="child::name"/></th>
  </tr>
  <xsl:for-each select="child::email">
    <tr>
      <td> </td>
      <td>
        <xsl:element name="a">
          <xsl:attribute name="href">mailto:<xsl:value-of select="child::username"/>@<xsl:value-of select="child::domain"/></xsl:attribute>
          <xsl:value-of select="child::username"/>@<xsl:value-of select="child::domain"/>
        </xsl:element>
      </td>
    </tr>
  </xsl:for-each>
</xsl:template>

</xsl:stylesheet>
