/**
 * After the page loads, look for any slideshow elements and start them.
 *
 * @note This script expects to be executed at the end of the body.
 */
(function()
{
	var slideshows = document.querySelectorAll('.slideshow');
	for (var i = 0, slideshow; slideshow = slideshows[i]; ++i)
		with ({slides: find(slideshow, '.slide'), slideIndex: 0})
			if (slides !== undefined && slides.length > 1)
			{
				// initialize z-index of slides
				for (var j = 0, slide; slide = slides[j]; ++j)
					if (isNaN(parseInt(slide.style.zIndex)))
						slide.style.zIndex = 0;

				// stagger slide-switching between slideshows
				setTimeout(function()
				{
					// switch to next slide and start slide-switching timer
					function nextSlide()
					{
						// find the next slide
						var lastSlide = slides[slideIndex];
						slideIndex = (slideIndex + 1) % slides.length;
						var slide = slides[slideIndex];

						// bring the next slide to the front
						slide.style.opacity = 0;
						slide.style.filter = 'alpha(opacity=0)';
						slide.style.position = 'absolute';
						slide.style.zIndex = parseInt(slide.style.zIndex) + 1;
						slide.style.display = 'block';

						// fade in the next slide
						var startTime = new Date().getTime();
						var fadeTimer = setInterval(function()
						{
							var timeElapsed = (new Date().getTime() - startTime) / 1000;
							if (timeElapsed >= SLIDESHOW_TRANSITION_DURATION)
							{
								clearInterval(fadeTimer);
								slide.style.opacity = 1;
								slide.style.filter = 'alpha(opacity=100)';
								lastSlide.style.display = 'none';
								slide.style.position = 'static';
								slide.style.zIndex = parseInt(slide.style.zIndex) - 1;
							}
							else
							{
								var opacity = timeElapsed / SLIDESHOW_TRANSITION_DURATION;
								slide.style.opacity = opacity;
								slide.style.filter = 'alpha(opacity=' + Math.round(opacity * 100) + ')';
							}
						}, 10);
					}
					nextSlide();
					setInterval(nextSlide, SLIDESHOW_SLIDE_DURATION * 1000);
				}, SLIDESHOW_SLIDE_DURATION / slideshows.length * (i + 1) * 1000);
			}
})();
