<?php
/**
 * Defines the configuration variables used by the main page.
 *
 * @note This file is loaded automatically during initialization.
 */

////////////////////////////////////////////////////////////////////////////////
// sizing

/**
 * The maximum width of the project-content iframe.
 */
define('PROJECT_CONTENT_MAX_WIDTH', '650px');

/**
 * The minimum width of the project-content iframe.
 */
define('PROJECT_CONTENT_MIN_WIDTH', '300px');

/**
 * The width of a project-information box, which is to the right of each
 * thumbnail.
 */
define('PROJECT_INFO_WIDTH', '100px');

/**
 * The height of a project thumbnail.
 */
define('PROJECT_THUMBNAIL_HEIGHT', '120px');

/**
 * The width of a project thumbnail.
 */
define('PROJECT_THUMBNAIL_WIDTH', '120px');

////////////////////////////////////////////////////////////////////////////////
// projects

/**
 * An array of all of the projects, ordered by presentability.
 */
$GLOBALS['PROJECTS'] = array_array2object(array(
	array(
		'id'       => 'page',
		'title'    => 'Portable Adventure Game Engine',
		'authors'  => array('David Osborn'),
		'desc'     => 'A 3D point-and-click adventure-game engine that I started at ACAD and have continued to work on.  This is my personal masterpiece.',
		'dir'      => 'projects/page',
		'tags'     => array('3d', 'acad', 'art', 'code', 'personal'),
		'tech'     => array('C++11', 'OpenGL', 'Lua', 'Boost', 'Bash', 'more...')),
	array(
		'id'       => 'explorer',
		'title'    => 'Explorer',
		'authors'  => array('David Osborn', 'Helen He'),
		'desc'     => 'An interactive 3D visualization of a procedurally-generated environment, developed at ACAD in collaboration with Helen He of the University of Calgary.',
		'dir'      => 'projects/explorer',
		'tags'     => array('3d', 'acad', 'art', 'code'),
		'tech'     => array('C++', 'OpenGL', 'SDL', 'GLEW')),
	array(
		'id'       => 'travelexperts',
		'title'    => 'Travel Experts',
		'authors'  => array(
			array('David Osborn', 'Frank (Xing) Cai', 'Robyn Hernandez', 'Silviu Bobea'),
			array('Abhinash Wijesinghe', 'David Osborn', 'Ignatius Vincent', 'Matt Dyrholm'),
			array('David Osborn', 'Meena Gupta', 'Olaniyi Bankole', 'Sylvie Lu')),
		'desc'     => 'A team project at SAIT, where we created a website and administrative application for a hypothetical travel agency.',
		'dir'      => 'projects/travelexperts',
		'tags'     => array('code', 'sait', 'web'),
		'tech'     => array(
			array('PHP', 'MySQL', 'HTML', 'CSS', 'Javascript'),
			array('ASP.NET', 'C#', 'CSS', 'HTML', 'Javascript', 'SQL Server'),
			array('CSS', 'HTML', 'JSP', 'Java', 'Javascript', 'Oracle Express'))),
	array(
		'id'       => 'thenaturalcleaningcompany',
		'title'    => 'The Natural Cleaning Company',
		'authors'  => array('David Osborn'),
		'desc'     => 'The website I created for a local cleaning service.',
		'dir'      => 'projects/naturalcleaning',
		'tags'     => array('code', 'personal', 'web'),
		'tech'     => array('PHP', 'HTML', 'CSS', 'Javascript')),
	array(
		'id'       => 'portfolio',
		'title'    => 'Portfolio',
		'authors'  => array('David Osborn'),
		'desc'     => 'A portfolio website to supplement my resume.  The best example I have of my web development skills.',
		'dir'      => 'projects/portfolio',
		'tags'     => array('code', 'personal', 'sait', 'web'),
		'tech'     => array('PHP', 'HTML5', 'CSS3', 'Javascript', 'LESS')),
	array(
		'id'       => 'ftpmirror',
		'title'    => 'FTP Mirror',
		'authors'  => array('David Osborn'),
		'desc'     => 'A Python script to mirror a local directory to a server over plain FTP.',
		'dir'      => 'projects/ftpmirror',
		'tags'     => array('code', 'personal', 'sait', 'util', 'web'),
		'tech'     => array('Python')),
	array(
		'id'       => 'chickenassault',
		'title'    => 'Chicken Assault',
		'authors'  => array('David Osborn'),
		'desc'     => 'A small 2D Flash game that I created for a school project at ACAD.',
		'dir'      => 'projects/chickenassault',
		'tags'     => array('acad', 'code', 'web'),
		'tech'     => array('Flash', 'ActionScript')),
	/*array(
		'id'       => 'gitlog2html',
		'title'    => 'GitLog2HTML',
		'authors'  => array('David Osborn'),
		'desc'     => 'A script to generate a web page showing the history of a Git repository.',
		'dir'      => 'projects/gitlog2html',
		'tags'     => array('code', 'sait', 'util'),
		'tech'     => array('Bash', 'HTML', 'jQuery')),*/
	array(
		'id'       => '3d',
		'title'    => '3D Animation',
		'authors'  => array('David Osborn'),
		'desc'     => 'Some 3D models and animations I have made.',
		'dir'      => 'projects/3d',
		'tags'     => array('3d', 'acad', 'art', 'personal'),
		'tech'     => array('Wings3D', 'Blender')),
	array(
		'id'       => 'drawings',
		'title'    => 'Drawings',
		'authors'  => array('David Osborn'),
		'desc'     => 'Various life drawings I have done.',
		'dir'      => 'projects/drawings',
		'tags'     => array('art', 'personal'),
		'tech'     => array('paper', 'pencil'))
	), 'id');

/**
 * An array of tags that can be used to categorize projects.
 */
$GLOBALS['TAGS'] = array_array2object(array(
	array(
		'id'      => '3d',
		'title'   => '3D',
		'applies' => NULL),
	array(
		'id'      => 'acad',
		'title'   => 'ACAD',
		'applies' => NULL),
	array(
		'id'      => 'art',
		'title'   => 'Art',
		'applies' => NULL),
	array(
		'id'      => 'code',
		'title'   => 'Programming',
		'applies' => NULL),
	array(
		'id'      => 'collab',
		'title'   => 'Collaboration',
		'applies' => function($project) {
			return isset($project->authors) && count($project->authors) > 1; }),
	array(
		'id'      => 'personal',
		'title'   => 'Personal',
		'applies' => NULL),
	array(
		'id'      => 'sait',
		'title'   => 'SAIT',
		'applies' => NULL),
	array(
		'id'      => 'web',
		'title'   => 'Web',
		'applies' => NULL),
	array(
		'id'      => 'util',
		'title'   => 'Tools',
		'applies' => NULL)
	), 'id');

/**
 * Perform some additional initialization.
 */
call_user_func(function()
{
	// apply tags to projects
	foreach ($GLOBALS['PROJECTS'] as $project)
		foreach ($GLOBALS['TAGS'] as $tag)
			if (isset($tag->applies))
			{
				$applies = $tag->applies;
				if ($applies($project) && !isset($project->tags[$tag->id]))
					array_push($project->tags, $tag->id);
			}

	// sort tags
	uasort($GLOBALS['TAGS'], function($a, $b) {
		return strcmp(
			$a->title,
			$b->title); });
	foreach ($GLOBALS['PROJECTS'] as $project)
		uasort($project->tags, function($a, $b) {
			return strcmp(
				$GLOBALS['TAGS'][$a]->title,
				$GLOBALS['TAGS'][$b]->title); });
});

////////////////////////////////////////////////////////////////////////////////
// pages

/**
 * The default page for the iframe.
 */
define('WELCOME_PAGE', 'welcome');

////////////////////////////////////////////////////////////////////////////////
// constants to export

/**
 * Add the configuration variables created in this script to the list of
 * constants to be exported to client-side scripts.
 */
$GLOBALS['EXPORT'] += array(

	// sizing
	'PROJECT_CONTENT_MIN_WIDTH'          => PROJECT_CONTENT_MIN_WIDTH,
	'PROJECT_CONTENT_MAX_WIDTH'          => PROJECT_CONTENT_MAX_WIDTH,
	'PROJECT_INFO_WIDTH'                 => PROJECT_INFO_WIDTH,
	'PROJECT_THUMBNAIL_WIDTH'            => PROJECT_THUMBNAIL_WIDTH,
	'PROJECT_THUMBNAIL_HEIGHT'           => PROJECT_THUMBNAIL_HEIGHT,

	// pages
	'WELCOME_PAGE'                       => WELCOME_PAGE);

////////////////////////////////////////////////////////////////////////////////
// fonts

/**
 * Add to the array of fonts to load from Google.
 */
$GLOBALS['GOOGLE_FONTS'] += array();
?>
