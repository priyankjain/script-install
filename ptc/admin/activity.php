<?php
//
// COPYRIGHT 2010 PTCSHOP.COM - WRITTEN BY ZACK MYERS ocnod1234@yahoo.com
// RESALE OF THIS WEB SCRIPT IS STRICTLY FORBIDDEN
// I DID NOT ENCRYPT IT FOR YOUR PERSONAL GAIN,
// SO PLEASE DON'T SELL OR GIVE AWAY MY WORK :-)
//
// THIS FILE IS ONLY FOR ADVANCED USERS TO MODIFY
//
// FOR BASIC CONFIGURATION, PLEASE MODIFY include/cfg.php
//
//
// --------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------
// unless you know what your doing :)
//
 loginCheck(); ?> 

<br>
<div align="center">
        <form name="form1" method="post" action="index.php">
          <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
           <thead><tr>
              <td>MEMBER'S ACTIVITY control</td>
            </tr>
            </thead><tbody>
            <tr>
              <td>Member Username:
            <input type="text" name="username" size="25">
                  <input type="hidden" name="act" value="activity">
                  <input type="hidden" name="tp" value="activity">
                  <input type="submit" name="Submit" value="Create member activities list">
              </td>
              </tbody>
            </tr>
          </table>
        </form>
        <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
          <thead>
          <tr>
            <td>Read emails activity</td>
            <td>Clicks activity</td>
            <td>Signup activity</td>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?

if($act=='activity')

{

$sql=mysql_query("SELECT * FROM activity WHERE username=".quote_smart($username)." AND ftask='ptr' ORDER BY fdate DESC");

for($i=0;$i<mysql_num_rows($sql); $i++)

{

mysql_data_seek($sql,$i);

extract(mysql_fetch_array($sql));

echo"<tr><td>$fdate<td></tr>";

}
if(mysql_num_rows($sql) == 0) echo "<tr><td>No Activity</td></tr>";
}



?>
            </table></td>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?

if($act=='activity')

{

$sql=mysql_query("SELECT * FROM activity WHERE username=".quote_smart($username)." AND ftask='ptc' ORDER BY fdate DESC");

for($i=0;$i<mysql_num_rows($sql); $i++)

{

mysql_data_seek($sql,$i);

extract(mysql_fetch_array($sql));

echo"<tr><td>$fdate<td></tr>";

}
if(mysql_num_rows($sql) == 0) echo "<tr><td>No Activity</td></tr>";
}



?>
            </table></td>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?

if($act=='activity')

{

$sql=mysql_query("SELECT * FROM activity WHERE username=".quote_smart($username)." AND ftask='pts' ORDER BY fdate DESC") or die(mysql_error());
for($i=0;$i<mysql_num_rows($sql); $i++)

{

mysql_data_seek($sql,$i);

extract(mysql_fetch_array($sql));

echo"<tr><td>$fdate<td></tr>";

}
if(mysql_num_rows($sql) == 0) echo "<tr><td>No Activity</td></tr>";

}



?>
            </table></td>
          </tr>
          </tbody>
        </table>
    </div>

