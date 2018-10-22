<?php
	get_header();
?>

<div id="cspace-browser"></div>
<script src="<?php echo get_post_meta(get_the_ID(), 'script location', true); ?>"></script>
<script>
	cspacePublicBrowser(
		<?php echo get_post_meta(get_the_ID(), 'config', true); ?>
	);
</script>

<?php
	get_footer();
