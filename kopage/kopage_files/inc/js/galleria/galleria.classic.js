/**
 * Galleria Classic Theme 2012-08-08
 * http://galleria.io
 *
 * Licensed under the MIT license
 * https://raw.github.com/aino/galleria/master/LICENSE
 *
 */

(function($) {

/*global window, jQuery, Galleria */

Galleria.addTheme({
    name: 'classic',
    author: 'Galleria',
    css: 'galleria.classic.css',
    defaults: {
		
        thumbCrop:  'height',
        _toggleInfo: false,
		imageCrop:true,
		loadingDone:false,
		transition: 'flash',
		thumbnails:false,
		responsive:true,
		showCounter:false,
		autoplay: true,
		keepSource:true,
		//lightbox: true
		
    },
    init: function(options) {
        var info = this.$('info-text'),///*info-link,info-close,*/
            touch = Galleria.TOUCH;
			
        this.$('loader').show().css('opacity', 0.4);

        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-80 });
            this.addIdleState( this.get('image-nav-right'), { right:-80 });
        }

        var activate = function(e) {
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        };

        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
            window.setTimeout(function() {
                activate(e);
            }, touch ? 300 : 0);
            this.$('info').toggle( this.hasInfo() );
        });

        this.bind('loadfinish', function(e) {
			
			if(options.loadingDone !== true){
				this.$('loader').parent().parent().parent().removeClass('galleriaLoading')
				options.loadingDone = true;
			}
			
			info.show();

            this.$('loader').fadeOut(200);
        });
    }
});

}(jQuery));