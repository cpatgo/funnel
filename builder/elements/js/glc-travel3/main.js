

equalHeight(jQuery(".offer-items img"));
				
	function equalHeight(group) {
	   tallest = 0;
	   group.each(function() {
		  thisHeight = jQuery(this).height();
		  if(thisHeight > tallest) {
			 tallest = thisHeight;
		  }
	   });
	   group.height(tallest);
	}
