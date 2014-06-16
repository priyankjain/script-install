<?php echo $pageHeader; ?>
<h2><?php echo __('Promotional Links and Banners'); ?></h2>
<p>
<table width="99%" border="0" class="box" cellpadding="0" cellspacing="0">
  <tr> 
    <td>      <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
        <tr>
          <td colspan="3" class="headerStyle"><?php echo __('PROMOTIONAL LINKS FOR REFERRALS'); ?> </td>
          </tr>
      </table></td>
  </tr>
  <tr> 
    <td>        <p><strong><?php echo __('Home Page Link'); ?></strong><br>
          <?php
		  
		  $setupinfo['ptrurl'] = prepURL($setupinfo['ptrurl']);
		  
		  echo '<input type="text" name="userRefURL" value="';
		  echo $setupinfo['ptrurl']."/index.php?refer=".$_SESSION['login'].'" readonly="readonly" size="65">';
		  ?>
          <br />
            <br />
            <strong><?php echo __('Link Directly to the registration form'); ?></strong><br />
            <?php
		  echo '<input type="text" name="userRefURL" value="'; 
		  echo $setupinfo['ptrurl']."/index.php?refer=".$_SESSION['login'].'&tp=signup" readonly="readonly" size="65">';
		  ?>
          <br />
            <br />
            <strong><?php echo __('Link Directly to the view ad\'s page'); ?></strong><br />
            <?php
		  echo '<input type="text" name="userRefURL" value="'; 
		  echo $setupinfo['ptrurl']."/index.php?refer=".$_SESSION['login'].'&tp=viewAds" readonly="readonly" size="65">';
		  ?>
        </p></td>
  </tr>
  <tr> 
    <td>  
        <p>&nbsp;</p>        <p><b><?php echo __('Banners'); ?></b></p></td>
  </tr>
  <?php
  $bannersShown = 0;
  $banners = array();
  $banners[] = 'banner1.gif';
  $banners[] = 'banner2.gif';
  $banners[] = 'banner3.gif';
  $banners[] = 'fbanner1.gif';
  $banners[] = 'fbanner2.gif';
  $banners[] = 'fbanner3.gif';
  
  foreach($banners as $k => $v) { 
	  if(is_file("banners/".$v)) {
	  	if(substr($v,0,1)=='f') {
			$width = '180';
			$height = '100';
		} else {
			$width = '468';
			$height = '60';
		}
	  	?>
          <tr> 
            <td> (<?php echo $width.'x'.$height; ?>)<br>                <img src="banners/<?php echo $v; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></td>
        </tr>
          <tr> 
            <td> <div style="padding: 10px 10px 10px 10px; border: thin #FF6600; background-color:#FFFFCC;">&lt;a href=<?php echo $setupinfo['ptrurl'];?>/index.php?refer=<?php echo $_SESSION['login']; ?> target=blank&gt;&lt;img src=<?php echo $setupinfo['ptrurl']; ?>/banners/<?php echo $v; ?>&gt;&lt;/a&gt;</div><br />
<?php echo __('Image URL'); ?>: <?php echo $setupinfo['ptrurl']; ?>/banners/<?php echo $v; ?><br />
<?php echo __('Ref Link'); ?>: <?php echo $setupinfo['ptrurl'];?>/index.php?refer=<?php echo $_SESSION['login']; ?><br />
<br />
</td>
        </tr>
          <?php
	  	$bannersShown++;
	  } //END FILE CHECK
  } //END FOR LOOP
  ?>
</table>
</p><?php echo $pageFooter; ?>