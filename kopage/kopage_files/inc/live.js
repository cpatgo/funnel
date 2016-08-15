
var refreshWithSave=1;

function showYouTube(video){

	// create overlay:
	$("body").prepend('<div id="videoFrame" style="background:rgba(0,0,0,0.5);text-align:center;position:fixed;left:0;right:0;top:0;bottom:0;height:100vw;z-index:77770;vertical-align:middle"><iframe style="box-shadow:5px 5px 35px rgba(0,0,0,0.5);position:fixed;left:15vw;right:15vw;top:8%;display:block;margin:0;padding:10px;background:#fff;width:70vw;height:40vw;max-height:90%;z-index:77771;border:0;" src="'+video+'?autoplay=1&showinfo=0&rel=0&autohide=1&disablekb=1&modestbranding=1"></iframe><a href="javascript:void(null)" onclick="$(\'#videoFrame\').remove();" style="display:block;height:8vw;width:8vw;line-height:8vw;font-size:4vw;padding:0;text-align:center;color:#fff;background:#333;position:fixed;top:5%;right:5vw;border-radius:10vw;box-shadow:5px 5px 35px rgba(0,0,0,0.8);"><i class="fa fa-close"></i></a></div>')
	
}


//alert(self.document.location.hash)

function koLiveMessage(title,message,icon){
		
	
	var customID=Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
	var customTimeout;
	
	if(icon == 1) icon = 'tick.gif';
	
$("body").append('<div class="koLiveMessage koLiveMessageSuccess hide" id="koMessage_'+customID+'">'
+'<a class="koLiveMessageClose" href="javascript:void(null)" onclick="$(\'#koMessage_'+customID+'\').slideUp()"><?php echo addslashes(_Close)?></a>'
+'<img src="<?php echo FilexLocation?>editor_images/'+icon+'">'
+'<h3>'+title+'</h3>'
+'<p>'+message+'</p>'
+'</div>');

	$("#koMessage_"+customID).fadeIn();
	customTimeout=setTimeout('$("#koMessage_'+customID+'").slideUp()',10000); // disappear in 10 seconds
	
}

function escapeHtml(text) {
	if(typeof text == 'undefined')return '';
	
	
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}
var ktempCode;
var ktempCodeId=null;

// start with giving each editable section an ID,
// and add control box.


		function stretchyNavShow(stretchyNav,stretchyNavTrigger){
			stretchyNav.addClass('nav-is-visible');
			stretchyNavTrigger.removeClass('fa-cog').addClass('fa-close');
		}
		function stretchyNavHide(stretchyNav,stretchyNavTrigger){
				stretchyNav.removeClass('nav-is-visible');
				stretchyNavTrigger.removeClass('fa-close').addClass('fa-cog')
		}
		
		

function k_EditInit(e,i){
	
	
	
	var koparsedContent=0;
	// in some cases, content is just not editable...
	//if(typeof koparsedNotEditable!=='undefined'){
	if(koparsedNotEditable != false){
		
		if(koparsedNotEditable == 'SEARCH')
			return;
		else 
			var koparsedContent=1;
		
	}
	
	if($("#kEdit_"+i).length > 0) return; 
	
	var theRel=$(e).attr('rel');
	var moveButton=1;
	var removeButton=1;
	var k_EditClass='';
	var editButton='';
	var addButton='';
	
	if(typeof theRel!=='undefined'){

		// it's sidebar/splash/footer
		moveButton=0;
		removeButton=0;
		
	}
	
	
	addButton+='<li><a href="javascript:void(null);" onclick="kopageToolbarTabs_newContent(\''+i+'\')"><span>'+langPhrase.blockAdd+'</span><i class="fa fa-plus-circle"></i></a></li>';
	
	editButton+='<li><a href="javascript:void(null)" onclick="k_Edit(\'';
	
	if($(e).hasClass('keditFooter')){editButton+='keditFooterEditable';}
	else editButton+=i;
	
	editButton+='\')"><span>'+langPhrase.blockEdit+'</span><i class="fa fa-pencil-square-o"></i></a></li>';// class="k_EditEdit koBg1 koBgHover2"
	
	var settingsButton='<li><a href="javascript:void(null)" onclick="kopageToolbarTabs(\'Block\',\''+i+'\')"><span>'+langPhrase.blockSettings+'</span><i class="fa fa-cog"></i></a></li>';//class="k_EditSettings koBg3"

	// don't allow editing for blocks which are column layout only,
	// just move or remove.
	
	if($(e).hasClass('keditLayout') || $(e).hasClass('noedit'))
		editButton='';
	else if($(e).hasClass('keditRow')){
		
		//if(!$(e).hasClass('keditFooter')){
		//editButton='';
		//}
		k_EditClass=' keditLayout';
		
	}
	
	if($(e).hasClass('keditNoEdit'))return;
		
	if(moveButton == 0 && removeButton == 0)
		editButton='';

	//editButton+=settingsButton;

	var $keditControls = '<div class="k_Edit'+k_EditClass+'" id="kEdit_'+i+'">'
	
	+'<nav class="keditNav">'
	+'<a class="keditNavTrigger fa fa-cog" href="javascript:void(null)"></a>'
	+'<ul>'
	
	$keditControls+= addButton;
	$keditControls+= editButton;
	$keditControls+= settingsButton;
	
	if(koparsedContent==0){
	
		
	
		if(moveButton == 1)
		//$keditControls+='<a href="javascript:void(null)" class="k_Move handle koBg3"><i class="fa fa-arrows fa-lg"></i></a>';
		$keditControls+='<li class="k_Move"><a href="javascript:void(null)"><span>'+langPhrase.blockMove+'</span><i class="fa fa-arrows"></i></a></li>'


	
	}
	
	$keditControls+='</ul>'
						+'<span aria-hidden="true" class="stretchy-nav-bg"></span>'
						+'</nav>'+'</div>';
	$keditControls=$($keditControls);
	
	
	//$keditControls='<div class="k_Edit">'+t+'</div>';
	$(e).prepend($keditControls);
		
//
//	$(e).hover(function(){
//		
//		if(!$(e).hasClass('keditRow') || $(e).hasClass('keditFooter')){
//			
//		$(this).find('.k_EditEdit').addClass('animated fadeIn');
//		$(this).find('.k_EditSettings').addClass('animated fadeInLeft');
//		$(this).find('.k_Move').addClass('animated fadeInRight');
//			
//		}
//		
//	})
	
	return true;
	
	
}



	
$(".kedit").each(function(i) {
		
	var newId = 'kpg_';
	
	if(typeof $(this).attr('rel') !== 'undefined')
	newId += $(this).attr('rel');
	
	if(newId == 'kpg_')
		var newId='kpg_'+Math.floor(Math.random()*100)+i;
	
	if(typeof $(this).id == 'undefined')
		$(this).attr('id',newId);
		
	k_EditInit(this,newId);
	
});


	
function hideModalManager(){
	
	$('a.kopageToolbarTabsClose,a.kopageToolbarTabsApply,a.kopageToolbarTabsRemove').css({'left':''})
	
	// make BODY scrollable again.
	$("html, body").css('overflow','auto');
	
	$("#kopageFManager").attr({'class':null});
	$("#kopageFManager").addClass('animated fadeOutLeft');

}


function showModalManager(option,optionalType,optionOption){
	
	
	var whichFrame='';
		
	if(optionOption == 'img'){
		
		$("#managerApplyToImage").val(option);
		$("#managerApplyTo").val('');
		
	} else if(optionOption=='tinymce'){
		
		window.tinymceCallback=option;
		
	}else{
		$("#managerApplyTo").val(option);
		$("#managerApplyToImage").val('');
		
	}

	
	// hide close/save/remove buttons:
	$('a.kopageToolbarTabsClose,a.kopageToolbarTabsApply,a.kopageToolbarTabsRemove').css({'left':'-1000px'});
	
	// make BODY not-scrollable
	$("html, body").css({'overflow':'hidden'});

	$("#kopageToolbarMoreSettings").hide();
	$("#kopageFManager").hide().attr({'class':null,'style':null});//removeClass('animated fadeInLeft flipOutY flipInY');
	$("#kopageFManager").show().addClass('animated tdFadeInRight');

	
	if(optionOption=='tinymce' || optionOption == 'img')
		$('#kopageFManager').css({'left':'0px'});
	else
		$('#kopageFManager').css({'left':'250px'});



	if(managerMode == "guide"){
		
		$("#fileManagerUploadToFolder").val("guide");
		showFolder(dataPath+"data/files/guide","guide");
		
	} else {
		
		showFolder(true);
		
	}
	
}

function kAssetManager(callback, value, meta){
	
	showModalManager(callback,meta,"tinymce");
	return;
		
}



function k_EditCancel(){
	
	if(ktempCodeId != null){
		
		// there was some temporary ID set, and editing wasn't cancelled...
		
		$("#k_Edit").hide();
		$("#k_EditActive").remove();
		
		// and "un-init" live editor
		tinymce.execCommand('mceRemoveEditor', false, ktempCodeId);
		
		
		$("#"+ktempCodeId).html(ktempCode).removeClass('keditActive');
		ktempCodeId=null;
		
	}
	
}
var tinyMceMoreOptionsStatus=1;

function kopage_infoNotice(msg){
	
	
		new jBox('Notice', {
	
		theme: 'NoticeBorder',color:'green',
		content: msg,
		stack:true,fixed:true,animation:'tada',color:'green',
		position:{x:'right',y:'top'},offset:{x: -15, y: 15},
		
		});
	
}

// when all is loaded...

$(function(){
	
	
	// for admin only, set background to color-value fields 
	$('.kopageColor').each(function(){
		$(this).css('background',$(this).val())
	});
	
	$('.jBoxTooltip').jBox('Tooltip',{theme:'TooltipBorder',zIndex:'65601',animation:'zoomIn'});
	$('.jBoxTooltipRight').jBox('Tooltip',{theme:'TooltipBorder',zIndex:'65601',animation:'move',position: {x: 'right',y: 'center'},outside: 'x'});
	
	
	/*
	// *** this is being removed as it was needed for old themes only
	// if there are any default links in template, pointing to admin area,
	// make them open in lightbox.
	
	$(".container a[href^='admin.php?']").each(function(){
	
		var el=$(this);
		var eh=el.attr('href');
		el.attr({'onclick':"kopage_showFrame('<?php echo addslashes(kToolbarModal_Default)?>','"+eh+"')",'href':'javascript:void(null)'})
		
	})
	*/
	
	
	var moveType;
	
	
});



function k_EditSave(refreshOption){
	
	if($(".WxEditableArea").hasClass('WxNoSave'))return true;
	
	// if there's any "don't leave" warning, remove it.
	
	$(window).unbind('beforeunload');
	
	
	$("#dragHelper").attr('rel','');
	
	// first cancel any block, which is currently being edited:
	k_EditCancel();
	
	// save new content & refresh page.
	
	kopage_showLoading();
	
	// make it not sortable anymore...
	//$( ".WxEditableArea" ).sortable( "destroy" );

	$('.blockKeditActive').removeClass('blockKeditActive');
	$('.keditModuleEditPlaceholder').removeClass('keditModuleEditPlaceholder');
	
	// #1 - If there are modules on page, 
	// "unparse" them, so there are tags only...
	
	$("body").append('<div id="k_TempSave"></div>');
	$("#k_TempSave").html($(".WxEditableArea").html())
	
	$("#k_TempSave .koparsed").each(function() {
		var koparsedRel=$( this ).attr( "rel" );
		if(typeof koparsedRel !== 'undefined'){
			
			var koparsedAddon=$( this ).attr( "data-kopased-addon" )
				
			
				if(typeof koparsedAddon!== "undefined" && 
					koparsedAddon!='')
						koparsedRel+=','+koparsedAddon;	
			
			
			
			
		$( this ).replaceWith('{%'+koparsedRel+'%}');
		
		
		
		
		
		
		
		}else
		$( this ).replaceWith($( this ).html());
		
	});
	
	
	$("#k_TempSave .kedit").removeAttr('style').removeAttr('spellcheck').removeClass('noedit').removeClass('kedit_fw').removeClass('ui-sortable-helper');




	// remove separators ("Add content here" buttons)
	
	$("#k_TempSave .keditSeparator,#k_TempSave .keditLinkEdit, #k_TempSave #keditSeparatorAdd,#k_TempSave .keditPlaceholderEdit").remove()
	
		
	keditImageTags=$("#k_TempSave .kedit img");
	
	
	
	
	// if images were made editable, undo it before TinyMCE starts:
	var keditImageClear=$('#k_TempSave .keditImage').remove();
	if(keditImageClear.length > 0){
	
		$('#k_TempSave span.keditImageWrap').each(function(){
			$(this).replaceWith($(this).find('img'))
		})
		
		
	}
	
	  
	$("#k_TempSave .kedit").each(function() {
		var keditBg=$( this ).attr( "data-background" );
		var keditBgColor=$( this ).attr( "data-bgcolor" );
		if(typeof keditBgColor == 'undefined')keditBgColor='';
		if(typeof keditBg != 'undefined' && keditBg.length > 0)
			keditBgColor+=' url('+keditBg+') no-repeat center center;background-size:cover';
			
		$( this ).attr('style','background:'+keditBgColor);
		
		//$( this ).replaceWith('{%'+$( this ).attr( "rel" )+'%}');
		//else
		//$( this ).replaceWith($( this ).html());
		
	});
	
	// any modules templrary placeholders?
	$("#k_TempSave .keditPlaceholder").each(function() {
		
		// what's real module's code?
		var keditModule=$(this).find('span').text();
		$( this ).replaceWith(keditModule);
		
	});

	// #2 - remove all helpers
	
	$("#k_TempSave #k_EditActive").remove();
	$("#k_TempSave .k_Edit").remove();
	
	// #3 - remove template's helpers:
	
	$("#k_TempSave .adminTip").remove();
	
	// #4 - remove additional (system's) JavaScript
	
	$("#k_TempSave #jsMenusSetup").remove();
	
	$("#k_TempSave .kedit-drag-widget,#k_TempSave .kedit-data-widget").remove()
	
	// it seems to be ready, make AJAX call:
	
	var postNewContent=$("#k_TempSave").html();
	
	postNewContent=postNewContent.replace(' style="background:"','');
	
	if(postNewContent.trim() == '')
		postNewContent='<div class="kedit">{%sitemap%}</div>';

	//alert(postNewContent);return;

	jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 data: "supermode=configUpdate&liveEdit=1&liveOption=subpage&pageMenuId="+menuMenuId+"&pageId="+menuPageId+"&content="+encodeURIComponent(postNewContent.trim()),//
	 success: function(data){
		
		
			if(loginFirst(data)){
				
				
				// user isn't logged in, try to restore this content.
				
				
				return;
				
			}
		
			if(data == 'OK'){
			
				if(refreshOption=='norefresh'){
					// don't refresh, just hide loading page
					kopage_hideLoading();
				}else{
				
					refreshWindow();
				
				}
				
		 	}else if(data == 'OK-REFRESH'){
			
				refreshWindow();
				
				
		 	}else
				alert(data);
			
		}
	});
	
	
}





function guidGenerator() {
    var S4 = function() {
       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return (S4()+S4());//+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

var ID = function () {
  // Math.random should be unique because of its seeding algorithm.
  // Convert it to base 36 (numbers + letters), and grab the first 9 characters
  // after the decimal.
  return /*'_' + */Math.random().toString(36).substr(2, 9);
};

function kopageToolbarTabs_newContent(w){
	
	
	/*
	
	
	// now add new.
	$(".WxEditableArea .kedit").each(function(i){

		$(this).before('<div class="keditSeparator" id="'+ID()+'"><div class="koBg1"><a href="javascript:void(null)" onclick="keditSeparatorMore(this)"><span class="koBg1" title="Add content here"><i class="fa fa-plus"></i></span></a></div>'
		+'</div>');
	});
	
	
	*/
		
		
		
	var keditSeparatorId=ID();
		
	if(typeof w === "undefined"){
		
		var keditSeparatorSelector=$('.WxEditableArea');
		keditSeparatorSelector.prepend('<div class="keditSeparator" id="'+keditSeparatorId+'"></div>');
	
	} else {
	
		var keditSeparatorSelector=$('.WxEditableArea #'+w);
		keditSeparatorSelector.before('<div class="keditSeparator" id="'+keditSeparatorId+'"></div>');
	
	}
	keditSeparatorMore(keditSeparatorId)
}

function keditSeparatorAdd(t,i){
	
	
	$("#keditSeparatorAdd").replaceWith('<div class=keditItem></div>');
	keditExtraAdd(t);keditSeparators();
	
	$('body').removeClass('kopageEditingMode');
	
}
/*	echo '<a href="javascript:void(null)" class="btn btn-default draggableModule koBgHover2" data-kid="{%'.$itemId.'%}" rel="keditItem" data-module-opt="'.$itemVal[0].'" data-module-id="'.$itemId.'" onclick="keditSeparatorAdd(this)" style="position:relative;z-index:2;">';
*/

function keditSeparatorModal_add(i,ii){
	
	
	if(ii == 'gallery'){
			
		var galleryEffect=$("input[name=galleryEffect]:checked").val();
		var galleryId=$("#galleryId_"+i).val();
		
		if(galleryEffect == 2)
		galleryId+=',slideshow';
		else if(galleryEffect == 3)
		galleryId+=',collage';
		
		// now subpages options
		else if(galleryEffect == 20)
		galleryId+=',vertical';
		else if(galleryEffect == 21)
		galleryId+=',horizontal';
		
		$("#keditSeparatorAdd").replaceWith('<div class=keditItem></div>');
		//koZeroPadding alert('{%gallery_'+galleryId+'%}');//return true;
		updateNewKeditItem('{%'+galleryId+'%}','save');
		keditSeparators();
	
	}
	
}
		
function keditSeparatorModal(i){
	
	
	$('#keditSeparatorAdd').append('<div id="keditSeparatorModal" style="z-index:999;position:absolute;top:50px;left:0;bottom:0;right:0;background:#fff;padding:0;"><span style="padding:30px;">'+_langPreloading+'</span></div>');
	
	$(".keditMoreContent").css('opacity',0)

	var mcontents='';
	
	if(i=='gallery'){
		

		jQuery.ajax({
				 type: "POST",
				 url: "admin.php",
				 data: "supermode=moduleInfo&liveEdit="+encodeURIComponent(i),
				 success: function(data){
					 
					 if(loginFirst(data))return;
	  
						  if(loginFirst(data)){
							$("#keditSeparatorModal").remove();
							return;
						  }
					 
						  var addNewModule='';
						  var modulesCount=0;
						  if(data.indexOf('|') == 0){
							  
							  var d=data.split('|');
							  var dlist='';
							  var dd='';
							  var ii=d.length;
							  var c=0; // count items
							  
							  
							  for(i=0;i<ii;i++){
								  
								  if(d[i].length>0){
									  
									c++; 
									dd=d[i].split('#');
									
									if(typeof dd[1] === 'undefined')
										dd[1]=dd[0];
										
									if(dd[1] == 'gallery')
									continue;
									
									dlist+='<input type="radio" name="galleryId" id="galleryId_'+i+'" value="'+dd[1]+'"><label class="koBg2Hover" onclick="keditSeparatorModal_add('+i+',\'gallery\')" for="galleryId_'+i+'"><span>'+dd[0]+'</span></label>';

									modulesCount++;
									
								  } 
								  
							  } 
							  							  
						
								mcontents+='<h3 class="koBg3" style="text-align:left;">'+langPhrase.galleryAdd+'</h3>';
								mcontents+='<div style="padding:30px;overflow:auto"><h4>'+langPhrase.galleryEffect+'</h4>';
								
								mcontents+='<input type="radio" name="galleryEffect" id="galleryEffect1" value="1" checked="checked"><label class="koBg2Hover"" for="galleryEffect1"><span>'+langPhrase.galleryDefault+'</span></label>';
								mcontents+='<input type="radio" name="galleryEffect" id="galleryEffect2" value="2"><label class="koBg2Hover"" for="galleryEffect2"><span>'+langPhrase.gallerySlideshow+'</span></label>';
								mcontents+='<input type="radio" name="galleryEffect" id="galleryEffect3" value="3"><label class="koBg2Hover" for="galleryEffect3"><span>'+langPhrase.galleryCollage+'</span></label>';
								
								mcontents+='</div>';
								mcontents+='<div style="padding:0 30px 30px;overflow:auto"><h4>'+langPhrase.galleryChoose+'</h4>';
								mcontents+=dlist;
								mcontents+='</div>';
								
								
								
							  jQuery("#keditSeparatorModal").html(mcontents);
							  
						  } else alert(data)
					}
				});

		
	}
	else if(i=='submenu'){
		

		jQuery.ajax({
				 type: "POST",
				 url: "admin.php",
				 data: "supermode=moduleInfo&liveEdit="+encodeURIComponent(i),
				 success: function(data){
					 
					 if(loginFirst(data))return;
	  
						  if(loginFirst(data)){
							$("#keditSeparatorModal").remove();
							return;
						  }
					 
						  var addNewModule='';
						  var modulesCount=0;
						  if(data.indexOf('|') == 0){
							  
							  var d=data.split('|');
							  var dlist='';
							  var dd='';
							  var ii=d.length;
							  var c=0; // count items
							  
							  
							  for(i=0;i<ii;i++){
								  
								  if(d[i].length>0){
									  
									c++; 
									dd=d[i].split('#');
									
									if(typeof dd[1] === 'undefined')
										dd[1]=dd[0];
										
									if(dd[1] == 'menu')
									continue;
									
									dlist+='<input type="radio" name="galleryId" id="galleryId_'+i+'" value="'+dd[1]+'"><label class="koBg2Hover" onclick="keditSeparatorModal_add('+i+',\'gallery\')" for="galleryId_'+i+'"><span>'+dd[0]+'</span></label>';

									modulesCount++;
									
								  } 
								  
							  } 
							  
						////MOD_MENU_MENU SubpagesMenu
								mcontents+='<h3 class="koBg3" style="text-align:left;">'+langPhrase.menuAdd+'</h3>';
								mcontents+='<div style="padding:30px;overflow:auto"><h4>'+langPhrase.displayMenu+'</h4>';
								
								mcontents+='<input type="radio" name="galleryEffect" id="galleryEffect1" value="20" checked="checked"><label class="koBg2Hover"" for="galleryEffect1"><span>'+langPhrase.vertical+'</span></label>';
								mcontents+='<input type="radio" name="galleryEffect" id="galleryEffect2" value="21"><label class="koBg2Hover"" for="galleryEffect2"><span>'+langPhrase.horizontal+'</span></label>';
								
								mcontents+='</div>';
								mcontents+='<div style="padding:0 30px 30px;overflow:auto"><h4>'+langPhrase.menuChoose+'</h4>';
								mcontents+=dlist;
								
								mcontents+='<input type="radio" name="galleryId" id="galleryId_'+i+'" value="'+dd[1]+'"><label class="koBg1Hover" onclick="keditSeparatorMore(0);kopage_showFrame(null,\'admin.php?e=menu\')" for="galleryId_'+i+'"><span><i class="fa fa-plus"></i> '+langPhrase.menuAdd+'</span></label>';


								mcontents+='</div>';
								
								
								
							  jQuery("#keditSeparatorModal").html(mcontents);
							  
						  } else alert(data)
					}
				});

		
	}
	
	
	
}

function keditSeparatorMore(t){

	kopageToolbar('hide');
	kopage_hideFrame();
	
	if(t === 0){
		
		$('body').removeClass('kopageEditingMode');
		$("#keditSeparatorAdd").slideUp(function(){$("#keditSeparatorAdd").remove();keditSeparators();$('.kopageStartGears').animate({'margin-left':0,'opacity':1},200);})
		
		
		return;
		
	}	
	
	//$('.kopageStartGears').animate({'margin-left':'-200px','opacity':0},200);
	
	
	$('body').addClass('kopageEditingMode');
	
	var s=$("#"+t);//.closest('.keditSeparator');
	var sw=s.width();

	if(sw < 510) sw = '1';
	else if(sw < 820) sw = '2';
	else sw = '3';
	
	kt=function(i,t){
		
		$("#keditSeparatorAdd .kopageToolbarTabsNavigation a").removeAttr('class');
		$(t).addClass('active');
		
		$("#keditSeparatorAdd .kt__").hide();
		$("#kt__"+i).show();
		
	}
	
	if(typeof noVideoInPopular === "undefined")
	var popularAdd='<a class="keditQuickContent koBgHover2" href="javascript:void(null)" data-kid="{%video%}" data-module-opt="10" onclick="keditSeparatorAdd(this)"><i class="fa fa-video-camera"></i><em>'+langPhrase.video+'</em></a>';
	else var popularAdd="";
		
	s.replaceWith('<div class="koBg1" id="keditSeparatorAdd" style="display:none;"><div id="" class="koBg4"><h3 class="koBg1"><a href="javascript:void(null)" style="float:right;" onclick="keditSeparatorMore(0)" class="koBg3"><i class="fa fa-close"></i></a>'+langPhrase.blockAdd+'</h3>'
	
	+'<div class="kopageToolbarTabsNavigation"><a href="javascript:void(null)" data-tab="content" class="active" onclick="kt(1,this)">'+langPhrase.popular+'</a><a data-tab="module" href="javascript:void(null)" onclick="kt(2,this)">'+langPhrase.blocks+'</a><a data-tab="module" href="javascript:void(null)" onclick="kt(3,this)">'+langPhrase.apps+'</a></div><div style="clear:both;background:#fff;overflow:auto;max-height:70vh;padding:10px;" class="">'
	
	+'<div id="kt__1" class="kt__" style="display:block">'
	+'<a class="keditQuickContent koBgHover2" href="javascript:void(null)" data-kid="1017" onclick="keditSeparatorAdd(this)"><i class="fa fa-header"></i><em>'+langPhrase.header+'</em></a>'
	+'<a class="keditQuickContent koBgHover2" href="javascript:void(null)" data-kid="1018" onclick="keditSeparatorAdd(this)"><i class="fa fa-align-left"></i><em>'+langPhrase.text+'</em></a>'
	+'<a class="keditQuickContent koBgHover2" href="javascript:void(null)" data-kid="1016" onclick="keditSeparatorAdd(this)"><i class="fa fa-image"></i><em>'+langPhrase.image+'</em></a>'
	+popularAdd
	+'</div>'
	
	+'<div id="kt__2" class="kt__" style="display:none">'
	+'<div class="keditMoreContent keditMoreContent'+sw+'"><div class="container-fluid">'+draggableContents+'</div></div>'
	+'</div>'
	
	+'<div id="kt__3" class="kt__" style="display:none">'
	+'<div class="keditMoreContent keditMoreContent'+sw+' keditMoreContentApps"><div class="container-fluid">'+draggableApps+'</div></div>'
	+'</div>'
	
	+'</div></div></div>');
	
	$("#keditSeparatorAdd").slideDown();
	$('html, body').animate({
				scrollTop: $("#keditSeparatorAdd").offset().top-50
	});

}
function keditSeparators(){
	
	
	// and now make all kedit items editable with double-click:
	
	$(".kedit:not(.keditRow)").dblclick(function(event){
	
	
	if($(this).hasClass('keditFooter')){
		k_Edit('keditFooterEditable');
		return;		
	}
			
	
	
		//alert('editing: '+this.id+", class: "+$(this).attr('class'));return;
		
		if($(event.target).is('.keditNavTrigger'))return;
		if($('body').hasClass('kopageEditingMode'))return;
		if(this.id!=ktempCodeId){
			k_Edit(this.id);
		}
		return;
	
	});
	
	return;
	/*
	
	//setTimeout(function(){
		
	// first, remove existing separators ("Add content here" buttons)	
	$(".WxEditableArea .keditSeparator").remove()
	
	// now add new.
	$(".WxEditableArea .kedit").each(function(i){

		$(this).before('<div class="keditSeparator" id="'+ID()+'"><div class="koBg1"><a href="javascript:void(null)" onclick="keditSeparatorMore(this)"><span class="koBg1" title="Add content here"><i class="fa fa-plus"></i></span></a></div>'
		+'</div>');
	});
	
	*/
}




function keditAddItem(k,t){
	
	if($("#"+_id).length>0){
	$("#"+_id).replaceWith('<div class="keditItem"></div>');
	} else {
	$('.WxEditableArea').prepend('<div class="keditItem"></div>');
	}
	
	
}




		
function keditDraggable(kedit,kedit_opt){

	var i = 'kpg_'+Math.round(1000000*Math.random());
	
	var co = $(kedit).html();
	var co_ = modulePlaceholder(co);
	$(kedit).html(co_).attr("id",i);co='';
	
		
	k_EditInit(kedit,i);
	
	
	//if(this.id!=ktempCodeId)
	
	if(typeof kedit_opt !== 'undefined' && kedit_opt == 'noedit')
		// only cancel editor if it was initiated already...
		k_EditCancel();
	else {
		
		if($("#"+i).hasClass('keditRow')){
			
			// instead editing, refresh sortables & draggables.
			setupDraggableAndSortable()
			
		} else {
	
			k_Edit(i);
			
			// update separators
			keditSeparators();
		
		}
		
	}
	// and now close the contents window...
	//kopage_openSettings('Content');

	$('#k_Content,#k_Modules').removeClass('k_slideIn');
	$("#kopageLeftToolbar a").removeClass('kt_slideIn');
	//$("#kopageLeftToolbar").removeClass('kopageLeftToolbarOpen');


	
	return;
	
	
}

function modulePlaceholder(co,copt) {
		
}




function loginFirst(data){
	
	if(data == '<!--LOGIN-->'){
		
		// user isn't logged in.
		
		basicModal.show(modalLogin);
		kopage_hideLoading();
		
		// now hide all opened windows, popups, etc.
		
		// if user tried to drag a module/content:
		$('#kedit_modulePromptClose').trigger('click');
		
		// if user tried to open files manager
		jQuery('#modal-FManager').modal('hide')
		
		// if anything was dragged here, cancel it
		updateNewKeditItem(null,"cancel");
		
		// if user was editing some content block (TinyMCE is active),
		// cancel this, so it's inactive & modals are gone.
		// ...or maybe not.
		
		return true;
		
			
	} else
		return false;
	
}





function refreshSession(){
	
	// function will refresh user's session, so he is not logged in automatically.
	
	// clear timeout in case if this function was called twice
	// - here and from an iframe
	clearTimeout(refreshTimeout);
	
	if($("#k_FrameOverlay").is(":visible")){
		
		// frame is already visible. Just reset timeout to try again later...
		refreshTimeout=setTimeout('refreshSession()',sessionRefreshRepeat);
		return;
		
		
	}
		
	// show small "loading" on bottom-left corner
	$("#kopageSessionRefresh").fadeIn();
	
	// Make an AJAX request:
	// check if user is still logged in. If now: open login window.
	jQuery.ajax({
		type: "POST",
		url: "admin.php",
		data: "supermode=configUpdate&alive=1",
	 	success: function(data){
			
			$("#kopageSessionRefresh").fadeOut();
			
			if(data=='OK'){
				
				// All is fine. User is logged in.
				// Set timeout to make sure, this function 
				// is going to be called soon again.
				
				refreshTimeout=setTimeout('refreshSession()',sessionRefreshRepeat);//120000); // repeat every 2 minutes.
				 
			} else {
				
				// user isn't logged in anymore.
				
				loginFirst('<!--LOGIN-->');
				
			}
			
		}
	});
	
	
	
}


function setupDraggableAndSortable(){

  /*
	$("#extraAddonsContent .btn-contents,a.draggableModule").draggable({

		start: function(event, ui) {
			draggableType='new';
			
			kopage_openSettings('closeAll');
			$(".WxEditableArea").addClass('kopageEditableActive');

		},
		stop: function(event, ui) {
						
			  
			$(".WxEditableArea").removeClass('kopageEditableActive');
			
			keditExtraAdd(this);
			
			$("#keditTempStyles").html('');
			
			// now count how many items there is:
			var countBlocks=0;
			$('.WxEditableArea .kedit').each(function(i){
				countBlocks=Math.round(i + 1);
			});
			countBlocks++;
			

		},
		
		connectToSortable:sortablesConnectedTo,//".WxEditableArea,.keditColumn",
		
		
		//helper: "clone",
		//revert: "invalid",
		//placeholder: "ui-state-highlight",
		//cursor:"move",
		
		//tolerance: "pointer",
		helper: function( event ) {
			
				var d = 'drag_'+Math.round(1000000*Math.random());
				
			    var dd = $( '#dragHelper' );
      			dd.clone().attr('id', 'dragHelper'+d ).addClass('keditItem').insertAfter( dd );

				return $("#dragHelper"+d);
				
			}
	
		});
		
		
		*/
		
		
	jQuery( ".WxEditableArea,.WxEditableArea .keditColumn" ).sortable({
					
					
			items: ".kedit",
			handle: ".k_Move",
			opacity: 1,   
			helper: "clone", 
			
			
			forceHelperSize:true,
			forcePlaceholderSize:true,	
			
			placeholder: "wxSortPlaceholder",	
			connectWith: sortablesConnectedTo,
			tolerance: "pointer",
			zIndex: 65558,
			
			
			start: function(event, ui) {
				$( this ).sortable( "refreshPositions" );
				$(".WxEditableArea").addClass('kopageEditableActive');
				
				
				
			},
			stop: function(event, ui) {
				
				//$( ui.item ).removeClass( "kopageEditableActiveItem" );
				$(".WxEditableArea").removeClass('kopageEditableActive');

				$(".kopageEditableActiveItem").each(function(i){
					
					$(this).removeClass('kopageEditableActiveItem')
				
				})
				// when done, make sure user will know 
				// he has to save changes...
				
				if(draggableType == 'new'){
					draggableType = '';
										
				} else {
					kopage_showLoading();
					setTimeout("k_EditSave('norefresh')",300);
				}
				//editSaveHighlight();
				
				// hide toolbar if opened
				kopageToolbar('hide');
				
				keditSeparators();
			}
		
	});
	
	keditSeparators();	
	$('body').removeClass('keditLoading');
	
}


function kopage_addNewModule(module,modulesCount){

  var newModuleName=$("#newModuleName").val();
  
  
  
	
  // make an AJAX call to create a new module:
  jQuery.ajax({
		 type: "POST",
		 url: "admin.php",
		 data: "supermode=moduleAdd&liveEdit="+encodeURIComponent(module)+"&liveEditName="+encodeURIComponent(newModuleName),
		 success: function(data){
			 
			 if(loginFirst(data))return;
			 
			 
			 if(data.indexOf('|') > 0){
				 
				 data = data.split('|');
					 
				 if(data[0] == 'OK'){
					 
					 
					 // SUCCESS! 
					 // New module has been added.
										 
					 // Replace temporary tag with new module name received from AJAX,
					 // in case if user typed some incorrect characters or system changed name
					 // to avoid duplicates:
					 
					 updateNewKeditItem('{%'+module+'_'+data[2]+'%}','noedit'); // add our tag, but don't save just yet
					 
					 // close this prompt:
					 $('#kedit_modulePrompt').fadeOut();
					 
										 
					 // now it should open a lightbox to start editing:
					 kopage_showFrame('','admin.php?p=quickEdit&module='+data[1]+'&action=new&lmode=light&id='+data[2]+'&fid='+data[2]+'&nid='+data[2]+'&item='+data[2]);
													
													
				 }
				 
			 }
			 
			 
			 
		 }
  });
					
					
  //alert('will add '+module+' named '+newModuleName);
  
  // TEST. Let's say module is added: skipping admin's area things,
  // what happens now:
  
  
  		  
										  
						 
								 
								 
  
}


function kopageEmbedHTML(){
							
	jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 data: "supermode=configUpdate&liveEdit=1&liveEditEmbed="+encodeURIComponent($("#kedit_EmbedHTML").val()),
	 success: function(data){
		 
		 if(loginFirst(data))return;
		 
		 
		 if(data.indexOf('|') > 0){
			 
			 data = data.split('|');
				 
			 if(data[0] == 'OK'){
				 
				updateNewKeditItem('{%'+data[1]+'%}','save');
				$("#kedit_modulePrompt").fadeOut();
				 
			 }
			 
			 
		 }else
			alert(data);
			
	 }});


}

function saveCustomThemeCode(){
	
	//
	
	kopage_showLoading();
	
	// make ajax request
	jQuery.ajax({
		 type: "POST",
		 url: "admin.php",
		 data: "supermode=configUpdate&iSplashTheme="+templateId+"&iSplash=1&iSplashElement=customCode&content="+encodeURIComponent($("#customThemeCode").val()),
		 success: function(data){
			 
			 if(loginFirst(data))return;
			 
			 if(data == 'OK'){
				 
				refreshWindow('open=info-saved');
				
			 }
			 
		 }
	});
				
				
}	


		

function kopage_showLoading(){

	$("#k_spinner").addClass('kopageLoading');
	$("#spinnerHolder").show();
	
}
function kopage_hideLoading(){

	$("#k_spinner").removeClass('kopageLoading');
	$("#spinnerHolder").fadeOut();
	
}
function kopage_hideFrame(){
	
	$('a.kopageToolbarTabsClose,a.kopageToolbarTabsApply,a.kopageToolbarTabsRemove').css({'left':''})
	
	// make BODY scrollable again.
	$("html, body").css('overflow','auto');
	
	$("#kopageToolbarMoreSettings").attr({'class':null,'style':null});
	$("#kopageToolbarMoreSettings").addClass('animated fadeOutLeft');

	$("#kopageMoreFrame iframe").remove();
	$("#kopageMoreFrame #kopageColors").remove();
	
	$("#kopageFManager").hide()

	
}

function kopage_manageModules(){
	
	kopage_showFrame('manage_modules');	
	
}


$(function(){setupDraggableAndSortable();})


function kopage_hideSettings(){
	kopage_hideFrame();
	kopageToolbar('hide')
}
function kopage_openSettings(w){
	
	
	
	$(".kopageLeftToolbar").removeClass('k_slideIn');
	$("#kopageLeftToolbar a").removeClass('kt_slideIn');
	
	if(w == 'closeAll')
		return;
		
	// hide tooltips:
	$(".twipsy").hide();

	if(w=='Fonts'){
		
		//$("#ko_font1").parent().show();
		//$("#ko_font2").parent().show();
	
		
		$("#ko_font1,#ko_font2").chosenImage().on('change',kopage_pageFonts);
	
	
	}
		
	// visible. Hide.
	if($('#k_'+w).position().left>0){
		
		
		// hide.	
		$('#k_'+w).removeClass('k_slideIn');
		
		if(w=='Colors')
			$("#colorSchemeStyle").html('')
		
	} else {
		
		$('#k_'+w).addClass('k_slideIn');
		$('#kt'+w).addClass('kt_slideIn');


		// now slide window to get the correct position:
		var ww='body',wfix=0;
		
		if(w == 'Content'||w == 'Sidebar'||w == 'Modules'){
			ww = '#content';
			wfix = 50;
		}else if(w == 'Footer')
			ww = '#footer';
		
		
		$('html, body').animate({
                        scrollTop: $(ww).offset().top-wfix
        });
					
	}
		
	
}

function kopage_pageSettings(){
	
	kopage_showLoading();
	
	// make ajax request
	
	jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 data: "supermode=configUpdate&liveEdit=1&liveOption=config&title="+encodeURIComponent($("#k_websiteTitle").val())+"&slogan="+encodeURIComponent($("#k_websiteSlogan").val())+"&logo="+encodeURIComponent($("#k_websiteLogo").val()),
	 success: function(data){
		 
		 if(loginFirst(data))return;
		 
		 if(data == 'OK'){
			 
			refreshWindow();
			
		 }else
			alert(data);
			
		}
	});
	
}



function kopage_colorSettings(){
	
	
	kopage_showLoading();
	
	// make ajax request
	
	jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 async: false,
	 data: "supermode=configUpdate&liveEdit=1&liveOption=colors&colorSchemeTemplate="+templateId+"&colorScheme="+encodeURIComponent($("#colorScheme").val()),
	 success: function(data){
		 
		 if(loginFirst(data))return;
		 
		 if(data == 'OK'){
			 
			refreshWindow();
						
		 }else
			alert(data);
			
		}
	});
	
	// and now hide window & apply changes for user's view
	
}



function kopage_menuSettings(){


jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 data: "supermode=configUpdate&liveEdit=1&showInMenu="+encodeURIComponent($("#menuShowThisSubpage:checked").length)+"&pageId="+menuPageId+"&pageMenuId="+menuMenuId+"&pageName="+encodeURIComponent($("#k_subpageName").val()),
	 success: function(data){
		 
			  if(loginFirst(data))return;
			  
			  if(data == "OK"){
			
				kopage_showLoading();
				refreshWindow();
			  
			  }else{
				  
				alert(data)
				
			  }
		}
});
	
return;

}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}




		
function changeMenuStyle(i){
	
	// quick reset
	$('#headerMenu').removeClass('koMenu1 koMenu2 koMenu3 koMenu4 koMenu5 koMenu6');	
	
		var saveElement='menuClass';
		var saveElementValue='';
		
	if(i > 0){
	
		var saveElementValue='koMenu'+i;
		$('#headerMenu').addClass(saveElementValue);	
				
	}
	saveToTheme(saveElement,saveElementValue,1);//
	
	// now hide toolbar:
	kopageToolbar('hide')

	
}

var logoAlignClass=$('#website').attr('class').toLowerCase();
	
function changeMenuLayout(i){
	
	// quick reset
	$('#website').removeClass('logoCentered logoRight logoLeft');	
	
	
		var saveElement='headerClass';
		var saveElementValue='';
		
	if(i==1){
	
		var saveElementValue='logoLeft';
		$('#website').addClass(saveElementValue);
		
	}else if(i==2){
	
		var saveElementValue='logoCentered';
		$('#website').addClass(saveElementValue);	
		
	}else if(i==3){
	
		var saveElementValue='logoRight';
		$('#website').addClass(saveElementValue);	
		
	}
	
	logoAlignClass=saveElementValue.toLowerCase();	
	
	saveToTheme(saveElement,saveElementValue);
	
	// now hide toolbar:
	kopageToolbar('hide')

	
}
	
	
	
		
function saveToTheme(saveElement,saveElementValue,refreshValue){
	
	
	jQuery.ajax({
	 type: "POST",
	 url: "admin.php",
	 data: "supermode=configUpdate&iSplashTheme="+templateId+"&iSplash=1&iSplashElement="+encodeURIComponent(saveElement)+"&pageMenuId="+menuMenuId+"&pageId="+menuPageId+"&content="+encodeURIComponent(saveElementValue.trim()),//
	 success: function(data){
		
		
			if(loginFirst(data)){
				
				// user isn't logged in, try to restore this content.
				return;
				
			}
		
			if(data == 'OK'){
			
				if(refreshValue == 1)refreshWindow();
				
			}else
				alert(data);
			
		}
	});
	
}
function changeTheme(i,ii){
	

	var forceRefresh=0;
	
	if($("html").hasClass('koTheme4') && i != 4){
		// current one was with sidebar, and it changes to topbar.
		forceRefresh=1;
	}else if (i == 4)
		forceRefresh=1;
	
		
	if(i > 0){
		
		var saveElement='htmlClass';
		var saveElementValue='koTheme'+i;
		
		$("html").attr('class',saveElementValue);
		
	}else{
		
		var saveElement='bodyClass';
		var saveElementValue='koTheme'+i;
		
		if(i == 'Dark' && ii == true)
			$("body").addClass(saveElementValue);
		if(i == 'Dark' && ii == false){
			$("body").removeClass(saveElementValue);
			saveElementValue='';
		}
	
	}
	
	saveToTheme(saveElement,saveElementValue);
	
	// now hide toolbar:
	kopageToolbar('hide');
	
	if($("ul.dropmenudiv").length > 0 && forceRefresh > 0){
		
		// refresh to re-init submenu
		refreshWindow();
		
		
	}
		
	
}



function kopage_blockRemove(){
	
	var blockID=$('#blockId').val();
	kopage_hideSettings();
	
	$("#"+blockID).slideUp("slow",function(){
		
		$("#"+blockID).remove();
		// save changes with no refresh required:
		setTimeout("k_EditSave('norefresh');",500)
	
	});
	
}




function kopage_blockSettings(o){
	
	// 
	
	
	var blockId=$("#blockId").val();
	var blockBackground=$('#blockBackground').val();
	var blockImage=$('#blockBackgroundImage').val();
	
	var blockStyle='';
	var blockData='';
	var blockClass='';
	
	//alert(o+'/' + blockId);return false
	
	$("#"+blockId).css('box-shadow',null)
	if(blockBackground.length > 0){
		
		// set background color to the block;
		$("#"+blockId).attr('data-bgcolor',blockBackground)//.css('background-color',blockBackground)
		blockStyle=blockBackground;
		blockData+=' data-bgcolor="'+blockBackground+'"';
		
	}else
		$("#"+blockId).attr('data-bgcolor',null)
	
	
	if(blockImage.length > 0){

		// set background color to the block;
		$("#"+blockId).attr('data-background',blockImage);//.css({'background':blockBackground+' url('+blockImage+') no-repeat center center','background-size':'cover'})
		///background:url(2.jpg) no-repeat center center;background-size:cover
		
		blockStyle=blockStyle+' url('+blockImage+') no-repeat 50% 0px;background-size:cover';
		blockData+=' data-background="'+blockImage+'"';
		
	}else
		$("#"+blockId).attr('data-background',null);//.css({'background':'','background-size':''})

	
		if($('#blockParallax:checked').length>0){
			blockData+=' data-parallax="1" ';
			blockClass+=" parallax-window "
		}
		
		
		if($('#blockDark:checked').length>0){
			blockClass+=" keditDark "
		}
		
		
		if($('#blockSplashHeight:checked').length == 0){
			blockClass+=" koHeaderAutoHeight "
		}
		
		
		if($('#blockSplashShow:checked').length == 0){
			blockClass+=" koHeaderHide "
		}
	
	if($("#"+blockId).hasClass('keditLeft')){
		
		blockData+=' data-align="keditLeft" ';
			blockClass+=" keditLeft ";

		
	} else if($("#"+blockId).hasClass('keditRight') || $("#"+blockId).hasClass('keditRight')){
		
		blockData+=' data-align="keditRight" ';
		blockClass+=" keditRight ";

		
	}


	if($('#blockFilter:checked').val()>0){
		
		blockClass+=' kfilter'+$("#blockFilterOption").val()+' ';
		
	}
	
	
	

	if(blockClass.length > 0)
	blockClass=' class="'+blockClass+'"';
	
	if(blockId != 'body')
	blockData+=blockClass;
	
		
		
	$("#"+blockId).attr('style','background:'+blockStyle);


	// if it's a template-configurable block (like splash-banner or footer),
	// send new changes to template configuration file too:
	//alert('will save headerData & headerFilter:'+"\n"+'style="background:'+blockStyle+'"'+"\n"+blockData)
	
	if(blockId == 'footerContent'){
		var saveElement='footerData';
		saveToTheme(saveElement,blockData+' style="background:'+blockStyle+'"')
	}else if(blockId == 'body'){
		var saveElement='bodyData';
		saveToTheme(saveElement,blockData+' style="background:'+blockStyle+'"')
	}else if(blockId == 'headerBanner'){
		var saveElement='headerData';
		
		saveToTheme(saveElement,blockData+' style="background:'+blockStyle+'"')
		
		//if($('#blockFilter:checked').length>0)
			//var headerFilter='<div class="filter"></div>';
		//else
		var headerFilter=' ';
		//
		saveToTheme('headerFilter',headerFilter)

	}


	//headerContent
	
	
	// save changes to file.
	k_EditSave('norefresh');
	
	// now hide toolbar.
	if(o != 1)
	kopage_hideSettings();
		
}




function kopageToolbarItem(i){
	
	var blockId = $('#blockId').val();
	var block=$("#"+blockId);
	var htag = 'h2';
	var btag = '';
	
	
	// headerBanner // footerContent
	if(blockId == 'headerBanner'){
		block=$('.keditHeader');
		blockId=block.attr('id');
		htag = 'h1';
		btag = ' btn-xl';
	}
		
	
	
	
	
	if(i == 1){ // header
	
	
		if($("#"+blockId+" h1").length===0 && $("#"+blockId+" h2").length===0){
			
			block.prepend('<'+htag+'>Title!</'+htag+'>');
			k_Edit(blockId);
			
			
		} else {
		
			// there's H1 already
			
		}
	
	
	} else if(i == 2){ // text
	
		if(block.find('h1').length){
			
			block.find('h1').after('<p>Text!</p>');
			k_Edit(blockId);
		
		} else if(block.find('h2').length){
			
			block.find('h2').after('<p>Text!</p>');
			k_Edit(blockId);
			
		} else {
			
			block.prepend('<p>Text!</p>');
			k_Edit(blockId);
			
		}
		
	}else if(i == 3){ // buttons
	
		if($("#"+blockId+" .koButtons").length===0){
			block.append('<div class="koButtons"><a class="btn btn-primary btn-lg'+btag+'" href="#">Buy Now</a> <a class="btn btn-default btn-lg'+btag+'" href="#">More Info</a></div>');
			k_Edit(blockId);
		}
		
	}
	
}





function kopageBlockInit(){
	
	var blockId = $('#blockId').val();
	var block = $("#"+blockId);
	/*if(blockId == 'headerBanner')
		$('#blockFilterCheckbox').show();
	else
		$('#blockFilterCheckbox').hide();*/
	
	
	$("#blockFilterOptions").hide();
	
	if(blockId == 'footerContent'){
		$('#footerBlockSettings').show();
	}else{
		$('#footerBlockSettings').hide();
	}
	if($("#blockBackgroundImage").val().length == 0 || blockId == 'body'){
		
		// hide image's settings:
		$("#blockParallaxCheckbox,#blockFilterCheckbox").hide();
		
	}else{
		
		// hide image's settings:
		$("#blockParallaxCheckbox").show();
		//if(blockId == 'headerBanner')
		$("#blockFilterCheckbox").show();
		
		
	}
	
	
	$('.blockKeditActive').removeClass('blockKeditActive');
	if(blockId!='headerBanner') // headerBanner is hidding menu on some themes
	$("#"+blockId).addClass('blockKeditActive');	
		
	// now slide window to get the correct position:
	
	$('html, body').animate({
		scrollTop: $("#"+blockId).offset().top-100
	});

	if(typeof $('#'+blockId).attr('data-parallax') === 'undefined')
		$( "#blockParallax" ).prop( "checked", false );
	else
		$( "#blockParallax" ).prop( "checked", true );
		
	$('#kopageBlockBody_input').hide();
	
	// "dark background" option, is it enabled?
	if($("#"+blockId).hasClass('keditDark')){
		
		$( "#blockDark" ).prop( "checked", true );	
		
	}else
		$( "#blockDark" ).prop( "checked", false );	
	
	// does this block have zero-padding class?
	if($("#"+blockId).hasClass('koZeroPadding')){
		
		$( "#blockZeroPadding" ).prop( "checked", true );	
		
	}else
		$( "#blockZeroPadding" ).prop( "checked", false );	
	
	
	$("#blockZeroPaddingCheckbox").hide();
	if(blockId.indexOf('kpg_') > -1){
		
		// not header/footer:
		$("#blockZeroPaddingCheckbox").show();
			
	}
	
	if(blockId == 'headerBanner'){
		
		$("#splashBlockSettings,#kopageBlockBody_input").show();
			
		// splash options now: show/hide and height:
		if($("#"+blockId).hasClass('koHeaderAutoHeight')){
			$( "#blockSplashHeight" ).prop( "checked", false );
		} else {
			$( "#blockSplashHeight" ).prop( "checked", true );
		}
		if($("#"+blockId).hasClass('koHeaderHide')){
			$( "#blockSplashShow" ).prop( "checked", false );
			$("#blockSplashHeightCheckbox").hide();
			$("#kopageBlockSettingsHolder").hide();
			$("#kopageBlockContentsHolder").hide();
		} else {
			$( "#blockSplashShow" ).prop( "checked", true );
			$("#blockSplashHeightCheckbox").show();
			$("#kopageBlockSettingsHolder").show();
			$("#kopageBlockContentsHolder").show();
		}
		
		
	
	} else {
		
		$("#splashBlockSettings").hide();
		$("#kopageBlockSettingsHolder").show();
		
	}
	


	if(blockId == 'body'){
		
		$('#kopageBlockContents_input,#kopageBlockAlign_input,#blockDarkCheckbox').hide();
		$('#blockEditButton,#blockColumnsNavigation').hide();
		
	} else 	if($("#"+blockId).hasClass('keditRow')){
		
		$('#blockEditButton').parent().hide();
		$('#blockColumnsNavigation').show()
		
		$('#kopageBlockContents_input,#kopageBlockAlign_input').hide()
		
		



	}else{
		$('#blockEditButton').parent().show();
		$('#blockColumnsNavigation').hide();
	
		$('#kopageBlockContents_input,#kopageBlockAlign_input,#blockEditButton').show()
		
	}

	
	


	// hide extra contents when block has more columns, there's no support for this yet.
	if(block.find('.row').length>0)
		$('#kopageBlockContents_input').hide()
	else {
		
		$("#kopageToolbarItem_1,#kopageToolbarItem_3").removeClass('active')
		// check which items should be available:
		if(block.find('h1').length > 0 || block.find('h2').length > 0)
		$("#kopageToolbarItem_1").addClass('active')
		
		if(block.find('.koButtons').length > 0)
		$("#kopageToolbarItem_3").addClass('active')
			
		
		
	}


	// don't show extra contents add/remove only when it's footer.
	if(blockId == 'footerContent')
	$('#kopageBlockContents_input').hide()
	
	var keditFilter=$("#"+blockId).attr('class');
	
	if(typeof keditFilter === "undefined")
		keditFilter="";
		
	
	keditFilter=keditFilter.toLowerCase();
	
	
	
	if(keditFilter.indexOf("kfilter") >= 0){
		$( "#blockFilter" ).prop( "checked", true );
		
		// now check which one filter was used
		keditFilter=keditFilter.split('kfilter');
		keditFilter=keditFilter[1].split(' ');
		keditFilter=keditFilter[0];
		
		$( "#blockFilterOption_" +keditFilter ).prop( "selected", true );		
		
	} else {
		$( "#blockFilter" ).prop( "checked", false );			
	}
		
		
}
function blockFilterCheck(opt){
	
	var blockId = $('#blockId').val();
	$('#'+blockId).removeClass('kfilter1 kfilter2 kfilter3 kfilter4 kfilter5 kfilter6 kfilter11 kfilter12 kfilter13 kfilter14 kfilter15 kfilter16 kfilter21 kfilter22 kfilter23 kfilter24');
	
	if(opt == 1)
		$( "#blockFilter" ).prop( "checked", true );	
		
	if($('#blockFilter:checked').val()>0){
		
		$('#'+blockId).addClass('kfilter'+$("#blockFilterOption").val());
		
		//alert('setting class "kfilter'+$("#blockFilterOption").val()+'" to element #'+blockId)
		//$('#'+blockId).append('<div class="filter"></div>');
		
	}
	if(blockId == 'headerBanner' || blockId == 'footerContent')
	kopage_blockSettings();
	else
	setTimeout("k_EditSave('norefresh');",500)
	
	
}
function blockParallaxCheck(){
	
	var blockId = $('#blockId').val();
	if($('#blockParallax:checked').val()>0){

		//alert('is checked, so setting parallax to '+blockId)
		$('#'+blockId).attr({'data-parallax':1}).addClass('parallax-window').parallax();
		
	} else {
		
		//alert('is NOT checked, so removing parallax from '+blockId)
		$('#'+blockId).attr({'data-parallax':null}).removeClass('parallax-window').parallax('destroy');
		
	}
	
	if(blockId == 'headerBanner' || blockId == 'footerContent')
	kopage_blockSettings();
	else
	setTimeout("k_EditSave('norefresh');",500)

	
}


function blockDarkCheck(){
	
	var blockId = $('#blockId').val();
	if($('#blockDark:checked').val()>0){

		$('#'+blockId).addClass('keditDark');
		
	} else {
		
		$('#'+blockId).removeClass('keditDark');
		
	}
	
	kopage_blockSettings();
}



function blockZeroPadding(){
	
	var blockId = $('#blockId').val();
	if($('#blockZeroPadding:checked').val()>0){

		$('#'+blockId).addClass('koZeroPadding');
		
	} else {
		
		$('#'+blockId).removeClass('koZeroPadding');
		
	}
	
	kopage_blockSettings();
}


function blockSplashHeightCheck(){
	
	var blockId = $('#blockId').val();
	if($('#blockSplashHeight:checked').val()>0){

		$('#'+blockId).removeClass('koHeaderAutoHeight');
		
	} else {
		
		$('#'+blockId).addClass('koHeaderAutoHeight');
		
	}
	
	kopage_blockSettings();
}
function blockSplashShowCheck(){
	
	var blockId = $('#blockId').val();
	if($('#blockSplashShow:checked').val()>0){

		$('#'+blockId).removeClass('koHeaderHide');
		$("#blockSplashHeightCheckbox").show();
		
	} else {
		
		$('#'+blockId).addClass('koHeaderHide');
		$('#'+blockId).removeClass('koHeaderAutoHeight');
		
		$("#blockSplashHeightCheckbox").hide();
		
		
	}
	
	kopage_blockSettings();
}





// Include RGBaster - https://github.com/briangonzalez/rgbaster.js
!function(n){"use strict";var t=function(){return document.createElement("canvas").getContext("2d")},e=function(n,e){var a=new Image,o=n.src||n;"data:"!==o.substring(0,5)&&(a.crossOrigin="Anonymous"),a.onload=function(){var n=t("2d");n.drawImage(a,0,0);var o=n.getImageData(0,0,a.width,a.height);e&&e(o.data)},a.src=o},a=function(n){return["rgb(",n,")"].join("")},o=function(n){return n.map(function(n){return a(n.name)})},r=5,i=10,c={};c.colors=function(n,t){t=t||{};var c=t.exclude||[],u=t.paletteSize||i;e(n,function(e){for(var i=n.width*n.height||e.length,m={},s="",d=[],f={dominant:{name:"",count:0},palette:Array.apply(null,new Array(u)).map(Boolean).map(function(){return{name:"0,0,0",count:0}})},l=0;i>l;){if(d[0]=e[l],d[1]=e[l+1],d[2]=e[l+2],s=d.join(","),m[s]=s in m?m[s]+1:1,-1===c.indexOf(a(s))){var g=m[s];g>f.dominant.count?(f.dominant.name=s,f.dominant.count=g):f.palette.some(function(n){return g>n.count?(n.name=s,n.count=g,!0):void 0})}l+=4*r}if(t.success){var p=o(f.palette);t.success({dominant:a(f.dominant.name),secondary:p[0],palette:p})}})},n.RGBaster=n.RGBaster||c}(window);

function blockContrastCheck(hexcolor){
	
	var r,g,b;
	
	if(hexcolor.indexOf(".") > -1){
		
		// it's not a hex color, but path to image
					
		RGBaster.colors(hexcolor, {
		  success: function(payload) {
			  
			b=payload.secondary.substring(4).replace(')','').split(',');
			r=parseInt(b[0],16);
			g=parseInt(b[1],16);
			b=parseInt(b[2],16);
		
			
			var yiq = ((r*299)+(g*587)+(b*114))/1000;
			var col = (yiq >= 180) ? 'black' : 'white';//128
			
			if(col == 'white')
				$( "#blockDark" ).prop( "checked", true ).trigger('change');	
			else
				$( "#blockDark" ).prop( "checked", false ).trigger('change');	

			//var blockId = $('#blockId').val();
			//$("#"+blockId).css('border','20px solid '+payload.secondary)
					
		  }});
		
		
	} else {
		
		// Take off the hash
		if(hexcolor.charAt(0)=="#")
		hexcolor = hexcolor.slice(1);
	 
		// Convert it to the right length if it is the shorthand
		if(hexcolor.length === 3) {
			hexcolor = hexcolor.replace(/([0-9a-f])/ig, '$1$1');
		}
	
		r = parseInt(hexcolor.substr(0,2),16);
		g = parseInt(hexcolor.substr(2,2),16);
		b = parseInt(hexcolor.substr(4,2),16);
		
		var yiq = ((r*299)+(g*587)+(b*114))/1000;
		var col = (yiq >= 180) ? 'black' : 'white';//128
		
		if(col == 'white')
			$( "#blockDark" ).prop( "checked", true ).trigger('change');	
		else
			$( "#blockDark" ).prop( "checked", false ).trigger('change');	
		
	}
	
	
		

}


function blockTextAlign(opt){
	
	var blockId = $('#blockId').val();
	var blockAlign;
	
	$('#'+blockId).removeClass('keditLeft keditRight keditCenter');
	
	if(opt == 1)
	blockAlign='keditLeft';
	else if(opt == 2)
	blockAlign='keditRight';
	else if(opt == 3)
	blockAlign='keditCenter';
	
	$('#'+blockId).attr({'data-align':blockAlign}).addClass(blockAlign);


	if(blockId == 'headerBanner' || blockId == 'footerContent')
	kopage_blockSettings();
	else
	setTimeout("k_EditSave('norefresh');",500)

	
}





function ko_mce_save(){
 
  $('#toolbarTinyMCE').slideUp();
  var s = $('#toolbarTinyMCE').attr('data-block');
  k_Edit(s,3);tinymce.remove();
  
 	// editable images were cleared before TinyMCE, so wrap it now, in this block only:
  
	$('#'+s+' a').editableLinks();
	$('#'+s+' img').editableImages();

	
	
  $("body").removeClass('kopageEditingMode');
  document.getElementById(s).blur(); // lose outline

}
function ko_mce_cancel(){
  
  $('#toolbarTinyMCE').slideUp();
  var s = $('#toolbarTinyMCE').attr('data-block');
  k_Edit(s,1);tinymce.remove();
  
	$('#'+s+' a').editableLinks();
	$('#'+s+' img').editableImages();
	
  $("body").removeClass('kopageEditingMode');
  document.getElementById(s).blur(); // lose outline
  
}


var featherEditor = new Aviary.Feather({
	apiKey: '5e65333b1bbf413cbbffc8df0169a540',
	theme: 'dark', // Check out our new 'light' and 'dark' themes! // onLoad
	tools: 'all',
	appendTo: '',
	displayImageSize:true,    
	language:configLanguage,
	onSave: function(imageID, newURL) {
		var img = document.getElementById(imageID);
		img.src = newURL;
			
		featherEditor.showWaitIndicator();

		jQuery.ajax({
		 type: "POST",
		 url: "admin.php",
		 data: "supermode=configUpdate&photoEditor="+encodeURIComponent(newURL),
		 success: function(data){
			 
				var rdata=data.split('|')
			 	if(rdata[0] == "OK"){
					img.src = rdata[1];
				} else alert(data)
				
				setTimeout("k_EditSave('norefresh');",500);
				featherEditor.close();
				
			}
		});
		
	},
	onError: function(errorObj) {
		alert(errorObj.message);
	}
});
function launchEditor(id, src) {
	featherEditor.launch({
		image: document.getElementById(id),
		url: src,
		
	});
	return false;
}

function keditLink(event,t,m){
	
	event.preventDefault();
	var im=$(t).parent('a');
	
	var modalLink = {
	body: '<h3 style="padding:0 0 5px 0;margin:0 0 30px;font-size:120%;border-bottom:1px solid #ccc">'+langPhrase.linkEditor+'</h3><input class="basicModal__text" type="text" name="linkAddress" placeholder="http://" value="'+im.attr('href')+'">',//<label style="padding:10px;"><input type="radio" name="linkAddressOpt" id="linkAddress_1" checked="checked" style="margin-right:5px">Link Address</label><label style="padding:10px;"><input name="linkAddressOpt" type="radio" id="linkAddress_2" style="margin-right:5px">Another subpage</label>',
	buttons: {
		cancel: {
			title: langPhrase.cancel,
			fn: basicModal.close
		},
		action: {
			title: langPhrase.save,
			fn: function(data) {

				if (data.linkAddress.length<1) return basicModal.error('linkAddress');
				
				var newLink = data.linkAddress;
				newLink = newLink.replace(/[`~!$^*|;'"<>\{\}\[\]\\]/gi, '');
				
				im.attr('href',newLink);
				basicModal.cancel();
				k_EditSave('norefresh');
				
			}
		}
	}
	}


	basicModal.show(modalLink);
		
	
}
function keditImage(t,m){

//
	//alert(t);	
	var im=$(t).parent().find('img');
	
	if(typeof im.attr('id')==='undefined'){
		im.attr('id',ID());
	}

	if(typeof m === "undefined")
	showModalManager(im.attr('id'),null,'img');
	else if(m == "edit"){
		
		//alert('edit: '+im.attr('src'))	
		return launchEditor(im.attr('id'), im.src);
		
		
	}
		
	//if()
	
	//showModalManager('k_websiteLogo');
	//.attr('src','https://placeholdit.imgix.net/~text?txtsize=30&bg=a7dbd8&txtclr=ffffff&txt=320%C3%97180&w=320&h=180')
	
}


	
(function($){
	
	//Attach this new method to jQuery
	$.fn.extend({ 
		 
		editableImages: function(options) {
			var defaults = {
				wrap: '<span class="keditImageWrap"/>'     
			}         
			 
			var options = $.extend(defaults, options);
			var o = options; 
			
			//Iterate over the current set of matched elements
			return this.each(function(i) {
				
				//if(i > 0)return
				 var e = $(this)
				 var eHeight = e.outerHeight();       
				 
				 var ee = e.closest('.koparsed');
				 
				 if ( ee.length > 0 ) return;
				 //alert(e.parentsUntil('.kedit').attr('class'))
				 
				 //alert('123'+i)
				 //return;
				 
				 
				 e.wrap(o.wrap);
				 e.after('<a data-balloon="'+langPhrase.imageFind+'" data-balloon-pos="down" class="keditImage koBgHover1" href="javascript:void(null)" onclick="keditImage(this)"><i class="fa fa-cog"></i></a><a data-balloon="'+langPhrase.imageEditor+'" data-balloon-pos="down" class="keditImage keditImageEdit koBgHover1" href="javascript:void(null)" onclick="keditImage(this,\'edit\')"><i class="fa fa-edit"></i></a>');
				//	keditImageEdit<p><input type='image' src='http://images.aviary.com/images/edit-photo.png' value='Edit photo' onclick="return launchEditor('image1', 'http://images.aviary.com/imagesv5/feather_default.jpg');" /></p>

			});
	  
		}
		
	});
	
	//Attach this new method to jQuery
	$.fn.extend({ 
		 
		editableLinks: function(options) {
			var defaults = {
				wrap: '<span class="keditImageWrap keditLinkWrap"/>'     
			}         
			 
			var options = $.extend(defaults, options);
			var o = options; 
			
			//Iterate over the current set of matched elements
			return this.each(function(i) {
				
				 var e = $(this);
				 var eHeight = e.outerHeight();       
				 
				 var ee = e.closest('.koparsed');if ( ee.length > 0 ) return;	
				 ee = e.closest('.k_Edit');if ( ee.length > 0 ) return;	
				 		 
				 e.append('<a data-balloon="'+langPhrase.linkEditor+'" data-balloon-pos="up" class="keditLinkEdit koBgHover1" href="javascript:void(null)" onclick="keditLink(event,this)"><i class="fa fa-cog"></i></a>');


			});
	  
		}
		
	});
})(jQuery);
	
$('.kedit a').editableLinks();
$('.kedit img').editableImages();

