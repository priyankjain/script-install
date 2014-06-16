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

#############################################################################
#############################################################################
#############################################################################

$q = dq("select * from $s[pr]site_news order by time desc",1);
while ($x = mysql_fetch_assoc($q))
{ unset($x[related_l],$x[related_a],$x[related_v],$x[related_n]);
  $x['date'] = datum($x[time],0);
  foreach ($s[item_types_short] as $k=>$what)
  { if ($x['related_'.$what])
    { $table = $s[item_types_tables][$what];
  	  $query = my_implode('n','or',explode(' ',$x['related_'.$what]));
      if ($query)
      { $q1 = dq("select * from $table where $query",1);
        while ($x1 = mysql_fetch_assoc($q1))
        $x['related_'.$what] .= '<a href="'.get_detail_page_url($what,$x1[n],$x1[rewrite_url],$x1[category],1).'">'.$x1[title].'</a><br />';
      }
    }
    if (!$x['related_'.$what]) { $x['hide_related_'.$what.'_begin'] = '<!--'; $x['hide_related_'.$what.'_end'] = '-->'; }
  }
  $a[news] .= parse_part('site_news.txt',$x);
}
page_from_template('site_news.html',$a);

#############################################################################
#############################################################################
#############################################################################

?>