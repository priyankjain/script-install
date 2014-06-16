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
 loginCheck(); ?><?php

if($act=='remove')

{

$sql=mysql_query("SELECT * FROM reads WHERE fnum=$fnum");

$arr=mysql_fetch_array($sql);

extract($arr);

mysql_query("DELETE FROM reads WHERE fnum=$fnum") or die(mysql_error());

echo"<b>TASK $fn HAS BEEN DELETED SUCCESSFULLY</b>";

exit;

}

$sql=mysql_query("SELECT * FROM reads WHERE fnum=$fnum");

$arr=mysql_fetch_array($sql);

extract($arr);

?>

<div align="center"><b>E-mail Campaign # 

  <?php echo  $fnum?>

  </b><br>

</div>

<table width="100%" border="0" cellspacing="0" background="../images/fon.gif.gif">

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">#</font></b></td>

<td width="83%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $fnum ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Size</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $fsize ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">URL</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $furl ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Reads</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $freads ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Subject</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $fsubject ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5" height="21"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Mail content</font></b></td>

<td width="83%" height="21"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $ftext ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $flog ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $fpass ?>

      </font></td>

</tr>

  

<tr> 

    

<td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">1 

      read price</font></b></td>

<td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      

<?php echo  $fprise ?>

      </font></td>

</tr>

  

<tr> 

    

<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font>

      

<form name="form1" method="post" action="">

        

<input type="hidden" name="act" value="remove">

        

<input type="hidden" name="fnum" value="<?php echo  $fnum; ?>">

        

<div align="center">

          

<input type="submit" name="Submit" value="Remove this campaign">

        </div>

</form>

<font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>

</tr>

</table>

<table width="95%"  border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td>Username</td>
    <td>IP Address </td>
  </tr>
  <?php
$sql = mysql_query("SELECT * FROM mailreads WHERE fmailnum='$fnum'");
$rows = mysql_num_rows($sql);
for($i = 0; $i<$rows; $i++) {
mysql_data_seek($sql, $i);
$arr = mysql_fetch_array($sql);
extract($arr);
	?>
	<tr>
		<td><?php echo $fourid; ?></td>
		<td><?php echo $fip; ?></td>
	</tr>
	<?php
}
?>
</table>
<CENTER><?php echo "<BR>There have been $i reads for this email campaign."; ?></CENTER>
