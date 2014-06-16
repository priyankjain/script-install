<!DOCTYPE html>
<html>
	<head>
		<!-- Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- End of Meta -->
		
		<!-- Page title -->
		<title>PTCShop Web Admin - Login</title>
		<!-- End of Page title -->
		
		<!-- Libraries -->
		<link type="text/css" href="./css/login.css" rel="stylesheet" />	
		<link type="text/css" href="./css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
		
		<script type="text/javascript" src="./js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="./js/easyTooltip.js"></script>
		<script type="text/javascript" src="./js/jquery-ui-1.7.2.custom.min.js"></script>
<!--[if lt IE 7]>
            <script type="text/javascript" src="./js/unitpngfix.js"></script>
<![endif]-->
		<!-- End of Libraries -->	
	</head>
	<body>
	<div id="container">
		<div class="logo">
			<a href=""><img src="assets/logo.png" alt="" width="254" height="60" /></a>		</div>
  
<div id="box">
			<form action="index.php" method="POST">
  <input type="hidden" name="action" value="Login to my account">
			<p class="main">
				<label>Username: </label>
				<input type="text" name="username" value="" /> 
				<label>Password: </label>
				<input type="password" name="password" value="">	
			</p>

			<p class="space"><span><FONT COLOR=RED><?php echo $errorToDisplay; ?></FONT></span>
				<input type="submit" value="Login" class="login" />
			</p>
			</form>
		</div>
	</div>
	</body>
</html>