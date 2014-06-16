<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<?php echo $pageHeader; ?>
<h2><?php echo __('Paid to Take Survey\'s'); ?></h2>
<p><?php echo __('We want to know your opinion! Share your views and opinions, and we will pay you! The available survey\'s are listed below.'); ?>
<table width="600"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
              <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM surveys WHERE fviews < fsize");
$rows=mysql_num_rows($sql);
if($rows == 0) {
	echo "<tr valign=\"top\">
                <td width=\"100%\">".__('There are currently no links to left to click. Check back soon!')."</td></tr>";
} else {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		$sq=@mysql_query("SELECT id FROM surveyactivity WHERE surveyID=".quote_smart($id)." AND username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		if(!$sq) {
			//
		} else {
			if(mysql_num_rows($sq) == 0) {
				$prise = $ptsurvey_pay_amount;
				
				$prise = getCommPrice($_SESSION['login'],'ptsurvey',$id);
				$fpaytype = getCommPayType('ptsurvey',$id);
				?>
              <tr valign="top" align="left">
                <td width="60%">
				<a href="index.php?tp=viewsurvey&id=<?php echo $id; ?>&startSurvey=1" target=blank onclick='location.reload()'><?php echo $surveyname; ?></a><BR>(<?php echo getCount("SELECT COUNT(DISTINCT `option`) FROM surveyquestions WHERE surveyID = ".quote_smart($id)."","SUM","COUNT(DISTINCT `option`)"); ?> <?php echo __('Questions'); ?>)
				</td>
				<td width="40%" align="right"><?php echo __('You earn'); ?>: 
				<?php
				if($fpaytype=='points') { ?><?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)<?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
				</td></tr> <?php
				$clicks++;
			}
		}
	}
	?><TR><TD COLSPAN="2"><?php
	if($start != 0) {
		$start=$start-$count;
		echo"<a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('previous')." $count</a> | ";
		$fl=1;
	}
	if($end<$rows) {
		if($fl) $start=$start+$count+$count; else $start=$start+$count;
		echo"| <a href=index.php?tp=$tp&st=$st&s=$s&start=$start>".__('next')." $count</a>";
	}
	?></TD></TR><?php
	if($clicks == 0) {
		echo "<tr><td COLSPAN=\"2\">".__('There are currently no survey\'s to earn on. Check back soon!')."</td></tr>";
	}
}
?>
            </table>
        </td>
    </tr>
</table>
</p>
<?php echo $pageFooter; ?>