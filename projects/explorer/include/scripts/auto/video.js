/**
 * If the video fails to play, replace it with the YouTube version.
 *
 * Based on the information provided by Mozilla:
 * https://developer.mozilla.org/en-US/docs/Web/HTML/Using_HTML5_audio_and_video
 */
(function()
{
	function fallback()
	{
		var youtubeVideo = document.createElement('iframe');
		youtubeVideo.setAttribute('width', '640');
		youtubeVideo.setAttribute('height', '360');
		youtubeVideo.setAttribute('src', 'https://www.youtube.com/embed/sIV6zDZnSJ4');
		youtubeVideo.setAttribute('frameborder', '0');
		youtubeVideo.setAttribute('allowfullscreen', 'allowfullscreen');

		var video = document.getElementById('video');
		video.parentNode.replaceChild(youtubeVideo, video);
	}

	var video = document.querySelector('video');
	if (!video)
	{
		fallback();
		return;
	}
	var lastSource = video.querySelector(video, 'source:last-child');
	bind(lastSource, 'error', fallback);
})();
