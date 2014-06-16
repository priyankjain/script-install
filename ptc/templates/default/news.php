<?php echo $pageHeader; ?>
<h1>News and Recent Events</h1>
<p>
                    <?php
$query = mysql_query("select article, title, newsDate from newsarchive ORDER BY newsDate DESC");
$count = mysql_num_rows($query);
if($count > 0) {
	for($i = 0; $i < $count;$i++) {
		mysql_data_seek($query, $i);
		$arr = mysql_fetch_array($query);
		$search = array(); $replace = array();
		$search[] = "\n";
		$replace[] = "<BR>\n";
		
		echo "<STRONG>".$arr['newsDate']." - ".__($arr['title'])."</STRONG><BR><div width=\"90%\" id=\"myriad\">".__(str_replace($search,$replace,$arr['article']))."</div><BR><HR>\r\n\r\n";
	}
} else {
	echo __("Thanks for visiting our news section! We are currently busy working on other more important things such as payouts ;-) Come back soon for more up to date events!");
}

?></p>
<br>
<br>
<?php echo $pageFooter; ?>