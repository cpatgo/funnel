/*greybox/redux/amix.dk*/
var ihBox_ANIMATION = true;

var ihBox_DONE = false;


function ihBox_show(caption, url, option) {
	
	// build answer.
	$('#kopageBarFAQ').remove();
	$('.kopageBarContent').append('<div id="kopageBarFAQ"><div id="kopageBarFAQ_Title" class="">'+caption+'<a id="kopageBarFAQ_Close" href="javascript:void(null)" onclick="$(\'#kopageBarFAQ\').remove();"><i class="fa fa-close"></i></a></div><div id="kopageBarFAQ_Content"><i class="fa fa-spin fa-spinner"></i> </div></div>');//'+option+'/'+url+'
	
	if(option == 4){
		
		// show video.
		//alert(url);
		regExp = /^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/;
		var match = url.match(regExp);
		var videoId;
		
		
		//alert(match[0]+"\n"+match[1]+"\n"+match[2]+"\n"+match[3])
	
		if (match&&match[2].length==11){
			videoId = match[2];
		}else{
			videoId = 0;
		}
	
		if(videoId == 0){
		
		// return an error;
		alert('Video Error!');
		
		} else {
		



			// there's a video ID, embed it now:
			var embedCode = '<div class="kvideo-centered" style="margin:-20px;"><iframe src="//www.youtube.com/embed/'+videoId+'?showinfo=0&rel=0&autohide=1&disablekb=1&modestbranding=1&autoplay=1';
			
			var isTimeStart=match[0];//https://youtu.be/r9kh29cVgvU?t=25s
			isTimeStart=isTimeStart.replace('&t=','?t=');
			isTimeStart=isTimeStart.split('?t=');
			if(isTimeStart.length>1){
								// there was time start in use.
				isTimeStart=isTimeStart[1];
				isTimeStart=isTimeStart.split('s');
				isTimeStart=isTimeStart[0];
				
				// are there minutes?
				var timeMinutes=isTimeStart.split('m');
				if(timeMinutes.length>1){
					
					isTimeStart=timeMinutes[1];
					timeMinutes=60*timeMinutes[0];
					
					isTimeStart=Math.round((1*isTimeStart)+(1*timeMinutes));
					
				}
				
				
				embedCode+='&start='+isTimeStart;//+'s';
				
			



			}
			
			
			embedCode+='" allowscriptaccess="always" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen" id="kvideohelp"></iframe></div>';
			jQuery("#kopageBarFAQ_Content").html(embedCode);
		}
		
		
		
		
		
		
		
	} else {
		
	var theID = url.split('ihelp=');
	// now load contents...
	jQuery.ajax({
		 type: "POST",
		 url: "index.php",
		 data: "supermode=ihelp&ihelp_id="+encodeURIComponent(theID[1]),
		 success: function(data){
			 
			var faqContentArea=$("#kopageBarFAQ_Content");
			faqContentArea.hide().html(data).fadeIn();
			
			// in case if content is too wide, apply overflow:
			var cHeight=Math.round($(window).height() - (faqContentArea.offset().top-$(window).scrollTop()));
			faqContentArea.css({'overflow':'auto','overflow-x':'hidden','top':faqContentArea.offset().top,'right':0,'width':'400px','height':cHeight}).scrollTop(0)
			
			
				  
			}
		});
		
		
	}
	
	return;
	
	//window.open(url,"ibox",'width=500,height=400,top=30,left=30,');return;
	$.fancybox({
			'padding'		: 10,
			'autoScale'		: false,
			//'transitionIn'	: 'none',
			//'transitionOut'	: 'none',
			//'title'			: this.title,
			'width'		    : ihBox_WIDTH,
			'height'		: ihBox_HEIGHT,
			'href'			: url,
			'type'			: 'iframe'
		});

	return false;
	
}

function ihBox_hide() {
    $("#ihBox_window,#ihBox_overlay").fadeOut(300, function () {
        $("#ihBox_window,#ihBox_overlay").hide()
    })
}
function ihBox_position() {
    var de = document.documentElement;

    var w = self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
    var h = self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;

    $("#ihBox_window").css({
        width: ihBox_WIDTH + "px",
        height: ihBox_HEIGHT + "px",
        left: ((w - ihBox_WIDTH) / 2) + "px",
        top: ((h - ihBox_HEIGHT) / 2) + "px"
    });

    $("#ihBox_frame").css("height", ihBox_HEIGHT - 32 + "px")
}
/*jGlideMenu/067*/
$.jGlideMenu = {
    useDropShadow: new Boolean(),
    useDragDrop: new Boolean(),
    defaultScrollSpeed: new Number(0),
    defaultScrollBackSpeed: new Number(0),
    slideRight: new Boolean(),
    useSmoothScrolling: new Boolean(),
    easeFx: new String(''),
    closeLinkMarkUp: new String(''),
    menuShowFx: new String(''),
    menuHideFx: new String(''),
    tileWidth: new Number(0),
    noClose: new Number(0),
    captionBack: new String(''),
    captionHelpdesk: new String(''),
    labelHelpdesk: new String(''),
    tileInset: new Number(0),
    itemsToDisplay: new Number(8),
    useTileURL: new Boolean(),
    tileSource: new String(''),
    URLParams: new Object(),
    loadImage: new String(''),
    onClickAction: new String(''),
    loadImageStyle: new Object(),
    initialTile: new String(''),
    alertOnError: new Boolean(),
    captureLinks: new Boolean(),
    imagePath: new String(),
    tileCount: new Number(0),
    animation: new Boolean(),
    helperImage: new Boolean(),
    currentElement: new Object(),
    currentElementID: new String(''),
    hasDragDropSupport: new Boolean(),
    hasShadowSupport: new Boolean(),
    displayToggle: new Boolean(),
    tileScrollPosition: new Array(),
    smoothScrollTimer: new Array(),
    mouseHover: new Boolean(),
    demoMode: new Boolean(),
    initialize: function (o) {
        return this.each(function () {
			
            $.jGlideMenu.animation = false;

            $.jGlideMenu.helperImage = false;

            $.jGlideMenu.hasDragDropSupport = false;

            $.jGlideMenu.hasShadowSupport = false;

            $.jGlideMenu.tileCount = 0;

            $.jGlideMenu.displayToggle = false;

            $.jGlideMenu.mouseHover = false;

            $.jGlideMenu.demoMode = false;

            $.jGlideMenu.currentElement = $(this);

            if (this.id) $.jGlideMenu.currentElementID = this.id;

            var s = {
                itemsToDisplay: 8,
                noClose: 0,
                captionBack: "Back",
                captionHelpdesk: "Helpdesk",
                labelHelpdesk: "",
                tileInset: 7,
                tileWidth: 400,
                useDropShadow: false,
                slideRight: true,
                useDragDrop: true,
                useSmoothScrolling: true,
                useTileURL: false,
                defaultScrollSpeed: 750,
                defaultScrollBackSpeed: 800,
                tileSource: 'myTiles',
                URLParams: {},
                closeLinkMarkUp: 'Close',
                menuShowFx: 'fadeIn',
                menuHideFx: 'fadeOut',
                easeFx: 'linear',
                loadImage: 'img/ajax.gif',
                initialTile: 'tile_001',
                alertOnError: false,
                captureLinks: true,
                loadImageStyle: {
                    'position': 'absolute',
                    'bottom': '10px',
                    'left': '10px',
                    'z-index': '999'
                },
                imagePath: 'img/',
                demoMode: false
            };

            if (o) $.extend(s, o);

            $.jGlideMenu.checkFeatures();

            if (s.closeLinkMarkUp.length < 1) s.closeLinkMarkUp = 'x Close';

            if (parseInt(s.itemsToDisplay) < 1) s.itemsToDisplay = 1;

            if (s.initialTile.length < 0) $.jGlideMenu.errorTrap('Invalid Configuration');

            for (i in s) $.jGlideMenu[i] = s[i];

            if ($.jGlideMenu.useTileURL == false && $.jGlideMenu.tileSource.length > 0) {
                $($.jGlideMenu.tileSource).css('display', 'none');

                $($.jGlideMenu.currentElement).children().not($.jGlideMenu.tileSource).remove()
            } else $($.jGlideMenu.currentElement).html('');

            $($.jGlideMenu.currentElement).append('<div style="overflow:hidden;position:fixed;top:50px;bottom:0;right:0;width:400px;"><div class="jGM_wrapper" id="jGM_wrapper_' + this.id + '"></div></div>');
			
            var a = document.createElement('img');

            a.src = $.jGlideMenu.loadImage;

            a.style.display = 'none';

            a.className = 'jGM_helper';

            a.id = 'jGM_helper' + $.jGlideMenu.currentElementID;

            $($.jGlideMenu.currentElement).append(a);

            $('img#' + a.id).css($.jGlideMenu.loadImageStyle);

            $.jGlideMenu.helperImage = true;


            $($.jGlideMenu.currentElement).find('div.jGM_header a').bind('click', function () {
                if ($.jGlideMenu.displayToggle == true) return false;

                $.jGlideMenu.toggleDisplay(true);

                return false
            });

            $(this).hover(function () {
                $.jGlideMenu.mouseHover = true
            }, function () {
                $.jGlideMenu.mouseHover = false
            });

            $.jGlideMenu.loadTile($.jGlideMenu.initialTile, $.jGlideMenu.URLParams)
        })
    },


    /*
     
     
     VISITOR CLICK HELP BAR, 
     help menu appears.
     
     
     */



    toggleDisplay: function (r) {


        if ($.jGlideMenu.noClose == 1) return false;

        $.jGlideMenu.displayToggle = true;

        $.jGlideMenu.mouseHover == false;

        if ($($.jGlideMenu.currentElement).css('display') == 'block') var a = 0;

        else var a = 1;

        if (r == true) {

            $.jGlideMenu.scrollToTile(0, $.jGlideMenu.defaultScrollBackSpeed);

            $.jGlideMenu.tileScrollPosition[0] = 0
        }
        if (a > 0) {
            $(".jGM_content").css({
                'overflow': 'hidden'
            });

            $($.jGlideMenu.currentElement).slideDown(function () {
                $(".jGM_content").css({
                    'overflow': 'scroll',
                    'overflow-x': 'hidden'
                })
            })
        } else {
            $(".jGM_content").css({
                'overflow': 'hidden'
            });

            $($.jGlideMenu.currentElement).slideUp(function () {
                $.jGlideMenu.scrollToTile(1, $.jGlideMenu.defaultScrollBackSpeed)
            })
        }
        $.jGlideMenu.displayToggle = false
    },
    checkFeatures: function () {
        $.jGlideMenu.hasDragDropSupport = $.isFunction($('body').Draggable);

        if ($.jGlideMenu.hasDragDropSupport == false) $.jGlideMenu.hasDragDropSupport = $.isFunction($('body').draggable);

        $.jGlideMenu.hasShadowSupport = $.isFunction($('body').dropShadow);

        if ($.jGlideMenu.hasShadowSupport == false) $.jGlideMenu.hasShadowSupport = $.isFunction($('body').shadow);

        return
    },
    parseURL: function (u) {
		
		return u;

        if (!$.browser.msie) {
            return u
        }
        if (u.indexOf('#tile_') < 0) {
            return u
        }
        var a = u.split('#');

        return '#' + a[(a.length - 1)]
    },
    countTiles: function () {
        $.jGlideMenu.tileCount = parseInt($('div.jGM_tile').size())
    },
    loadTile: function (u, p) {
        var c = document.createElement('div');

        var d = $.jGlideMenu.tileCount + 1;

        c.id = 'jGM_tile_' + $.jGlideMenu.currentElementID + '_' + d;

        if ($.jGlideMenu.slideRight == true) var e = $.jGlideMenu.tileWidth * $.jGlideMenu.tileCount + $.jGlideMenu.tileInset;

        else var e = $.jGlideMenu.tileWidth * $.jGlideMenu.tileCount * -1 + $.jGlideMenu.tileInset;

        $('#jGM_wrapper_' + $.jGlideMenu.currentElementID).append(c);

        $('#' + c.id).addClass('jGM_tile').css({
			left: e + 'px',
            height: $(window).height() - 70 + 'px',
            /*top: '5px',
            left: e + 'px',
            height: '340px',
            width: '380px',
            position: 'absolute',
            overflow: 'hidden',
            margin: 0,
            padding: 0,
            border: 0,//'2px solid blue',
            display: 'block'*/
        });

        var f = '<div style="height:100%;margin:0;border:0;width:100%;padding:0;text-align:center;">' + '<h3>Missing Tile</h3><p>Unable to locate the requested Tile</p></div>';

        if ($.jGlideMenu.useTileURL == false) {
            if ($('ul#' + u).size() < 1) {
                $.jGlideMenu.errorTrap('Invalid Tile Request');

                return false
            }
            var g = $('ul#' + u).attr('title');

            var h = $('ul#' + u).attr('alt');

            var i = $('ul#' + u + ' li').size();

            var j = [];

            $('ul#' + u + ' li').each(function () {
                if ($('a', this).size() > 0) j[j.length] = [$('a', this).attr('href'), $('a', this).text(), 1];

                else j[j.length] = [$(this).attr('rel'), $(this).text(), 0]
            });

            var f = $.jGlideMenu.buildTile(g, h, j)
        } else {
            if ($.jGlideMenu.tileSource.length < 1) {
                $.jGlideMenu.errorTrap('Invalid AJAX Request');

                return false
            }
            var k = 'img#jGM_helper' + $.jGlideMenu.currentElementID;

            $(k).ajaxStart(function () {
                $(this).fadeIn()
            }).ajaxStop(function () {
                $(this).fadeOut()
            });

            p.tile = u;

            var l = $.jGlideMenu.tileSource;
			
            $('#asdad32e3rsd').append('<div id="jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + '" style="display:none;">' + l + '</div>');

            if ($('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + ' ul#' + u).size() < 1) {
                $.jGlideMenu.errorTrap('AJAX: Invalid Tile Request');

                return false
            }
            var g = $('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + ' ul#' + u).attr('title');

            var h = $('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + ' ul#' + u).attr('alt');

            var i = $('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + ' ul#' + u + ' li').size();

            var j = [];

            $('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile + ' ul#' + u + ' li').each(function () {
                if ($('a', this).size() > 0) j[j.length] = [$('a', this).attr('href'), $('a', this).text(), 1, $('a', this).attr('rel'), $('a', this).attr('class')];

                else j[j.length] = [$(this).attr('rel'), $(this).text(), 0]
            });

            $('#jGM_temp_' + $.jGlideMenu.currentElementID + p.tile).remove();

            f = $.jGlideMenu.buildTile(g, h, j);

            $(k).ajaxStart(function () {}).ajaxStop(function () {})
        }
        $('#' + c.id).html(f);

        $('#' + c.id + ' div.jGM_content a').bind('click', function () {
            var a = '';

            a = $.jGlideMenu.parseURL($(this).attr('href'));

            if (a.length < 1) {
                return false
            }
            if (a.substr(0, 1) == '#') {
                if ($.jGlideMenu.animation == true) return false;

                var b = a.substr(1, a.length - 1);

                $.jGlideMenu.loadTile(b, $.jGlideMenu.URLParams);

                return false
            } else {
                if ($.jGlideMenu.demoMode) {
                    alert('Navigation Requestion: ' + a)
                } else {
                    window.location.href = a
                }
                if ($.jGlideMenu.captureLinks == true) return false
            }
            return true
        });

        $.jGlideMenu.countTiles();
        if ($.jGlideMenu.tileCount > 1) {
            $('#' + c.id).append('<div class="jGM_back"><a href="javascript:void(null)" class="btn btn-default">&larr; '+$.jGlideMenu.captionBack+'</a></div>');
	
           // $('p.jGM_desc').text('or Find the best answer below...');
			
            $('#' + c.id + ' div.jGM_back').bind('click', function () {
                if ($.jGlideMenu.animation == true) return false;

                $.jGlideMenu.scrollToTile((d - 1), $.jGlideMenu.defaultScrollBackSpeed);

                return false
            });

            if ($.jGlideMenu.tileCount > 2) {
                $('#' + c.id).append('<div class="jGM_reset"><a href="#">&laquo; Home</a></div>');

                $('#' + c.id + ' div.jGM_reset').bind('click', function () {
                    if ($.jGlideMenu.animation == true) return false;

                    $.jGlideMenu.scrollToTile(1, $.jGlideMenu.defaultScrollBackSpeed);

                    return false
                })
            }
        }
			
        $.jGlideMenu.tileScrollPosition[d] = 0;

        $.jGlideMenu.drawPagers(c.id, $('#' + c.id + ' .jGM_content a').size());

        if ($.jGlideMenu.useSmoothScrolling == false) {
            $('#' + c.id + ' .jGM_pager a').click(function () {
                var a = 1;

                if ($(this).attr('rel') == 'Up') a = 0;

                $.jGlideMenu.scrollItems(a)
            })
        } else {
            $('#' + c.id + ' .jGM_pager a').hover(function () {
                var a = 1;

                if ($(this).attr('rel') == 'Up') a = 0;

                $.jGlideMenu.smoothScrollTimer[$.jGlideMenu.tileCount] = window.setInterval('$.jGlideMenu.scrollItems(' + a + ')', 250)
            }, function () {
                window.clearInterval($.jGlideMenu.smoothScrollTimer[$.jGlideMenu.tileCount])
            })
        }
        $.jGlideMenu.scrollToTile(d, $.jGlideMenu.defaultScrollSpeed)
    },
    scrollItems: function (d) {
        var x = '#jGM_tile_' + $.jGlideMenu.currentElementID + '_' + $.jGlideMenu.tileCount;

        var s = $(x + ' .jGM_content a');

        var c = $.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount];

        if (c <= 0 && d == 0) return;

        if (c + $.jGlideMenu.itemsToDisplay >= $(s).size() && d == 1) return;

        if (d == 0) $.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount]--;

        else $.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount]++;

        $(s).show();

        $(x + ' .jGM_content').children('a:lt(' + $.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount] + ')').hide();

        $.jGlideMenu.drawPagers(x.substr(1, x.length), $(s).size())
    },
    drawPagers: function (p, c) {
        $('#' + p + ' .jGM_pager').find('a').each(function () {
            if ($(this).attr('rel') == 'Up') {
                if ($.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount] > 0) $(this).css('display', 'block');

                else $(this).css('display', 'none')
            } else {
                if ($.jGlideMenu.tileScrollPosition[$.jGlideMenu.tileCount] + $.jGlideMenu.itemsToDisplay < $('#' + p + ' .jGM_content a').size()) $(this).css('display', 'block');

                else $(this).css('display', 'none')
            }
        })
    },
    cleanTiles: function (n) {
        var a = n + 1;

        var b = $.jGlideMenu.tileCount;

        if (n >= b) return false;

        for (var i = a; i <= b; i++) {
			
            $('#jGM_tile_' + $.jGlideMenu.currentElementID + '_' + i).remove();

            $.jGlideMenu.tileScrollPosition[i] = 0
        }
        $.jGlideMenu.countTiles();

        return
    },
    scrollToTile: function (n, s) {
        $.jGlideMenu.countTiles();

        var t = $.jGlideMenu.tileCount;

        if (n > t) n = t;

        if (n < 1) n = 1;

        if (!s) s = $.jGlideMenu.defaultScrollSpeed;

        var b = ($.jGlideMenu.tileWidth * n) - $.jGlideMenu.tileWidth;

        var a = ($.jGlideMenu.slideRight == true) ? b * -1 : b;

        $.jGlideMenu.animation = true;

        $(".jGM_content").css({
            'overflow': 'scroll',
            'overflow-x': 'hidden'
        });

        $('div#jGM_wrapper_' + $.jGlideMenu.currentElementID).animate({
            'left': a
        }, s, $.jGlideMenu.easeFx, function () {
            if (n < t) $.jGlideMenu.cleanTiles(n);

            if (a != 0) a += 'px';

            $(this).css({
                'left': a
            });

            $.jGlideMenu.animation = false
        })
    },
    helpNow: function (a, b, c) {
		
		
		//alert(a+"\n"+b+"\n"+c);return
		if($.jGlideMenu.onClickAction=='close')
        	$.fn.jGlideMenuToggle();

        if (a == 1) {
            ihBox_show(unescape(c), b, a)
        } else if (a == 2) {
            window.open(b)
        } else { // if (a == 3) {
            ihBox_show(unescape(c), b, a)
        }
    },
    buildTile: function (t, d, l) {

        if ($.jGlideMenu.imagePath.length > 1 && $.jGlideMenu.imagePath.substr(-1, 1) != '/') $.jGlideMenu.imagePath += '/';

        var a = new String('');
        var aOpt;
        //
       // if (ihMember > 0) {
            // New version, with support tickets
            a = '<div class="jGM_cats_header"><a href="javascript:void(null)" onclick="$.jGlideMenu.helpNow(3,\'' + ihUrl + 'index.php?ihelp=1&help=contact&mid=' + ihMember + '&bid=' + ihBusiness + '&opt=ticket\',\'Submit a Support Ticket\')"><span></span>' + t + '<p class="jGM_desc">' + d + '</p></a></div>';
       /* } else {
            // OLD version, no support 
            a = '<div class="jGM_cats jGM_cats_header_no_support"><h1>' + t + '</h1><p class="jGM_desc">' + d + '</p></div>';
        }
		*/
        a += '<div class="jGM_content">';

        var b;

        for (var i = 0; i < l.length; i++) {

            var c = (l[i][2] == 1) ? '' : '#';

            var e = (l[i][2] == 1) ? '' : ' class="jGM_more"';

            if (l[i][3] != null && l[i][4] != null) {
                if (l[i][4] == 'redirect') {
                    a += '<a href="javascript:void(null)" onclick="$.jGlideMenu.helpNow(2,\'' + l[i][3] + '\',\'' + escape(l[i][1]) + '\')">' + l[i][1] + '</a>'
                } else if (l[i][4] == 'flash') {
                    a += '<a href="javascript:void(null)" onclick="$.jGlideMenu.helpNow(3,\'' + l[i][3] + '\',\'' + escape(l[i][1]) + '\')">' + l[i][1] + '</a>'
                } else if (l[i][4] == 'video') {
                    a += '<a href="javascript:void(null)" onclick="$.jGlideMenu.helpNow(4,\'' + l[i][3] + '\',\'' + escape(l[i][1]) + '\')">' + l[i][1] + '</a>'
                } else {
                    a += '<a href="javascript:void(null)" onclick="$.jGlideMenu.helpNow(1,\'' + l[i][3] + '\',\'' + escape(l[i][1]) + '\')">' + l[i][1]+'</a>'
                }
            } else {
                a += '<a href="' + c + l[i][0] + '"' + e + '>' + l[i][1] + '</a>'
            }

        }
        a += '</div>';

        return a
    },
    errorTrap: function (m) {

        if ($.jGlideMenu.alertOnError == true) alert(m);

        return
    }

};

$.fn.jGlideMenu = $.jGlideMenu.initialize;

$.fn.jGlideMenuToggle = $.jGlideMenu.toggleDisplay;
$.fn.startIzzyHelp = $.jGlideMenu.toggleDisplay;

$.fn.reverse = function () {
    return this.pushStack(this.get().reverse(), arguments)
};