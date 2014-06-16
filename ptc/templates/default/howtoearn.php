<style type="text/css">
<!--
.leftCol { width:470px; float:left; margin:0; padding:0 20px; border-right:1px solid #f2f2f2;}
.rightCol  { width:200px; float:right; margin:0; padding:0 20px;}
-->
</style><?php echo $pageHeader; ?>
        <?php $howtoearn = getValue("SELECT value FROM design WHERE name = 'howtoearn'");
		if($howtoearn != '') {
			echo displayContent($howtoearn);
		} else {
			?><h1><?php echo __('How to Earn with our innovative system'); ?></h1>
        <p>
        <?php echo __('Getting started and earning an income (Part time or full time) with our system is easy! We have built a solid foundation for you to earn through multiple streams of income on a regular basis.'); ?>        </p>
<?php if($disablePTC != '1') { ?>
        <!-- PTC -->
        <div class="blogga">
          <img src="templates/default/images/chart.png" alt="picture" width="64" height="64" style="float: left;" />
          <h2><?php echo __('Paid to View Websites'); ?></h2>
          <?php echo __('Get paid for viewing our advertisers website\'s is one of the easiest and most common way\'s to earn with our system. You simply log into your members back office, and click on the "How to Earn" members link, then from here click on the "Get Paid To Click" blue link. Here you will be able to view and click on the available "Paid to Click" links in our system. These links are updated daily, so check back often for more chances to earn!');?>
          <p></p>
        </div>
<?php } ?>
<?php if($disablePTEMAIL != '1') { ?>
		<!-- PTEMAIL -->
        <div class="blogga"> <img src="templates/default/images/mail_send.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __('Paid to Read Emails'); ?></h2>
          <?php echo __('Get paid for viewing the email\'s we send to you. It\'s simple, we send you an e-mail (If you have paid emails enabled...), when you see an email from us in your inbox, simply open the email, take a moment of your time to read the email and read the message from our advertisers, and then click on the "Paid Link" at the bottom of the advertisers message. You will be shown the advertisers website for a moment, and then credited for receiving that email!'); ?>
        <p></p>
        </div>
<?php } ?>
<?php if($disablePTR != '1') { ?>
		<!-- PTR -->
        <div class="blogga"> <img src="templates/default/images/full_page_dollar.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __('Paid to Read Ad\'s'); ?></h2>
          <?php echo __('Get paid to read text based advertisements.
        When you login to your back office, we have a "Paid to Read Ad\'s" area that has text based advertisements you can read through, and we will pay you for your time. After you read the advertisement, you will click "Completed" and our advertisers website will be shown for a specified amount of time and then you will be credited.'); ?>
          <p></p>
        </div>
<?php } ?>
<?php if($disablePTSURVEY != '1') { ?>
		<!-- PTSURVEY -->
        <div class="blogga"> <img src="templates/default/images/comments.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __('Paid to Take Survey\'s'); ?></h2>
          <?php echo __('Get paid for sharing your opinion with others. Our advertisers have websites, and they want to know what you think about them! That\'s why we pay you to express your opinion about the website you where just shown. Sometimes the survey may be about the website itself, or perhaps the message that you understood from viewing the website (Like what you thought the main purpose of the website was, to sell product, informative, etc) and this helps our advertiser\'s make better websites that are easy to understand.'); ?>
        <p></p>
        </div>
<?php } ?>
<?php if($disablePTS != '1') { ?>
        <!-- PTS -->
        <div class="blogga"> <img src="templates/default/images/accept_page.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __('Paid to Sign Up'); ?></h2>
          <?php echo __('Get paid to join our advertiser\'s websites! 
        Our advertisers want to pay you to join their online programs. Whether it\'s a free or paid membership depends on the advertisers, but one thing remains the same, we will pay you the amount shown next to the link under "You Earn"... Simply sign up to the website listed, paste your "Username / Email" address you used and the welcome letter (for approval, you can remove the sensitive information) and your sign up credit will be added to your account as soon as it has been verified!'); ?>
          <p></p>
        </div>
<?php } ?>
        <div class="blogga"> <img src="templates/default/images/users.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __('Paid to Refer Others'); ?></h2>
          <?php echo __('Get paid to refer friends, family and others.
        When someone signs up through your personalized "Referral URL", that person will be placed in your "Downline" or rather, "Organization". Whatever you call it, it simply means a group of people you have referred. When you refer someone new, and they perform one or more "Paid Tasks", you will get paid a percentage from their efforts! It\'s our little way of saying, Thanks for sharing our system with others!'); ?>
          <p align="center"></p>
        </div>
        <div class="clr"></div>
        
        <p><?php echo __('It\'s easy to get started earning, simply click the button below!'); ?><br />
          <a href="index.php?tp=signup"><img src="templates/default/images/joinButton.png" width="314" height="43" border="0" /></a></p>
        <p></p>
        <p></p>
        <?php
		}
		
		?>
      </div>
    </div>
</div>