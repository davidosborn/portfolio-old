<?php
/**
 * Transforms each element of an array with a function.
 */
function array_transform($array, $function)
{
	foreach ($array as &$element)
		$element = $function($element);
	return $array;
}

/**
 * Converts an object implementing Traversable to an associative array.
 */
function traversable_to_array($traversable)
{
	$result = array();
	foreach ($traversable as $key => $value)
		$result[$key] = $value;
	return $result;
}
?>
