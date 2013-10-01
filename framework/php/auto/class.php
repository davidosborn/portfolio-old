<?php
/**
 * Converts an array to a new object of type StdClass, where the array elements
 * are replicated as properties in the object.
 */
function array2object($a)
{
	$o = new StdClass();
	foreach ($a as $k => $v)
		$o->$k = $v;
	return $o;
}

/**
 * Converts an array of arrays to an array of new objects of type StdClass,
 * where the array elements are replicated as properties in the objects.  The
 * key, if specified, must be the key of a value in the inner arrays which will
 * be used to create keys in the outer array.
 */
function array_array2object($aa, $key=NULL)
{
	array_array2object_inplace($aa, $key);
	return $aa;
}

/**
 * The in-place implementation of array_array2object().
 */
function array_array2object_inplace(&$aa, $key=NULL)
{
	if (isset($key))
	{
		$ao = array();
		foreach ($aa as $a)
			$ao[$a[$key]] = array2object($a);
		$aa = $ao;
	}
	else
	{
		foreach ($aa as &$a)
			$a = array2object($a);
	}
}
?>
