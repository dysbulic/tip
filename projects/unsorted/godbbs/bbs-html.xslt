<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="bbs">
  <!-- <xsl:processing-instruction name="cocoon-format">type="text/html"</xsl:processing-instruction> -->
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
      <title><xsl:value-of select="title"/></title>
      <link rel="stylesheet" href="../styles/resume.css" type="text/css"/>
      <style type="text/css">
        table { border: 1px solid; border-collapse: collapse; }
        th, td { padding-left: .5em; padding-right: .5em; border: 1px solid; }
        .post-id, .user-id, .time, .forum-id, .thread-id { text-align: left; }
        .time { text-align: right; }
        .user-id { text-align: center; }
        .alias { font-style: italic; }
        .hr { border: none; }
        p { text-align: justify; }
      </style>
    </head>
    <body>
       <xsl:apply-templates/>
    </body>
  </html>
</xsl:template>

<xsl:template match="title">
  <h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="subtitle">
  <h3><xsl:apply-templates/></h3>
</xsl:template>

<xsl:template match="forum">
  <table>
    <xsl:apply-templates/>
  </table>
</xsl:template>

<xsl:template match="thread">
  <xsl:apply-templates/>
</xsl:template>

<xsl:template match="post">
  <xsl:variable name="auth" select="author"/>
  <tr>
    <th class="post-id">
      Post #<xsl:value-of select="count(preceding-sibling::*) + 1"/>
    </th>
    <th class="user-id">
      <xsl:value-of select="$auth"/>
      <xsl:if test="count(//user[username=$auth]/alias) > 0">
        <xsl:text> (</xsl:text>
        <span class="alias">
          <xsl:value-of select="/descendant::user[child::username=$auth]/child::alias"/>
        </span>
        <xsl:text>)</xsl:text>
      </xsl:if>
    </th>
    <th class="time">
      <xsl:value-of select="time"/>
    </th>
  </tr>
  <tr>
    <td colspan="3">
      <xsl:apply-templates/>
    </td>
  </tr>
  <xsl:if test="position() != last()">
    <tr><td class="hr" colspan="3"><hr width="80%"/></td></tr>
  </xsl:if>
</xsl:template>

<xsl:template match="p">
  <xsl:choose>
    <xsl:when test="@type='normal'">
      <p><xsl:apply-templates/></p>
    </xsl:when>
    <xsl:when test="@type='pre'">
      <pre><xsl:apply-templates/></pre>
    </xsl:when>
    <xsl:otherwise>
      <p>Don't know how to handle <xsl:value-of select="@type"/> for:</p>
      <p><xsl:apply-templates/></p>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="line">
  <xsl:apply-templates/><br/>
</xsl:template>

<xsl:template match="quote">
  <hr width="80%"/>
  <xsl:apply-templates/>
</xsl:template>

<xsl:template match="user | author | time"/>

</xsl:stylesheet>
