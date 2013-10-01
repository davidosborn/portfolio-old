<?php
foreach ($GLOBALS['GOOGLE_FONTS'] as $font)
{
	$font = strtr($font, ' ', '+');
	echo "<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family=$font\"/>\n";
}
?>
