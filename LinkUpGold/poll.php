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
$s[back] = getenv('HTTP_REFERER'); if (!$s[back]) $s[back] = $s[site_url];
$_GET = replace_array_text($_GET);
if ($_GET[action]=='vote') vote($_GET);
show_poll($_GET[n]);

##################################################################################
##################################################################################
##################################################################################

function vote($in) {
global $s;
$s[voted_now] = 1;
if ($_COOKIE[LUG_vote][$in[n]]) { if ($s[old_poll_rate]) header ("Location: $s[back]"); else show_poll($in[n]); exit; }
$array = array(1,2,3,4,5); if ((!in_array($in[a],$array)) OR (!is_numeric($in[a])) OR (!is_numeric($in[n]))) exit;
setcookie ("LUG_vote[$in[n]]",1,$s[cas]+2592000);
dq("update $s[pr]polls set n$in[a] = n$in[a]+1 where n = '$in[n]' and active = '1'",0);
$q = dq("select * from $s[pr]polls where n = '$in[n]' AND active = '1'",0);
$d = mysql_fetch_assoc($q);
$total = $d[n1]+$d[n2]+$d[n3]+$d[n4]+$d[n5];
$max = max($d[n1],$d[n2],$d[n3],$d[n4],$d[n5]);
if ($max>0)
{ for ($x=1;$x<=5;$x++)
  { $size[$x] = ($d["n$x"]/$max)*180;
    if ($size[$x]<1) $size[$x] = 0;
  }
}
else $size[1] = $size[2] = $size[3] = $size[4] = $size[5] = 1;
dq("update $s[pr]polls set p1 = '$size[1]', p2 = '$size[2]', p3 = '$size[3]', p4 = '$size[4]', p5 = '$size[5]', votes = $total where n = $in[n] and active = 1",0);
if ($s[old_poll_rate]) header ("Location: $s[back]");
else show_poll($in[n]);
exit;
}

##################################################################################

function show_poll($n) {
global $s,$m;
if ($n) $n_query = "and n = '$n'";
$q = dq("select * from $s[pr]polls where active = 1 $n_query",0);
$data = mysql_fetch_assoc($q);
if (!$data[n]) return false;
$voted_before = $_COOKIE[LUG_vote][$data[n]];
for ($x=1;$x<=5;$x++)
{ if ($data["a$x"])
  { $w = $data["p$x"]+1;
    $data["vote_link_$x"] = "$s[site_url]/poll.php?action=vote&amp;n=$data[n]&amp;a=$x";
    $data["vote_argument_$x"] = "?action=vote&amp;n=$data[n]&amp;a=$x";
    if ((!$s[poll_result_after]) OR ($voted_before) OR ($s[voted_now])) $data["graph_$x"] = "<img border=0 src=\\\"$s[site_url]/images/poll$x.png\\\" width=$w height=15><br />".$data["a$x"]."<br />(".$data["n$x"].")";
    else $data["graph_$x"] = $data["a$x"];
    $data["display$x"] = '';
  }
  else $data["display$x"] = 'none';
}
$data[site_url] = $s[site_url];
if ($voted_before) echo parsejava('poll_results.txt',$data);
elseif ($s[voted_now]) { $data[info] = '<span class="text10_bold">'.$m[thank_for_vote].'</span>'; echo iconv($s[charset],'UTF-8',stripslashes(parse_part('poll_results.txt',$data))); }
elseif ($s[old_poll_rate]) parsejava('poll_old.txt',$data);
else parsejava('poll.txt',$data);
exit;
}

##################################################################################
##################################################################################
##################################################################################

?>