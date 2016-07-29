// leadpages_input_data variables come from the template.json "variables" section
var leadpages_input_data = {};

$(function () {

	$('.share__btn').click(function(event){
		event.preventDefault();
		var service = $(this).data('service');
		switch(service) {
			case 'facebook':
				url = ( LeadPageData['facebookurl']['value'] ? LeadPageData['facebookurl']['value'] : document.URL);
				window_size = "width=585,height=368";
				go = 'http://www.facebook.com/sharer/sharer.php?u=' + url;
				break;
			case 'twitter':
				url = ( LeadPageData['twitterurl']['value'] ? LeadPageData['twitterurl']['value'] : document.URL);
				window_size = "width=585,height=261";
				go = 'http://www.twitter.com/intent/tweet?url=' + url;
				break;
			case 'google':
				url = ( LeadPageData['googleurl']['value'] ? LeadPageData['googleurl']['value'] : document.URL);
				window_size = "width=517,height=511";
				go = 'http://plus.google.com/share?url=' + url;
				break;
			default:
				return false;
		}
		window.open(go, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,' + window_size);
	});

});
