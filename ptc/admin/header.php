<?php
$privelage = getValue("SELECT privelages FROM admins WHERE username = ".quote_smart($_SESSION['admin'])."");
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>PTCShop Administrator | Powerful backend admin user interface</title>
	<link href="style.css" rel="stylesheet" media="all" type="text/css"/>
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/easyTooltip.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
    
    
	<!--[if IE 6]>
	<link href="css/ie6.css" rel="stylesheet" media="all" />
	
	<script src="js/pngfix.js"></script>
	<script>
	  /* EXAMPLE */
	  DD_belatedPNG.fix('.logo, .other ul#dashboard-buttons li a');
	  DD_belatedPNG.fix('.png, .other ul#dashboard-buttons li a');
	</script>
	<![endif]-->
	<!--[if IE 7]>
	<link href="css/ie7.css" rel="stylesheet" media="all" />
	<![endif]-->
</head>
<body>
	<div id="header">
		<div id="top-menu" >
			<?php
            if($privelage === 'superadmin') { ?>
            <a href="index.php?tp=admins" title="Manage Admins">Manage Admins</a>
			| <?php } ?><a href="index.php?tp=editinfo" title="Edit my admin profile">My profile</a> 
			
			<span>Logged in as <a href="index.php?tp=editinfo" title="Logged in as <?php echo $_SESSION['admin']; ?>"><?php echo $_SESSION['admin']; ?></a></span>|
			<a href="index.php?tp=setglobal" title="Settings">Settings</a>
			| <a href="index.php?tp=logout" title="Logout">Logout</a>
		</div>
		<div id="sitename"><a href="index.php?tp=" title="Administration Home" class="logo float-left tooltip"><img src="assets/logo.png" alt="PTCShop Admin" width="254" height="60" class="png" /></a>	
			<?php /*<div class="button float-right">
				<a href="#" id="searchUser_link" class="btn ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Search For a Member</a>
			</div>
			<div id="searchUser" title="Search For a Member">
				<form action="#" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<ul>
						<li>
							<label for="email" class="desc">
								Email:
							</label>
							<div>
								<input type="text" tabindex="1" maxlength="255" value="" class="field text full" name="email" id="email" />
							</div>
						</li>
						<li>
							<label for="password" class="desc">
								Password:
							</label>
							<div>
								<input type="text" tabindex="1" maxlength="255" value="" class="field text full" name="password" id="password" />
							</div>
						</li>
					</ul>
				</form>
			</div>
			<div id="dialog" title="Dialog Title">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			</div>*/ ?>
		</div>
<ul id="navigation" class="sf-navbar" style="z-index:5;">
                    <li><a href="index.php?tp=">Dashboard</a><ul>
                        <li><a href="index.php?tp=setglobal">Website Settings</a></li>
                        <li><a href="index.php?tp=setdesign">Website Design</a></li>
                        <li><a href="index.php?tp=siteactions">Website Pages / Actions</a></li>
                        <li><a href="index.php?tp=menuEditor">Website Menu's</a></li>
                        <li><a href="index.php?tp=translateeditor">Translations Editor</a></li>
                        <li><a href="index.php?tp=payopt">Payment Methods</a></li>
                        <li><a href="index.php?tp=features">Sidebar Editor</a></li>
                        <li><a href="index.php?tp=news">News Archive</a></li>
                        <li><a href="index.php?tp=refcontest">Referral Contest</a></li>
                        <li><a href="index.php?tp=setemails">System Emails</a></li>
                        <li><a href="index.php?tp=country">Manage Countries</a></li>
                        <li><a href="index.php?tp=updateDatabase">Update Database Snapshot</a></li>
                    </ul></li>
                    <li><a href="index.php?tp=manageads">Advertisements</a>
                        <ul>
                            <li><a href="index.php?tp=packSpecials">Manage Packs</a></li>
                            <li><a href="index.php?tp=manageads&adType=email">Email Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=signup">Signup Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=links">Paid to Click Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=survey">Paid to Take Survey's Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=ptrad">Paid to Read Ad's Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=banner">Banner Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=fbanner">Featured Banner Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=fad">Featured Ad Campaigns</a></li>
                            <li><a href="index.php?tp=manageads&adType=flinks">Featured Link Campaigns</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?tp=orderHistory">Order History</a><ul>
                        <li><a href="index.php?tp=orderHistory&viewIncomplete=1">Pending Orders</a></li>
                        <li><a href="index.php?tp=orderHistory">Order History</a></li>
                    </ul></li>
                    <li><a href="index.php?tp=members">Members</a><ul>
                        <li><a href="index.php?tp=members">View Members</a></li>
                        <li><a href="index.php?tp=assignreferrals">Assign Referrals</a></li>
                        <li><a href="index.php?tp=activity">Member Activity</a></li>
                        <li><a href="index.php?tp=convert">Convert Points</a></li>
                        <li><a href="index.php?tp=emailMembers">Email Blast</a></li>
                        <li><a href="index.php?tp=payments">Withdraw Requests</a></li>
                        <li><a href="index.php?tp=chitcontrol">Cheater Control</a></li>
                        <li><a href="index.php?tp=membershiptypes">Membership Management</a></li>
                    </ul></li>
                    <li><a href="index.php?tp=helptips">Help</a>
                        <ul>
                            <li><a href="index.php?tp=aboutScript">About this script</a></li>
                            <!--<li><a href="index.php?tp=helptips">Help and Tips</a></li>-->
                            <li><a href="http://www.ptcshop.com/" target="_blank">Powered By PTCShop.com</a></li>
                        </ul>
                    </li>
                    
                </ul>
	</div>