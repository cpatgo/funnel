jQuery(document).ready(function($){
	$(window).unload(function() {
	 	$.post(siteUrl+'/glc/logout.php');
	});
});