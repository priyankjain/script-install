<?PHP

#################################################
##                                             ##
##               Link Up Gold                  ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                                             ##
##               version:  8.0                 ##
##            copyright (c) 2012               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./common.php');

$table = $s[item_types_tables][$_GET[what]]; if (!$table) exit;
$where = get_where_fixed_part('',0,'',$s[cas]);
$q = dq("select *,MD5(RAND()) as m from $table where $where order by created desc limit 10",1);
while ($b = mysql_fetch_assoc($q))
{ $pocet++;
  $b[url] = get_detail_page_url($_GET[what],$b[n],$b[rewrite_url],$b[category],1);
  $a[items] .= stripslashes(parse_part('javascript_export_item.txt',$b));
}

$a[site_url] = $s[site_url];
$a1 = stripslashes(parse_part('javascript_export.txt',$a));

$lines = explode("\n",$a1);
foreach ($lines as $k=>$v)
{ $v = unreplace_once_html(trim($v));
  $v = str_replace('"','\\"',$v);
  $complete .= "document.write(\"$v\");\n";
}
echo $complete;


?>