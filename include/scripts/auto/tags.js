/**
 * Filters the listed projects by the currently selected tags.  Only projects
 * having all of the selected tags will be displayed.  If no tags are selected,
 * all of the projects will be displayed.
 */
function filterProjectsByTags()
{
	var projects = document.querySelectorAll('.project');
	var tags = document.querySelectorAll('.tag.selected');
	if (tags.length > 0)
	{
		$not = document.querySelector('#tags-not').innerHTML === 'not tagged';
		switch (document.querySelector('#tags-andor').innerHTML)
		{
			case 'all': // and
			for (var i = 0; i < projects.length; ++i)
			{
				var show = true;
				for (var j = 0; j < tags.length; ++j)
					if (!hasClass(projects[i], tags[j].id))
					{
						show = false;
						break;
					}
				projects[i].style.display = show ^ $not ? 'block' : 'none';
			}
			break;

			case 'any': // or
			for (var i = 0; i < projects.length; ++i)
			{
				var show = false;
				for (var j = 0; j < tags.length; ++j)
					if (hasClass(projects[i], tags[j].id))
					{
						show = true;
						break;
					}
				projects[i].style.display = show ^ $not ? 'block' : 'none';
			}
			break;
		}
	}
	else
	{
		// no tags are selected, so show all projects
		for (var i = 0; i < projects.length; ++i)
			projects[i].style.display = 'block';
	}

	/**
	 * Simulate a resize event, as the number of listed projects may have
	 * changed, possibly affecting the size of the document.
	 */
	var iframe = document.querySelector('#content>iframe');
	iframe.dispatchEvent(new CustomEvent('load'));
}

/**
 * Initialize the tag list.
 */
(function()
{
	// bind click handler for and/or combiner
	bind(document.querySelector('#tags-andor'), 'click', function()
	{
		this.innerHTML = this.innerHTML === 'any' ? 'all' : 'any';
		filterProjectsByTags();
	});

	// bind click handler for not combiner
	bind(document.querySelector('#tags-not'), 'click', function()
	{
		this.innerHTML = this.innerHTML === 'tagged' ? 'not tagged' : 'tagged';
		filterProjectsByTags();
	});

	// bind click handler for tags
	var tags = document.querySelectorAll('#tags>ul>li.tag');
	for (var i = 0; i < tags.length; ++i)
		bind(tags[i], 'click', function()
		{
			toggleClass(this, 'selected');
			filterProjectsByTags();
		});

	// make tags block visible
	document.getElementById('tags').style.display = 'block';
})();
