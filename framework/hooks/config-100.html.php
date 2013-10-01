<?php
/**
 * Defines the configuration variables that are available to the entire site.
 */

////////////////////////////////////////////////////////////////////////////////
// site information

define('SITE_HEAD_TITLE',        'David\'s Portfolio');
define('SITE_BODY_TITLE',        'David Osborn');
define('SITE_AUTHOR',            'David Osborn');
define('SITE_AUTHOR_LINK',       'mailto:davidcosborn@gmail.com');
define('SITE_HOST',              'DreamHost.com');
define('SITE_HOST_LINK',         'http://dreamhost.com/');
define('SITE_CODE_HOST',         'GitHub');
define('SITE_CODE_LINK',         'https://github.com/davidcosborn/portfolio');
define('SITE_CODE_LICENSE',      'MIT license');
define('SITE_CODE_LICENSE_LINK', 'https://github.com/davidcosborn/portfolio/blob/master/LICENSE');

////////////////////////////////////////////////////////////////////////////////
// animation

/**
 * The interval between swapping slides in the slideshow, in seconds.
 */
define('SLIDESHOW_SLIDE_DURATION', 5);

/**
 * The duration of the cross-fade animation used when swapping slides, in
 * seconds.
 */
define('SLIDESHOW_TRANSITION_DURATION', 1);

////////////////////////////////////////////////////////////////////////////////
// constants to export

/**
 * Add the configuration variables created in this script to the list of
 * constants to be exported to client-side scripts.
 */
$GLOBALS['EXPORT'] += array(

	// site information
	'SITE_HEAD_TITLE'                    => SITE_HEAD_TITLE,
	'SITE_BODY_TITLE'                    => SITE_BODY_TITLE,
	'SITE_AUTHOR'                        => SITE_AUTHOR,
	'SITE_AUTHOR_LINK'                   => SITE_AUTHOR_LINK,
	'SITE_HOST'                          => SITE_HOST,
	'SITE_HOST_LINK'                     => SITE_HOST_LINK,
	'SITE_CODE_HOST'                     => SITE_CODE_HOST,
	'SITE_CODE_LINK'                     => SITE_CODE_LINK,
	'SITE_CODE_LICENSE'                  => SITE_CODE_LICENSE,
	'SITE_CODE_LICENSE_LINK'             => SITE_CODE_LICENSE_LINK,

	// animation
	'SLIDESHOW_SLIDE_DURATION'           => SLIDESHOW_SLIDE_DURATION,
	'SLIDESHOW_TRANSITION_DURATION'      => SLIDESHOW_TRANSITION_DURATION);

////////////////////////////////////////////////////////////////////////////////
// fonts

/**
 * An array of fonts to load from Google.  They will be loaded by the
 * framework's font hook.
 */
$GLOBALS['GOOGLE_FONTS'] = array();
?>
