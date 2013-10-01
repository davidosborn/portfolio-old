<?php
include_once 'array.php'; // array_transform
include_once 'file.php'; // list_dir, resolve_file
include_once 'path.php'; // join_path

/**
 * Formats the values of an associative array for export to the LESS compiler.
 */
function format_vars_for_less($vars)
{
	return array_filter(array_transform($vars, function($value)
	{
		switch (gettype($value))
		{
			case 'boolean':
			case 'integer':
			case 'double' :
			case 'string' : return var_export($value, true);
			default       : return NULL;
		}
	}), 'is_set');
}

////////////////////////////////////////////////////////////////////////////////

/**
 * A specialized version of the LESS compiler.
 */
class _lessc extends lessc
{
	function __construct()
	{
		parent::__construct();

		$this->addImportDir(SITE_ROOT_DIR . '/' . join_path(PAGE_FROM_SITE_ROOT, '/include/styles'));
		$this->addImportDir(SITE_ROOT_DIR . '/framework/styles');
		$this->setFormatter('compressed');

		// make constants available to scripts
		$this->setVariables(format_vars_for_less($GLOBALS['EXPORT']));
	}

	/**
	 * Defers to the parent implementation, after inserting an @import
	 * statement for the global configuration file.
	 */
	function compile($string, $name = null)
	{
		$prefix = <<<EOF
@import config.less;
EOF;
		return parent::compile($prefix . $string, $name);
	}
}

/**
 * Returns a single, shared instance of the LESS compiler.
 */
function _lessc()
{
	static $lessc;
	if (!isset($lessc))
		$lessc = new _lessc();
	return $lessc;
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Generates a <link> tag for an external stylesheet.
 */
function load_style($file)
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
	if (ends_with($file, '.less'))
	{
		// determine filenames for generated files
		$less_file = join_path(SITE_ROOT_DIR, $file);
		$cache_file = strtr($file, '/' . DIRECTORY_SEPARATOR, '__');
		$cache_file = join_path(CACHE_FROM_SITE_ROOT, $cache_file);
		$css_file = substr($cache_file, 0, -4) . 'css';
		$css_url = join_path(SITE_ROOT_URL, $css_file);
		$cache_file = join_path(SITE_ROOT_DIR, $cache_file);
		$css_file = join_path(SITE_ROOT_DIR, $css_file);

		// compile LESS file into CSS file when different from cached copy
		if (is_file($cache_file))
		{
			$cache = unserialize(file_get_contents($cache_file));
			$new_cache = _lessc()->cachedCompile($cache);
			if ($new_cache['updated'] > $cache['updated'])
			{
				file_put_contents($cache_file, serialize($cache));
				file_put_contents($css_file, $new_cache['compiled']);
			}
		}
		else
		{
			// this is the first time compiling this file
			$cache = _lessc()->cachedCompile($less_file);
			file_put_contents($cache_file, serialize($cache));
			file_put_contents($css_file, $cache['compiled']);
		}
	}
	else $css_url = join_path(SITE_ROOT_URL, $file);

	// produce <link> tag for CSS file
	echo "<link rel=\"stylesheet\" href=\"$css_url\"/>\n";
}

/**
 * Generates a <link> tag for an external stylesheet, if it exists.
 *
 * @return TRUE if a <link> tag was generated.
 */
function load_style_if_exists($file)
{
	$file = resolve_file($file, STYLE_EXTENSIONS);
	if ($file === FALSE)
		return FALSE;
	load_style($file);
	return TRUE;
}

/**
 * Generates <link> tags for external stylesheets found by searching a path.
 *
 * @param string $dir The directory to search, relative to the site root.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir().
 */
function load_styles($dir, $flags=0)
{
	$files = list_dir($dir, STYLE_PATTERN, ListDirPathType::FROM_SITE_ROOT,
		ListDirFlags::EXPAND_BRACES | $flags);
	foreach ($files as $file)
		load_style($file);
}
?>
