<?php
include_once 'array.php'; // array_transform, traversable_to_array
include_once 'file.php'; // list_dir, resolve_file
include_once 'path.php'; // join_path

/**
 * Formats the values of an associative array for export to Javascript.
 */
function format_vars_for_scripts($vars)
{
	return array_filter(array_transform($vars, function($value)
	{
		switch (gettype($value))
		{
			case 'object':
			if (!$value instanceof Traversable) return NULL;
			$value = traversable_to_array($value);

			case 'array'  : return json_encode($value);
			case 'boolean':
			case 'integer':
			case 'double' :
			case 'string' : return var_export($value, true);
			default       : return NULL;
		}
	}), 'is_set');
}

/**
 * Generates a <script> tag, where the script defines the specified variables
 * in the global scope.
 */
function export_vars_for_scripts($vars)
{
	echo "<script>\n";
	$vars = format_vars_for_scripts($vars);
	foreach ($vars as $key => $value)
		echo "var $key = $value;\n";
	echo "</script>\n";
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Generates a <script> tag for an external script.
 */
function load_script($file)
{
	if (ends_with($file, '.php'))
	{
		// determine filenames for generated files
		$php_file = join_path(SITE_ROOT_DIR, $file);
		$file = strtr($file, '/' . DIRECTORY_SEPARATOR, '__');
		$file = join_path(CACHE_FROM_SITE_ROOT, $file);
		$file = substr($file, 0, -4);

		// parse file with PHP and write to cache
		ob_start();
		include $php_file;
		file_put_contents(join_path(SITE_ROOT_DIR, $file), ob_get_clean());
	}

	// produce <script> tag for file
	$file = join_path(SITE_ROOT_URL, $file);
	echo "<script src=\"$file\"></script>\n";
}

/**
 * Generates a <script> tag for an external script, if it exists.
 *
 * @return TRUE if a <script> tag was generated.
 */
function load_script_if_exists($file)
{
	$file = resolve_file($file, SCRIPT_EXTENSIONS);
	if ($file === FALSE)
		return FALSE;
	load_script($file);
	return TRUE;
}

/**
 * Generates <script> tags for external scripts found by searching a path.
 *
 * @param string $dir The directory to search, relative to the site root.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir().
 */
function load_scripts($dir, $flags=0)
{
	$files = list_dir($dir, SCRIPT_PATTERN, ListDirPathType::FROM_SITE_ROOT,
		ListDirFlags::EXPAND_BRACES | $flags);
	foreach ($files as $file)
		load_script($file);
}
?>
