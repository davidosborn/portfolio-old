<?php
// load SyntaxHighlighter stylesheets
load_style_if_exists('framework/thirdparty/SyntaxHighlighter/styles/shCore');
load_style_if_exists('framework/thirdparty/SyntaxHighlighter/styles/shThemeDefault');
?>

<style>
	/* adjust SyntaxHighlighter styling */
	.syntaxhighlighter
	{
		padding-bottom: 16px !important;
		padding-top:    16px !important;
	}
</style>

<?php
// auto-load third-party stylesheets
load_styles('framework/styles/thirdparty/auto', ListDirFlags::RECURSIVE);
load_styles(join_path(PAGE_FROM_SITE_ROOT, 'include/styles/thirdparty/auto'), ListDirFlags::RECURSIVE);

// auto-load stylesheets
load_styles('framework/styles/auto', ListDirFlags::RECURSIVE);
load_styles(join_path(PAGE_FROM_SITE_ROOT, 'include/styles/auto'), ListDirFlags::RECURSIVE);
?>
