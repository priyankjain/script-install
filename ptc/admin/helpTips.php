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
<style type="text/css">
<!--
.style1 {
	color: #009900;
	font-weight: bold;
}
.style4 {font-size: 18px}
.style5 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>

<h1>Help with the PTCShop Web Script</h1>
<p>Welcome to the &quot;Frequently Asked Questions&quot; portion of the PTCShop web script. I will try and cover some of the basic, and more complex features in this document. Below you will find a list of questions and I will try to answer them with details that I would find helpfull given that I was just starting out with a &quot;Paid to Business&quot;.</p>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">General Questions</a></li>
        <li><a href="#tabs-2">Administration Panel Questions</a></li>
        <li><a href="#tabs-3">Website Questions</a></li>
        <li><a href="#tabs-4">Development Questions</a></li>
    </ul>
    
    <!-- First tab -->
    <div id="tabs-1">
     
<hr>
<h2><strong>General Questions</strong></h2>
<a id="g1"></a><h3><strong>Where do I start?!?</strong></h3>
<p>Well, Here's a good start, so great job on that. I do highly recommend skimming through this page, just so you know what questions there are, should they pop up later, you'll know where to find the answer!<br>
  <br>
Now in order to get started right, it is crucial to know how you can administrate your website. What all you can do, and knowing how to do it (Such as viewing how many members joined today, if there are any cheaters detected waiting manual action, etc), so after you browse this document, take the time to look around your back office page by page. The menu's at the top of the admin panel will give you a good understanding of what you can do right away with your website. <br>
<br>
After you've done this, I recommend completing the following checklist.</p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;Sign up as a member on your own website (For the experience and to create yourself an account)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;Browse your default members back office (May not have any ad's to click yet, need advertisers...)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;Login to your admin panel (Usually located at yoursite.com/admin )<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;Get to know your administration panel, create some &quot;Advertisements&quot;, follow the directions, &quot;Add a link&quot; etc.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;If you have any questions, search this document with <strong>CTRL+F</strong><br>

<p>&nbsp;</p>
<a id="g2"></a><h3><strong>How can I make money with my website?</strong></h3>
<p>Well, the first thing to take note of is, you can LOSE money! <strong>ONLY IF</strong> you don't setup your website right.</p>
<p>You are almost guaranteed to stay in profit if you manage your business properly, and keep a side &quot;Pool&quot; of funds that are reserved for the members. You just have to be carefull with how much you pay out in &quot;Referral Bonus's&quot; and how much you pay &quot;Per Click&quot; that members receive, and how much you are charging for that click.</p>
<p>IE: Bad Idea<br>
If you sell 1,000,000 Paid to Click credits for <?php echo $setupinfo['currency']; ?>1 . Then turn around and set each click to earn <?php echo $setupinfo['currency']; ?>0.01 each, </p>
<p>Then it's easy to understand your members would earn <?php echo $setupinfo['currency']; ?>0.01 * 1,000,000 = <?php echo $setupinfo['currency']; ?>10,000 (the number of clicks in that <?php echo $setupinfo['currency']; ?>1 ad pack).</p>
<p>So you receive <?php echo $setupinfo['currency']; ?>1, and payout <?php echo $setupinfo['currency']; ?>10,000 ... <span class="style5">THAT WAS A BAD IDEA!</span></p>
<p><strong>Now for the Good Example!</strong><br>
Say you charge <?php echo $setupinfo['currency']; ?>10 per 100 Paid to Click credits. Then pay your members <?php echo $setupinfo['currency']; ?>0.005 to click on one of those &quot;credits&quot;.</p>
<p>You would have <?php echo $setupinfo['currency']; ?>0.005 * 100 (<?php echo $setupinfo['currency']; ?>0.50) to pay out, <span class="style1">leaving you <span class="style4"><?php echo $setupinfo['currency']; ?>9.50 Profit!</span> GREAT IDEA!</span> </p>
<p>So to put it simply, make sure that the (<?php echo $setupinfo['currency']; ?> Pay Per Click * Number of Credits) - (<?php echo $setupinfo['currency']; ?> Cost of Pack) &gt; 0</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div>
    <div id="tabs-2"><p>Administration Panel Questions....</p>
    <p><a href="#g1">Where do I start?</a><br>
      <a href="#g2">How can I make money with my website?</a></p></div>
    <div id="tabs-3"><p>Website Questions....</p>
    <p><a href="#g1">Where do I start?</a><br>
      <a href="#g2">How can I make money with my website?</a></p></div>
    <div id="tabs-4"><p>Development Questions....</p>
    <p><a href="#g1">Where do I start?</a><br>
      <a href="#g2">How can I make money with my website?</a></p></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>