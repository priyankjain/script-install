</div></div></div>
<div class="body" style="background-image: url('<?php echo $templateFolder; ?>images/bg.gif');">
                                    <div class="body_resize">
<div class="clr"></div>
                                      <div class="left" style="height: 200px;">
                                        <h2><?php echo __('Advertising Special'); ?></h2>
                                        <?php
						   $sql=mysql_query("SELECT fnum, pack_price, pack_name FROM packages WHERE pack_price>0 AND packSpecial>0 ORDER BY fnum DESC LIMIT 1");
						   $count = mysql_num_rows($sql);
						   if($count > 0) {
						   for($i = 1; $i  < $count+1;$i++) {
						   		
									mysql_data_seek($sql, $i-1);
									extract(mysql_fetch_array($sql));
									if(!visibleAd($fnum)) {
										$limit = 500;
										$fnumList = $fnum;
										
										while(!visibleAd($fnum)) {
											$fnum = getValue("SELECT fnum FROM packages WHERE pack_price>0 AND packSpecial>0 AND fnum NOT IN (".$fnumList.") ORDER BY fnum DESC LIMIT 1");
											$fnumList .= ','.$fnum;
										}
										
										
										$sql = mysql_query("SELECT fnum, pack_price, pack_name FROM packages WHERE pack_price>0 AND packSpecial>0 AND fnum NOT IN (".$fnumList.") ORDER BY fnum DESC LIMIT 1");
										$count = mysql_num_rows($sql);
										mysql_data_seek($sql, $i-1);
										extract(mysql_fetch_array($sql));
										
									}
									$query = mysql_query("SELECT b.pack_name, b.pack_price FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($fnum)."");
									$c = mysql_num_rows($query);
										if($c > 0) {  ?>
            <table width="288" height="107" border="0" cellpadding="5" cellspacing="0">
<tr valign="top" <?php if($_REQUEST['id'] == $fnum) { echo "bgcolor=\"#FFFFCC\""; } else { ?>bgcolor="#FFFFFF"<?php } ?>>
                                    <td height="101" bgcolor="#FFFFFF">
                                      <form name="form1" method="post" action="index.php">
                                        <strong style="color:#006ba2;"> <?php echo $pack_name; ?> <?php echo $setupinfo['currency'].number_format($pack_price,2); ?></strong><br><span style="font-size: 10px"><span style="font-family: Arial, Helvetica, sans-serif">
                                        <?
												for($k=0; $k<$c; $k++){
													mysql_data_seek($query,$k);
													extract(mysql_fetch_array($query));
													if(isset($pack_name)) { ?>
&nbsp;&nbsp;&bull;&nbsp; <?php echo $pack_name; ?> (<?php echo $setupinfo['currency'].number_format($pack_price, 2); ?> Value)</span></span><span style="font-size: 10px; font-family: Arial, Helvetica, sans-serif"><BR>
                  <?php }
												}
												?>
                  </span><br>
                                  <input type="hidden" name="packID" value="<?php echo $fnum; ?>">
                                  <input type="hidden" name="tp" value="advertise">
                                  <input type="hidden" name="td" value="ordernow">
                                  <a href="index.php?tp=advertise&act=viewInfo&adType=Specials&id=<?php echo $fnum; ?>"><img src="<?php echo $templateFolder; ?>images/more_info.gif" border="0" align="middle" /></a>&nbsp;&nbsp; 
                                  <input name="Submit" type="image" value="Buy Now" src="<?php echo $templateFolder; ?>images/order_now.gif" align="middle" style="margin-top: 5px;">
                                      </form></td>
                                  </tr>
                              </table>
            <?php
										} //if($c > 0) 
									} //for($i = 0; $i  < $count;$i++) {
								}//END IF COUNT > 0
						?></p>
                                        <div class="bg"></div>
                                        </div>
                                      <div class="left" style="height: 200px;">
                                        <h2><?php echo __('Featured Ads'); ?></h2>
                                        <?php 
										  $sql=mysql_query("SELECT fsize-fshows AS creditsLeft, flink, fname, fnum, fshows,description FROM featuredads WHERE fsize-fshows > 0 ORDER BY RAND() LIMIT 1");

		$rows=mysql_num_rows($sql);

		if($rows > 0) {

			@$arr=mysql_fetch_array($sql);

			@extract($arr);

			echo "<a href=\"index.php?tp=out&id=$fnum&t=fa\" target=\"_blank\">".'<strong style="color:#006ba2;">'.$fname."</strong></a><br>".$description."";

			$shows=$fshows+1;

			mysql_query("UPDATE featuredads SET fshows=".quote_smart($shows)." WHERE fnum=".quote_smart($fnum)."");

		} else {

			echo "<p>".'<strong style="color:#006ba2;">'.__('Put your ad here!').'</strong><BR><a href="index.php?tp=advertise">'.__('Advertise Here').'</a></p>';

		}
										  
										   ?>
                                        <div class="bg"></div>
                                        <?php 
										  $sql=mysql_query("SELECT fsize-fshows AS creditsLeft, flink, fname, fnum, fshows,description FROM featuredads WHERE fsize-fshows > 0 AND fnum != ".quote_smart($fnum)." ORDER BY RAND() LIMIT 1");

		$rows=mysql_num_rows($sql);

		if($rows > 0) {

			@$arr=mysql_fetch_array($sql);

			@extract($arr);

			echo "<a href=\"index.php?tp=out&id=$fnum&t=fa\" target=\"_blank\">".'<strong style="color:#006ba2;">'.$fname."</strong></a><br>".$description."";

			$shows=$fshows+1;

			mysql_query("UPDATE featuredads SET fshows=".quote_smart($shows)." WHERE fnum=".quote_smart($fnum)."");

		} else {

			echo "<p>".'<strong style="color:#006ba2;">Put your ad here!</strong><BR><a href="index.php?tp=advertise">'.__('Advertise Here').'</a></p>';

		}
										  
										   ?>
                                        </div>
                                      <div class="left" style="height: 200px;">
                                        <h2><?php echo __('Featured Banner'); ?></h2>
                                        <?php displayFBanner(); ?>
                                      
                                        <p>&nbsp;</p>  <div class="bg"></div><?php if($setupinfo['poweredby'] == '1') { ?>This website is powered by <a href="http://www.ptcshop.com/" target="_blank">PTCShop.com</a><?php } ?>
                                      </div>
                                      <div class="clr"></div>
                                    </div>
                                  </div>
                                </div>

<div class="footer" style="background-image:url('<?php echo $templateFolder; ?>/images/footerBG.gif'); background-position:top; background-repeat:repeat-x;"> <?php
if($dispContentBanner == 1) {
	echo "<div style=\"width: 100%\" align=\"center\"><BR>";
	displayBanner();
	echo "<BR><BR></div>";
}
?> 
  <div class="footer_resize">
    <span id="myriad"><?php echo __('Copyright'); ?> &copy;<font size="1"><?php echo date("Y"); ?></font> <?php echo $ptrurl; ?> - <?php echo __('All Rights Reserved'); ?></span><br />
    <span id="myriad"><a href="index.php"><?php echo __('Home'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=signup"><?php echo __('Join Now'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=member"><?php echo __('Members Login'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=advertise"><?php echo __('Advertise Here'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=terms"><?php echo __('Terms and Conditions'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=faq"><?php echo __('F.A.Q.'); ?></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php?tp=contacts"><?php echo __('Contact Us'); ?></a></span></p>
   <?php if($setupinfo['poweredby'] == '1') { ?> <p class="rightt">Powered by <a href="http://www.ptcshop.com/" target="_blank">ptcshop.com</a></p><?php } ?>
    <div class="clr"></div>
  </div>
</div>
</body>
</html>