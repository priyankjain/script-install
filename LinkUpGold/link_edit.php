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
include('./f_create_edit.php');
$s[selected_menu] = 1;
get_messages('link_create_edit.php');
include($s[phppath].'/data/data_forms.php');
$s[adv_extra] = array('l_adv_v_title','l_adv_r_title','l_adv_v_url','l_adv_r_url','l_adv_v_recip','l_adv_r_recip','l_adv_v_description','l_adv_r_description','l_adv_v_detail','l_adv_r_detail','l_adv_details_html_editor','l_adv_v_keywords','l_adv_r_keywords','l_adv_v_map','l_adv_r_map','l_adv_v_rss_url','l_adv_r_rss_url','l_adv_v_start_end','l_adv_max_cats_users','l_adv_max_pictures_users','l_adv_min_title','l_adv_max_title','l_adv_min_description','l_adv_max_description','l_adv_min_detail','l_adv_max_detail','l_adv_min_keywords','l_adv_max_keywords','l_adv_allowed_keywords','l_adv_pr_google_min'); // can't do it automatically!

check_post_rights('l');
switch ($_GET[action]) {
case 'links_list'			: links_list($_GET[email]);
case 'link_edit'			: link_edit($_GET);
case 'send_password'		: send_password($_GET[n]);
case 'delete_image'			: delete_image($_GET);
case 'link_statistic'		: link_statistic($_GET);
case 'link_reset_statistic'	: link_reset_statistic($_GET[n]);
case 'link_delete'			: link_delete($_GET[n]);
}
if (!$_POST) link_login_form($_GET[n]);
switch ($_POST[action]) {
case 'link_edit'			: link_edit($_POST);
case 'link_edited'			: link_edited($_POST);
}

##################################################################################

function link_statistic($in) {
global $s,$m;
$a = check_link_access_rights($in[n],'','');
if ($a[sponsored]) $a[is_advertising] = $m[link_is_adv];
else $a[is_advertising] = $m[link_isnot_adv];
if ($a[status]=='enabled') $a[status] = $m[Enabled]; else $a[status] = $m[Disabled];

$q = dq("select * from $s[pr]links_stat where n = '$in[n]'",1); $stat = mysql_fetch_assoc($q);
if (!mysql_num_rows($q))
{ dq("insert into $s[pr]links_stat values('$in[n]','$a[hits]','$a[hits]','100','$a[hits_m]','$a[hits_m]','100','$a[hits]','$a[hits]','100','$a[created]')",1);
  $q = dq("select * from $s[pr]links_stat where n = '$in[n]'",1); $stat = mysql_fetch_assoc($q);
}
$stat[statistic_reset] = datum($stat[reseted],1);

if (is_numeric($in[year])) $stat[year] = $in[year]; else $stat[year] = year_number($s[cas]);
if (is_numeric($in[month])) $month = $in[month]; else $month = month_number($s[cas]);
$stat[month_name] = $m['m'.$month];
$monthly_stat = link_get_monthly_stat($in[n],$stat[year],$month);
$a = array_merge((array)$a,(array)$stat,(array)$monthly_stat);
page_from_template('link_statistic.html',$a);
}

##################################################################################

function link_get_monthly_stat($n,$year,$month) {
global $s;
$q = dq("select * from $s[pr]links_days where n = '$n' and y = '$year' and m = '$month'",1);
while ($data = mysql_fetch_assoc($q)) $a['day'.$data[d]] = $data;
$dni = date('t',mktime(0,0,0,$month,15,$year));
for ($x=1;$x<=$dni;$x++)
{ $data[day] = $x;
  if ($a["day$x"][i]) $data[i] = $a["day$x"][i]; else $data[i] = 0;
  if ($a["day$x"][c]) $data[c] = $a["day$x"][c]; else $data[c] = 0;
  if ($a["day$x"][r]) $data[r] = $a["day$x"][r]; else $data[r] = 0;
  $table .= parse_part('link_statistic_day.txt',$data);
  $total_i += $data[i]; $total_c += $data[c]; 
}
$total_r = round(($total_c/$total_i)*100,2);
if ($month==1) { $prev_m = 12; $prev_y = $year - 1; } else { $prev_m = $month - 1; $prev_y = $year; }
if ($month==12) { $next_m = 1; $next_y = $year + 1; } else { $next_m = $month + 1; $next_y = $year; }
$previous_month = "$s[site_url]/link_edit.php?action=link_statistic&amp;n=$n&amp;month=$prev_m&amp;year=$prev_y";
$next_month = "$s[site_url]/link_edit.php?action=link_statistic&amp;n=$n&amp;month=$next_m&amp;year=$next_y";
return array('statistic_days'=>$table,'days_i'=>$total_i,'days_c'=>$total_c,'days_r'=>$total_r,'previous_month'=>$previous_month,'next_month'=>$next_month);
}

##################################################################################

function link_reset_statistic($n) {
global $s,$m;
$a = check_link_access_rights($n,'','');
dq("update $s[pr]links_stat set i_reset = '0', c_reset = '0', r_reset = '0', reseted = '$s[cas]'  where n = '$n'",1);
$a[n] = $n;
link_statistic($a);
}

##################################################################################

function send_password($n) {
global $s,$m;
$q = dq("select email,password,n from $s[pr]links where n = '$n'",1);
$a = mysql_fetch_assoc($q);
if (!$a[email]) problem($m[not_found]);
$a[to] = $a[email];
$a[login_url] = "$s[site_url]/link_edit.php?n=$n";
mail_from_template('link_password_remind.txt',$a);
$s[info] = info_line($m[pass_sent].' '.$a[email]);
link_login_form($n);
}

##################################################################################
##################################################################################
##################################################################################

function link_edit($in) {
global $s,$m;
$link = check_link_access_rights($in[n],$in[url],$in[password]);
if ($link[sponsored]) foreach ($s[adv_extra] as $k=>$v) { $v1 = str_replace('l_adv_','l_',$v); $s[$v1] = $s[$v]; }
if ($in[action]=='link_edit')
{ $in = $link;
  $in[action]='link_edit';
}
else $in = array_merge((array)$link,(array)$in); // edited
$a = link_create_edit_form_public($in,$link[n]);
page_from_template('link_edit.html',$a);
}

##################################################################################

function link_edited($in) {
global $s,$m;
$link = check_link_access_rights($in[n],'','');
if ($link[sponsored]) foreach ($s[adv_extra] as $k=>$v) { $v1 = str_replace('l_adv_','l_',$v); $s[$v1] = $s[$v]; }
$in = link_form_control($in); $a = $in[1];
if ($in[0])
{ $a[info] = info_line($m[errorsfound],implode('<br />',$in[0]));
  link_edit($a);
}
$usit = item_updated_get_usit('l',$a,1); $a = array_merge((array)$a,(array)$usit);
$a = link_edited_to_database($a);
link_edited_send_emails($a);
link_created_edited_thankyou($a,'link_edited.html');
}

##################################################################################

function link_edited_to_database($in) {
global $s,$m;
$old = get_item_variables('l',$in[n],0);
$c = categories_edited($in[c]);
$en_cats = has_some_enabled_categories('l',$in[c]);
dq("delete from $s[pr]links where n = '$in[n]' and (status = 'queue' or status = 'wait')",1);
$rewrite_url = discover_rewrite_url($in[title],0,'l'); 
if ($s[LUG_u_n])
{ $owner = get_user_variables($s[LUG_u_n]);
  $in[email] = $owner[email]; $in[name] = $owner[name]; $in[password] = $owner[password];
}
if (!$s[l_v_start_end]) { $in[t1] = $old[t1]; $in[t2] = $old[t2]; }
if ($s[autoapr])
{ dq("update $s[pr]links set url = '$in[url]', recip = '$in[recip]', title = '$in[title]', description = '$in[description]', detail = '$in[detail]', keywords = '$in[keywords]', map = '$in[map]', rss_url = '$in[rss_url]', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$s[LUG_u_n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', updated = '$s[cas]', t1 = '$in[t1]', t2 = '$in[t2]', status = 'enabled', en_cats = '$en_cats', rewrite_url = '$rewrite_url' where n = '$in[n]' and status != 'queue'",1);
  add_update_user_items('l',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('l',$in[n],0,1,$in[delete_image]);
  $s[use_for] = 'l'; 
}
else
{ $in[link_n] = $in[n];
  $in[user_n] = $s[LUG_u_n];
  $in[created_timestamp] = $old[created];
  $in[t1_timestamp] = $in[t1];
  $in[t2_timestamp] = $in[t2];
  $in[comments] = $old[comments];
  $in[popular] = $old[popular];
  $in[pick] = $old[pick];
  $in[status] = 'queue';
  $in[categories] = $in[c];
  $in[sponsored] = $old[sponsored];
  $in[dynamic_price] = $old[dynamic_price];
  $in[rating] = $old[rating];
  $in[votes] = $old[votes];
  $in[clicks_in] = $old[clicks_in];
  $in[clicks_in_m] = $old[clicks_in_m];
  $in[hits] = $old[hits];
  $in[hits_m] = $old[hits_m];
  enter_link($in);
  dq("insert into $s[pr]links_recips_info values ('$in[n]','$in[i_recip]')",1);
  add_update_user_items('l_q',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('l',$in[n],1,1,$in[delete_image]);
  $s[use_for] = 'l_q'; 
}
update_item_index('l',$in[n]);
update_item_image1('l',$in[n]);
recount_items_cats('l',$in[c],$old[c]);
return $in;
}

##################################################################################

function link_edited_send_emails($a) {
global $s;
$x = user_defined_items_emails($a[all_user_items_list],$a[value_codes],$a[value_texts],'_user_item.txt',1); $a = array_merge((array)$a,(array)$x);
$categories = $a[c];
$y = list_of_categories_for_item('l',0,$a[c],"\n",1); $a[categories] = $y[categories_names];
if ($s[l_i_new])
{ $a[to] = $s[mail];
  mail_from_template('link_admin.txt',$a);
}
if (($s[l_i_new_admins]) AND (count($categories)==1))
{ $q = dq("select email from $s[pr]admins_cats,$s[pr]admins where $s[pr]admins_cats.category = '$categories[0]' AND $s[pr]admins_cats.n = $s[pr]admins.n group by $s[pr]admins.n",1);
  while ($x = mysql_fetch_row($q)) { $a[to] = $x[0]; mail_from_template('link_admin.txt',$a); }
}
if ($s[l_i_owner])
{ $a[to] = $a[email];
  if ($s[autoapr]) mail_from_template('link_updated.txt',$a);
  else mail_from_template('link_updated_queued.txt',$a);
}
}

##################################################################################
##################################################################################
##################################################################################

function link_delete($n) {
global $s,$m;
if (!$s[users_can_delete_l]) exit;
check_link_access_rights($n,'','');
delete_items('l',$n);
$s[info] = info_line($m[link_deleted]);
links_list();
}

##################################################################################
##################################################################################
##################################################################################


?>