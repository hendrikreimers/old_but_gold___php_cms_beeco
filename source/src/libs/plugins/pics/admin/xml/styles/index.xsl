<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output method="html"/>

<xsl:include href="ground.xsl"/>
<xsl:include href="default.xsl"/>

<xsl:template match="group">

    <a>
      <xsl:attribute name="href">
        listgroup.php?group=<xsl:value-of select="id"/>&amp;SID=<xsl:value-of select="/plugin/settings/SID"/>
      </xsl:attribute>
      <xsl:value-of select="title"/>
    </a><br/>
    <xsl:value-of select="desc"/><br/>
<br/>

</xsl:template>

</xsl:stylesheet>