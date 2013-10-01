/**
 * An implementation of document.querySelector{,All} for Internet Explorer 7.
 *
 * Based substantially on code by Dan Nye, used under the MIT license:
 * http://www.codecouch.com/2012/05/adding-document-queryselectorall-support-to-ie-7/
 * http://www.codecouch.com/terms/
 */
(function()
{
	if (!document.querySelectorAll)
	{
		var s = document.createStyleSheet();
		document.querySelectorAll = function(r)
		{
			var a = document.all;
			var c = [];
			r = r.replace(/\[for\b/gi, '[htmlFor').split(',');
			for (var i = 0; i < r.length; ++i)
			{
				s.addRule(r[i], 'k:v');
				for (var j = 0; j < a.length; ++j)
					a[j].currentStyle.k && c.push(a[j]);
				s.removeRule(0);
			}
			return c;
		}
		document.querySelector = function(r)
		{
			return document.querySelectorAll(r)[0];
		}
	}
})();
