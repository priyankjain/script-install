<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo "$ptrname | Get paid to read e-mails, click links, signups, refer other!"; ?></title>
<META NAME="title" CONTENT="<?php echo $ptrname; ?> - Get paid to click links, read e-mails, signup, refer other!"> 
<META NAME="description" CONTENT="<?php echo $ptrname; ?> - Get paid to visit websites, take surveys, signup to programs, and read emails"> 
<META NAME="keywords" CONTENT="<?php echo $ptrname; ?>, PTC, Paid to Click, GPT, Get Paid To, PTR, Paid to Read, PTSU, Paid to Signup, Guaranteed Signups, Paid to Read Emails, Paid to Take Surveys, Unique Website Visitors, Website Traffic, Online Advertising, Bux">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
if($setupinfo['siteStyle']=='orange') $styles = 'style_orange.css';
else if($setupinfo['siteStyle']=='green') $styles = 'style_green.css';
else $styles = 'style.css';
	?><link href="<?php echo $templateFolder.$styles; ?>" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
<script type="text/javascript" src="<?php echo $templateFolder; ?>js/unitpngfix.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php echo $templateFolder; ?>js/jquery.js"></script>

<script type="text/javascript" charset="utf-8">
// <![CDATA[
$(document).ready(function(){	
	$("#slider").easySlider({
		controlsBefore:	'<p id="controls">',
		controlsAfter:	'</p>',
		auto: true, 
		continuous: true
	});	
});
// ]]>
</script>
<style type="text/css">
.gallery { width:978px; height:299px; margin:0 auto; padding:0; }
#slider { margin:0; padding:0; list-style:none; }
#slider ul,
#slider li { margin:0; padding:0; list-style:none; }
/* 
    define width and height of list item (slide)
    entire slider area will adjust according to the parameters provided here
*/
#slider li { width:978px; height:299px; overflow:hidden; }
p#controls { margin:0; padding:0; position:relative; }
#prevBtn { display:block; margin:0; overflow:hidden; width:23px; height:23px; position:absolute; left:3px; top:-155px; }
#nextBtn { display:block; margin:0; overflow:hidden; width:23px; height:23px; position:absolute; left: 950px; top:-155px; }
#prevBtn a { display:block; width:23px; height:23px; background:url(<?php echo $templateFolder; ?>images/l_arrow.gif) no-repeat 0 0; }
#nextBtn a { display:block; width:23px; height:23px; background:url(<?php echo $templateFolder; ?>images/r_arrow.gif) no-repeat 0 0; }
.fnt10pxGrey {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #999999;
}
<?php
if($_SESSION['lang'] != 'en') {
?>
.menu { width: 1040px; }
.menu ul { padding: 2px 2px 2px 2px; }
.menu ul li { }
.menu ul li a { font-size: 11px; padding:  }
<?php
}
?>
</style>
<?php
if($useDraconCaptcha) {
	?><!--[if IE]><link rel="stylesheet" type="text/css" href="/templates/default/css/Dracon_IE.css" /><!--<[endif]-->
    <!--[if !IE]><link rel="stylesheet" type="text/css" href="/templates/default/css/Dracon.css" /><!--<![endif]--><?php
}
?>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="block_header">
      <table width="947" border="0" align="center" cellpadding="2" cellspacing="1">
    <tr>
            <td width="947"><div align="right" class="fnt10pxGrey"><?php
    $totalMembers = getValue("SELECT COUNT(username) FROM users");
	$membersToday = getValue("SELECT COUNT(username) FROM users WHERE regdate = DATE(NOW())");
	$hitsToday = getValue("SELECT SUM(visits) FROM websitevisits WHERE visitDate = DATE(NOW())");
	$paidOut = getValue("SELECT SUM(famount) FROM payrequest WHERE paidOut = 1");
	
	?>
              <?php echo $setupinfo['currency']; ?><?php echo number_format($paidOut, 0, ".", ","); ?> <?php echo __('Total Paid Out'); ?>.&nbsp;&nbsp;&nbsp;        <?php echo number_format($totalMembers, 0, ".", ","); ?> <?php echo __('Total Members'); ?>. &nbsp;&nbsp;&nbsp;
        <?php echo number_format($membersToday,0,".",","); ?>         <?php echo __('Members Today'); ?>. &nbsp;&nbsp;&nbsp;
        <?php echo number_format($hitsToday, 0, ".", ","); ?> <?php echo __('Hits Today'); ?>.&nbsp;&nbsp;&nbsp;&nbsp;<?php
        $sql = mysql_query("SELECT * FROM languages WHERE enabled = '1'");
		$count = mysql_num_rows($sql);
		if($count > 0) {
			$languageSelection = array( 	"auto" => "automatic",
									"sq" => "albanian",
									"ar" => "arabic",
									"bg" => "bulgarian",
									"ca" => "catalan",
									"zh-CN" => "chinese",
									"hr" => "croatian",
									"cs" => "czech",
									"da" => "danish",
									"nl" => "dutch",
									"en" => "english",
									"et" => "estonian",
									"tl" => "filipino",
									"fi" => "finnish",
									"fr" => "french",
									"gl" => "galician",
									"de" => "german",
									"el" => "greek",
									"iw" => "hebrew",
									"hi" => "hindi",
									"hu" => "hungarian",
									"id" => "indonesian",
									"it" => "italian",
									"ja" => "japanese",
									"ko" => "korean",
									"lv" => "latvian",
									"lt" => "lithuanian",
									"mt" => "maltese",
									"no" => "norwegian",
									"fa" => "persian alpha",
									"pl" => "polish",
									"pt" => "portuguese",
									"ro" => "romanian",
									"ru" => "russian",
									"sr" => "serbian",
									"sk" => "slovak",
									"sl" => "slovenian",
									"es" => "spanish",
									"sv" => "swedish",
									"th" => "thai",
									"tr" => "turkish",
									"uk" => "ukrainian",
									"vi" => "vietnamese"
									 );
			for($i = 0;$i < $count;$i++) {
				mysql_data_seek($sql, $i);
				$arr = mysql_fetch_array($sql);
				$currentLang = $arr['language'];
				$formalLanguage = $languageSelection[$currentLang];
				?><a href="index.php?lang=<?php echo $currentLang; ?>"><?php echo ucfirst($formalLanguage); ?></a>&nbsp;&nbsp;<?php
			}
		}
		?></div></td>
        </tr>
      </table>
  <table width="980" height="94" border="0" align="center" cellpadding="10" cellspacing="1">
            <tr>
              <td height="92" valign="middle"><div class="logo"><?php 
		//ECHO LOGO
		$logo = getValue("select value from design where name='logo'");
		if($logo == '') echo '<span id="myriad" style="font-size: 24px; font-weight: bold; font-family:Verdana, Arial, Helvetica, sans-serif; color: #FFFFFF; padding-top: 25px;">'.$ptrname.'</span>'; else echo $logo;
		?></div>
        
        <div id="banner" align="center" style="padding-top: 14px;"><?php displayBanner(); ?></div>
        
        </td>
            </tr>
          </table>
          <div class="menu">
        <?php
		$menuType = 'website';
		$query = mysql_query("SELECT * FROM menus WHERE menuType = ".quote_smart($menuType)." ORDER BY sortOrder");
		$count = mysql_num_rows($query);
		$thisTP = $_REQUEST['tp'];
		if($count == 0) {
			$debugLog = "Using Static Menu\n";
			if($thisTP == 'advertise') $arr['icon'] = 'cart.png';
			if($thisTP == 'news') $arr['icon'] = 'hot.png';
			if($thisTP == 'howtoearn') $arr['icon'] = 'dollar.png';
			if($thisTP == 'signup') $arr['icon'] = 'ok.png';
			if($thisTP == 'faq') $arr['icon'] = 'info.png';
			if($thisTP == 'member') $arr['icon'] = 'key.png';
			if($thisTP == 'viewads') $arr['icon'] = 'present.png';
			
?>
		<ul>
          <li><?php if($_REQUEST['tp'] == '' || $_REQUEST['tp'] == 'home') echo "<img src=\"".$templateFolder."images/icons/golden_offer.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php"<?php if($_REQUEST['tp'] == '' || $_REQUEST['tp'] == 'home') echo ' class="active"'; ?>><span><?php echo __('Home'); ?></span></a></li>
          
          <li><?php if($_REQUEST['tp'] == 'news') echo "<img src=\"".$templateFolder."images/icons/hot.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php?tp=news"<?php if($_REQUEST['tp'] == 'news') echo ' class="active"'; ?>><span><?php echo __('News'); ?></span></a></li>
          
          <li><?php if($_REQUEST['tp'] == 'advertise') echo "<img src=\"".$templateFolder."images/icons/cart.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php?tp=advertise"<?php if($_REQUEST['tp'] == 'advertise') echo ' class="active"'; ?>><span><?php echo __('Advertise'); ?></span></a></li>
          
          
          <li><?php if($_REQUEST['tp'] == 'howtoearn') echo "<img src=\"".$templateFolder."images/icons/dollar.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php?tp=howtoearn"<?php if($_REQUEST['tp'] == 'howtoearn') echo ' class="active"'; ?>><span><?php echo __('Ways to Earn'); ?></span></a></li>
          
          
          <li><?php if(strtolower($_REQUEST['tp']) == 'viewads') echo "<img src=\"".$templateFolder."images/icons/present.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php?tp=viewAds"<?php if(strtolower($_REQUEST['tp']) == 'viewads') echo ' class="active"'; ?>><span><?php echo __('View Ads'); ?></span></a></li>
          
          
          <li><?php if($_REQUEST['tp'] == 'member' || $_REQUEST['tp'] == 'user') echo "<img src=\"".$templateFolder."images/icons/key.png\" valign=\"absmiddle\" border=\"0\"> "; ?><a href="index.php?tp=member"<?php if($_REQUEST['tp'] == 'member' || $_REQUEST['tp'] == 'user') echo ' class="active"'; ?>><span><?php echo __('Members Area'); ?></span></a></li>
          
          
          <li><a href="index.php?tp=signup"<?php if($_REQUEST['tp'] == 'signup') echo "<img src=\"".$templateFolder."images/icons/ok.png\" valign=\"absmiddle\" border=\"0\"> "; ?>><span><?php echo __('Get Started'); ?></span></a></li>
        
        
        </ul>
        <?php
		} else {
			echo "<ul>";
			$debugLog = "Using Dynamic Menu\n";
			$thisTP = strtolower($_REQUEST['tp']);
			for($i = 0;$i < $count;$i++) {
				mysql_data_seek($query, $i);
				$arr = mysql_fetch_array($query);
				if($i != 0) echo $spacer;
				echo "<li><a href=\"".$arr['menuAction']."\"";
				$tpSplit = explode("tp=", $arr['menuAction']);
				$tpSplit2 = explode("&",$tpSplit[1]);
				$tp2 = $tpSplit2[0];
				
				if($tp2 == 'member' && (
				$_REQUEST['tp'] == 'orders'
				|| $_REQUEST['tp'] == 'editinfo'
				|| $_REQUEST['tp'] == 'redemption'
				|| $_REQUEST['tp'] == 'reflinks'
				|| $_REQUEST['tp'] == 'referrals'
				|| $_REQUEST['tp'] == 'manageads'
				
				)) echo " class=\"active\""; 
				
				
				if(($_REQUEST['tp'] == $tp2 && $tp2 != '') || ($_REQUEST['tp'] == $tp2 && $arr['menuAction'] == 'index.php')) echo " class=\"active\"";
				$debugLog .= "IF (".$_REQUEST['tp']." == ".print_r($tp2,1).") echo active...\n";
				if($_SESSION['lang'] == 'en') $size = 14; else $size = 11;
				echo "><span style=\"font-size: ".$size."px;\"><strong style=\"padding: 0; margin:0;\">";
				
				if($tp2 == $_REQUEST['tp'] && $thisTP == 'advertise') $arr['icon'] = 'cart.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'news') $arr['icon'] = 'hot.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'howtoearn') $arr['icon'] = 'dollar.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'signup') $arr['icon'] = 'ok.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'faq') $arr['icon'] = 'info.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'contacts') $arr['icon'] = 'mail.png';
				else if($tp2 == 'member' && (
				$thisTP == 'member'
				|| $thisTP == 'orders'
				|| $thisTP == 'editinfo'
				|| $thisTP == 'redemption'
				|| $thisTP == 'reflinks'
				|| $thisTP == 'referrals'
				|| $thisTP == 'manageads'
				)) $arr['icon'] = 'key.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == 'viewads') $arr['icon'] = 'present.png';
				else if($tp2 == $_REQUEST['tp'] && $thisTP == '' && $arr['menuAction'] == 'index.php') $arr['icon'] = 'customer_service.png';
				else $arr['icon'] = '';
				
				if($arr['icon'] != '' && $_SESSION['lang'] == 'en') echo "<img src=\"".$templateFolder."images/icons/".$arr['icon']."\" valign=\"absmiddle\" border=\"0\" height=\"16\" width=\"16\" style=\"padding: 0; margin:0; padding-right: 0px; padding-left: 0px;padding-top:4px; padding-bottom: 0px;\"> &nbsp;";
				echo __($arr['menuName'],false)."</strong></span></a></li>\n";
			}
			echo "</ul>";
		} 
		
		?>
      </div>
      <div class="clr"></div>
      
  </div>
</div>
</div><!-- memberMenu --><?php
if(isset($_SESSION['login'])) {
?>
                                  <table id="Table_01" width="100%" height="28" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td valign="middle" bgcolor="#EFEFEF" height="28"><div style="padding: 10px;" class="block_header2">
                                        <?php 
										  //showMenu("member",'font12pxSize','');
										  
										  
										  $query = mysql_query("SELECT * FROM menus WHERE menuType = ".quote_smart("member")." ORDER BY sortOrder");

		$count = mysql_num_rows($query);


			for($i = 0;$i < $count;$i++) {

				mysql_data_seek($query, $i);

				$arr = mysql_fetch_array($query);

				if($i != 0) echo $spacer;

				echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$arr['menuAction']."\" class=\"".$class."\" style=\"".$style."\">";
				$thisTP = str_replace("index.php?tp=", "", $arr['menuAction']);
				
				if($arr['icon'] != '') echo "<img src=\"".$templateFolder."images/icons/".$arr['icon']."\" valign=\"absmiddle\" border=\"0\"> ";
				echo __($arr['menuName'],false);
				echo "</a>";

			}

		
										  
										   ?>
                                      </div></td>
                                    </tr>
                                  </table>
<?php
}
?><!--body start-->
<div class="main" style="background-image: url('<?php echo $templateFolder; ?>images/bg.gif');">
<div class="slider_top" style="background-image: url('<?php echo $templateFolder; ?>images/bg.gif');">