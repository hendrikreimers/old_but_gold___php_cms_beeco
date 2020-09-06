<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output method="html"/>

<xsl:variable name="path"><xsl:value-of select="plugin/settings/path"/></xsl:variable>
<xsl:variable name="title"><xsl:value-of select="plugin/@title"/></xsl:variable>
<xsl:variable name="name"><xsl:value-of select="plugin/@name"/></xsl:variable>

<xsl:template match="settings">
  <xsl:comment>--------- DO NOTHING ---------</xsl:comment>
</xsl:template>

<xsl:template match="text()">
  <xsl:value-of select="."/>
</xsl:template>

<xsl:template match="plugin">
  <xsl:apply-templates/>
</xsl:template>

</xsl:stylesheet>