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
 loginCheck(); ?><?
if($act=='remove')

{

mysql_query("DELETE FROM signups WHERE fnum=$fn") or die(mysql_error());

echo"<b>TASK $fn HAS BEEN DELETED SUCCESSFULLY</b>";

exit;

}

$sql=mysql_query("SELECT * FROM signups WHERE fnum=$fnum");

$arr=mysql_fetch_array($sql);

extract($arr);

?>

<div align="center"><b>Sign-up Campaign # 

  <?php echo  $fnum?>

  </b><br>

</div>

<table width="100%" border="0" cellspacing="0" background="../images/fon.gif.gif">

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Advertiser's 

      e-mail</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fmail ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Advertiser's 

      name </font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fname ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site 

      name</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fsitename ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site 

      description</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fnote ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Campaign 

      size </font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fsize ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">URL</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $furl ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $flogin ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fpassword ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Start 

      date</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fstartdate ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">End 

      date</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fenddate ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">1 

      sign-up price</font></b></td>

    <td width="76%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $fprise ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Secret 

      question</font></b></td>

    <td width="76%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $squest ?>

      </font></td>

  </tr>

  <tr> 

    <td width="24%" bgcolor="f5f5f5"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Secret 

      answer</font></b></td>

    <td width="76%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 

      <?php echo  $sansw ?>

      </font></td>

  </tr>

  <tr> 

    <td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font> 

      <form name="form1" method="post" action="">

        <input type="hidden" name="act" value="remove">

        <input type="hidden" name="fn" value="<?php echo  $fnum; ?>">

        <div align="center"> 

          <input type="submit" name="Submit" value="Remove this campaign">

        </div>

      </form>

      <font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>

  </tr>

</table>

