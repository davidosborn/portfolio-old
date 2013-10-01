/**
 * Preloads an image and calls the callback function when it is done.
 */
function preload_image(image, callback)
{
	var element = document.createElement('img');
	element.onload = function() { element.src = image; };
}
