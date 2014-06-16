<?php
//
// COPYRIGHT 2010 PTCSHOP.COM - WRITTEN BY ZACK MYERS ocnod1234@yahoo.com
// RESALE OF THIS WEB SCRIPT IS STRICTLY FORBIDDEN
// I DID NOT ENCRYPT IT FOR YOUR PERSONAL GAIN,
// SO PLEASE DON'T SELL OR GIVE AWAY MY WORK :-)
//
// THIS FILE IS ONLY FOR ADVANCED USERS TO MODIFY
//
// FOR BASIC CONFIGURATION, PLEASE MODIFY include/cfg.php
//
//
// --------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------
// unless you know what your doing :)
//
 loginCheck(); ?><style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
	color: #666666;
	font-family: Arial, Helvetica, sans-serif;
}
.style2 {font-family: Arial, Helvetica, sans-serif}
.style3 {font-size: 12px}
.style4 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<?php
$webQuestions[]['question'] = 'What kind of advertising can people purchase?';
$webQuestions[]['answer'] 	= 'There is a variety of advertisements members can purchase and have placed on the website. <BR>Banners, Featured Banners, Featured Ads, Survey\'s, Website Click thru\'s, Paid to read email\'s, and Featured Links.';
$webQuestions[]['question'] = 'What payment methods do you offer?';
$webQuestions[]['answer'] 	= 'A Variety of payment methods can be used by members to purchase advertisements. The current ones are paypal, stormpay, e-gold, account funds and moneybookers.<BR>To update this, you will need to click on Settings, and then click on Payment / Payout options.<BR><BR>';
//$webQuestions['question'][] = '';
//$webQuestions['answer'][] 	= '';
$memberQuestions[]['question'] = 'agfds';
$memberQuestions[]['answer'] 	= '546';
$adminQuestions[]['question'] = 'jfghk,';
$adminQuestions[]['answer'] 	= '87o565rk6k';
?>
<table width="600" border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td><p align="center" class="style1">Frequently Asked Questions</p>
      <p align="left"><span class="style2"><span class="style3"><strong>Website Questions</strong><br>
	<?php
	
	foreach($webQuestions as $k => $v) {
	?>  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &bull; <a href="#" onClick="showDiv('<?php echo $k; ?>')"><?php echo $v['question']; ?></a><br>
<div style="display: none;" id="<?php echo $k; ?>"><?php echo $v['answer']; ?><BR></div>
<?php
}
?>

      </span></span></p>
      <p align="left" class="style4"><strong>Members Questions</strong><br>
	<?php
	
	foreach($memberQuestions as $k => $v) {
	?>  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &bull; <a href="#" onClick="showDiv('<?php echo $k; ?>')"><?php echo $v['question']; ?></a><br>
<div style="display: none;" id="<?php echo $k; ?>"><?php echo $v['answer']; ?><BR></div>
<?php
}
?>
      </p>
      <p class="style4"><strong>Administrative Questions</strong><br>
<?php
	foreach($adminQuestions as $k => $v) {
	?>  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &bull; <a href="#" onClick="showDiv('<?php echo $k; ?>')"><?php echo $v['question']; ?></a><br>
<div style="display: none;" id="<?php echo $k; ?>"><?php echo $v['answer']; ?><BR></div>
<?php
}
?>
    </p></td>
  </tr>
</table>
<p align="center" class="style1">&nbsp;</p>
