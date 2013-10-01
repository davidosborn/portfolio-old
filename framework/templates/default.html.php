<?php
/**
 * This is the default template, which provides a minimal layout.
 */
?>
<?php header('Content-Type: text/html; charset=UTF-8')?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<?php load_hook('head')?>
	</head>

	<body <?php if (MOBILE) echo 'class="mobile"'?> id="<?php echo PAGE_ID?>">
		<?php load_hook('body')?>
		<?php load_hook('script')?>
	</body>
</html>
