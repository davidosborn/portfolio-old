/**
 * Perform some initialization of the iframe, and make it responsive.
 *
 * @note This script expects to be executed at the end of the body.
 */
(function()
{
	var iframe = document.querySelector('#content>iframe');
	if (!iframe) return;

	function init_iframe()
	{
		var body = iframe.contentWindow.document.body;
		if (body === null)
		{
			iframe.style.height = '100%';
			return;
		}

		/**
		 * Adjust the height of the iframe to match its content, so that no
		 * scrollbars are required.
		 *
		 * @fixme This implementation is imperfect.  It gives the iframe some
		 * additional height because the scrollHeight of the contained body is
		 * not being reported accurately.  It also seems to affect the
		 * positioning of the footer below the iframe.
		 */
		iframe.style.height = '100%';
		var height = body.scrollHeight;
		height += 64; // extra spacing to help avoid vertical scrollbar
		if (height > iframe.scrollHeight)
			iframe.style.height = height + 'px';

		// hide vertical scrollbar in IE
		// FIXME: we shouldn't force the scrollbar to be hidden
		body.scroll = 'no';
	}
	init_iframe();

	// bind event handlers
	bind(iframe, 'load',   init_iframe);
	// FIXME: disabled because it was causing an event cascade in IE
	//bind(iframe, 'resize', init_iframe);
})();
