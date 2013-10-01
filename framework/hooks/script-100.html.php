<?php
// load SyntaxHighlighter scripts
load_script_if_exists('framework/thirdparty/SyntaxHighlighter/scripts/shCore');
load_script_if_exists('framework/thirdparty/SyntaxHighlighter/scripts/shAutoloader');
?>

<script>
	// run SyntaxHighlighter
	// based on Alex Gorbatchev's example code:
	// http://alexgorbatchev.com/SyntaxHighlighter/manual/api/autoloader.html#example
	function FixPathsForSyntaxHighlighter()
	{
		var results = [];
		for (var i = 0; i < arguments.length; ++i)
			results.push(arguments[i].replace('@', '<?php echo SITE_ROOT_URL?>framework/thirdparty/SyntaxHighlighter/brushes/'));
		return results;
	};
	SyntaxHighlighter.autoloader.apply(null, FixPathsForSyntaxHighlighter(
		'applescript            @shBrushAppleScript.js',
		'actionscript3 as3      @shBrushAS3.js',
		'bash shell             @shBrushBash.js',
		'coldfusion cf          @shBrushColdFusion.js',
		'cpp c                  @shBrushCpp.js',
		'c# c-sharp csharp      @shBrushCSharp.js',
		'css                    @shBrushCss.js',
		'delphi pascal          @shBrushDelphi.js',
		'diff patch pas         @shBrushDiff.js',
		'erl erlang             @shBrushErlang.js',
		'groovy                 @shBrushGroovy.js',
		'java                   @shBrushJava.js',
		'jfx javafx             @shBrushJavaFX.js',
		'js jscript javascript  @shBrushJScript.js',
		'perl pl                @shBrushPerl.js',
		'php                    @shBrushPhp.js',
		'text plain             @shBrushPlain.js',
		'py python              @shBrushPython.js',
		'ruby rails ror rb      @shBrushRuby.js',
		'sass scss              @shBrushSass.js',
		'scala                  @shBrushScala.js',
		'sql                    @shBrushSql.js',
		'vb vbnet               @shBrushVb.js',
		'xml xhtml xslt html    @shBrushXml.js'
	));
	SyntaxHighlighter.all();
</script>

<?php
// auto-load third-party scripts
load_scripts('framework/scripts/thirdparty/auto', ListDirFlags::RECURSIVE);
load_scripts(join_path(PAGE_FROM_SITE_ROOT, 'include/scripts/thirdparty/auto'), ListDirFlags::RECURSIVE);

// auto-load scripts
load_scripts('framework/scripts/auto', ListDirFlags::RECURSIVE);
load_scripts(join_path(PAGE_FROM_SITE_ROOT, 'include/scripts/auto'), ListDirFlags::RECURSIVE);
?>
