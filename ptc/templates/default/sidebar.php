<?php
$tp = $_REQUEST['tp'];
if($tp != '' && $tp != 'home') { ?>
  <div <?php if($sideBarLeft == 0) echo 'class="right"'; else echo 'class="left"'; ?> style="margin: 0;">
        
  <style type="text/css">
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
.font12pxSize {font-size: 12px}
.fontArialType {font-family: Arial, Helvetica, sans-serif}
.font12pxArialType {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.style1 {color: #000000}
</style><?php

//DISPLAY USER CUSTOM CONTENT
echo displayContent(getValue("SELECT value FROM design WHERE `name` = 'homeright'"));

if(isset($_SESSION['login']) && $_SESSION['login'] != '') { //IF (session_is_registered("login")
	  extract(getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login']).""));
	  ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('Members Area'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="174" bgcolor="#FFFFFF"><div align="center">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="arial12pxReg"><div align="left" class="font12pxSize fontArialType" style="width: 100%; height: 200px; overflow: auto;"> <?php echo "$fname1 $fname2 "?><br />
                              <?php //if($gold=='yes') echo"<STRONG><FONT COLOR=GOLD> GOLD MEMBER</FONT></STRONG>";
				  $sql = mysql_query("SELECT membershipName, DATE(endDate) AS endDate, lifetime FROM memberships WHERE active = '1' AND username = ".quote_smart($username)." ORDER BY id ASC");
				  $count = mysql_num_rows($sql);
				  if($count > 0) {
				  	$membership = mysql_fetch_array($sql);
					echo $membership['membershipName'];
					if($membership['lifetime'] == '1') {
						echo "<BR>Lifetime Membership";
					} else {
						echo "<BR>Ends: ".$membership['endDate']."";
					}
				  }
				  
				   ?>
                              <table width="100%"  border="0" cellspacing="0" cellpadding="2">
                                <tr class="font12pxSize">
                                  <td><?php echo ucfirst($setupinfo['pointsName']); ?> <?php echo __('Balance'); ?></td>
                                  <td><div align="">
                                      <?php $points = userPoints($_SESSION['login']); echo $points;
									   ?>
                                  </div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo $setupinfo['currencyName']; ?> <?php echo __('Balance'); ?></td>
                                  <td><div align=""><?php echo $setupinfo['currency']; ?>
                                        <?php 
				  /*$total=$ftmclicks+$ftmreads+$ftmregs+$subonus;
	  $total1=$total;
	  echo number_format($total, 4);
	  
	  */
	  $earnings = totalEarnings($_SESSION['login']);
	  echo number_format($earnings,4,".",",");
	  if($points > 0) { echo '<br /><a href="index.php?tp=converter">'.__('Converter').'</a>'; }
	  if($earnings > number_format($minpay,2)) { echo "<BR><a href=\"index.php?tp=redemption\">".__('Withdraw')."</a>"; }
	  ?>
                                  </div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td>&nbsp;</td>
                                  <td><div align=""></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><strong><?php echo __('Credits'); ?></strong> </td>
                                  <td><div align=""></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('PTC'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'links'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Banner'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login']); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Featured Banner'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'fbanner'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Featured Ad'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'fad'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Paid E-Mail'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'email'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Guaranteed Sign-Up'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'signup'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Featured Link'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'flinks'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Paid Survey'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'survey'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><?php echo __('Paid To Read Ads'); ?></td>
                                  <td><div align=""><?php echo totalBannerCredits($_SESSION['login'],'ptrad'); ?></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td>&nbsp;</td>
                                  <td><div align=""></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><span class="font12pxArialType"><?php echo __('Referral Link Hits'); ?></span></td>
                                  <td><div align=""><span class="font12pxArialType"><?php echo getCount("SELECT SUM(visits) FROM referrals WHERE username = ".quote_smart($_SESSION['login']).""); ?></span></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td>&nbsp;</td>
                                  <td><div align=""></div></td>
                                </tr>
                                <tr class="font12pxSize">
                                  <td><span class="fontArialType font12pxSize"><strong><?php echo __('Downline Overview'); ?></strong></span></td>
                                  <td><div align=""></div></td>
                                </tr>
                                <?php
					for($i = 0;$i < $levels;$i++) {
					$level = $i+1;
					if($i == 0) { $ref = ''; } else { $ref = $level; }
					?>
                                <tr class="font12pxSize">
                                  <td><span class="fontArialType font12pxSize">Level <?php echo $level; ?> <?php echo __('Downline'); ?></span></td>
                                  <td><div align=""><span class="fontArialType font12pxSize"><?php echo getValue("SELECT COUNT(fid) FROM users WHERE frefer".$ref." = ".quote_smart($_SESSION['login']).""); ?> </span></div></td>
                                </tr>
                                <?php
				   }
				   ?>
                              </table>
                        </div></td>
                      </tr>
                </table>
                </div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <?php
	  }
	  ?><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('FEATURED LINKS'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="44" bgcolor="#FFFFFF"><div align="center">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td><div align="center">
                            <?php
displayFLinks(10);

?>
                        </div></td>
                      </tr>
                    </table>
                    <br>
                </div></td>
            </tr>
          </table></td>
        </tr>
      </table> <?php 

 if ($displayfad == 'yes') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('FEATURED AD'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="44" bgcolor="#FFFFFF"><div align="center" style="
	width:100%;
	overflow: auto;
	font-size: 12px;">
                  <?php
displayFAd();

?>
              </div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <?php }

 if ($displaycontest == 'yes') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('REFERRAL CONTEST'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="19" bgcolor="#FFFFFF"><div align="center">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="arial12pxReg">
                      <tr>
                        <td><div align="center" class="font12pxSize"><?php echo __('Place'); ?></div></td>
                        <td><div align="center" class="font12pxSize"><?php echo __('Username'); ?></div></td>
                        <td><div align="center" class="font12pxSize"><?php echo __('Referrals'); ?></div></td>
                      </tr>
                      <?
		
		$sql=mysql_query("SELECT refcount, username FROM users ORDER BY refcount DESC LIMIT 0, 10");
		$rows=mysql_num_rows($sql);
		if($rows<10) $fin=$rows; else $fin=5;
		for($i=0;$i<$fin;$i++)
		{
		mysql_data_seek($sql,$i);
		extract(mysql_fetch_array($sql));
		$a=$i+1;
		//if(is_int($i/2))echo"<tr bgcolor=f5f5f5>"; else echo"<tr>";
		echo "<tr>";
		echo"<td><div align=\"center\"><b><font size=\"1\">$a</div></font></b></td><td><div align=\"center\"><b><font size=\"1\">$username</div></font></b></td><td><div align=\"center\"><b><font size=\"1\">$refcount ".__('referrals')."</div></font></b></td></tr>
		
		";
		
		}?>
                </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <?php
} $displaytopclickers = 'yes';
	if ($displaytopearners == 'yes' && $_SESSION['login'] == '') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('Top Earners This Month'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="22" bgcolor="#FFFFFF"><div align="center">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2" class="arial12pxReg">
                    <?
		
		$sql=mysql_query("SELECT COUNT(fnum) AS clicks, username, fdate FROM taskactivity GROUP BY username HAVING clicks > 0 AND fdate >= ".quote_smart(date("Y-m-")."01")." ORDER BY clicks DESC LIMIT 0, 10") or die(mysql_error());
		$rows=mysql_num_rows($sql);
		if($rows<10) $fin=$rows; else $fin=10;
		for($i=0;$i<$fin;$i++)
		{
		mysql_data_seek($sql,$i);
		extract(mysql_fetch_array($sql));
		$a=$i+1;
		//if(is_int($i/2))echo"<tr bgcolor=f5f5f5>"; else echo"<tr>";
		?>
                    <tr>
                      <td><div align="left"><b><?php echo $username." ( ".$clicks." )"; ?></b></div></td>
                    </tr>
                    <?php
		}?>
                  </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <?php
}
	  if ($displaytop5 == 'yes' && $_SESSION['login'] == '') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('Top Referrers This Month'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="22" bgcolor="#FFFFFF"><div align="center">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2" class="arial12pxReg">
                    <?php
		
		$sql=mysql_query("SELECT (
SELECT COUNT( u.fid )
FROM users u
WHERE u.frefer = users.username AND u.regdate >= ".quote_smart(date("Y-m-")."01")."
) AS totalUsers, username, regdate
FROM users
GROUP BY username
HAVING totalUsers >0");
		$rows=mysql_num_rows($sql);
		if($rows<10) $fin=$rows; else $fin=10;
		if($fin == 0) {
		?><tr>
                      <td><div align="left"><?php echo __('There are currently no runner up\'s in the top referrer\'s for this month!'); ?></div></td>
                    </tr><?php
		} else {
		for($i=0;$i<$fin;$i++)
		{
		mysql_data_seek($sql,$i);
		$arr = mysql_fetch_array($sql);
		$a=$i+1;
		//if(is_int($i/2))echo"<tr bgcolor=f5f5f5>"; else echo"<tr>";
		?>
                    <tr>
                      <td><div align="left"><b><?php echo $arr['username']." ( ".$arr['totalUsers']." )"; ?></b></div></td>
                    </tr>
                    <?php
		}
		}?>
                  </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
      </table>      
      <br>
      <?php
}
        ?>
      <?php if ($displayfbanner == 'yes') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('FEATURED BANNER'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="44" bgcolor="#FFFFFF"><div align="center">
                  <?php
displayFBanner();

?>
              </div></td>
            </tr>
          </table></td>
        </tr>
      </table>      
      <br>
      <?php } ?>
      <?php
	  if ($displaystats == 'yes') { ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
            <tr>
              <td colspan="3" class=""><h2><?php echo __('WEBSITE STATS'); ?></h2></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
            <tr>
              <td height="44" bgcolor="#FFFFFF"><div align="center">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="50%"><?php echo __('Total Members'); ?> </td>
                        <td width="50 %"><?php
echo getValue("SELECT COUNT(username) FROM users");

?></td>
                      </tr>
                      <tr>
                        <td><?php echo __('Members Today'); ?> </td>
                        <td><?php
$sql1=mysql_query("SELECT COUNT(username) FROM users WHERE regdate = ".quote_smart(date("Y-m-d"))."");
$ar = mysql_fetch_array($sql1);
echo $ar['COUNT(username)'];
?></td>
                      </tr>
                      <tr>
                        <td><?php echo __('Members online'); ?> </td>
                        <td><?php
					$timeoutseconds 	= 300;                                                                                                 
$timestamp=time();
$timeout=$timestamp-$timeoutseconds;
mysql_query("INSERT INTO useronline VALUES (".quote_smart($timestamp).",".quote_smart($_SERVER['REMOTE_ADDR']).",".quote_smart($_SERVER['PHP_SELF']).")") or die(mysql_error());
mysql_query("DELETE FROM useronline WHERE `usertime`<".quote_smart($timeout)."") or die(mysql_error());
$result=mysql_query("SELECT DISTINCT ip FROM useronline WHERE file=".quote_smart($_SERVER['PHP_SELF'])."") or die(mysql_error());
$user = mysql_num_rows($result);

echo $user;
?></td>
                      </tr>
                      <tr>
                        <td><?php echo __('Total paid out'); ?></td>
                        <td><?php
					$sqe = mysql_query("SELECT SUM(famount) AS totalAmount FROM payrequest WHERE paidOut = '1'");
					$rowse = mysql_num_rows($sqe);
					$a = mysql_fetch_array($sqe);
					$total = $a['totalAmount'];
					
					echo "$".number_format($total,2)."";
					?></td>
                      </tr>
                      <tr>
                        <td><?php echo __('Hits Today'); ?> </td>
                        <td><?php echo getValue("SELECT SUM(visits) FROM websitevisits WHERE visitDate = DATE(NOW())"); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo __('Hits Overall'); ?> </td>
                        <td><?php echo getValue("SELECT SUM(visits) FROM websitevisits"); ?></td>
                      </tr>
                    </table>
                </div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <br>      
      <?php } ?>
         
      <div class="bg"></div>
</div>
     <div class="clr"></div>
    </div>
  </div>
  <?php
  
  }
  
  ?>