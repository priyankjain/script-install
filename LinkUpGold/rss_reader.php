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

########################################################################################
########################################################################################
########################################################################################

function ContentElement($string,$starttag,$endtag) {
$len_starttag=strlen($starttag);
$len_endtag=strlen($endtag);
$poz_starttag=strpos($string,$starttag);
$poz_endtag=strpos($string,$endtag,$poz_starttag+$len_starttag);
$read_from = $poz_starttag+$len_starttag;
$read_to = $poz_endtag-$poz_starttag-$len_endtag+1;
if ($read_to<=0) return false;
$a = substr($string,$read_from,$read_to);
return $a;
}

########################################################################################

function clsRSS($our_path,$our_charset="",$max_items) {
//if (!fclose(fopen($our_path,"r"))) return false;
$rss_file = fetchURL($our_path);
$rss_file = replace_once_html(str_replace('<![CDATA[','',str_replace(']]>','',$rss_file)));
preg_match("/encoding=\"(.+)\?>/is",$rss_file,$Items1); $encoding = trim(str_replace('"','',$Items1[1]));
$rss_file = str_replace('€','&euro;',$rss_file);
$rss_file = str_replace('°','&deg;',$rss_file);
$rss_file = str_replace('’','&#039;',$rss_file);
$rss_file = str_replace('ß','ss',$rss_file);
$rss_file = str_replace('®','',$rss_file);
$rss_file = refund_html($rss_file);
if ((function_exists('iconv')) AND ($our_charset!='') AND ($encoding))
{ $r = iconv($encoding,$our_charset,$rss_file);
  $r = str_replace('€','&euro;',$r);
  $r = str_replace('°','&deg;',$r);
  $r = str_replace('’','&#039;',$r);
  if ($r) $rss_file = $r;
}
/*
€ &euro;
° &deg;
’ '
*/
$rss_file = str_replace("'",'&#039;',stripslashes(html_entity_decode($rss_file,ENT_NOQUOTES,$s[charset])));
preg_match_all("/<item>(.+)<\/item>/Uis",$rss_file,$Items1,PREG_SET_ORDER);
foreach($Items1 as $k => $v) { $one_item = clsItem($v[0]); if ($one_item) $items_array[] = $one_item; if (count($items_array)>=$max_items) break; }
return $items_array;
}

########################################################################################

function clsItem($str) {
//echo $str; exit;
preg_match("/<title>(.+)<\/title>/is",$str,$b); $a[title] = $b[1];
preg_match("/<link>(.+)<\/link>/is",$str,$b); $a[url] = $b[1];
preg_match("/<description>(.+)<\/description>/is",$str,$b); $a[description] = $b[1];
preg_match("/<image>(.+)<\/image>/is",$str,$b); $a[image] = $b[1];
//if (!$a[image]) { preg_match("/http:\/\/(.+).jpg/i",$str,$b); if ($b[1]) $a[description] = '<img style="float: left; margin: 0px 5px 0px 2px;" src="http://'.$b[1].'.jpg">'.$a[description]; }
if ((!$a[title]) OR (!$a[url])) return false;
foreach ($a as $k=>$v) $a[$k] = str_replace('&#60;','<',$v);
$a[description] = str_replace('<a ','<span ',str_replace('</a>','</span>',$a[description]));
return $a;
}

########################################################################################
########################################################################################
########################################################################################

?>