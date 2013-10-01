<?php
/**
 * Builds a path by joining the arguments as sub paths.
 *
 * @note The paths do not need to have been normalized.
 *
 * @note Leading and trailing path-separators, of the first and last components
 *       respectively, are retained.
 */
function join_path()
{
	$paths = func_get_args();
	if (empty($paths)) return '';
	if (count($paths) === 1) return $paths[0];

	// trim path-separators at connection points
	$paths[0] = rtrim_path($paths[0]);
	$paths[count($paths) - 1] = ltrim_path($paths[count($paths) - 1]);
	for ($i = 1; $i < count($paths) - 1; ++$i)
		$paths[$i] = trim_path($paths[$i]);

	return implode('/', array_filter($paths));
}

/**
 * Returns the path without leading path-separators.
 */
function ltrim_path($path)
{
	return ltrim($path, '/' . DIRECTORY_SEPARATOR);
}

/**
 * Returns the path without trailing path-separators.
 */
function rtrim_path($path)
{
	return rtrim($path, '/' . DIRECTORY_SEPARATOR);
}

/**
 * Returns the path without leading or trailing path-separators.
 */
function trim_path($path)
{
	return trim($path, '/' . DIRECTORY_SEPARATOR);
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Normalizes a path.  Path-separators are converted to the POSIX style, and
 * adjacent separators are merged.  Extraneous components are factored out of
 * the path, including current- and parent-directory symbols.
 *
 * @note Leading and trailing path-separators are retained.
 */
function normalize_path($path)
{
	if (empty($path)) return $path;

	// remember leading/trailing path-separators
	$leading_separator = $path[0] === '/';
	$trailing_separator = $path[strlen($path) - 1] === '/';

	// convert to POSIX path-separators
	$path = strtr($path, '\\', '/');

	$path = preg_replace(
		array(
			'/(?<=[\\/])[\\/]+/', // merge adjacent path-separators
			'/(\\A|\\/)\\.(?=(\\/|\\Z))/'), // remove current-directory symbols
		NULL, $path);

	/**
	 * Factor out parent-directory symbols.  The regular expression must be
	 * executed repeatedly until all possible factoring has been done, because
	 * the parent-directory components may be nested.
	 */
	for (;;)
	{
		$n = strlen($path);
		$path = preg_replace('/(\\A|\\/)(?!\\.{2})[^\\/]+\\/\\.{2}(?=(\\/|\\Z))/', '', $path);
		if (strlen($path) === $n) break;
	}

	// restore leading/trailing path-separators
	if (!empty($path))
	{
		if ($leading_separator) {
			if ($path[0] !== '/') $path = '/' . $path; }
		elseif ($path[0] === '/') $path = substr($path, 1);

		if ($trailing_separator) {
			if ($path[strlen($path) - 1] !== '/') $path .= '/'; }
		elseif ($path[strlen($path) - 1] === '/') $path = substr($path, 0, -1);
	}
	elseif ($leading_separator || $trailing_separator) $path = '/';

	return $path;
}

/**
 * Returns TRUE if the path is under the base.
 *
 * @note The paths are assumed to have been normalized.  It is the caller's
 *       responsibility to normalize them if necessary.  If a path is not
 *       normalized, the result may be incorrect.
 *
 * @note The path and the base are interpreted as being absolute, whether they
 *       have a leading path-separator or not.  Leading path-separators are
 *       implicit and ignored.
 */
function path_is_under($path, $base)
{
	// ignore leading path-separators
	$path = rtrim($path, '/');
	$base = rtrim($base, '/');

	// check if the path starts with the base
	if (strncmp($path, $base, strlen($base)) !== 0)
		return FALSE;

	/**
	 * If the path doesn't end or have a path-separator immediately after the
	 * intersection, then the last component of the base is not a match.
	 */
	return
		strlen($path) === strlen($base) ||
		$path[strlen($base)] === '/';
}

/**
 * Converts an absolute path to a relative path, using another path as its base.
 * If the path is not entirely under its base, up-directory components will be
 * added as necessary.
 *
 * @note The paths are assumed to have been normalized.  It is the caller's
 *       responsibility to normalize them if necessary.  If a path is not
 *       normalized, the result may be incorrect.
 *
 * @note The path and the base are interpreted as being absolute, whether they
 *       have a leading path-separator or not.  Leading path-separators are
 *       implicit and ignored, and the resulting path will not have a leading
 *       path-separator.
 *
 * @note Trailing path-separators are retained.
 */
function path_relative_to($path, $base)
{
	// remove leading path-separators
	if (!empty($path) && $path[0] === '/')
		$path = not_false_or(substr($path, 1), '');
	if (!empty($base))
	{
		if ($base[0] === '/')
			$base = not_false_or(substr($base, 1), '');

		// remove trailing path-separator from base
		if (!empty($base))
		{
			if (substr($base, -1) === '/')
				$base = substr($base, 0, strlen($base) - 1);

			// optimization
			goto base_not_empty;
		}
		// optimization
		goto base_empty;
	}

	// if the base is empty, the path can be returned as is
	if (empty($base))
	{
		base_empty:
		return $path;
	}
	base_not_empty:

	// if the path is empty, the result is a move up from the base to the root
	if (empty($path))
		return '..' . str_repeat('/..', substr_count($base, '/'));

	/**
	 * Find first mismatching character.
	 * Based on the algorithm from <http://stackoverflow.com/a/7475502>.
	 */
	$i = strspn($path ^ $base, "\0");

	/**
	 * If the path does not match the base at all, the result is a move up from
	 * the base to the root, followed by the path.
	 */
	if (!$i)
		return str_repeat('../', substr_count($base, '/') + 1) . $path;

	/**
	 * If the path is under the base, we don't have to do any backtracking and
	 * the base can be factored out completely.  Check if the path is an initial
	 * substring of the base, which would indicate that the path is probably
	 * under the base.
	 */
	if ($i === strlen($base))
	{
		/**
		 * If the path is the same as the base, except for a possible trailing
		 * path-separator, the result is an empty path.
		 */
		if ($i === strlen($path) ||
			$i === strlen($path) - 1 && substr($path, -1) === '/')
				return '';

		/**
		 * If the path is entirely under the base, the result is the remainder
		 * of the path minus the base.
		 */
		if ($path[$i] === '/')
			return substr($path, $i + 1);

		/**
		 * The final component of the base is only a substring of the respective
		 * component of the path, so the result is the remainder of the path
		 * after backtracking one level and subtracting the base at that point.
		 */
		return '../' . substr($path,
			not_false_or(strrpos($path, '/', $i - 1 - strlen($path)), 0));
	}

	/**
	 * The path is more than one level away from being under the base, so the
	 * result is the remainder of the path after backtracking through the
	 * mismatching levels and subtracting the base at that point.
	 */
	$i = not_false_or(strrpos($base, '/', $i - 1 - strlen($base)), -1) + 1;
	return '..' . str_repeat('/..', substr_count($base, '/', $i)) . '/' . substr($path, $i);
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the starting index of the full extension of the file at the specified
 * path, which is the index of the file's first extension separator.
 */
function path_extension_index($path)
{
	return strpos($path, '.', not_false_or(strrpos($path, '/'), -1) + 1);
}

/**
 * Returns the starting index of the last extension of the file at the specified
 * path, which is the index of the file's last extension separator.
 */
function path_last_extension_index($path)
{
	return strrpos($path, '.', not_false_or(strrpos($path, '/'), -1) + 1);
}

/**
 * Returns TRUE if the path has an extension.
 */
function path_has_extension($path)
{
	return path_extension_index($path) !== FALSE;
}

/**
 * Returns the full extension of the file at the specified path, or FALSE if
 * the file doesn't have an extension.
 */
function path_extension($path)
{
	$i = path_extension_index($path);
	return
		$i === FALSE ? FALSE :
		++$i === strlen($path) ? '' :
		strtolower(substr($path, $i));
}

/**
 * Returns the last extension of the file at the specified path, or FALSE if
 * the file doesn't have an extension.
 */
function path_last_extension($path)
{
	$i = path_last_extension_index($path);
	return
		$i === FALSE ? FALSE :
		++$i === strlen($path) ? '' :
		strtolower(substr($path, $i));
}

/**
 * Replaces the full extension of the file at the specified path.
 */
function path_replace_extension($path, $extension)
{
	$i = path_extension_index($path);
	return ($i !== FALSE ? substr($path, 0, $i + 1) : $path . '.') . $extension;
}

/**
 * Removes the full extension from the file at the specified path.
 */
function path_without_extension($path)
{
	$i = path_extension_index($path);
	return $i !== FALSE ? substr($path, 0, $i) : $path;
}

/**
 * Removes the last extension from the file at the specified path.
 */
function path_without_last_extension($path)
{
	$i = path_last_extension_index($path);
	return $i !== FALSE ? substr($path, 0, $i) : $path;
}

////////////////////////////////////////////////////////////////////////////////
// tests

/*assert(normalize_path('') === '');
assert(normalize_path('/') === '/');
assert(normalize_path('//') === '/');
assert(normalize_path('///') === '/');
assert(normalize_path('.') === '');
assert(normalize_path('./.') === '');
assert(normalize_path('/./') === '/');
assert(normalize_path('/./.') === '/');
assert(normalize_path('././') === '/');

assert(normalize_path('a/..') === '');
assert(normalize_path('/a/..') === '/');
assert(normalize_path('a/../') === '/');
assert(normalize_path('/a/../') === '/');
assert(normalize_path('a/b/..') === 'a');
assert(normalize_path('/a/b/..') === '/a');
assert(normalize_path('a/b/../') === 'a/');
assert(normalize_path('a/../b') === 'b');
assert(normalize_path('/a/../b') === '/b');
assert(normalize_path('a/../b/') === 'b/');
assert(normalize_path('a/b/../..') === '');
assert(normalize_path('a/b/../../') === '/');
assert(normalize_path('/a/b/../..') === '/');
assert(normalize_path('a/b/c/../..') === 'a');
assert(normalize_path('a/b/c/../../') === 'a/');
assert(normalize_path('/a/b/c/../..') === '/a');
assert(normalize_path('/a/b/c/../../') === '/a/');

assert(normalize_path('.a') === '.a');
assert(normalize_path('a.') === 'a.');
assert(normalize_path('..a') === '..a');
assert(normalize_path('a..') === 'a..');
assert(normalize_path('/.a') === '/.a');
assert(normalize_path('a./') === 'a./');

assert(normalize_path('//../aaa/bbb/../../ccc/./../ddd///.///eee//') === '/../ddd/eee/');

assert(path_relative_to('/', '/') === '');
assert(path_relative_to('/', '') === '');
assert(path_relative_to('', '/') === '');
assert(path_relative_to('//', '//') === '');
assert(path_relative_to('a/b', '') === 'a/b');
assert(path_relative_to('', 'a/b') === '../..');
assert(path_relative_to('a/b', 'a/b') === '');
assert(path_relative_to('a/b/', 'a/b') === '');
assert(path_relative_to('a/b', 'a') === 'b');
assert(path_relative_to('a/b', '/a') === 'b');
assert(path_relative_to('a/b', 'a/') === 'b');
assert(path_relative_to('/a/b', 'a') === 'b');
assert(path_relative_to('a/b/', 'a') === 'b/');
assert(path_relative_to('a/b/c', 'a/d') === '../b/c');
assert(path_relative_to('a/b/c', 'd/e') === '../../a/b/c');

assert(path_extension_index('') === FALSE);
assert(path_extension_index('a') === FALSE);
assert(path_extension_index('a.') === 1);
assert(path_extension_index('a.b') === 1);
assert(path_extension_index('a.b.') === 1);
assert(path_extension_index('a.b.c') === 1);
assert(path_extension_index('.') === 0);
assert(path_extension_index('..') === 0);
assert(path_extension_index('.a') === 0);
assert(path_extension_index('.a.') === 0);

assert(path_last_extension_index('') === FALSE);
assert(path_last_extension_index('a') === FALSE);
assert(path_last_extension_index('a.') === 1);
assert(path_last_extension_index('a.b') === 1);
assert(path_last_extension_index('a.b.') === 3);
assert(path_last_extension_index('a.b.c') === 3);
assert(path_last_extension_index('.') === 0);
assert(path_last_extension_index('..') === 1);
assert(path_last_extension_index('.a') === 0);
assert(path_last_extension_index('.a.') === 2);*/
?>
