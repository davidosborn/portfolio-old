<title>
	<?php load_hook('head-title', HookFlags::EXCLUSIVE)?>
</title>

<?php
// load the most important things first
load_hook('style');
load_hook('font');
load_hook('head-script');
load_hook('favicon');
load_hook('meta');
?>

<base target="_top"/>
