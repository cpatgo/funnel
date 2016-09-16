/* ---------- TEMPLATE DATA ---------- */

var leadpages_input_data = {
	'video-source'  : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.mp4', // what what
	'image-source'  : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.png', // what what
	'mobile-source' : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.jpg'  // what what
};

var template_data = {
	'video-source'  : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.mp4',
	'image-source'  : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.png',
	'mobile-source' : 'https://d1pn1voq3v40vn.cloudfront.net/2.0/video/time-lapse-street.jpg'
};

function sanitize_input_data() {
	var prop, key, data = {};
	for (prop in leadpages_input_data) {
		key = prop.replace(/^\s*\'|\"/, '').replace(/\'|\"\s*$/, '');
		data[key] = leadpages_input_data[prop];
	}
	return data;
}

function get_data(key) {
	var input_data = sanitize_input_data();
	return input_data[key] || template_data[key];
}

/* ---------- VIDEO PREP ---------- */
$(function() {

	function showPage(){
		$('#big-video-wrap').css({ opacity:0 });
		$('#big-video-wrap').animate({ opacity:1 }, 2500);
	}

	var BV = new $.BigVideo();
	BV.init();

	var agentString = navigator.userAgent.toLowerCase();
	var agentSearch = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i;
	var isMobile = agentSearch.test(agentString);

	// mobile devices will not play a video unless you click,
	// so we will show a lower quality static image for them.
	if ( isMobile ) {
		BV.show( get_data('mobile-source') );
	}
	else {
		BV.show( get_data('video-source'), {
			controls:false,
			doLoop:true,
			ambient:true
		});
	}

	// once loaded show the video.
	BV.getPlayer().ready(function(){
		showPage();
	});

	// incase the browser can't handle video, show a static image for them.
	BV.getPlayer().on('error', function(){
		BV.show( get_data('image-source') );
		showPage();
	});

});