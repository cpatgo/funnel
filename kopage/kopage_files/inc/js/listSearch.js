/* based on "FAQ Plugin" / goFaq / @author: Rod Howard, goideate.com, April 28, 2012, GNU/GPL Version 3 */
(function($) {
	
    $.fn.listSearch = function(options) {
		
        var defaults = { 
        	enableSearch: true,
        	noResults: 'Nothing Found',
        	searchPlaceholder: 'Search',
        	searchDefault : ''
        };
		
        var options = $.extend({}, defaults, options);
        
		return this.each(function(i) {
			
            var $this = $(this);
            
            var $container = $this.wrap ('<div class="faq-container"></div>');
            
            $this.addClass ('faq-list');
            
            if (options.enableSearch) {            	
	            var $form = generateSearchForm();
	            $form.insertBefore ($this);
            }
            
            var $empty = generateEmptySearch();
            $empty.appendTo ($container);
            
            generateAnswerNumbers($this);
			
			
			
        });
        function search (e) {
			var el, container, filter, count, pattern, container, answers, toc, tocs, empty;
			
			el = $(this);
			container = el.parents('.faq-container');
			filter = el.val();
			answers = container.find('.faq-list').find('li');
			empty = container.find('.faq-empty');
			pattern = new RegExp (filter, 'i');
			patternAvoid = new RegExp ('', 'i');
			
			answers.hide();
			
			$.grep(answers.find('.faq-text a'),function(input){
				if(pattern.test($(input).text())) {
					$(input).parents('li').show();
					var index = $(input).parents('li').index();		
				}
			});
			
			if (!answers.is(':visible')) {
				empty.show();
			} else {
				empty.hide();
			}
		}
        
		
       
		
		function generateEmptySearch () {
			var $empty = $('<div>', { 'class': 'faq-empty hide alert alert-info', 'style':'margin-top:25px' });
			
			return $empty.html (options.noResults);
		}
        
        function generateSearchForm () {
        	
			
        	var $input = $('<input>', { 'value':options.searchDefault,'id':'inputSearch','type': 'text', 'class' : 'input-xxlarge', 'name': 'inputSearch', 'placeholder': options.searchPlaceholder });
        	
        	$input.bind ('keyup', search)
        	
        	return $input;
        }
        
        function generateAnswerNumbers ($list) {
        	$list.find ('li').each (function (i) {
        		var id = parseInt (i+1);
        		$(this).wrapInner ('<div class="faq-text"></div>');
        		
        		
        	});
        }
		
        
	};
			
})(jQuery);