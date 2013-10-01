<?php
/**
 * This template divides the page into header, body, and footer sections.
 */
?>
<?php header('Content-Type: text/html; charset=UTF-8')?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<?php load_hook('head')?>
	</head>

	<body <?php if (MOBILE) echo 'class="mobile"'?> id="<?php echo PAGE_ID?>">
		<?php load_hook('top')?>
		<table>
			<thead>
				<tr>
					<td id="header" class="header">
						<?php load_hook('header')?>
					</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td id="footer">
						<?php load_hook('footer')?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td id="body">
						<?php load_hook('body')?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php load_hook('script')?>
	</body>
</html>
