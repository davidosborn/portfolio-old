<?php
include_once 'array.php'; // array_transform
include_once 'path.php'; // ltrim_path, path_has_extension
include_once 'pattern.php'; // expand_braces, fnmatch_any

/**
 * An enumeration of the path types that can be returned by list_dir().
 */
class ListDirPathType
{
	/**
	 * The path is relative to the search directory.  This is the default.
	 */
	const FROM_SEARCH_ROOT = 0;

	/**
	 * The path is absolute.
	 */
	const ABSOLUTE = 1;

	/**
	 * The path is relative to the document root.
	 */
	const FROM_DOCUMENT_ROOT = 2;

	/**
	 * The path is relative to the site root.
	 */
	const FROM_SITE_ROOT = 3;

	/**
	 * The path is an absolute-path-reference URL.
	 */
	const URL = 4;
};

/**
 * Flags affecting the behaviour of list_dir().
 */
class ListDirFlags
{
	/**
	 * Indicates that the pattern should be processed for brace expansion.
	 */
	const EXPAND_BRACES = 0x01;

	/**
	 * Indicates that the pattern is a regular expression.  Otherwise, the
	 * pattern is a shell pattern.
	 */
	const REGULAR_EXPRESSION = 0x02;

	/**
	 * Recursively searches subdirectories.
	 */
	const RECURSIVE = 0x04;

	/**
	 * Ignores file/directory names that start with a dot.
	 */
	const SKIP_DOT = 0x08;
};

/**
 * Finds all the files in a directory tree on the server.
 */
function list_dir($root, $pattern='', $pathType=0, $flags=0)
{
	// check if the directory exists
	if (!is_dir(join_path(SITE_ROOT_DIR, $root)))
		return array();

	// initialize path prefix for results
	switch ($pathType)
	{
		case ListDirPathType::FROM_SEARCH_ROOT:   $prefix = ''; break;
		case ListDirPathType::ABSOLUTE:           $prefix = join_path(SITE_ROOT_DIR, $root) . '/'; break;
		case ListDirPathType::FROM_DOCUMENT_ROOT: $prefix = join_path(SITE_ROOT_FROM_DOCUMENT_ROOT, $root) . '/'; break;
		case ListDirPathType::FROM_SITE_ROOT:     $prefix = $root . '/'; break;
		case ListDirPathType::URL:                $prefix = join_path(SITE_ROOT_URL, $root) . '/'; break;
		default: die('invalid path type');
	}

	// expand braces into multiple patterns
	$patterns = ($flags & ListDirFlags::EXPAND_BRACES) ?
		expand_braces($pattern) : array($pattern);

	// walk through filesystem tree, starting at root
	$results = array();
	$iter = ($flags & ListDirFlags::RECURSIVE) ?
		new RecursiveIteratorIterator(new RecursiveDirectoryIterator(join_path(SITE_ROOT_DIR, $root))) :
		new DirectoryIterator(join_path(SITE_ROOT_DIR, $root));
	foreach ($iter as $file)
	{
		// ignore '.' and '..'
		if ($iter->isDot()) continue;

		// normalize filename
		$file = ($flags & ListDirFlags::RECURSIVE) ?
			$iter->getInnerIterator()->getSubPathname() :
			$file->getFilename();
		$file = strtr($file, DIRECTORY_SEPARATOR, '/');

		// check filename against patterns
		if ($flags & ListDirFlags::REGULAR_EXPRESSION)
		{
			if (!preg_match_any($patterns, $file))
				continue;
		}
		elseif (!fnmatch_any($patterns, $file))
			continue;

		// add prefix to filename
		$file = $prefix . $file;

		// optionally exclude paths having a dot prefix
		if (($flags & ListDirFlags::SKIP_DOT) &&
			preg_match('/[\/\\\]\\./', $file))
				continue;

		$results[] = $file;
	}

	sort($results);
	return $results;
}

/**
 * Searches for a file with one of the specified extensions, if the path does
 * not already have an extension.
 */
function resolve_file($path, $extensions)
{
	$path = ltrim_path($path);

	if (is_string($extensions))
		$extensions = explode(',', $extensions);

	// if file has no extension, try all known extensions
	$paths = path_has_extension($path) ? array($path) :
		array_transform($extensions,
			function($extension) use ($path) { return "$path.$extension"; });

	// load the first existing file
	foreach ($paths as $path)
		if (file_exists(SITE_ROOT_DIR . '/' . $path))
			return $path;

	// file not found
	return FALSE;
}

/**
 * Create a directory if it doesn't already exist.
 */
function mkdir_if_not_exists($dir)
{
	if (!file_exists($dir))
		mkdir($dir, 0777, TRUE);
}
?>
