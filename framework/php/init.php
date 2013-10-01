<?php
/**
 * Initializes the framework.
 *
 * @note This script must be included at the very beginning of every page to
 *       initialize the framework that drives the site.
 */

////////////////////////////////////////////////////////////////////////////////

include_once 'auto/php.php'; // must be included before any other PHP files

include_once 'auto/file.php'; // mkdir_if_not_exists
include_once 'auto/load_php.php'; // load_php_files
include_once 'auto/path.php'; // normalize_path, path_{is_under,relative_to}
include_once 'auto/string.php'; // remainder_if_ends_with
include_once 'auto/template.php'; // load_hook

////////////////////////////////////////////////////////////////////////////////

/**
 * @defgroup options
 *
 * A page may define any of these constants before including this file to
 * override their default values.
 *
 * @{
 */

/**
 * TRUE if the current page should be cached.
 */
if (!defined('CACHE_PAGE'))
	define('CACHE_PAGE', TRUE);

///@}

////////////////////////////////////////////////////////////////////////////////

/**
 * The document root, as an absolute filesystem path.
 */
define('DOCUMENT_ROOT', rtrim(normalize_path($_SERVER['DOCUMENT_ROOT']), '/'));

/**
 * The site root, as an absolute filesystem path.
 *
 * @note The relative location of this file within the site is used as a frame
 *       of reference to calculate the value of this constant.  If this file is
 *       moved within the site, the calculation will need to be updated.
 */
define('SITE_ROOT_DIR', normalize_path(__FILE__ . '/../../..'));

/**
 * The site root, relative to the document root.
 */
define('SITE_ROOT_FROM_DOCUMENT_ROOT', call_user_func(function()
{
	/**
	 * If the site root is under the document root, we can just return the
	 * difference.
	 */
	if (path_is_under(SITE_ROOT_DIR, DOCUMENT_ROOT))
		return path_relative_to(SITE_ROOT_DIR, DOCUMENT_ROOT);

	/**
	 * For site roots that are not under the document root, which may be the
	 * case for Apache aliases, we must go a little further...
	 */
	$diff = remainder_if_ends_with(
		normalize_path($_SERVER['SCRIPT_NAME']),
		path_relative_to(normalize_path($_SERVER['SCRIPT_FILENAME']), SITE_ROOT_DIR));
	if ($diff !== FALSE)
		return trim($diff, '/');

	// if the site root cannot be computed, raise an error
	die('failed to compute site root relative to document root');
}));

/**
 * The site root, as a local URL (that is, an absolute-path reference).  It will
 * be relative to the document root, and it will include a leading and trailing
 * path separator.
 */
define('SITE_ROOT_URL', '/' . SITE_ROOT_FROM_DOCUMENT_ROOT . '/');

/**
 * The directory of the current page, relative to the site root.
 */
define('PAGE_FROM_SITE_ROOT', call_user_func(function()
{
	$path = dirname(
		path_relative_to(
			normalize_path($_SERVER['SCRIPT_NAME']),
			SITE_ROOT_URL));
	return $path === '.' ? '' : $path;
}));

/**
 * The directory of the current page, as an absolute filesystem path.
 */
define('PAGE_DIR', join_path(SITE_ROOT_DIR, PAGE_FROM_SITE_ROOT));

/**
 * The directory of the current page, as a local URL.
 */
define('PAGE_URL', join_path(SITE_ROOT_URL, PAGE_FROM_SITE_ROOT) . '/');

/**
 * The directory where cached files are stored, relative to the site root.
 */
define('CACHE_FROM_SITE_ROOT', 'framework/cache');

/**
 * The directory where cached files are stored, as an absolute filesystem path.
 */
define('CACHE_DIR', SITE_ROOT_DIR . '/' . CACHE_FROM_SITE_ROOT);

/**
 * The directory where cached files are stored, as a local URL.
 */
define('CACHE_URL', SITE_ROOT_URL . CACHE_FROM_SITE_ROOT . '/');

/**
 * The ID of the current page.
 */
define('PAGE_ID', strtr(PAGE_FROM_SITE_ROOT, '/', '_') ?: 'root');

/**
 * A file containing a cached copy of the current page, as an absolute
 * filesystem path.
 */
define('CACHED_PAGE', CACHE_DIR . '/' . PAGE_ID . '.html');

/**
 * TRUE if the user is on a mobile device.
 */
define('MOBILE', call_user_func(function()
{
	include_once 'thirdparty/Mobile_Detect.php';

	$md = new Mobile_Detect;
	return $md->isMobile();
}));

////////////////////////////////////////////////////////////////////////////////

/**
 * The recognized extensions for external HTML files.
 */
define('HTML_EXTENSIONS', 'html,html.php,php,shtml,shtml.php');

/**
 * The shell pattern to use when searching for external HTML files.
 */
define('HTML_PATTERN', '*.{' . HTML_EXTENSIONS . '}');

/**
 * The recognized extensions for external scripts.
 */
define('SCRIPT_EXTENSIONS', 'js,js.php,php');

/**
 * The shell pattern to use when searching for external scripts.
 */
define('SCRIPT_PATTERN', '*.{' . SCRIPT_EXTENSIONS . '}');

/**
 * The recognized extensions for external stylesheets.
 */
define('STYLE_EXTENSIONS', 'css,css.php,less,less.php,php');

/**
 * The shell pattern to use when searching for external stylesheets.
 */
define('STYLE_PATTERN', '*.{' . STYLE_EXTENSIONS . '}');

////////////////////////////////////////////////////////////////////////////////

/**
 * An array of constants that may be exported to client-side scripts, such as
 * Javascript and LESS.  Constants that are not useful to the client or that may
 * pose a security risk should not be listed here.
 */
$GLOBALS['EXPORT'] = array(
	'SITE_ROOT_FROM_DOCUMENT_ROOT'       => SITE_ROOT_FROM_DOCUMENT_ROOT,
	'SITE_ROOT_URL'                      => SITE_ROOT_URL,
	'PAGE_FROM_SITE_ROOT'                => PAGE_FROM_SITE_ROOT,
	'PAGE_URL'                           => PAGE_URL,
	'CACHE_FROM_SITE_ROOT'               => CACHE_FROM_SITE_ROOT,
	'CACHE_URL'                          => CACHE_URL,

	'PAGE_ID'                            => PAGE_ID,
	'MOBILE'                             => MOBILE,

	'HTML_EXTENSIONS'                    => HTML_EXTENSIONS,
	'HTML_PATTERN'                       => HTML_PATTERN,
	'SCRIPT_EXTENSIONS'                  => SCRIPT_EXTENSIONS,
	'SCRIPT_PATTERN'                     => SCRIPT_PATTERN,
	'STYLE_EXTENSIONS'                   => STYLE_EXTENSIONS,
	'STYLE_PATTERN'                      => STYLE_PATTERN);

////////////////////////////////////////////////////////////////////////////////

// initialize cache
call_user_func(function()
{
	// create cache directory if it doesn't already exist
	mkdir_if_not_exists(CACHE_DIR);

	/**
	 * Purge the cache if the hostname changes, which may happen if the site is
	 * copied naively to another server.
	 */
	$hostname = getenv('HOSTNAME') ?: getenv('COMPUTERNAME');
	$hostname_file = CACHE_DIR . '/.hostname';
	if (!file_exists($hostname_file) ||
		file_get_contents($hostname_file) !== $hostname)
	{
		foreach (glob(CACHE_DIR . '/*') as $file)
			unlink($file);
		file_put_contents($hostname_file, $hostname);
	}

	// prefer to load the current page from the cache
	if (CACHE_PAGE &&
		file_exists(CACHED_PAGE) &&
		file_exists(CACHE_DIR . '/.timestamp') &&
		filemtime(CACHED_PAGE) >= filemtime(CACHE_DIR . '/.timestamp') &&
		readfile(CACHED_PAGE) !== FALSE)
			exit;
});

////////////////////////////////////////////////////////////////////////////////

// use output buffering to do some HTML post-processing and caching
ob_start(function($s)
{
	/**
	 * Remove whitespace between HTML tags.  This is done to avoid the single-
	 * space text-spans that browsers sometimes render when there is whitespace
	 * between HTML tags.
	 */
	/*$regex_lookbehind_tag  = '(?<=[a-z0-9\\/"\'?]>)';
	$regex_lookbehind_text = '(?<!^\\s)';
	$regex_lookahead_tag   = '(?=<[a-z\\/?!])';
	$regex_lookahead_text  = '(?!^\\s)';

	$s = preg_replace(
		array(
			"/$regex_lookbehind_tag\\s+$regex_lookahead_tag/",
			"/$regex_lookbehind_tag\\s+$regex_lookahead_text/",
			"/$regex_lookbehind_text\\s+$regex_lookahead_tag/"),
		array('', ' ', ' '), $s);

	// merge adjacent whitespace
	$s = preg_replace('/\\s+/', ' ', $s);*/

	// compress with gzip
	/*$s = gzencode($s);
	header('Content-Encoding: gzip');
	header('Content-Length: ' . strlen($s));*/

	// store page in cache
	if (CACHE_PAGE)
		file_put_contents(CACHED_PAGE, ob_get_contents());

	return $s;
});

////////////////////////////////////////////////////////////////////////////////

// initialize session before loading PHP files, in case they need it
session_start();

////////////////////////////////////////////////////////////////////////////////

// auto-load third-party PHP files
load_php_files('framework/php/thirdparty/auto', ListDirFlags::RECURSIVE);
load_php_files(join_path(PAGE_FROM_SITE_ROOT, 'include/php/thirdparty/auto'), ListDirFlags::RECURSIVE);

// auto-load PHP files
load_php_files('framework/php/auto', ListDirFlags::RECURSIVE);
load_php_files(join_path(PAGE_FROM_SITE_ROOT, 'include/php/auto'), ListDirFlags::RECURSIVE);

////////////////////////////////////////////////////////////////////////////////

// load configuration
load_hook('config');
?>
