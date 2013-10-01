<?php
/**
 * Display the date/time when the site was last updated.
 */
$file = CACHE_DIR . '/.timestamp';
if (file_exists($file))
{
	$mtime = filemtime($file);
	$seconds = time() - $mtime;

	if ($seconds < 60) // 1 minute
	{
		$mtime = $seconds . ' second' . ($seconds !== 1 ? 's' : '') . ' ago';
	}
	elseif ($seconds < 3600) // 1 hour
	{
		$minutes = (int)($seconds / 60);
		$seconds = $seconds % 60;
		$mtime =
			$minutes . ' minute' . ($minutes !== 1 ? 's' : '') . ', ' .
			$seconds . ' second' . ($seconds !== 1 ? 's' : '') . ' ago';
	}
	elseif ($seconds < 86400) // 1 day
	{
		$hours   = (int)($seconds / 3600);
		$minutes = (int)($seconds / 60) % 60;
		$mtime =
			$hours   . ' hour'   . ($hours   !== 1 ? 's' : '') . ', ' .
			$minutes . ' minute' . ($minutes !== 1 ? 's' : '') . ' ago';
	}
	elseif ($seconds < 604800) // 1 week
	{
		$days  = (int)($seconds / 86400);
		$hours = (int)($seconds / 3600) % 24;
		$mtime =
			$days  . ' day'  . ($days  !== 1 ? 's' : '') . ', ' .
			$hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ago';
	}
	elseif ($seconds < 2419200) // 4 weeks
	{
		$weeks = (int)($seconds / 604800);
		$days  = (int)($seconds / 86400) % 7;
		$mtime =
			$weeks . ' week' . ($weeks !== 1 ? 's' : '') . ', ' .
			$days  . ' day'  . ($days  !== 1 ? 's' : '') . ' ago';
	}
	else $mtime = date('F j, Y', $mtime);
	?>
	<p>
		The website was last updated <?php echo $mtime?>.
	</p>
	<?php
}
?>
