<?php echo $pageHeader;
$tags = buildPageTags();
$tags['{PAGE_NAME}'] = __('Frequently Asked Questions');

?>
        
        <h1><?php echo __(getValue("SELECT pageName FROM siteactions WHERE tp = ".quote_smart($tp)."")); ?></h1>
		<p>
		<?php echo displayContent(getValue("SELECT `value` FROM design WHERE `name` = ".quote_smart($tp).""),$tags); ?>
        </p>
<?php echo $pageFooter; ?>