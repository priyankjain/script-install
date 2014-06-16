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

error_reporting  (E_ERROR | E_PARSE);
if (!$_POST) setup_form();
else setup_write_data($_POST);

###########################################################################

function setup_write_data($in) {
global $s;
include('./data/default_data.php');
show_admin_head();
if (!$in[dbhost]) $chyby[] = 'Your mysql database host is missing.';
if (!$in[nodbpass])
{ if (!$in[dbusername]) $chyby[] = 'Mysql database username is missing.';
  if (!$in[dbpassword]) $chyby[] = 'Password to mysql database is missing.';
}
if (!$in[dbname]) $chyby[] = 'Missing name of your mysql database.';
if (!eregi("^[a-z_]+$",$in[pr])) $chyby[] = 'Missing or wrong prefix of your tables.';
if (!$in[phppath]) $chyby[] = 'Full path to your php folder is missing.';
if ($chyby) chyba('<br /><b>One or more errors found. Please go back and try again.</b><br /><br />Errors:<br />'.join('<br />',$chyby),1);

$in[phppath] = str_replace(chr(92),'/',$in[phppath]);
foreach ($s as $k=>$v) $data .= "\$s[$k] = '$v';\n";
foreach ($in as $k=>$v) { $v = addslashes(str_replace("''","'",$v)); $data .= "\$s[$k] = \"$v\";\n"; }
$data = "<?PHP\n\n$data \n?>"; create_write_file("$in[phppath]/data/data.php",$data,1,0666,1);

$data = "AuthName \"BANNED\"\nAuthType Basic\nAuthUserFile /dev/null\nAuthGroupFile /dev/null\n\nrequire valid-user\n\n";
create_write_file("$in[phppath]/data/.htaccess",$data,0,0644,0);
create_write_file("$in[phppath]/styles/_common/templates/.htaccess",$data,0,0644,0);
create_write_file("$in[phppath]/styles/_common/email_templates/.htaccess",$data,0,0644,0);

/* --------------------------------------------------------------------- */

include('./data/data.php');
$linkid = db_connect(); if (!$linkid) chyba($s[db_error],1);

$table[] = 'adlinks';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `price` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `c_now` int(10) unsigned NOT NULL DEFAULT '0',
  `c_total` int(10) unsigned NOT NULL DEFAULT '0',
  `c` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text3` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text4` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text5` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text6` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text7` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text8` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text9` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text10` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `html` text CHARACTER SET utf8,
  PRIMARY KEY (`n`),
  KEY `price` (`price`),
  KEY `c_now` (`c_now`),
  KEY `approved` (`approved`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'admins';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lastaccesss` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'admins_cats';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `n` mediumint(8) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'admins_rights';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `action` varchar(50) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'ads';
$q[] = "(
  `n` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `html` text CHARACTER SET utf8,
  `edited` int(10) unsigned NOT NULL DEFAULT '0',
  `edited_by` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'adv_packs';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bonus` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'articles';
$q[] = "(
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `map` text CHARACTER SET utf8,
  `c` text CHARACTER SET utf8,
  `c_path` text CHARACTER SET utf8,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `hits_m` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `t1` int(10) unsigned NOT NULL DEFAULT '0',
  `t2` int(10) unsigned NOT NULL DEFAULT '0',
  `status` set('enabled','disabled','queue','wait') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `en_cats` tinyint(1) NOT NULL DEFAULT '0',
  `rewrite_url` text CHARACTER SET utf8,
  PRIMARY KEY (`n`,`status`),
  KEY `title` (`title`),
  KEY `t1` (`t1`),
  KEY `t2` (`t2`),
  KEY `status` (`status`),
  KEY `en_cats` (`en_cats`),
  KEY `description` (`description`),
  FULLTEXT KEY `text` (`text`),
  FULLTEXT KEY `c` (`c`),
  FULLTEXT KEY `c_path` (`c_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'blacklist';
$q[] = "(
  `what` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `phrase` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'blogs';
$q[] = "(
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `map` text CHARACTER SET utf8,
  `c` text CHARACTER SET utf8,
  `c_path` text CHARACTER SET utf8,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `hits_m` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `t1` int(10) unsigned NOT NULL DEFAULT '0',
  `t2` int(10) unsigned NOT NULL DEFAULT '0',
  `status` set('enabled','disabled','queue','wait') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `en_cats` tinyint(1) NOT NULL DEFAULT '0',
  `rewrite_url` text CHARACTER SET utf8,
  PRIMARY KEY (`n`,`status`),
  KEY `title` (`title`),
  KEY `t1` (`t1`),
  KEY `t2` (`t2`),
  KEY `status` (`status`),
  KEY `en_cats` (`en_cats`),
  KEY `description` (`description`),
  FULLTEXT KEY `text` (`text`),
  FULLTEXT KEY `c` (`c`),
  FULLTEXT KEY `c_path` (`c_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'board';
$q[] = "(
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'cats';
$q[] = "(
  `n` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias_of` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `image1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `m_keyword` text CHARACTER SET utf8,
  `m_desc` text CHARACTER SET utf8,
  `ad1` text CHARACTER SET utf8,
  `ad2` text CHARACTER SET utf8,
  `ad3` text CHARACTER SET utf8,
  `ad1n` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ad2n` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ad3n` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `path_text` text CHARACTER SET utf8,
  `path_n` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `submithere` tinyint(1) NOT NULL DEFAULT '0',
  `bigboss` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `similar` text CHARACTER SET utf8,
  `recip` tinyint(1) NOT NULL DEFAULT '0',
  `pagename` text CHARACTER SET utf8,
  `rewrite_url` text CHARACTER SET utf8,
  `tmpl_cat` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tmpl_one` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tmpl_det` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ad_manager` text CHARACTER SET utf8,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `users_only_cat` tinyint(1) NOT NULL DEFAULT '0',
  `users_only_items` tinyint(1) NOT NULL DEFAULT '0',
  `use_for` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `cat_group` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `in_menu` tinyint(1) NOT NULL DEFAULT '0',
  `hide_home` tinyint(1) NOT NULL DEFAULT '0',
  `item_created` int(10) unsigned NOT NULL DEFAULT '0',
  `item_edited` int(10) unsigned NOT NULL DEFAULT '0',
  `rss_url` text CHARACTER SET utf8,
  `rss_items` int(10) unsigned NOT NULL DEFAULT '0',
  `rss_read_interval` int(10) unsigned NOT NULL DEFAULT '0',
  `last_import` int(10) unsigned NOT NULL DEFAULT '0',
  `last_cleaning` int(10) unsigned NOT NULL DEFAULT '0',
  `youtube_keywords` text CHARACTER SET utf8,
  `max_items` int(10) unsigned NOT NULL DEFAULT '0',
  `map_address` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `latitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `longitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `map_zoom` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `country` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `zip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `dmoz_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`n`),
  KEY `parent` (`parent`),
  KEY `name` (`name`),
  KEY `path_n` (`path_n`),
  KEY `bigboss` (`bigboss`),
  KEY `visible` (`visible`),
  KEY `use_for` (`use_for`),
  FULLTEXT KEY `path_text` (`path_text`),
  FULLTEXT KEY `similar` (`similar`),
  FULLTEXT KEY `ad_manager` (`ad_manager`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'cats_disabled';
$q[] = "(
  `use_for` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'cats_dmoz';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `level` int(10) unsigned NOT NULL,
  `parent_path` text CHARACTER SET utf8,
  `path` text CHARACTER SET utf8,
  PRIMARY KEY (`n`),
  FULLTEXT KEY `path` (`path`),
  FULLTEXT KEY `parent_path` (`parent_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'cats_items';
$q[] = "(
  `what` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL,
  `c` int(11) NOT NULL,
  `primary` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'city_zip';
$q[] = "(
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `zip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lat` double(10,4) NOT NULL,
  `lon` double(10,4) NOT NULL,
  PRIMARY KEY (`n`),
  UNIQUE KEY `countryzip` (`country`,`zip`,`city`),
  KEY `zip` (`zip`),
  KEY `cityzip` (`city`,`zip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'comments';
$q[] = "(
  `n` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `item_no` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`n`),
  KEY `what` (`what`),
  KEY `item_no` (`item_no`),
  KEY `approved` (`approved`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'countries';
$q[] = "(
  `code` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `flag` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `i` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `name` (`name`),
  KEY `allowed` (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'er_reports';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text CHARACTER SET utf8,
  `link` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'files';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `item_n` int(10) unsigned NOT NULL DEFAULT '0',
  `queue` tinyint(1) NOT NULL DEFAULT '0',
  `file_n` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` text CHARACTER SET utf8,
  `description` text CHARACTER SET utf8,
  `file_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `extension` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`),
  KEY `item_n` (`item_n`),
  KEY `queue` (`queue`),
  KEY `what` (`what`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'import_temp';
$q[] = "(
  `what` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `line` text CHARACTER SET utf8,
  UNIQUE KEY `n` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'index';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `all_text` text CHARACTER SET utf8,
  KEY `what` (`what`),
  FULLTEXT KEY `all_text` (`all_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'index_suggest';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `word` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  KEY `word` (`word`),
  KEY `what` (`what`,`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'ip_country';
$q[] = "(
  `start` int(10) unsigned NOT NULL DEFAULT '0',
  `end` int(10) unsigned NOT NULL DEFAULT '0',
  `cc` char(2) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`start`,`end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'ip_country_temp';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `cc` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `n` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'items_maps';
$q[] = "(
  `what` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `latitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `longitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `map_zoom` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `country` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `zip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `work` (`what`,`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `recip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `detail` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `map` text CHARACTER SET utf8,
  `rss_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `c` text CHARACTER SET utf8,
  `c_path` text CHARACTER SET utf8,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `rating` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `clicks_in` int(10) unsigned NOT NULL DEFAULT '0',
  `clicks_in_m` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `hits_m` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `pick` tinyint(1) NOT NULL DEFAULT '0',
  `t1` int(10) unsigned NOT NULL DEFAULT '0',
  `t2` int(10) unsigned NOT NULL DEFAULT '0',
  `status` set('enabled','disabled','queue','wait') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `en_cats` tinyint(1) NOT NULL DEFAULT '0',
  `rewrite_url` text CHARACTER SET utf8,
  `sponsored` int(1) NOT NULL DEFAULT '0',
  `dynamic_price` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`n`,`status`),
  KEY `url` (`url`),
  KEY `title` (`title`),
  KEY `description` (`description`),
  KEY `t1` (`t1`),
  KEY `t2` (`t2`),
  KEY `status` (`status`),
  KEY `en_cats` (`en_cats`),
  KEY `sponsored` (`sponsored`),
  FULLTEXT KEY `detail` (`detail`),
  FULLTEXT KEY `keywords` (`keywords`),
  FULLTEXT KEY `c` (`c`),
  FULLTEXT KEY `c_path` (`c_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_adv';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `c_order` int(10) unsigned NOT NULL DEFAULT '0',
  `c_now` int(10) unsigned NOT NULL DEFAULT '0',
  `i_order` int(10) unsigned NOT NULL DEFAULT '0',
  `i_now` int(10) unsigned NOT NULL DEFAULT '0',
  `d_order` int(10) unsigned NOT NULL DEFAULT '0',
  `d_validby` int(10) unsigned NOT NULL DEFAULT '0',
  `c_dynamic_price` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `c_dynamic_order` int(10) unsigned NOT NULL DEFAULT '0',
  `c_dynamic_now` int(10) unsigned NOT NULL DEFAULT '0',
  `d_order_simple` int(10) unsigned NOT NULL DEFAULT '0',
  `d_validby_simple` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_adv_prices';
$q[] = "(
  `days` int(10) unsigned NOT NULL DEFAULT '0',
  `price` double(10,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_days';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `i` int(10) unsigned NOT NULL DEFAULT '0',
  `c` int(10) unsigned NOT NULL DEFAULT '0',
  `r` double(5,2) unsigned NOT NULL DEFAULT '0.00',
  `y` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d` smallint(5) unsigned NOT NULL DEFAULT '0',
  KEY `n` (`n`),
  KEY `y` (`y`),
  KEY `m` (`m`),
  KEY `d` (`d`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_extra_orders';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `order_time` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `control` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `info` text CHARACTER SET utf8,
  `notes` text CHARACTER SET utf8,
  `payment_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `link_or_pack` int(10) unsigned NOT NULL DEFAULT '0',
  `days_clicks_or_value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_ip';
$q[] = "(
  `n` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `what` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `direction` char(3) CHARACTER SET utf8 DEFAULT NULL,
  KEY `n` (`n`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_recips_info';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `info` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'links_stat';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `i` int(10) unsigned NOT NULL DEFAULT '0',
  `c` int(10) unsigned NOT NULL DEFAULT '0',
  `r` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `i_month` int(10) unsigned NOT NULL DEFAULT '0',
  `c_month` int(10) unsigned NOT NULL DEFAULT '0',
  `r_month` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `i_reset` int(10) unsigned NOT NULL DEFAULT '0',
  `c_reset` int(10) unsigned NOT NULL DEFAULT '0',
  `r_reset` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `reseted` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `n` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'login_failed';
$q[] = "(
  `who` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'login_failed_ip';
$q[] = "(
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'log_search';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `word` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`n`),
  KEY `what` (`what`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'news';
$q[] = "(
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `url` text CHARACTER SET utf8,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `map` text CHARACTER SET utf8,
  `c` text CHARACTER SET utf8,
  `c_path` text CHARACTER SET utf8,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `hits_m` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `t1` int(10) unsigned NOT NULL DEFAULT '0',
  `t2` int(10) unsigned NOT NULL DEFAULT '0',
  `status` set('enabled','disabled','queue','wait') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `en_cats` tinyint(1) NOT NULL DEFAULT '0',
  `rewrite_url` text CHARACTER SET utf8,
  PRIMARY KEY (`n`,`status`),
  KEY `title` (`title`),
  KEY `subtitle` (`description`),
  KEY `t1` (`t1`),
  KEY `t2` (`t2`),
  KEY `status` (`status`),
  KEY `en_cats` (`en_cats`),
  FULLTEXT KEY `text` (`text`),
  FULLTEXT KEY `c` (`c`),
  FULLTEXT KEY `c_path` (`c_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'online';
$q[] = "(
  `time` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'payment_process';
$q[] = "(
  `ip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `order_n` int(10) unsigned NOT NULL DEFAULT '0',
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `remember_me` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'polls';
$q[] = "(
  `n` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `a1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `a2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `a3` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `a4` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `a5` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `n1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `n2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `n3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `n4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `n5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `p1` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `p2` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `p3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `p4` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `p5` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'rates';
$q[] = "(
  `what` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'rss_content';
$q[] = "(
  `what` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `cat` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'site_news';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `details` text CHARACTER SET utf8,
  `related_l` text CHARACTER SET utf8,
  `related_a` text CHARACTER SET utf8,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'smilies';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shortcut` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'static';
$q[] = "(
  `page` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `style` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `mark` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `html` text CHARACTER SET utf8,
  KEY `search` (`page`,`style`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'times';
$q[] = "(
  `what` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'unconfirmed';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'users';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `nick` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address3` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phone1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phone2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `site_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `detail` text CHARACTER SET utf8,
  `user1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user3` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `showemail` tinyint(1) NOT NULL DEFAULT '0',
  `news1` tinyint(1) NOT NULL DEFAULT '0',
  `news2` tinyint(1) NOT NULL DEFAULT '0',
  `news3` tinyint(1) NOT NULL DEFAULT '0',
  `news4` tinyint(1) NOT NULL DEFAULT '0',
  `news5` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `joined` int(10) unsigned NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `style` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `post_art` tinyint(1) NOT NULL DEFAULT '0',
  `post_links` tinyint(1) NOT NULL DEFAULT '0',
  `post_blogs` tinyint(4) NOT NULL,
  `links` int(10) unsigned NOT NULL DEFAULT '0',
  `articles` int(10) unsigned NOT NULL DEFAULT '0',
  `blogs` int(10) unsigned NOT NULL,
  `reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `rank` tinyint(1) NOT NULL DEFAULT '0',
  `funds_paid` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `funds_incl` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `funds_now` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`n`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'usit_avail_val';
$q[] = "(
  `item_n` int(10) unsigned NOT NULL DEFAULT '0',
  `use_for` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `value_code` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `rank` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `value` (`value_code`),
  KEY `item_n` (`item_n`),
  KEY `use_for` (`use_for`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'usit_list';
$q[] = "(
  `item_n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `use_for` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `search_n` smallint(5) unsigned NOT NULL DEFAULT '0',
  `kind` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `show_na` tinyint(1) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `def_value_text` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `def_value_code` int(10) unsigned NOT NULL DEFAULT '0',
  `rank` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` smallint(5) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `visible_forms` tinyint(1) NOT NULL DEFAULT '0',
  `visible_pages` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_n`),
  KEY `use_for` (`use_for`),
  KEY `search_n` (`search_n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'usit_search';
$q[] = "(
  `use_for` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `all_usit` text CHARACTER SET utf8,
  `v1` text CHARACTER SET utf8,
  `v2` text CHARACTER SET utf8,
  `v3` text CHARACTER SET utf8,
  `v4` text CHARACTER SET utf8,
  `v5` text CHARACTER SET utf8,
  `v6` text CHARACTER SET utf8,
  `v7` text CHARACTER SET utf8,
  `v8` text CHARACTER SET utf8,
  `v9` text CHARACTER SET utf8,
  `v10` text CHARACTER SET utf8,
  `v11` text CHARACTER SET utf8,
  `v12` text CHARACTER SET utf8,
  `v13` text CHARACTER SET utf8,
  `v14` text CHARACTER SET utf8,
  `v15` text CHARACTER SET utf8,
  `v16` text CHARACTER SET utf8,
  `v17` text CHARACTER SET utf8,
  `v18` text CHARACTER SET utf8,
  `v19` text CHARACTER SET utf8,
  `v20` text CHARACTER SET utf8,
  `v21` text CHARACTER SET utf8,
  `v22` text CHARACTER SET utf8,
  `v23` text CHARACTER SET utf8,
  `v24` text CHARACTER SET utf8,
  `v25` text CHARACTER SET utf8,
  `v26` text CHARACTER SET utf8,
  `v27` text CHARACTER SET utf8,
  `v28` text CHARACTER SET utf8,
  `v29` text CHARACTER SET utf8,
  `v30` text CHARACTER SET utf8,
  `v31` text CHARACTER SET utf8,
  `v32` text CHARACTER SET utf8,
  `v33` text CHARACTER SET utf8,
  `v34` text CHARACTER SET utf8,
  `v35` text CHARACTER SET utf8,
  `v36` text CHARACTER SET utf8,
  `v37` text CHARACTER SET utf8,
  `v38` text CHARACTER SET utf8,
  `v39` text CHARACTER SET utf8,
  `v40` text CHARACTER SET utf8,
  `v41` text CHARACTER SET utf8,
  `v42` text CHARACTER SET utf8,
  `v43` text CHARACTER SET utf8,
  `v44` text CHARACTER SET utf8,
  `v45` text CHARACTER SET utf8,
  `v46` text CHARACTER SET utf8,
  `v47` text CHARACTER SET utf8,
  `v48` text CHARACTER SET utf8,
  `v49` text CHARACTER SET utf8,
  `v50` text CHARACTER SET utf8,
  `v51` text CHARACTER SET utf8,
  `v52` text CHARACTER SET utf8,
  `v53` text CHARACTER SET utf8,
  `v54` text CHARACTER SET utf8,
  `v55` text CHARACTER SET utf8,
  `v56` text CHARACTER SET utf8,
  `v57` text CHARACTER SET utf8,
  `v58` text CHARACTER SET utf8,
  `v59` text CHARACTER SET utf8,
  `v60` text CHARACTER SET utf8,
  KEY `use_for` (`use_for`),
  KEY `n` (`n`),
  FULLTEXT KEY `all_usit` (`all_usit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'usit_values';
$q[] = "(
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `use_for` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `item_n` int(10) unsigned NOT NULL DEFAULT '0',
  `value_code` int(10) unsigned NOT NULL DEFAULT '0',
  `value_text` text CHARACTER SET utf8,
  KEY `use_for` (`use_for`),
  KEY `item_n` (`item_n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_favorites';
$q[] = "(
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `what` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_friends';
$q[] = "(
  `user1` int(10) unsigned NOT NULL,
  `user2` int(10) unsigned NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `work1` (`user1`,`accepted`),
  KEY `work2` (`user2`,`accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_private_notes';
$q[] = "(
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `what` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_to_email';
$q[] = "(
  `what` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`what`,`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_wall';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `n` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'videos';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `keywords` text CHARACTER SET utf8,
  `picture` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `map` text CHARACTER SET utf8,
  `c` text CHARACTER SET utf8,
  `c_path` text CHARACTER SET utf8,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `rating` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `youtube_id` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `youtube_length` int(10) unsigned NOT NULL DEFAULT '0',
  `youtube_thumbnail` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `video_code` text CHARACTER SET utf8,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `hits_m` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  `pick` tinyint(1) NOT NULL DEFAULT '0',
  `t1` int(10) unsigned NOT NULL DEFAULT '0',
  `t2` int(10) unsigned NOT NULL DEFAULT '0',
  `status` set('enabled','disabled','queue','wait') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `en_cats` tinyint(1) NOT NULL DEFAULT '0',
  `rewrite_url` text CHARACTER SET utf8,
  PRIMARY KEY (`n`,`status`),
  KEY `title` (`title`),
  KEY `t1` (`t1`),
  KEY `t2` (`t2`),
  KEY `status` (`status`),
  KEY `en_cats` (`en_cats`),
  FULLTEXT KEY `keywords` (`keywords`),
  FULLTEXT KEY `c` (`c`),
  FULLTEXT KEY `c_path` (`c_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

###########################################################################

$tables = count($table);

for ($x=0;$x<=$tables-1;$x++)
{ if (mysql_query("DESCRIBE $s[pr]$table[$x]")) $uzbylo++;
  elseif ($q[$x])
  { $infnum = '';
    $r = mysql_query("CREATE TABLE $s[pr]$table[$x] $q[$x]");
    if (!$r) { $infnum = mysql_errno(); chyba(mysql_error(),0); $chyby++; }
    else hlaseni("Table $s[pr]$table[$x] created.\n");
  }
}
if (!$chyby)
{ if ($uzbylo)
  { if ($uzbylo<$tables)
    hlaseni('<b>Setup created some tables, some tables have been created in the past.</b>');
    elseif ($uzbylo==$tables)
    hlaseni ('<b>Setup did not created any tables, all necessary tables have been created in the past.</b>');
  }
  else hlaseni ('<b>Setup created all necessary tables.</b>');
}
else chyba ('<b>One or more errors found. Cannot continue.<br>Please make sure your database exists or ask yor server admin for help.</b>',1);

###########################################################################

$query = mysql_query("select count(*) from $s[pr]admins where username = 'admin1'");
$pocet = mysql_fetch_row($query);
if (!$pocet[0])
{ $password = md5('admin1');
  mysql_query("INSERT INTO $s[pr]admins VALUES (1,'admin1','$password','none@set.yet','',0)");
$admin_rights = array(
'site_news',
'adlinks',
'ads',
'search_log',
'board',
'newsletter',
'email_users',
'users',
'polls',
'blacklist',
'email_owners',
'categories_news',
'all_news',
'news',
'categories_videos',
'all_videos',
'videos',
'categories_articles',
'all_articles',
'articles',
'categories_blogs',
'all_blogs',
'blogs',
'categories_links',
'all_links',
'links',
'messages',
'templates',
'adv_prices_orders',
'admins',
'database_tools',
'configuration',
'reset_rebuild');
  foreach ($admin_rights as $k=>$v) mysql_query("INSERT INTO $s[pr]admins_rights VALUES (1,'$v')");
  hlaseni('<b>Administrator "admin1" created.</b>');
}

mysql_query("truncate table $s[pr]smilies");
mysql_query("INSERT INTO $s[pr]smilies VALUES(1, ':D', 'biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(2, ':-D', 'biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(3, ':grin:', 'biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(4, ':)', 'smile.gif', 'Smile')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(5, ':-)', 'smile.gif', 'Smile')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(6, ':smile:', 'smile.gif', 'Smile')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(7, ':(', 'sad.gif', 'Sad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(8, ':-(', 'sad.gif', 'Sad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(9, ':sad:', 'sad.gif', 'Sad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(10, ':o', 'surprised.gif', 'Surprised')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(11, ':-o', 'surprised.gif', 'Surprised')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(12, ':eek:', 'surprised.gif', 'Surprised')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(14, ':?', 'confused.gif', 'Confused')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(15, ':-?', 'confused.gif', 'Confused')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(16, ':???:', 'confused.gif', 'Confused')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(17, '8)', 'cool.gif', 'Cool')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(18, '8-)', 'cool.gif', 'Cool')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(19, ':cool:', 'cool.gif', 'Cool')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(21, ':x', 'mad.gif', 'Mad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(22, ':-x', 'mad.gif', 'Mad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(23, ':mad:', 'mad.gif', 'Mad')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(24, ':P', 'razz.gif', 'Razz')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(25, ':-P', 'razz.gif', 'Razz')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(26, ':razz:', 'razz.gif', 'Razz')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(27, ':oops:', 'redface.gif', 'Embarassed')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(30, ':twisted:', 'twisted.gif', 'Twisted Evil')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(31, ':roll:', 'rolleyes.gif', 'Rolling Eyes')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(32, ':wink:', 'wink.gif', 'Wink')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(33, ';)', 'wink.gif', 'Wink')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(34, ';-)', 'wink.gif', 'Wink')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(35, ':!:', 'exclaim.gif', 'Exclamation')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(39, ':|', 'neutral.gif', 'Neutral')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(40, ':-|', 'neutral.gif', 'Neutral')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(41, ':neutral:', 'neutral.gif', 'Neutral')");
mysql_query("INSERT INTO $s[pr]smilies VALUES(42, ':ninja:', 'ninja.gif', 'Ninja')");

mysql_query("truncate table $s[pr]countries");
mysql_query("INSERT INTO $s[pr]countries VALUES('AF', 'Afghanistan', 'Afghanistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AL', 'Albania', 'Albania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DZ', 'Algeria', 'Algeria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AS', 'American Samoa', 'American_Samoa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AD', 'Andorra', 'Andorra.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AO', 'Angola', 'Angola.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AI', 'Anguilla', 'Anguilla.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AG', 'Antigua and Barbuda', 'Antigua_and_Barbuda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AR', 'Argentina', 'Argentina.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AM', 'Armenia', 'Armenia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AW', 'Aruba', 'Aruba.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AU', 'Australia', 'Australia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AT', 'Austria', 'Austria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AZ', 'Azerbaijan', 'Azerbaijan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BS', 'Bahamas', 'Bahamas.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BH', 'Bahrain', 'Bahrain.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BD', 'Bangladesh', 'Bangladesh.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BB', 'Barbados', 'Barbados.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BY', 'Belarus', 'Belarus.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BE', 'Belgium', 'Belgium.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BZ', 'Belize', 'Belize.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BJ', 'Benin', 'Benin.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BM', 'Bermuda', 'Bermuda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BT', 'Bhutan', 'Bhutan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BO', 'Bolivia', 'Bolivia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BA', 'Bosnia and Herzegovina', 'Bosnia_and_Herzegovina.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BW', 'Botswana', 'Botswana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BV', 'Bouvet Island', 'Bouvet_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BR', 'Brazil', 'Brazil.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IO', 'British Indian Ocean Territory', 'British_Indian_Ocean_Territory.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BN', 'Brunei Darussalam', 'Brunei_Darussalam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BG', 'Bulgaria', 'Bulgaria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BF', 'Burkina Faso', 'Burkina_Faso.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MM', 'Burma', 'Burma.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BI', 'Burundi', 'Burundi.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KH', 'Cambodia', 'Cambodia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CM', 'Cameroon', 'Cameroon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CA', 'Canada', 'Canada.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CV', 'Cape Verde', 'Cape_Verde.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KY', 'Cayman Islands', 'Cayman_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CF', 'Central African Republic', 'Central_African_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TD', 'Chad', 'Chad.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CL', 'Chile', 'Chile.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CN', 'China', 'China.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CX', 'Christmas Island', 'Christmas_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CC', 'Cocos (Keeling) Islands', 'Cocos_Keeling_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CO', 'Colombia', 'Colombia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KM', 'Comoros', 'Comoros.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CD', 'Congo, Democratic Republic of the', 'Congo_Democratic_Republic_of_the.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CG', 'Congo, Republic of the', 'Congo_Republic_of_the.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CK', 'Cook Islands', 'Cook_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CR', 'Costa Rica', 'Costa_Rica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CI', 'Cote d&#039;Ivoire', 'Cote_dIvoire.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HR', 'Croatia', 'Croatia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CU', 'Cuba', 'Cuba.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CY', 'Cyprus', 'Cyprus.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CZ', 'Czech Republic', 'Czech_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DK', 'Denmark', 'Denmark.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DJ', 'Djibouti', 'Djibouti.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DM', 'Dominica', 'Dominica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DO', 'Dominican Republic', 'Dominican_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EC', 'Ecuador', 'Ecuador.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EG', 'Egypt', 'Egypt.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SV', 'El Salvador', 'El_Salvador.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GQ', 'Equatorial Guinea', 'Equatorial_Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ER', 'Eritrea', 'Eritrea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EE', 'Estonia', 'Estonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ET', 'Ethiopia', 'Ethiopia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FK', 'Falkland Islands (Malvinas)', 'Falkland_Islands_Malvinas.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FO', 'Faroe Islands', 'Faroe_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FJ', 'Fiji', 'Fiji.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FI', 'Finland', 'Finland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FR', 'France', 'France.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GF', 'French Guiana', 'French_Guiana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PF', 'French Polynesia', 'French_Polynesia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TF', 'French Southern and Antarctic Lands', 'French_Southern_and_Antarctic_Lands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GA', 'Gabon', 'Gabon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GM', 'Gambia', 'Gambia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GE', 'Georgia', 'Georgia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DE', 'Germany', 'Germany.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GH', 'Ghana', 'Ghana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GI', 'Gibraltar', 'Gibraltar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GR', 'Greece', 'Greece.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GL', 'Greenland', 'Greenland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GD', 'Grenada', 'Grenada.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GP', 'Guadeloupe', 'Guadeloupe.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GU', 'Guam', 'Guam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GT', 'Guatemala', 'Guatemala.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GG', 'Guernsey', 'Guernsey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GN', 'Guinea', 'Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GW', 'Guinea-Bissau', 'Guinea-Bissau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GY', 'Guyana', 'Guyana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HT', 'Haiti', 'Haiti.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HM', 'Heard Island and McDonald Islands', 'Heard_Island_and_McDonald_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VA', 'Holy See (Vatican City)', 'Holy_See_Vatican_City.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HN', 'Honduras', 'Honduras.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HK', 'Hong Kong', 'Hong_Kong.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HU', 'Hungary', 'Hungary.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IS', 'Iceland', 'Iceland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IN', 'India', 'India.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ID', 'Indonesia', 'Indonesia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IR', 'Iran', 'Iran.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IQ', 'Iraq', 'Iraq.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IE', 'Ireland', 'Ireland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IM', 'Isle of Man', 'Isle_of_Man.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IL', 'Israel', 'Israel.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IT', 'Italy', 'Italy.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JM', 'Jamaica', 'Jamaica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JP', 'Japan', 'Japan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JE', 'Jersey', 'Jersey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JO', 'Jordan', 'Jordan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KZ', 'Kazakhstan', 'Kazakhstan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KE', 'Kenya', 'Kenya.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KI', 'Kiribati', 'Kiribati.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KR', 'Korea', 'Korea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KP', 'Korea North', 'Korea_North.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KW', 'Kuwait', 'Kuwait.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KG', 'Kyrgyzstan', 'Kyrgyzstan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LA', 'Laos', 'Laos.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LV', 'Latvia', 'Latvia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LB', 'Lebanon', 'Lebanon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LS', 'Lesotho', 'Lesotho.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LR', 'Liberia', 'Liberia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LY', 'Libya', 'Libya.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LI', 'Liechtenstein', 'Liechtenstein.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LT', 'Lithuania', 'Lithuania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LU', 'Luxembourg', 'Luxembourg.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MO', 'Macau', 'Macau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MK', 'Macedonia', 'Macedonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MG', 'Madagascar', 'Madagascar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MW', 'Malawi', 'Malawi.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MY', 'Malaysia', 'Malaysia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MV', 'Maldives', 'Maldives.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ML', 'Mali', 'Mali.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MT', 'Malta', 'Malta.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MH', 'Marshall Islands', 'Marshall_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MQ', 'Martinique', 'Martinique.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MR', 'Mauritania', 'Mauritania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MU', 'Mauritius', 'Mauritius.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('YT', 'Mayotte', 'Mayotte.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MX', 'Mexico', 'Mexico.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FM', 'Micronesia, Federated States of', 'Micronesia_Federated_States_of.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MD', 'Moldova', 'Moldova.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MC', 'Monaco', 'Monaco.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MN', 'Mongolia', 'Mongolia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ME', 'Montenegro', 'Montenegro.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MS', 'Montserrat', 'Montserrat.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MA', 'Morocco', 'Morocco.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MZ', 'Mozambique', 'Mozambique.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NA', 'Namibia', 'Namibia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NR', 'Nauru', 'Nauru.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NP', 'Nepal', 'Nepal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NL', 'Netherlands', 'Netherlands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AN', 'Netherlands Antilles', 'Netherlands_Antilles.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NC', 'New Caledonia', 'New_Caledonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NZ', 'New Zealand', 'New_Zealand.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NI', 'Nicaragua', 'Nicaragua.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NE', 'Niger', 'Niger.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NG', 'Nigeria', 'Nigeria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NU', 'Niue', 'Niue.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NF', 'Norfolk Island', 'Norfolk_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MP', 'Northern Mariana Islands', 'Northern_Mariana_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NO', 'Norway', 'Norway.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('OM', 'Oman', 'Oman.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PK', 'Pakistan', 'Pakistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PW', 'Palau', 'Palau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PS', 'Palestinian Territory', 'Palestinian_Territory.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PA', 'Panama', 'Panama.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PG', 'Papua New Guinea', 'Papua_New_Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PY', 'Paraguay', 'Paraguay.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PE', 'Peru', 'Peru.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PH', 'Philippines', 'Philippines.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PN', 'Pitcairn_Islands', 'Pitcairn_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PL', 'Poland', 'Poland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PT', 'Portugal', 'Portugal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PR', 'Puerto Rico', 'Puerto_Rico.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('QA', 'Qatar', 'Qatar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RE', 'Reunion', 'Reunion.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RO', 'Romania', 'Romania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RU', 'Russia', 'Russia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RW', 'Rwanda', 'Rwanda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SH', 'Saint Helena Ascension and Tristan da Cunha', 'Saint_Helena_Ascension_and_Tristan_da_Cunha.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KN', 'Saint Kitts and Nevis', 'Saint_Kitts_and_Nevis.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LC', 'Saint Lucia', 'Saint_Lucia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PM', 'Saint Pierre and Miquelon', 'Saint_Pierre_and_Miquelon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VC', 'Saint Vincent and the Grenadines', 'Saint_Vincent_and_the_Grenadines.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('WS', 'Samoa', 'Samoa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SM', 'San Marino', 'San_Marino.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ST', 'Sao Tome and Principe', 'Sao_Tome_and_Principe.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SA', 'Saudi Arabia', 'Saudi_Arabia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SN', 'Senegal', 'Senegal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RS', 'Serbia', 'Serbia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SC', 'Seychelles', 'Seychelles.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SL', 'Sierra Leone', 'Sierra_Leone.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SG', 'Singapore', 'Singapore.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SK', 'Slovakia', 'Slovakia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SI', 'Slovenia', 'Slovenia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SB', 'Solomon Islands', 'Solomon_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SO', 'Somalia', 'Somalia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZA', 'South Africa', 'South_Africa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GS', 'South Georgia and South Sandwich Islands', 'South_Georgia_and_South_Sandwich_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ES', 'Spain', 'Spain.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LK', 'Sri Lanka', 'Sri_Lanka.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SD', 'Sudan', 'Sudan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SR', 'Suriname', 'Suriname.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SZ', 'Swaziland', 'Swaziland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SE', 'Sweden', 'Sweden.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CH', 'Switzerland', 'Switzerland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SY', 'Syria', 'Syria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TW', 'Taiwan', 'Taiwan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TJ', 'Tajikistan', 'Tajikistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TZ', 'Tanzania', 'Tanzania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TH', 'Thailand', 'Thailand.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TL', 'Timor-Leste', 'Timor-Leste.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TG', 'Togo', 'Togo.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TK', 'Tokelau', 'Tokelau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TO', 'Tonga', 'Tonga.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TT', 'Trinidad and Tobago', 'Trinidad_and_Tobago.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TN', 'Tunisia', 'Tunisia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TR', 'Turkey', 'Turkey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TM', 'Turkmenistan', 'Turkmenistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TC', 'Turks and Caicos Islands', 'Turks_and_Caicos_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TV', 'Tuvalu', 'Tuvalu.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UG', 'Uganda', 'Uganda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UA', 'Ukraine', 'Ukraine.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AE', 'United Arab Emirates', 'United_Arab_Emirates.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GB', 'United Kingdom', 'United_Kingdom.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('US', 'United States', 'United_States.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UM', 'United States Minor Outlying Islands', 'United_States_Minor_Outlying_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UY', 'Uruguay', 'Uruguay.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UZ', 'Uzbekistan', 'Uzbekistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VU', 'Vanuatu', 'Vanuatu.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VE', 'Venezuela', 'Venezuela.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VN', 'Vietnam', 'Vietnam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VG', 'Virgin Islands, British', 'Virgin_Islands_British.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VI', 'Virgin Islands, U.S.', 'Virgin_Islands_US.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('WF', 'Wallis and Futuna', 'Wallis_and_Futuna.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EH', 'Western Sahara', 'Western_Sahara.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('YE', 'Yemen', 'Yemen.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZM', 'Zambia', 'Zambia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZW', 'Zimbabwe', 'Zimbabwe.png', 0)");
hlaseni("Entered data to table $s[pr]countries.");
what_now();
}

###########################################################################
###########################################################################
###########################################################################

function what_now() {
?>
<br><table width=750 cellpadding=15 cellspacing=0 class="common_table"><tr><td align="center">
<span class="text13a_bold">Link Up Gold has been successfully installed.<br>If all will work fine, delete "setup.php" from your server.</span>
<br>Now continue to your <a target="_blank" href="administration/index.php"><b>administration</b></a>.<br>Use username "admin1" and password "admin1".<br>
Inside the admin area click on link "Configuration" and enter values to all required fields.<br>
</td></tr></table><br><br>
<?PHP
exit;
}

###########################################################################
###########################################################################
###########################################################################

function setup_form() {
show_admin_head();
?>
<form method="POST" action="setup.php">
<table border="0" width="800" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Link Up Gold - Installation</td></tr>
<tr><td align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td colspan=2 align="center" class="cell_embosed1">Enter variables to all these fields.<br>If you don't have a mysql database, ask your server admin to create one for you.</td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1" nowrap>Mysql database host<br><span class="text10">Try to enter "localhost" if you are not sure.</span></td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10"  style="width:550px" name="dbhost"></td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1" nowrap>Mysql database username</td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10"  style="width:550px" name="dbusername"></td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1" nowrap>Mysql database password</td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10"  style="width:550px" name="dbpassword"></td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1" nowrap>Name of your mysql database</td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10" style="width:550px" name="dbname"></td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1" nowrap>Password is not needed<br><span class="text10">It should be unchecked on 99% servers.</span></td>
<td align="left" valign="top" class="cell_embosed1"><input type="checkbox" name="nodbpass" value="1"></td></tr>
<tr>
<td align="left" valign="top" class="cell_embosed1">Prefix of all tables which should be created in the database. English letters only.<br><span class="text10">It is useful if you need install it more than one times and have only one database. Do not change it if you are not sure.</span></td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10" maxLength="10"  style="width:550px" name="pr" value="lug_"></td>
</tr>
<tr>
<td align="left" valign="top" class="cell_embosed1">Full path to the folder where the scripts are located.<br><span class="text10">Value predefined in this field should be correct. Don't change it if you are not sure that it's incorrect. No trailing slash.</span></td>
<td align="left" valign="top" class="cell_embosed1"><INPUT class="field10"  style="width:550px" name="phppath" value="<?PHP echo ereg_replace('/setup.php','',ereg_replace('//','/',str_replace(chr(92), '/',getenv("SCRIPT_FILENAME")))) ?>"><br><span class="text10">Example for Linux: /htdocs/sites/user/folder_name<br>Example for Windows: C:/somefolder/domain.com/folder_name</span></td>
</tr>
<tr>
<td align="middle" width="100%" colSpan=2 class="cell_embosed1"><INPUT type=submit value="Install" name=D1 class="button10"></TD>
</TR></TBODY></TABLE></TD></TR></TABLE></FORM>
</FORM>
<br>
<?PHP
exit();
}

#####################################################################################
#####################################################################################
#####################################################################################

function chyba($text,$fatal) {
echo "<span class=\"text13a_bold\"><b>$text</b></span><br>";
if ($fatal) { echo "<span class=\"text13a_bold\"><b><br>Can't continue!</b></span><br>"; exit(); }
}

#####################################################################################

function hlaseni($text) {
echo ''.$text.'</span><br>';
}

#####################################################################################
#####################################################################################
#####################################################################################

function create_write_file($file,$content,$owerwrite,$chmod,$fatal) {
if ( (!$owerwrite) AND (file_exists($file)) )
{ hlaseni ("File '$file' already exists. Skipping."); return 0; }
if (!$sb = @fopen($file,'w'))
{ chyba ("Unable to create file '$file'. Make sure this directory exists and it has 777 permission.",$fatal);
  return 0; }
$zapis = fwrite ($sb,$content); fclose($sb);
if (!$zapis)
{ chyba ("Cannot write to file '$file'. Make sure this directory exists and it has 777 permission.",$fatal);
  return 0; }
hlaseni ("Created file '$file'.");
if ($chmod) chmod($file,$chmod);
}

#####################################################################################
#####################################################################################
#####################################################################################

function db_connect() {
global $s;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "Unable to connect to the host $s[dbhost]. Check database host, username, password."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
return $link_id;
}

#####################################################################################

function show_admin_head() {
echo stripslashes(str_replace('styles.css','administration/styles.css',str_replace('javascripts.js','administration/javascripts.js',implode('',file('./administration/_head.txt')))));
}

#####################################################################################
#####################################################################################
#####################################################################################


?>