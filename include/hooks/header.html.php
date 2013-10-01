<div id="title">
	<?php load_hook('body-title')?>
</div>

<div id="tags">
	<h1>
		Only show projects
		<span id="tags-not"   class="tags-combiner">tagged</span> with
		<span id="tags-andor" class="tags-combiner">any</span> of:
	</h1>
	<ul>
		<?php
		foreach ($GLOBALS['TAGS'] as $tag)
			echo <<<EOF
<li class="tag" id="tag-$tag->id">
	<span class="spacer">
		$tag->title
	</span>
	<span class="text">
		<table>
			<tr>
				<td>
					$tag->title
				</td>
			</tr>
		</table>
	</span>
</li>
EOF;
		?>
	</ul>
</div>
