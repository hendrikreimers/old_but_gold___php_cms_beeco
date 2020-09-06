<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output method="html"/>

<xsl:include href="ground.xsl"/>
<xsl:include href="default.xsl"/>

<xsl:template match="group">

    <table border="0">
      <xsl:apply-templates/>
    </table>

</xsl:template>

<xsl:template match="entry">

    <tr>
      <xsl:apply-templates/>
    </tr>

</xsl:template>

<xsl:template match="id|desc">

    <td width="100">
      <xsl:apply-templates/>
    </td>

</xsl:template>

<xsl:template match="title">

    <td width="100">
      <b><xsl:apply-templates/></b>
    </td>

</xsl:template>

<xsl:template match="img">

    <td width="100">
      <img>
	<xsl:attribute name="src">
	  <xsl:apply-templates/>
	</xsl:attribute>
      </img>
    </td>

</xsl:template>


</xsl:stylesheet>