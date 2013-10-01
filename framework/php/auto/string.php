<?php
/**
 * Returns TRUE if the first string starts with the second string.
 */
function starts_with($string, $start)
{
	return strncmp($string, $start, strlen($start)) === 0;
}

/**
 * Returns TRUE if the first string ends with the second string.
 */
function ends_with($string, $end)
{
	return strcmp(substr($string, -strlen($end)), $end) === 0;
}

/**
 * If the first string starts with the second string, returns the subset of the
 * first string that occurs after the second string.  Otherwise, returns FALSE.
 */
function remainder_if_starts_with($string, $start)
{
	return starts_with($string, $start) ? substr($string, strlen($start)) : FALSE;
}

/**
 * If the first string ends with the second string, returns the subset of the
 * first string that occurs before the second string.  Otherwise, returns FALSE.
 */
function remainder_if_ends_with($string, $end)
{
	return ends_with($string, $end) ? substr($string, 0, -strlen($end)) : FALSE;
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Joins an array of words into a string using english grammar.
 */
function format_english_list($pieces, $conjunction='and', $serial_comma=TRUE)
{
	if (!$pieces)
		return '';

	if (count($pieces) == 1)
		return $pieces[0];

	if (!$conjunction)
		return implode($pieces, ', ');

	$last_piece = array_pop($pieces);
	$csv = implode($pieces, ', ');
	if (count($pieces) >= 2 && $serial_comma) $csv .= ',';
	return implode(' ', array($csv, $conjunction, $last_piece));
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Converts an identifier to camelCase, where the first letter is lowercase.
 */
function lower_camel_case($s)
{
	return lcfirst(str_replace(' ', '', ucwords(strtr($s, '_', ' '))));
}

/**
 * Converts an identifier to CamelCase, where the first letter is capitalized.
 */
function upper_camel_case($s)
{
	return str_replace(' ', '', ucwords(strtr($s, '_', ' ')));
}
?>
