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



if(!$count)



$count=100;


if($_REQUEST['massSuspend'] == 'yes') {
	echo "Mass suspending ".count($_REQUEST['userID'])." records.<BR>";
}	

if($_REQUEST['massEmail'] == 'yes') {
	echo "Mass emailing ".count($_REQUEST['userID'])." records.<BR>";
}	
?>

<h2>Members database <a href="index.php?tp=exportMembers"><span style="font-size:12px; font-weight: normal;">(Export Members List)</span></a></h2>
<hr />

<table width="100%" border="0" cellpadding="5" cellspacing="2" bgcolor="#FFFFFF">

  <tr bgcolor="#ECECEC">

    <td colspan="6">

      <div align="center"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#003333">Search options:</font></b></div></td>

  </tr>

  <tr valign="top" bgcolor="#ECECEC">

    <td>

      <form name="form1" method="post" action="index.php"><input type="hidden" name="tp" value="members">
Username: <br>
            
        <input type="text" name="username" size="15">
            
            <input type="hidden" name="tp" value="members">
               
            <input type="hidden" name="seek" value="id">
                
            <input type="submit" name="Submit5" value="Seek">
                  
    </form></td>

	<td>

      <form name="form1" method="post" action="index.php">
        <p>
          <input type="hidden" name="tp" value="members">
          Reg. date: <br>
            
          from
            
          <input type="text" name="date1" size="5" value="<?php echo date("Y:m:d h:m:s"); ?>">
            
          <br />
          to &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="text" name="date2" size="5" value="<?php echo date("Y:m:d h:m:s"); ?>">
              <br>
          yyyy-mm-dd<br />
       
          <input type="hidden" name="tp" value="members">
            
          <input type="hidden" name="seek" value="date">
            
          <input type="submit" name="Submit6" value="Seek">
              
           </p>
      </form></td>


	<td>

      <form name="form1" method="post" action="index.php">
        <p>
          <input type="hidden" name="tp" value="members">
          Last Activity: <br>
            
          from
            
          <input type="text" name="date1" size="5" value="<?php echo date("Y:m:d h:m:s"); ?>">
            
          <br />
          to &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="text" name="date2" size="5" value="<?php echo date("Y:m:d h:m:s"); ?>">
              <br>
          yyyy-mm-dd<br />
       
          <input type="hidden" name="tp" value="members">
            
          <input type="hidden" name="seek" value="lastActivity">
            
          <input type="submit" name="Submit6" value="Seek">
              
           </p>
      </form></td>

    <td>

      <form name="form1" method="post" action="index.php">
        <p>
          <input type="hidden" name="tp" value="members">Email:<br>
            
          <input name="mail" type="text" size="15">
        
          <input type="hidden" name="tp" value="members">
          
          <input type="hidden" name="seek" value="mail">
            
          <input type="submit" name="Submit" value="Seek">
              
          </p>
      </form></td>

    

    <td>

      <form name="form1" method="post" action="index.php">
        <p>
          <input type="hidden" name="tp" value="members">
          
          Referred By: <br>
            
            <input name="refer" type="text" size="15">
       
          <input type="hidden" name="tp" value="members">
          
          <input type="hidden" name="seek" value="refer">
             
          <input type="submit" name="Submit3" value="Seek">
          
          </p>
      </form></td>

    

	<td>

      <form name="form1" method="post" action="index.php">
        <p>
          <input type="hidden" name="tp" value="members">
          Country: <br>
            
          <select name="country">
              
              <?php

								$query = mysql_query("SELECT country FROM countries ORDER BY country ASC");

								$count = mysql_num_rows($query);

								for($i = 0;$i < $count;$i++) {

									mysql_data_seek($query, $i);

									$arr  = mysql_fetch_array($query);

									?>
              
              <option value="<?php echo $arr['country']; ?>"><?php echo $arr['country']; ?></option>
              
              <?php } ?>
          </select>
        
          <input type="hidden" name="tp" value="members">
          
          <input type="hidden" name="seek" value="country">
            
          <input type="submit" name="Submit2" value="Seek">
              
          </p>
      </form></td>

  </tr>

</table>

<br>
<form name="massSubmit" method="post" action="index.php">
<input type="hidden" name="tp" value="members" />
<input type="hidden" name="act" value="massSubmit" />
<div class="hastable_disabled"><table border="0" cellpadding="0" cellspacing="0" class="fullwidth">
<thead>
        <tr>
          <td>&nbsp;</td>
          <td>Reg. date</td>
          <td>Last Activity</td>
          <td>Username</td>
          <td>Name</td>
          <td>Referrer</td>
          <td>Balance</td>
          <td><div align="right">Options</div></td>
        </tr>
</thead><tbody>
        <?php

	if($seek=='date')$sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND regdate >= '$date1' AND regdate <= '$date2' ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else if($seek=='lastActivity')$sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND lastActivity >= '$date1' AND lastActivity <= '$date2' ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else if($seek=='mail') $sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND femail LIKE ".quote_smart("%".$mail."%")." ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else if($seek=='country') $sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND fcountry LIKE ".quote_smart("%".$country."%")." ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else if($seek=='refer') $sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND frefer LIKE ".quote_smart("%".$refer."%")." ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else if($seek=='id') $sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' AND username LIKE ".quote_smart("%".$username."%")." ORDER BY lastActivity DESC,fid DESC") or die(mysql_error());
	else $sql=mysql_query("SELECT * FROM users WHERE accountType = 'member' ORDER BY lastActivity DESC,fid DESC");

	$rows=mysql_num_rows($sql);
	if(!$start) $start=0;
	$end=$start+$count;
	if($rows<=$end) $end=$rows;

	for($i=$start;$i<$end;$i++) {
	mysql_data_seek($sql,$i);
	$arr=mysql_fetch_array($sql);
	extract($arr);

	if(!$frefer2) $frefer2='';
	?><tr onMouseOver="this.bgColor='#ECECEC'" onMouseout="this.bgColor='#FFFFFF'">
        <td>&nbsp;</td>
        <td><?php echo $regdate; ?></td>
        <td><?php echo $lastActivity; ?></td>
		<td><?php echo $username; 
		
		$membership = getUserMembershipDetails($username);
		if($membership['status'] == TRUE){
			echo '<BR>'.$membership['membershipName'].' (Ends: ';
			if($membership['lifetime'] != '1') echo $membership['endDate'].')'; else echo 'Never. Lifetime Membership.';
		}
		?></td>
        <td><?php echo $fname1; ?></td>
        <td><?php echo $frefer; ?></td>
        <td><?php echo $setupinfo['currency']; ?><?php echo totalEarnings($username); ?></td>
        <td><div align="right"><a href="index.php?tp=userview&uid=<?php echo $username; ?>" target=_self>view</a></div></td>
        </tr><?php
	}



	?><tr><td align=center colspan=11><?php



	if($start!=0) {$start=$start-$count; $fl=1; echo"<a href='index.php?tp=members&start=$start'>Previous $count</a> | ";}



	if($end!=$rows) {if(!$fl) $start=$start+$count; else $start=$start+$count+$count; echo"<a href='index.php?tp=members&start=$start'>Next $count</a>";}

?>

	</td></tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
  <tr>

    <td colspan="12">TOTAL MEMBERS: <?php echo  $rows?></td>
  </tr>
</tbody>
</table>
  </div></form>