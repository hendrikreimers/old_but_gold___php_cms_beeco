<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:template match="/">

<html>
<head>
<title>MiniCMS-SE - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link rel="stylesheet" href="{$path}/styles/admin.css"/>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="726" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr style="line-height:0px;"> 
    <td width="401"><img src="{$path}/gfx/admin/header1.jpg" width="401" height="182"/></td>
    <td width="325"><img src="{$path}/gfx/admin/header2.jpg" width="325" height="182"/></td>
  </tr>
  <tr> 
    <td height="21" colspan="2" align="left" valign="top" bgcolor="#F0F0F0"> 
      <table width="505" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>&#160;</td>
        </tr>
        <tr> 
          <td width="489" height="30" align="left" valign="middle" background="{$path}/gfx/admin/titlebar.gif" style="background-repeat: no-repeat; background-position: 0 0"><font color="#FFFFFF" size="3" face="Verdana, Arial, Helvetica, sans-serif">&#160;&#160;&#160;<strong><xsl:value-of select="$title"/></strong></font></td>
        </tr>
        <tr> 
          <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&#160;&#160;&#160;&#160;&#160;<strong>(<xsl:value-of select="$name"/>)</strong></font></div></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td height="768" colspan="2" align="left" valign="top" bgcolor="#F0F0F0"> 
      <p>&#160;</p>
      <table width="700" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="26">&#160;</td>
          <td width="668" align="left" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><xsl:apply-templates/></font></td>
        </tr>
      </table>
      <p>&#160;</p></td>
  </tr>
</table>
</body>
</html>

</xsl:template>
</xsl:stylesheet>