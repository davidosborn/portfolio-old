<?php
include_once 'function.php'; // call_memoized
include_once 'load_html.php';
include_once 'path.php'; // join_path

////////////////////////////////////////////////////////////////////////////////

/**
 * Loads the specified template.
 */
function load_template($template = 'default')
{
	load_html("framework/templates/$template");
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Flags affecting the behaviour of load_hook().
 */
class HookFlags
{
	/**
	 * Loads only the highest-priority hook.
	 */
	const EXCLUSIVE = 0x01;
};

/**
 * Loads a hook in the current template.
 */
function load_hook($hook, $flags=0)
{
	// search for hook files
	$pattern = sprintf('/(^|\/)%s(|-\d+)\.(%s)$/i', preg_quote($hook), strtr(HTML_EXTENSIONS, ',', '|'));
	$files = array_merge(
		call_memoized('list_dir', 'framework/hooks', $pattern, ListDirPathType::FROM_SITE_ROOT, ListDirFlags::REGULAR_EXPRESSION),
		call_memoized('list_dir', join_path(PAGE_FROM_SITE_ROOT, 'include/hooks'), $pattern, ListDirPathType::FROM_SITE_ROOT, ListDirFlags::REGULAR_EXPRESSION));

	// sort files by priority
	$files_by_priority = array();
	foreach ($files as $i => $file)
	{
		$matches = array();
		$priority = preg_match('/\d+/', $file, $matches) ?
			min(intval($matches[0]), 100) : 50;

		// make sure framework always wins priority battles
		if (starts_with($file, 'framework/'))
		{
			switch ($priority)
			{
				case 0:   $priority = -1;
				case 100: $priority = 101;
			}
		}

		$files_by_priority[$priority] = $file;
	}
	krsort($files_by_priority);
	$files = array_values($files_by_priority);

	// if exclusive, drop all but the highest-priority file
	if ($flags & HookFlags::EXCLUSIVE)
		array_splice($files, 1);

	// include files
	foreach ($files as $file)
		include SITE_ROOT_DIR . '/' . $file;
}
?>
