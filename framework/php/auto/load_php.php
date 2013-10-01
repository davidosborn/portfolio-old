<?php
include_once 'file.php'; // list_dir
include_once 'path.php'; // join_path

/**
 * Includes an external PHP file.
 */
function load_php_file($file)
{
	include_once join_path(SITE_ROOT_DIR, $file);
}

/**
 * Includes an external PHP file, if it exists.
 */
function load_php_file_if_exists($file)
{
	// if file has no extension, use default extension
	if (strpos($file, '.') === FALSE)
		$file .= '.php';

	if (is_file(join_path(SITE_ROOT_DIR, $file)))
		load_php_file($file);
}

/**
 * Includes external PHP files found by searching a path.
 *
 * @param string $dir The directory to search, relative to the site root.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir().
 */
function load_php_files($dir, $flags=0)
{
	$files = list_dir($dir, '*.php', ListDirPathType::FROM_SITE_ROOT, $flags);
	foreach ($files as $file)
		load_php_file($file);
}
?>
