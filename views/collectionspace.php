<?php
	get_header();
?>

<div id="cspace-browser"></div>
<script src="<?= CollectionSpace::get_browser_script_url() ?>"></script>
<script>
	cspacePublicBrowser(<?= CollectionSpace::get_browser_config() ?>);
</script>

<?php
	get_footer();
