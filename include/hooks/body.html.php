<table>
	<tr>
		<?php
		/**
		 * Configure the iframe.
		 */
		switch (isset_or($_GET['iframe']))
		{
			case '0': case 'false': case 'no':  case 'off': $use_iframe = false; break;
			case '1': case 'true':  case 'yes': case 'on':  $use_iframe = true;  break;
			default: $use_iframe = !MOBILE;
		}
		if ($use_iframe)
		{
			/**
			 * Parse the query string to see if the user wants a specific
			 * project.
			 */
			$iframe_src = SITE_ROOT_URL . (isset($_GET['project']) ?
				$GLOBALS['PROJECTS'][$_GET['project']]->dir : WELCOME_PAGE) . '/';

			?>
			<td id="content">
				<iframe name="content" src="<?php echo $iframe_src?>" frameBorder="0" allowTransparency="true">
				</iframe>
			</td>
			<?php
		}
		else
		{
			/**
			 * If the iframe is disabled, but the user wants a specific project,
			 * redirect to that project's page.
			 */
			if (isset($_GET['project']))
			{
				header('Location: ' . SITE_ROOT_URL . $GLOBALS['PROJECTS'][$_GET['project']]->dir . '/');
				exit;
			}
			?>
				<td>
					<?php load_html_if_exists('framework/content/intro')?>
					<br/>
				</td>
			</tr>
			<tr>
			<?php
		}
		?>
		<td>
			<div id="projects">
				<?php
				foreach ($GLOBALS['PROJECTS'] as $project)
				{
					/**
					 * Build the project element's class string from the
					 * project's tags.
					 */
					$class = array_map(function($tag) { return "tag-$tag"; }, $project->tags);
					array_unshift($class, 'project');
					$class = implode(' ', $class);
					?>

					<a href="<?php echo SITE_ROOT_URL, $project->dir?>/"
						target="<?php echo $use_iframe ? 'content' : '_self'?>">

						<div class="<?php echo $class?>" id="project-<?php echo $project->id?>">
							<div class="highlight"></div>
							<table>
								<tr>
									<td>
										<div class="thumbnail">
											<?php
											$project_thumbnails = list_dir(
												$project->dir . '/include/assets',
												'thumbnail.{gif,jpg,png}',
												ListDirPathType::URL, ListDirFlags::EXPAND_BRACES);
											$thumbnail =
												$project_thumbnails ?
												$project_thumbnails[0] :
												PAGE_URL . 'include/assets/thumbnail.png';
											echo "<img src=\"$thumbnail\"/>";
											?>
										</div>
									</td>
									<td>
										<div class="info">
											<div class="title">
												<?php echo $project->title?>
											</div>
											<div class="description">
												<?php echo $project->desc?>
											</div>
											<!--<div class="tags">
												<?php
												$tags = array();
												foreach ($project->tags as $tag)
													array_push($tags, $GLOBALS['TAGS'][$tag]->title);
												echo format_english_list($tags, null);
												?>
											</div>-->
										</div>
									</td>
								</tr>
							</table>
						</div>
					</a>
					<?php
				}
				?>
			</div>
		</td>
	</tr>
</table>
