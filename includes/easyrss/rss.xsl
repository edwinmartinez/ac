<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="rss/channel">
<html>
<head>
	<title><xsl:value-of select="title"/></title>
</head>
<body>
	<div style="margin: 20px">
		<a href="{link}" style="font-family: sans; font-size: 24px; color: #399; margin: 20px"><xsl:value-of select="description"/></a>
	</div>
	<xsl:for-each select="item">
		<div>
			<a href="{link}" style="font-family: sans; font-size: 16px; color: #39c;"><xsl:value-of select="title"/></a><xsl:if test="pubDate"><span style="font-family: sans; font-size: 12px; color: #000;"> (posted on <xsl:value-of select="pubDate"/>)</span></xsl:if>
		</div>
		<div style="font-family: sans; font-size: 12px; color: #000; border-left: 1px dashed #399; border-bottom: 1px dashed #399; margin: 10px; padding: 10px"> <xsl:value-of select="description" disable-output-escaping="yes" /></div>
	</xsl:for-each>
</body>
</html>
</xsl:template>
</xsl:stylesheet>