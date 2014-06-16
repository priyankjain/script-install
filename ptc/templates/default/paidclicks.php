<?php

if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<?php echo $pageHeader; ?><script type="text/javascript" language="javascript">
<!--
	function hideDivTag(tagID) {
		var divTag;
		divTag = document.getElementById(tagID);
		divTag.style.display = "none";
	}
-->
</script>

<h1><?php echo __('Get Paid to Click'); ?></h1>
<p><?php echo __('Viewing websites and getting paid to click links (To view our advertisers websites) is one of the most used methods to earn money with'); ?> <?php echo $ptrname; ?>.</p>
          <div align="center" style="overflow: auto; width: 100%; height: 500;">
            
              <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM tasks WHERE fvisits < fsize");
$rows=mysql_num_rows($sql);
if($rows == 0) {
	echo "There are currently no links to left to click. Check back soon!<br />";
} else {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		$sq=@mysql_query("SELECT fnum FROM taskactivity WHERE task=".quote_smart($fn)." AND username=".quote_smart($_SESSION['login'])." AND fdate=DATE(now())") or die(mysql_error());
		if(!$sq) {
			//
		} else {
			if(mysql_num_rows($sq) == 0) {
				$prise = getCommPrice($_SESSION['login'],'links',$fn);
				$fpaytype = getCommPayType('links',$fn);
				?>
              	<div id="ad<?php echo $fn; ?>">
				<a href="index.php?tp=visit_task&t=<?php echo $fn; ?>&id=<?php echo $fn; ?>" target='_blank' onclick='hideDivTag("ad<?php echo $fn; ?>");'><?php echo $fsitename; ?></a>
				 ( <?php echo __('You earn');?>: 
				<?php
				if($fpaytype=='points') { ?><?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)<?php }
				else if($fpaytype=='usd') echo $setupinfo['currency']."$prise";
				?> ) <BR /><BR /></div><?php
				$clicks++;
			}
		}
	}
	?><br />
<br />
<?php
	if($start != 0) {
		$start=$start-$count;
		echo"<a href=index.php?tp=$tp&st=$st&s=$s&start=$start>previous $count</a> | ";
		$fl=1;
	}
	if($end<$rows) {
		if($fl) $start=$start+$count+$count; else $start=$start+$count;
		echo"| <a href=index.php?tp=$tp&st=$st&s=$s&start=$start>next $count</a>";
	}
	?><?php
	if($clicks == 0) {
		echo __("<br /><br /><strong>We're Sorry</strong>, There are currently no links to click left. Check back soon! Link's are renewing and they are on a first come first serve basis!");
	}
}
?>
            
</div>

<?php echo $pageFooter; ?>