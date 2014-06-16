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
if($act=='remove') {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
	$sql=mysql_query("SELECT * FROM tasks WHERE fn=".quote_smart($fn)."");
	$arr=mysql_fetch_array($sql);
	extract($arr);
	//mysql_query("DROP TABLE task$fn")or die(mysql_error());
	mysql_query("DELETE FROM taskactivity WHERE task=".quote_smart($fn)."");
	mysql_query("DELETE FROM tasks WHERE fn=".quote_smart($fn)."") or die(mysql_error());
	echo"<b>TASK $fn HAS BEEN DELETED SUCCESSFULLY</b>";
}
//	exit;
}

$sql=mysql_query("SELECT * FROM tasks WHERE fn=".quote_smart($fn)."");
$arr=mysql_fetch_array($sql);
extract($arr);

?>

<div align="center"><b>Visit Campaign # 

  <?php echo  $fn?>

  </b><br>

</div>

<table width="100%" border="0" cellspacing="0" background="../images/fon.gif.gif">

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Code</font></b></td>

    <td width="83%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fn ?>

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

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site 

      name</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fsitename ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site 

      description</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fnote ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delivered 

      visits</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fvisits ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Name</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fname ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5" height="21"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 

      name</font></b></td>

    <td width="83%" height="21"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $flname ?>

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

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Start 

      date</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $startdate ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">End 

      date</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $enddate ?>

      </font></td>

  </tr>

  <tr> 

    <td width="17%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">1 

      visit price</font></b></td>

    <td width="83%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $prise ?>

      </font></td>

  </tr>

  <tr> 

    <td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font>

      <form name="form1" method="post" action="">

        <input type="hidden" name="act" value="remove">

        <input type="hidden" name="fn" value="<?php echo  $fn; ?>">

        <div align="center">

          <input type="submit" name="Submit" value="Remove this campaign">

        </div>

      </form>

      <font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>

  </tr>

</table>

