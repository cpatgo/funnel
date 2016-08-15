//** All Levels Navigational Menu- (c) Dynamic Drive DHTML code library: http://www.dynamicdrive.com
//** Script Download/ instructions page: http://www.dynamicdrive.com/dynamicindex1/ddlevelsmenu/
//** Usage Terms: http://www.dynamicdrive.com/notice.htm

//** Current version: 2.2 See changelog.txt for details



var moveLeft={};
var moveUp={};
var ddlevelsmenu={


enableshim: false,//true, //enable IFRAME shim to prevent drop down menus from being hidden below SELECT or FLASH elements? (tip: disable if not in use, for efficiency)

arrowpointers:{
	//downarrow: ["ddlevelsfiles/arrow-down.gif", 11,7], //[path_to_down_arrow, arrowwidth, arrowheight]
	downarrow: ["ddlevelsfiles/spacer.gif", 0,0],
	rightarrow: ["ddlevelsfiles/spacer.gif", 0,0],
	//rightarrow: ["ddlevelsfiles/arrow-right.gif", 12,12], //[path_to_right_arrow, arrowwidth, arrowheight]
	//showarrow: {toplevel: true, sublevel: true} //Show arrow images on top level items and sub level items, respectively?
	showarrow: {toplevel: false, sublevel: false}
},
hideinterval: 500, //delay in milliseconds before entire menu disappears onmouseout.
effects: {enableswipe: false, enablefade: false, duration: 300},
//effects: {enableswipe: true, enablefade: true, duration: 200},
httpsiframesrc: "blank.htm", //If menu is run on a secure (https) page, the IFRAME shim feature used by the script should point to an *blank* page *within* the secure area to prevent an IE security prompt. Specify full URL to that page on your server (leave as is if not applicable).

///No need to edit beyond here////////////////////

topmenuids: [], //array containing ids of all the primary menus on the page
topitems: {}, //object array containing all top menu item links
subuls: {}, //object array containing all ULs
lastactivesubul: {}, //object object containing info for last mouse out menu item's UL
topitemsindex: -1,
ulindex: -1,
hidetimers: {},//{}, //object array timer
shimadded: false,
nonFF: !/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent), //detect non FF browsers
getoffset:function(what, offsettype){
	

	return (what.offsetParent)? what[offsettype]+this.getoffset(what.offsetParent, offsettype) : what[offsettype]
},

getoffsetof:function(el,mainmenuid){
	
	var gpos = jQuery(el).position();

	//el._offsets={left:this.getoffset(el, "offsetLeft")+parseInt(moveLeft[mainmenuid]), top:this.getoffset(el, "offsetTop")+parseInt(moveUp[mainmenuid])}

	el._offsets={left:gpos.left+parseInt(moveLeft[mainmenuid]), top:gpos.top+parseInt(moveUp[mainmenuid])}
},

getwindowsize:function(){
	//this.docwidth=window.innerWidth? window.innerWidth-10 : this.standardbody.clientWidth-10;
	//this.docheight=window.innerHeight? window.innerHeight-15 : this.standardbody.clientHeight-18
	this.docwidth=jQuery(window).width()-25; // (-25 to have less tolerance, to avoid menu close to end of browser...)
	this.docheight=jQuery(window).height()-25;
},

gettopitemsdimensions:function(){
	for (var m=0; m<this.topmenuids.length; m++){
		var topmenuid=this.topmenuids[m];
		for (var i=0; i<this.topitems[topmenuid].length; i++){
			var header=this.topitems[topmenuid][i];
			
			if (typeof header!== 'undefined' ){ 

			var submenu=document.getElementById(header.getAttribute('rel'));
			if (submenu!== null ) 
			header._dimensions={w:header.offsetWidth, h:header.offsetHeight, submenuw:submenu.offsetWidth, submenuh:submenu.offsetHeight}
			
			}
			
		}
	}
	//alert('gettopitemsdimensions: '+header._dimensions.w+', '+header._dimensions.h+', '+header._dimensions.submenuw+', '+header._dimensions.submenuh);

},

isContained:function(m, e){
	var e=window.event || e;
	var c=e.relatedTarget || ((e.type=="mouseover")? e.fromElement : e.toElement);
	while (c && c!=m)try {c=c.parentNode} catch(e){c=m}
	if (c==m)
		return true;
	else
		return false;
},

addpointer:function(target, imgclass, imginfo, BeforeorAfter){
	var pointer=document.createElement("img");
	pointer.src=imginfo[0];
	pointer.style.width=imginfo[1]+"px";
	pointer.style.height=imginfo[2]+"px";
	if(imgclass=="rightarrowpointer"){
		pointer.style.left=target.offsetWidth-imginfo[2]-2+"px"
	}
	pointer.className=imgclass;
	var target_firstEl=target.childNodes[target.firstChild.nodeType!=1? 1 : 0]; //see if the first child element within A is a SPAN (found in sliding doors technique)
	if (target_firstEl && target_firstEl.tagName=="SPAN"){
		target=target_firstEl //arrow should be added inside this SPAN instead if found
	}
	if (BeforeorAfter=="before")
		target.insertBefore(pointer, target.firstChild);
	else
		target.appendChild(pointer);
},

css:function(el, targetclass, action){
	var needle=new RegExp("(^|\\s+)"+targetclass+"($|\\s+)", "ig");
	if (action=="check")
		return needle.test(el.className);
	else if (action=="remove")
		el.className=el.className.replace(needle, "");
	else if (action=="add" && !needle.test(el.className))
		el.className+=" "+targetclass;
},

addshimmy:function(target){
	var shim=(!window.opera)? document.createElement("iframe") : document.createElement("div"); //Opera 9.24 doesnt seem to support transparent IFRAMEs
	shim.className="ddiframeshim";
	shim.setAttribute("src", location.protocol=="https:"? this.httpsiframesrc : "about:blank");
	shim.setAttribute("frameborder", "0");
	target.appendChild(shim);
	try{
		shim.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)'
	}
	catch(e){}
	return shim
},

positionshim:function(header, submenu, dir, scrollX, scrollY){
	if (header._istoplevel){
		var scrollY=window.pageYOffset? window.pageYOffset : this.standardbody.scrollTop;
		var topgap=header._offsets.top-scrollY;
		var bottomgap=scrollY+this.docheight-header._offsets.top-header._dimensions.h;
		if (topgap>0){
			this.shimmy.topshim.style.left=scrollX+"px";
			this.shimmy.topshim.style.top=scrollY+"px";
			this.shimmy.topshim.style.width="99%";
			this.shimmy.topshim.style.height=topgap+"px" //distance from top window edge to top of menu item
		}
		if (bottomgap>0){
			this.shimmy.bottomshim.style.left=scrollX+"px";
			this.shimmy.bottomshim.style.top=header._offsets.top + header._dimensions.h +"px";
			this.shimmy.bottomshim.style.width="99%";
			this.shimmy.bottomshim.style.height=bottomgap+"px" //distance from bottom of menu item to bottom window edge
		}
	}
},

hideshim:function(){
	this.shimmy.topshim.style.width=this.shimmy.bottomshim.style.width=0;
	this.shimmy.topshim.style.height=this.shimmy.bottomshim.style.height=0
},



/* =======================================================================




			BUILD a SUBMENU - this was called when page was loaded,
			it'll build a submenu struture and prepare it to show/hide




======================================================================= */



buildmenu:function(mainmenuid, realpageid, header, submenu, submenupos, istoplevel, dir){


	header._master=mainmenuid; //Indicate which top menu this header is associated with
	header._pos=submenupos; //Indicate pos of sub menu this header is associated with
	header._istoplevel=istoplevel;
	
	var themeType=1;
	var realDir;
	
	if(istoplevel)realDir=dir;
	
	if($("html").hasClass('koTheme4'))
	themeType=2
	
	if (istoplevel){
	
		if(themeType == 2){ // vertical menu
			$(header).find('span').append(' <i class="fa fa-angle-right"></i>');
			dir='sidebar';
		}else
			$(header).find('span').append(' <i class="fa fa-angle-down"></i>');

		this.addEvent(header, function(e){
		ddlevelsmenu.hidemenu(ddlevelsmenu.subuls[this._master][parseInt(this._pos)])
		}, "click")
	}else
	dir ='sidebar'
	
	this.subuls[mainmenuid][submenupos]=submenu;
	
	header._dimensions={w:jQuery(header).width(), 
						h:jQuery(header).height(), 
						submenuw:jQuery(submenu).width(), 
						submenuh:jQuery(submenu).height()};
						
	// do some additional positioning (need to investigate)
	this.getoffsetof(header);
	
	// apply style to submenu
	jQuery(submenu).css({left:0,top:0,visibility:"hidden"});
	
	// on MOUSE-OVER...
	this.addEvent(header, function(e){
		
		if (!ddlevelsmenu.isContained(this, e)){
			var submenu=ddlevelsmenu.subuls[this._master][parseInt(this._pos)];
			
			if (this._istoplevel){
				ddlevelsmenu.css(this, "selected", "add");
				clearTimeout(ddlevelsmenu.hidetimers[this._master][this._pos])
			}
			
			// positioning...
			ddlevelsmenu.getoffsetof(header,mainmenuid);
			var scrollX=window.pageXOffset? window.pageXOffset : ddlevelsmenu.standardbody.scrollLeft;
			var scrollY=window.pageYOffset? window.pageYOffset : ddlevelsmenu.standardbody.scrollTop;
			var submenurightedge=jQuery(this).offset().left + this._dimensions.submenuw + (this._istoplevel && dir=="topbar"? 0 : this._dimensions.w);
			var submenubottomedge=jQuery(this).offset().top + this._dimensions.submenuh;
			//Sub menu starting left position
			var menuleft=(this._istoplevel? jQuery(this).offset().left + (dir=="sidebar"? this._dimensions.w : 0) : this._dimensions.w);
			
			if (submenurightedge-scrollX>ddlevelsmenu.docwidth){
				menuleft+= -this._dimensions.submenuw + (this._istoplevel && dir=="topbar" ? this._dimensions.w : -this._dimensions.w);
			}
			
			//Sub menu starting top position
			var menutop=(this._istoplevel? jQuery(this).offset().top + (dir=="sidebar"? 0 : this._dimensions.h) : jQuery(this).position().top);
			if (submenubottomedge-scrollY>ddlevelsmenu.docheight){ //no room downwards?
				if (this._dimensions.submenuh<jQuery(this).offset().top+(dir=="sidebar"? this._dimensions.h : 0)-scrollY){ //move up?
					menutop+= - this._dimensions.submenuh + (this._istoplevel && dir=="topbar"? -this._dimensions.h : this._dimensions.h)
				}
				else{ //top of window edge
					menutop+= -(jQuery(this).offset().top-scrollY) + (this._istoplevel && dir=="topbar"? -this._dimensions.h : 0)
				}
			}
			
			if (this._istoplevel)
				jQuery(submenu).css({top:menutop+parseInt(moveUp[mainmenuid]),left:menuleft+parseInt(moveLeft[mainmenuid])});
			else
				jQuery(submenu).css({top:menutop,left:menuleft});
			
			// is "shim" required here?
			if (ddlevelsmenu.enableshim && (ddlevelsmenu.effects.enableswipe==false || ddlevelsmenu.nonFF)){ 
				//apply shim immediately only if animation is turned off, or if on, in non FF2.x browsers
				ddlevelsmenu.positionshim(header, submenu, dir, scrollX, scrollY)
				
			// or, no "shim" required - default action
			} else{
				// apply additional position
				submenu.FFscrollInfo={x:scrollX, y:scrollY}
			}
			//alert(ddlevelsmenu.hidetimers)
            clearTimeout(ddlevelsmenu.hidetimers[this._master][this._pos]);
			ddlevelsmenu.showmenu(header, submenu, dir)
		}
	}, "mouseover");
	
	// MOUSE-OUT event
	this.addEvent(header, function(e){
		var submenu=ddlevelsmenu.subuls[this._master][parseInt(this._pos)];
		
		if (this._istoplevel){
			
			// hide drop down ul if mouse moves out of menu bar item but not into drop down ul itself
			if (!ddlevelsmenu.isContained(this, e) && !ddlevelsmenu.isContained(submenu, e)){ 

                ddlevelsmenu.hidetimers[this._master][this._pos]=setTimeout(function(){
					ddlevelsmenu.hidemenu(submenu);}, ddlevelsmenu.hideinterval);
			}
			
		}
		else if (!this._istoplevel && !ddlevelsmenu.isContained(this, e)){
        
			ddlevelsmenu.hidetimers[this._master][this._pos]=setTimeout(function(){
				ddlevelsmenu.hidemenu(submenu);}, ddlevelsmenu.hideinterval);
			
		}

	}, "mouseout")
},


setopacity:function(el, value){
	el.style.opacity=value;
	if (typeof el.style.opacity!="string"){ 
		el.style.MozOpacity=value;
		if (el.filters){
			el.style.filter="progid:DXImageTransform.Microsoft.alpha(opacity="+ value*100 +")"
		}
	}
},

/* =======================================================================




			SHOW SUBMENU - visitor just moved mouse on menu, item,
			submenu should appear now...




======================================================================= */


showmenu:function(header, submenu, dir){
	
    //jQuery("ul[id^='dropmenu_']").css('visibility','visible');
	
	// reset its position.
	var id="#"+submenu.id;
	//submenu.style.margin="0"; // not needed when using jQuery...
	//submenu.style.padding="0"; // as above
	if(jQuery(id).css('visibility')=='visible' && jQuery(id).css('display')=='block')
	return;
	jQuery(id).css({'margin':'0','padding':'0','visibility':"visible",'display':'none'});
	
	
	if (this.effects.enableswipe){
		jQuery(id).slideDown();
	} else if(this.effects.enablefade){
		jQuery(id).fadeIn();
	} else {
		jQuery(id).show();
	}
	
	return;
	
},



/* =======================================================================




			SHOW SUBMENU STEP #2 - show menu was launched, but if there is any effect in use,
			this function will make it work.




======================================================================= */



/* =======================================================================




			HIDE SUBMENU - there was a command set, to hide menu for some reason,
			sample reason: mouse-out




======================================================================= */



hidemenu:function(submenu){
	
	jQuery("#"+submenu.id).stop().hide();
	return;
	if (typeof submenu._pos!="undefined"){ //if submenu is outermost UL drop down menu
		this.css(this.topitems[submenu._master][parseInt(submenu._pos)], "selected", "remove");
		if (this.enableshim)
			this.hideshim();
	}
	
	submenu.style.left="-1000px";
	submenu.style.top="-1000px";
	submenu.style.visibility="hidden"
},


addEvent:function(target, functionref, tasktype) {
	if (target.addEventListener)
		target.addEventListener(tasktype, functionref, false);
	else if (target.attachEvent)
		target.attachEvent('on'+tasktype, function(){return functionref.call(target, window.event)});
},



init:function(mainmenuid, dir){
	
	// mainmenuid = menu ID, by default it's #1

	
	this.standardbody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body;
	this.topitemsindex=0;
	this.ulindex=0;

	this.topmenuids.push(mainmenuid);
	this.topitems[mainmenuid]=[]; //declare array on object
	this.subuls[mainmenuid]=[]; //declare array on object
	this.hidetimers[mainmenuid]=[]; //declare hide entire menu timer
	
	// is "shimmy" in use? For IE6 only...
	if (this.enableshim && !this.shimadded){
		this.shimmy={};
		this.shimmy.topshim=this.addshimmy(document.body); //create top iframe shim obj
		this.shimmy.bottomshim=this.addshimmy(document.body); //create bottom iframe shim obj
		this.shimadded=true
	}
    
	// get all links which call to our dropdown menu
    var alllinks=jQuery("a[rel^='dropmenu_"+mainmenuid+"_']");
    
	// get current size of this window.
	this.getwindowsize();
	
	// now, for each link which is calling to any menu,
	// create a submenu and link them together.
	
	for (var i=0; i<alllinks.length; i++){
		
		var temp = new Array();
		
		// if only we can get "rel" value, we can continue
		if(alllinks[i].getAttribute('rel')){

			var menuitem=alllinks[i];
			temp = alllinks[i].getAttribute('rel').split('_');
			this.realpageid=temp[1]+'_'+temp[2];
	
			this.topitemsindex++;
			this.ulindex++;
			this.topitems[mainmenuid][this.topitemsindex]=menuitem; //store ref to main menu links

			// add some style to our submenu:
			jQuery("#"+menuitem.getAttribute('rel')).css({'z-index':'2000'});

													
			var dropul=document.getElementById(menuitem.getAttribute('rel'));
						
			dropul._master=mainmenuid;  //Indicate which main menu this main UL is associated with
			dropul._pos=this.topitemsindex; //Indicate which main menu item this main UL is associated with
			this.addEvent(dropul, function(){ddlevelsmenu.hidemenu(this)}, "click");
			var arrowclass=(dir=="sidebar")? "rightarrowpointer" : "downarrowpointer";
			var arrowpointer=(dir=="sidebar")? this.arrowpointers.rightarrow : this.arrowpointers.downarrow;
			
			this.buildmenu(mainmenuid, this.realpageid, menuitem, dropul, this.ulindex, true, dir); //build top level menu
			
			dropul.onmouseover=function(){
				clearTimeout(ddlevelsmenu.hidetimers[this._master][this._pos])
			};
			this.addEvent(dropul, function(e){ //hide menu if mouse moves out of main UL element into open space
            
				if (!ddlevelsmenu.isContained(this, e) && !ddlevelsmenu.isContained(ddlevelsmenu.topitems[this._master][parseInt(this._pos)], e)){
					var dropul=this;
					if (ddlevelsmenu.enableshim)
						ddlevelsmenu.hideshim();
						
					ddlevelsmenu.hidetimers[this._master][this._pos]=setTimeout(function(){ddlevelsmenu.hidemenu(dropul);}, ddlevelsmenu.hideinterval)
				}
			}, "mouseout");
			
			
			// does this submenu have another submenu below?
			var subuls=dropul.getElementsByTagName("ul");
			
			// for each submenu below, create additional menu
			for (var c=0; c<subuls.length; c++){
				
				//this.ulindex++;
				var parentli=subuls[c].parentNode;
				
				this.topitemsindex++;
				this.ulindex++;
				this.topitems[mainmenuid][this.topitemsindex]=parentli; //store ref to main menu links
			
				
				// add right-arrow image, if in use...
				// we'll skip that.
				this.buildmenu(mainmenuid, this.realpageid, parentli, subuls[c], this.ulindex, false, dir) //build sub level menus
			}
		}
	} //end for loop
	this.addEvent(window, function(){ddlevelsmenu.getwindowsize(); ddlevelsmenu.gettopitemsdimensions()}, "resize")
},

setup:function(mainmenuid,dir,mleft,mup){
	
	// mainmenuid = menu ID, default menu is always #1
	
	jQuery('.dropfirst').prependTo('body');
	
	
	jQuery(function(){
					
			moveLeft[mainmenuid]=mleft;
			moveUp[mainmenuid]=mup;
			ddlevelsmenu.init(mainmenuid, dir);
	
	})

}

}







;(function($) {
	
    $._mobileMenu = function(e, options) {
		
        var d = {

            menuMaxWidth: 750,
            menuCaption: menuCaption//"Select a Page"

        }

        var p = this;
        var $e = $(e),e = e;
			
			
		p.goTo = function(e){
	
	
			// Crossbrowser issues - http://www.quirksmode.org/js/events_properties.html
			var targ;
			if (!e) e = window.event;
			if (e.target) targ = e.target;
			else if (e.srcElement) targ = e.srcElement;
			if (targ.nodeType === 3) // defeat Safari bug
				targ = targ.parentNode;
	
				if(targ.value) window.location.href = targ.value;
				
    	}

		
	
        p.init = function() {
			
			var mobileMenu;
			if($("#headerMenu").hasClass('koMenu6') || $("#headerMenu").hasClass('koMenu8'))
			var mobileMenu=true;
			
			if($(window).innerWidth()>d.menuMaxWidth && mobileMenu!=true){
				
				//$(document.body).on("touchmove", true);
				$("#mobileMenu").remove();
				$('body').removeClass('mobileMenuOpen_Body');
				$("#mobileMenuBodyHolder").contents().unwrap();
				$(e).show();
				
				return;
				
			} else {
				
				if($("#mobileMenu").length==0){
				$(e).before('<div id="mobileMenu"></div>').hide();
				
				if(mobileMenu){
					// show stack icon instead default menu./mobileMenuOpen
					$(e).before('<ul class="menuHolder menuStack topmenu"><li><a href="javascript:void(null)" class="" onclick="$(\'#mobileMenuHolder, .mobileMenuClose\').show();$(\'.mobileMenuOpen\').hide();$(window).scrollTop(0);$(\'#mobileMenuBodyHolder\').addClass(\'menuSlide\')/*$(\'body\').css({\'overflow\':\'hidden\'}).on(\'touchmove\', false);$(\'.mobileMenu\').on(\'touchmove\', true);*/"><i class="fa fa-bars fa-lg"></i></a></li></ul>')
					
					
						
				}
				
				}
				else
				$(e).hide();
				
				$('body').addClass('mobileMenuOpen_Body');
				
				$_mobileMenu='<div id="mobileMenu"><a href="javascript:void(null)" class="mobileMenuClose" onclick="$(\'#mobileMenuHolder, .mobileMenuClose\').hide();$(\'.mobileMenuOpen\').show();$(\'#mobileMenuBodyHolder\').removeClass(\'menuSlide\')/*$(\'body\').css(\'overflow\',\'auto\').on(\'touchmove\', true);*/"><i class="fa fa-times"></i></a>';
							
				// hide for menu#6, as it's using mobile opener by default
				if(mobileMenu){
					// show stack icon instead default menu.
					
										
				} else {
				$_mobileMenu+='<a href="javascript:void(null)" class="mobileMenuOpen" onclick="$(\'#mobileMenuHolder, .mobileMenuClose\').show();$(\'.mobileMenuOpen\').hide();$(window).scrollTop(0);$(\'#mobileMenuBodyHolder\').addClass(\'menuSlide\')/*$(\'body\').css({\'overflow\':\'hidden\'}).on(\'touchmove\', false);$(\'.mobileMenu\').on(\'touchmove\', true);*/"><i class="fa fa-bars"></i></a>';
				}
				
				
				$_mobileMenu+='<div id="mobileMenuHolder"><ul class="mobileMenu">';
				
				$mobileMenu='<select style="width:100%;" id="mobileMenuSelect">';
				
				$mobileMenu+='<option>'+d.menuCaption+'</option>';
			
				$(e).find('a').each(function(index){
					$mobileMenu+='<option value="'+$(this).attr('href')+'">' +$(this).text()+'</option>';
					$_mobileMenu+='<li><a';
					
					
					
						if($(this).parent().hasClass('item_active'))
							$_mobileMenu+=' class="mobileActive"'
							
					$_mobileMenu+=' href="'+$(this).attr('href')+'">' +$(this).text()+'<i class="icon-chevron-right pull-right"></i></a>';
					
					$thisSubpage=$(this).attr('rel');
					$_mMCount=0;
					$_mobileSubmenuMenu='';
					$("#"+$thisSubpage+" a").each(function(index){
					
						$mobileMenu+='<option value="'+$(this).attr('href')+'"> - '+$(this).text()+'</option>';
					
						$_mMCount++;
						$_mobileSubmenuMenu+='<li><a';
						
						if($(this).parent().hasClass('item_active'))
							$_mobileSubmenuMenu+=' class="mobileActive"'
							
						
						$_mobileSubmenuMenu+=' href="'+$(this).attr('href')+'"><i class="icon-arrow-right"></i>' +$(this).text()+'</a></a></li>';
						
						
					});
					
					if($_mMCount > 0){
					
						$_mobileMenu+='<ul>'+$_mobileSubmenuMenu+'</ul>';
						
					}
					$_mobileMenu+='</li>';
				});
				
				$mobileMenu+='</select>';
				$_mobileMenu+='</ul></div></div>';
								
				if($('.mobileMenu').length){
					
				} else {
				
				
				$("body").wrapInner('<div id="mobileMenuBodyHolder" />')
							
				$("#mobileMenuBodyHolder").before($_mobileMenu);//.on("touchmove", true);
				}

			}
        }
		
		$(window).resize(function(){p.init()});
		p.init();

    }

    $.fn._mobileMenu = function(options) {

        return this.each(function() {
			
            if (undefined == $(this).data('_mobileMenu')) {
				$(this).data('_mobileMenu', new $._mobileMenu(this, options));

            }

        });

    }

})(jQuery);

		
jQuery(function(){
		jQuery('ul.topmenu')._mobileMenu();})