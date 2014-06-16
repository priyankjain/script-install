<?php
echo $pageHeader;
$tags = buildPageTags();
$tags['{PAGE_NAME}'] = __('Frequently Asked Questions');

?>
<link rel="stylesheet" href="style.css" type="text/css">
 <style type="text/css">
<!--
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
-->
 </style>
 <h1><?php echo __('Frequently Asked Questions');?></h1>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="box">
  <tr>
    <td>
                  <?php echo displayContent(getValue("select value from design where name='faq'"),$tags); ?>
</td>
  </tr>
</table>
<?php echo $pageFooter; ?>