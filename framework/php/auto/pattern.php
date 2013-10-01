<?php
/**
 * Expands the curly-brace substrings in an extended shell pattern to produce
 * multiple fnmatch-compatible patterns.
 */
function expand_braces($pattern)
{
	$expanded_patterns = array();
	$new_patterns = array($pattern);
	while ($new_patterns)
	{
		$old_patterns = $new_patterns;
		$new_patterns = array();
		foreach ($old_patterns as $pattern)
			if (($start = strrpos($pattern, '{')) !== FALSE &&
				($end = strpos($pattern, '}', $start + 1)) !== FALSE)
			{
				$subs = explode(',', substr($pattern, $start + 1, $end - $start - 1));
				foreach ($subs as $sub)
					$new_patterns[] = substr($pattern, 0, $start) . $sub . substr($pattern, $end + 1);
			}
			else $expanded_patterns[] = $pattern;
	}
	return $expanded_patterns;
}

/**
 * Returns TRUE if fnmatch() matches one of an array of patterns.
 */
function fnmatch_any($patterns, $string, $flags=0)
{
	if (function_exists('fnmatch'))
	{
		foreach ($patterns as $pattern)
			if (fnmatch($pattern, $string, $flags))
				return TRUE;
	}
	else
		// FIXME: parse flags to simulate fnmatch
		foreach ($patterns as $pattern)
		{
			$replacements = array('/' => '\/', '\*' => '.*');
			$pattern = '/^' . strtr(preg_quote($pattern), $replacements) . '$/';
			if (preg_match($pattern, $string))
				return TRUE;
		}
	return FALSE;
}

/**
 * Returns TRUE if preg_match() matches one of an array of patterns.
 */
function preg_match_any($patterns, $string)
{
	foreach ($patterns as $pattern)
		if (preg_match($pattern, $string))
			return TRUE;
	return FALSE;
}
?>
