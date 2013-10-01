<?php
include_once 'file.php'; // resolve_file

/**
 * Includes an external HTML file, if it exists.
 *
 * @return TRUE if the file was included.
 */
function load_html_if_exists($file)
{
	$file = resolve_file($file, HTML_EXTENSIONS);
	if ($file === FALSE)
		return FALSE;
	include SITE_ROOT_DIR . '/' . $file;
	return TRUE;
}

/**
 * Includes an external HTML file.
 */
function load_html($file)
{
	if (!load_html_if_exists($file))
		die("File not found: $file");
}
?>
