<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
session_start();
$username = $_SESSION['login'];
?><html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="ss" method="post" action="index.php">
  <table width="100%" border="0" cellpadding="0">
    <tr>
      <td width="17%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please wait</font>
        <input type="text" name="tt" size="2">
        <font face="Verdana, Arial, Helvetica, sans-serif" size="2">sec. </font></td>
      <td width="83%"><div align="center">
        <?php
		   $id = $_REQUEST['id'];
		   $t = $_REQUEST['t'];
	  	   displayBanner();
	  ?>
        </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> <div align="center">       </div></td>
    </tr>
  </table>
</form>
<form name="finishtask" action="index.php" method="post">
<input type="hidden" name="tp" value="taskptradfinish">
<input type="hidden" name="id" value="<?php echo  $id?>">
<input type="hidden" name="t" value="<?php echo  $t?>">
<input type="hidden" name="username" value="<?php echo  $username?>">
</form>
  
  <script>
var sec=<?php 
echo getAdTimer($id, 'ptrad', $_SESSION['login']);
 ?>;
secund();
function secund()
{
sec--;
document.ss.tt.value=sec;
if(sec==0) document.finishtask.submit();
setTimeout('secund()',1000);
}
</script>
    
</body>
</html>

