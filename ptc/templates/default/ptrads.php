<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<?php echo $pageHeader; ?>
<h2><?php echo __('Get Paid to Read Ad\'s'); ?></h2>
<p><script type="text/javascript" language="javascript">
<!--
	function hideDivTag(tagID) {
		var divTag;
		divTag = document.getElementById(tagID);
		divTag.style.display = "none";
	}
-->
</script><h3><?php echo __('Paid to read advertisements'); ?> </h3>
           <div align="center" style="overflow: auto; width: 100%; height: 500;"> 
              <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM ptrads WHERE fvisits < fsize");
$rows=mysql_num_rows($sql);
if($rows == 0) {
	echo __("There are currently no links to left to click. Check back soon!");
} else {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		$sq=mysql_query("SELECT fnum FROM ptradsactivity WHERE task=".quote_smart($fn)." AND username=".quote_smart($_SESSION['login'])." AND fdate=DATE(now())") or die(mysql_error());
		if(!$sq) {
			//
		} else {
			if(mysql_num_rows($sq) == 0) {
				$prise = getCommPrice($_SESSION['login'],'ptreadads',$fn);
				$fpaytype = getCommPayType('ptreadads',$fn);
				?>
              <div id="ad<?php echo $fn; ?>">
               
				<a href="index.php?tp=ptrads_visit&sF=1&id=<?php echo $fn; ?>" target="_blank" onclick='hideDivTag("ad<?php echo $fn; ?>");'><?php echo $fsitename; ?></a>
				( You earn: 
				<?php
				if($fpaytype=='points') { ?><?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)<?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?> )
				</div>  <BR /><BR /><?php
				$clicks++;
			}
		}
	}
	?><?php
	if($start != 0) {
		$start=$start-$count;
		echo"<a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('previous')." $count</a> | ";
		$fl=1;
	}
	if($end<$rows) {
		if($fl) $start=$start+$count+$count; else $start=$start+$count;
		echo"| <a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('next')." $count</a>";
	}
	?><?php
	if($clicks == 0) {
		echo __("There are currently no paid to read ad's to earn on. Check back soon!");
	}
}
?>
</div>
</p>
<?php echo $pageFooter; ?>