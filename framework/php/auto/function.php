<?php
/**
 * Caches the results of a function call.
 *
 * @fixme Its a little worrisome that we are using a single MD5 hash as the key,
 *        since its possible that there will be collisions.  However, as this is
 *        a small website, we aren't going to worry about that right now.
 */
function call_memoized()
{
	static $cache = array();
	$args = func_get_args();
	$hash = md5(serialize($args));
	$func = array_shift($args);
	if (!isset($cache[$hash]))
		$cache[$hash] = call_user_func_array($func, $args);
	return $cache[$hash];
}
?>
