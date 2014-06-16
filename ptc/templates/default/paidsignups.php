<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<?php echo $pageHeader; ?><h2><?php echo __('Get Paid to Sign Up'); ?></h2>
<p><?php
if(!$start) $start=0;
$count=5;
//$sql=mysql_query("SELECT * FROM signups WHERE fsize > fsignups");
$sql = mysql_query("SELECT * FROM signups WHERE fsize > fsignups AND fnum NOT IN (
SELECT tasknum FROM signtask WHERE fourlog = ".quote_smart($_SESSION['login'])."
)");
$rows=mysql_num_rows($sql);
if($rows<=($start+$count))
$end=$rows;
else
$end=$start+$count;

//$totalTasks=0;
for($i=$start;$i<$end;$i++) {
	mysql_data_seek($sql,$i);
	$arr=mysql_fetch_array($sql);
	extract($arr);
	$pts_pay_amount = getCommPrice($_SESSION['login'],'ptsignup',$fnum);
	$pts_pay_type = getCommPayType('ptsignup',$fnum);
	?><?php echo __('Task number'); ?>:<?php echo $fnum; ?> <br><b><?php echo $fsitename; ?></b><br><?php echo $fnote; ?><br><a href="<?php echo $furl; ?>" target=blank><?php echo $furl; ?></a><br><?php echo __('You earn'); ?>: <?php echo $pts_pay_amount; ?> <?php echo $pts_pay_type; ?><br><a href="index.php?tp=confirmreg&id=<?php echo $username; ?>&num=<?php echo $fnum; ?>" target=blank><?php echo __('Confirm registration',false); ?></a><BR><BR><?php
}
//if($totalTasks > 0) {
if($end != $rows || $start != 0) {
	if($start != 0){
		$start=$start-$count;
		echo"<a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('previous')." $count</a> | ";
		$fl=1;
	}
	if($end<$rows) {
		if($fl) {
			$start=$start+$count+$count;
		} else {
			$start=$start+$count;
		}
		echo"| <a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('next')." $count</a>";
	}
} else { //IF $totalTasks == 0
	echo __("There are currently no signup tasks. Please check back soon!<BR>");
}
?>
</p>
<?php echo $pageFooter; ?>