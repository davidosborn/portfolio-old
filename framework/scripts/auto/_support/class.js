function hasClass(element, className)
{
	return element.className.match(new RegExp('(^|\\s)' + className + '($|\\s)'));
}

function appendClass(element, className)
{
	// FIXME: test this; not sure how clean it is
	element.className += (element.className ? ' ' : '') + className;
}

function removeClass(element, className)
{
	// FIXME: this doesn't necessarily leave the class in a clean state
	var regex = new RegExp('(^|\\s+)' + className + '($|\\s+)');
	element.className = element.className.replace(regex, ' ');
}

function toggleClass(element, className)
{
	if (hasClass(element, className))
		removeClass(element, className);
	else appendClass(element, className);
}
