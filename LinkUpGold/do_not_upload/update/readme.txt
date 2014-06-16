Link Up Gold - UPDATE FROM VERSION 7.0 TO VERSION 8.0



This update will work only if you already have version 7.0.
If you use an older version, update to the version 7.0 first.



FOLLOW THESE STEPS
1) Create a database backup by using phpMyAdmin. You must do this 
step to be be able to restore the current version if something goes 
wrong. Save structures of all tables as well as data. Download the
backup file to your home/office computer.
2) Download complete folder "data" from your server.
3) If you modified templates, download also folder "styles".
4) Delete all php files in main and administration directories, 
delete the folder "styles".
5) Upload and chmod ALL FILES like when you were install it as 
a first time installation (install instructions are in the manual). 
Replace existing files if needed. Do not upload setup.php. Do not
upload files data/data.php and  data/data_forms.php.
6) Upload file update.php to your main directory and run it. You'll
receive other instructions. Make sure to run each steps only once.
7) Once all steps have been finished, delete file update.php from the 
server and go to administration. First of all edit your admin's account.
There are new rights which you should allow. Hit the submit button 
below the form and go to configuration. Check the values and enter 
values to empty fields. Also check fields for all sections which you 
want to use. It's available in the Configuration form => Visible sections.
Hit submit button below the form. Also go to configuration 
of submit forms, there are some new options.
8) If you use the rewrite option of the HTML Plugin, take new commands and
use them in your .htaccess file.
9) Go to "IP/country data" and import the IP-Country Database.
10) Go to reset/rebuild and run the function "Repair index for searching". Then run daily job.
11) Everything should now work. Check your public and admin pages.
12) If all works fine, you can delete the backup files.



IF SOMETHING GOES WRONG
1) Create a screenshot of the error you received when running the update
script or copy the error message to a text file. Save this image or text
to a file.
2) Delete update.php.
3) Go to phpMyAdmin, delete all tables and restore 
the old data from the backup you created before the update.
4) Now the old version should work again.
5) Send us the file where we can see the error you received as well as 
the database backup file you have. We will advice you or update the
database for you on our server.

