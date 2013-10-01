/**
 * Returns an array of the elements matching a selector, below the specified
 * root element.
 */
function find(root, selector)
{
	// give the root element an ID if it doesn't already have one
	var noId = false;
	if (!root.id)
	{
		noId = true;
		root.id = 'temp_id_for_find_function';
	}

	// put the selector under the root element
	selector = selector.indexOf('&') !== -1 ?
		selector.replace('&', '#' + root.id) :
		'#' + root.id + ' ' + selector;

	// find all elements matching the selector
	var result = document.querySelectorAll(selector);

	// remove the root element's ID if it didn't originally have one
	if (noId)
		root.id = undefined;

	return result;
}
