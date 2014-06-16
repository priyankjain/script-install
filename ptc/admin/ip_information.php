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
 loginCheck(); ?><html>

<body>

<?

$ip=$checkip;

if ($ip!="")

{

        $sock=fsockopen ("whois.ripe.net",43,$errno,$errstr);

        if (!$sock)

        {

                echo ($errstr($errno)."<br>");

        }

        else

        {

                fputs ($sock,$ip."\r\n");

                while (!feof($sock))

                {

                        echo (str_replace(":",":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",fgets ($sock,128))."<br>");

                }

        }

        fclose ($sock);

}

?>



</body>

</html>



