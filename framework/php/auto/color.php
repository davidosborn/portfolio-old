<?php
include_once 'array.php'; // array_transform

/**
 * Converts a set of three unit-scale color-components to a 6-digit CSS-
 * compatible hexadecimal-string.
 */
function color2hex($r, $g, $b)
{
	return '#' . bin2hex(pack('C3', $r * 255, $g * 255, $b * 255));
}

/**
 * Parses a CSS-color string and returns it as an array of 4 unit-scale color-
 * components.
 */
function parse_color($color)
{
	if (strncmp($color, '#', 1) === 0)
	{
		switch (strlen($color))
		{
			case 4:
			$color = $color[0] .
				$color[1] . $color[1] .
				$color[2] . $color[2] .
				$color[3] . $color[3];

			case 7:
			return array(
				(float)hexdec(substr($color, 1, 2)) / 255,
				(float)hexdec(substr($color, 3, 2)) / 255,
				(float)hexdec(substr($color, 5, 2)) / 255);

			default:
			die('hex triplet must have 3 or 6 digits');
		}
	}
	elseif (strncmp($color, 'rgba(', 5) === 0)
	{
		return array_transform(
			implode(',', substr($color, 5, -1)),
			function($x) { return (float)intval($x) / 255; });
	}
	elseif (strncmp($color, 'rgb(', 4) === 0)
	{
		return
			array_merge(
				array_transform(
					implode(',', substr($color, 5, -1)),
					function($x) { return (float)intval($x) / 255; }),
				array(1));
	}
	elseif (strncmp($color, 'hsv(', 4) === 0)
	{
		return
			array_merge(
				call_user_func_array(
					'hsv2rgb',
					array_transform(
						implode(',', substr($color, 5, -1)),
						function($x) { return (float)intval($x) / 255; })),
				array(1));
	}
	die('invalid color format');
}

/**
 * Converts an array of unit-scale color-components to a CSS-color string.
 */
function export_color($color)
{
	switch (count($color))
	{
		case 4:
		if ($color[3] < 1)
			return 'rgba(' .
				(int)($color[0] * 255) . ',' .
				(int)($color[1] * 255) . ',' .
				(int)($color[2] * 255) . ',' .
				(int)($color[3] * 255) . ')';

		case 3:
		return sprintf('#%02x%02x%02x',
			(int)($color[0] * 255),
			(int)($color[1] * 255),
			(int)($color[2] * 255));
	}
}

/**
 * Converts HSV to RGB color.
 *
 * @see Algorithm from http://www.cs.rit.edu/~ncs/color/t_convert.html.
 */
function hsv2rgb($h, $s, $v)
{
	$i = $h * 6;
	$f = $h * 6 - $i;
	$p = $v * (1 - $s);
	$q = $v * (1 - $s * $f);
	$t = $v * (1 - $s * (1 - $f));

	switch ($i)
	{
		case 0:  $r = $v; $g = $t; $b = $p; break;
		case 1:  $r = $q; $g = $v; $b = $p; break;
		case 2:  $r = $p; $g = $v; $b = $t; break;
		case 3:  $r = $p; $g = $q; $b = $v; break;
		case 4:  $r = $t; $g = $p; $b = $v; break;
		default: $r = $v; $g = $p; $b = $q;
	}

	return color2hex($r, $g, $b);
}

/**
 * Adds a scalar value to each component in a color array.
 */
function add_color($color, $x)
{
	return array_transform($color,
		function($y) use($x) { return min(max($y + $x, 0), 1); });
}

/**
 * Subtracts a scalar value to each component in a color array.
 */
function sub_color($color, $x)
{
	return array_transform($color,
		function($y) use($x) { return min(max($y - $x, 0), 1); });
}

/**
 * Multiplies each component in a color array by a scalar value.
 */
function mul_color($color, $x)
{
	return array_transform($color,
		function($y) use($x) { return min(max($y * $x, 0), 1); });
}
?>
