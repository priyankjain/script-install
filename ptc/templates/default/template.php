<?php

//BASE FILE FOR INCLUSION INTO EVERY FILE FOR THIS TEMPLATE
//TEMPLATE NAME
$thisTemplateName = 'default';

//INCLUDED AT TOP OF CONTENT PAGES
$pageHeader = '
<div class="body1"><div class="body">
    <div class="body_resize">
		<div ';
if($sideBarLeft == 0) $pageHeader .= 'class="left"'; else $pageHeader .= 'class="right"';
$pageHeader .= '>';

//INCLUDED AT BOTTOM OF CONTENT PAGES
$pageFooter = '</div></div></div>';




?>