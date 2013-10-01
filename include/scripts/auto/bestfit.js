/**
 * Adjust the project boxes to take up the maximum width of their parent
 * container.
 */
(function()
{
	// get container and its constant attributes
	var container = document.getElementById('projects');
	var containerPadding = 16;

	// get projects and their constant attributes
	var projects = find(container, '.project');
	var projectMinWidth = projects[0].clientWidth;
	var projectPadding = 16;

	function bestFit()
	{
		/**
		 * Remove any locking of the container's width, as introduced by this
		 * function, get the updated width, and lock the width again.  This
		 * prevents the floating child-elements from wrapping before this
		 * function has a chance to re-adjust their width.
		 */
		for (var i = 0; i < projects.length; ++i)
			projects[i].style.width = '';
		container.style.width = '';
		container.style.width = container.clientWidth;

		var spacePerRow = container.clientWidth - containerPadding;
		spacePerRow -= 1; // buffer space to prevent unexpected line wrapping
		var projectsPerRow = Math.floor(spacePerRow / projectMinWidth);
		spacePerRow -= projectsPerRow * projectPadding;
		var spacePerProject = spacePerRow / projectsPerRow;

		var floatSpaceUsed = 0;
		var intSpaceUsed = 0;
		for (var i = 0; i < projects.length; ++i)
			if ((i + 1) % projectsPerRow !== 0)
			{
				floatSpaceUsed += spacePerProject;
				var intSpace = Math.round(floatSpaceUsed - intSpaceUsed);
				intSpaceUsed += intSpace;
				projects[i].style.width = intSpace + 'px';
			}
			else
			{
				// give the remaining space to the last project in the row
				projects[i].style.width = spacePerRow - intSpaceUsed + 'px';

				// reset for the next row
				floatSpaceUsed = 0;
				intSpaceUsed = 0;
			}

		return;
	}
	bestFit();

	/**
	 * The width of the .project elements is initially determined by the
	 * .thumbnail and .info elements, but after running bestFit(), it will be
	 * determined by the .project elements.  Therefore, remove any width
	 * specification from the .info elements, allowing them to grow to fill
	 * their parent.
	 */
	var infoBoxes = container.getElementsByClassName('info');
	for (var i = 0; i < infoBoxes.length; ++i)
		infoBoxes[i].style.width = 'auto';

	bind(window, 'resize', bestFit);
})();
