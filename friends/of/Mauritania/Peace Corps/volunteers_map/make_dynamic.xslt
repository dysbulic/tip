<?xml version="1.0"?>
<!-- XSLT stylesheet to take the map and make it into a
   -  dynamic representation of volunteer locations.
  -->
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:svg="http://www.w3.org/2000/svg"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output
   method="xml"
   standalone="no"
   indent="yes"/>

  <xsl:template match="/">
    <xsl:processing-instruction name="xml-stylesheet">
      type="text/css" href="map.css"
    </xsl:processing-instruction>
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="svg:svg">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:attribute name="a:scriptImplementation">Adobe</xsl:attribute>
      <xsl:text>
      </xsl:text>
      <script type="text/ecmascript"
       a:scriptImplementation="Adobe" xlink:href="coordinate.js"/>
      <xsl:text>
      </xsl:text>
      <script type="text/ecmascript"
       a:scriptImplementation="Adobe" xlink:href="translate_to_user.js"/>
      <xsl:text>
      </xsl:text>
      <script type="text/ecmascript"
       a:scriptImplementation="Adobe" xlink:href="load_file.js"/>
      <xsl:text>
      </xsl:text>
      <script type="text/ecmascript"
       a:scriptImplementation="Adobe" xlink:href="add_cities.js"/>
      <xsl:text>
      </xsl:text>
      <script type="text/ecmascript"
       a:scriptImplementation="Adobe" xlink:href="add_volunteers.js"/>
      <xsl:text>
      </xsl:text>
      <metadata>
        <html:link html:rel="volunteers" html:href="../volunteers.xml"/>
        <html:link html:rel="geodata" html:href="mauritania.xml"/>
      </metadata>
      <xsl:apply-templates/>
      <!-- Adobe viewer (v.3.0) doesn't support animation in
         - <image>s or <use> of elements in other pages, so it is
         - necessary to include the element this way.
        -->
      <xsl:copy-of select="document('target.svg')/svg:svg/*"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="*|@*">
    <xsl:copy-of select="."/>
  </xsl:template>

  <xsl:template match="svg:svg/@width|svg:svg/@height">
    <xsl:attribute name="{name()}">100%</xsl:attribute>
  </xsl:template>

  <xsl:template match="svg:style">
  </xsl:template>
</xsl:stylesheet>
