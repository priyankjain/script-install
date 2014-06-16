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
//click.php?n=number

if ($s[gateway])
{ if (!$_GET[a])
  { $_SESSION[LUG_refer_remote] = $s[ip];
    $_SESSION[LUG_refer_link] = $_GET[n];
    setcookie(LUG_refer_remote,$s[ip],$s[cas]+30); 
    setcookie(LUG_refer_link,$_GET[n],$s[cas]+30); 
    page_from_template('click.html',$a);
  }
  else
  { if (($_COOKIE[LUG_refer_remote]==$_SESSION[LUG_refer_remote]) AND ($_COOKIE[LUG_refer_link]==$_SESSION[LUG_refer_link]))
    $n = $_SESSION[LUG_refer_link];
    else $n = 0;
  }
}
else $n = $_GET[n];

count_click('in','link',$n);

if ($s[in_to_cat])
{ $link = get_item_variables('l',$n);
  $category = explode(' ',str_replace('_','',$link[c]));
  if ($category[0]) $location = category_url('l',$category[0],0,'',1,'','','','');
}
if (!$location) $location = $s[site_url];
header("HTTP/1.1 301 Moved Permanently");
header ("Location: $location");
exit;

?>