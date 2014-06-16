<?php
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="ss" method="post" action="index.php">
  <table width="100%" border="0" cellpadding="0">
    <tr>
      <td width="16%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Please wait'); ?></font> 
        <input type="text" name="tt" size="2"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <?php echo __('seconds'); ?>
        </font></td>
      <td width="84%"><div align="center">
          <?
	   displayBanner();
	  ?>
        </div></td>
    </tr>
  </table>
</form>
<form name="finishtask" action="index.php" method="post">

<input type="hidden" name="tp" value="taskfinishmail">
<input type="hidden" name="id" value="<?php echo  $username?>">
<input type="hidden" name="mailcode" value="<?php echo  $mailcode?>">
</form>
  
  <script>
var sec=<?php
echo getAdTimer($id, 'email', $_SESSION['login']);
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

