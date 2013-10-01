<?php
/**
 * Core PHP enhancements.
 *
 * @note It is expected that this file will be included before any other non-
 *       third-party PHP files.
 */

/**
 * Includes a PHP file, but only if it exists.
 */
function include_if_exists($file)
{
	if (is_file($file))
		include $file;
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the first argument, if it is equal to the second argument.
 * Otherwise, returns the third argument, if it is provided, or NULL.
 */
function equal_or($var, $other, $default=NULL)
{
	return $var === $other ? $var : $default;
}

/**
 * Returns TRUE if the variable is empty.
 *
 * @note This function is provided to allow calling empty on constants, which is
 *       otherwise illegal.
 */
function is_empty($var)
{
	return empty($var);
}

/**
 * Returns TRUE if the variable is empty.
 *
 * @note This function is provided to allow calling isset on constants, which is
 *       otherwise illegal.
 */
function is_set($var)
{
	return isset($var);
}

/**
 * Returns the first argument, if it is set. Otherwise, returns the second
 * argument, if it is provided, or NULL.
 */
function isset_or(&$var, $default=NULL)
{
	return isset($var) ? $var : $default;
}

/**
 * Returns the first argument, if it is not empty.  Otherwise, returns the
 * second argument, if it is provided, or NULL.
 */
function not_empty_or($var, $default=NULL)
{
	return !empty($var) ? $var : $default;
}

/**
 * Returns the first argument, if it is not FALSE.  Otherwise, returns the
 * second argument, if it is provided, or NULL.
 */
function not_false_or($var, $default=NULL)
{
	return $var !== FALSE ? $var : $default;
}

/**
 * Returns the first argument, initialized to the second argument if it is not
 * already set.
 */
function set_default(&$var, $default=NULL)
{
	if (!isset($var))
		$var = $default;
	return $var;
}
?>
