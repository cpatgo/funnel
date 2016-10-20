(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function () {
	"use strict";

	require('./modules/ui');
	require('./modules/builder');
	require('./modules/config');
	require('./modules/imageLibrary');
	require('./modules/account');

}());
},{"./modules/account":2,"./modules/builder":3,"./modules/config":5,"./modules/imageLibrary":6,"./modules/ui":8}],2:[function(require,module,exports){
(function () {
	"use strict";

	var appUI = require('./ui.js').appUI;

	var account = {
        
        buttonUpdateAccountDetails: document.getElementById('accountDetailsSubmit'),
        buttonUpdateLoginDetails: document.getElementById('accountLoginSubmit'),
        
        init: function() {
            
            $(this.buttonUpdateAccountDetails).on('click', this.updateAccountDetails);
            $(this.buttonUpdateLoginDetails).on('click', this.updateLoginDetails);
                        
        },
        
        
        /*
            updates account details
        */
        updateAccountDetails: function() {
            
            //all fields filled in?
            
            var allGood = 1;
            
            if( $('#account_details input#firstname').val() === '' ) {
                $('#account_details input#firstname').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_details input#firstname').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }
            
            if( $('#account_details input#lastname').val() === '' ) {
                $('#account_details input#lastname').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_details input#lastname').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }
		
            if( allGood === 1 ) {

                var theButton = $(this);
                
                //disable button
                $(this).addClass('disabled');
                
                //show loader
                $('#account_details .loader').fadeIn(500);
                
                //remove alerts
                $('#account_details .alerts > *').remove();
                
                $.ajax({
                    url: appUI.siteUrl+"users/uaccount",
                    type: 'post',
                    dataType: 'json',
                    data: $('#account_details').serialize()
                }).done(function(ret){
                    
                    //enable button
                    theButton.removeClass('disabled');
                    
                    //hide loader
                    $('#account_details .loader').hide();
                    $('#account_details .alerts').append( $(ret.responseHTML) );

                    if( ret.responseCode === 1 ) {//success
                        setTimeout(function () { 
                            $('#account_details .alerts > *').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }
                });

            }
            
        },
        
        
        /*
            updates account login details
        */
        updateLoginDetails: function() {
			
			console.log(appUI);
            
            var allGood = 1;
            
            if( $('#account_login input#email').val() === '' ) {
                $('#account_login input#email').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_login input#email').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }
            
            if( $('#account_login input#password').val() === '' ) {
                $('#account_login input#password').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_login input#password').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }
            
            if( allGood === 1 ) {
                
                var theButton = $(this);

                //disable button
                $(this).addClass('disabled');
                
                //show loader
                $('#account_login .loader').fadeIn(500);
                
                //remove alerts
                $('#account_login .alerts > *').remove();
                
                $.ajax({
                    url: appUI.siteUrl+"users/ulogin",
                    type: 'post',
                    dataType: 'json',
                    data: $('#account_login').serialize()
                }).done(function(ret){
                    
                    //enable button
                    theButton.removeClass('disabled');
                    
                    //hide loader
                    $('#account_login .loader').hide();
                    $('#account_login .alerts').append( $(ret.responseHTML) );
					
                    if( ret.responseCode === 1 ) {//success
                        setTimeout(function () { 
                            $('#account_login .alerts > *').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }
                
                });
            
            }
            
        }
        
    };
    
    account.init();

}());
},{"./ui.js":8}],3:[function(require,module,exports){
(function () {
	"use strict";

    var siteBuilderUtils = require('./utils.js');
    var bConfig = require('./config.js');
    var appUI = require('./ui.js').appUI;
    var publisher = require('../vendor/publisher');
    var form_id = 0;

	 /*
        Basic Builder UI initialisation
    */
    var builderUI = {
        
        allBlocks: {},                                              //holds all blocks loaded from the server
        menuWrapper: document.getElementById('menu'),
        primarySideMenuWrapper: document.getElementById('main'),
        buttonBack: document.getElementById('backButton'),
        buttonBackConfirm: document.getElementById('leavePageButton'),
        
        aceEditors: {},
        frameContents: '',                                      //holds frame contents
        templateID: 0,                                          //holds the template ID for a page (???)
                
        modalDeleteBlock: document.getElementById('deleteBlock'),
        modalResetBlock: document.getElementById('resetBlock'),
        modalDeletePage: document.getElementById('deletePage'),
        buttonDeletePageConfirm: document.getElementById('deletePageConfirm'),
        
        dropdownPageLinks: document.getElementById('internalLinksDropdown'),

        pageInUrl: null,
        
        tempFrame: {},

        currentResponsiveMode: {},
                
        init: function(){
                                                
            //load blocks
            $.getJSON(appUI.baseUrl+'elements.json?v=12345678', function(data){ builderUI.allBlocks = data; builderUI.implementBlocks(); });
            
            //sitebar hover animation action
            $(this.menuWrapper).on('mouseenter', function(){
                
                $(this).stop().animate({'left': '0px'}, 500);
                
            }).on('mouseleave', function(){
                
                $(this).stop().animate({'left': '-190px'}, 500);
                
                $('#menu #main a').removeClass('active');
                $('.menu .second').stop().animate({
                    width: 0
                }, 500, function(){
                    $('#menu #second').hide();
                });
                
            });
            
            //prevent click event on ancors in the block section of the sidebar
            $(this.primarySideMenuWrapper).on('click', 'a:not(.actionButtons)', function(e){e.preventDefault();});
            
            $(this.buttonBack).on('click', this.backButton);
            $(this.buttonBackConfirm).on('click', this.backButtonConfirm);
            
            //notify the user of pending chnages when clicking the back button
            $(window).bind('beforeunload', function(){
                if( site.pendingChanges === true ) {
                    return 'Your site contains changed which haven\'t been saved yet. Are you sure you want to leave?';
                }
            });
                                                
            //URL parameters
            builderUI.pageInUrl = siteBuilderUtils.getParameterByName('p');

        },
        
        
        /*
            builds the blocks into the site bar
        */
        implementBlocks: function() {

            var newItem, loaderFunction;
            
            for( var key in this.allBlocks.elements ) {
                
                var niceKey = key.toLowerCase().replace(" ", "_");
                
                $('<li><a href="" id="'+niceKey+'">'+key+'</a></li>').appendTo('#menu #main ul#elementCats');
                
                for( var x = 0; x < this.allBlocks.elements[key].length; x++ ) {
                    
                    if( this.allBlocks.elements[key][x].thumbnail === null ) {//we'll need an iframe
                        
                        //build us some iframes!
                        
                        if( this.allBlocks.elements[key][x].sandbox ) {
                            
                            if( this.allBlocks.elements[key][x].loaderFunction ) {
                                loaderFunction = 'data-loaderfunction="'+this.allBlocks.elements[key][x].loaderFunction+'"';
                            }
                            
                            newItem = $('<li class="element '+niceKey+'"><iframe src="'+appUI.baseUrl+this.allBlocks.elements[key][x].url+'" scrolling="no" sandbox="allow-same-origin"></iframe></li>');
                        
                        } else {
                            
                            newItem = $('<li class="element '+niceKey+'"><iframe src="about:blank" scrolling="no"></iframe></li>');
                        
                        }
                        
                        newItem.find('iframe').uniqueId();
                        newItem.find('iframe').attr('src', appUI.baseUrl+this.allBlocks.elements[key][x].url);
                    
                    } else {//we've got a thumbnail
                        
                        if( this.allBlocks.elements[key][x].sandbox ) {
                            
                            if( this.allBlocks.elements[key][x].loaderFunction ) {
                                loaderFunction = 'data-loaderfunction="'+this.allBlocks.elements[key][x].loaderFunction+'"';
                            }
                            
                            newItem = $('<li class="element '+niceKey+'"><img src="'+appUI.baseUrl+this.allBlocks.elements[key][x].thumbnail+'" data-srcc="'+appUI.baseUrl+this.allBlocks.elements[key][x].url+'" data-height="'+this.allBlocks.elements[key][x].height+'" data-sandbox="" '+loaderFunction+'></li>');
                            
                        } else {
                                
                            newItem = $('<li class="element '+niceKey+'"><img src="'+appUI.baseUrl+this.allBlocks.elements[key][x].thumbnail+'" data-srcc="'+appUI.baseUrl+this.allBlocks.elements[key][x].url+'" data-height="'+this.allBlocks.elements[key][x].height+'"></li>');
                                
                        }
                    }
                    
                    newItem.appendTo('#menu #second ul#elements');
            
                    //zoomer works

                    var theHeight;
                    
                    if( this.allBlocks.elements[key][x].height ) {
                        
                        theHeight = this.allBlocks.elements[key][x].height*0.25;
                    
                    } else {
                        
                        theHeight = 'auto';
                        
                    }
                    
                    newItem.find('iframe').zoomer({
                        zoom: 0.25,
                        width: 270,
                        height: theHeight,
                        message: "Drag&Drop Me!"
                    });
                
                }
            
            }
            
            //draggables
            builderUI.makeDraggable();
            
        },
                
        
        /*
            event handler for when the back link is clicked
        */
        backButton: function() {
            
            if( site.pendingChanges === true ) {
                $('#backModal').modal('show');
                return false;
            }
            
        },
        
        
        /*
            button for confirming leaving the page
        */
        backButtonConfirm: function() {
            
            site.pendingChanges = false;//prevent the JS alert after confirming user wants to leave
            
        },
                
       
        /*
            makes the blocks and templates in the sidebar draggable onto the canvas
        */
        makeDraggable: function() {
                        
            $('#elements li, #templates li').each(function(){

                $(this).draggable({
                    helper: function() {
                        return $('<div style="height: 100px; width: 300px; background: #F9FAFA; box-shadow: 5px 5px 1px rgba(0,0,0,0.1); text-align: center; line-height: 100px; font-size: 28px; color: #16A085"><span class="fui-list"></span></div>');
                    },
                    revert: 'invalid',
                    appendTo: 'body',
                    connectToSortable: '#pageList > ul',
                    start: function () {
                        site.moveMode('on');
                    },
                    stop: function () {}
                }); 
            
            });
            
            $('#elements li a').each(function(){
                
                $(this).unbind('click').bind('click', function(e){
                    e.preventDefault();
                });
            
            });
            
        },
        
        
        /*
            Implements the site on the canvas, called from the Site object when the siteData has completed loading
        */
        populateCanvas: function() {

            var i,
                counter = 1;
                        
            //loop through the pages
                                    
            for( i in site.pages ) {
                
                var newPage = new Page(i, site.pages[i], counter);
                                            
                counter++;

                //set this page as active?
                if( builderUI.pageInUrl === i ) {
                    newPage.selectPage();
                }
                                
            }
            
            //activate the first page
            if(site.sitePages.length > 0 && builderUI.pageInUrl === null) {
                site.sitePages[0].selectPage();
            }
                                    
        },


        /*
            Canvas loading on/off
        */
        canvasLoading: function (value) {

            if ( value === 'on' && document.getElementById('frameWrapper').querySelectorAll('#canvasOverlay').length === 0 ) {

                var overlay = document.createElement('DIV');

                overlay.style.display = 'flex';
                $(overlay).hide();
                overlay.id = 'canvasOverlay';

                overlay.innerHTML = '<div class="loader"><span>{</span><span>}</span></div>';

                document.getElementById('frameWrapper').appendChild(overlay);

                $('#canvasOverlay').fadeIn(500);

            } else if ( value === 'off' && document.getElementById('frameWrapper').querySelectorAll('#canvasOverlay').length === 1 ) {

                site.loaded();

                $('#canvasOverlay').fadeOut(500, function () {
                    this.remove();
                });

                form_id = $('#formID').val();
                if(form_id !== 0) {
                    site.quick_load_form();
                }

            }

        }
        
    };

    /*
        Page constructor
    */
    function Page (pageName, page, counter) {
    
        this.name = pageName || "";
        this.pageID = page.page_id || 0;
        this.blocks = [];
        this.parentUL = {}; //parent UL on the canvas
        this.status = '';//'', 'new' or 'changed'
        this.scripts = [];//tracks script URLs used on this page
        
        this.pageSettings = {
            title: page.pages_title || '',
            meta_description: page.meta_description || '',
            meta_keywords: page.meta_keywords || '',
            header_includes: page.header_includes || '',
            page_css: page.page_css || ''
        };
                
        this.pageMenuTemplate = '<a href="" class="menuItemLink">page</a><span class="pageButtons"><a href="" class="fileEdit fui-new"></a><a href="" class="fileDel fui-cross"><a class="btn btn-xs btn-primary btn-embossed fileSave fui-check" href="#"></a></span></a></span>';
        
        this.menuItem = {};//reference to the pages menu item for this page instance
        this.linksDropdownItem = {};//reference to the links dropdown item for this page instance
        
        this.parentUL = document.createElement('UL');
        this.parentUL.setAttribute('id', "page"+counter);
                
        /*
            makes the clicked page active
        */
        this.selectPage = function() {
            
            //console.log('select:');
            //console.log(this.pageSettings);
                        
            //mark the menu item as active
            site.deActivateAll();
            $(this.menuItem).addClass('active');
                        
            //let Site know which page is currently active
            site.setActive(this);
            
            //display the name of the active page on the canvas
            site.pageTitle.innerHTML = this.name;
            
            //load the page settings into the page settings modal
            site.inputPageSettingsTitle.value = this.pageSettings.title;
            site.inputPageSettingsMetaDescription.value = this.pageSettings.meta_description;
            site.inputPageSettingsMetaKeywords.value = this.pageSettings.meta_keywords;
            site.inputPageSettingsIncludes.value = this.pageSettings.header_includes;
            site.inputPageSettingsPageCss.value = this.pageSettings.page_css;
                          
            //trigger custom event
            $('body').trigger('changePage');
            
            //reset the heights for the blocks on the current page
            for( var i in this.blocks ) {
                
                if( Object.keys(this.blocks[i].frameDocument).length > 0 ){
                    this.blocks[i].heightAdjustment();
                }
            
            }
            
            //show the empty message?
            this.isEmpty();
                                    
        };
        
        /*
            changed the location/order of a block within a page
        */
        this.setPosition = function(frameID, newPos) {
            
            //we'll need the block object connected to iframe with frameID
            
            for(var i in this.blocks) {
                
                if( this.blocks[i].frame.getAttribute('id') === frameID ) {
                    
                    //change the position of this block in the blocks array
                    this.blocks.splice(newPos, 0, this.blocks.splice(i, 1)[0]);
                    
                }
                
            }
                        
        };
        
        /*
            delete block from blocks array
        */
        this.deleteBlock = function(block) {
            
            //remove from blocks array
            for( var i in this.blocks ) {
                if( this.blocks[i] === block ) {
                    //found it, remove from blocks array
                    this.blocks.splice(i, 1);
                }
            }
            
            site.setPendingChanges(true);
            
        };
        
        /*
            toggles all block frameCovers on this page
        */
        this.toggleFrameCovers = function(onOrOff) {
            
            for( var i in this.blocks ) {
                                 
                this.blocks[i].toggleCover(onOrOff);
                
            }
            
        };
        
        /*
            setup for editing a page name
        */
        this.editPageName = function() {
            
            if( !this.menuItem.classList.contains('edit') ) {
            
                //hide the link
                this.menuItem.querySelector('a.menuItemLink').style.display = 'none';
            
                //insert the input field
                var newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.setAttribute('name', 'page');
                newInput.setAttribute('value', this.name);
                this.menuItem.insertBefore(newInput, this.menuItem.firstChild);
                    
                newInput.focus();
        
                var tmpStr = newInput.getAttribute('value');
                newInput.setAttribute('value', '');
                newInput.setAttribute('value', tmpStr);
                            
                this.menuItem.classList.add('edit');
            
            }
            
        };
        
        /*
            Updates this page's name (event handler for the save button)
        */
        this.updatePageNameEvent = function(el) {
            
            if( this.menuItem.classList.contains('edit') ) {
            
                //el is the clicked button, we'll need access to the input
                var theInput = this.menuItem.querySelector('input[name="page"]');
                
                //make sure the page's name is OK
                if( site.checkPageName(theInput.value) ) {
                   
                    this.name = site.prepPageName( theInput.value );
            
                    this.menuItem.querySelector('input[name="page"]').remove();
                    this.menuItem.querySelector('a.menuItemLink').innerHTML = this.name;
                    this.menuItem.querySelector('a.menuItemLink').style.display = 'block';
            
                    this.menuItem.classList.remove('edit');
                
                    //update the links dropdown item
                    this.linksDropdownItem.text = this.name;
                    this.linksDropdownItem.setAttribute('value', this.name+".html");
                    
                    //update the page name on the canvas
                    site.pageTitle.innerHTML = this.name;
            
                    //changed page title, we've got pending changes
                    site.setPendingChanges(true);
                                        
                } else {
                    
                    alert(site.pageNameError);
                    
                }
                                        
            }
            
        };
        
        /*
            deletes this entire page
        */
        this.delete = function() {
                        
            //delete from the Site
            for( var i in site.sitePages ) {
                
                if( site.sitePages[i] === this ) {//got a match!
                    
                    //delete from site.sitePages
                    site.sitePages.splice(i, 1);
                    
                    //delete from canvas
                    this.parentUL.remove();
                    
                    //add to deleted pages
                    site.pagesToDelete.push(this.name);
                    
                    //delete the page's menu item
                    this.menuItem.remove();
                    
                    //delet the pages link dropdown item
                    this.linksDropdownItem.remove();
                    
                    //activate the first page
                    site.sitePages[0].selectPage();
                    
                    //page was deleted, so we've got pending changes
                    site.setPendingChanges(true);
                    
                }
                
            }
                        
        };
        
        /*
            checks if the page is empty, if so show the 'empty' message
        */
        this.isEmpty = function() {
            
            if( this.blocks.length === 0 ) {
                
                site.messageStart.style.display = 'block';
                site.divFrameWrapper.classList.add('empty');
                             
            } else {
                
                site.messageStart.style.display = 'none';
                site.divFrameWrapper.classList.remove('empty');
                
            }
                        
        };
            
        /*
            preps/strips this page data for a pending ajax request
        */
        this.prepForSave = function() {
            
            var page = {};
                    
            page.name = this.name;
            page.pageSettings = this.pageSettings;
            page.status = this.status;
            page.pageID = this.pageID;
            page.blocks = [];
                    
            //process the blocks
                    
            for( var x = 0; x < this.blocks.length; x++ ) {
                        
                var block = {};
                        
                if( this.blocks[x].sandbox ) {
                            
                    block.frameContent = "<html>"+$('#sandboxes #'+this.blocks[x].sandbox).contents().find('html').html()+"</html>";
                    block.sandbox = true;
                    block.loaderFunction = this.blocks[x].sandbox_loader;
                            
                } else {
                                                        
                    block.frameContent = this.blocks[x].getSource();
                    block.sandbox = false;
                    block.loaderFunction = '';
                            
                }
                        
                block.frameHeight = this.blocks[x].frameHeight;
                block.originalUrl = this.blocks[x].originalUrl;
                if ( this.blocks[x].global ) block.frames_global = true;
                                                                
                page.blocks.push(block);
                        
            }
            
            return page;
            
        };
            
        /*
            generates the full page, using skeleton.html
        */
        this.fullPage = function() {
            
            var page = this;//reference to self for later
            page.scripts = [];//make sure it's empty, we'll store script URLs in there later
                        
            var newDocMainParent = $('iframe#skeleton').contents().find( bConfig.pageContainer );
            
            //empty out the skeleton first
            $('iframe#skeleton').contents().find( bConfig.pageContainer ).html('');
            
            //remove old script tags
            $('iframe#skeleton').contents().find( 'script' ).each(function(){
                $(this).remove();
            });

            var theContents;
                        
            for( var i in this.blocks ) {
                
                //grab the block content
                if (this.blocks[i].sandbox !== false) {
                                
                    theContents = $('#sandboxes #'+this.blocks[i].sandbox).contents().find( bConfig.pageContainer ).clone();
                            
                } else {
                                
                    theContents = $(this.blocks[i].frameDocument.body).find( bConfig.pageContainer ).clone();
                            
                }
                                
                //remove video frameCovers
                theContents.find('.frameCover').each(function () {
                    $(this).remove();
                });
                
                //remove video frameWrappers
                theContents.find('.videoWrapper').each(function(){
                    
                    var cnt = $(this).contents();
                    $(this).replaceWith(cnt);
                    
                });
                
                //remove style leftovers from the style editor
                for( var key in bConfig.editableItems ) {
                                                                
                    theContents.find( key ).each(function(){
                                                                        
                        $(this).removeAttr('data-selector');
                        
                        $(this).css('outline', '');
                        $(this).css('outline-offset', '');
                        $(this).css('cursor', '');
                                                                        
                        if( $(this).attr('style') === '' ) {
                                        
                            $(this).removeAttr('style');
                                    
                        }
                                
                    });
                            
                }
                
                //remove style leftovers from the content editor
                for ( var x = 0; x < bConfig.editableContent.length; ++x) {
                                
                    theContents.find( bConfig.editableContent[x] ).each(function(){
                                    
                        $(this).removeAttr('data-selector');
                                
                    });
                            
                }
                
                //append to DOM in the skeleton
                newDocMainParent.append( $(theContents.html()) );
                
                //do we need to inject any scripts?
                var scripts = $(this.blocks[i].frameDocument.body).find('script');
                var theIframe = document.getElementById("skeleton");
                                            
                if( scripts.size() > 0 ) {
                                
                    scripts.each(function(){

                        var script;
                                    
                        if( $(this).text() !== '' ) {//script tags with content
                                        
                            script = theIframe.contentWindow.document.createElement("script");
                            script.type = 'text/javascript';
                            script.innerHTML = $(this).text();
                                        
                            theIframe.contentWindow.document.body.appendChild(script);
                                    
                        } else if( $(this).attr('src') !== null && page.scripts.indexOf($(this).attr('src')) === -1 ) {
                            //use indexOf to make sure each script only appears on the produced page once
                                        
                            script = theIframe.contentWindow.document.createElement("script");
                            script.type = 'text/javascript';
                            script.src = $(this).attr('src');
                                        
                            theIframe.contentWindow.document.body.appendChild(script);
                            
                            page.scripts.push($(this).attr('src'));
                                    
                        }
                                
                    });
                            
                }
            
            }
                        
        };


        /*
            Checks if all blocks on this page have finished loading
        */
        this.loaded = function () {

            var i;

            for ( i = 0; i <this.blocks.length; i++ ) {

                if ( !this.blocks[i].loaded ) return false;

            }

            return true;

        };
            
        /*
            clear out this page
        */
        this.clear = function() {
            
            var block = this.blocks.pop();
            
            while( block !== undefined ) {
                
                block.delete();
                
                block = this.blocks.pop();
                
            }
                                    
        };


        /*
            Height adjustment for all blocks on the page
        */
        this.heightAdjustment = function () {

            for ( var i = 0; i < this.blocks.length; i++ ) {
                this.blocks[i].heightAdjustment();
            }

        };
         
        
        //loop through the frames/blocks
        
        if( page.hasOwnProperty('blocks') ) {
        
            for( var x = 0; x < page.blocks.length; x++ ) {
            
                //create new Block
            
                var newBlock = new Block();
            
                page.blocks[x].src = appUI.siteUrl+"sites/getframe/"+page.blocks[x].frames_id;
                
                //sandboxed block?
                if( page.blocks[x].frames_sandbox === '1') {
                                        
                    newBlock.sandbox = true;
                    newBlock.sandbox_loader = page.blocks[x].frames_loaderfunction;
                
                }
                
                newBlock.frameID = page.blocks[x].frames_id;
                if ( page.blocks[x].frames_global === '1' ) newBlock.global = true;
                newBlock.createParentLI(page.blocks[x].frames_height);
                newBlock.createFrame(page.blocks[x]);
                newBlock.createFrameCover();
                newBlock.insertBlockIntoDom(this.parentUL);
                                                                    
                //add the block to the new page
                this.blocks.push(newBlock);
                                        
            }
            
        }
        
        //add this page to the site object
        site.sitePages.push( this );
        
        //plant the new UL in the DOM (on the canvas)
        site.divCanvas.appendChild(this.parentUL);
        
        //make the blocks/frames in each page sortable
        
        var thePage = this;
        
        $(this.parentUL).sortable({
            revert: true,
            placeholder: "drop-hover",
            handle: '.dragBlock',
            cancel: '',
            stop: function () {
                site.moveMode('off');
                site.setPendingChanges(true);
                if ( !site.loaded() ) builderUI.canvasLoading('on');
            },
            beforeStop: function(event, ui){
                
                //template or regular block?
                var attr = ui.item.attr('data-frames');

                var newBlock;
                    
                if (typeof attr !== typeof undefined && attr !== false) {//template, build it
                 
                    $('#start').hide();
                                        
                    //clear out all blocks on this page    
                    thePage.clear();
                                            
                    //create the new frames
                    var frameIDs = ui.item.attr('data-frames').split('-');
                    var heights = ui.item.attr('data-heights').split('-');
                    var urls = ui.item.attr('data-originalurls').split('-');
                        
                    for( var x = 0; x < frameIDs.length; x++) {
                                                
                        newBlock = new Block();
                        newBlock.createParentLI(heights[x]);
                        
                        var frameData = {};
                        
                        frameData.src = appUI.siteUrl+'sites/getframe/'+frameIDs[x];
                        frameData.frames_original_url = appUI.siteUrl+'sites/getframe/'+frameIDs[x];
                        frameData.frames_height = heights[x];
                        
                        newBlock.createFrame( frameData );
                        newBlock.createFrameCover();
                        newBlock.insertBlockIntoDom(thePage.parentUL);
                        
                        //add the block to the new page
                        thePage.blocks.push(newBlock);
                        
                        //dropped element, so we've got pending changes
                        site.setPendingChanges(true);
                            
                    }
                
                    //set the tempateID
                    builderUI.templateID = ui.item.attr('data-pageid');
                                                                                    
                    //make sure nothing gets dropped in the lsit
                    ui.item.html(null);
                        
                    //delete drag place holder
                    $('body .ui-sortable-helper').remove();
                    
                } else {//regular block
                
                    //are we dealing with a new block being dropped onto the canvas, or a reordering og blocks already on the canvas?
                
                    if( ui.item.find('.frameCover > button').size() > 0 ) {//re-ordering of blocks on canvas
                    
                        //no need to create a new block object, we simply need to make sure the position of the existing block in the Site object
                        //is changed to reflect the new position of the block on th canvas
                    
                        var frameID = ui.item.find('iframe').attr('id');
                        var newPos = ui.item.index();
                    
                        site.activePage.setPosition(frameID, newPos);
                                        
                    } else {//new block on canvas
                                                
                        //new block                    
                        newBlock = new Block();
                                
                        newBlock.placeOnCanvas(ui);
                                    
                    }
                    
                }
                
            },
            start: function (event, ui) {

                site.moveMode('on');
                    
                if( ui.item.find('.frameCover').size() !== 0 ) {
                    builderUI.frameContents = ui.item.find('iframe').contents().find( bConfig.pageContainer ).html();
                }
            
            },
            over: function(){
                    
                $('#start').hide();
                
            }
        });
        
        //add to the pages menu
        this.menuItem = document.createElement('LI');
        this.menuItem.innerHTML = this.pageMenuTemplate;
        
        $(this.menuItem).find('a:first').text(pageName).attr('href', '#page'+counter);
        
        var theLink = $(this.menuItem).find('a:first').get(0);
        
        //bind some events
        this.menuItem.addEventListener('click', this, false);
        
        this.menuItem.querySelector('a.fileEdit').addEventListener('click', this, false);
        this.menuItem.querySelector('a.fileSave').addEventListener('click', this, false);
        this.menuItem.querySelector('a.fileDel').addEventListener('click', this, false);
        
        //add to the page link dropdown
        this.linksDropdownItem = document.createElement('OPTION');
        this.linksDropdownItem.setAttribute('value', pageName+".html");
        this.linksDropdownItem.text = pageName;
                
        builderUI.dropdownPageLinks.appendChild( this.linksDropdownItem );
        
        site.pagesMenu.appendChild(this.menuItem);
                    
    }
    
    Page.prototype.handleEvent = function(event) {
        switch (event.type) {
            case "click": 
                                
                if( event.target.classList.contains('fileEdit') ) {
                
                    this.editPageName();
                    
                } else if( event.target.classList.contains('fileSave') ) {
                                        
                    this.updatePageNameEvent(event.target);
                
                } else if( event.target.classList.contains('fileDel') ) {
                    
                    var thePage = this;
                
                    $(builderUI.modalDeletePage).modal('show');
                    
                    $(builderUI.modalDeletePage).off('click', '#deletePageConfirm').on('click', '#deletePageConfirm', function() {
                        
                        thePage.delete();
                        
                        $(builderUI.modalDeletePage).modal('hide');
                        
                    });
                                        
                } else {
                    
                    this.selectPage();
                
                }
                
        }
    };


    /*
        Block constructor
    */
    function Block () {
        
        this.frameID = 0;
        this.loaded = false;
        this.sandbox = false;
        this.sandbox_loader = '';
        this.status = '';//'', 'changed' or 'new'
        this.global = false;
        this.originalUrl = '';
        
        this.parentLI = {};
        this.frameCover = {};
        this.frame = {};
        this.frameDocument = {};
        this.frameHeight = 0;
        
        this.annot = {};
        this.annotTimeout = {};
        
        /*
            creates the parent container (LI)
        */
        this.createParentLI = function(height) {
            
            this.parentLI = document.createElement('LI');
            this.parentLI.setAttribute('class', 'element');
            //this.parentLI.setAttribute('style', 'height: '+height+'px');
            
        };
        
        /*
            creates the iframe on the canvas
        */
        this.createFrame = function(frame) {
                        
            this.frame = document.createElement('IFRAME');
            this.frame.setAttribute('frameborder', 0);
            this.frame.setAttribute('scrolling', 0);
            this.frame.setAttribute('src', frame.src);
            this.frame.setAttribute('data-originalurl', frame.frames_original_url);
            this.originalUrl = frame.frames_original_url;
            //this.frame.setAttribute('data-height', frame.frames_height);
            //this.frameHeight = frame.frames_height;
                        
            $(this.frame).uniqueId();
            
            //sandbox?
            if( this.sandbox !== false ) {
                            
                this.frame.setAttribute('data-loaderfunction', this.sandbox_loader);
                this.frame.setAttribute('data-sandbox', this.sandbox);
                            
                //recreate the sandboxed iframe elsewhere
                var sandboxedFrame = $('<iframe src="'+frame.src+'" id="'+this.sandbox+'" sandbox="allow-same-origin"></iframe>');
                $('#sandboxes').append( sandboxedFrame );
                            
            }
                        
        };
            
        /*
            insert the iframe into the DOM on the canvas
        */
        this.insertBlockIntoDom = function(theUL) {
            
            this.parentLI.appendChild(this.frame);
            theUL.appendChild( this.parentLI );
            
            this.frame.addEventListener('load', this, false);

            builderUI.canvasLoading('on');
            
        };
            
        /*
            sets the frame document for the block's iframe
        */
        this.setFrameDocument = function() {
            
            //set the frame document as well
            if( this.frame.contentDocument ) {
                this.frameDocument = this.frame.contentDocument;   
            } else {
                this.frameDocument = this.frame.contentWindow.document;
            }
            
            //this.heightAdjustment();
                                    
        };
        
        /*
            creates the frame cover and block action button
        */
        this.createFrameCover = function() {
            
            //build the frame cover and block action buttons
            this.frameCover = document.createElement('DIV');
            this.frameCover.classList.add('frameCover');
            this.frameCover.classList.add('fresh');
                    
            var delButton = document.createElement('BUTTON');
            delButton.setAttribute('class', 'btn btn-inverse btn-sm deleteBlock');
            delButton.setAttribute('type', 'button');
            delButton.innerHTML = '<i class="fui-trash"></i> <span>remove</span>';
            delButton.addEventListener('click', this, false);
                    
            var resetButton = document.createElement('BUTTON');
            resetButton.setAttribute('class', 'btn btn-inverse btn-sm resetBlock');
            resetButton.setAttribute('type', 'button');
            resetButton.innerHTML = '<i class="fa fa-refresh"></i> <span>reset</span>';
            resetButton.addEventListener('click', this, false);
                    
            var htmlButton = document.createElement('BUTTON');
            htmlButton.setAttribute('class', 'btn btn-inverse btn-sm htmlBlock');
            htmlButton.setAttribute('type', 'button');
            htmlButton.innerHTML = '<i class="fa fa-code"></i> <span>source</span>';
            htmlButton.addEventListener('click', this, false);

            var dragButton = document.createElement('BUTTON');
            dragButton.setAttribute('class', 'btn btn-inverse btn-sm dragBlock');
            dragButton.setAttribute('type', 'button');
            dragButton.innerHTML = '<i class="fa fa-arrows"></i> <span>Move</span>';
            dragButton.addEventListener('click', this, false);

            var globalLabel = document.createElement('LABEL');
            globalLabel.classList.add('checkbox');
            globalLabel.classList.add('primary');
            var globalCheckbox = document.createElement('INPUT');
            globalCheckbox.type = 'checkbox';
            globalCheckbox.setAttribute('data-toggle', 'checkbox');
            globalCheckbox.checked = this.global;
            globalLabel.appendChild(globalCheckbox);
            var globalText = document.createTextNode('Global');
            globalLabel.appendChild(globalText);

            var trigger = document.createElement('span');
            trigger.classList.add('fui-gear');
                    
            this.frameCover.appendChild(delButton);
            this.frameCover.appendChild(resetButton);
            this.frameCover.appendChild(htmlButton);
            this.frameCover.appendChild(dragButton);
            this.frameCover.appendChild(globalLabel);
            this.frameCover.appendChild(trigger);
                            
            this.parentLI.appendChild(this.frameCover);

            var theBlock = this;

            $(globalCheckbox).on('change', function (e) {

                theBlock.toggleGlobal(e);

            }).radiocheck();
                                                        
        };


        /*
            
        */
        this.toggleGlobal = function (e) {

            if ( e.currentTarget.checked ) this.global = true;
            else this.global = false;

            //we've got pending changes
            site.setPendingChanges(true);

            console.log(this);

        };

            
        /*
            automatically corrects the height of the block's iframe depending on its content
        */
        this.heightAdjustment = function() {
            
            if ( Object.keys(this.frameDocument).length !== 0 ) {

                var height,
                    bodyHeight = this.frameDocument.body.offsetHeight,
                    pageContainerHeight = this.frameDocument.body.querySelector( bConfig.pageContainer ).offsetHeight;

                if ( bodyHeight > pageContainerHeight && !this.frameDocument.body.classList.contains( bConfig.bodyPaddingClass ) ) height = pageContainerHeight;
                else height = bodyHeight;

                this.frame.style.height = height+"px";
                this.parentLI.style.height = height+"px";
                //this.frameCover.style.height = height+"px";
                
                this.frameHeight = height;

            }
                                                                                    
        };
            
        /*
            deletes a block
        */
        this.delete = function() {
                        
            //remove from DOM/canvas with a nice animation
            $(this.frame.parentNode).fadeOut(500, function(){
                    
                this.remove();
                    
                site.activePage.isEmpty();
                
            });
            
            //remove from blocks array in the active page
            site.activePage.deleteBlock(this);
            
            //sanbox
            if( this.sanbdox ) {
                document.getElementById( this.sandbox ).remove();   
            }
            
            //element was deleted, so we've got pending change
            site.setPendingChanges(true);
                        
        };
            
        /*
            resets a block to it's orignal state
        */
        this.reset = function (fireEvent) {

            if ( typeof fireEvent === 'undefined') fireEvent = true;
            
            //reset frame by reloading it
            this.frame.contentWindow.location = this.frame.getAttribute('data-originalurl');
            
            //sandbox?
            if( this.sandbox ) {
                var sandboxFrame = document.getElementById(this.sandbox).contentWindow.location.reload();  
            }
            
            //element was deleted, so we've got pending changes
            site.setPendingChanges(true);

            builderUI.canvasLoading('on');

            if ( fireEvent ) publisher.publish('onBlockChange', this, 'reload');
            
        };
            
        /*
            launches the source code editor
        */
        this.source = function() {
            
            //hide the iframe
            this.frame.style.display = 'none';
            
            //disable sortable on the parentLI
            $(this.parentLI.parentNode).sortable('disable');
            
            //built editor element
            var theEditor = document.createElement('DIV');
            theEditor.classList.add('aceEditor');
            $(theEditor).uniqueId();
            
            this.parentLI.appendChild(theEditor);
            
            //build and append error drawer
            var newLI = document.createElement('LI');
            var errorDrawer = document.createElement('DIV');
            errorDrawer.classList.add('errorDrawer');
            errorDrawer.setAttribute('id', 'div_errorDrawer');
            errorDrawer.innerHTML = '<button type="button" class="btn btn-xs btn-embossed btn-default button_clearErrorDrawer" id="button_clearErrorDrawer">CLEAR</button>';
            newLI.appendChild(errorDrawer);
            errorDrawer.querySelector('button').addEventListener('click', this, false);
            this.parentLI.parentNode.insertBefore(newLI, this.parentLI.nextSibling);
            
            ace.config.set("basePath", "/js/vendor/ace");
            
            var theId = theEditor.getAttribute('id');
            var editor = ace.edit( theId );

            //editor.getSession().setUseWrapMode(true);
            
            var pageContainer = this.frameDocument.querySelector( bConfig.pageContainer );
            var theHTML = pageContainer.innerHTML;
            

            editor.setValue( theHTML );
            editor.setTheme("ace/theme/twilight");
            editor.getSession().setMode("ace/mode/html");
            
            var block = this;
            
            
            editor.getSession().on("changeAnnotation", function(){
                
                block.annot = editor.getSession().getAnnotations();
                
                clearTimeout(block.annotTimeout);

                var timeoutCount;
                
                if( $('#div_errorDrawer p').size() === 0 ) {
                    timeoutCount = bConfig.sourceCodeEditSyntaxDelay;
                } else {
                    timeoutCount = 100;
                }
                
                block.annotTimeout = setTimeout(function(){
                                                            
                    for (var key in block.annot){
                    
                        if (block.annot.hasOwnProperty(key)) {
                        
                            if( block.annot[key].text !== "Start tag seen without seeing a doctype first. Expected e.g. <!DOCTYPE html>." ) {
                            
                                var newLine = $('<p></p>');
                                var newKey = $('<b>'+block.annot[key].type+': </b>');
                                var newInfo = $('<span> '+block.annot[key].text + "on line " + " <b>" + block.annot[key].row+'</b></span>');
                                newLine.append( newKey );
                                newLine.append( newInfo );
                    
                                $('#div_errorDrawer').append( newLine );
                        
                            }
                    
                        }
                
                    }
                
                    if( $('#div_errorDrawer').css('display') === 'none' && $('#div_errorDrawer').find('p').size() > 0 ) {
                        $('#div_errorDrawer').slideDown();
                    }
                        
                }, timeoutCount);
                
            
            });
            
            //buttons
            var cancelButton = document.createElement('BUTTON');
            cancelButton.setAttribute('type', 'button');
            cancelButton.classList.add('btn');
            cancelButton.classList.add('btn-danger');
            cancelButton.classList.add('editCancelButton');
            cancelButton.classList.add('btn-sm');
            cancelButton.innerHTML = '<i class="fui-cross"></i> <span>Cancel</span>';
            cancelButton.addEventListener('click', this, false);
            
            var saveButton = document.createElement('BUTTON');
            saveButton.setAttribute('type', 'button');
            saveButton.classList.add('btn');
            saveButton.classList.add('btn-primary');
            saveButton.classList.add('editSaveButton');
            saveButton.classList.add('btn-sm');
            saveButton.innerHTML = '<i class="fui-check"></i> <span>Save</span>';
            saveButton.addEventListener('click', this, false);
            
            var buttonWrapper = document.createElement('DIV');
            buttonWrapper.classList.add('editorButtons');
            
            buttonWrapper.appendChild( cancelButton );
            buttonWrapper.appendChild( saveButton );
            
            this.parentLI.appendChild( buttonWrapper );
            
            builderUI.aceEditors[ theId ] = editor;
            
        };
            
        /*
            cancels the block source code editor
        */
        this.cancelSourceBlock = function() {

            //enable draggable on the LI
            $(this.parentLI.parentNode).sortable('enable');
		
            //delete the errorDrawer
            $(this.parentLI.nextSibling).remove();
        
            //delete the editor
            this.parentLI.querySelector('.aceEditor').remove();
            $(this.frame).fadeIn(500);
                        
            $(this.parentLI.querySelector('.editorButtons')).fadeOut(500, function(){
                $(this).remove();
            });
            
        };
            
        /*
            updates the blocks source code
        */
        this.saveSourceBlock = function() {
            
            //enable draggable on the LI
            $(this.parentLI.parentNode).sortable('enable');
            
            var theId = this.parentLI.querySelector('.aceEditor').getAttribute('id');
            var theContent = builderUI.aceEditors[theId].getValue();
            
            //delete the errorDrawer
            document.getElementById('div_errorDrawer').parentNode.remove();
            
            //delete the editor
            this.parentLI.querySelector('.aceEditor').remove();
            
            //update the frame's content
            this.frameDocument.querySelector( bConfig.pageContainer ).innerHTML = theContent;
            this.frame.style.display = 'block';
            
            //sandboxed?
            if( this.sandbox ) {
                
                var sandboxFrame = document.getElementById( this.sandbox );
                var sandboxFrameDocument = sandboxFrame.contentDocument || sandboxFrame.contentWindow.document;
                
                builderUI.tempFrame = sandboxFrame;
                
                sandboxFrameDocument.querySelector( bConfig.pageContainer ).innerHTML = theContent;
                                
                //do we need to execute a loader function?
                if( this.sandbox_loader !== '' ) {
                    
                    /*
                    var codeToExecute = "sandboxFrame.contentWindow."+this.sandbox_loader+"()";
                    var tmpFunc = new Function(codeToExecute);
                    tmpFunc();
                    */
                    
                }
                
            }
            
            $(this.parentLI.querySelector('.editorButtons')).fadeOut(500, function(){
                $(this).remove();
            });
            
            //adjust height of the frame
            this.heightAdjustment();
            
            //new page added, we've got pending changes
            site.setPendingChanges(true);
            
            //block has changed
            this.status = 'changed';

            publisher.publish('onBlockChange', this, 'change');
            publisher.publish('onBlockLoaded', this);

        };
            
        /*
            clears out the error drawer
        */
        this.clearErrorDrawer = function() {
            
            var ps = this.parentLI.nextSibling.querySelectorAll('p');
                        
            for( var i = 0; i < ps.length; i++ ) {
                ps[i].remove();  
            }
                        
        };
            
        /*
            toggles the visibility of this block's frameCover
        */
        this.toggleCover = function(onOrOff) {
            
            if( onOrOff === 'On' ) {
                
                this.parentLI.querySelector('.frameCover').style.display = 'block';
                
            } else if( onOrOff === 'Off' ) {
             
                this.parentLI.querySelector('.frameCover').style.display = 'none';
                
            }
            
        };
            
        /*
            returns the full source code of the block's frame
        */
        this.getSource = function() {
            
            var source = "<html>";
            source += this.frameDocument.head.outerHTML;
            source += this.frameDocument.body.outerHTML;
            
            return source;
            
        };
            
        /*
            places a dragged/dropped block from the left sidebar onto the canvas
        */
        this.placeOnCanvas = function(ui) {
            
            //frame data, we'll need this before messing with the item's content HTML
            var frameData = {}, attr;
                
            if( ui.item.find('iframe').size() > 0 ) {//iframe thumbnail
                    
                frameData.src = ui.item.find('iframe').attr('src');
                frameData.frames_original_url = ui.item.find('iframe').attr('src');
                frameData.frames_height = ui.item.height();
                    
                //sandboxed block?
                attr = ui.item.find('iframe').attr('sandbox');
                                
                if (typeof attr !== typeof undefined && attr !== false) {
                    this.sandbox = siteBuilderUtils.getRandomArbitrary(10000, 1000000000);
                    this.sandbox_loader = ui.item.find('iframe').attr('data-loaderfunction');
                }
                                        
            } else {//image thumbnail
                    
                frameData.src = ui.item.find('img').attr('data-srcc');
                frameData.frames_original_url = ui.item.find('img').attr('data-srcc');
                frameData.frames_height = ui.item.find('img').attr('data-height');
                                    
                //sandboxed block?
                attr = ui.item.find('img').attr('data-sandbox');
                                
                if (typeof attr !== typeof undefined && attr !== false) {
                    this.sandbox = siteBuilderUtils.getRandomArbitrary(10000, 1000000000);
                    this.sandbox_loader = ui.item.find('img').attr('data-loaderfunction');
                }
                    
            }                
                                
            //create the new block object
            this.frameID = 0;
            this.parentLI = ui.item.get(0);
            this.parentLI.innerHTML = '';
            this.status = 'new';
            this.createFrame(frameData);
            this.parentLI.style.height = this.frameHeight+"px";
            this.createFrameCover();
                
            this.frame.addEventListener('load', this);
                
            //insert the created iframe
            ui.item.append($(this.frame));
                                           
            //add the block to the current page
            site.activePage.blocks.splice(ui.item.index(), 0, this);
                
            //custom event
            ui.item.find('iframe').trigger('canvasupdated');
                                
            //dropped element, so we've got pending changes
            site.setPendingChanges(true);
            
        };

        /*
            injects external JS (defined in config.js) into the block
        */
        this.loadJavascript = function () {

            var i,
                old,
                newScript;

            //remove old ones
            old = this.frameDocument.querySelectorAll('script.builder');

            for ( i = 0; i < old.length; i++ ) old[i].remove();

            //inject
            for ( i = 0; i < bConfig.externalJS.length; i++ ) {
                
                newScript = document.createElement('SCRIPT');
                newScript.classList.add('builder');
                newScript.src = bConfig.externalJS[i];

                this.frameDocument.querySelector('body').appendChild(newScript);
            
            }

        };


        /*
            Checks if this block has external stylesheet
        */
        this.hasExternalCSS = function (src) {

            var externalCss,
                x;

            externalCss = this.frameDocument.querySelectorAll('link[href*="' + src + '"]');

            return externalCss.length !== 0;

        };
        
    }
    
    Block.prototype.handleEvent = function(event) {
        switch (event.type) {
            case "load": 
                this.setFrameDocument();
                this.heightAdjustment();
                this.loadJavascript();
                
                $(this.frameCover).removeClass('fresh', 500);

                publisher.publish('onBlockLoaded', this);

                this.loaded = true;

                builderUI.canvasLoading('off');

                break;
                
            case "click":
                
                var theBlock = this;
                
                //figure out what to do next
                
                if( event.target.classList.contains('deleteBlock') || event.target.parentNode.classList.contains('deleteBlock') ) {//delete this block
                    
                    $(builderUI.modalDeleteBlock).modal('show');                    
                    
                    $(builderUI.modalDeleteBlock).off('click', '#deleteBlockConfirm').on('click', '#deleteBlockConfirm', function(){
                        theBlock.delete(event);
                        $(builderUI.modalDeleteBlock).modal('hide');
                    });
                    
                } else if( event.target.classList.contains('resetBlock') || event.target.parentNode.classList.contains('resetBlock') ) {//reset the block
                    
                    $(builderUI.modalResetBlock).modal('show'); 
                    
                    $(builderUI.modalResetBlock).off('click', '#resetBlockConfirm').on('click', '#resetBlockConfirm', function(){
                        theBlock.reset();
                        $(builderUI.modalResetBlock).modal('hide');
                    });
                       
                } else if( event.target.classList.contains('htmlBlock') || event.target.parentNode.classList.contains('htmlBlock') ) {//source code editor
                    
                    theBlock.source();
                    
                } else if( event.target.classList.contains('editCancelButton') || event.target.parentNode.classList.contains('editCancelButton') ) {//cancel source code editor
                    
                    theBlock.cancelSourceBlock();
                    
                } else if( event.target.classList.contains('editSaveButton') || event.target.parentNode.classList.contains('editSaveButton') ) {//save source code
                    
                    theBlock.saveSourceBlock();
                    
                } else if( event.target.classList.contains('button_clearErrorDrawer') ) {//clear error drawer
                    
                    theBlock.clearErrorDrawer();
                    
                }
                
        }
    };


    /*
        Site object literal
    */
    /*jshint -W003 */
    var site = {
        
        pendingChanges: false,      //pending changes or no?
        pages: {},                  //array containing all pages, including the child frames, loaded from the server on page load
        is_admin: 0,                //0 for non-admin, 1 for admin
        data: {},                   //container for ajax loaded site data
        pagesToDelete: [],          //contains pages to be deleted
                
        sitePages: [],              //this is the only var containing the recent canvas contents
        
        sitePagesReadyForServer: {},     //contains the site data ready to be sent to the server
        
        activePage: {},             //holds a reference to the page currently open on the canvas
        
        pageTitle: document.getElementById('pageTitle'),//holds the page title of the current page on the canvas
        
        divCanvas: document.getElementById('pageList'),//DIV containing all pages on the canvas
        
        pagesMenu: document.getElementById('pages'), //UL containing the pages menu in the sidebar
                
        buttonNewPage: document.getElementById('addPage'),
        liNewPage: document.getElementById('newPageLI'),
        
        inputPageSettingsTitle: document.getElementById('pageData_title'),
        inputPageSettingsMetaDescription: document.getElementById('pageData_metaDescription'),
        inputPageSettingsMetaKeywords: document.getElementById('pageData_metaKeywords'),
        inputPageSettingsIncludes: document.getElementById('pageData_headerIncludes'),
        inputPageSettingsPageCss: document.getElementById('pageData_headerCss'),
        
        buttonSubmitPageSettings: document.getElementById('pageSettingsSubmittButton'),
        
        modalPageSettings: document.getElementById('pageSettingsModal'),
        
        buttonSave: document.getElementById('savePage'),
        
        messageStart: document.getElementById('start'),
        divFrameWrapper: document.getElementById('frameWrapper'),
        
        skeleton: document.getElementById('skeleton'),
		
		autoSaveTimer: {},
        
        init: function() {
                        
            $.getJSON(appUI.siteUrl+"sites/siteData", function(data){
                
                if( data.site !== undefined ) {
                    site.data = data.site;
                }
                if( data.pages !== undefined ) {
                    site.pages = data.pages;
                }
                
                site.is_admin = data.is_admin;
                
				if( $('#pageList').size() > 0 ) {
                	builderUI.populateCanvas();
				}

                if( data.site.viewmode ) {
                    publisher.publish('onSetMode', data.site.viewmode);
                }
                
                //fire custom event
                $('body').trigger('siteDataLoaded');
                
            });
            
            $(this.buttonNewPage).on('click', site.newPage);
            $(this.modalPageSettings).on('show.bs.modal', site.loadPageSettings);
            $(this.buttonSubmitPageSettings).on('click', site.updatePageSettings);
            $(this.buttonSave).on('click', function(){site.save(true);});
            
            //auto save time 
            this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);

            publisher.subscribe('onBlockChange', function (block, type) {

                if ( block.global ) {

                    for ( var i = 0; i < site.sitePages.length; i++ ) {

                        for ( var y = 0; y < site.sitePages[i].blocks.length; y ++ ) {

                            if ( site.sitePages[i].blocks[y] !== block && site.sitePages[i].blocks[y].originalUrl === block.originalUrl && site.sitePages[i].blocks[y].global ) {

                                if ( type === 'change' ) {

                                    site.sitePages[i].blocks[y].frameDocument.body = block.frameDocument.body.cloneNode(true);

                                    publisher.publish('onBlockLoaded', site.sitePages[i].blocks[y]);

                                } else if ( type === 'reload' ) {

                                    site.sitePages[i].blocks[y].reset(false);

                                }

                            }

                        }

                    }

                }

            });
                            
        },
        
        autoSave: function(){
                                    
            if(site.pendingChanges) {
                site.save(false);
            }
			
			window.clearInterval(this.autoSaveTimer);
            this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);
        
        },
                
        setPendingChanges: function(value) {
                        
            this.pendingChanges = value;
            
            if( value === true ) {
				
				//reset timer
				window.clearInterval(this.autoSaveTimer);
            	this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);
                
                $('#savePage .bLabel').text("Save now (!)");
                
                if( site.activePage.status !== 'new' ) {
                
                    site.activePage.status = 'changed';
                    
                }
			
            } else {
	
                $('#savePage .bLabel').text("Nothing to save");
				
                site.updatePageStatus('');

            }
            
        },
                   
        save: function(showConfirmModal) {

            publisher.publish('onBeforeSave');
                                    
            //fire custom event
            $('body').trigger('beforeSave');

            //disable button
            $("a#savePage").addClass('disabled');
	
            //remove old alerts
            $('#errorModal .modal-body > *, #successModal .modal-body > *').each(function(){
                $(this).remove();
            });
	
            site.prepForSave(false);
            
            var serverData = {};
            serverData.pages = this.sitePagesReadyForServer;
            if( this.pagesToDelete.length > 0 ) {
                serverData.toDelete = this.pagesToDelete;
            }

            serverData.siteData = this.data;

            //store current responsive mode as well
            serverData.siteData.responsiveMode = builderUI.currentResponsiveMode;

            $.ajax({
                url: appUI.siteUrl+"sites/save",
                type: "POST",
                dataType: "json",
                data: serverData,
            }).done(function(res){
	
                //enable button
                $("a#savePage").removeClass('disabled');
	
                if( res.responseCode === 0 ) {
			
                    if( showConfirmModal ) {
				
                        $('#errorModal .modal-body').append( $(res.responseHTML) );
                        $('#errorModal').modal('show');
				
                    }
		
                } else if( res.responseCode === 1 ) {
		
                    if( showConfirmModal ) {
		
                        $('#successModal .modal-body').append( $(res.responseHTML) );
                        $('#successModal').modal('show');
				
                    }
			
			
                    //no more pending changes
                    site.setPendingChanges(false);
			

                    //update revisions?
                    $('body').trigger('changePage');
                
                }
            });
    
        },
        
        /*
            preps the site data before sending it to the server
        */
        prepForSave: function(template) {
            
            this.sitePagesReadyForServer = {};
            
            if( template ) {//saving template, only the activePage is needed
                
                this.sitePagesReadyForServer[this.activePage.name] = this.activePage.prepForSave();
                
                this.activePage.fullPage();
                
            } else {//regular save
            
                //find the pages which need to be send to the server
                for( var i = 0; i < this.sitePages.length; i++ ) {
                                
                    if( this.sitePages[i].status !== '' ) {
                                    
                        this.sitePagesReadyForServer[this.sitePages[i].name] = this.sitePages[i].prepForSave();
                    
                    }
                
                }
            
            }
                                                                            
        },
        
        
        /*
            sets a page as the active one
        */
        setActive: function(page) {
            
            //reference to the active page
            this.activePage = page;
            
            //hide other pages
            for(var i in this.sitePages) {
                this.sitePages[i].parentUL.style.display = 'none';   
            }
            
            //display active one
            this.activePage.parentUL.style.display = 'block';
            
        },
        
        
        /*
            de-active all page menu items
        */
        deActivateAll: function() {
            
            var pages = this.pagesMenu.querySelectorAll('li');
            
            for( var i = 0; i < pages.length; i++ ) {
                pages[i].classList.remove('active');
            }
            
        },
        
        
        /*
            adds a new page to the site
        */
        newPage: function() {
            
            site.deActivateAll();
            
            //create the new page instance
            
            var pageData = [];
            var temp = {
                pages_id: 0
            };
            pageData[0] = temp;
            
            var newPageName = 'page'+(site.sitePages.length+1);
            
            var newPage = new Page(newPageName, pageData, site.sitePages.length+1);
            
            newPage.status = 'new';
            
            newPage.selectPage();
            newPage.editPageName();
        
            newPage.isEmpty();
                        
            site.setPendingChanges(true);
                                    
        },
        
        
        /*
            checks if the name of a page is allowed
        */
        checkPageName: function(pageName) {
            
            //make sure the name is unique
            for( var i in this.sitePages ) {
                
                if( this.sitePages[i].name === pageName && this.activePage !== this.sitePages[i] ) {
                    this.pageNameError = "The page name must be unique.";
                    return false;
                }   
                
            }
            
            return true;
            
        },
        
        
        /*
            removes unallowed characters from the page name
        */
        prepPageName: function(pageName) {
            
            pageName = pageName.replace(' ', '');
            pageName = pageName.replace(/[?*!.|&#;$%@"<>()+,]/g, "");
            
            return pageName;
            
        },
        
        
        /*
            save page settings for the current page
        */
        updatePageSettings: function() {
            
            site.activePage.pageSettings.title = site.inputPageSettingsTitle.value;
            site.activePage.pageSettings.meta_description = site.inputPageSettingsMetaDescription.value;
            site.activePage.pageSettings.meta_keywords = site.inputPageSettingsMetaKeywords.value;
            site.activePage.pageSettings.header_includes = site.inputPageSettingsIncludes.value;
            site.activePage.pageSettings.page_css = site.inputPageSettingsPageCss.value;
                        
            site.setPendingChanges(true);
            
            $(site.modalPageSettings).modal('hide');
            
        },
        
        
        /*
            update page statuses
        */
        updatePageStatus: function(status) {
            
            for( var i in this.sitePages ) {
                this.sitePages[i].status = status;   
            }
            
        },


        /*
            Checks all the blocks in this site have finished loading
        */
        loaded: function () {

            var i;

            for ( i = 0; i < this.sitePages.length; i++ ) {

                if ( !this.sitePages[i].loaded() ) return false;

            }

            return true;

        },


        /*
            Make every block have an overlay during dragging to prevent mouse event issues
        */
        moveMode: function (value) {

            var i;

            for ( i = 0; i < this.activePage.blocks.length; i++ ) {

                if ( value === 'on' ) this.activePage.blocks[i].frameCover.classList.add('move');
                else if ( value === 'off' ) this.activePage.blocks[i].frameCover.classList.remove('move');

            }

        },

        /*
            Get form from AEM
        */
        quick_load_form: function () {
            
            if($('#pageList iframe').contents().find('#user_form_div_remove').length){
                jQuery('#pageList iframe').contents().find('#user_form_div_remove').remove();
            }

            if($('#pageList iframe').contents().find('#user_form_div').length){
                jQuery.ajax({
                    type: "post",
                    url: "/sites/fetchForm",
                    data: {
                        'formID':form_id
                    },
                    dataType: 'json',
                    success:function(result) {
                        if(result.type === 'success') {
                            var jheight = jQuery('#pageList iframe').contents().height();
                            jheight += 100;
                            jQuery('#pageList iframe').contents().find('#user_form_div').html(result.html);
                            jQuery('#pageList iframe').contents().find('#user_form_div_remove').remove();
                            jQuery('#pageList iframe', window.parent.document).height(jheight+'px');
                            return true;
                        } else {
                            return false;
                        }
                    },
                    error: function(errorThrown){
                        console.log(errorThrown);
                    }
                });
            }
        }
    
    };

    builderUI.init(); site.init();

    
    //**** EXPORTS
    module.exports.site = site;
    module.exports.builderUI = builderUI;

}());
},{"../vendor/publisher":10,"./config.js":5,"./ui.js":8,"./utils.js":9}],4:[function(require,module,exports){
(function () {
    "use strict";

    var siteBuilder = require('./builder.js');

    /*
        constructor function for Element
    */
    module.exports.Element = function (el) {
                
        this.element = el;
        this.sandbox = false;
        this.parentFrame = {};
        this.parentBlock = {};//reference to the parent block element
        this.editableAttributes = [];
        
        //make current element active/open (being worked on)
        this.setOpen = function() {
            
            $(this.element).off('mouseenter mouseleave click');
                                            
            $(this.element).css({'outline': '2px solid rgba(233,94,94,0.5)', 'outline-offset':'-2px', 'cursor': 'pointer'});
            
        };
        
        //sets up hover and click events, making the element active on the canvas
        this.activate = function() {
            
            var element = this;

            //data attributes for color
            if ( this.element.tagName === 'A' ) $(this.element).data('color', getComputedStyle(this.element).color);
            
            $(this.element).css({'outline': 'none', 'cursor': ''});
                                    
            $(this.element).on('mouseenter', function(e) {

                e.stopPropagation();
                                    
                $(this).css({'outline': '2px solid rgba(233,94,94,0.5)', 'outline-offset': '-2px', 'cursor': 'pointer'});
            
            }).on('mouseleave', function() {
                
                $(this).css({'outline': '', 'cursor': '', 'outline-offset': ''});
            
            }).on('click', function(e) {
                                                                
                e.preventDefault();
                e.stopPropagation();
                
                element.clickHandler(this);
            
            });
            
        };
        
        this.deactivate = function() {
            
            $(this.element).off('mouseenter mouseleave click');
            $(this.element).css({'outline': 'none', 'cursor': 'inherit'});

        };
        
        //removes the elements outline
        this.removeOutline = function() {
            
            $(this.element).css({'outline': 'none', 'cursor': 'inherit'});
            
        };
        
        //sets the parent iframe
        this.setParentFrame = function() {
            
            var doc = this.element.ownerDocument;
            var w = doc.defaultView || doc.parentWindow;
            var frames = w.parent.document.getElementsByTagName('iframe');
            
            for (var i= frames.length; i-->0;) {
                
                var frame= frames[i];
                
                try {
                    var d= frame.contentDocument || frame.contentWindow.document;
                    if (d===doc)
                        this.parentFrame = frame;
                } catch(e) {}
            }
            
        };
        
        //sets this element's parent block reference
        this.setParentBlock = function() {
            
            //loop through all the blocks on the canvas
            for( var i = 0; i < siteBuilder.site.sitePages.length; i++ ) {
                                
                for( var x = 0; x < siteBuilder.site.sitePages[i].blocks.length; x++ ) {
                                        
                    //if the block's frame matches this element's parent frame
                    if( siteBuilder.site.sitePages[i].blocks[x].frame === this.parentFrame ) {
                        //create a reference to that block and store it in this.parentBlock
                        this.parentBlock = siteBuilder.site.sitePages[i].blocks[x];
                    }
                
                }
                
            }
                        
        };
        
        
        this.setParentFrame();
        
        /*
            is this block sandboxed?
        */
        
        if( this.parentFrame.getAttribute('data-sandbox') ) {
            this.sandbox = this.parentFrame.getAttribute('data-sandbox');   
        }
                
    };

}());
},{"./builder.js":3}],5:[function(require,module,exports){
(function () {
	"use strict";
        
    module.exports.pageContainer = "#page";

    module.exports.bodyPaddingClass = "bPadding";
    
    module.exports.editableItems = {
        'span.fa': ['color', 'font-size'],
        '.bg.bg1': ['background-color'],
        'nav a': ['color', 'font-weight', 'text-transform'],
        'img': ['border-top-left-radius', 'border-top-right-radius', 'border-bottom-left-radius', 'border-bottom-right-radius', 'border-color', 'border-style', 'border-width'],
        'hr.dashed': ['border-color', 'border-width'],
        '.divider > span': ['color', 'font-size'],
        'hr.shadowDown': ['margin-top', 'margin-bottom'],
        '.footer a': ['color'],
        '.social a': ['color'],
        '.bg.bg1, .bg.bg2, .header10, .header11': ['background-image', 'background-color'],
        '.frameCover': [],
        '.editContent': ['content', 'color', 'font-size', 'background-color', 'font-family'],
        'a.btn, button.btn': ['border-radius', 'font-size', 'background-color'],
        '#pricing_table2 .pricing2 .bottom li': ['content']
    };
    
    module.exports.editableItemOptions = {
        'nav a : font-weight': ['400', '700'],
        'a.btn : border-radius': ['0px', '4px', '10px'],
        'img : border-style': ['none', 'dotted', 'dashed', 'solid'],
        'img : border-width': ['1px', '2px', '3px', '4px'],
        'h1, h2, h3, h4, h5, p : font-family': ['default', 'Lato', 'Helvetica', 'Arial', 'Times New Roman'],
        'h2 : font-family': ['default', 'Lato', 'Helvetica', 'Arial', 'Times New Roman'],
        'h3 : font-family': ['default', 'Lato', 'Helvetica', 'Arial', 'Times New Roman'],
        'p : font-family': ['default', 'Lato', 'Helvetica', 'Arial', 'Times New Roman']
    };

    module.exports.responsiveModes = {
        desktop: '97%',
        mobile: '480px',
        tablet: '1024px'
    };

    module.exports.editableContent = ['.editContent', '.navbar a', 'button', 'a.btn', '.footer a:not(.fa)', '.tableWrapper', 'h1', 'h2'];

    module.exports.autoSaveTimeout = 300000;
    
    module.exports.sourceCodeEditSyntaxDelay = 10000;

    module.exports.mediumCssUrls = [
        '//cdn.jsdelivr.net/medium-editor/latest/css/medium-editor.min.css',
        '/css/medium-bootstrap.css'
    ];
    module.exports.mediumButtons = ['bold', 'italic', 'underline', 'anchor', 'orderedlist', 'unorderedlist', 'h1', 'h2', 'h3', 'h4', 'removeFormat'];

    module.exports.externalJS = [
        'js/builder_in_block.js'
    ];
                    
}());
},{}],6:[function(require,module,exports){
(function (){
	"use strict";

    var bConfig = require('./config.js');
    var siteBuilder = require('./builder.js');
    var editor = require('./styleeditor.js').styleeditor;
    var appUI = require('./ui.js').appUI;

    var imageLibrary = {
        
        imageModal: document.getElementById('imageModal'),
        inputImageUpload: document.getElementById('imageFile'),
        buttonUploadImage: document.getElementById('uploadImageButton'),
        imageLibraryLinks: document.querySelectorAll('.images > .image .buttons .btn-primary, .images .imageWrap > a'),//used in the library, outside the builder UI
        myImages: document.getElementById('myImages'),//used in the image library, outside the builder UI
    
        init: function(){
            
            $(this.imageModal).on('show.bs.modal', this.imageLibrary);
            $(this.inputImageUpload).on('change', this.imageInputChange);
            $(this.buttonUploadImage).on('click', this.uploadImage);
            $(this.imageLibraryLinks).on('click', this.imageInModal);
            $(this.myImages).on('click', '.buttons .btn-danger', this.deleteImage);
            
        },
        
        
        /*
            image library modal
        */
        imageLibrary: function() {
                        			
            $('#imageModal').off('click', '.image button.useImage');
			
            $('#imageModal').on('click', '.image button.useImage', function(){
                
                //update live image
                $(editor.activeElement.element).attr('src', $(this).attr('data-url'));

                //update image URL field
                $('input#imageURL').val( $(this).attr('data-url') );
				
                //hide modal
                $('#imageModal').modal('hide');
				
                //height adjustment of the iframe heightAdjustment
				editor.activeElement.parentBlock.heightAdjustment();							
				
                //we've got pending changes
                siteBuilder.site.setPendingChanges(true);
			
                $(this).unbind('click');
            
            });
            
        },
        
        
        /*
            image upload input chaneg event handler
        */
        imageInputChange: function() {
            
            if( $(this).val() === '' ) {
                //no file, disable submit button
                $('button#uploadImageButton').addClass('disabled');
            } else {
                //got a file, enable button
                $('button#uploadImageButton').removeClass('disabled');
            }
            
        },
        
        
        /*
            upload an image to the image library
        */
        uploadImage: function() {
            
            if( $('input#imageFile').val() !== '' ) {
                
                //remove old alerts
                $('#imageModal .modal-alerts > *').remove();
                
                //disable button
                $('button#uploadImageButton').addClass('disable');

                //show loader
                $('#imageModal .loader').fadeIn(500);
                
                var form = $('form#imageUploadForm');
                var formdata = false;

                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                
                var formAction = form.attr('action');
                
                $.ajax({
                    url : formAction,
                    data : formdata ? formdata : form.serialize(),
                    cache : false,
                    contentType : false,
                    processData : false,
                    dataType: "json",
                    type : 'POST'
                }).done(function(ret){
                    
                    //enable button
                    $('button#uploadImageButton').addClass('disable');
                    
                    //hide loader
                    $('#imageModal .loader').fadeOut(500);
                    
                    if( ret.responseCode === 0 ) {//error
                        
                        $('#imageModal .modal-alerts').append( $(ret.responseHTML) );
			
                    } else if( ret.responseCode === 1 ) {//success
                        
                        //append my image
                        $('#myImagesTab > *').remove();
                        $('#myImagesTab').append( $(ret.myImages) );
                        $('#imageModal .modal-alerts').append( $(ret.responseHTML) );
                        
                        setTimeout(function(){$('#imageModal .modal-alerts > *').fadeOut(500);}, 3000);
                    
                    }
                
                });
            
            } else {

                alert('No image selected');
            
            }
            
        },
        
        
        /*
            displays image in modal
        */
        imageInModal: function(e) {
            
            e.preventDefault();
    		
    		var theSrc = $(this).closest('.image').find('img').attr('src');
    		
    		$('img#thePic').attr('src', theSrc);
    		
    		$('#viewPic').modal('show');
            
        },
        
        
        /*
            deletes an image from the library
        */
        deleteImage: function(e) {
            
            e.preventDefault();
    		
    		var toDel = $(this).closest('.image');
    		var theURL = $(this).attr('data-img');
    		
    		$('#deleteImageModal').modal('show');
    		
    		$('button#deleteImageButton').click(function(){
    		
    			$(this).addClass('disabled');
    			
    			var theButton = $(this);
    		
    			$.ajax({
                    url: appUI.siteUrl+"assets/delImage",
    				data: {file: theURL},
    				type: 'post'
    			}).done(function(){
    			
    				theButton.removeClass('disabled');
    				
    				$('#deleteImageModal').modal('hide');
    				
    				toDel.fadeOut(800, function(){
    									
    					$(this).remove();
    										
    				});
    			
    			});
    		
    		
    		});
            
        }
        
    };
    
    imageLibrary.init();

}());
},{"./builder.js":3,"./config.js":5,"./styleeditor.js":7,"./ui.js":8}],7:[function(require,module,exports){
(function (){
	"use strict";

	var canvasElement = require('./canvasElement.js').Element;
	var bConfig = require('./config.js');
	var siteBuilder = require('./builder.js');
    var publisher = require('../vendor/publisher');

    var styleeditor = {

        buttonSaveChanges: document.getElementById('saveStyling'),
        activeElement: {}, //holds the element currenty being edited
        allStyleItemsOnCanvas: [],
        _oldIcon: [],
        styleEditor: document.getElementById('styleEditor'),
        formStyle: document.getElementById('stylingForm'),
        buttonRemoveElement: document.getElementById('deleteElementConfirm'),
        buttonCloneElement: document.getElementById('cloneElementButton'),
        buttonResetElement: document.getElementById('resetStyleButton'),
        selectLinksInernal: document.getElementById('internalLinksDropdown'),
        selectLinksPages: document.getElementById('pageLinksDropdown'),
        videoInputYoutube: document.getElementById('youtubeID'),
        videoInputVimeo: document.getElementById('vimeoID'),
        inputCustomLink: document.getElementById('internalLinksCustom'),
        linkImage: null,
        linkIcon: null,
        inputLinkText: document.getElementById('linkText'),
        selectIcons: document.getElementById('icons'),
        buttonDetailsAppliedHide: document.getElementById('detailsAppliedMessageHide'),
        buttonCloseStyleEditor: document.querySelector('#styleEditor > a.close'),
        ulPageList: document.getElementById('pageList'),
        responsiveToggle: document.getElementById('responsiveToggle'),
        theScreen: document.getElementById('screen'),

        init: function() {

            publisher.subscribe('closeStyleEditor', function () {
                styleeditor.closeStyleEditor();
            });

            publisher.subscribe('onBlockLoaded', function (block) {
                styleeditor.setupCanvasElements(block);
            });

            publisher.subscribe('onSetMode', function (mode) {
                styleeditor.responsiveModeChange(mode);
            });

            //events
            $(this.buttonSaveChanges).on('click', this.updateStyling);
            $(this.formStyle).on('focus', 'input', this.animateStyleInputIn).on('blur', 'input', this.animateStyleInputOut);
            $(this.buttonRemoveElement).on('click', this.deleteElement);
            $(this.buttonCloneElement).on('click', this.cloneElement);
            $(this.buttonResetElement).on('click', this.resetElement);
            $(this.videoInputYoutube).on('focus', function(){ $(styleeditor.videoInputVimeo).val(''); });
            $(this.videoInputVimeo).on('focus', function(){ $(styleeditor.videoInputYoutube).val(''); });
            $(this.inputCustomLink).on('focus', this.resetSelectAllLinks);
            $(this.buttonDetailsAppliedHide).on('click', function(){$(this).parent().fadeOut(500);});
            $(this.buttonCloseStyleEditor).on('click', this.closeStyleEditor);
            $(this.inputCustomLink).on('focus', this.inputCustomLinkFocus).on('blur', this.inputCustomLinkBlur);
            $(document).on('modeContent modeBlocks', 'body', this.deActivateMode);

            //chosen font-awesome dropdown
            $(this.selectIcons).chosen({'search_contains': true});

            //check if formData is supported
            if (!window.FormData){
                this.hideFileUploads();
            }

            //listen for the beforeSave event
            $('body').on('beforeSave', this.closeStyleEditor);

            //responsive toggle
            $(this.responsiveToggle).on('click', 'a', this.toggleResponsiveClick);

            //set the default responsive mode
            siteBuilder.builderUI.currentResponsiveMode = Object.keys(bConfig.responsiveModes)[0];

        },

        /*
            Event handler for responsive mode links
        */
        toggleResponsiveClick: function (e) {

            e.preventDefault();
            
            styleeditor.responsiveModeChange(this.getAttribute('data-responsive'));

        },


        /*
            Toggles the responsive mode
        */
        responsiveModeChange: function (mode) {

            var links,
                i;

            //UI stuff
            links = styleeditor.responsiveToggle.querySelectorAll('li');

            for ( i = 0; i < links.length; i++ ) links[i].classList.remove('active');

            document.querySelector('a[data-responsive="' + mode + '"]').parentNode.classList.add('active');


            for ( var key in bConfig.responsiveModes ) {

                if ( bConfig.responsiveModes.hasOwnProperty(key) ) this.theScreen.classList.remove(key);

            }

            if ( bConfig.responsiveModes[mode] ) {

                this.theScreen.classList.add(mode);
                $(this.theScreen).animate({width: bConfig.responsiveModes[mode]}, 650, function () {
                    //height adjustment
                    siteBuilder.site.activePage.heightAdjustment();
                });

            }

            siteBuilder.builderUI.currentResponsiveMode = mode;

        },


        /*
            Activates style editor mode
        */
        setupCanvasElements: function(block) {

            if ( block === undefined ) return false;

            var i;

            //create an object for every editable element on the canvas and setup it's events

            for( var key in bConfig.editableItems ) {

                $(block.frame).contents().find( bConfig.pageContainer + ' '+ key ).each(function () {

                    styleeditor.setupCanvasElementsOnElement(this, key);

                });

            }

        },


        /*
            Sets up canvas elements on element
        */
        setupCanvasElementsOnElement: function (element, key) {

            //Element object extention
            canvasElement.prototype.clickHandler = function(el) {
                styleeditor.styleClick(this);
            };

            var newElement = new canvasElement(element);

            newElement.editableAttributes = bConfig.editableItems[key];
            newElement.setParentBlock();
            newElement.activate();

            styleeditor.allStyleItemsOnCanvas.push( newElement );

            if ( typeof key !== undefined ) $(element).attr('data-selector', key);

        },


        /*
            Event handler for when the style editor is envoked on an item
        */
        styleClick: function(element) {

            //if we have an active element, make it unactive
            if( Object.keys(this.activeElement).length !== 0) {
                this.activeElement.activate();
            }

            //set the active element
            this.activeElement = element;

            //unbind hover and click events and make this item active
            this.activeElement.setOpen();

            var theSelector = $(this.activeElement.element).attr('data-selector');

            $('#editingElement').text( theSelector );

            //activate first tab
            $('#detailTabs a:first').click();

            //hide all by default
            $('ul#detailTabs li:gt(0)').hide();

            //content editor?
            for( var item in bConfig.editableItems ) {

                if( bConfig.editableItems.hasOwnProperty(item) && item === theSelector ) {

                    if ( bConfig.editableItems[item].indexOf('content') !== -1 ) {

                        //edit content
                        publisher.publish('onClickContent', element.element);

                    }

                }

            }

            //what are we dealing with?
            if( $(this.activeElement.element).prop('tagName') === 'A' || $(this.activeElement.element).parent().prop('tagName') === 'A' ) {

                this.editLink(this.activeElement.element);

            }

			if( $(this.activeElement.element).prop('tagName') === 'IMG' ){

                this.editImage(this.activeElement.element);

            }

			if( $(this.activeElement.element).attr('data-type') === 'video' ) {

                this.editVideo(this.activeElement.element);

            }

			if( $(this.activeElement.element).hasClass('fa') ) {

                this.editIcon(this.activeElement.element);

            }

            //load the attributes
            this.buildeStyleElements(theSelector);

            //open side panel
            this.toggleSidePanel('open');

            return false;

        },


        /*
            dynamically generates the form fields for editing an elements style attributes
        */
        buildeStyleElements: function(theSelector) {

            //delete the old ones first
            $('#styleElements > *:not(#styleElTemplate)').each(function(){

                $(this).remove();

            });

            for( var x=0; x<bConfig.editableItems[theSelector].length; x++ ) {

                //create style elements
                var newStyleEl = $('#styleElTemplate').clone();
                newStyleEl.attr('id', '');
                newStyleEl.find('.control-label').text( bConfig.editableItems[theSelector][x]+":" );

                if( theSelector + " : " + bConfig.editableItems[theSelector][x] in bConfig.editableItemOptions) {//we've got a dropdown instead of open text input

                    newStyleEl.find('input').remove();

                    var newDropDown = $('<select class="form-control select select-primary btn-block select-sm"></select>');
                    newDropDown.attr('name', bConfig.editableItems[theSelector][x]);


                    for( var z=0; z<bConfig.editableItemOptions[ theSelector+" : "+bConfig.editableItems[theSelector][x] ].length; z++ ) {

                        var newOption = $('<option value="'+bConfig.editableItemOptions[theSelector+" : "+bConfig.editableItems[theSelector][x]][z]+'">'+bConfig.editableItemOptions[theSelector+" : "+bConfig.editableItems[theSelector][x]][z]+'</option>');


                        if( bConfig.editableItemOptions[theSelector+" : "+bConfig.editableItems[theSelector][x]][z] === $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) ) {
                            //current value, marked as selected
                            newOption.attr('selected', 'true');

                        }

                        newDropDown.append( newOption );

                    }

                    newStyleEl.append( newDropDown );
                    newDropDown.select2();

                } else {

                    newStyleEl.find('input').val( $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) ).attr('name', bConfig.editableItems[theSelector][x]);

                    if( bConfig.editableItems[theSelector][x] === 'background-image' ) {

                        newStyleEl.find('input').bind('focus', function(){

                            var theInput = $(this);

                            $('#imageModal').modal('show');
                            $('#imageModal .image button.useImage').unbind('click');
                            $('#imageModal').on('click', '.image button.useImage', function(){

                                $(styleeditor.activeElement.element).css('background-image',  'url("'+$(this).attr('data-url')+'")');

                                //update live image
                                theInput.val( 'url("'+$(this).attr('data-url')+'")' );

                                //hide modal
                                $('#imageModal').modal('hide');

                                //we've got pending changes
                                siteBuilder.site.setPendingChanges(true);

                            });

                        });

                    } else if( bConfig.editableItems[theSelector][x].indexOf("color") > -1 ) {

                        if( $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) !== 'transparent' && $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) !== 'none' && $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) !== '' ) {

                            newStyleEl.val( $(styleeditor.activeElement.element).css( bConfig.editableItems[theSelector][x] ) );

                        }

                        newStyleEl.find('input').spectrum({
                            preferredFormat: "hex",
                            showPalette: true,
                            allowEmpty: true,
                            showInput: true,
                            palette: [
                                ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                                ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                                ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                                ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                                ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
                            ]
                        });

                    }

                }

                newStyleEl.css('display', 'block');

                $('#styleElements').append( newStyleEl );

                $('#styleEditor form#stylingForm').height('auto');

            }

        },


        /*
            Applies updated styling to the canvas
        */
        updateStyling: function() {

            var elementID,
                length;

            $('#styleEditor #tab1 .form-group:not(#styleElTemplate) input, #styleEditor #tab1 .form-group:not(#styleElTemplate) select').each(function(){

				if( $(this).attr('name') !== undefined ) {

                	$(styleeditor.activeElement.element).css( $(this).attr('name'),  $(this).val());

				}

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).css( $(this).attr('name'),  $(this).val() );

                }

                /* END SANDBOX */

            });

            //links
            if( $(styleeditor.activeElement.element).prop('tagName') === 'A' ) {

                //change the href prop?
                styleeditor.activeElement.element.href = document.getElementById('internalLinksCustom').value;

                length = styleeditor.activeElement.element.childNodes.length;
                
                //does the link contain an image?
                if( styleeditor.linkImage ) styleeditor.activeElement.element.childNodes[length-1].nodeValue = document.getElementById('linkText').value;
                else if ( styleeditor.linkIcon ) styleeditor.activeElement.element.childNodes[length-1].nodeValue = document.getElementById('linkText').value;
                else styleeditor.activeElement.element.innerText = document.getElementById('linkText').value;

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).attr('href', $('input#internalLinksCustom').val());


                }

                /* END SANDBOX */

            }

            if( $(styleeditor.activeElement.element).parent().prop('tagName') === 'A' ) {

                //change the href prop?
                styleeditor.activeElement.element.parentNode.href = document.getElementById('internalLinksCustom').value;

                length = styleeditor.activeElement.element.childNodes.length;
                

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).parent().attr('href', $('input#internalLinksCustom').val());

                }

                /* END SANDBOX */

            }

            //icons
            if( $(styleeditor.activeElement.element).hasClass('fa') ) {

                //out with the old, in with the new :)
                //get icon class name, starting with fa-
                var get = $.grep(styleeditor.activeElement.element.className.split(" "), function(v, i){

                    return v.indexOf('fa-') === 0;

                }).join();

                //if the icons is being changed, save the old one so we can reset it if needed

                if( get !== $('select#icons').val() ) {

                    $(styleeditor.activeElement.element).uniqueId();
                    styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] = get;

                }

                $(styleeditor.activeElement.element).removeClass( get ).addClass( $('select#icons').val() );


                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');
                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).removeClass( get ).addClass( $('select#icons').val() );

                }

                /* END SANDBOX */

            }

            //video URL
            if( $(styleeditor.activeElement.element).attr('data-type') === 'video' ) {

                if( $('input#youtubeID').val() !== '' ) {

                    $(styleeditor.activeElement.element).prev().attr('src', "//www.youtube.com/embed/"+$('#video_Tab input#youtubeID').val());

                } else if( $('input#vimeoID').val() !== '' ) {

                    $(styleeditor.activeElement.element).prev().attr('src', "//player.vimeo.com/video/"+$('#video_Tab input#vimeoID').val()+"?title=0&amp;byline=0&amp;portrait=0");

                }

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    if( $('input#youtubeID').val() !== '' ) {

                        $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).prev().attr('src', "//www.youtube.com/embed/"+$('#video_Tab input#youtubeID').val());

                    } else if( $('input#vimeoID').val() !== '' ) {

                        $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).prev().attr('src', "//player.vimeo.com/video/"+$('#video_Tab input#vimeoID').val()+"?title=0&amp;byline=0&amp;portrait=0");

                    }

                }

                /* END SANDBOX */

            }

            $('#detailsAppliedMessage').fadeIn(600, function(){

                setTimeout(function(){ $('#detailsAppliedMessage').fadeOut(1000); }, 3000);

            });

            //adjust frame height
            styleeditor.activeElement.parentBlock.heightAdjustment();


            //we've got pending changes
            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        /*
            on focus, we'll make the input fields wider
        */
        animateStyleInputIn: function() {

            $(this).css('position', 'absolute');
            $(this).css('right', '0px');
            $(this).animate({'width': '100%'}, 500);
            $(this).focus(function(){
                this.select();
            });

        },


        /*
            on blur, we'll revert the input fields to their original size
        */
        animateStyleInputOut: function() {

            $(this).animate({'width': '42%'}, 500, function(){
                $(this).css('position', 'relative');
                $(this).css('right', 'auto');
            });

        },


        /*
            builds the dropdown with #blocks on this page
        */
        buildBlocksDropdown: function (currentVal) {

            $(styleeditor.selectLinksInernal).select2('destroy');

            if( typeof currentVal === 'undefined' ) currentVal = null;

            var x,
                newOption;

            styleeditor.selectLinksInernal.innerHTML = '';

            newOption = document.createElement('OPTION');
            newOption.innerText = "Choose a block";
            newOption.setAttribute('value', '#');
            styleeditor.selectLinksInernal.appendChild(newOption);

            for ( x = 0; x < siteBuilder.site.activePage.blocks.length; x++ ) {

                var frameDoc = siteBuilder.site.activePage.blocks[x].frameDocument;
                var pageContainer  = frameDoc.querySelector(bConfig.pageContainer);
                var theID = pageContainer.children[0].id;

                newOption = document.createElement('OPTION');
                newOption.innerText = '#' + theID;
                newOption.setAttribute('value', '#' + theID);
                if( currentVal === '#' + theID ) newOption.setAttribute('selected', true);

                styleeditor.selectLinksInernal.appendChild(newOption);

            }

            $(styleeditor.selectLinksInernal).select2();
            $(styleeditor.selectLinksInernal).trigger('change');

            $(styleeditor.selectLinksInernal).off('change').on('change', function () {
                styleeditor.inputCustomLink.value = this.value;
                styleeditor.resetPageDropdown();
            });

        },


        /*
            blur event handler for the custom link input
        */
        inputCustomLinkBlur: function (e) {

            var value = e.target.value,
                x;

            //pages match?
            for ( x = 0; x < styleeditor.selectLinksPages.querySelectorAll('option').length; x++ ) {

                if ( value === styleeditor.selectLinksPages.querySelectorAll('option')[x].value ) {

                    styleeditor.selectLinksPages.selectedIndex = x;
                    $(styleeditor.selectLinksPages).trigger('change').select2();

                }

            }

            //blocks match?
            for ( x = 0; styleeditor.selectLinksInernal.querySelectorAll('option').length; x++ ) {

                if ( value === styleeditor.selectLinksInernal.querySelectorAll('option')[x].value ) {

                    styleeditor.selectLinksInernal.selectedIndex = x;
                    $(styleeditor.selectLinksInernal).trigger('change').select2();

                }

            }

        },


        /*
            focus event handler for the custom link input
        */
        inputCustomLinkFocus: function () {

            styleeditor.resetPageDropdown();
            styleeditor.resetBlockDropdown();

        },


        /*
            builds the dropdown with pages to link to
        */
        buildPagesDropdown: function (currentVal) {

            $(styleeditor.selectLinksPages).select2('destroy');

            if( typeof currentVal === 'undefined' ) currentVal = null;

            var x,
                newOption;

            styleeditor.selectLinksPages.innerHTML = '';

            newOption = document.createElement('OPTION');
            newOption.innerText = "Choose a page";
            newOption.setAttribute('value', '#');
            styleeditor.selectLinksPages.appendChild(newOption);

            for( x = 0; x < siteBuilder.site.sitePages.length; x++ ) {

                newOption = document.createElement('OPTION');
                newOption.innerText = siteBuilder.site.sitePages[x].name;
                newOption.setAttribute('value', siteBuilder.site.sitePages[x].name + '.html');
                if( currentVal === siteBuilder.site.sitePages[x].name + '.html') newOption.setAttribute('selected', true);

                styleeditor.selectLinksPages.appendChild(newOption);

            }

            $(styleeditor.selectLinksPages).select2();
            $(styleeditor.selectLinksPages).trigger('change');

            $(styleeditor.selectLinksPages).off('change').on('change', function () {
                styleeditor.inputCustomLink.value = this.value;
                styleeditor.resetBlockDropdown();
            });

        },


        /*
            reset the block link dropdown
        */
        resetBlockDropdown: function () {

            styleeditor.selectLinksInernal.selectedIndex = 0;
            $(styleeditor.selectLinksInernal).select2('destroy').select2();

        },


        /*
            reset the page link dropdown
        */
        resetPageDropdown: function () {

            styleeditor.selectLinksPages.selectedIndex = 0;
            $(styleeditor.selectLinksPages).select2('destroy').select2();

        },


        /*
            when the clicked element is an anchor tag (or has a parent anchor tag)
        */
        editLink: function(el) {

            var theHref;

            $('a#link_Link').parent().show();

            //set theHref
            if( $(el).prop('tagName') === 'A' ) {

                theHref = $(el).attr('href');

            } else if( $(el).parent().prop('tagName') === 'A' ) {

                theHref = $(el).parent().attr('href');

            }

            styleeditor.buildPagesDropdown(theHref);
            styleeditor.buildBlocksDropdown(theHref);
            styleeditor.inputCustomLink.value = theHref;

            //grab an image?
            if ( el.querySelector('img') ) styleeditor.linkImage = el.querySelector('img');
            else styleeditor.linkImage = null;

            //grab an icon?
            if ( el.querySelector('.fa') ) styleeditor.linkIcon = el.querySelector('.fa').cloneNode(true);
            else styleeditor.linkIcon = null;

            styleeditor.inputLinkText.value = el.innerText;

        },


        /*
            when the clicked element is an image
        */
        editImage: function(el) {

            $('a#img_Link').parent().show();

            //set the current SRC
            $('.imageFileTab').find('input#imageURL').val( $(el).attr('src') );

            //reset the file upload
            $('.imageFileTab').find('a.fileinput-exists').click();

        },


        /*
            when the clicked element is a video element
        */
        editVideo: function(el) {

            var matchResults;

            $('a#video_Link').parent().show();
            $('a#video_Link').click();

            //inject current video ID,check if we're dealing with Youtube or Vimeo

            if( $(el).prev().attr('src').indexOf("vimeo.com") > -1 ) {//vimeo

                matchResults = $(el).prev().attr('src').match(/player\.vimeo\.com\/video\/([0-9]*)/);

                $('#video_Tab input#vimeoID').val( matchResults[matchResults.length-1] );
                $('#video_Tab input#youtubeID').val('');

            } else {//youtube

                //temp = $(el).prev().attr('src').split('/');
                var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
                matchResults = $(el).prev().attr('src').match(regExp);

                $('#video_Tab input#youtubeID').val( matchResults[1] );
                $('#video_Tab input#vimeoID').val('');

            }

        },


        /*
            when the clicked element is an fa icon
        */
        editIcon: function() {

            $('a#icon_Link').parent().show();

            //get icon class name, starting with fa-
            var get = $.grep(this.activeElement.element.className.split(" "), function(v, i){

                return v.indexOf('fa-') === 0;

            }).join();

            $('select#icons option').each(function(){

                if( $(this).val() === get ) {

                    $(this).attr('selected', true);

                    $('#icons').trigger('chosen:updated');

                }

            });

        },


        /*
            delete selected element
        */
        deleteElement: function() {

            publisher.publish('onBeforeDelete');

            var toDel;

            //determine what to delete
            if( $(styleeditor.activeElement.element).prop('tagName') === 'A' ) {//ancor

                if( $(styleeditor.activeElement.element).parent().prop('tagName') ==='LI' ) {//clone the LI

                    toDel = $(styleeditor.activeElement.element).parent();

                } else {

                    toDel = $(styleeditor.activeElement.element);

                }

            } else if( $(styleeditor.activeElement.element).prop('tagName') === 'IMG' ) {//image

                if( $(styleeditor.activeElement.element).parent().prop('tagName') === 'A' ) {//clone the A

                    toDel = $(styleeditor.activeElement.element).parent();

                } else {

                    toDel = $(styleeditor.activeElement.element);

                }

            } else {//everything else

                toDel = $(styleeditor.activeElement.element);

            }


            toDel.fadeOut(500, function(){

                var randomEl = $(this).closest('body').find('*:first');

                toDel.remove();

                /* SANDBOX */

                var elementID = $(styleeditor.activeElement.element).attr('id');

                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).remove();

                /* END SANDBOX */

                styleeditor.activeElement.parentBlock.heightAdjustment();

                //we've got pending changes
                siteBuilder.site.setPendingChanges(true);

            });

            $('#deleteElement').modal('hide');

            styleeditor.closeStyleEditor();

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        /*
            clones the selected element
        */
        cloneElement: function() {

            publisher.publish('onBeforeClone');

            var theClone, theClone2, theOne, cloned, cloneParent, elementID;

            if( $(styleeditor.activeElement.element).parent().hasClass('propClone') ) {//clone the parent element

                theClone = $(styleeditor.activeElement.element).parent().clone();
                theClone.find( $(styleeditor.activeElement.element).prop('tagName') ).attr('style', '');

                theClone2 = $(styleeditor.activeElement.element).parent().clone();
                theClone2.find( $(styleeditor.activeElement.element).prop('tagName') ).attr('style', '');

                theOne = theClone.find( $(styleeditor.activeElement.element).prop('tagName') );
                cloned = $(styleeditor.activeElement.element).parent();

                cloneParent = $(styleeditor.activeElement.element).parent().parent();

            } else {//clone the element itself

                theClone = $(styleeditor.activeElement.element).clone();

                theClone.attr('style', '');

                /*if( styleeditor.activeElement.sandbox ) {
                    theClone.attr('id', '').uniqueId();
                }*/

                theClone2 = $(styleeditor.activeElement.element).clone();
                theClone2.attr('style', '');

                /*
                if( styleeditor.activeElement.sandbox ) {
                    theClone2.attr('id', theClone.attr('id'));
                }*/

                theOne = theClone;
                cloned = $(styleeditor.activeElement.element);

                cloneParent = $(styleeditor.activeElement.element).parent();

            }

            cloned.after( theClone );

            /* SANDBOX */

            if( styleeditor.activeElement.sandbox ) {

                elementID = $(styleeditor.activeElement.element).attr('id');
                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).after( theClone2 );

            }

            /* END SANDBOX */

            //make sure the new element gets the proper events set on it
            var newElement = new canvasElement(theOne.get(0));
            newElement.activate();

            //possible height adjustments
            styleeditor.activeElement.parentBlock.heightAdjustment();

            //we've got pending changes
            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        /*
            resets the active element
        */
        resetElement: function() {

            if( $(styleeditor.activeElement.element).closest('body').width() !== $(styleeditor.activeElement.element).width() ) {

                $(styleeditor.activeElement.element).attr('style', '').css({'outline': '3px dashed red', 'cursor': 'pointer'});

            } else {

                $(styleeditor.activeElement.element).attr('style', '').css({'outline': '3px dashed red', 'outline-offset':'-3px', 'cursor': 'pointer'});

            }

            /* SANDBOX */

            if( styleeditor.activeElement.sandbox ) {

                var elementID = $(styleeditor.activeElement.element).attr('id');
                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).attr('style', '');

            }

            /* END SANDBOX */

            $('#styleEditor form#stylingForm').height( $('#styleEditor form#stylingForm').height()+"px" );

            $('#styleEditor form#stylingForm .form-group:not(#styleElTemplate)').fadeOut(500, function(){

                $(this).remove();

            });


            //reset icon

            if( styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] !== null ) {

                var get = $.grep(styleeditor.activeElement.element.className.split(" "), function(v, i){

                    return v.indexOf('fa-') === 0;

                }).join();

                $(styleeditor.activeElement.element).removeClass( get ).addClass( styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] );

                $('select#icons option').each(function(){

                    if( $(this).val() === styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] ) {

                        $(this).attr('selected', true);
                        $('#icons').trigger('chosen:updated');

                    }

                });

            }

            setTimeout( function(){styleeditor.buildeStyleElements( $(styleeditor.activeElement.element).attr('data-selector') );}, 550);

            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        resetSelectLinksPages: function() {

            $('#internalLinksDropdown').select2('val', '#');

        },

        resetSelectLinksInternal: function() {

            $('#pageLinksDropdown').select2('val', '#');

        },

        resetSelectAllLinks: function() {

            $('#internalLinksDropdown').select2('val', '#');
            $('#pageLinksDropdown').select2('val', '#');
            this.select();

        },

        /*
            hides file upload forms
        */
        hideFileUploads: function() {

            $('form#imageUploadForm').hide();
            $('#imageModal #uploadTabLI').hide();

        },


        /*
            closes the style editor
        */
        closeStyleEditor: function (e) {

            if ( e !== undefined ) e.preventDefault();

            if ( styleeditor.activeElement.editableAttributes && styleeditor.activeElement.editableAttributes.indexOf('content') === -1 ) {
                styleeditor.activeElement.removeOutline();
                styleeditor.activeElement.activate();
            }

            if( $('#styleEditor').css('left') === '0px' ) {

                styleeditor.toggleSidePanel('close');

            }

        },


        /*
            toggles the side panel
        */
        toggleSidePanel: function(val) {

            if( val === 'open' && $('#styleEditor').css('left') === '-300px' ) {
                $('#styleEditor').animate({'left': '0px'}, 250);
            } else if( val === 'close' && $('#styleEditor').css('left') === '0px' ) {
                $('#styleEditor').animate({'left': '-300px'}, 250);
            }

        },

    };

    styleeditor.init();

    exports.styleeditor = styleeditor;

}());
},{"../vendor/publisher":10,"./builder.js":3,"./canvasElement.js":4,"./config.js":5}],8:[function(require,module,exports){
(function () {

/* globals siteUrl:false, baseUrl:false */
    "use strict";
        
    var appUI = {
        
        firstMenuWidth: 190,
        secondMenuWidth: 300,
        loaderAnimation: document.getElementById('loader'),
        secondMenuTriggerContainers: $('#menu #main #elementCats, #menu #main #templatesUl'),
        siteUrl: siteUrl,
        baseUrl: baseUrl,
        
        setup: function(){
            
            // Fade the loader animation
            $(appUI.loaderAnimation).fadeOut(function(){
                $('#menu').animate({'left': -appUI.firstMenuWidth}, 1000);
            });
            
            // Tabs
            $(".nav-tabs a").on('click', function (e) {
                e.preventDefault();
                $(this).tab("show");
            });
            
            $("select.select").select2();
            
            $(':radio, :checkbox').radiocheck();
            
            // Tooltips
            $("[data-toggle=tooltip]").tooltip("hide");
            
            // Table: Toggle all checkboxes
            $('.table .toggle-all :checkbox').on('click', function () {
                var $this = $(this);
                var ch = $this.prop('checked');
                $this.closest('.table').find('tbody :checkbox').radiocheck(!ch ? 'uncheck' : 'check');
            });
            
            // Add style class name to a tooltips
            $(".tooltip").addClass(function() {
                if ($(this).prev().attr("data-tooltip-style")) {
                    return "tooltip-" + $(this).prev().attr("data-tooltip-style");
                }
            });
            
            $(".btn-group").on('click', "a", function() {
                $(this).siblings().removeClass("active").end().addClass("active");
            });
            
            // Focus state for append/prepend inputs
            $('.input-group').on('focus', '.form-control', function () {
                $(this).closest('.input-group, .form-group').addClass('focus');
            }).on('blur', '.form-control', function () {
                $(this).closest('.input-group, .form-group').removeClass('focus');
            });
            
            // Table: Toggle all checkboxes
            $('.table .toggle-all').on('click', function() {
                var ch = $(this).find(':checkbox').prop('checked');
                $(this).closest('.table').find('tbody :checkbox').checkbox(!ch ? 'check' : 'uncheck');
            });
            
            // Table: Add class row selected
            $('.table tbody :checkbox').on('check uncheck toggle', function (e) {
                var $this = $(this)
                , check = $this.prop('checked')
                , toggle = e.type === 'toggle'
                , checkboxes = $('.table tbody :checkbox')
                , checkAll = checkboxes.length === checkboxes.filter(':checked').length;

                $this.closest('tr')[check ? 'addClass' : 'removeClass']('selected-row');
                if (toggle) $this.closest('.table').find('.toggle-all :checkbox').checkbox(checkAll ? 'check' : 'uncheck');
            });
            
            // Switch
            $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
                        
            appUI.secondMenuTriggerContainers.on('click', 'a:not(.btn)', appUI.secondMenuAnimation);
                        
        },
        
        secondMenuAnimation: function(){
        
            $('#menu #main a').removeClass('active');
            $(this).addClass('active');
	
            //show only the right elements
            $('#menu #second ul li').hide();
            $('#menu #second ul li.'+$(this).attr('id')).show();

            if( $(this).attr('id') === 'all' ) {
                $('#menu #second ul#elements li').show();		
            }
	
            $('.menu .second').css('display', 'block').stop().animate({
                width: appUI.secondMenuWidth
            }, 500);	
                
        }
        
    };
    
    //initiate the UI
    appUI.setup();


    //**** EXPORTS
    module.exports.appUI = appUI;
    
}());
},{}],9:[function(require,module,exports){
(function () {
    "use strict";
    
    exports.getRandomArbitrary = function(min, max) {
        return Math.floor(Math.random() * (max - min) + min);
    };

    exports.getParameterByName = function (name, url) {

        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
        
    };
    
}());
},{}],10:[function(require,module,exports){
/*!
 * publisher.js - (c) Ryan Florence 2011
 * github.com/rpflorence/publisher.js
 * MIT License
*/

// UMD Boilerplate \o/ && D:
(function (root, factory) {
  if (typeof exports === 'object') {
    module.exports = factory(); // node
  } else if (typeof define === 'function' && define.amd) {
    define(factory); // amd
  } else {
    // window with noConflict
    var _publisher = root.publisher;
    var publisher = root.publisher = factory();
    root.publisher.noConflict = function () {
      root.publisher = _publisher;
      return publisher;
    }
  }
}(this, function () {

  var publisher = function (obj) {
    var topics = {};
    obj = obj || {};

    obj.publish = function (topic/*, messages...*/) {
      if (!topics[topic]) return obj;
      var messages = [].slice.call(arguments, 1);
      for (var i = 0, l = topics[topic].length; i < l; i++) {
        topics[topic][i].handler.apply(topics[topic][i].context, messages);
      }
      return obj;
    };

    obj.subscribe = function (topicOrSubscriber, handlerOrTopics) {
      var firstType = typeof topicOrSubscriber;

      if (firstType === 'string') {
        return subscribe.apply(null, arguments);
      }

      if (firstType === 'object' && !handlerOrTopics) {
        return subscribeMultiple.apply(null, arguments);
      }

      if (typeof handlerOrTopics === 'string') {
        return hitch.apply(null, arguments);
      }

      return hitchMultiple.apply(null, arguments);
    };

    function subscribe (topic, handler, context) {
      var reference = { handler: handler, context: context || obj };
      topic = topics[topic] || (topics[topic] = []);
      topic.push(reference);
      return {
        attach: function () {
          topic.push(reference);
          return this;
        },
        detach: function () {
          erase(topic, reference);
          return this;
        }
      };
    };

    function subscribeMultiple (pairs) {
      var subscriptions = {};
      for (var topic in pairs) {
        if (!pairs.hasOwnProperty(topic)) continue;
        subscriptions[topic] = subscribe(topic, pairs[topic]);
      }
      return subscriptions;
    };

    function hitch (subscriber, topic) {
      return subscribe(topic, subscriber[topic], subscriber);
    };

    function hitchMultiple (subscriber, topics) {
      var subscriptions = [];
      for (var i = 0, l = topics.length; i < l; i++) {
        subscriptions.push( hitch(subscriber, topics[i]) );
      }
      return subscriptions;
    };

    function erase (arr, victim) {
      for (var i = 0, l = arr.length; i < l; i++){
        if (arr[i] === victim) arr.splice(i, 1);
      }
    }

    return obj;
  };

  // publisher is a publisher, so meta ...
  return publisher(publisher);
}));

},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9pbWFnZXMuanMiLCJqcy9tb2R1bGVzL2FjY291bnQuanMiLCJqcy9tb2R1bGVzL2J1aWxkZXIuanMiLCJqcy9tb2R1bGVzL2NhbnZhc0VsZW1lbnQuanMiLCJqcy9tb2R1bGVzL2NvbmZpZy5qcyIsImpzL21vZHVsZXMvaW1hZ2VMaWJyYXJ5LmpzIiwianMvbW9kdWxlcy9zdHlsZWVkaXRvci5qcyIsImpzL21vZHVsZXMvdWkuanMiLCJqcy9tb2R1bGVzL3V0aWxzLmpzIiwianMvdmVuZG9yL3B1Ymxpc2hlci5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ1RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3RKQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDdmlFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUMzSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDekRBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDMU1BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNqbUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDaEhBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDbkJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0cmVxdWlyZSgnLi9tb2R1bGVzL3VpJyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9idWlsZGVyJyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9jb25maWcnKTtcblx0cmVxdWlyZSgnLi9tb2R1bGVzL2ltYWdlTGlicmFyeScpO1xuXHRyZXF1aXJlKCcuL21vZHVsZXMvYWNjb3VudCcpO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXG5cdHZhciBhcHBVSSA9IHJlcXVpcmUoJy4vdWkuanMnKS5hcHBVSTtcblxuXHR2YXIgYWNjb3VudCA9IHtcbiAgICAgICAgXG4gICAgICAgIGJ1dHRvblVwZGF0ZUFjY291bnREZXRhaWxzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWNjb3VudERldGFpbHNTdWJtaXQnKSxcbiAgICAgICAgYnV0dG9uVXBkYXRlTG9naW5EZXRhaWxzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWNjb3VudExvZ2luU3VibWl0JyksXG4gICAgICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblVwZGF0ZUFjY291bnREZXRhaWxzKS5vbignY2xpY2snLCB0aGlzLnVwZGF0ZUFjY291bnREZXRhaWxzKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25VcGRhdGVMb2dpbkRldGFpbHMpLm9uKCdjbGljaycsIHRoaXMudXBkYXRlTG9naW5EZXRhaWxzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGRhdGVzIGFjY291bnQgZGV0YWlsc1xuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVBY2NvdW50RGV0YWlsczogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYWxsIGZpZWxkcyBmaWxsZWQgaW4/XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjZmlyc3RuYW1lJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjZmlyc3RuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDA7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjZmlyc3RuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2xhc3RuYW1lJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjbGFzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNsYXN0bmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgfVxuXHRcdFxuICAgICAgICAgICAgaWYoIGFsbEdvb2QgPT09IDEgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgdGhlQnV0dG9uID0gJCh0aGlzKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3Nob3cgbG9hZGVyXG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyAubG9hZGVyJykuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgYWxlcnRzXG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyAuYWxlcnRzID4gKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybDogYXBwVUkuc2l0ZVVybCtcInVzZXJzL3VhY2NvdW50XCIsXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwb3N0JyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdqc29uJyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YTogJCgnI2FjY291bnRfZGV0YWlscycpLnNlcmlhbGl6ZSgpXG4gICAgICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXQpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgICAgIHRoZUJ1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vaGlkZSBsb2FkZXJcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyAubG9hZGVyJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5hbGVydHMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL3N1Y2Nlc3NcbiAgICAgICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkgeyBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5hbGVydHMgPiAqJykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uICgpIHsgJCh0aGlzKS5yZW1vdmUoKTsgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9LCAzMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlcyBhY2NvdW50IGxvZ2luIGRldGFpbHNcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlTG9naW5EZXRhaWxzOiBmdW5jdGlvbigpIHtcblx0XHRcdFxuXHRcdFx0Y29uc29sZS5sb2coYXBwVUkpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNlbWFpbCcpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNlbWFpbCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNlbWFpbCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnI2FjY291bnRfbG9naW4gaW5wdXQjcGFzc3dvcmQnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gaW5wdXQjcGFzc3dvcmQnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gaW5wdXQjcGFzc3dvcmQnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIGFsbEdvb2QgPT09IDEgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIHRoZUJ1dHRvbiA9ICQodGhpcyk7XG5cbiAgICAgICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3Nob3cgbG9hZGVyXG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmxvYWRlcicpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIGFsZXJ0c1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIC5hbGVydHMgPiAqJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1widXNlcnMvdWxvZ2luXCIsXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwb3N0JyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdqc29uJyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YTogJCgnI2FjY291bnRfbG9naW4nKS5zZXJpYWxpemUoKVxuICAgICAgICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24ocmV0KXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZW5hYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICAgICB0aGVCdXR0b24ucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2hpZGUgbG9hZGVyXG4gICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIC5sb2FkZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIC5hbGVydHMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcblx0XHRcdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgaWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDEgKSB7Ly9zdWNjZXNzXG4gICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHsgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmFsZXJ0cyA+IConKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24gKCkgeyAkKHRoaXMpLnJlbW92ZSgpOyB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sIDMwMDApO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgICAgIFxuICAgIH07XG4gICAgXG4gICAgYWNjb3VudC5pbml0KCk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cbiAgICB2YXIgc2l0ZUJ1aWxkZXJVdGlscyA9IHJlcXVpcmUoJy4vdXRpbHMuanMnKTtcbiAgICB2YXIgYkNvbmZpZyA9IHJlcXVpcmUoJy4vY29uZmlnLmpzJyk7XG4gICAgdmFyIGFwcFVJID0gcmVxdWlyZSgnLi91aS5qcycpLmFwcFVJO1xuICAgIHZhciBwdWJsaXNoZXIgPSByZXF1aXJlKCcuLi92ZW5kb3IvcHVibGlzaGVyJyk7XG4gICAgdmFyIGZvcm1faWQgPSAwO1xuXG5cdCAvKlxuICAgICAgICBCYXNpYyBCdWlsZGVyIFVJIGluaXRpYWxpc2F0aW9uXG4gICAgKi9cbiAgICB2YXIgYnVpbGRlclVJID0ge1xuICAgICAgICBcbiAgICAgICAgYWxsQmxvY2tzOiB7fSwgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9ob2xkcyBhbGwgYmxvY2tzIGxvYWRlZCBmcm9tIHRoZSBzZXJ2ZXJcbiAgICAgICAgbWVudVdyYXBwZXI6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtZW51JyksXG4gICAgICAgIHByaW1hcnlTaWRlTWVudVdyYXBwZXI6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtYWluJyksXG4gICAgICAgIGJ1dHRvbkJhY2s6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYWNrQnV0dG9uJyksXG4gICAgICAgIGJ1dHRvbkJhY2tDb25maXJtOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGVhdmVQYWdlQnV0dG9uJyksXG4gICAgICAgIFxuICAgICAgICBhY2VFZGl0b3JzOiB7fSxcbiAgICAgICAgZnJhbWVDb250ZW50czogJycsICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hvbGRzIGZyYW1lIGNvbnRlbnRzXG4gICAgICAgIHRlbXBsYXRlSUQ6IDAsICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9ob2xkcyB0aGUgdGVtcGxhdGUgSUQgZm9yIGEgcGFnZSAoPz8/KVxuICAgICAgICAgICAgICAgIFxuICAgICAgICBtb2RhbERlbGV0ZUJsb2NrOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGVsZXRlQmxvY2snKSxcbiAgICAgICAgbW9kYWxSZXNldEJsb2NrOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncmVzZXRCbG9jaycpLFxuICAgICAgICBtb2RhbERlbGV0ZVBhZ2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVQYWdlJyksXG4gICAgICAgIGJ1dHRvbkRlbGV0ZVBhZ2VDb25maXJtOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGVsZXRlUGFnZUNvbmZpcm0nKSxcbiAgICAgICAgXG4gICAgICAgIGRyb3Bkb3duUGFnZUxpbmtzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW50ZXJuYWxMaW5rc0Ryb3Bkb3duJyksXG5cbiAgICAgICAgcGFnZUluVXJsOiBudWxsLFxuICAgICAgICBcbiAgICAgICAgdGVtcEZyYW1lOiB7fSxcblxuICAgICAgICBjdXJyZW50UmVzcG9uc2l2ZU1vZGU6IHt9LFxuICAgICAgICAgICAgICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xvYWQgYmxvY2tzXG4gICAgICAgICAgICAkLmdldEpTT04oYXBwVUkuYmFzZVVybCsnZWxlbWVudHMuanNvbj92PTEyMzQ1Njc4JywgZnVuY3Rpb24oZGF0YSl7IGJ1aWxkZXJVSS5hbGxCbG9ja3MgPSBkYXRhOyBidWlsZGVyVUkuaW1wbGVtZW50QmxvY2tzKCk7IH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NpdGViYXIgaG92ZXIgYW5pbWF0aW9uIGFjdGlvblxuICAgICAgICAgICAgJCh0aGlzLm1lbnVXcmFwcGVyKS5vbignbW91c2VlbnRlcicsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5zdG9wKCkuYW5pbWF0ZSh7J2xlZnQnOiAnMHB4J30sIDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9KS5vbignbW91c2VsZWF2ZScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5zdG9wKCkuYW5pbWF0ZSh7J2xlZnQnOiAnLTE5MHB4J30sIDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnI21lbnUgI21haW4gYScpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAkKCcubWVudSAuc2Vjb25kJykuc3RvcCgpLmFuaW1hdGUoe1xuICAgICAgICAgICAgICAgICAgICB3aWR0aDogMFxuICAgICAgICAgICAgICAgIH0sIDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgJCgnI21lbnUgI3NlY29uZCcpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3ByZXZlbnQgY2xpY2sgZXZlbnQgb24gYW5jb3JzIGluIHRoZSBibG9jayBzZWN0aW9uIG9mIHRoZSBzaWRlYmFyXG4gICAgICAgICAgICAkKHRoaXMucHJpbWFyeVNpZGVNZW51V3JhcHBlcikub24oJ2NsaWNrJywgJ2E6bm90KC5hY3Rpb25CdXR0b25zKScsIGZ1bmN0aW9uKGUpe2UucHJldmVudERlZmF1bHQoKTt9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbkJhY2spLm9uKCdjbGljaycsIHRoaXMuYmFja0J1dHRvbik7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uQmFja0NvbmZpcm0pLm9uKCdjbGljaycsIHRoaXMuYmFja0J1dHRvbkNvbmZpcm0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL25vdGlmeSB0aGUgdXNlciBvZiBwZW5kaW5nIGNobmFnZXMgd2hlbiBjbGlja2luZyB0aGUgYmFjayBidXR0b25cbiAgICAgICAgICAgICQod2luZG93KS5iaW5kKCdiZWZvcmV1bmxvYWQnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGlmKCBzaXRlLnBlbmRpbmdDaGFuZ2VzID09PSB0cnVlICkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJ1lvdXIgc2l0ZSBjb250YWlucyBjaGFuZ2VkIHdoaWNoIGhhdmVuXFwndCBiZWVuIHNhdmVkIHlldC4gQXJlIHlvdSBzdXJlIHlvdSB3YW50IHRvIGxlYXZlPyc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vVVJMIHBhcmFtZXRlcnNcbiAgICAgICAgICAgIGJ1aWxkZXJVSS5wYWdlSW5VcmwgPSBzaXRlQnVpbGRlclV0aWxzLmdldFBhcmFtZXRlckJ5TmFtZSgncCcpO1xuXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGJ1aWxkcyB0aGUgYmxvY2tzIGludG8gdGhlIHNpdGUgYmFyXG4gICAgICAgICovXG4gICAgICAgIGltcGxlbWVudEJsb2NrczogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHZhciBuZXdJdGVtLCBsb2FkZXJGdW5jdGlvbjtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIga2V5IGluIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBuaWNlS2V5ID0ga2V5LnRvTG93ZXJDYXNlKCkucmVwbGFjZShcIiBcIiwgXCJfXCIpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQoJzxsaT48YSBocmVmPVwiXCIgaWQ9XCInK25pY2VLZXkrJ1wiPicra2V5Kyc8L2E+PC9saT4nKS5hcHBlbmRUbygnI21lbnUgI21haW4gdWwjZWxlbWVudENhdHMnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBmb3IoIHZhciB4ID0gMDsgeCA8IHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV0ubGVuZ3RoOyB4KysgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS50aHVtYm5haWwgPT09IG51bGwgKSB7Ly93ZSdsbCBuZWVkIGFuIGlmcmFtZVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2J1aWxkIHVzIHNvbWUgaWZyYW1lcyFcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbiApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbG9hZGVyRnVuY3Rpb24gPSAnZGF0YS1sb2FkZXJmdW5jdGlvbj1cIicrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbisnXCInO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpZnJhbWUgc3JjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udXJsKydcIiBzY3JvbGxpbmc9XCJub1wiIHNhbmRib3g9XCJhbGxvdy1zYW1lLW9yaWdpblwiPjwvaWZyYW1lPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbSA9ICQoJzxsaSBjbGFzcz1cImVsZW1lbnQgJytuaWNlS2V5KydcIj48aWZyYW1lIHNyYz1cImFib3V0OmJsYW5rXCIgc2Nyb2xsaW5nPVwibm9cIj48L2lmcmFtZT48L2xpPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0uZmluZCgnaWZyYW1lJykudW5pcXVlSWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc3JjJywgYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnVybCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Ugey8vd2UndmUgZ290IGEgdGh1bWJuYWlsXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0ubG9hZGVyRnVuY3Rpb24gKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxvYWRlckZ1bmN0aW9uID0gJ2RhdGEtbG9hZGVyZnVuY3Rpb249XCInK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0ubG9hZGVyRnVuY3Rpb24rJ1wiJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbSA9ICQoJzxsaSBjbGFzcz1cImVsZW1lbnQgJytuaWNlS2V5KydcIj48aW1nIHNyYz1cIicrYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnRodW1ibmFpbCsnXCIgZGF0YS1zcmNjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udXJsKydcIiBkYXRhLWhlaWdodD1cIicrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5oZWlnaHQrJ1wiIGRhdGEtc2FuZGJveD1cIlwiICcrbG9hZGVyRnVuY3Rpb24rJz48L2xpPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbSA9ICQoJzxsaSBjbGFzcz1cImVsZW1lbnQgJytuaWNlS2V5KydcIj48aW1nIHNyYz1cIicrYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnRodW1ibmFpbCsnXCIgZGF0YS1zcmNjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udXJsKydcIiBkYXRhLWhlaWdodD1cIicrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5oZWlnaHQrJ1wiPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBuZXdJdGVtLmFwcGVuZFRvKCcjbWVudSAjc2Vjb25kIHVsI2VsZW1lbnRzJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy96b29tZXIgd29ya3NcblxuICAgICAgICAgICAgICAgICAgICB2YXIgdGhlSGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uaGVpZ2h0ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVIZWlnaHQgPSB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCowLjI1O1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdGhlSGVpZ2h0ID0gJ2F1dG8nO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0uZmluZCgnaWZyYW1lJykuem9vbWVyKHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHpvb206IDAuMjUsXG4gICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDogMjcwLFxuICAgICAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiB0aGVIZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgICAgICBtZXNzYWdlOiBcIkRyYWcmRHJvcCBNZSFcIlxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kcmFnZ2FibGVzXG4gICAgICAgICAgICBidWlsZGVyVUkubWFrZURyYWdnYWJsZSgpO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZXZlbnQgaGFuZGxlciBmb3Igd2hlbiB0aGUgYmFjayBsaW5rIGlzIGNsaWNrZWRcbiAgICAgICAgKi9cbiAgICAgICAgYmFja0J1dHRvbjogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCBzaXRlLnBlbmRpbmdDaGFuZ2VzID09PSB0cnVlICkge1xuICAgICAgICAgICAgICAgICQoJyNiYWNrTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBidXR0b24gZm9yIGNvbmZpcm1pbmcgbGVhdmluZyB0aGUgcGFnZVxuICAgICAgICAqL1xuICAgICAgICBiYWNrQnV0dG9uQ29uZmlybTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUucGVuZGluZ0NoYW5nZXMgPSBmYWxzZTsvL3ByZXZlbnQgdGhlIEpTIGFsZXJ0IGFmdGVyIGNvbmZpcm1pbmcgdXNlciB3YW50cyB0byBsZWF2ZVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgICAgICAgICAgXG4gICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBtYWtlcyB0aGUgYmxvY2tzIGFuZCB0ZW1wbGF0ZXMgaW4gdGhlIHNpZGViYXIgZHJhZ2dhYmxlIG9udG8gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICBtYWtlRHJhZ2dhYmxlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI2VsZW1lbnRzIGxpLCAjdGVtcGxhdGVzIGxpJykuZWFjaChmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5kcmFnZ2FibGUoe1xuICAgICAgICAgICAgICAgICAgICBoZWxwZXI6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuICQoJzxkaXYgc3R5bGU9XCJoZWlnaHQ6IDEwMHB4OyB3aWR0aDogMzAwcHg7IGJhY2tncm91bmQ6ICNGOUZBRkE7IGJveC1zaGFkb3c6IDVweCA1cHggMXB4IHJnYmEoMCwwLDAsMC4xKTsgdGV4dC1hbGlnbjogY2VudGVyOyBsaW5lLWhlaWdodDogMTAwcHg7IGZvbnQtc2l6ZTogMjhweDsgY29sb3I6ICMxNkEwODVcIj48c3BhbiBjbGFzcz1cImZ1aS1saXN0XCI+PC9zcGFuPjwvZGl2PicpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICByZXZlcnQ6ICdpbnZhbGlkJyxcbiAgICAgICAgICAgICAgICAgICAgYXBwZW5kVG86ICdib2R5JyxcbiAgICAgICAgICAgICAgICAgICAgY29ubmVjdFRvU29ydGFibGU6ICcjcGFnZUxpc3QgPiB1bCcsXG4gICAgICAgICAgICAgICAgICAgIHN0YXJ0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzaXRlLm1vdmVNb2RlKCdvbicpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBzdG9wOiBmdW5jdGlvbiAoKSB7fVxuICAgICAgICAgICAgICAgIH0pOyBcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoJyNlbGVtZW50cyBsaSBhJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcykudW5iaW5kKCdjbGljaycpLmJpbmQoJ2NsaWNrJywgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgSW1wbGVtZW50cyB0aGUgc2l0ZSBvbiB0aGUgY2FudmFzLCBjYWxsZWQgZnJvbSB0aGUgU2l0ZSBvYmplY3Qgd2hlbiB0aGUgc2l0ZURhdGEgaGFzIGNvbXBsZXRlZCBsb2FkaW5nXG4gICAgICAgICovXG4gICAgICAgIHBvcHVsYXRlQ2FudmFzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgdmFyIGksXG4gICAgICAgICAgICAgICAgY291bnRlciA9IDE7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9vcCB0aHJvdWdoIHRoZSBwYWdlc1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIGkgaW4gc2l0ZS5wYWdlcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgbmV3UGFnZSA9IG5ldyBQYWdlKGksIHNpdGUucGFnZXNbaV0sIGNvdW50ZXIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBjb3VudGVyKys7XG5cbiAgICAgICAgICAgICAgICAvL3NldCB0aGlzIHBhZ2UgYXMgYWN0aXZlP1xuICAgICAgICAgICAgICAgIGlmKCBidWlsZGVyVUkucGFnZUluVXJsID09PSBpICkge1xuICAgICAgICAgICAgICAgICAgICBuZXdQYWdlLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2FjdGl2YXRlIHRoZSBmaXJzdCBwYWdlXG4gICAgICAgICAgICBpZihzaXRlLnNpdGVQYWdlcy5sZW5ndGggPiAwICYmIGJ1aWxkZXJVSS5wYWdlSW5VcmwgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBzaXRlLnNpdGVQYWdlc1swXS5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBDYW52YXMgbG9hZGluZyBvbi9vZmZcbiAgICAgICAgKi9cbiAgICAgICAgY2FudmFzTG9hZGluZzogZnVuY3Rpb24gKHZhbHVlKSB7XG5cbiAgICAgICAgICAgIGlmICggdmFsdWUgPT09ICdvbicgJiYgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZyYW1lV3JhcHBlcicpLnF1ZXJ5U2VsZWN0b3JBbGwoJyNjYW52YXNPdmVybGF5JykubGVuZ3RoID09PSAwICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIG92ZXJsYXkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdESVYnKTtcblxuICAgICAgICAgICAgICAgIG92ZXJsYXkuc3R5bGUuZGlzcGxheSA9ICdmbGV4JztcbiAgICAgICAgICAgICAgICAkKG92ZXJsYXkpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICBvdmVybGF5LmlkID0gJ2NhbnZhc092ZXJsYXknO1xuXG4gICAgICAgICAgICAgICAgb3ZlcmxheS5pbm5lckhUTUwgPSAnPGRpdiBjbGFzcz1cImxvYWRlclwiPjxzcGFuPns8L3NwYW4+PHNwYW4+fTwvc3Bhbj48L2Rpdj4nO1xuXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZyYW1lV3JhcHBlcicpLmFwcGVuZENoaWxkKG92ZXJsYXkpO1xuXG4gICAgICAgICAgICAgICAgJCgnI2NhbnZhc092ZXJsYXknKS5mYWRlSW4oNTAwKTtcblxuICAgICAgICAgICAgfSBlbHNlIGlmICggdmFsdWUgPT09ICdvZmYnICYmIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdmcmFtZVdyYXBwZXInKS5xdWVyeVNlbGVjdG9yQWxsKCcjY2FudmFzT3ZlcmxheScpLmxlbmd0aCA9PT0gMSApIHtcblxuICAgICAgICAgICAgICAgIHNpdGUubG9hZGVkKCk7XG5cbiAgICAgICAgICAgICAgICAkKCcjY2FudmFzT3ZlcmxheScpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBmb3JtX2lkID0gJCgnI2Zvcm1JRCcpLnZhbCgpO1xuICAgICAgICAgICAgICAgIGlmKGZvcm1faWQgIT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5xdWlja19sb2FkX2Zvcm0oKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9XG4gICAgICAgIFxuICAgIH07XG5cbiAgICAvKlxuICAgICAgICBQYWdlIGNvbnN0cnVjdG9yXG4gICAgKi9cbiAgICBmdW5jdGlvbiBQYWdlIChwYWdlTmFtZSwgcGFnZSwgY291bnRlcikge1xuICAgIFxuICAgICAgICB0aGlzLm5hbWUgPSBwYWdlTmFtZSB8fCBcIlwiO1xuICAgICAgICB0aGlzLnBhZ2VJRCA9IHBhZ2UucGFnZV9pZCB8fCAwO1xuICAgICAgICB0aGlzLmJsb2NrcyA9IFtdO1xuICAgICAgICB0aGlzLnBhcmVudFVMID0ge307IC8vcGFyZW50IFVMIG9uIHRoZSBjYW52YXNcbiAgICAgICAgdGhpcy5zdGF0dXMgPSAnJzsvLycnLCAnbmV3JyBvciAnY2hhbmdlZCdcbiAgICAgICAgdGhpcy5zY3JpcHRzID0gW107Ly90cmFja3Mgc2NyaXB0IFVSTHMgdXNlZCBvbiB0aGlzIHBhZ2VcbiAgICAgICAgXG4gICAgICAgIHRoaXMucGFnZVNldHRpbmdzID0ge1xuICAgICAgICAgICAgdGl0bGU6IHBhZ2UucGFnZXNfdGl0bGUgfHwgJycsXG4gICAgICAgICAgICBtZXRhX2Rlc2NyaXB0aW9uOiBwYWdlLm1ldGFfZGVzY3JpcHRpb24gfHwgJycsXG4gICAgICAgICAgICBtZXRhX2tleXdvcmRzOiBwYWdlLm1ldGFfa2V5d29yZHMgfHwgJycsXG4gICAgICAgICAgICBoZWFkZXJfaW5jbHVkZXM6IHBhZ2UuaGVhZGVyX2luY2x1ZGVzIHx8ICcnLFxuICAgICAgICAgICAgcGFnZV9jc3M6IHBhZ2UucGFnZV9jc3MgfHwgJydcbiAgICAgICAgfTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgdGhpcy5wYWdlTWVudVRlbXBsYXRlID0gJzxhIGhyZWY9XCJcIiBjbGFzcz1cIm1lbnVJdGVtTGlua1wiPnBhZ2U8L2E+PHNwYW4gY2xhc3M9XCJwYWdlQnV0dG9uc1wiPjxhIGhyZWY9XCJcIiBjbGFzcz1cImZpbGVFZGl0IGZ1aS1uZXdcIj48L2E+PGEgaHJlZj1cIlwiIGNsYXNzPVwiZmlsZURlbCBmdWktY3Jvc3NcIj48YSBjbGFzcz1cImJ0biBidG4teHMgYnRuLXByaW1hcnkgYnRuLWVtYm9zc2VkIGZpbGVTYXZlIGZ1aS1jaGVja1wiIGhyZWY9XCIjXCI+PC9hPjwvc3Bhbj48L2E+PC9zcGFuPic7XG4gICAgICAgIFxuICAgICAgICB0aGlzLm1lbnVJdGVtID0ge307Ly9yZWZlcmVuY2UgdG8gdGhlIHBhZ2VzIG1lbnUgaXRlbSBmb3IgdGhpcyBwYWdlIGluc3RhbmNlXG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0gPSB7fTsvL3JlZmVyZW5jZSB0byB0aGUgbGlua3MgZHJvcGRvd24gaXRlbSBmb3IgdGhpcyBwYWdlIGluc3RhbmNlXG4gICAgICAgIFxuICAgICAgICB0aGlzLnBhcmVudFVMID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnVUwnKTtcbiAgICAgICAgdGhpcy5wYXJlbnRVTC5zZXRBdHRyaWJ1dGUoJ2lkJywgXCJwYWdlXCIrY291bnRlcik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBtYWtlcyB0aGUgY2xpY2tlZCBwYWdlIGFjdGl2ZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNlbGVjdFBhZ2UgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9jb25zb2xlLmxvZygnc2VsZWN0OicpO1xuICAgICAgICAgICAgLy9jb25zb2xlLmxvZyh0aGlzLnBhZ2VTZXR0aW5ncyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbWFyayB0aGUgbWVudSBpdGVtIGFzIGFjdGl2ZVxuICAgICAgICAgICAgc2l0ZS5kZUFjdGl2YXRlQWxsKCk7XG4gICAgICAgICAgICAkKHRoaXMubWVudUl0ZW0pLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sZXQgU2l0ZSBrbm93IHdoaWNoIHBhZ2UgaXMgY3VycmVudGx5IGFjdGl2ZVxuICAgICAgICAgICAgc2l0ZS5zZXRBY3RpdmUodGhpcyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGlzcGxheSB0aGUgbmFtZSBvZiB0aGUgYWN0aXZlIHBhZ2Ugb24gdGhlIGNhbnZhc1xuICAgICAgICAgICAgc2l0ZS5wYWdlVGl0bGUuaW5uZXJIVE1MID0gdGhpcy5uYW1lO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xvYWQgdGhlIHBhZ2Ugc2V0dGluZ3MgaW50byB0aGUgcGFnZSBzZXR0aW5ncyBtb2RhbFxuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc1RpdGxlLnZhbHVlID0gdGhpcy5wYWdlU2V0dGluZ3MudGl0bGU7XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzTWV0YURlc2NyaXB0aW9uLnZhbHVlID0gdGhpcy5wYWdlU2V0dGluZ3MubWV0YV9kZXNjcmlwdGlvbjtcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NNZXRhS2V5d29yZHMudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5tZXRhX2tleXdvcmRzO1xuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc0luY2x1ZGVzLnZhbHVlID0gdGhpcy5wYWdlU2V0dGluZ3MuaGVhZGVyX2luY2x1ZGVzO1xuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc1BhZ2VDc3MudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5wYWdlX2NzcztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3RyaWdnZXIgY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignY2hhbmdlUGFnZScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3Jlc2V0IHRoZSBoZWlnaHRzIGZvciB0aGUgYmxvY2tzIG9uIHRoZSBjdXJyZW50IHBhZ2VcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5ibG9ja3MgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIE9iamVjdC5rZXlzKHRoaXMuYmxvY2tzW2ldLmZyYW1lRG9jdW1lbnQpLmxlbmd0aCA+IDAgKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ibG9ja3NbaV0uaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3Nob3cgdGhlIGVtcHR5IG1lc3NhZ2U/XG4gICAgICAgICAgICB0aGlzLmlzRW1wdHkoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNoYW5nZWQgdGhlIGxvY2F0aW9uL29yZGVyIG9mIGEgYmxvY2sgd2l0aGluIGEgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNldFBvc2l0aW9uID0gZnVuY3Rpb24oZnJhbWVJRCwgbmV3UG9zKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vd2UnbGwgbmVlZCB0aGUgYmxvY2sgb2JqZWN0IGNvbm5lY3RlZCB0byBpZnJhbWUgd2l0aCBmcmFtZUlEXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvcih2YXIgaSBpbiB0aGlzLmJsb2Nrcykge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCB0aGlzLmJsb2Nrc1tpXS5mcmFtZS5nZXRBdHRyaWJ1dGUoJ2lkJykgPT09IGZyYW1lSUQgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NoYW5nZSB0aGUgcG9zaXRpb24gb2YgdGhpcyBibG9jayBpbiB0aGUgYmxvY2tzIGFycmF5XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzLnNwbGljZShuZXdQb3MsIDAsIHRoaXMuYmxvY2tzLnNwbGljZShpLCAxKVswXSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZSBibG9jayBmcm9tIGJsb2NrcyBhcnJheVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmRlbGV0ZUJsb2NrID0gZnVuY3Rpb24oYmxvY2spIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgZnJvbSBibG9ja3MgYXJyYXlcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5ibG9ja3MgKSB7XG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzW2ldID09PSBibG9jayApIHtcbiAgICAgICAgICAgICAgICAgICAgLy9mb3VuZCBpdCwgcmVtb3ZlIGZyb20gYmxvY2tzIGFycmF5XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzLnNwbGljZShpLCAxKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB0b2dnbGVzIGFsbCBibG9jayBmcmFtZUNvdmVycyBvbiB0aGlzIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy50b2dnbGVGcmFtZUNvdmVycyA9IGZ1bmN0aW9uKG9uT3JPZmYpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzW2ldLnRvZ2dsZUNvdmVyKG9uT3JPZmYpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2V0dXAgZm9yIGVkaXRpbmcgYSBwYWdlIG5hbWVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5lZGl0UGFnZU5hbWUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICF0aGlzLm1lbnVJdGVtLmNsYXNzTGlzdC5jb250YWlucygnZWRpdCcpICkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9oaWRlIHRoZSBsaW5rXG4gICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLm1lbnVJdGVtTGluaycpLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2luc2VydCB0aGUgaW5wdXQgZmllbGRcbiAgICAgICAgICAgICAgICB2YXIgbmV3SW5wdXQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpbnB1dCcpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnR5cGUgPSAndGV4dCc7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuc2V0QXR0cmlidXRlKCduYW1lJywgJ3BhZ2UnKTtcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgdGhpcy5uYW1lKTtcbiAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLmluc2VydEJlZm9yZShuZXdJbnB1dCwgdGhpcy5tZW51SXRlbS5maXJzdENoaWxkKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuZm9jdXMoKTtcbiAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIHRtcFN0ciA9IG5ld0lucHV0LmdldEF0dHJpYnV0ZSgndmFsdWUnKTtcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgJycpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgndmFsdWUnLCB0bXBTdHIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LmFkZCgnZWRpdCcpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBVcGRhdGVzIHRoaXMgcGFnZSdzIG5hbWUgKGV2ZW50IGhhbmRsZXIgZm9yIHRoZSBzYXZlIGJ1dHRvbilcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy51cGRhdGVQYWdlTmFtZUV2ZW50ID0gZnVuY3Rpb24oZWwpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0JykgKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2VsIGlzIHRoZSBjbGlja2VkIGJ1dHRvbiwgd2UnbGwgbmVlZCBhY2Nlc3MgdG8gdGhlIGlucHV0XG4gICAgICAgICAgICAgICAgdmFyIHRoZUlucHV0ID0gdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPVwicGFnZVwiXScpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vbWFrZSBzdXJlIHRoZSBwYWdlJ3MgbmFtZSBpcyBPS1xuICAgICAgICAgICAgICAgIGlmKCBzaXRlLmNoZWNrUGFnZU5hbWUodGhlSW5wdXQudmFsdWUpICkge1xuICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWUgPSBzaXRlLnByZXBQYWdlTmFtZSggdGhlSW5wdXQudmFsdWUgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0W25hbWU9XCJwYWdlXCJdJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5tZW51SXRlbUxpbmsnKS5pbm5lckhUTUwgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5tZW51SXRlbUxpbmsnKS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLmNsYXNzTGlzdC5yZW1vdmUoJ2VkaXQnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgdGhlIGxpbmtzIGRyb3Bkb3duIGl0ZW1cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS50ZXh0ID0gdGhpcy5uYW1lO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnNldEF0dHJpYnV0ZSgndmFsdWUnLCB0aGlzLm5hbWUrXCIuaHRtbFwiKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vdXBkYXRlIHRoZSBwYWdlIG5hbWUgb24gdGhlIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnBhZ2VUaXRsZS5pbm5lckhUTUwgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9jaGFuZ2VkIHBhZ2UgdGl0bGUsIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgYWxlcnQoc2l0ZS5wYWdlTmFtZUVycm9yKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlcyB0aGlzIGVudGlyZSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZGVsZXRlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIGZyb20gdGhlIFNpdGVcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gc2l0ZS5zaXRlUGFnZXMgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHNpdGUuc2l0ZVBhZ2VzW2ldID09PSB0aGlzICkgey8vZ290IGEgbWF0Y2ghXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0ZSBmcm9tIHNpdGUuc2l0ZVBhZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzLnNwbGljZShpLCAxKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIGZyb20gY2FudmFzXG4gICAgICAgICAgICAgICAgICAgIHRoaXMucGFyZW50VUwucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2FkZCB0byBkZWxldGVkIHBhZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUucGFnZXNUb0RlbGV0ZS5wdXNoKHRoaXMubmFtZSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0ZSB0aGUgcGFnZSdzIG1lbnUgaXRlbVxuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldCB0aGUgcGFnZXMgbGluayBkcm9wZG93biBpdGVtXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0ucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2FjdGl2YXRlIHRoZSBmaXJzdCBwYWdlXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzWzBdLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vcGFnZSB3YXMgZGVsZXRlZCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjaGVja3MgaWYgdGhlIHBhZ2UgaXMgZW1wdHksIGlmIHNvIHNob3cgdGhlICdlbXB0eScgbWVzc2FnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmlzRW1wdHkgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzLmxlbmd0aCA9PT0gMCApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzaXRlLm1lc3NhZ2VTdGFydC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICBzaXRlLmRpdkZyYW1lV3JhcHBlci5jbGFzc0xpc3QuYWRkKCdlbXB0eScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgc2l0ZS5tZXNzYWdlU3RhcnQuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICBzaXRlLmRpdkZyYW1lV3JhcHBlci5jbGFzc0xpc3QucmVtb3ZlKCdlbXB0eScpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHByZXBzL3N0cmlwcyB0aGlzIHBhZ2UgZGF0YSBmb3IgYSBwZW5kaW5nIGFqYXggcmVxdWVzdFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnByZXBGb3JTYXZlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlID0ge307XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgcGFnZS5uYW1lID0gdGhpcy5uYW1lO1xuICAgICAgICAgICAgcGFnZS5wYWdlU2V0dGluZ3MgPSB0aGlzLnBhZ2VTZXR0aW5ncztcbiAgICAgICAgICAgIHBhZ2Uuc3RhdHVzID0gdGhpcy5zdGF0dXM7XG4gICAgICAgICAgICBwYWdlLnBhZ2VJRCA9IHRoaXMucGFnZUlEO1xuICAgICAgICAgICAgcGFnZS5ibG9ja3MgPSBbXTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3Byb2Nlc3MgdGhlIGJsb2Nrc1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgdGhpcy5ibG9ja3MubGVuZ3RoOyB4KysgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgYmxvY2sgPSB7fTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCB0aGlzLmJsb2Nrc1t4XS5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBibG9jay5mcmFtZUNvbnRlbnQgPSBcIjxodG1sPlwiKyQoJyNzYW5kYm94ZXMgIycrdGhpcy5ibG9ja3NbeF0uc2FuZGJveCkuY29udGVudHMoKS5maW5kKCdodG1sJykuaHRtbCgpK1wiPC9odG1sPlwiO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5zYW5kYm94ID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2subG9hZGVyRnVuY3Rpb24gPSB0aGlzLmJsb2Nrc1t4XS5zYW5kYm94X2xvYWRlcjtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgYmxvY2suZnJhbWVDb250ZW50ID0gdGhpcy5ibG9ja3NbeF0uZ2V0U291cmNlKCk7XG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLnNhbmRib3ggPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2subG9hZGVyRnVuY3Rpb24gPSAnJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBibG9jay5mcmFtZUhlaWdodCA9IHRoaXMuYmxvY2tzW3hdLmZyYW1lSGVpZ2h0O1xuICAgICAgICAgICAgICAgIGJsb2NrLm9yaWdpbmFsVXJsID0gdGhpcy5ibG9ja3NbeF0ub3JpZ2luYWxVcmw7XG4gICAgICAgICAgICAgICAgaWYgKCB0aGlzLmJsb2Nrc1t4XS5nbG9iYWwgKSBibG9jay5mcmFtZXNfZ2xvYmFsID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBwYWdlLmJsb2Nrcy5wdXNoKGJsb2NrKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gcGFnZTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBnZW5lcmF0ZXMgdGhlIGZ1bGwgcGFnZSwgdXNpbmcgc2tlbGV0b24uaHRtbFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmZ1bGxQYWdlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlID0gdGhpczsvL3JlZmVyZW5jZSB0byBzZWxmIGZvciBsYXRlclxuICAgICAgICAgICAgcGFnZS5zY3JpcHRzID0gW107Ly9tYWtlIHN1cmUgaXQncyBlbXB0eSwgd2UnbGwgc3RvcmUgc2NyaXB0IFVSTHMgaW4gdGhlcmUgbGF0ZXJcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIG5ld0RvY01haW5QYXJlbnQgPSAkKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2VtcHR5IG91dCB0aGUgc2tlbGV0b24gZmlyc3RcbiAgICAgICAgICAgICQoJ2lmcmFtZSNza2VsZXRvbicpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuaHRtbCgnJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVtb3ZlIG9sZCBzY3JpcHQgdGFnc1xuICAgICAgICAgICAgJCgnaWZyYW1lI3NrZWxldG9uJykuY29udGVudHMoKS5maW5kKCAnc2NyaXB0JyApLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHZhciB0aGVDb250ZW50cztcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2dyYWIgdGhlIGJsb2NrIGNvbnRlbnRcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5ibG9ja3NbaV0uc2FuZGJveCAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzID0gJCgnI3NhbmRib3hlcyAjJyt0aGlzLmJsb2Nrc1tpXS5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzID0gJCh0aGlzLmJsb2Nrc1tpXS5mcmFtZURvY3VtZW50LmJvZHkpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSB2aWRlbyBmcmFtZUNvdmVyc1xuICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoJy5mcmFtZUNvdmVyJykuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgdmlkZW8gZnJhbWVXcmFwcGVyc1xuICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoJy52aWRlb1dyYXBwZXInKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB2YXIgY250ID0gJCh0aGlzKS5jb250ZW50cygpO1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlcGxhY2VXaXRoKGNudCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIHN0eWxlIGxlZnRvdmVycyBmcm9tIHRoZSBzdHlsZSBlZGl0b3JcbiAgICAgICAgICAgICAgICBmb3IoIHZhciBrZXkgaW4gYkNvbmZpZy5lZGl0YWJsZUl0ZW1zICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCBrZXkgKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlQXR0cignZGF0YS1zZWxlY3RvcicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcygnb3V0bGluZScsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdvdXRsaW5lLW9mZnNldCcsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdjdXJzb3InLCAnJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLmF0dHIoJ3N0eWxlJykgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlQXR0cignc3R5bGUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIHN0eWxlIGxlZnRvdmVycyBmcm9tIHRoZSBjb250ZW50IGVkaXRvclxuICAgICAgICAgICAgICAgIGZvciAoIHZhciB4ID0gMDsgeCA8IGJDb25maWcuZWRpdGFibGVDb250ZW50Lmxlbmd0aDsgKyt4KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCBiQ29uZmlnLmVkaXRhYmxlQ29udGVudFt4XSApLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdkYXRhLXNlbGVjdG9yJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9hcHBlbmQgdG8gRE9NIGluIHRoZSBza2VsZXRvblxuICAgICAgICAgICAgICAgIG5ld0RvY01haW5QYXJlbnQuYXBwZW5kKCAkKHRoZUNvbnRlbnRzLmh0bWwoKSkgKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2RvIHdlIG5lZWQgdG8gaW5qZWN0IGFueSBzY3JpcHRzP1xuICAgICAgICAgICAgICAgIHZhciBzY3JpcHRzID0gJCh0aGlzLmJsb2Nrc1tpXS5mcmFtZURvY3VtZW50LmJvZHkpLmZpbmQoJ3NjcmlwdCcpO1xuICAgICAgICAgICAgICAgIHZhciB0aGVJZnJhbWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInNrZWxldG9uXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggc2NyaXB0cy5zaXplKCkgPiAwICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgc2NyaXB0cy5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBzY3JpcHQ7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLnRleHQoKSAhPT0gJycgKSB7Ly9zY3JpcHQgdGFncyB3aXRoIGNvbnRlbnRcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQgPSB0aGVJZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic2NyaXB0XCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC50eXBlID0gJ3RleHQvamF2YXNjcmlwdCc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LmlubmVySFRNTCA9ICQodGhpcykudGV4dCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc2NyaXB0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmKCAkKHRoaXMpLmF0dHIoJ3NyYycpICE9PSBudWxsICYmIHBhZ2Uuc2NyaXB0cy5pbmRleE9mKCQodGhpcykuYXR0cignc3JjJykpID09PSAtMSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL3VzZSBpbmRleE9mIHRvIG1ha2Ugc3VyZSBlYWNoIHNjcmlwdCBvbmx5IGFwcGVhcnMgb24gdGhlIHByb2R1Y2VkIHBhZ2Ugb25jZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdCA9IHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzY3JpcHRcIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQuc3JjID0gJCh0aGlzKS5hdHRyKCdzcmMnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGVJZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHNjcmlwdCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcGFnZS5zY3JpcHRzLnB1c2goJCh0aGlzKS5hdHRyKCdzcmMnKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2hlY2tzIGlmIGFsbCBibG9ja3Mgb24gdGhpcyBwYWdlIGhhdmUgZmluaXNoZWQgbG9hZGluZ1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmxvYWRlZCA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgdmFyIGk7XG5cbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDx0aGlzLmJsb2Nrcy5sZW5ndGg7IGkrKyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggIXRoaXMuYmxvY2tzW2ldLmxvYWRlZCApIHJldHVybiBmYWxzZTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcblxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjbGVhciBvdXQgdGhpcyBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY2xlYXIgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGJsb2NrID0gdGhpcy5ibG9ja3MucG9wKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHdoaWxlKCBibG9jayAhPT0gdW5kZWZpbmVkICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmRlbGV0ZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrID0gdGhpcy5ibG9ja3MucG9wKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBIZWlnaHQgYWRqdXN0bWVudCBmb3IgYWxsIGJsb2NrcyBvbiB0aGUgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmhlaWdodEFkanVzdG1lbnQgPSBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIGZvciAoIHZhciBpID0gMDsgaSA8IHRoaXMuYmxvY2tzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzW2ldLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9O1xuICAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvL2xvb3AgdGhyb3VnaCB0aGUgZnJhbWVzL2Jsb2Nrc1xuICAgICAgICBcbiAgICAgICAgaWYoIHBhZ2UuaGFzT3duUHJvcGVydHkoJ2Jsb2NrcycpICkge1xuICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgcGFnZS5ibG9ja3MubGVuZ3RoOyB4KysgKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2NyZWF0ZSBuZXcgQmxvY2tcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBuZXdCbG9jayA9IG5ldyBCbG9jaygpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgcGFnZS5ibG9ja3NbeF0uc3JjID0gYXBwVUkuc2l0ZVVybCtcInNpdGVzL2dldGZyYW1lL1wiK3BhZ2UuYmxvY2tzW3hdLmZyYW1lc19pZDtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3NhbmRib3hlZCBibG9jaz9cbiAgICAgICAgICAgICAgICBpZiggcGFnZS5ibG9ja3NbeF0uZnJhbWVzX3NhbmRib3ggPT09ICcxJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5zYW5kYm94ID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suc2FuZGJveF9sb2FkZXIgPSBwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfbG9hZGVyZnVuY3Rpb247XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmZyYW1lSUQgPSBwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfaWQ7XG4gICAgICAgICAgICAgICAgaWYgKCBwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfZ2xvYmFsID09PSAnMScgKSBuZXdCbG9jay5nbG9iYWwgPSB0cnVlO1xuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZVBhcmVudExJKHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19oZWlnaHQpO1xuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZUZyYW1lKHBhZ2UuYmxvY2tzW3hdKTtcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZUNvdmVyKCk7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suaW5zZXJ0QmxvY2tJbnRvRG9tKHRoaXMucGFyZW50VUwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2FkZCB0aGUgYmxvY2sgdG8gdGhlIG5ldyBwYWdlXG4gICAgICAgICAgICAgICAgdGhpcy5ibG9ja3MucHVzaChuZXdCbG9jayk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICAgICAgLy9hZGQgdGhpcyBwYWdlIHRvIHRoZSBzaXRlIG9iamVjdFxuICAgICAgICBzaXRlLnNpdGVQYWdlcy5wdXNoKCB0aGlzICk7XG4gICAgICAgIFxuICAgICAgICAvL3BsYW50IHRoZSBuZXcgVUwgaW4gdGhlIERPTSAob24gdGhlIGNhbnZhcylcbiAgICAgICAgc2l0ZS5kaXZDYW52YXMuYXBwZW5kQ2hpbGQodGhpcy5wYXJlbnRVTCk7XG4gICAgICAgIFxuICAgICAgICAvL21ha2UgdGhlIGJsb2Nrcy9mcmFtZXMgaW4gZWFjaCBwYWdlIHNvcnRhYmxlXG4gICAgICAgIFxuICAgICAgICB2YXIgdGhlUGFnZSA9IHRoaXM7XG4gICAgICAgIFxuICAgICAgICAkKHRoaXMucGFyZW50VUwpLnNvcnRhYmxlKHtcbiAgICAgICAgICAgIHJldmVydDogdHJ1ZSxcbiAgICAgICAgICAgIHBsYWNlaG9sZGVyOiBcImRyb3AtaG92ZXJcIixcbiAgICAgICAgICAgIGhhbmRsZTogJy5kcmFnQmxvY2snLFxuICAgICAgICAgICAgY2FuY2VsOiAnJyxcbiAgICAgICAgICAgIHN0b3A6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzaXRlLm1vdmVNb2RlKCdvZmYnKTtcbiAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgIGlmICggIXNpdGUubG9hZGVkKCkgKSBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb24nKTtcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBiZWZvcmVTdG9wOiBmdW5jdGlvbihldmVudCwgdWkpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vdGVtcGxhdGUgb3IgcmVndWxhciBibG9jaz9cbiAgICAgICAgICAgICAgICB2YXIgYXR0ciA9IHVpLml0ZW0uYXR0cignZGF0YS1mcmFtZXMnKTtcblxuICAgICAgICAgICAgICAgIHZhciBuZXdCbG9jaztcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7Ly90ZW1wbGF0ZSwgYnVpbGQgaXRcbiAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoJyNzdGFydCcpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9jbGVhciBvdXQgYWxsIGJsb2NrcyBvbiB0aGlzIHBhZ2UgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZVBhZ2UuY2xlYXIoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vY3JlYXRlIHRoZSBuZXcgZnJhbWVzXG4gICAgICAgICAgICAgICAgICAgIHZhciBmcmFtZUlEcyA9IHVpLml0ZW0uYXR0cignZGF0YS1mcmFtZXMnKS5zcGxpdCgnLScpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgaGVpZ2h0cyA9IHVpLml0ZW0uYXR0cignZGF0YS1oZWlnaHRzJykuc3BsaXQoJy0nKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHVybHMgPSB1aS5pdGVtLmF0dHIoJ2RhdGEtb3JpZ2luYWx1cmxzJykuc3BsaXQoJy0nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBmb3IoIHZhciB4ID0gMDsgeCA8IGZyYW1lSURzLmxlbmd0aDsgeCsrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrID0gbmV3IEJsb2NrKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVQYXJlbnRMSShoZWlnaHRzW3hdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGZyYW1lRGF0YSA9IHt9O1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBmcmFtZURhdGEuc3JjID0gYXBwVUkuc2l0ZVVybCsnc2l0ZXMvZ2V0ZnJhbWUvJytmcmFtZUlEc1t4XTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfb3JpZ2luYWxfdXJsID0gYXBwVUkuc2l0ZVVybCsnc2l0ZXMvZ2V0ZnJhbWUvJytmcmFtZUlEc1t4XTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfaGVpZ2h0ID0gaGVpZ2h0c1t4XTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlRnJhbWUoIGZyYW1lRGF0YSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlRnJhbWVDb3ZlcigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suaW5zZXJ0QmxvY2tJbnRvRG9tKHRoZVBhZ2UucGFyZW50VUwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2FkZCB0aGUgYmxvY2sgdG8gdGhlIG5ldyBwYWdlXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVQYWdlLmJsb2Nrcy5wdXNoKG5ld0Jsb2NrKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9kcm9wcGVkIGVsZW1lbnQsIHNvIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9zZXQgdGhlIHRlbXBhdGVJRFxuICAgICAgICAgICAgICAgICAgICBidWlsZGVyVUkudGVtcGxhdGVJRCA9IHVpLml0ZW0uYXR0cignZGF0YS1wYWdlaWQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL21ha2Ugc3VyZSBub3RoaW5nIGdldHMgZHJvcHBlZCBpbiB0aGUgbHNpdFxuICAgICAgICAgICAgICAgICAgICB1aS5pdGVtLmh0bWwobnVsbCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldGUgZHJhZyBwbGFjZSBob2xkZXJcbiAgICAgICAgICAgICAgICAgICAgJCgnYm9keSAudWktc29ydGFibGUtaGVscGVyJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7Ly9yZWd1bGFyIGJsb2NrXG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vYXJlIHdlIGRlYWxpbmcgd2l0aCBhIG5ldyBibG9jayBiZWluZyBkcm9wcGVkIG9udG8gdGhlIGNhbnZhcywgb3IgYSByZW9yZGVyaW5nIG9nIGJsb2NrcyBhbHJlYWR5IG9uIHRoZSBjYW52YXM/XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCB1aS5pdGVtLmZpbmQoJy5mcmFtZUNvdmVyID4gYnV0dG9uJykuc2l6ZSgpID4gMCApIHsvL3JlLW9yZGVyaW5nIG9mIGJsb2NrcyBvbiBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL25vIG5lZWQgdG8gY3JlYXRlIGEgbmV3IGJsb2NrIG9iamVjdCwgd2Ugc2ltcGx5IG5lZWQgdG8gbWFrZSBzdXJlIHRoZSBwb3NpdGlvbiBvZiB0aGUgZXhpc3RpbmcgYmxvY2sgaW4gdGhlIFNpdGUgb2JqZWN0XG4gICAgICAgICAgICAgICAgICAgICAgICAvL2lzIGNoYW5nZWQgdG8gcmVmbGVjdCB0aGUgbmV3IHBvc2l0aW9uIG9mIHRoZSBibG9jayBvbiB0aCBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZnJhbWVJRCA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignaWQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdQb3MgPSB1aS5pdGVtLmluZGV4KCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnNldFBvc2l0aW9uKGZyYW1lSUQsIG5ld1Bvcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7Ly9uZXcgYmxvY2sgb24gY2FudmFzXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vbmV3IGJsb2NrICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrID0gbmV3IEJsb2NrKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2sucGxhY2VPbkNhbnZhcyh1aSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uIChldmVudCwgdWkpIHtcblxuICAgICAgICAgICAgICAgIHNpdGUubW92ZU1vZGUoJ29uJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCB1aS5pdGVtLmZpbmQoJy5mcmFtZUNvdmVyJykuc2l6ZSgpICE9PSAwICkge1xuICAgICAgICAgICAgICAgICAgICBidWlsZGVyVUkuZnJhbWVDb250ZW50cyA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5odG1sKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgb3ZlcjogZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnI3N0YXJ0JykuaGlkZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgXG4gICAgICAgIC8vYWRkIHRvIHRoZSBwYWdlcyBtZW51XG4gICAgICAgIHRoaXMubWVudUl0ZW0gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMSScpO1xuICAgICAgICB0aGlzLm1lbnVJdGVtLmlubmVySFRNTCA9IHRoaXMucGFnZU1lbnVUZW1wbGF0ZTtcbiAgICAgICAgXG4gICAgICAgICQodGhpcy5tZW51SXRlbSkuZmluZCgnYTpmaXJzdCcpLnRleHQocGFnZU5hbWUpLmF0dHIoJ2hyZWYnLCAnI3BhZ2UnK2NvdW50ZXIpO1xuICAgICAgICBcbiAgICAgICAgdmFyIHRoZUxpbmsgPSAkKHRoaXMubWVudUl0ZW0pLmZpbmQoJ2E6Zmlyc3QnKS5nZXQoMCk7XG4gICAgICAgIFxuICAgICAgICAvL2JpbmQgc29tZSBldmVudHNcbiAgICAgICAgdGhpcy5tZW51SXRlbS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgXG4gICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5maWxlRWRpdCcpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EuZmlsZVNhdmUnKS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLmZpbGVEZWwnKS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgXG4gICAgICAgIC8vYWRkIHRvIHRoZSBwYWdlIGxpbmsgZHJvcGRvd25cbiAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ09QVElPTicpO1xuICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnNldEF0dHJpYnV0ZSgndmFsdWUnLCBwYWdlTmFtZStcIi5odG1sXCIpO1xuICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnRleHQgPSBwYWdlTmFtZTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgYnVpbGRlclVJLmRyb3Bkb3duUGFnZUxpbmtzLmFwcGVuZENoaWxkKCB0aGlzLmxpbmtzRHJvcGRvd25JdGVtICk7XG4gICAgICAgIFxuICAgICAgICBzaXRlLnBhZ2VzTWVudS5hcHBlbmRDaGlsZCh0aGlzLm1lbnVJdGVtKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgfVxuICAgIFxuICAgIFBhZ2UucHJvdG90eXBlLmhhbmRsZUV2ZW50ID0gZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgc3dpdGNoIChldmVudC50eXBlKSB7XG4gICAgICAgICAgICBjYXNlIFwiY2xpY2tcIjogXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaWxlRWRpdCcpICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLmVkaXRQYWdlTmFtZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbGVTYXZlJykgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMudXBkYXRlUGFnZU5hbWVFdmVudChldmVudC50YXJnZXQpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZmlsZURlbCcpICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRoZVBhZ2UgPSB0aGlzO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZVBhZ2UpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZVBhZ2UpLm9mZignY2xpY2snLCAnI2RlbGV0ZVBhZ2VDb25maXJtJykub24oJ2NsaWNrJywgJyNkZWxldGVQYWdlQ29uZmlybScsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVQYWdlLmRlbGV0ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZVBhZ2UpLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgfVxuICAgIH07XG5cblxuICAgIC8qXG4gICAgICAgIEJsb2NrIGNvbnN0cnVjdG9yXG4gICAgKi9cbiAgICBmdW5jdGlvbiBCbG9jayAoKSB7XG4gICAgICAgIFxuICAgICAgICB0aGlzLmZyYW1lSUQgPSAwO1xuICAgICAgICB0aGlzLmxvYWRlZCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnNhbmRib3ggPSBmYWxzZTtcbiAgICAgICAgdGhpcy5zYW5kYm94X2xvYWRlciA9ICcnO1xuICAgICAgICB0aGlzLnN0YXR1cyA9ICcnOy8vJycsICdjaGFuZ2VkJyBvciAnbmV3J1xuICAgICAgICB0aGlzLmdsb2JhbCA9IGZhbHNlO1xuICAgICAgICB0aGlzLm9yaWdpbmFsVXJsID0gJyc7XG4gICAgICAgIFxuICAgICAgICB0aGlzLnBhcmVudExJID0ge307XG4gICAgICAgIHRoaXMuZnJhbWVDb3ZlciA9IHt9O1xuICAgICAgICB0aGlzLmZyYW1lID0ge307XG4gICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudCA9IHt9O1xuICAgICAgICB0aGlzLmZyYW1lSGVpZ2h0ID0gMDtcbiAgICAgICAgXG4gICAgICAgIHRoaXMuYW5ub3QgPSB7fTtcbiAgICAgICAgdGhpcy5hbm5vdFRpbWVvdXQgPSB7fTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjcmVhdGVzIHRoZSBwYXJlbnQgY29udGFpbmVyIChMSSlcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jcmVhdGVQYXJlbnRMSSA9IGZ1bmN0aW9uKGhlaWdodCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnTEknKTtcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuc2V0QXR0cmlidXRlKCdjbGFzcycsICdlbGVtZW50Jyk7XG4gICAgICAgICAgICAvL3RoaXMucGFyZW50TEkuc2V0QXR0cmlidXRlKCdzdHlsZScsICdoZWlnaHQ6ICcraGVpZ2h0KydweCcpO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY3JlYXRlcyB0aGUgaWZyYW1lIG9uIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jcmVhdGVGcmFtZSA9IGZ1bmN0aW9uKGZyYW1lKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdJRlJBTUUnKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdmcmFtZWJvcmRlcicsIDApO1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ3Njcm9sbGluZycsIDApO1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ3NyYycsIGZyYW1lLnNyYyk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnZGF0YS1vcmlnaW5hbHVybCcsIGZyYW1lLmZyYW1lc19vcmlnaW5hbF91cmwpO1xuICAgICAgICAgICAgdGhpcy5vcmlnaW5hbFVybCA9IGZyYW1lLmZyYW1lc19vcmlnaW5hbF91cmw7XG4gICAgICAgICAgICAvL3RoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLWhlaWdodCcsIGZyYW1lLmZyYW1lc19oZWlnaHQpO1xuICAgICAgICAgICAgLy90aGlzLmZyYW1lSGVpZ2h0ID0gZnJhbWUuZnJhbWVzX2hlaWdodDtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmZyYW1lKS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NhbmRib3g/XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94ICE9PSBmYWxzZSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnZGF0YS1sb2FkZXJmdW5jdGlvbicsIHRoaXMuc2FuZGJveF9sb2FkZXIpO1xuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLXNhbmRib3gnLCB0aGlzLnNhbmRib3gpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVjcmVhdGUgdGhlIHNhbmRib3hlZCBpZnJhbWUgZWxzZXdoZXJlXG4gICAgICAgICAgICAgICAgdmFyIHNhbmRib3hlZEZyYW1lID0gJCgnPGlmcmFtZSBzcmM9XCInK2ZyYW1lLnNyYysnXCIgaWQ9XCInK3RoaXMuc2FuZGJveCsnXCIgc2FuZGJveD1cImFsbG93LXNhbWUtb3JpZ2luXCI+PC9pZnJhbWU+Jyk7XG4gICAgICAgICAgICAgICAgJCgnI3NhbmRib3hlcycpLmFwcGVuZCggc2FuZGJveGVkRnJhbWUgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpbnNlcnQgdGhlIGlmcmFtZSBpbnRvIHRoZSBET00gb24gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmluc2VydEJsb2NrSW50b0RvbSA9IGZ1bmN0aW9uKHRoZVVMKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuYXBwZW5kQ2hpbGQodGhpcy5mcmFtZSk7XG4gICAgICAgICAgICB0aGVVTC5hcHBlbmRDaGlsZCggdGhpcy5wYXJlbnRMSSApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmZyYW1lLmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCB0aGlzLCBmYWxzZSk7XG5cbiAgICAgICAgICAgIGJ1aWxkZXJVSS5jYW52YXNMb2FkaW5nKCdvbicpO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHNldHMgdGhlIGZyYW1lIGRvY3VtZW50IGZvciB0aGUgYmxvY2sncyBpZnJhbWVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zZXRGcmFtZURvY3VtZW50ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2V0IHRoZSBmcmFtZSBkb2N1bWVudCBhcyB3ZWxsXG4gICAgICAgICAgICBpZiggdGhpcy5mcmFtZS5jb250ZW50RG9jdW1lbnQgKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50ID0gdGhpcy5mcmFtZS5jb250ZW50RG9jdW1lbnQ7ICAgXG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudCA9IHRoaXMuZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy90aGlzLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNyZWF0ZXMgdGhlIGZyYW1lIGNvdmVyIGFuZCBibG9jayBhY3Rpb24gYnV0dG9uXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY3JlYXRlRnJhbWVDb3ZlciA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1aWxkIHRoZSBmcmFtZSBjb3ZlciBhbmQgYmxvY2sgYWN0aW9uIGJ1dHRvbnNcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3ZlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmNsYXNzTGlzdC5hZGQoJ2ZyYW1lQ292ZXInKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5jbGFzc0xpc3QuYWRkKCdmcmVzaCcpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBkZWxCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIGRlbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gZGVsZXRlQmxvY2snKTtcbiAgICAgICAgICAgIGRlbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICBkZWxCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZnVpLXRyYXNoXCI+PC9pPiA8c3Bhbj5yZW1vdmU8L3NwYW4+JztcbiAgICAgICAgICAgIGRlbEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcmVzZXRCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnYnRuIGJ0bi1pbnZlcnNlIGJ0bi1zbSByZXNldEJsb2NrJyk7XG4gICAgICAgICAgICByZXNldEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICByZXNldEJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmYSBmYS1yZWZyZXNoXCI+PC9pPiA8c3Bhbj5yZXNldDwvc3Bhbj4nO1xuICAgICAgICAgICAgcmVzZXRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGh0bWxCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIGh0bWxCdXR0b24uc2V0QXR0cmlidXRlKCdjbGFzcycsICdidG4gYnRuLWludmVyc2UgYnRuLXNtIGh0bWxCbG9jaycpO1xuICAgICAgICAgICAgaHRtbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICBodG1sQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLWNvZGVcIj48L2k+IDxzcGFuPnNvdXJjZTwvc3Bhbj4nO1xuICAgICAgICAgICAgaHRtbEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcblxuICAgICAgICAgICAgdmFyIGRyYWdCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIGRyYWdCdXR0b24uc2V0QXR0cmlidXRlKCdjbGFzcycsICdidG4gYnRuLWludmVyc2UgYnRuLXNtIGRyYWdCbG9jaycpO1xuICAgICAgICAgICAgZHJhZ0J1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLWFycm93c1wiPjwvaT4gPHNwYW4+TW92ZTwvc3Bhbj4nO1xuICAgICAgICAgICAgZHJhZ0J1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcblxuICAgICAgICAgICAgdmFyIGdsb2JhbExhYmVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnTEFCRUwnKTtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmNsYXNzTGlzdC5hZGQoJ2NoZWNrYm94Jyk7XG4gICAgICAgICAgICBnbG9iYWxMYWJlbC5jbGFzc0xpc3QuYWRkKCdwcmltYXJ5Jyk7XG4gICAgICAgICAgICB2YXIgZ2xvYmFsQ2hlY2tib3ggPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdJTlBVVCcpO1xuICAgICAgICAgICAgZ2xvYmFsQ2hlY2tib3gudHlwZSA9ICdjaGVja2JveCc7XG4gICAgICAgICAgICBnbG9iYWxDaGVja2JveC5zZXRBdHRyaWJ1dGUoJ2RhdGEtdG9nZ2xlJywgJ2NoZWNrYm94Jyk7XG4gICAgICAgICAgICBnbG9iYWxDaGVja2JveC5jaGVja2VkID0gdGhpcy5nbG9iYWw7XG4gICAgICAgICAgICBnbG9iYWxMYWJlbC5hcHBlbmRDaGlsZChnbG9iYWxDaGVja2JveCk7XG4gICAgICAgICAgICB2YXIgZ2xvYmFsVGV4dCA9IGRvY3VtZW50LmNyZWF0ZVRleHROb2RlKCdHbG9iYWwnKTtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmFwcGVuZENoaWxkKGdsb2JhbFRleHQpO1xuXG4gICAgICAgICAgICB2YXIgdHJpZ2dlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgICAgIHRyaWdnZXIuY2xhc3NMaXN0LmFkZCgnZnVpLWdlYXInKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQoZGVsQnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChyZXNldEJ1dHRvbik7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQoaHRtbEJ1dHRvbik7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQoZHJhZ0J1dHRvbik7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQoZ2xvYmFsTGFiZWwpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKHRyaWdnZXIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCh0aGlzLmZyYW1lQ292ZXIpO1xuXG4gICAgICAgICAgICB2YXIgdGhlQmxvY2sgPSB0aGlzO1xuXG4gICAgICAgICAgICAkKGdsb2JhbENoZWNrYm94KS5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUpIHtcblxuICAgICAgICAgICAgICAgIHRoZUJsb2NrLnRvZ2dsZUdsb2JhbChlKTtcblxuICAgICAgICAgICAgfSkucmFkaW9jaGVjaygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy50b2dnbGVHbG9iYWwgPSBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICBpZiAoIGUuY3VycmVudFRhcmdldC5jaGVja2VkICkgdGhpcy5nbG9iYWwgPSB0cnVlO1xuICAgICAgICAgICAgZWxzZSB0aGlzLmdsb2JhbCA9IGZhbHNlO1xuXG4gICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIGNvbnNvbGUubG9nKHRoaXMpO1xuXG4gICAgICAgIH07XG5cbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYXV0b21hdGljYWxseSBjb3JyZWN0cyB0aGUgaGVpZ2h0IG9mIHRoZSBibG9jaydzIGlmcmFtZSBkZXBlbmRpbmcgb24gaXRzIGNvbnRlbnRcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5oZWlnaHRBZGp1c3RtZW50ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmICggT2JqZWN0LmtleXModGhpcy5mcmFtZURvY3VtZW50KS5sZW5ndGggIT09IDAgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgaGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICBib2R5SGVpZ2h0ID0gdGhpcy5mcmFtZURvY3VtZW50LmJvZHkub2Zmc2V0SGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICBwYWdlQ29udGFpbmVySGVpZ2h0ID0gdGhpcy5mcmFtZURvY3VtZW50LmJvZHkucXVlcnlTZWxlY3RvciggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkub2Zmc2V0SGVpZ2h0O1xuXG4gICAgICAgICAgICAgICAgaWYgKCBib2R5SGVpZ2h0ID4gcGFnZUNvbnRhaW5lckhlaWdodCAmJiAhdGhpcy5mcmFtZURvY3VtZW50LmJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCBiQ29uZmlnLmJvZHlQYWRkaW5nQ2xhc3MgKSApIGhlaWdodCA9IHBhZ2VDb250YWluZXJIZWlnaHQ7XG4gICAgICAgICAgICAgICAgZWxzZSBoZWlnaHQgPSBib2R5SGVpZ2h0O1xuXG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZS5zdHlsZS5oZWlnaHQgPSBoZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgICAgIHRoaXMucGFyZW50TEkuc3R5bGUuaGVpZ2h0ID0gaGVpZ2h0K1wicHhcIjtcbiAgICAgICAgICAgICAgICAvL3RoaXMuZnJhbWVDb3Zlci5zdHlsZS5oZWlnaHQgPSBoZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWVIZWlnaHQgPSBoZWlnaHQ7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGVzIGEgYmxvY2tcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5kZWxldGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgZnJvbSBET00vY2FudmFzIHdpdGggYSBuaWNlIGFuaW1hdGlvblxuICAgICAgICAgICAgJCh0aGlzLmZyYW1lLnBhcmVudE5vZGUpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UuaXNFbXB0eSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVtb3ZlIGZyb20gYmxvY2tzIGFycmF5IGluIHRoZSBhY3RpdmUgcGFnZVxuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLmRlbGV0ZUJsb2NrKHRoaXMpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NhbmJveFxuICAgICAgICAgICAgaWYoIHRoaXMuc2FuYmRveCApIHtcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggdGhpcy5zYW5kYm94ICkucmVtb3ZlKCk7ICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZWxlbWVudCB3YXMgZGVsZXRlZCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHJlc2V0cyBhIGJsb2NrIHRvIGl0J3Mgb3JpZ25hbCBzdGF0ZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnJlc2V0ID0gZnVuY3Rpb24gKGZpcmVFdmVudCkge1xuXG4gICAgICAgICAgICBpZiAoIHR5cGVvZiBmaXJlRXZlbnQgPT09ICd1bmRlZmluZWQnKSBmaXJlRXZlbnQgPSB0cnVlO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3Jlc2V0IGZyYW1lIGJ5IHJlbG9hZGluZyBpdFxuICAgICAgICAgICAgdGhpcy5mcmFtZS5jb250ZW50V2luZG93LmxvY2F0aW9uID0gdGhpcy5mcmFtZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtb3JpZ2luYWx1cmwnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zYW5kYm94P1xuICAgICAgICAgICAgaWYoIHRoaXMuc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveEZyYW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5zYW5kYm94KS5jb250ZW50V2luZG93LmxvY2F0aW9uLnJlbG9hZCgpOyAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZWxlbWVudCB3YXMgZGVsZXRlZCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgYnVpbGRlclVJLmNhbnZhc0xvYWRpbmcoJ29uJyk7XG5cbiAgICAgICAgICAgIGlmICggZmlyZUV2ZW50ICkgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCB0aGlzLCAncmVsb2FkJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgbGF1bmNoZXMgdGhlIHNvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNvdXJjZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2hpZGUgdGhlIGlmcmFtZVxuICAgICAgICAgICAgdGhpcy5mcmFtZS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Rpc2FibGUgc29ydGFibGUgb24gdGhlIHBhcmVudExJXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkucGFyZW50Tm9kZSkuc29ydGFibGUoJ2Rpc2FibGUnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9idWlsdCBlZGl0b3IgZWxlbWVudFxuICAgICAgICAgICAgdmFyIHRoZUVkaXRvciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuICAgICAgICAgICAgdGhlRWRpdG9yLmNsYXNzTGlzdC5hZGQoJ2FjZUVkaXRvcicpO1xuICAgICAgICAgICAgJCh0aGVFZGl0b3IpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuYXBwZW5kQ2hpbGQodGhlRWRpdG9yKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9idWlsZCBhbmQgYXBwZW5kIGVycm9yIGRyYXdlclxuICAgICAgICAgICAgdmFyIG5ld0xJID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnTEknKTtcbiAgICAgICAgICAgIHZhciBlcnJvckRyYXdlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuICAgICAgICAgICAgZXJyb3JEcmF3ZXIuY2xhc3NMaXN0LmFkZCgnZXJyb3JEcmF3ZXInKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLnNldEF0dHJpYnV0ZSgnaWQnLCAnZGl2X2Vycm9yRHJhd2VyJyk7XG4gICAgICAgICAgICBlcnJvckRyYXdlci5pbm5lckhUTUwgPSAnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLXhzIGJ0bi1lbWJvc3NlZCBidG4tZGVmYXVsdCBidXR0b25fY2xlYXJFcnJvckRyYXdlclwiIGlkPVwiYnV0dG9uX2NsZWFyRXJyb3JEcmF3ZXJcIj5DTEVBUjwvYnV0dG9uPic7XG4gICAgICAgICAgICBuZXdMSS5hcHBlbmRDaGlsZChlcnJvckRyYXdlcik7XG4gICAgICAgICAgICBlcnJvckRyYXdlci5xdWVyeVNlbGVjdG9yKCdidXR0b24nKS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkucGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUobmV3TEksIHRoaXMucGFyZW50TEkubmV4dFNpYmxpbmcpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBhY2UuY29uZmlnLnNldChcImJhc2VQYXRoXCIsIFwiL2pzL3ZlbmRvci9hY2VcIik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciB0aGVJZCA9IHRoZUVkaXRvci5nZXRBdHRyaWJ1dGUoJ2lkJyk7XG4gICAgICAgICAgICB2YXIgZWRpdG9yID0gYWNlLmVkaXQoIHRoZUlkICk7XG5cbiAgICAgICAgICAgIC8vZWRpdG9yLmdldFNlc3Npb24oKS5zZXRVc2VXcmFwTW9kZSh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2VDb250YWluZXIgPSB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvciggYkNvbmZpZy5wYWdlQ29udGFpbmVyICk7XG4gICAgICAgICAgICB2YXIgdGhlSFRNTCA9IHBhZ2VDb250YWluZXIuaW5uZXJIVE1MO1xuICAgICAgICAgICAgXG5cbiAgICAgICAgICAgIGVkaXRvci5zZXRWYWx1ZSggdGhlSFRNTCApO1xuICAgICAgICAgICAgZWRpdG9yLnNldFRoZW1lKFwiYWNlL3RoZW1lL3R3aWxpZ2h0XCIpO1xuICAgICAgICAgICAgZWRpdG9yLmdldFNlc3Npb24oKS5zZXRNb2RlKFwiYWNlL21vZGUvaHRtbFwiKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGJsb2NrID0gdGhpcztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgXG4gICAgICAgICAgICBlZGl0b3IuZ2V0U2Vzc2lvbigpLm9uKFwiY2hhbmdlQW5ub3RhdGlvblwiLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmFubm90ID0gZWRpdG9yLmdldFNlc3Npb24oKS5nZXRBbm5vdGF0aW9ucygpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dChibG9jay5hbm5vdFRpbWVvdXQpO1xuXG4gICAgICAgICAgICAgICAgdmFyIHRpbWVvdXRDb3VudDtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggJCgnI2Rpdl9lcnJvckRyYXdlciBwJykuc2l6ZSgpID09PSAwICkge1xuICAgICAgICAgICAgICAgICAgICB0aW1lb3V0Q291bnQgPSBiQ29uZmlnLnNvdXJjZUNvZGVFZGl0U3ludGF4RGVsYXk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdGltZW91dENvdW50ID0gMTAwO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBibG9jay5hbm5vdFRpbWVvdXQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgZm9yICh2YXIga2V5IGluIGJsb2NrLmFubm90KXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoYmxvY2suYW5ub3QuaGFzT3duUHJvcGVydHkoa2V5KSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoIGJsb2NrLmFubm90W2tleV0udGV4dCAhPT0gXCJTdGFydCB0YWcgc2VlbiB3aXRob3V0IHNlZWluZyBhIGRvY3R5cGUgZmlyc3QuIEV4cGVjdGVkIGUuZy4gPCFET0NUWVBFIGh0bWw+LlwiICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3TGluZSA9ICQoJzxwPjwvcD4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld0tleSA9ICQoJzxiPicrYmxvY2suYW5ub3Rba2V5XS50eXBlKyc6IDwvYj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld0luZm8gPSAkKCc8c3Bhbj4gJytibG9jay5hbm5vdFtrZXldLnRleHQgKyBcIm9uIGxpbmUgXCIgKyBcIiA8Yj5cIiArIGJsb2NrLmFubm90W2tleV0ucm93Kyc8L2I+PC9zcGFuPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdMaW5lLmFwcGVuZCggbmV3S2V5ICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0xpbmUuYXBwZW5kKCBuZXdJbmZvICk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjZGl2X2Vycm9yRHJhd2VyJykuYXBwZW5kKCBuZXdMaW5lICk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCAkKCcjZGl2X2Vycm9yRHJhd2VyJykuY3NzKCdkaXNwbGF5JykgPT09ICdub25lJyAmJiAkKCcjZGl2X2Vycm9yRHJhd2VyJykuZmluZCgncCcpLnNpemUoKSA+IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZGl2X2Vycm9yRHJhd2VyJykuc2xpZGVEb3duKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0sIHRpbWVvdXRDb3VudCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1dHRvbnNcbiAgICAgICAgICAgIHZhciBjYW5jZWxCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuLWRhbmdlcicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2VkaXRDYW5jZWxCdXR0b24nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4tc20nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmdWktY3Jvc3NcIj48L2k+IDxzcGFuPkNhbmNlbDwvc3Bhbj4nO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgc2F2ZUJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bicpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4tcHJpbWFyeScpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdlZGl0U2F2ZUJ1dHRvbicpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4tc20nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZnVpLWNoZWNrXCI+PC9pPiA8c3Bhbj5TYXZlPC9zcGFuPic7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYnV0dG9uV3JhcHBlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuICAgICAgICAgICAgYnV0dG9uV3JhcHBlci5jbGFzc0xpc3QuYWRkKCdlZGl0b3JCdXR0b25zJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGJ1dHRvbldyYXBwZXIuYXBwZW5kQ2hpbGQoIGNhbmNlbEJ1dHRvbiApO1xuICAgICAgICAgICAgYnV0dG9uV3JhcHBlci5hcHBlbmRDaGlsZCggc2F2ZUJ1dHRvbiApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmFwcGVuZENoaWxkKCBidXR0b25XcmFwcGVyICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGJ1aWxkZXJVSS5hY2VFZGl0b3JzWyB0aGVJZCBdID0gZWRpdG9yO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNhbmNlbHMgdGhlIGJsb2NrIHNvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNhbmNlbFNvdXJjZUJsb2NrID0gZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIC8vZW5hYmxlIGRyYWdnYWJsZSBvbiB0aGUgTElcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlKS5zb3J0YWJsZSgnZW5hYmxlJyk7XG5cdFx0XG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgZXJyb3JEcmF3ZXJcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5uZXh0U2libGluZykucmVtb3ZlKCk7XG4gICAgICAgIFxuICAgICAgICAgICAgLy9kZWxldGUgdGhlIGVkaXRvclxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuYWNlRWRpdG9yJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAkKHRoaXMuZnJhbWUpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmVkaXRvckJ1dHRvbnMnKSkuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwZGF0ZXMgdGhlIGJsb2NrcyBzb3VyY2UgY29kZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNhdmVTb3VyY2VCbG9jayA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2VuYWJsZSBkcmFnZ2FibGUgb24gdGhlIExJXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkucGFyZW50Tm9kZSkuc29ydGFibGUoJ2VuYWJsZScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgdGhlSWQgPSB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5hY2VFZGl0b3InKS5nZXRBdHRyaWJ1dGUoJ2lkJyk7XG4gICAgICAgICAgICB2YXIgdGhlQ29udGVudCA9IGJ1aWxkZXJVSS5hY2VFZGl0b3JzW3RoZUlkXS5nZXRWYWx1ZSgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgZXJyb3JEcmF3ZXJcbiAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkaXZfZXJyb3JEcmF3ZXInKS5wYXJlbnROb2RlLnJlbW92ZSgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgZWRpdG9yXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5hY2VFZGl0b3InKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy91cGRhdGUgdGhlIGZyYW1lJ3MgY29udGVudFxuICAgICAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmlubmVySFRNTCA9IHRoZUNvbnRlbnQ7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NhbmRib3hlZD9cbiAgICAgICAgICAgIGlmKCB0aGlzLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIHNhbmRib3hGcmFtZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCB0aGlzLnNhbmRib3ggKTtcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveEZyYW1lRG9jdW1lbnQgPSBzYW5kYm94RnJhbWUuY29udGVudERvY3VtZW50IHx8IHNhbmRib3hGcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS50ZW1wRnJhbWUgPSBzYW5kYm94RnJhbWU7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgc2FuZGJveEZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvciggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuaW5uZXJIVE1MID0gdGhlQ29udGVudDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9kbyB3ZSBuZWVkIHRvIGV4ZWN1dGUgYSBsb2FkZXIgZnVuY3Rpb24/XG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuc2FuZGJveF9sb2FkZXIgIT09ICcnICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLypcbiAgICAgICAgICAgICAgICAgICAgdmFyIGNvZGVUb0V4ZWN1dGUgPSBcInNhbmRib3hGcmFtZS5jb250ZW50V2luZG93LlwiK3RoaXMuc2FuZGJveF9sb2FkZXIrXCIoKVwiO1xuICAgICAgICAgICAgICAgICAgICB2YXIgdG1wRnVuYyA9IG5ldyBGdW5jdGlvbihjb2RlVG9FeGVjdXRlKTtcbiAgICAgICAgICAgICAgICAgICAgdG1wRnVuYygpO1xuICAgICAgICAgICAgICAgICAgICAqL1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZWRpdG9yQnV0dG9ucycpKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYWRqdXN0IGhlaWdodCBvZiB0aGUgZnJhbWVcbiAgICAgICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL25ldyBwYWdlIGFkZGVkLCB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Jsb2NrIGhhcyBjaGFuZ2VkXG4gICAgICAgICAgICB0aGlzLnN0YXR1cyA9ICdjaGFuZ2VkJztcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCB0aGlzLCAnY2hhbmdlJyk7XG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0xvYWRlZCcsIHRoaXMpO1xuXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNsZWFycyBvdXQgdGhlIGVycm9yIGRyYXdlclxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNsZWFyRXJyb3JEcmF3ZXIgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBzID0gdGhpcy5wYXJlbnRMSS5uZXh0U2libGluZy5xdWVyeVNlbGVjdG9yQWxsKCdwJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgPSAwOyBpIDwgcHMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgcHNbaV0ucmVtb3ZlKCk7ICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB0b2dnbGVzIHRoZSB2aXNpYmlsaXR5IG9mIHRoaXMgYmxvY2sncyBmcmFtZUNvdmVyXG4gICAgICAgICovXG4gICAgICAgIHRoaXMudG9nZ2xlQ292ZXIgPSBmdW5jdGlvbihvbk9yT2ZmKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCBvbk9yT2ZmID09PSAnT24nICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmZyYW1lQ292ZXInKS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSBpZiggb25Pck9mZiA9PT0gJ09mZicgKSB7XG4gICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZnJhbWVDb3ZlcicpLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcmV0dXJucyB0aGUgZnVsbCBzb3VyY2UgY29kZSBvZiB0aGUgYmxvY2sncyBmcmFtZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmdldFNvdXJjZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgc291cmNlID0gXCI8aHRtbD5cIjtcbiAgICAgICAgICAgIHNvdXJjZSArPSB0aGlzLmZyYW1lRG9jdW1lbnQuaGVhZC5vdXRlckhUTUw7XG4gICAgICAgICAgICBzb3VyY2UgKz0gdGhpcy5mcmFtZURvY3VtZW50LmJvZHkub3V0ZXJIVE1MO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gc291cmNlO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHBsYWNlcyBhIGRyYWdnZWQvZHJvcHBlZCBibG9jayBmcm9tIHRoZSBsZWZ0IHNpZGViYXIgb250byB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIHRoaXMucGxhY2VPbkNhbnZhcyA9IGZ1bmN0aW9uKHVpKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZnJhbWUgZGF0YSwgd2UnbGwgbmVlZCB0aGlzIGJlZm9yZSBtZXNzaW5nIHdpdGggdGhlIGl0ZW0ncyBjb250ZW50IEhUTUxcbiAgICAgICAgICAgIHZhciBmcmFtZURhdGEgPSB7fSwgYXR0cjtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLnNpemUoKSA+IDAgKSB7Ly9pZnJhbWUgdGh1bWJuYWlsXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5zcmMgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ3NyYycpO1xuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfb3JpZ2luYWxfdXJsID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdzcmMnKTtcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX2hlaWdodCA9IHVpLml0ZW0uaGVpZ2h0KCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2FuZGJveGVkIGJsb2NrP1xuICAgICAgICAgICAgICAgIGF0dHIgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ3NhbmRib3gnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2FuZGJveCA9IHNpdGVCdWlsZGVyVXRpbHMuZ2V0UmFuZG9tQXJiaXRyYXJ5KDEwMDAwLCAxMDAwMDAwMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94X2xvYWRlciA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignZGF0YS1sb2FkZXJmdW5jdGlvbicpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7Ly9pbWFnZSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLnNyYyA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1zcmNjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19vcmlnaW5hbF91cmwgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtc3JjYycpO1xuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfaGVpZ2h0ID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLWhlaWdodCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zYW5kYm94ZWQgYmxvY2s/XG4gICAgICAgICAgICAgICAgYXR0ciA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1zYW5kYm94Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgYXR0ciAhPT0gdHlwZW9mIHVuZGVmaW5lZCAmJiBhdHRyICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNhbmRib3ggPSBzaXRlQnVpbGRlclV0aWxzLmdldFJhbmRvbUFyYml0cmFyeSgxMDAwMCwgMTAwMDAwMDAwMCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2FuZGJveF9sb2FkZXIgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtbG9hZGVyZnVuY3Rpb24nKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2NyZWF0ZSB0aGUgbmV3IGJsb2NrIG9iamVjdFxuICAgICAgICAgICAgdGhpcy5mcmFtZUlEID0gMDtcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkgPSB1aS5pdGVtLmdldCgwKTtcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuaW5uZXJIVE1MID0gJyc7XG4gICAgICAgICAgICB0aGlzLnN0YXR1cyA9ICduZXcnO1xuICAgICAgICAgICAgdGhpcy5jcmVhdGVGcmFtZShmcmFtZURhdGEpO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5zdHlsZS5oZWlnaHQgPSB0aGlzLmZyYW1lSGVpZ2h0K1wicHhcIjtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlRnJhbWVDb3ZlcigpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5mcmFtZS5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgdGhpcyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2luc2VydCB0aGUgY3JlYXRlZCBpZnJhbWVcbiAgICAgICAgICAgIHVpLml0ZW0uYXBwZW5kKCQodGhpcy5mcmFtZSkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hZGQgdGhlIGJsb2NrIHRvIHRoZSBjdXJyZW50IHBhZ2VcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5ibG9ja3Muc3BsaWNlKHVpLml0ZW0uaW5kZXgoKSwgMCwgdGhpcyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2N1c3RvbSBldmVudFxuICAgICAgICAgICAgdWkuaXRlbS5maW5kKCdpZnJhbWUnKS50cmlnZ2VyKCdjYW52YXN1cGRhdGVkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kcm9wcGVkIGVsZW1lbnQsIHNvIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcblxuICAgICAgICAvKlxuICAgICAgICAgICAgaW5qZWN0cyBleHRlcm5hbCBKUyAoZGVmaW5lZCBpbiBjb25maWcuanMpIGludG8gdGhlIGJsb2NrXG4gICAgICAgICovXG4gICAgICAgIHRoaXMubG9hZEphdmFzY3JpcHQgPSBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHZhciBpLFxuICAgICAgICAgICAgICAgIG9sZCxcbiAgICAgICAgICAgICAgICBuZXdTY3JpcHQ7XG5cbiAgICAgICAgICAgIC8vcmVtb3ZlIG9sZCBvbmVzXG4gICAgICAgICAgICBvbGQgPSB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnc2NyaXB0LmJ1aWxkZXInKTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCBvbGQubGVuZ3RoOyBpKysgKSBvbGRbaV0ucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgIC8vaW5qZWN0XG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IGJDb25maWcuZXh0ZXJuYWxKUy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBuZXdTY3JpcHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdTQ1JJUFQnKTtcbiAgICAgICAgICAgICAgICBuZXdTY3JpcHQuY2xhc3NMaXN0LmFkZCgnYnVpbGRlcicpO1xuICAgICAgICAgICAgICAgIG5ld1NjcmlwdC5zcmMgPSBiQ29uZmlnLmV4dGVybmFsSlNbaV07XG5cbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvcignYm9keScpLmFwcGVuZENoaWxkKG5ld1NjcmlwdCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIENoZWNrcyBpZiB0aGlzIGJsb2NrIGhhcyBleHRlcm5hbCBzdHlsZXNoZWV0XG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaGFzRXh0ZXJuYWxDU1MgPSBmdW5jdGlvbiAoc3JjKSB7XG5cbiAgICAgICAgICAgIHZhciBleHRlcm5hbENzcyxcbiAgICAgICAgICAgICAgICB4O1xuXG4gICAgICAgICAgICBleHRlcm5hbENzcyA9IHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdsaW5rW2hyZWYqPVwiJyArIHNyYyArICdcIl0nKTtcblxuICAgICAgICAgICAgcmV0dXJuIGV4dGVybmFsQ3NzLmxlbmd0aCAhPT0gMDtcblxuICAgICAgICB9O1xuICAgICAgICBcbiAgICB9XG4gICAgXG4gICAgQmxvY2sucHJvdG90eXBlLmhhbmRsZUV2ZW50ID0gZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgc3dpdGNoIChldmVudC50eXBlKSB7XG4gICAgICAgICAgICBjYXNlIFwibG9hZFwiOiBcbiAgICAgICAgICAgICAgICB0aGlzLnNldEZyYW1lRG9jdW1lbnQoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmxvYWRKYXZhc2NyaXB0KCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzLmZyYW1lQ292ZXIpLnJlbW92ZUNsYXNzKCdmcmVzaCcsIDUwMCk7XG5cbiAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0xvYWRlZCcsIHRoaXMpO1xuXG4gICAgICAgICAgICAgICAgdGhpcy5sb2FkZWQgPSB0cnVlO1xuXG4gICAgICAgICAgICAgICAgYnVpbGRlclVJLmNhbnZhc0xvYWRpbmcoJ29mZicpO1xuXG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBjYXNlIFwiY2xpY2tcIjpcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgdGhlQmxvY2sgPSB0aGlzO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZmlndXJlIG91dCB3aGF0IHRvIGRvIG5leHRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZGVsZXRlQmxvY2snKSB8fCBldmVudC50YXJnZXQucGFyZW50Tm9kZS5jbGFzc0xpc3QuY29udGFpbnMoJ2RlbGV0ZUJsb2NrJykgKSB7Ly9kZWxldGUgdGhpcyBibG9ja1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVCbG9jaykubW9kYWwoJ3Nob3cnKTsgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVCbG9jaykub2ZmKCdjbGljaycsICcjZGVsZXRlQmxvY2tDb25maXJtJykub24oJ2NsaWNrJywgJyNkZWxldGVCbG9ja0NvbmZpcm0nLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suZGVsZXRlKGV2ZW50KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlQmxvY2spLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ3Jlc2V0QmxvY2snKSB8fCBldmVudC50YXJnZXQucGFyZW50Tm9kZS5jbGFzc0xpc3QuY29udGFpbnMoJ3Jlc2V0QmxvY2snKSApIHsvL3Jlc2V0IHRoZSBibG9ja1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxSZXNldEJsb2NrKS5tb2RhbCgnc2hvdycpOyBcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsUmVzZXRCbG9jaykub2ZmKCdjbGljaycsICcjcmVzZXRCbG9ja0NvbmZpcm0nKS5vbignY2xpY2snLCAnI3Jlc2V0QmxvY2tDb25maXJtJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLnJlc2V0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbFJlc2V0QmxvY2spLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2h0bWxCbG9jaycpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnaHRtbEJsb2NrJykgKSB7Ly9zb3VyY2UgY29kZSBlZGl0b3JcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLnNvdXJjZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXRDYW5jZWxCdXR0b24nKSB8fCBldmVudC50YXJnZXQucGFyZW50Tm9kZS5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXRDYW5jZWxCdXR0b24nKSApIHsvL2NhbmNlbCBzb3VyY2UgY29kZSBlZGl0b3JcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLmNhbmNlbFNvdXJjZUJsb2NrKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZWRpdFNhdmVCdXR0b24nKSB8fCBldmVudC50YXJnZXQucGFyZW50Tm9kZS5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXRTYXZlQnV0dG9uJykgKSB7Ly9zYXZlIHNvdXJjZSBjb2RlXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5zYXZlU291cmNlQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdidXR0b25fY2xlYXJFcnJvckRyYXdlcicpICkgey8vY2xlYXIgZXJyb3IgZHJhd2VyXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5jbGVhckVycm9yRHJhd2VyKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgfVxuICAgIH07XG5cblxuICAgIC8qXG4gICAgICAgIFNpdGUgb2JqZWN0IGxpdGVyYWxcbiAgICAqL1xuICAgIC8qanNoaW50IC1XMDAzICovXG4gICAgdmFyIHNpdGUgPSB7XG4gICAgICAgIFxuICAgICAgICBwZW5kaW5nQ2hhbmdlczogZmFsc2UsICAgICAgLy9wZW5kaW5nIGNoYW5nZXMgb3Igbm8/XG4gICAgICAgIHBhZ2VzOiB7fSwgICAgICAgICAgICAgICAgICAvL2FycmF5IGNvbnRhaW5pbmcgYWxsIHBhZ2VzLCBpbmNsdWRpbmcgdGhlIGNoaWxkIGZyYW1lcywgbG9hZGVkIGZyb20gdGhlIHNlcnZlciBvbiBwYWdlIGxvYWRcbiAgICAgICAgaXNfYWRtaW46IDAsICAgICAgICAgICAgICAgIC8vMCBmb3Igbm9uLWFkbWluLCAxIGZvciBhZG1pblxuICAgICAgICBkYXRhOiB7fSwgICAgICAgICAgICAgICAgICAgLy9jb250YWluZXIgZm9yIGFqYXggbG9hZGVkIHNpdGUgZGF0YVxuICAgICAgICBwYWdlc1RvRGVsZXRlOiBbXSwgICAgICAgICAgLy9jb250YWlucyBwYWdlcyB0byBiZSBkZWxldGVkXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIHNpdGVQYWdlczogW10sICAgICAgICAgICAgICAvL3RoaXMgaXMgdGhlIG9ubHkgdmFyIGNvbnRhaW5pbmcgdGhlIHJlY2VudCBjYW52YXMgY29udGVudHNcbiAgICAgICAgXG4gICAgICAgIHNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyOiB7fSwgICAgIC8vY29udGFpbnMgdGhlIHNpdGUgZGF0YSByZWFkeSB0byBiZSBzZW50IHRvIHRoZSBzZXJ2ZXJcbiAgICAgICAgXG4gICAgICAgIGFjdGl2ZVBhZ2U6IHt9LCAgICAgICAgICAgICAvL2hvbGRzIGEgcmVmZXJlbmNlIHRvIHRoZSBwYWdlIGN1cnJlbnRseSBvcGVuIG9uIHRoZSBjYW52YXNcbiAgICAgICAgXG4gICAgICAgIHBhZ2VUaXRsZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VUaXRsZScpLC8vaG9sZHMgdGhlIHBhZ2UgdGl0bGUgb2YgdGhlIGN1cnJlbnQgcGFnZSBvbiB0aGUgY2FudmFzXG4gICAgICAgIFxuICAgICAgICBkaXZDYW52YXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlTGlzdCcpLC8vRElWIGNvbnRhaW5pbmcgYWxsIHBhZ2VzIG9uIHRoZSBjYW52YXNcbiAgICAgICAgXG4gICAgICAgIHBhZ2VzTWVudTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VzJyksIC8vVUwgY29udGFpbmluZyB0aGUgcGFnZXMgbWVudSBpbiB0aGUgc2lkZWJhclxuICAgICAgICAgICAgICAgIFxuICAgICAgICBidXR0b25OZXdQYWdlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWRkUGFnZScpLFxuICAgICAgICBsaU5ld1BhZ2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCduZXdQYWdlTEknKSxcbiAgICAgICAgXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzVGl0bGU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV90aXRsZScpLFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc01ldGFEZXNjcmlwdGlvbjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX21ldGFEZXNjcmlwdGlvbicpLFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc01ldGFLZXl3b3JkczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX21ldGFLZXl3b3JkcycpLFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc0luY2x1ZGVzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfaGVhZGVySW5jbHVkZXMnKSxcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NQYWdlQ3NzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfaGVhZGVyQ3NzJyksXG4gICAgICAgIFxuICAgICAgICBidXR0b25TdWJtaXRQYWdlU2V0dGluZ3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlU2V0dGluZ3NTdWJtaXR0QnV0dG9uJyksXG4gICAgICAgIFxuICAgICAgICBtb2RhbFBhZ2VTZXR0aW5nczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VTZXR0aW5nc01vZGFsJyksXG4gICAgICAgIFxuICAgICAgICBidXR0b25TYXZlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2F2ZVBhZ2UnKSxcbiAgICAgICAgXG4gICAgICAgIG1lc3NhZ2VTdGFydDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N0YXJ0JyksXG4gICAgICAgIGRpdkZyYW1lV3JhcHBlcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZyYW1lV3JhcHBlcicpLFxuICAgICAgICBcbiAgICAgICAgc2tlbGV0b246IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdza2VsZXRvbicpLFxuXHRcdFxuXHRcdGF1dG9TYXZlVGltZXI6IHt9LFxuICAgICAgICBcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQuZ2V0SlNPTihhcHBVSS5zaXRlVXJsK1wic2l0ZXMvc2l0ZURhdGFcIiwgZnVuY3Rpb24oZGF0YSl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIGRhdGEuc2l0ZSAhPT0gdW5kZWZpbmVkICkge1xuICAgICAgICAgICAgICAgICAgICBzaXRlLmRhdGEgPSBkYXRhLnNpdGU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKCBkYXRhLnBhZ2VzICE9PSB1bmRlZmluZWQgKSB7XG4gICAgICAgICAgICAgICAgICAgIHNpdGUucGFnZXMgPSBkYXRhLnBhZ2VzO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzaXRlLmlzX2FkbWluID0gZGF0YS5pc19hZG1pbjtcbiAgICAgICAgICAgICAgICBcblx0XHRcdFx0aWYoICQoJyNwYWdlTGlzdCcpLnNpemUoKSA+IDAgKSB7XG4gICAgICAgICAgICAgICAgXHRidWlsZGVyVUkucG9wdWxhdGVDYW52YXMoKTtcblx0XHRcdFx0fVxuXG4gICAgICAgICAgICAgICAgaWYoIGRhdGEuc2l0ZS52aWV3bW9kZSApIHtcbiAgICAgICAgICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uU2V0TW9kZScsIGRhdGEuc2l0ZS52aWV3bW9kZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZmlyZSBjdXN0b20gZXZlbnRcbiAgICAgICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignc2l0ZURhdGFMb2FkZWQnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uTmV3UGFnZSkub24oJ2NsaWNrJywgc2l0ZS5uZXdQYWdlKTtcbiAgICAgICAgICAgICQodGhpcy5tb2RhbFBhZ2VTZXR0aW5ncykub24oJ3Nob3cuYnMubW9kYWwnLCBzaXRlLmxvYWRQYWdlU2V0dGluZ3MpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblN1Ym1pdFBhZ2VTZXR0aW5ncykub24oJ2NsaWNrJywgc2l0ZS51cGRhdGVQYWdlU2V0dGluZ3MpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblNhdmUpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCl7c2l0ZS5zYXZlKHRydWUpO30pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2F1dG8gc2F2ZSB0aW1lIFxuICAgICAgICAgICAgdGhpcy5hdXRvU2F2ZVRpbWVyID0gc2V0VGltZW91dChzaXRlLmF1dG9TYXZlLCBiQ29uZmlnLmF1dG9TYXZlVGltZW91dCk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5zdWJzY3JpYmUoJ29uQmxvY2tDaGFuZ2UnLCBmdW5jdGlvbiAoYmxvY2ssIHR5cGUpIHtcblxuICAgICAgICAgICAgICAgIGlmICggYmxvY2suZ2xvYmFsICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGZvciAoIHZhciBpID0gMDsgaSA8IHNpdGUuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBmb3IgKCB2YXIgeSA9IDA7IHkgPCBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3MubGVuZ3RoOyB5ICsrICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0gIT09IGJsb2NrICYmIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5vcmlnaW5hbFVybCA9PT0gYmxvY2sub3JpZ2luYWxVcmwgJiYgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldLmdsb2JhbCApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIHR5cGUgPT09ICdjaGFuZ2UnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0uZnJhbWVEb2N1bWVudC5ib2R5ID0gYmxvY2suZnJhbWVEb2N1bWVudC5ib2R5LmNsb25lTm9kZSh0cnVlKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tMb2FkZWQnLCBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0pO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoIHR5cGUgPT09ICdyZWxvYWQnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0ucmVzZXQoZmFsc2UpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBhdXRvU2F2ZTogZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoc2l0ZS5wZW5kaW5nQ2hhbmdlcykge1xuICAgICAgICAgICAgICAgIHNpdGUuc2F2ZShmYWxzZSk7XG4gICAgICAgICAgICB9XG5cdFx0XHRcblx0XHRcdHdpbmRvdy5jbGVhckludGVydmFsKHRoaXMuYXV0b1NhdmVUaW1lcik7XG4gICAgICAgICAgICB0aGlzLmF1dG9TYXZlVGltZXIgPSBzZXRUaW1lb3V0KHNpdGUuYXV0b1NhdmUsIGJDb25maWcuYXV0b1NhdmVUaW1lb3V0KTtcbiAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIHNldFBlbmRpbmdDaGFuZ2VzOiBmdW5jdGlvbih2YWx1ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBlbmRpbmdDaGFuZ2VzID0gdmFsdWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCB2YWx1ZSA9PT0gdHJ1ZSApIHtcblx0XHRcdFx0XG5cdFx0XHRcdC8vcmVzZXQgdGltZXJcblx0XHRcdFx0d2luZG93LmNsZWFySW50ZXJ2YWwodGhpcy5hdXRvU2F2ZVRpbWVyKTtcbiAgICAgICAgICAgIFx0dGhpcy5hdXRvU2F2ZVRpbWVyID0gc2V0VGltZW91dChzaXRlLmF1dG9TYXZlLCBiQ29uZmlnLmF1dG9TYXZlVGltZW91dCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnI3NhdmVQYWdlIC5iTGFiZWwnKS50ZXh0KFwiU2F2ZSBub3cgKCEpXCIpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBzaXRlLmFjdGl2ZVBhZ2Uuc3RhdHVzICE9PSAnbmV3JyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnN0YXR1cyA9ICdjaGFuZ2VkJztcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuXHRcdFx0XG4gICAgICAgICAgICB9IGVsc2Uge1xuXHRcbiAgICAgICAgICAgICAgICAkKCcjc2F2ZVBhZ2UgLmJMYWJlbCcpLnRleHQoXCJOb3RoaW5nIHRvIHNhdmVcIik7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgIHNpdGUudXBkYXRlUGFnZVN0YXR1cygnJyk7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgIFxuICAgICAgICBzYXZlOiBmdW5jdGlvbihzaG93Q29uZmlybU1vZGFsKSB7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJlZm9yZVNhdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9maXJlIGN1c3RvbSBldmVudFxuICAgICAgICAgICAgJCgnYm9keScpLnRyaWdnZXIoJ2JlZm9yZVNhdmUnKTtcblxuICAgICAgICAgICAgLy9kaXNhYmxlIGJ1dHRvblxuICAgICAgICAgICAgJChcImEjc2F2ZVBhZ2VcIikuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIGFsZXJ0c1xuICAgICAgICAgICAgJCgnI2Vycm9yTW9kYWwgLm1vZGFsLWJvZHkgPiAqLCAjc3VjY2Vzc01vZGFsIC5tb2RhbC1ib2R5ID4gKicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG5cdFxuICAgICAgICAgICAgc2l0ZS5wcmVwRm9yU2F2ZShmYWxzZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBzZXJ2ZXJEYXRhID0ge307XG4gICAgICAgICAgICBzZXJ2ZXJEYXRhLnBhZ2VzID0gdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlcjtcbiAgICAgICAgICAgIGlmKCB0aGlzLnBhZ2VzVG9EZWxldGUubGVuZ3RoID4gMCApIHtcbiAgICAgICAgICAgICAgICBzZXJ2ZXJEYXRhLnRvRGVsZXRlID0gdGhpcy5wYWdlc1RvRGVsZXRlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBzZXJ2ZXJEYXRhLnNpdGVEYXRhID0gdGhpcy5kYXRhO1xuXG4gICAgICAgICAgICAvL3N0b3JlIGN1cnJlbnQgcmVzcG9uc2l2ZSBtb2RlIGFzIHdlbGxcbiAgICAgICAgICAgIHNlcnZlckRhdGEuc2l0ZURhdGEucmVzcG9uc2l2ZU1vZGUgPSBidWlsZGVyVUkuY3VycmVudFJlc3BvbnNpdmVNb2RlO1xuXG4gICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHVybDogYXBwVUkuc2l0ZVVybCtcInNpdGVzL3NhdmVcIixcbiAgICAgICAgICAgICAgICB0eXBlOiBcIlBPU1RcIixcbiAgICAgICAgICAgICAgICBkYXRhVHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICAgICAgZGF0YTogc2VydmVyRGF0YSxcbiAgICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24ocmVzKXtcblx0XG4gICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgJChcImEjc2F2ZVBhZ2VcIikucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFxuICAgICAgICAgICAgICAgIGlmKCByZXMucmVzcG9uc2VDb2RlID09PSAwICkge1xuXHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIGlmKCBzaG93Q29uZmlybU1vZGFsICkge1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNlcnJvck1vZGFsIC5tb2RhbC1ib2R5JykuYXBwZW5kKCAkKHJlcy5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZXJyb3JNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICB9XG5cdFx0XG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCByZXMucmVzcG9uc2VDb2RlID09PSAxICkge1xuXHRcdFxuICAgICAgICAgICAgICAgICAgICBpZiggc2hvd0NvbmZpcm1Nb2RhbCApIHtcblx0XHRcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNzdWNjZXNzTW9kYWwgLm1vZGFsLWJvZHknKS5hcHBlbmQoICQocmVzLnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNzdWNjZXNzTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgfVxuXHRcdFx0XG5cdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgLy9ubyBtb3JlIHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKGZhbHNlKTtcblx0XHRcdFxuXG4gICAgICAgICAgICAgICAgICAgIC8vdXBkYXRlIHJldmlzaW9ucz9cbiAgICAgICAgICAgICAgICAgICAgJCgnYm9keScpLnRyaWdnZXIoJ2NoYW5nZVBhZ2UnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBwcmVwcyB0aGUgc2l0ZSBkYXRhIGJlZm9yZSBzZW5kaW5nIGl0IHRvIHRoZSBzZXJ2ZXJcbiAgICAgICAgKi9cbiAgICAgICAgcHJlcEZvclNhdmU6IGZ1bmN0aW9uKHRlbXBsYXRlKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXIgPSB7fTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHRlbXBsYXRlICkgey8vc2F2aW5nIHRlbXBsYXRlLCBvbmx5IHRoZSBhY3RpdmVQYWdlIGlzIG5lZWRlZFxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXJbdGhpcy5hY3RpdmVQYWdlLm5hbWVdID0gdGhpcy5hY3RpdmVQYWdlLnByZXBGb3JTYXZlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5hY3RpdmVQYWdlLmZ1bGxQYWdlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9IGVsc2Ugey8vcmVndWxhciBzYXZlXG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2ZpbmQgdGhlIHBhZ2VzIHdoaWNoIG5lZWQgdG8gYmUgc2VuZCB0byB0aGUgc2VydmVyXG4gICAgICAgICAgICAgICAgZm9yKCB2YXIgaSA9IDA7IGkgPCB0aGlzLnNpdGVQYWdlcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLnNpdGVQYWdlc1tpXS5zdGF0dXMgIT09ICcnICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyW3RoaXMuc2l0ZVBhZ2VzW2ldLm5hbWVdID0gdGhpcy5zaXRlUGFnZXNbaV0ucHJlcEZvclNhdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2V0cyBhIHBhZ2UgYXMgdGhlIGFjdGl2ZSBvbmVcbiAgICAgICAgKi9cbiAgICAgICAgc2V0QWN0aXZlOiBmdW5jdGlvbihwYWdlKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVmZXJlbmNlIHRvIHRoZSBhY3RpdmUgcGFnZVxuICAgICAgICAgICAgdGhpcy5hY3RpdmVQYWdlID0gcGFnZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9oaWRlIG90aGVyIHBhZ2VzXG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdGhpcy5zaXRlUGFnZXMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnNpdGVQYWdlc1tpXS5wYXJlbnRVTC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnOyAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Rpc3BsYXkgYWN0aXZlIG9uZVxuICAgICAgICAgICAgdGhpcy5hY3RpdmVQYWdlLnBhcmVudFVMLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlLWFjdGl2ZSBhbGwgcGFnZSBtZW51IGl0ZW1zXG4gICAgICAgICovXG4gICAgICAgIGRlQWN0aXZhdGVBbGw6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZXMgPSB0aGlzLnBhZ2VzTWVudS5xdWVyeVNlbGVjdG9yQWxsKCdsaScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpID0gMDsgaSA8IHBhZ2VzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgIHBhZ2VzW2ldLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGFkZHMgYSBuZXcgcGFnZSB0byB0aGUgc2l0ZVxuICAgICAgICAqL1xuICAgICAgICBuZXdQYWdlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5kZUFjdGl2YXRlQWxsKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY3JlYXRlIHRoZSBuZXcgcGFnZSBpbnN0YW5jZVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZURhdGEgPSBbXTtcbiAgICAgICAgICAgIHZhciB0ZW1wID0ge1xuICAgICAgICAgICAgICAgIHBhZ2VzX2lkOiAwXG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgcGFnZURhdGFbMF0gPSB0ZW1wO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgbmV3UGFnZU5hbWUgPSAncGFnZScrKHNpdGUuc2l0ZVBhZ2VzLmxlbmd0aCsxKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIG5ld1BhZ2UgPSBuZXcgUGFnZShuZXdQYWdlTmFtZSwgcGFnZURhdGEsIHNpdGUuc2l0ZVBhZ2VzLmxlbmd0aCsxKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgbmV3UGFnZS5zdGF0dXMgPSAnbmV3JztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgbmV3UGFnZS5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICBuZXdQYWdlLmVkaXRQYWdlTmFtZSgpO1xuICAgICAgICBcbiAgICAgICAgICAgIG5ld1BhZ2UuaXNFbXB0eSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNoZWNrcyBpZiB0aGUgbmFtZSBvZiBhIHBhZ2UgaXMgYWxsb3dlZFxuICAgICAgICAqL1xuICAgICAgICBjaGVja1BhZ2VOYW1lOiBmdW5jdGlvbihwYWdlTmFtZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL21ha2Ugc3VyZSB0aGUgbmFtZSBpcyB1bmlxdWVcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5zaXRlUGFnZXMgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuc2l0ZVBhZ2VzW2ldLm5hbWUgPT09IHBhZ2VOYW1lICYmIHRoaXMuYWN0aXZlUGFnZSAhPT0gdGhpcy5zaXRlUGFnZXNbaV0gKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMucGFnZU5hbWVFcnJvciA9IFwiVGhlIHBhZ2UgbmFtZSBtdXN0IGJlIHVuaXF1ZS5cIjtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH0gICBcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVtb3ZlcyB1bmFsbG93ZWQgY2hhcmFjdGVycyBmcm9tIHRoZSBwYWdlIG5hbWVcbiAgICAgICAgKi9cbiAgICAgICAgcHJlcFBhZ2VOYW1lOiBmdW5jdGlvbihwYWdlTmFtZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBwYWdlTmFtZSA9IHBhZ2VOYW1lLnJlcGxhY2UoJyAnLCAnJyk7XG4gICAgICAgICAgICBwYWdlTmFtZSA9IHBhZ2VOYW1lLnJlcGxhY2UoL1s/KiEufCYjOyQlQFwiPD4oKSssXS9nLCBcIlwiKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgcmV0dXJuIHBhZ2VOYW1lO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHNhdmUgcGFnZSBzZXR0aW5ncyBmb3IgdGhlIGN1cnJlbnQgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVQYWdlU2V0dGluZ3M6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLnRpdGxlID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc1RpdGxlLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5tZXRhX2Rlc2NyaXB0aW9uID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFEZXNjcmlwdGlvbi52YWx1ZTtcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MubWV0YV9rZXl3b3JkcyA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NNZXRhS2V5d29yZHMudmFsdWU7XG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLmhlYWRlcl9pbmNsdWRlcyA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NJbmNsdWRlcy52YWx1ZTtcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MucGFnZV9jc3MgPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzUGFnZUNzcy52YWx1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJChzaXRlLm1vZGFsUGFnZVNldHRpbmdzKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwZGF0ZSBwYWdlIHN0YXR1c2VzXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZVBhZ2VTdGF0dXM6IGZ1bmN0aW9uKHN0YXR1cykge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuc2l0ZVBhZ2VzICkge1xuICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzW2ldLnN0YXR1cyA9IHN0YXR1czsgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIENoZWNrcyBhbGwgdGhlIGJsb2NrcyBpbiB0aGlzIHNpdGUgaGF2ZSBmaW5pc2hlZCBsb2FkaW5nXG4gICAgICAgICovXG4gICAgICAgIGxvYWRlZDogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCB0aGlzLnNpdGVQYWdlcy5sZW5ndGg7IGkrKyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggIXRoaXMuc2l0ZVBhZ2VzW2ldLmxvYWRlZCgpICkgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgTWFrZSBldmVyeSBibG9jayBoYXZlIGFuIG92ZXJsYXkgZHVyaW5nIGRyYWdnaW5nIHRvIHByZXZlbnQgbW91c2UgZXZlbnQgaXNzdWVzXG4gICAgICAgICovXG4gICAgICAgIG1vdmVNb2RlOiBmdW5jdGlvbiAodmFsdWUpIHtcblxuICAgICAgICAgICAgdmFyIGk7XG5cbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgdGhpcy5hY3RpdmVQYWdlLmJsb2Nrcy5sZW5ndGg7IGkrKyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggdmFsdWUgPT09ICdvbicgKSB0aGlzLmFjdGl2ZVBhZ2UuYmxvY2tzW2ldLmZyYW1lQ292ZXIuY2xhc3NMaXN0LmFkZCgnbW92ZScpO1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgKCB2YWx1ZSA9PT0gJ29mZicgKSB0aGlzLmFjdGl2ZVBhZ2UuYmxvY2tzW2ldLmZyYW1lQ292ZXIuY2xhc3NMaXN0LnJlbW92ZSgnbW92ZScpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuICAgICAgICAvKlxuICAgICAgICAgICAgR2V0IGZvcm0gZnJvbSBBRU1cbiAgICAgICAgKi9cbiAgICAgICAgcXVpY2tfbG9hZF9mb3JtOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCQoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2X3JlbW92ZScpLmxlbmd0aCl7XG4gICAgICAgICAgICAgICAgalF1ZXJ5KCcjcGFnZUxpc3QgaWZyYW1lJykuY29udGVudHMoKS5maW5kKCcjdXNlcl9mb3JtX2Rpdl9yZW1vdmUnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYoJCgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuZmluZCgnI3VzZXJfZm9ybV9kaXYnKS5sZW5ndGgpe1xuICAgICAgICAgICAgICAgIGpRdWVyeS5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogXCJwb3N0XCIsXG4gICAgICAgICAgICAgICAgICAgIHVybDogXCIvc2l0ZXMvZmV0Y2hGb3JtXCIsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdmb3JtSUQnOmZvcm1faWRcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdqc29uJyxcbiAgICAgICAgICAgICAgICAgICAgc3VjY2VzczpmdW5jdGlvbihyZXN1bHQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKHJlc3VsdC50eXBlID09PSAnc3VjY2VzcycpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgamhlaWdodCA9IGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuaGVpZ2h0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgamhlaWdodCArPSAxMDA7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgalF1ZXJ5KCcjcGFnZUxpc3QgaWZyYW1lJykuY29udGVudHMoKS5maW5kKCcjdXNlcl9mb3JtX2RpdicpLmh0bWwocmVzdWx0Lmh0bWwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuZmluZCgnI3VzZXJfZm9ybV9kaXZfcmVtb3ZlJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgalF1ZXJ5KCcjcGFnZUxpc3QgaWZyYW1lJywgd2luZG93LnBhcmVudC5kb2N1bWVudCkuaGVpZ2h0KGpoZWlnaHQrJ3B4Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZXJyb3I6IGZ1bmN0aW9uKGVycm9yVGhyb3duKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGVycm9yVGhyb3duKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgXG4gICAgfTtcblxuICAgIGJ1aWxkZXJVSS5pbml0KCk7IHNpdGUuaW5pdCgpO1xuXG4gICAgXG4gICAgLy8qKioqIEVYUE9SVFNcbiAgICBtb2R1bGUuZXhwb3J0cy5zaXRlID0gc2l0ZTtcbiAgICBtb2R1bGUuZXhwb3J0cy5idWlsZGVyVUkgPSBidWlsZGVyVUk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcbiAgICBcInVzZSBzdHJpY3RcIjtcblxuICAgIHZhciBzaXRlQnVpbGRlciA9IHJlcXVpcmUoJy4vYnVpbGRlci5qcycpO1xuXG4gICAgLypcbiAgICAgICAgY29uc3RydWN0b3IgZnVuY3Rpb24gZm9yIEVsZW1lbnRcbiAgICAqL1xuICAgIG1vZHVsZS5leHBvcnRzLkVsZW1lbnQgPSBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWw7XG4gICAgICAgIHRoaXMuc2FuZGJveCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnBhcmVudEZyYW1lID0ge307XG4gICAgICAgIHRoaXMucGFyZW50QmxvY2sgPSB7fTsvL3JlZmVyZW5jZSB0byB0aGUgcGFyZW50IGJsb2NrIGVsZW1lbnRcbiAgICAgICAgdGhpcy5lZGl0YWJsZUF0dHJpYnV0ZXMgPSBbXTtcbiAgICAgICAgXG4gICAgICAgIC8vbWFrZSBjdXJyZW50IGVsZW1lbnQgYWN0aXZlL29wZW4gKGJlaW5nIHdvcmtlZCBvbilcbiAgICAgICAgdGhpcy5zZXRPcGVuID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5vZmYoJ21vdXNlZW50ZXIgbW91c2VsZWF2ZSBjbGljaycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5jc3MoeydvdXRsaW5lJzogJzJweCBzb2xpZCByZ2JhKDIzMyw5NCw5NCwwLjUpJywgJ291dGxpbmUtb2Zmc2V0JzonLTJweCcsICdjdXJzb3InOiAncG9pbnRlcid9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLy9zZXRzIHVwIGhvdmVyIGFuZCBjbGljayBldmVudHMsIG1ha2luZyB0aGUgZWxlbWVudCBhY3RpdmUgb24gdGhlIGNhbnZhc1xuICAgICAgICB0aGlzLmFjdGl2YXRlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBlbGVtZW50ID0gdGhpcztcblxuICAgICAgICAgICAgLy9kYXRhIGF0dHJpYnV0ZXMgZm9yIGNvbG9yXG4gICAgICAgICAgICBpZiAoIHRoaXMuZWxlbWVudC50YWdOYW1lID09PSAnQScgKSAkKHRoaXMuZWxlbWVudCkuZGF0YSgnY29sb3InLCBnZXRDb21wdXRlZFN0eWxlKHRoaXMuZWxlbWVudCkuY29sb3IpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkuY3NzKHsnb3V0bGluZSc6ICdub25lJywgJ2N1cnNvcic6ICcnfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5vbignbW91c2VlbnRlcicsIGZ1bmN0aW9uKGUpIHtcblxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcyh7J291dGxpbmUnOiAnMnB4IHNvbGlkIHJnYmEoMjMzLDk0LDk0LDAuNSknLCAnb3V0bGluZS1vZmZzZXQnOiAnLTJweCcsICdjdXJzb3InOiAncG9pbnRlcid9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSkub24oJ21vdXNlbGVhdmUnLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcyh7J291dGxpbmUnOiAnJywgJ2N1cnNvcic6ICcnLCAnb3V0bGluZS1vZmZzZXQnOiAnJ30pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KS5vbignY2xpY2snLCBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZWxlbWVudC5jbGlja0hhbmRsZXIodGhpcyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICB0aGlzLmRlYWN0aXZhdGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLm9mZignbW91c2VlbnRlciBtb3VzZWxlYXZlIGNsaWNrJyk7XG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkuY3NzKHsnb3V0bGluZSc6ICdub25lJywgJ2N1cnNvcic6ICdpbmhlcml0J30pO1xuXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvL3JlbW92ZXMgdGhlIGVsZW1lbnRzIG91dGxpbmVcbiAgICAgICAgdGhpcy5yZW1vdmVPdXRsaW5lID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5jc3MoeydvdXRsaW5lJzogJ25vbmUnLCAnY3Vyc29yJzogJ2luaGVyaXQnfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8vc2V0cyB0aGUgcGFyZW50IGlmcmFtZVxuICAgICAgICB0aGlzLnNldFBhcmVudEZyYW1lID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBkb2MgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudDtcbiAgICAgICAgICAgIHZhciB3ID0gZG9jLmRlZmF1bHRWaWV3IHx8IGRvYy5wYXJlbnRXaW5kb3c7XG4gICAgICAgICAgICB2YXIgZnJhbWVzID0gdy5wYXJlbnQuZG9jdW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2lmcmFtZScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IgKHZhciBpPSBmcmFtZXMubGVuZ3RoOyBpLS0+MDspIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgZnJhbWU9IGZyYW1lc1tpXTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgZD0gZnJhbWUuY29udGVudERvY3VtZW50IHx8IGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgICAgIGlmIChkPT09ZG9jKVxuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRGcmFtZSA9IGZyYW1lO1xuICAgICAgICAgICAgICAgIH0gY2F0Y2goZSkge31cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLy9zZXRzIHRoaXMgZWxlbWVudCdzIHBhcmVudCBibG9jayByZWZlcmVuY2VcbiAgICAgICAgdGhpcy5zZXRQYXJlbnRCbG9jayA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xvb3AgdGhyb3VnaCBhbGwgdGhlIGJsb2NrcyBvbiB0aGUgY2FudmFzXG4gICAgICAgICAgICBmb3IoIHZhciBpID0gMDsgaSA8IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBmb3IoIHZhciB4ID0gMDsgeCA8IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9pZiB0aGUgYmxvY2sncyBmcmFtZSBtYXRjaGVzIHRoaXMgZWxlbWVudCdzIHBhcmVudCBmcmFtZVxuICAgICAgICAgICAgICAgICAgICBpZiggc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3hdLmZyYW1lID09PSB0aGlzLnBhcmVudEZyYW1lICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgLy9jcmVhdGUgYSByZWZlcmVuY2UgdG8gdGhhdCBibG9jayBhbmQgc3RvcmUgaXQgaW4gdGhpcy5wYXJlbnRCbG9ja1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRCbG9jayA9IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t4XTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIHRoaXMuc2V0UGFyZW50RnJhbWUoKTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpcyB0aGlzIGJsb2NrIHNhbmRib3hlZD9cbiAgICAgICAgKi9cbiAgICAgICAgXG4gICAgICAgIGlmKCB0aGlzLnBhcmVudEZyYW1lLmdldEF0dHJpYnV0ZSgnZGF0YS1zYW5kYm94JykgKSB7XG4gICAgICAgICAgICB0aGlzLnNhbmRib3ggPSB0aGlzLnBhcmVudEZyYW1lLmdldEF0dHJpYnV0ZSgnZGF0YS1zYW5kYm94Jyk7ICAgXG4gICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICB9O1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuICAgICAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5wYWdlQ29udGFpbmVyID0gXCIjcGFnZVwiO1xuXG4gICAgbW9kdWxlLmV4cG9ydHMuYm9keVBhZGRpbmdDbGFzcyA9IFwiYlBhZGRpbmdcIjtcbiAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5lZGl0YWJsZUl0ZW1zID0ge1xuICAgICAgICAnc3Bhbi5mYSc6IFsnY29sb3InLCAnZm9udC1zaXplJ10sXG4gICAgICAgICcuYmcuYmcxJzogWydiYWNrZ3JvdW5kLWNvbG9yJ10sXG4gICAgICAgICduYXYgYSc6IFsnY29sb3InLCAnZm9udC13ZWlnaHQnLCAndGV4dC10cmFuc2Zvcm0nXSxcbiAgICAgICAgJ2ltZyc6IFsnYm9yZGVyLXRvcC1sZWZ0LXJhZGl1cycsICdib3JkZXItdG9wLXJpZ2h0LXJhZGl1cycsICdib3JkZXItYm90dG9tLWxlZnQtcmFkaXVzJywgJ2JvcmRlci1ib3R0b20tcmlnaHQtcmFkaXVzJywgJ2JvcmRlci1jb2xvcicsICdib3JkZXItc3R5bGUnLCAnYm9yZGVyLXdpZHRoJ10sXG4gICAgICAgICdoci5kYXNoZWQnOiBbJ2JvcmRlci1jb2xvcicsICdib3JkZXItd2lkdGgnXSxcbiAgICAgICAgJy5kaXZpZGVyID4gc3Bhbic6IFsnY29sb3InLCAnZm9udC1zaXplJ10sXG4gICAgICAgICdoci5zaGFkb3dEb3duJzogWydtYXJnaW4tdG9wJywgJ21hcmdpbi1ib3R0b20nXSxcbiAgICAgICAgJy5mb290ZXIgYSc6IFsnY29sb3InXSxcbiAgICAgICAgJy5zb2NpYWwgYSc6IFsnY29sb3InXSxcbiAgICAgICAgJy5iZy5iZzEsIC5iZy5iZzIsIC5oZWFkZXIxMCwgLmhlYWRlcjExJzogWydiYWNrZ3JvdW5kLWltYWdlJywgJ2JhY2tncm91bmQtY29sb3InXSxcbiAgICAgICAgJy5mcmFtZUNvdmVyJzogW10sXG4gICAgICAgICcuZWRpdENvbnRlbnQnOiBbJ2NvbnRlbnQnLCAnY29sb3InLCAnZm9udC1zaXplJywgJ2JhY2tncm91bmQtY29sb3InLCAnZm9udC1mYW1pbHknXSxcbiAgICAgICAgJ2EuYnRuLCBidXR0b24uYnRuJzogWydib3JkZXItcmFkaXVzJywgJ2ZvbnQtc2l6ZScsICdiYWNrZ3JvdW5kLWNvbG9yJ10sXG4gICAgICAgICcjcHJpY2luZ190YWJsZTIgLnByaWNpbmcyIC5ib3R0b20gbGknOiBbJ2NvbnRlbnQnXVxuICAgIH07XG4gICAgXG4gICAgbW9kdWxlLmV4cG9ydHMuZWRpdGFibGVJdGVtT3B0aW9ucyA9IHtcbiAgICAgICAgJ25hdiBhIDogZm9udC13ZWlnaHQnOiBbJzQwMCcsICc3MDAnXSxcbiAgICAgICAgJ2EuYnRuIDogYm9yZGVyLXJhZGl1cyc6IFsnMHB4JywgJzRweCcsICcxMHB4J10sXG4gICAgICAgICdpbWcgOiBib3JkZXItc3R5bGUnOiBbJ25vbmUnLCAnZG90dGVkJywgJ2Rhc2hlZCcsICdzb2xpZCddLFxuICAgICAgICAnaW1nIDogYm9yZGVyLXdpZHRoJzogWycxcHgnLCAnMnB4JywgJzNweCcsICc0cHgnXSxcbiAgICAgICAgJ2gxLCBoMiwgaDMsIGg0LCBoNSwgcCA6IGZvbnQtZmFtaWx5JzogWydkZWZhdWx0JywgJ0xhdG8nLCAnSGVsdmV0aWNhJywgJ0FyaWFsJywgJ1RpbWVzIE5ldyBSb21hbiddLFxuICAgICAgICAnaDIgOiBmb250LWZhbWlseSc6IFsnZGVmYXVsdCcsICdMYXRvJywgJ0hlbHZldGljYScsICdBcmlhbCcsICdUaW1lcyBOZXcgUm9tYW4nXSxcbiAgICAgICAgJ2gzIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ10sXG4gICAgICAgICdwIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ11cbiAgICB9O1xuXG4gICAgbW9kdWxlLmV4cG9ydHMucmVzcG9uc2l2ZU1vZGVzID0ge1xuICAgICAgICBkZXNrdG9wOiAnOTclJyxcbiAgICAgICAgbW9iaWxlOiAnNDgwcHgnLFxuICAgICAgICB0YWJsZXQ6ICcxMDI0cHgnXG4gICAgfTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmVkaXRhYmxlQ29udGVudCA9IFsnLmVkaXRDb250ZW50JywgJy5uYXZiYXIgYScsICdidXR0b24nLCAnYS5idG4nLCAnLmZvb3RlciBhOm5vdCguZmEpJywgJy50YWJsZVdyYXBwZXInLCAnaDEnLCAnaDInXTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmF1dG9TYXZlVGltZW91dCA9IDMwMDAwMDtcbiAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5zb3VyY2VDb2RlRWRpdFN5bnRheERlbGF5ID0gMTAwMDA7XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5tZWRpdW1Dc3NVcmxzID0gW1xuICAgICAgICAnLy9jZG4uanNkZWxpdnIubmV0L21lZGl1bS1lZGl0b3IvbGF0ZXN0L2Nzcy9tZWRpdW0tZWRpdG9yLm1pbi5jc3MnLFxuICAgICAgICAnL2Nzcy9tZWRpdW0tYm9vdHN0cmFwLmNzcydcbiAgICBdO1xuICAgIG1vZHVsZS5leHBvcnRzLm1lZGl1bUJ1dHRvbnMgPSBbJ2JvbGQnLCAnaXRhbGljJywgJ3VuZGVybGluZScsICdhbmNob3InLCAnb3JkZXJlZGxpc3QnLCAndW5vcmRlcmVkbGlzdCcsICdoMScsICdoMicsICdoMycsICdoNCcsICdyZW1vdmVGb3JtYXQnXTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmV4dGVybmFsSlMgPSBbXG4gICAgICAgICdqcy9idWlsZGVyX2luX2Jsb2NrLmpzJ1xuICAgIF07XG4gICAgICAgICAgICAgICAgICAgIFxufSgpKTsiLCIoZnVuY3Rpb24gKCl7XG5cdFwidXNlIHN0cmljdFwiO1xuXG4gICAgdmFyIGJDb25maWcgPSByZXF1aXJlKCcuL2NvbmZpZy5qcycpO1xuICAgIHZhciBzaXRlQnVpbGRlciA9IHJlcXVpcmUoJy4vYnVpbGRlci5qcycpO1xuICAgIHZhciBlZGl0b3IgPSByZXF1aXJlKCcuL3N0eWxlZWRpdG9yLmpzJykuc3R5bGVlZGl0b3I7XG4gICAgdmFyIGFwcFVJID0gcmVxdWlyZSgnLi91aS5qcycpLmFwcFVJO1xuXG4gICAgdmFyIGltYWdlTGlicmFyeSA9IHtcbiAgICAgICAgXG4gICAgICAgIGltYWdlTW9kYWw6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbWFnZU1vZGFsJyksXG4gICAgICAgIGlucHV0SW1hZ2VVcGxvYWQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbWFnZUZpbGUnKSxcbiAgICAgICAgYnV0dG9uVXBsb2FkSW1hZ2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd1cGxvYWRJbWFnZUJ1dHRvbicpLFxuICAgICAgICBpbWFnZUxpYnJhcnlMaW5rczogZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmltYWdlcyA+IC5pbWFnZSAuYnV0dG9ucyAuYnRuLXByaW1hcnksIC5pbWFnZXMgLmltYWdlV3JhcCA+IGEnKSwvL3VzZWQgaW4gdGhlIGxpYnJhcnksIG91dHNpZGUgdGhlIGJ1aWxkZXIgVUlcbiAgICAgICAgbXlJbWFnZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdteUltYWdlcycpLC8vdXNlZCBpbiB0aGUgaW1hZ2UgbGlicmFyeSwgb3V0c2lkZSB0aGUgYnVpbGRlciBVSVxuICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuaW1hZ2VNb2RhbCkub24oJ3Nob3cuYnMubW9kYWwnLCB0aGlzLmltYWdlTGlicmFyeSk7XG4gICAgICAgICAgICAkKHRoaXMuaW5wdXRJbWFnZVVwbG9hZCkub24oJ2NoYW5nZScsIHRoaXMuaW1hZ2VJbnB1dENoYW5nZSk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uVXBsb2FkSW1hZ2UpLm9uKCdjbGljaycsIHRoaXMudXBsb2FkSW1hZ2UpO1xuICAgICAgICAgICAgJCh0aGlzLmltYWdlTGlicmFyeUxpbmtzKS5vbignY2xpY2snLCB0aGlzLmltYWdlSW5Nb2RhbCk7XG4gICAgICAgICAgICAkKHRoaXMubXlJbWFnZXMpLm9uKCdjbGljaycsICcuYnV0dG9ucyAuYnRuLWRhbmdlcicsIHRoaXMuZGVsZXRlSW1hZ2UpO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGltYWdlIGxpYnJhcnkgbW9kYWxcbiAgICAgICAgKi9cbiAgICAgICAgaW1hZ2VMaWJyYXJ5OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFx0XHRcdFxuICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5vZmYoJ2NsaWNrJywgJy5pbWFnZSBidXR0b24udXNlSW1hZ2UnKTtcblx0XHRcdFxuICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5vbignY2xpY2snLCAnLmltYWdlIGJ1dHRvbi51c2VJbWFnZScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy91cGRhdGUgbGl2ZSBpbWFnZVxuICAgICAgICAgICAgICAgICQoZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignc3JjJywgJCh0aGlzKS5hdHRyKCdkYXRhLXVybCcpKTtcblxuICAgICAgICAgICAgICAgIC8vdXBkYXRlIGltYWdlIFVSTCBmaWVsZFxuICAgICAgICAgICAgICAgICQoJ2lucHV0I2ltYWdlVVJMJykudmFsKCAkKHRoaXMpLmF0dHIoJ2RhdGEtdXJsJykgKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgLy9oaWRlIG1vZGFsXG4gICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5tb2RhbCgnaGlkZScpO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAvL2hlaWdodCBhZGp1c3RtZW50IG9mIHRoZSBpZnJhbWUgaGVpZ2h0QWRqdXN0bWVudFxuXHRcdFx0XHRlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jay5oZWlnaHRBZGp1c3RtZW50KCk7XHRcdFx0XHRcdFx0XHRcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblx0XHRcdFxuICAgICAgICAgICAgICAgICQodGhpcykudW5iaW5kKCdjbGljaycpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpbWFnZSB1cGxvYWQgaW5wdXQgY2hhbmVnIGV2ZW50IGhhbmRsZXJcbiAgICAgICAgKi9cbiAgICAgICAgaW1hZ2VJbnB1dENoYW5nZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKHRoaXMpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAvL25vIGZpbGUsIGRpc2FibGUgc3VibWl0IGJ1dHRvblxuICAgICAgICAgICAgICAgICQoJ2J1dHRvbiN1cGxvYWRJbWFnZUJ1dHRvbicpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvL2dvdCBhIGZpbGUsIGVuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKCdidXR0b24jdXBsb2FkSW1hZ2VCdXR0b24nKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGxvYWQgYW4gaW1hZ2UgdG8gdGhlIGltYWdlIGxpYnJhcnlcbiAgICAgICAgKi9cbiAgICAgICAgdXBsb2FkSW1hZ2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnaW5wdXQjaW1hZ2VGaWxlJykudmFsKCkgIT09ICcnICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIG9sZCBhbGVydHNcbiAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAubW9kYWwtYWxlcnRzID4gKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKCdidXR0b24jdXBsb2FkSW1hZ2VCdXR0b24nKS5hZGRDbGFzcygnZGlzYWJsZScpO1xuXG4gICAgICAgICAgICAgICAgLy9zaG93IGxvYWRlclxuICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5sb2FkZXInKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgZm9ybSA9ICQoJ2Zvcm0jaW1hZ2VVcGxvYWRGb3JtJyk7XG4gICAgICAgICAgICAgICAgdmFyIGZvcm1kYXRhID0gZmFsc2U7XG5cbiAgICAgICAgICAgICAgICBpZiAod2luZG93LkZvcm1EYXRhKXtcbiAgICAgICAgICAgICAgICAgICAgZm9ybWRhdGEgPSBuZXcgRm9ybURhdGEoZm9ybVswXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBmb3JtQWN0aW9uID0gZm9ybS5hdHRyKCdhY3Rpb24nKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmwgOiBmb3JtQWN0aW9uLFxuICAgICAgICAgICAgICAgICAgICBkYXRhIDogZm9ybWRhdGEgPyBmb3JtZGF0YSA6IGZvcm0uc2VyaWFsaXplKCksXG4gICAgICAgICAgICAgICAgICAgIGNhY2hlIDogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnRUeXBlIDogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIHByb2Nlc3NEYXRhIDogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcbiAgICAgICAgICAgICAgICAgICAgdHlwZSA6ICdQT1NUJ1xuICAgICAgICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24ocmV0KXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZW5hYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICAgICAkKCdidXR0b24jdXBsb2FkSW1hZ2VCdXR0b24nKS5hZGRDbGFzcygnZGlzYWJsZScpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9oaWRlIGxvYWRlclxuICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAubG9hZGVyJykuZmFkZU91dCg1MDApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDAgKSB7Ly9lcnJvclxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAubW9kYWwtYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG5cdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vc3VjY2Vzc1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2FwcGVuZCBteSBpbWFnZVxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI215SW1hZ2VzVGFiID4gKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI215SW1hZ2VzVGFiJykuYXBwZW5kKCAkKHJldC5teUltYWdlcykgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5tb2RhbC1hbGVydHMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpeyQoJyNpbWFnZU1vZGFsIC5tb2RhbC1hbGVydHMgPiAqJykuZmFkZU91dCg1MDApO30sIDMwMDApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgYWxlcnQoJ05vIGltYWdlIHNlbGVjdGVkJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkaXNwbGF5cyBpbWFnZSBpbiBtb2RhbFxuICAgICAgICAqL1xuICAgICAgICBpbWFnZUluTW9kYWw6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIFx0XHRcbiAgICBcdFx0dmFyIHRoZVNyYyA9ICQodGhpcykuY2xvc2VzdCgnLmltYWdlJykuZmluZCgnaW1nJykuYXR0cignc3JjJyk7XG4gICAgXHRcdFxuICAgIFx0XHQkKCdpbWcjdGhlUGljJykuYXR0cignc3JjJywgdGhlU3JjKTtcbiAgICBcdFx0XG4gICAgXHRcdCQoJyN2aWV3UGljJykubW9kYWwoJ3Nob3cnKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGVzIGFuIGltYWdlIGZyb20gdGhlIGxpYnJhcnlcbiAgICAgICAgKi9cbiAgICAgICAgZGVsZXRlSW1hZ2U6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIFx0XHRcbiAgICBcdFx0dmFyIHRvRGVsID0gJCh0aGlzKS5jbG9zZXN0KCcuaW1hZ2UnKTtcbiAgICBcdFx0dmFyIHRoZVVSTCA9ICQodGhpcykuYXR0cignZGF0YS1pbWcnKTtcbiAgICBcdFx0XG4gICAgXHRcdCQoJyNkZWxldGVJbWFnZU1vZGFsJykubW9kYWwoJ3Nob3cnKTtcbiAgICBcdFx0XG4gICAgXHRcdCQoJ2J1dHRvbiNkZWxldGVJbWFnZUJ1dHRvbicpLmNsaWNrKGZ1bmN0aW9uKCl7XG4gICAgXHRcdFxuICAgIFx0XHRcdCQodGhpcykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0dmFyIHRoZUJ1dHRvbiA9ICQodGhpcyk7XG4gICAgXHRcdFxuICAgIFx0XHRcdCQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybDogYXBwVUkuc2l0ZVVybCtcImFzc2V0cy9kZWxJbWFnZVwiLFxuICAgIFx0XHRcdFx0ZGF0YToge2ZpbGU6IHRoZVVSTH0sXG4gICAgXHRcdFx0XHR0eXBlOiAncG9zdCdcbiAgICBcdFx0XHR9KS5kb25lKGZ1bmN0aW9uKCl7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XHR0aGVCdXR0b24ucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFx0XHRcbiAgICBcdFx0XHRcdCQoJyNkZWxldGVJbWFnZU1vZGFsJykubW9kYWwoJ2hpZGUnKTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0dG9EZWwuZmFkZU91dCg4MDAsIGZ1bmN0aW9uKCl7XG4gICAgXHRcdFx0XHRcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdCQodGhpcykucmVtb3ZlKCk7XG4gICAgXHRcdFx0XHRcdFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdH0pO1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdH0pO1xuICAgIFx0XHRcbiAgICBcdFx0XG4gICAgXHRcdH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcbiAgICBcbiAgICBpbWFnZUxpYnJhcnkuaW5pdCgpO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKXtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0dmFyIGNhbnZhc0VsZW1lbnQgPSByZXF1aXJlKCcuL2NhbnZhc0VsZW1lbnQuanMnKS5FbGVtZW50O1xuXHR2YXIgYkNvbmZpZyA9IHJlcXVpcmUoJy4vY29uZmlnLmpzJyk7XG5cdHZhciBzaXRlQnVpbGRlciA9IHJlcXVpcmUoJy4vYnVpbGRlci5qcycpO1xuICAgIHZhciBwdWJsaXNoZXIgPSByZXF1aXJlKCcuLi92ZW5kb3IvcHVibGlzaGVyJyk7XG5cbiAgICB2YXIgc3R5bGVlZGl0b3IgPSB7XG5cbiAgICAgICAgYnV0dG9uU2F2ZUNoYW5nZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzYXZlU3R5bGluZycpLFxuICAgICAgICBhY3RpdmVFbGVtZW50OiB7fSwgLy9ob2xkcyB0aGUgZWxlbWVudCBjdXJyZW50eSBiZWluZyBlZGl0ZWRcbiAgICAgICAgYWxsU3R5bGVJdGVtc09uQ2FudmFzOiBbXSxcbiAgICAgICAgX29sZEljb246IFtdLFxuICAgICAgICBzdHlsZUVkaXRvcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N0eWxlRWRpdG9yJyksXG4gICAgICAgIGZvcm1TdHlsZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N0eWxpbmdGb3JtJyksXG4gICAgICAgIGJ1dHRvblJlbW92ZUVsZW1lbnQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVFbGVtZW50Q29uZmlybScpLFxuICAgICAgICBidXR0b25DbG9uZUVsZW1lbnQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjbG9uZUVsZW1lbnRCdXR0b24nKSxcbiAgICAgICAgYnV0dG9uUmVzZXRFbGVtZW50OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncmVzZXRTdHlsZUJ1dHRvbicpLFxuICAgICAgICBzZWxlY3RMaW5rc0luZXJuYWw6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzRHJvcGRvd24nKSxcbiAgICAgICAgc2VsZWN0TGlua3NQYWdlczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VMaW5rc0Ryb3Bkb3duJyksXG4gICAgICAgIHZpZGVvSW5wdXRZb3V0dWJlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgneW91dHViZUlEJyksXG4gICAgICAgIHZpZGVvSW5wdXRWaW1lbzogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3ZpbWVvSUQnKSxcbiAgICAgICAgaW5wdXRDdXN0b21MaW5rOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW50ZXJuYWxMaW5rc0N1c3RvbScpLFxuICAgICAgICBsaW5rSW1hZ2U6IG51bGwsXG4gICAgICAgIGxpbmtJY29uOiBudWxsLFxuICAgICAgICBpbnB1dExpbmtUZXh0OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGlua1RleHQnKSxcbiAgICAgICAgc2VsZWN0SWNvbnM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpY29ucycpLFxuICAgICAgICBidXR0b25EZXRhaWxzQXBwbGllZEhpZGU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZXRhaWxzQXBwbGllZE1lc3NhZ2VIaWRlJyksXG4gICAgICAgIGJ1dHRvbkNsb3NlU3R5bGVFZGl0b3I6IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyNzdHlsZUVkaXRvciA+IGEuY2xvc2UnKSxcbiAgICAgICAgdWxQYWdlTGlzdDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VMaXN0JyksXG4gICAgICAgIHJlc3BvbnNpdmVUb2dnbGU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdyZXNwb25zaXZlVG9nZ2xlJyksXG4gICAgICAgIHRoZVNjcmVlbjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NjcmVlbicpLFxuXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIuc3Vic2NyaWJlKCdjbG9zZVN0eWxlRWRpdG9yJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmNsb3NlU3R5bGVFZGl0b3IoKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIuc3Vic2NyaWJlKCdvbkJsb2NrTG9hZGVkJywgZnVuY3Rpb24gKGJsb2NrKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2V0dXBDYW52YXNFbGVtZW50cyhibG9jayk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnN1YnNjcmliZSgnb25TZXRNb2RlJywgZnVuY3Rpb24gKG1vZGUpIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNwb25zaXZlTW9kZUNoYW5nZShtb2RlKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvL2V2ZW50c1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblNhdmVDaGFuZ2VzKS5vbignY2xpY2snLCB0aGlzLnVwZGF0ZVN0eWxpbmcpO1xuICAgICAgICAgICAgJCh0aGlzLmZvcm1TdHlsZSkub24oJ2ZvY3VzJywgJ2lucHV0JywgdGhpcy5hbmltYXRlU3R5bGVJbnB1dEluKS5vbignYmx1cicsICdpbnB1dCcsIHRoaXMuYW5pbWF0ZVN0eWxlSW5wdXRPdXQpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblJlbW92ZUVsZW1lbnQpLm9uKCdjbGljaycsIHRoaXMuZGVsZXRlRWxlbWVudCk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uQ2xvbmVFbGVtZW50KS5vbignY2xpY2snLCB0aGlzLmNsb25lRWxlbWVudCk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uUmVzZXRFbGVtZW50KS5vbignY2xpY2snLCB0aGlzLnJlc2V0RWxlbWVudCk7XG4gICAgICAgICAgICAkKHRoaXMudmlkZW9JbnB1dFlvdXR1YmUpLm9uKCdmb2N1cycsIGZ1bmN0aW9uKCl7ICQoc3R5bGVlZGl0b3IudmlkZW9JbnB1dFZpbWVvKS52YWwoJycpOyB9KTtcbiAgICAgICAgICAgICQodGhpcy52aWRlb0lucHV0VmltZW8pLm9uKCdmb2N1cycsIGZ1bmN0aW9uKCl7ICQoc3R5bGVlZGl0b3IudmlkZW9JbnB1dFlvdXR1YmUpLnZhbCgnJyk7IH0pO1xuICAgICAgICAgICAgJCh0aGlzLmlucHV0Q3VzdG9tTGluaykub24oJ2ZvY3VzJywgdGhpcy5yZXNldFNlbGVjdEFsbExpbmtzKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25EZXRhaWxzQXBwbGllZEhpZGUpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCl7JCh0aGlzKS5wYXJlbnQoKS5mYWRlT3V0KDUwMCk7fSk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uQ2xvc2VTdHlsZUVkaXRvcikub24oJ2NsaWNrJywgdGhpcy5jbG9zZVN0eWxlRWRpdG9yKTtcbiAgICAgICAgICAgICQodGhpcy5pbnB1dEN1c3RvbUxpbmspLm9uKCdmb2N1cycsIHRoaXMuaW5wdXRDdXN0b21MaW5rRm9jdXMpLm9uKCdibHVyJywgdGhpcy5pbnB1dEN1c3RvbUxpbmtCbHVyKTtcbiAgICAgICAgICAgICQoZG9jdW1lbnQpLm9uKCdtb2RlQ29udGVudCBtb2RlQmxvY2tzJywgJ2JvZHknLCB0aGlzLmRlQWN0aXZhdGVNb2RlKTtcblxuICAgICAgICAgICAgLy9jaG9zZW4gZm9udC1hd2Vzb21lIGRyb3Bkb3duXG4gICAgICAgICAgICAkKHRoaXMuc2VsZWN0SWNvbnMpLmNob3Nlbih7J3NlYXJjaF9jb250YWlucyc6IHRydWV9KTtcblxuICAgICAgICAgICAgLy9jaGVjayBpZiBmb3JtRGF0YSBpcyBzdXBwb3J0ZWRcbiAgICAgICAgICAgIGlmICghd2luZG93LkZvcm1EYXRhKXtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGVGaWxlVXBsb2FkcygpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL2xpc3RlbiBmb3IgdGhlIGJlZm9yZVNhdmUgZXZlbnRcbiAgICAgICAgICAgICQoJ2JvZHknKS5vbignYmVmb3JlU2F2ZScsIHRoaXMuY2xvc2VTdHlsZUVkaXRvcik7XG5cbiAgICAgICAgICAgIC8vcmVzcG9uc2l2ZSB0b2dnbGVcbiAgICAgICAgICAgICQodGhpcy5yZXNwb25zaXZlVG9nZ2xlKS5vbignY2xpY2snLCAnYScsIHRoaXMudG9nZ2xlUmVzcG9uc2l2ZUNsaWNrKTtcblxuICAgICAgICAgICAgLy9zZXQgdGhlIGRlZmF1bHQgcmVzcG9uc2l2ZSBtb2RlXG4gICAgICAgICAgICBzaXRlQnVpbGRlci5idWlsZGVyVUkuY3VycmVudFJlc3BvbnNpdmVNb2RlID0gT2JqZWN0LmtleXMoYkNvbmZpZy5yZXNwb25zaXZlTW9kZXMpWzBdO1xuXG4gICAgICAgIH0sXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEV2ZW50IGhhbmRsZXIgZm9yIHJlc3BvbnNpdmUgbW9kZSBsaW5rc1xuICAgICAgICAqL1xuICAgICAgICB0b2dnbGVSZXNwb25zaXZlQ2xpY2s6IGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzcG9uc2l2ZU1vZGVDaGFuZ2UodGhpcy5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmVzcG9uc2l2ZScpKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIFRvZ2dsZXMgdGhlIHJlc3BvbnNpdmUgbW9kZVxuICAgICAgICAqL1xuICAgICAgICByZXNwb25zaXZlTW9kZUNoYW5nZTogZnVuY3Rpb24gKG1vZGUpIHtcblxuICAgICAgICAgICAgdmFyIGxpbmtzLFxuICAgICAgICAgICAgICAgIGk7XG5cbiAgICAgICAgICAgIC8vVUkgc3R1ZmZcbiAgICAgICAgICAgIGxpbmtzID0gc3R5bGVlZGl0b3IucmVzcG9uc2l2ZVRvZ2dsZS5xdWVyeVNlbGVjdG9yQWxsKCdsaScpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IGxpbmtzLmxlbmd0aDsgaSsrICkgbGlua3NbaV0uY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XG5cbiAgICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbZGF0YS1yZXNwb25zaXZlPVwiJyArIG1vZGUgKyAnXCJdJykucGFyZW50Tm9kZS5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcblxuXG4gICAgICAgICAgICBmb3IgKCB2YXIga2V5IGluIGJDb25maWcucmVzcG9uc2l2ZU1vZGVzICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCBiQ29uZmlnLnJlc3BvbnNpdmVNb2Rlcy5oYXNPd25Qcm9wZXJ0eShrZXkpICkgdGhpcy50aGVTY3JlZW4uY2xhc3NMaXN0LnJlbW92ZShrZXkpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICggYkNvbmZpZy5yZXNwb25zaXZlTW9kZXNbbW9kZV0gKSB7XG5cbiAgICAgICAgICAgICAgICB0aGlzLnRoZVNjcmVlbi5jbGFzc0xpc3QuYWRkKG1vZGUpO1xuICAgICAgICAgICAgICAgICQodGhpcy50aGVTY3JlZW4pLmFuaW1hdGUoe3dpZHRoOiBiQ29uZmlnLnJlc3BvbnNpdmVNb2Rlc1ttb2RlXX0sIDY1MCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAvL2hlaWdodCBhZGp1c3RtZW50XG4gICAgICAgICAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuYWN0aXZlUGFnZS5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuYnVpbGRlclVJLmN1cnJlbnRSZXNwb25zaXZlTW9kZSA9IG1vZGU7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBBY3RpdmF0ZXMgc3R5bGUgZWRpdG9yIG1vZGVcbiAgICAgICAgKi9cbiAgICAgICAgc2V0dXBDYW52YXNFbGVtZW50czogZnVuY3Rpb24oYmxvY2spIHtcblxuICAgICAgICAgICAgaWYgKCBibG9jayA9PT0gdW5kZWZpbmVkICkgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgLy9jcmVhdGUgYW4gb2JqZWN0IGZvciBldmVyeSBlZGl0YWJsZSBlbGVtZW50IG9uIHRoZSBjYW52YXMgYW5kIHNldHVwIGl0J3MgZXZlbnRzXG5cbiAgICAgICAgICAgIGZvciggdmFyIGtleSBpbiBiQ29uZmlnLmVkaXRhYmxlSXRlbXMgKSB7XG5cbiAgICAgICAgICAgICAgICAkKGJsb2NrLmZyYW1lKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciArICcgJysga2V5ICkuZWFjaChmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2V0dXBDYW52YXNFbGVtZW50c09uRWxlbWVudCh0aGlzLCBrZXkpO1xuXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIFNldHMgdXAgY2FudmFzIGVsZW1lbnRzIG9uIGVsZW1lbnRcbiAgICAgICAgKi9cbiAgICAgICAgc2V0dXBDYW52YXNFbGVtZW50c09uRWxlbWVudDogZnVuY3Rpb24gKGVsZW1lbnQsIGtleSkge1xuXG4gICAgICAgICAgICAvL0VsZW1lbnQgb2JqZWN0IGV4dGVudGlvblxuICAgICAgICAgICAgY2FudmFzRWxlbWVudC5wcm90b3R5cGUuY2xpY2tIYW5kbGVyID0gZnVuY3Rpb24oZWwpIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zdHlsZUNsaWNrKHRoaXMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIG5ld0VsZW1lbnQgPSBuZXcgY2FudmFzRWxlbWVudChlbGVtZW50KTtcblxuICAgICAgICAgICAgbmV3RWxlbWVudC5lZGl0YWJsZUF0dHJpYnV0ZXMgPSBiQ29uZmlnLmVkaXRhYmxlSXRlbXNba2V5XTtcbiAgICAgICAgICAgIG5ld0VsZW1lbnQuc2V0UGFyZW50QmxvY2soKTtcbiAgICAgICAgICAgIG5ld0VsZW1lbnQuYWN0aXZhdGUoKTtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWxsU3R5bGVJdGVtc09uQ2FudmFzLnB1c2goIG5ld0VsZW1lbnQgKTtcblxuICAgICAgICAgICAgaWYgKCB0eXBlb2Yga2V5ICE9PSB1bmRlZmluZWQgKSAkKGVsZW1lbnQpLmF0dHIoJ2RhdGEtc2VsZWN0b3InLCBrZXkpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgRXZlbnQgaGFuZGxlciBmb3Igd2hlbiB0aGUgc3R5bGUgZWRpdG9yIGlzIGVudm9rZWQgb24gYW4gaXRlbVxuICAgICAgICAqL1xuICAgICAgICBzdHlsZUNsaWNrOiBmdW5jdGlvbihlbGVtZW50KSB7XG5cbiAgICAgICAgICAgIC8vaWYgd2UgaGF2ZSBhbiBhY3RpdmUgZWxlbWVudCwgbWFrZSBpdCB1bmFjdGl2ZVxuICAgICAgICAgICAgaWYoIE9iamVjdC5rZXlzKHRoaXMuYWN0aXZlRWxlbWVudCkubGVuZ3RoICE9PSAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3RpdmVFbGVtZW50LmFjdGl2YXRlKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vc2V0IHRoZSBhY3RpdmUgZWxlbWVudFxuICAgICAgICAgICAgdGhpcy5hY3RpdmVFbGVtZW50ID0gZWxlbWVudDtcblxuICAgICAgICAgICAgLy91bmJpbmQgaG92ZXIgYW5kIGNsaWNrIGV2ZW50cyBhbmQgbWFrZSB0aGlzIGl0ZW0gYWN0aXZlXG4gICAgICAgICAgICB0aGlzLmFjdGl2ZUVsZW1lbnQuc2V0T3BlbigpO1xuXG4gICAgICAgICAgICB2YXIgdGhlU2VsZWN0b3IgPSAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdkYXRhLXNlbGVjdG9yJyk7XG5cbiAgICAgICAgICAgICQoJyNlZGl0aW5nRWxlbWVudCcpLnRleHQoIHRoZVNlbGVjdG9yICk7XG5cbiAgICAgICAgICAgIC8vYWN0aXZhdGUgZmlyc3QgdGFiXG4gICAgICAgICAgICAkKCcjZGV0YWlsVGFicyBhOmZpcnN0JykuY2xpY2soKTtcblxuICAgICAgICAgICAgLy9oaWRlIGFsbCBieSBkZWZhdWx0XG4gICAgICAgICAgICAkKCd1bCNkZXRhaWxUYWJzIGxpOmd0KDApJykuaGlkZSgpO1xuXG4gICAgICAgICAgICAvL2NvbnRlbnQgZWRpdG9yP1xuICAgICAgICAgICAgZm9yKCB2YXIgaXRlbSBpbiBiQ29uZmlnLmVkaXRhYmxlSXRlbXMgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zLmhhc093blByb3BlcnR5KGl0ZW0pICYmIGl0ZW0gPT09IHRoZVNlbGVjdG9yICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGlmICggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW2l0ZW1dLmluZGV4T2YoJ2NvbnRlbnQnKSAhPT0gLTEgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIC8vZWRpdCBjb250ZW50XG4gICAgICAgICAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25DbGlja0NvbnRlbnQnLCBlbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL3doYXQgYXJlIHdlIGRlYWxpbmcgd2l0aD9cbiAgICAgICAgICAgIGlmKCAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyB8fCAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHtcblxuICAgICAgICAgICAgICAgIHRoaXMuZWRpdExpbmsodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICB9XG5cblx0XHRcdGlmKCAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgPT09ICdJTUcnICl7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmVkaXRJbWFnZSh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgIH1cblxuXHRcdFx0aWYoICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2RhdGEtdHlwZScpID09PSAndmlkZW8nICkge1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lZGl0VmlkZW8odGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICB9XG5cblx0XHRcdGlmKCAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5oYXNDbGFzcygnZmEnKSApIHtcblxuICAgICAgICAgICAgICAgIHRoaXMuZWRpdEljb24odGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vbG9hZCB0aGUgYXR0cmlidXRlc1xuICAgICAgICAgICAgdGhpcy5idWlsZGVTdHlsZUVsZW1lbnRzKHRoZVNlbGVjdG9yKTtcblxuICAgICAgICAgICAgLy9vcGVuIHNpZGUgcGFuZWxcbiAgICAgICAgICAgIHRoaXMudG9nZ2xlU2lkZVBhbmVsKCdvcGVuJyk7XG5cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGR5bmFtaWNhbGx5IGdlbmVyYXRlcyB0aGUgZm9ybSBmaWVsZHMgZm9yIGVkaXRpbmcgYW4gZWxlbWVudHMgc3R5bGUgYXR0cmlidXRlc1xuICAgICAgICAqL1xuICAgICAgICBidWlsZGVTdHlsZUVsZW1lbnRzOiBmdW5jdGlvbih0aGVTZWxlY3Rvcikge1xuXG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgb2xkIG9uZXMgZmlyc3RcbiAgICAgICAgICAgICQoJyNzdHlsZUVsZW1lbnRzID4gKjpub3QoI3N0eWxlRWxUZW1wbGF0ZSknKS5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgZm9yKCB2YXIgeD0wOyB4PGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl0ubGVuZ3RoOyB4KysgKSB7XG5cbiAgICAgICAgICAgICAgICAvL2NyZWF0ZSBzdHlsZSBlbGVtZW50c1xuICAgICAgICAgICAgICAgIHZhciBuZXdTdHlsZUVsID0gJCgnI3N0eWxlRWxUZW1wbGF0ZScpLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5hdHRyKCdpZCcsICcnKTtcbiAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmZpbmQoJy5jb250cm9sLWxhYmVsJykudGV4dCggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XStcIjpcIiApO1xuXG4gICAgICAgICAgICAgICAgaWYoIHRoZVNlbGVjdG9yICsgXCIgOiBcIiArIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gaW4gYkNvbmZpZy5lZGl0YWJsZUl0ZW1PcHRpb25zKSB7Ly93ZSd2ZSBnb3QgYSBkcm9wZG93biBpbnN0ZWFkIG9mIG9wZW4gdGV4dCBpbnB1dFxuXG4gICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuZmluZCgnaW5wdXQnKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgICAgICAgICB2YXIgbmV3RHJvcERvd24gPSAkKCc8c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sIHNlbGVjdCBzZWxlY3QtcHJpbWFyeSBidG4tYmxvY2sgc2VsZWN0LXNtXCI+PC9zZWxlY3Q+Jyk7XG4gICAgICAgICAgICAgICAgICAgIG5ld0Ryb3BEb3duLmF0dHIoJ25hbWUnLCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdKTtcblxuXG4gICAgICAgICAgICAgICAgICAgIGZvciggdmFyIHo9MDsgejxiQ29uZmlnLmVkaXRhYmxlSXRlbU9wdGlvbnNbIHRoZVNlbGVjdG9yK1wiIDogXCIrYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSBdLmxlbmd0aDsgeisrICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3T3B0aW9uID0gJCgnPG9wdGlvbiB2YWx1ZT1cIicrYkNvbmZpZy5lZGl0YWJsZUl0ZW1PcHRpb25zW3RoZVNlbGVjdG9yK1wiIDogXCIrYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XV1bel0rJ1wiPicrYkNvbmZpZy5lZGl0YWJsZUl0ZW1PcHRpb25zW3RoZVNlbGVjdG9yK1wiIDogXCIrYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XV1bel0rJzwvb3B0aW9uPicpO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCBiQ29uZmlnLmVkaXRhYmxlSXRlbU9wdGlvbnNbdGhlU2VsZWN0b3IrXCIgOiBcIitiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdXVt6XSA9PT0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vY3VycmVudCB2YWx1ZSwgbWFya2VkIGFzIHNlbGVjdGVkXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3T3B0aW9uLmF0dHIoJ3NlbGVjdGVkJywgJ3RydWUnKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdEcm9wRG93bi5hcHBlbmQoIG5ld09wdGlvbiApO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmFwcGVuZCggbmV3RHJvcERvd24gKTtcbiAgICAgICAgICAgICAgICAgICAgbmV3RHJvcERvd24uc2VsZWN0MigpO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmZpbmQoJ2lucHV0JykudmFsKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgKS5hdHRyKCduYW1lJywgYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gPT09ICdiYWNrZ3JvdW5kLWltYWdlJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5maW5kKCdpbnB1dCcpLmJpbmQoJ2ZvY3VzJywgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0aGVJbnB1dCA9ICQodGhpcyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLmltYWdlIGJ1dHRvbi51c2VJbWFnZScpLnVuYmluZCgnY2xpY2snKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm9uKCdjbGljaycsICcuaW1hZ2UgYnV0dG9uLnVzZUltYWdlJywgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCdiYWNrZ3JvdW5kLWltYWdlJywgICd1cmwoXCInKyQodGhpcykuYXR0cignZGF0YS11cmwnKSsnXCIpJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgbGl2ZSBpbWFnZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGVJbnB1dC52YWwoICd1cmwoXCInKyQodGhpcykuYXR0cignZGF0YS11cmwnKSsnXCIpJyApO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vaGlkZSBtb2RhbFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm1vZGFsKCdoaWRlJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdLmluZGV4T2YoXCJjb2xvclwiKSA+IC0xICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICE9PSAndHJhbnNwYXJlbnQnICYmICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSAhPT0gJ25vbmUnICYmICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSAhPT0gJycgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLnZhbCggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5maW5kKCdpbnB1dCcpLnNwZWN0cnVtKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwcmVmZXJyZWRGb3JtYXQ6IFwiaGV4XCIsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2hvd1BhbGV0dGU6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYWxsb3dFbXB0eTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaG93SW5wdXQ6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcGFsZXR0ZTogW1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjMDAwXCIsXCIjNDQ0XCIsXCIjNjY2XCIsXCIjOTk5XCIsXCIjY2NjXCIsXCIjZWVlXCIsXCIjZjNmM2YzXCIsXCIjZmZmXCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjZjAwXCIsXCIjZjkwXCIsXCIjZmYwXCIsXCIjMGYwXCIsXCIjMGZmXCIsXCIjMDBmXCIsXCIjOTBmXCIsXCIjZjBmXCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjZjRjY2NjXCIsXCIjZmNlNWNkXCIsXCIjZmZmMmNjXCIsXCIjZDllYWQzXCIsXCIjZDBlMGUzXCIsXCIjY2ZlMmYzXCIsXCIjZDlkMmU5XCIsXCIjZWFkMWRjXCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjZWE5OTk5XCIsXCIjZjljYjljXCIsXCIjZmZlNTk5XCIsXCIjYjZkN2E4XCIsXCIjYTJjNGM5XCIsXCIjOWZjNWU4XCIsXCIjYjRhN2Q2XCIsXCIjZDVhNmJkXCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjZTA2NjY2XCIsXCIjZjZiMjZiXCIsXCIjZmZkOTY2XCIsXCIjOTNjNDdkXCIsXCIjNzZhNWFmXCIsXCIjNmZhOGRjXCIsXCIjOGU3Y2MzXCIsXCIjYzI3YmEwXCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjYzAwXCIsXCIjZTY5MTM4XCIsXCIjZjFjMjMyXCIsXCIjNmFhODRmXCIsXCIjNDU4MThlXCIsXCIjM2Q4NWM2XCIsXCIjNjc0ZWE3XCIsXCIjYTY0ZDc5XCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjOTAwXCIsXCIjYjQ1ZjA2XCIsXCIjYmY5MDAwXCIsXCIjMzg3NjFkXCIsXCIjMTM0ZjVjXCIsXCIjMGI1Mzk0XCIsXCIjMzUxYzc1XCIsXCIjNzQxYjQ3XCJdLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBbXCIjNjAwXCIsXCIjNzgzZjA0XCIsXCIjN2Y2MDAwXCIsXCIjMjc0ZTEzXCIsXCIjMGMzNDNkXCIsXCIjMDczNzYzXCIsXCIjMjAxMjRkXCIsXCIjNGMxMTMwXCJdXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5jc3MoJ2Rpc3BsYXknLCAnYmxvY2snKTtcblxuICAgICAgICAgICAgICAgICQoJyNzdHlsZUVsZW1lbnRzJykuYXBwZW5kKCBuZXdTdHlsZUVsICk7XG5cbiAgICAgICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3IgZm9ybSNzdHlsaW5nRm9ybScpLmhlaWdodCgnYXV0bycpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBBcHBsaWVzIHVwZGF0ZWQgc3R5bGluZyB0byB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZVN0eWxpbmc6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICB2YXIgZWxlbWVudElELFxuICAgICAgICAgICAgICAgIGxlbmd0aDtcblxuICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yICN0YWIxIC5mb3JtLWdyb3VwOm5vdCgjc3R5bGVFbFRlbXBsYXRlKSBpbnB1dCwgI3N0eWxlRWRpdG9yICN0YWIxIC5mb3JtLWdyb3VwOm5vdCgjc3R5bGVFbFRlbXBsYXRlKSBzZWxlY3QnKS5lYWNoKGZ1bmN0aW9uKCl7XG5cblx0XHRcdFx0aWYoICQodGhpcykuYXR0cignbmFtZScpICE9PSB1bmRlZmluZWQgKSB7XG5cbiAgICAgICAgICAgICAgICBcdCQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoICQodGhpcykuYXR0cignbmFtZScpLCAgJCh0aGlzKS52YWwoKSk7XG5cblx0XHRcdFx0fVxuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5jc3MoICQodGhpcykuYXR0cignbmFtZScpLCAgJCh0aGlzKS52YWwoKSApO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIC8vbGlua3NcbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7XG5cbiAgICAgICAgICAgICAgICAvL2NoYW5nZSB0aGUgaHJlZiBwcm9wP1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5ocmVmID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludGVybmFsTGlua3NDdXN0b20nKS52YWx1ZTtcblxuICAgICAgICAgICAgICAgIGxlbmd0aCA9IHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jaGlsZE5vZGVzLmxlbmd0aDtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2RvZXMgdGhlIGxpbmsgY29udGFpbiBhbiBpbWFnZT9cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IubGlua0ltYWdlICkgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNoaWxkTm9kZXNbbGVuZ3RoLTFdLm5vZGVWYWx1ZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsaW5rVGV4dCcpLnZhbHVlO1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgKCBzdHlsZWVkaXRvci5saW5rSWNvbiApIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jaGlsZE5vZGVzW2xlbmd0aC0xXS5ub2RlVmFsdWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGlua1RleHQnKS52YWx1ZTtcbiAgICAgICAgICAgICAgICBlbHNlIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5pbm5lclRleHQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGlua1RleHQnKS52YWx1ZTtcblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkuYXR0cignaHJlZicsICQoJ2lucHV0I2ludGVybmFsTGlua3NDdXN0b20nKS52YWwoKSk7XG5cblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHtcblxuICAgICAgICAgICAgICAgIC8vY2hhbmdlIHRoZSBocmVmIHByb3A/XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LnBhcmVudE5vZGUuaHJlZiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzQ3VzdG9tJykudmFsdWU7XG5cbiAgICAgICAgICAgICAgICBsZW5ndGggPSBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2hpbGROb2Rlcy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgXG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuXG4gICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLnBhcmVudCgpLmF0dHIoJ2hyZWYnLCAkKCdpbnB1dCNpbnRlcm5hbExpbmtzQ3VzdG9tJykudmFsKCkpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL2ljb25zXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmhhc0NsYXNzKCdmYScpICkge1xuXG4gICAgICAgICAgICAgICAgLy9vdXQgd2l0aCB0aGUgb2xkLCBpbiB3aXRoIHRoZSBuZXcgOilcbiAgICAgICAgICAgICAgICAvL2dldCBpY29uIGNsYXNzIG5hbWUsIHN0YXJ0aW5nIHdpdGggZmEtXG4gICAgICAgICAgICAgICAgdmFyIGdldCA9ICQuZ3JlcChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2xhc3NOYW1lLnNwbGl0KFwiIFwiKSwgZnVuY3Rpb24odiwgaSl7XG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHYuaW5kZXhPZignZmEtJykgPT09IDA7XG5cbiAgICAgICAgICAgICAgICB9KS5qb2luKCk7XG5cbiAgICAgICAgICAgICAgICAvL2lmIHRoZSBpY29ucyBpcyBiZWluZyBjaGFuZ2VkLCBzYXZlIHRoZSBvbGQgb25lIHNvIHdlIGNhbiByZXNldCBpdCBpZiBuZWVkZWRcblxuICAgICAgICAgICAgICAgIGlmKCBnZXQgIT09ICQoJ3NlbGVjdCNpY29ucycpLnZhbCgpICkge1xuXG4gICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5fb2xkSWNvblskKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKV0gPSBnZXQ7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucmVtb3ZlQ2xhc3MoIGdldCApLmFkZENsYXNzKCAkKCdzZWxlY3QjaWNvbnMnKS52YWwoKSApO1xuXG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5yZW1vdmVDbGFzcyggZ2V0ICkuYWRkQ2xhc3MoICQoJ3NlbGVjdCNpY29ucycpLnZhbCgpICk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vdmlkZW8gVVJMXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2RhdGEtdHlwZScpID09PSAndmlkZW8nICkge1xuXG4gICAgICAgICAgICAgICAgaWYoICQoJ2lucHV0I3lvdXR1YmVJRCcpLnZhbCgpICE9PSAnJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJldigpLmF0dHIoJ3NyYycsIFwiLy93d3cueW91dHViZS5jb20vZW1iZWQvXCIrJCgnI3ZpZGVvX1RhYiBpbnB1dCN5b3V0dWJlSUQnKS52YWwoKSk7XG5cbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoICQoJ2lucHV0I3ZpbWVvSUQnKS52YWwoKSAhPT0gJycgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByZXYoKS5hdHRyKCdzcmMnLCBcIi8vcGxheWVyLnZpbWVvLmNvbS92aWRlby9cIiskKCcjdmlkZW9fVGFiIGlucHV0I3ZpbWVvSUQnKS52YWwoKStcIj90aXRsZT0wJmFtcDtieWxpbmU9MCZhbXA7cG9ydHJhaXQ9MFwiKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoICQoJ2lucHV0I3lvdXR1YmVJRCcpLnZhbCgpICE9PSAnJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkucHJldigpLmF0dHIoJ3NyYycsIFwiLy93d3cueW91dHViZS5jb20vZW1iZWQvXCIrJCgnI3ZpZGVvX1RhYiBpbnB1dCN5b3V0dWJlSUQnKS52YWwoKSk7XG5cbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmKCAkKCdpbnB1dCN2aW1lb0lEJykudmFsKCkgIT09ICcnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5wcmV2KCkuYXR0cignc3JjJywgXCIvL3BsYXllci52aW1lby5jb20vdmlkZW8vXCIrJCgnI3ZpZGVvX1RhYiBpbnB1dCN2aW1lb0lEJykudmFsKCkrXCI/dGl0bGU9MCZhbXA7YnlsaW5lPTAmYW1wO3BvcnRyYWl0PTBcIik7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkKCcjZGV0YWlsc0FwcGxpZWRNZXNzYWdlJykuZmFkZUluKDYwMCwgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXsgJCgnI2RldGFpbHNBcHBsaWVkTWVzc2FnZScpLmZhZGVPdXQoMTAwMCk7IH0sIDMwMDApO1xuXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy9hZGp1c3QgZnJhbWUgaGVpZ2h0XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLmhlaWdodEFkanVzdG1lbnQoKTtcblxuXG4gICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jaywgJ2NoYW5nZScpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgb24gZm9jdXMsIHdlJ2xsIG1ha2UgdGhlIGlucHV0IGZpZWxkcyB3aWRlclxuICAgICAgICAqL1xuICAgICAgICBhbmltYXRlU3R5bGVJbnB1dEluOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ3Bvc2l0aW9uJywgJ2Fic29sdXRlJyk7XG4gICAgICAgICAgICAkKHRoaXMpLmNzcygncmlnaHQnLCAnMHB4Jyk7XG4gICAgICAgICAgICAkKHRoaXMpLmFuaW1hdGUoeyd3aWR0aCc6ICcxMDAlJ30sIDUwMCk7XG4gICAgICAgICAgICAkKHRoaXMpLmZvY3VzKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgdGhpcy5zZWxlY3QoKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgb24gYmx1ciwgd2UnbGwgcmV2ZXJ0IHRoZSBpbnB1dCBmaWVsZHMgdG8gdGhlaXIgb3JpZ2luYWwgc2l6ZVxuICAgICAgICAqL1xuICAgICAgICBhbmltYXRlU3R5bGVJbnB1dE91dDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQodGhpcykuYW5pbWF0ZSh7J3dpZHRoJzogJzQyJSd9LCA1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ3Bvc2l0aW9uJywgJ3JlbGF0aXZlJyk7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ3JpZ2h0JywgJ2F1dG8nKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgYnVpbGRzIHRoZSBkcm9wZG93biB3aXRoICNibG9ja3Mgb24gdGhpcyBwYWdlXG4gICAgICAgICovXG4gICAgICAgIGJ1aWxkQmxvY2tzRHJvcGRvd246IGZ1bmN0aW9uIChjdXJyZW50VmFsKSB7XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS5zZWxlY3QyKCdkZXN0cm95Jyk7XG5cbiAgICAgICAgICAgIGlmKCB0eXBlb2YgY3VycmVudFZhbCA9PT0gJ3VuZGVmaW5lZCcgKSBjdXJyZW50VmFsID0gbnVsbDtcblxuICAgICAgICAgICAgdmFyIHgsXG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uO1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgICAgIG5ld09wdGlvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ09QVElPTicpO1xuICAgICAgICAgICAgbmV3T3B0aW9uLmlubmVyVGV4dCA9IFwiQ2hvb3NlIGEgYmxvY2tcIjtcbiAgICAgICAgICAgIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgJyMnKTtcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5hcHBlbmRDaGlsZChuZXdPcHRpb24pO1xuXG4gICAgICAgICAgICBmb3IgKCB4ID0gMDsgeCA8IHNpdGVCdWlsZGVyLnNpdGUuYWN0aXZlUGFnZS5ibG9ja3MubGVuZ3RoOyB4KysgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgZnJhbWVEb2MgPSBzaXRlQnVpbGRlci5zaXRlLmFjdGl2ZVBhZ2UuYmxvY2tzW3hdLmZyYW1lRG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgdmFyIHBhZ2VDb250YWluZXIgID0gZnJhbWVEb2MucXVlcnlTZWxlY3RvcihiQ29uZmlnLnBhZ2VDb250YWluZXIpO1xuICAgICAgICAgICAgICAgIHZhciB0aGVJRCA9IHBhZ2VDb250YWluZXIuY2hpbGRyZW5bMF0uaWQ7XG5cbiAgICAgICAgICAgICAgICBuZXdPcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdPUFRJT04nKTtcbiAgICAgICAgICAgICAgICBuZXdPcHRpb24uaW5uZXJUZXh0ID0gJyMnICsgdGhlSUQ7XG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgndmFsdWUnLCAnIycgKyB0aGVJRCk7XG4gICAgICAgICAgICAgICAgaWYoIGN1cnJlbnRWYWwgPT09ICcjJyArIHRoZUlEICkgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgnc2VsZWN0ZWQnLCB0cnVlKTtcblxuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5hcHBlbmRDaGlsZChuZXdPcHRpb24pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS5zZWxlY3QyKCk7XG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkudHJpZ2dlcignY2hhbmdlJyk7XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS5vZmYoJ2NoYW5nZScpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuaW5wdXRDdXN0b21MaW5rLnZhbHVlID0gdGhpcy52YWx1ZTtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNldFBhZ2VEcm9wZG93bigpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBibHVyIGV2ZW50IGhhbmRsZXIgZm9yIHRoZSBjdXN0b20gbGluayBpbnB1dFxuICAgICAgICAqL1xuICAgICAgICBpbnB1dEN1c3RvbUxpbmtCbHVyOiBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICB2YXIgdmFsdWUgPSBlLnRhcmdldC52YWx1ZSxcbiAgICAgICAgICAgICAgICB4O1xuXG4gICAgICAgICAgICAvL3BhZ2VzIG1hdGNoP1xuICAgICAgICAgICAgZm9yICggeCA9IDA7IHggPCBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLnF1ZXJ5U2VsZWN0b3JBbGwoJ29wdGlvbicpLmxlbmd0aDsgeCsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5xdWVyeVNlbGVjdG9yQWxsKCdvcHRpb24nKVt4XS52YWx1ZSApIHtcblxuICAgICAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLnNlbGVjdGVkSW5kZXggPSB4O1xuICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLnRyaWdnZXIoJ2NoYW5nZScpLnNlbGVjdDIoKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL2Jsb2NrcyBtYXRjaD9cbiAgICAgICAgICAgIGZvciAoIHggPSAwOyBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwucXVlcnlTZWxlY3RvckFsbCgnb3B0aW9uJykubGVuZ3RoOyB4KysgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoIHZhbHVlID09PSBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwucXVlcnlTZWxlY3RvckFsbCgnb3B0aW9uJylbeF0udmFsdWUgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLnNlbGVjdGVkSW5kZXggPSB4O1xuICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkudHJpZ2dlcignY2hhbmdlJykuc2VsZWN0MigpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBmb2N1cyBldmVudCBoYW5kbGVyIGZvciB0aGUgY3VzdG9tIGxpbmsgaW5wdXRcbiAgICAgICAgKi9cbiAgICAgICAgaW5wdXRDdXN0b21MaW5rRm9jdXM6IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzZXRQYWdlRHJvcGRvd24oKTtcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc2V0QmxvY2tEcm9wZG93bigpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgYnVpbGRzIHRoZSBkcm9wZG93biB3aXRoIHBhZ2VzIHRvIGxpbmsgdG9cbiAgICAgICAgKi9cbiAgICAgICAgYnVpbGRQYWdlc0Ryb3Bkb3duOiBmdW5jdGlvbiAoY3VycmVudFZhbCkge1xuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLnNlbGVjdDIoJ2Rlc3Ryb3knKTtcblxuICAgICAgICAgICAgaWYoIHR5cGVvZiBjdXJyZW50VmFsID09PSAndW5kZWZpbmVkJyApIGN1cnJlbnRWYWwgPSBudWxsO1xuXG4gICAgICAgICAgICB2YXIgeCxcbiAgICAgICAgICAgICAgICBuZXdPcHRpb247XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgICAgIG5ld09wdGlvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ09QVElPTicpO1xuICAgICAgICAgICAgbmV3T3B0aW9uLmlubmVyVGV4dCA9IFwiQ2hvb3NlIGEgcGFnZVwiO1xuICAgICAgICAgICAgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgndmFsdWUnLCAnIycpO1xuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5hcHBlbmRDaGlsZChuZXdPcHRpb24pO1xuXG4gICAgICAgICAgICBmb3IoIHggPSAwOyB4IDwgc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXMubGVuZ3RoOyB4KysgKSB7XG5cbiAgICAgICAgICAgICAgICBuZXdPcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdPUFRJT04nKTtcbiAgICAgICAgICAgICAgICBuZXdPcHRpb24uaW5uZXJUZXh0ID0gc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbeF0ubmFtZTtcbiAgICAgICAgICAgICAgICBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCd2YWx1ZScsIHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW3hdLm5hbWUgKyAnLmh0bWwnKTtcbiAgICAgICAgICAgICAgICBpZiggY3VycmVudFZhbCA9PT0gc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbeF0ubmFtZSArICcuaHRtbCcpIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3NlbGVjdGVkJywgdHJ1ZSk7XG5cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLmFwcGVuZENoaWxkKG5ld09wdGlvbik7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS5zZWxlY3QyKCk7XG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLnRyaWdnZXIoJ2NoYW5nZScpO1xuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLm9mZignY2hhbmdlJykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5pbnB1dEN1c3RvbUxpbmsudmFsdWUgPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc2V0QmxvY2tEcm9wZG93bigpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXNldCB0aGUgYmxvY2sgbGluayBkcm9wZG93blxuICAgICAgICAqL1xuICAgICAgICByZXNldEJsb2NrRHJvcGRvd246IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLnNlbGVjdGVkSW5kZXggPSAwO1xuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLnNlbGVjdDIoJ2Rlc3Ryb3knKS5zZWxlY3QyKCk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXNldCB0aGUgcGFnZSBsaW5rIGRyb3Bkb3duXG4gICAgICAgICovXG4gICAgICAgIHJlc2V0UGFnZURyb3Bkb3duOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMuc2VsZWN0ZWRJbmRleCA9IDA7XG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLnNlbGVjdDIoJ2Rlc3Ryb3knKS5zZWxlY3QyKCk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICB3aGVuIHRoZSBjbGlja2VkIGVsZW1lbnQgaXMgYW4gYW5jaG9yIHRhZyAob3IgaGFzIGEgcGFyZW50IGFuY2hvciB0YWcpXG4gICAgICAgICovXG4gICAgICAgIGVkaXRMaW5rOiBmdW5jdGlvbihlbCkge1xuXG4gICAgICAgICAgICB2YXIgdGhlSHJlZjtcblxuICAgICAgICAgICAgJCgnYSNsaW5rX0xpbmsnKS5wYXJlbnQoKS5zaG93KCk7XG5cbiAgICAgICAgICAgIC8vc2V0IHRoZUhyZWZcbiAgICAgICAgICAgIGlmKCAkKGVsKS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHtcblxuICAgICAgICAgICAgICAgIHRoZUhyZWYgPSAkKGVsKS5hdHRyKCdocmVmJyk7XG5cbiAgICAgICAgICAgIH0gZWxzZSBpZiggJChlbCkucGFyZW50KCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7XG5cbiAgICAgICAgICAgICAgICB0aGVIcmVmID0gJChlbCkucGFyZW50KCkuYXR0cignaHJlZicpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmJ1aWxkUGFnZXNEcm9wZG93bih0aGVIcmVmKTtcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmJ1aWxkQmxvY2tzRHJvcGRvd24odGhlSHJlZik7XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5pbnB1dEN1c3RvbUxpbmsudmFsdWUgPSB0aGVIcmVmO1xuXG4gICAgICAgICAgICAvL2dyYWIgYW4gaW1hZ2U/XG4gICAgICAgICAgICBpZiAoIGVsLnF1ZXJ5U2VsZWN0b3IoJ2ltZycpICkgc3R5bGVlZGl0b3IubGlua0ltYWdlID0gZWwucXVlcnlTZWxlY3RvcignaW1nJyk7XG4gICAgICAgICAgICBlbHNlIHN0eWxlZWRpdG9yLmxpbmtJbWFnZSA9IG51bGw7XG5cbiAgICAgICAgICAgIC8vZ3JhYiBhbiBpY29uP1xuICAgICAgICAgICAgaWYgKCBlbC5xdWVyeVNlbGVjdG9yKCcuZmEnKSApIHN0eWxlZWRpdG9yLmxpbmtJY29uID0gZWwucXVlcnlTZWxlY3RvcignLmZhJykuY2xvbmVOb2RlKHRydWUpO1xuICAgICAgICAgICAgZWxzZSBzdHlsZWVkaXRvci5saW5rSWNvbiA9IG51bGw7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmlucHV0TGlua1RleHQudmFsdWUgPSBlbC5pbm5lclRleHQ7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICB3aGVuIHRoZSBjbGlja2VkIGVsZW1lbnQgaXMgYW4gaW1hZ2VcbiAgICAgICAgKi9cbiAgICAgICAgZWRpdEltYWdlOiBmdW5jdGlvbihlbCkge1xuXG4gICAgICAgICAgICAkKCdhI2ltZ19MaW5rJykucGFyZW50KCkuc2hvdygpO1xuXG4gICAgICAgICAgICAvL3NldCB0aGUgY3VycmVudCBTUkNcbiAgICAgICAgICAgICQoJy5pbWFnZUZpbGVUYWInKS5maW5kKCdpbnB1dCNpbWFnZVVSTCcpLnZhbCggJChlbCkuYXR0cignc3JjJykgKTtcblxuICAgICAgICAgICAgLy9yZXNldCB0aGUgZmlsZSB1cGxvYWRcbiAgICAgICAgICAgICQoJy5pbWFnZUZpbGVUYWInKS5maW5kKCdhLmZpbGVpbnB1dC1leGlzdHMnKS5jbGljaygpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgd2hlbiB0aGUgY2xpY2tlZCBlbGVtZW50IGlzIGEgdmlkZW8gZWxlbWVudFxuICAgICAgICAqL1xuICAgICAgICBlZGl0VmlkZW86IGZ1bmN0aW9uKGVsKSB7XG5cbiAgICAgICAgICAgIHZhciBtYXRjaFJlc3VsdHM7XG5cbiAgICAgICAgICAgICQoJ2EjdmlkZW9fTGluaycpLnBhcmVudCgpLnNob3coKTtcbiAgICAgICAgICAgICQoJ2EjdmlkZW9fTGluaycpLmNsaWNrKCk7XG5cbiAgICAgICAgICAgIC8vaW5qZWN0IGN1cnJlbnQgdmlkZW8gSUQsY2hlY2sgaWYgd2UncmUgZGVhbGluZyB3aXRoIFlvdXR1YmUgb3IgVmltZW9cblxuICAgICAgICAgICAgaWYoICQoZWwpLnByZXYoKS5hdHRyKCdzcmMnKS5pbmRleE9mKFwidmltZW8uY29tXCIpID4gLTEgKSB7Ly92aW1lb1xuXG4gICAgICAgICAgICAgICAgbWF0Y2hSZXN1bHRzID0gJChlbCkucHJldigpLmF0dHIoJ3NyYycpLm1hdGNoKC9wbGF5ZXJcXC52aW1lb1xcLmNvbVxcL3ZpZGVvXFwvKFswLTldKikvKTtcblxuICAgICAgICAgICAgICAgICQoJyN2aWRlb19UYWIgaW5wdXQjdmltZW9JRCcpLnZhbCggbWF0Y2hSZXN1bHRzW21hdGNoUmVzdWx0cy5sZW5ndGgtMV0gKTtcbiAgICAgICAgICAgICAgICAkKCcjdmlkZW9fVGFiIGlucHV0I3lvdXR1YmVJRCcpLnZhbCgnJyk7XG5cbiAgICAgICAgICAgIH0gZWxzZSB7Ly95b3V0dWJlXG5cbiAgICAgICAgICAgICAgICAvL3RlbXAgPSAkKGVsKS5wcmV2KCkuYXR0cignc3JjJykuc3BsaXQoJy8nKTtcbiAgICAgICAgICAgICAgICB2YXIgcmVnRXhwID0gLy4qKD86eW91dHUuYmVcXC98dlxcL3x1XFwvXFx3XFwvfGVtYmVkXFwvfHdhdGNoXFw/dj0pKFteI1xcJlxcP10qKS4qLztcbiAgICAgICAgICAgICAgICBtYXRjaFJlc3VsdHMgPSAkKGVsKS5wcmV2KCkuYXR0cignc3JjJykubWF0Y2gocmVnRXhwKTtcblxuICAgICAgICAgICAgICAgICQoJyN2aWRlb19UYWIgaW5wdXQjeW91dHViZUlEJykudmFsKCBtYXRjaFJlc3VsdHNbMV0gKTtcbiAgICAgICAgICAgICAgICAkKCcjdmlkZW9fVGFiIGlucHV0I3ZpbWVvSUQnKS52YWwoJycpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICB3aGVuIHRoZSBjbGlja2VkIGVsZW1lbnQgaXMgYW4gZmEgaWNvblxuICAgICAgICAqL1xuICAgICAgICBlZGl0SWNvbjogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQoJ2EjaWNvbl9MaW5rJykucGFyZW50KCkuc2hvdygpO1xuXG4gICAgICAgICAgICAvL2dldCBpY29uIGNsYXNzIG5hbWUsIHN0YXJ0aW5nIHdpdGggZmEtXG4gICAgICAgICAgICB2YXIgZ2V0ID0gJC5ncmVwKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNsYXNzTmFtZS5zcGxpdChcIiBcIiksIGZ1bmN0aW9uKHYsIGkpe1xuXG4gICAgICAgICAgICAgICAgcmV0dXJuIHYuaW5kZXhPZignZmEtJykgPT09IDA7XG5cbiAgICAgICAgICAgIH0pLmpvaW4oKTtcblxuICAgICAgICAgICAgJCgnc2VsZWN0I2ljb25zIG9wdGlvbicpLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLnZhbCgpID09PSBnZXQgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5hdHRyKCdzZWxlY3RlZCcsIHRydWUpO1xuXG4gICAgICAgICAgICAgICAgICAgICQoJyNpY29ucycpLnRyaWdnZXIoJ2Nob3Nlbjp1cGRhdGVkJyk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlIHNlbGVjdGVkIGVsZW1lbnRcbiAgICAgICAgKi9cbiAgICAgICAgZGVsZXRlRWxlbWVudDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJlZm9yZURlbGV0ZScpO1xuXG4gICAgICAgICAgICB2YXIgdG9EZWw7XG5cbiAgICAgICAgICAgIC8vZGV0ZXJtaW5lIHdoYXQgdG8gZGVsZXRlXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkgey8vYW5jb3JcblxuICAgICAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkucHJvcCgndGFnTmFtZScpID09PSdMSScgKSB7Ly9jbG9uZSB0aGUgTElcblxuICAgICAgICAgICAgICAgICAgICB0b0RlbCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKTtcblxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAgICAgdG9EZWwgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0gZWxzZSBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0lNRycgKSB7Ly9pbWFnZVxuXG4gICAgICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHsvL2Nsb25lIHRoZSBBXG5cbiAgICAgICAgICAgICAgICAgICAgdG9EZWwgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCk7XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgICAgIHRvRGVsID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9IGVsc2Ugey8vZXZlcnl0aGluZyBlbHNlXG5cbiAgICAgICAgICAgICAgICB0b0RlbCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIHRvRGVsLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgdmFyIHJhbmRvbUVsID0gJCh0aGlzKS5jbG9zZXN0KCdib2R5JykuZmluZCgnKjpmaXJzdCcpO1xuXG4gICAgICAgICAgICAgICAgdG9EZWwucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICB2YXIgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG5cbiAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLmhlaWdodEFkanVzdG1lbnQoKTtcblxuICAgICAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAkKCcjZGVsZXRlRWxlbWVudCcpLm1vZGFsKCdoaWRlJyk7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmNsb3NlU3R5bGVFZGl0b3IoKTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLCAnY2hhbmdlJyk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjbG9uZXMgdGhlIHNlbGVjdGVkIGVsZW1lbnRcbiAgICAgICAgKi9cbiAgICAgICAgY2xvbmVFbGVtZW50OiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmVmb3JlQ2xvbmUnKTtcblxuICAgICAgICAgICAgdmFyIHRoZUNsb25lLCB0aGVDbG9uZTIsIHRoZU9uZSwgY2xvbmVkLCBjbG9uZVBhcmVudCwgZWxlbWVudElEO1xuXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLmhhc0NsYXNzKCdwcm9wQ2xvbmUnKSApIHsvL2Nsb25lIHRoZSBwYXJlbnQgZWxlbWVudFxuXG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICB0aGVDbG9uZS5maW5kKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpICkuYXR0cignc3R5bGUnLCAnJyk7XG5cbiAgICAgICAgICAgICAgICB0aGVDbG9uZTIgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICB0aGVDbG9uZTIuZmluZCggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSApLmF0dHIoJ3N0eWxlJywgJycpO1xuXG4gICAgICAgICAgICAgICAgdGhlT25lID0gdGhlQ2xvbmUuZmluZCggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSApO1xuICAgICAgICAgICAgICAgIGNsb25lZCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKTtcblxuICAgICAgICAgICAgICAgIGNsb25lUGFyZW50ID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLnBhcmVudCgpO1xuXG4gICAgICAgICAgICB9IGVsc2Ugey8vY2xvbmUgdGhlIGVsZW1lbnQgaXRzZWxmXG5cbiAgICAgICAgICAgICAgICB0aGVDbG9uZSA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jbG9uZSgpO1xuXG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUuYXR0cignc3R5bGUnLCAnJyk7XG5cbiAgICAgICAgICAgICAgICAvKmlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoZUNsb25lLmF0dHIoJ2lkJywgJycpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICAgICAgfSovXG5cbiAgICAgICAgICAgICAgICB0aGVDbG9uZTIgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICB0aGVDbG9uZTIuYXR0cignc3R5bGUnLCAnJyk7XG5cbiAgICAgICAgICAgICAgICAvKlxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoZUNsb25lMi5hdHRyKCdpZCcsIHRoZUNsb25lLmF0dHIoJ2lkJykpO1xuICAgICAgICAgICAgICAgIH0qL1xuXG4gICAgICAgICAgICAgICAgdGhlT25lID0gdGhlQ2xvbmU7XG4gICAgICAgICAgICAgICAgY2xvbmVkID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgY2xvbmVQYXJlbnQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgY2xvbmVkLmFmdGVyKCB0aGVDbG9uZSApO1xuXG4gICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcbiAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5hZnRlciggdGhlQ2xvbmUyICk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgLy9tYWtlIHN1cmUgdGhlIG5ldyBlbGVtZW50IGdldHMgdGhlIHByb3BlciBldmVudHMgc2V0IG9uIGl0XG4gICAgICAgICAgICB2YXIgbmV3RWxlbWVudCA9IG5ldyBjYW52YXNFbGVtZW50KHRoZU9uZS5nZXQoMCkpO1xuICAgICAgICAgICAgbmV3RWxlbWVudC5hY3RpdmF0ZSgpO1xuXG4gICAgICAgICAgICAvL3Bvc3NpYmxlIGhlaWdodCBhZGp1c3RtZW50c1xuICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jay5oZWlnaHRBZGp1c3RtZW50KCk7XG5cbiAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLCAnY2hhbmdlJyk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXNldHMgdGhlIGFjdGl2ZSBlbGVtZW50XG4gICAgICAgICovXG4gICAgICAgIHJlc2V0RWxlbWVudDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY2xvc2VzdCgnYm9keScpLndpZHRoKCkgIT09ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS53aWR0aCgpICkge1xuXG4gICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ3N0eWxlJywgJycpLmNzcyh7J291dGxpbmUnOiAnM3B4IGRhc2hlZCByZWQnLCAnY3Vyc29yJzogJ3BvaW50ZXInfSk7XG5cbiAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignc3R5bGUnLCAnJykuY3NzKHsnb3V0bGluZSc6ICczcHggZGFzaGVkIHJlZCcsICdvdXRsaW5lLW9mZnNldCc6Jy0zcHgnLCAnY3Vyc29yJzogJ3BvaW50ZXInfSk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLmF0dHIoJ3N0eWxlJywgJycpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvciBmb3JtI3N0eWxpbmdGb3JtJykuaGVpZ2h0KCAkKCcjc3R5bGVFZGl0b3IgZm9ybSNzdHlsaW5nRm9ybScpLmhlaWdodCgpK1wicHhcIiApO1xuXG4gICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3IgZm9ybSNzdHlsaW5nRm9ybSAuZm9ybS1ncm91cDpub3QoI3N0eWxlRWxUZW1wbGF0ZSknKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgIH0pO1xuXG5cbiAgICAgICAgICAgIC8vcmVzZXQgaWNvblxuXG4gICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuX29sZEljb25bJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyldICE9PSBudWxsICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIGdldCA9ICQuZ3JlcChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2xhc3NOYW1lLnNwbGl0KFwiIFwiKSwgZnVuY3Rpb24odiwgaSl7XG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHYuaW5kZXhPZignZmEtJykgPT09IDA7XG5cbiAgICAgICAgICAgICAgICB9KS5qb2luKCk7XG5cbiAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucmVtb3ZlQ2xhc3MoIGdldCApLmFkZENsYXNzKCBzdHlsZWVkaXRvci5fb2xkSWNvblskKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKV0gKTtcblxuICAgICAgICAgICAgICAgICQoJ3NlbGVjdCNpY29ucyBvcHRpb24nKS5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykudmFsKCkgPT09IHN0eWxlZWRpdG9yLl9vbGRJY29uWyQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpXSApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5hdHRyKCdzZWxlY3RlZCcsIHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ljb25zJykudHJpZ2dlcignY2hvc2VuOnVwZGF0ZWQnKTtcblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBzZXRUaW1lb3V0KCBmdW5jdGlvbigpe3N0eWxlZWRpdG9yLmJ1aWxkZVN0eWxlRWxlbWVudHMoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdkYXRhLXNlbGVjdG9yJykgKTt9LCA1NTApO1xuXG4gICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2ssICdjaGFuZ2UnKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgcmVzZXRTZWxlY3RMaW5rc1BhZ2VzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCgnI2ludGVybmFsTGlua3NEcm9wZG93bicpLnNlbGVjdDIoJ3ZhbCcsICcjJyk7XG5cbiAgICAgICAgfSxcblxuICAgICAgICByZXNldFNlbGVjdExpbmtzSW50ZXJuYWw6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKCcjcGFnZUxpbmtzRHJvcGRvd24nKS5zZWxlY3QyKCd2YWwnLCAnIycpO1xuXG4gICAgICAgIH0sXG5cbiAgICAgICAgcmVzZXRTZWxlY3RBbGxMaW5rczogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQoJyNpbnRlcm5hbExpbmtzRHJvcGRvd24nKS5zZWxlY3QyKCd2YWwnLCAnIycpO1xuICAgICAgICAgICAgJCgnI3BhZ2VMaW5rc0Ryb3Bkb3duJykuc2VsZWN0MigndmFsJywgJyMnKTtcbiAgICAgICAgICAgIHRoaXMuc2VsZWN0KCk7XG5cbiAgICAgICAgfSxcblxuICAgICAgICAvKlxuICAgICAgICAgICAgaGlkZXMgZmlsZSB1cGxvYWQgZm9ybXNcbiAgICAgICAgKi9cbiAgICAgICAgaGlkZUZpbGVVcGxvYWRzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCgnZm9ybSNpbWFnZVVwbG9hZEZvcm0nKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAjdXBsb2FkVGFiTEknKS5oaWRlKCk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjbG9zZXMgdGhlIHN0eWxlIGVkaXRvclxuICAgICAgICAqL1xuICAgICAgICBjbG9zZVN0eWxlRWRpdG9yOiBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICBpZiAoIGUgIT09IHVuZGVmaW5lZCApIGUucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgaWYgKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVkaXRhYmxlQXR0cmlidXRlcyAmJiBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVkaXRhYmxlQXR0cmlidXRlcy5pbmRleE9mKCdjb250ZW50JykgPT09IC0xICkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucmVtb3ZlT3V0bGluZSgpO1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuYWN0aXZhdGUoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYoICQoJyNzdHlsZUVkaXRvcicpLmNzcygnbGVmdCcpID09PSAnMHB4JyApIHtcblxuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnRvZ2dsZVNpZGVQYW5lbCgnY2xvc2UnKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgdG9nZ2xlcyB0aGUgc2lkZSBwYW5lbFxuICAgICAgICAqL1xuICAgICAgICB0b2dnbGVTaWRlUGFuZWw6IGZ1bmN0aW9uKHZhbCkge1xuXG4gICAgICAgICAgICBpZiggdmFsID09PSAnb3BlbicgJiYgJCgnI3N0eWxlRWRpdG9yJykuY3NzKCdsZWZ0JykgPT09ICctMzAwcHgnICkge1xuICAgICAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvcicpLmFuaW1hdGUoeydsZWZ0JzogJzBweCd9LCAyNTApO1xuICAgICAgICAgICAgfSBlbHNlIGlmKCB2YWwgPT09ICdjbG9zZScgJiYgJCgnI3N0eWxlRWRpdG9yJykuY3NzKCdsZWZ0JykgPT09ICcwcHgnICkge1xuICAgICAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvcicpLmFuaW1hdGUoeydsZWZ0JzogJy0zMDBweCd9LCAyNTApO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cbiAgICB9O1xuXG4gICAgc3R5bGVlZGl0b3IuaW5pdCgpO1xuXG4gICAgZXhwb3J0cy5zdHlsZWVkaXRvciA9IHN0eWxlZWRpdG9yO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cbi8qIGdsb2JhbHMgc2l0ZVVybDpmYWxzZSwgYmFzZVVybDpmYWxzZSAqL1xuICAgIFwidXNlIHN0cmljdFwiO1xuICAgICAgICBcbiAgICB2YXIgYXBwVUkgPSB7XG4gICAgICAgIFxuICAgICAgICBmaXJzdE1lbnVXaWR0aDogMTkwLFxuICAgICAgICBzZWNvbmRNZW51V2lkdGg6IDMwMCxcbiAgICAgICAgbG9hZGVyQW5pbWF0aW9uOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbG9hZGVyJyksXG4gICAgICAgIHNlY29uZE1lbnVUcmlnZ2VyQ29udGFpbmVyczogJCgnI21lbnUgI21haW4gI2VsZW1lbnRDYXRzLCAjbWVudSAjbWFpbiAjdGVtcGxhdGVzVWwnKSxcbiAgICAgICAgc2l0ZVVybDogc2l0ZVVybCxcbiAgICAgICAgYmFzZVVybDogYmFzZVVybCxcbiAgICAgICAgXG4gICAgICAgIHNldHVwOiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBGYWRlIHRoZSBsb2FkZXIgYW5pbWF0aW9uXG4gICAgICAgICAgICAkKGFwcFVJLmxvYWRlckFuaW1hdGlvbikuZmFkZU91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQoJyNtZW51JykuYW5pbWF0ZSh7J2xlZnQnOiAtYXBwVUkuZmlyc3RNZW51V2lkdGh9LCAxMDAwKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBUYWJzXG4gICAgICAgICAgICAkKFwiLm5hdi10YWJzIGFcIikub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS50YWIoXCJzaG93XCIpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoXCJzZWxlY3Quc2VsZWN0XCIpLnNlbGVjdDIoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnOnJhZGlvLCA6Y2hlY2tib3gnKS5yYWRpb2NoZWNrKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRvb2x0aXBzXG4gICAgICAgICAgICAkKFwiW2RhdGEtdG9nZ2xlPXRvb2x0aXBdXCIpLnRvb2x0aXAoXCJoaWRlXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBUYWJsZTogVG9nZ2xlIGFsbCBjaGVja2JveGVzXG4gICAgICAgICAgICAkKCcudGFibGUgLnRvZ2dsZS1hbGwgOmNoZWNrYm94Jykub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciAkdGhpcyA9ICQodGhpcyk7XG4gICAgICAgICAgICAgICAgdmFyIGNoID0gJHRoaXMucHJvcCgnY2hlY2tlZCcpO1xuICAgICAgICAgICAgICAgICR0aGlzLmNsb3Nlc3QoJy50YWJsZScpLmZpbmQoJ3Rib2R5IDpjaGVja2JveCcpLnJhZGlvY2hlY2soIWNoID8gJ3VuY2hlY2snIDogJ2NoZWNrJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gQWRkIHN0eWxlIGNsYXNzIG5hbWUgdG8gYSB0b29sdGlwc1xuICAgICAgICAgICAgJChcIi50b29sdGlwXCIpLmFkZENsYXNzKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIGlmICgkKHRoaXMpLnByZXYoKS5hdHRyKFwiZGF0YS10b29sdGlwLXN0eWxlXCIpKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBcInRvb2x0aXAtXCIgKyAkKHRoaXMpLnByZXYoKS5hdHRyKFwiZGF0YS10b29sdGlwLXN0eWxlXCIpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKFwiLmJ0bi1ncm91cFwiKS5vbignY2xpY2snLCBcImFcIiwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5zaWJsaW5ncygpLnJlbW92ZUNsYXNzKFwiYWN0aXZlXCIpLmVuZCgpLmFkZENsYXNzKFwiYWN0aXZlXCIpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIEZvY3VzIHN0YXRlIGZvciBhcHBlbmQvcHJlcGVuZCBpbnB1dHNcbiAgICAgICAgICAgICQoJy5pbnB1dC1ncm91cCcpLm9uKCdmb2N1cycsICcuZm9ybS1jb250cm9sJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgnLmlucHV0LWdyb3VwLCAuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdmb2N1cycpO1xuICAgICAgICAgICAgfSkub24oJ2JsdXInLCAnLmZvcm0tY29udHJvbCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNsb3Nlc3QoJy5pbnB1dC1ncm91cCwgLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnZm9jdXMnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBUYWJsZTogVG9nZ2xlIGFsbCBjaGVja2JveGVzXG4gICAgICAgICAgICAkKCcudGFibGUgLnRvZ2dsZS1hbGwnKS5vbignY2xpY2snLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICB2YXIgY2ggPSAkKHRoaXMpLmZpbmQoJzpjaGVja2JveCcpLnByb3AoJ2NoZWNrZWQnKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNsb3Nlc3QoJy50YWJsZScpLmZpbmQoJ3Rib2R5IDpjaGVja2JveCcpLmNoZWNrYm94KCFjaCA/ICdjaGVjaycgOiAndW5jaGVjaycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYmxlOiBBZGQgY2xhc3Mgcm93IHNlbGVjdGVkXG4gICAgICAgICAgICAkKCcudGFibGUgdGJvZHkgOmNoZWNrYm94Jykub24oJ2NoZWNrIHVuY2hlY2sgdG9nZ2xlJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpXG4gICAgICAgICAgICAgICAgLCBjaGVjayA9ICR0aGlzLnByb3AoJ2NoZWNrZWQnKVxuICAgICAgICAgICAgICAgICwgdG9nZ2xlID0gZS50eXBlID09PSAndG9nZ2xlJ1xuICAgICAgICAgICAgICAgICwgY2hlY2tib3hlcyA9ICQoJy50YWJsZSB0Ym9keSA6Y2hlY2tib3gnKVxuICAgICAgICAgICAgICAgICwgY2hlY2tBbGwgPSBjaGVja2JveGVzLmxlbmd0aCA9PT0gY2hlY2tib3hlcy5maWx0ZXIoJzpjaGVja2VkJykubGVuZ3RoO1xuXG4gICAgICAgICAgICAgICAgJHRoaXMuY2xvc2VzdCgndHInKVtjaGVjayA/ICdhZGRDbGFzcycgOiAncmVtb3ZlQ2xhc3MnXSgnc2VsZWN0ZWQtcm93Jyk7XG4gICAgICAgICAgICAgICAgaWYgKHRvZ2dsZSkgJHRoaXMuY2xvc2VzdCgnLnRhYmxlJykuZmluZCgnLnRvZ2dsZS1hbGwgOmNoZWNrYm94JykuY2hlY2tib3goY2hlY2tBbGwgPyAnY2hlY2snIDogJ3VuY2hlY2snKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBTd2l0Y2hcbiAgICAgICAgICAgICQoXCJbZGF0YS10b2dnbGU9J3N3aXRjaCddXCIpLndyYXAoJzxkaXYgY2xhc3M9XCJzd2l0Y2hcIiAvPicpLnBhcmVudCgpLmJvb3RzdHJhcFN3aXRjaCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBhcHBVSS5zZWNvbmRNZW51VHJpZ2dlckNvbnRhaW5lcnMub24oJ2NsaWNrJywgJ2E6bm90KC5idG4pJywgYXBwVUkuc2Vjb25kTWVudUFuaW1hdGlvbik7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIHNlY29uZE1lbnVBbmltYXRpb246IGZ1bmN0aW9uKCl7XG4gICAgICAgIFxuICAgICAgICAgICAgJCgnI21lbnUgI21haW4gYScpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuXHRcbiAgICAgICAgICAgIC8vc2hvdyBvbmx5IHRoZSByaWdodCBlbGVtZW50c1xuICAgICAgICAgICAgJCgnI21lbnUgI3NlY29uZCB1bCBsaScpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJyNtZW51ICNzZWNvbmQgdWwgbGkuJyskKHRoaXMpLmF0dHIoJ2lkJykpLnNob3coKTtcblxuICAgICAgICAgICAgaWYoICQodGhpcykuYXR0cignaWQnKSA9PT0gJ2FsbCcgKSB7XG4gICAgICAgICAgICAgICAgJCgnI21lbnUgI3NlY29uZCB1bCNlbGVtZW50cyBsaScpLnNob3coKTtcdFx0XG4gICAgICAgICAgICB9XG5cdFxuICAgICAgICAgICAgJCgnLm1lbnUgLnNlY29uZCcpLmNzcygnZGlzcGxheScsICdibG9jaycpLnN0b3AoKS5hbmltYXRlKHtcbiAgICAgICAgICAgICAgICB3aWR0aDogYXBwVUkuc2Vjb25kTWVudVdpZHRoXG4gICAgICAgICAgICB9LCA1MDApO1x0XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcbiAgICBcbiAgICAvL2luaXRpYXRlIHRoZSBVSVxuICAgIGFwcFVJLnNldHVwKCk7XG5cblxuICAgIC8vKioqKiBFWFBPUlRTXG4gICAgbW9kdWxlLmV4cG9ydHMuYXBwVUkgPSBhcHBVSTtcbiAgICBcbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcbiAgICBcInVzZSBzdHJpY3RcIjtcbiAgICBcbiAgICBleHBvcnRzLmdldFJhbmRvbUFyYml0cmFyeSA9IGZ1bmN0aW9uKG1pbiwgbWF4KSB7XG4gICAgICAgIHJldHVybiBNYXRoLmZsb29yKE1hdGgucmFuZG9tKCkgKiAobWF4IC0gbWluKSArIG1pbik7XG4gICAgfTtcblxuICAgIGV4cG9ydHMuZ2V0UGFyYW1ldGVyQnlOYW1lID0gZnVuY3Rpb24gKG5hbWUsIHVybCkge1xuXG4gICAgICAgIGlmICghdXJsKSB1cmwgPSB3aW5kb3cubG9jYXRpb24uaHJlZjtcbiAgICAgICAgbmFtZSA9IG5hbWUucmVwbGFjZSgvW1xcW1xcXV0vZywgXCJcXFxcJCZcIik7XG4gICAgICAgIHZhciByZWdleCA9IG5ldyBSZWdFeHAoXCJbPyZdXCIgKyBuYW1lICsgXCIoPShbXiYjXSopfCZ8I3wkKVwiKSxcbiAgICAgICAgICAgIHJlc3VsdHMgPSByZWdleC5leGVjKHVybCk7XG4gICAgICAgIGlmICghcmVzdWx0cykgcmV0dXJuIG51bGw7XG4gICAgICAgIGlmICghcmVzdWx0c1syXSkgcmV0dXJuICcnO1xuICAgICAgICByZXR1cm4gZGVjb2RlVVJJQ29tcG9uZW50KHJlc3VsdHNbMl0ucmVwbGFjZSgvXFwrL2csIFwiIFwiKSk7XG4gICAgICAgIFxuICAgIH07XG4gICAgXG59KCkpOyIsIi8qIVxuICogcHVibGlzaGVyLmpzIC0gKGMpIFJ5YW4gRmxvcmVuY2UgMjAxMVxuICogZ2l0aHViLmNvbS9ycGZsb3JlbmNlL3B1Ymxpc2hlci5qc1xuICogTUlUIExpY2Vuc2VcbiovXG5cbi8vIFVNRCBCb2lsZXJwbGF0ZSBcXG8vICYmIEQ6XG4oZnVuY3Rpb24gKHJvb3QsIGZhY3RvcnkpIHtcbiAgaWYgKHR5cGVvZiBleHBvcnRzID09PSAnb2JqZWN0Jykge1xuICAgIG1vZHVsZS5leHBvcnRzID0gZmFjdG9yeSgpOyAvLyBub2RlXG4gIH0gZWxzZSBpZiAodHlwZW9mIGRlZmluZSA9PT0gJ2Z1bmN0aW9uJyAmJiBkZWZpbmUuYW1kKSB7XG4gICAgZGVmaW5lKGZhY3RvcnkpOyAvLyBhbWRcbiAgfSBlbHNlIHtcbiAgICAvLyB3aW5kb3cgd2l0aCBub0NvbmZsaWN0XG4gICAgdmFyIF9wdWJsaXNoZXIgPSByb290LnB1Ymxpc2hlcjtcbiAgICB2YXIgcHVibGlzaGVyID0gcm9vdC5wdWJsaXNoZXIgPSBmYWN0b3J5KCk7XG4gICAgcm9vdC5wdWJsaXNoZXIubm9Db25mbGljdCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgIHJvb3QucHVibGlzaGVyID0gX3B1Ymxpc2hlcjtcbiAgICAgIHJldHVybiBwdWJsaXNoZXI7XG4gICAgfVxuICB9XG59KHRoaXMsIGZ1bmN0aW9uICgpIHtcblxuICB2YXIgcHVibGlzaGVyID0gZnVuY3Rpb24gKG9iaikge1xuICAgIHZhciB0b3BpY3MgPSB7fTtcbiAgICBvYmogPSBvYmogfHwge307XG5cbiAgICBvYmoucHVibGlzaCA9IGZ1bmN0aW9uICh0b3BpYy8qLCBtZXNzYWdlcy4uLiovKSB7XG4gICAgICBpZiAoIXRvcGljc1t0b3BpY10pIHJldHVybiBvYmo7XG4gICAgICB2YXIgbWVzc2FnZXMgPSBbXS5zbGljZS5jYWxsKGFyZ3VtZW50cywgMSk7XG4gICAgICBmb3IgKHZhciBpID0gMCwgbCA9IHRvcGljc1t0b3BpY10ubGVuZ3RoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgIHRvcGljc1t0b3BpY11baV0uaGFuZGxlci5hcHBseSh0b3BpY3NbdG9waWNdW2ldLmNvbnRleHQsIG1lc3NhZ2VzKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBvYmo7XG4gICAgfTtcblxuICAgIG9iai5zdWJzY3JpYmUgPSBmdW5jdGlvbiAodG9waWNPclN1YnNjcmliZXIsIGhhbmRsZXJPclRvcGljcykge1xuICAgICAgdmFyIGZpcnN0VHlwZSA9IHR5cGVvZiB0b3BpY09yU3Vic2NyaWJlcjtcblxuICAgICAgaWYgKGZpcnN0VHlwZSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgcmV0dXJuIHN1YnNjcmliZS5hcHBseShudWxsLCBhcmd1bWVudHMpO1xuICAgICAgfVxuXG4gICAgICBpZiAoZmlyc3RUeXBlID09PSAnb2JqZWN0JyAmJiAhaGFuZGxlck9yVG9waWNzKSB7XG4gICAgICAgIHJldHVybiBzdWJzY3JpYmVNdWx0aXBsZS5hcHBseShudWxsLCBhcmd1bWVudHMpO1xuICAgICAgfVxuXG4gICAgICBpZiAodHlwZW9mIGhhbmRsZXJPclRvcGljcyA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgcmV0dXJuIGhpdGNoLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgICB9XG5cbiAgICAgIHJldHVybiBoaXRjaE11bHRpcGxlLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIHN1YnNjcmliZSAodG9waWMsIGhhbmRsZXIsIGNvbnRleHQpIHtcbiAgICAgIHZhciByZWZlcmVuY2UgPSB7IGhhbmRsZXI6IGhhbmRsZXIsIGNvbnRleHQ6IGNvbnRleHQgfHwgb2JqIH07XG4gICAgICB0b3BpYyA9IHRvcGljc1t0b3BpY10gfHwgKHRvcGljc1t0b3BpY10gPSBbXSk7XG4gICAgICB0b3BpYy5wdXNoKHJlZmVyZW5jZSk7XG4gICAgICByZXR1cm4ge1xuICAgICAgICBhdHRhY2g6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICB0b3BpYy5wdXNoKHJlZmVyZW5jZSk7XG4gICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH0sXG4gICAgICAgIGRldGFjaDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGVyYXNlKHRvcGljLCByZWZlcmVuY2UpO1xuICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9XG4gICAgICB9O1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBzdWJzY3JpYmVNdWx0aXBsZSAocGFpcnMpIHtcbiAgICAgIHZhciBzdWJzY3JpcHRpb25zID0ge307XG4gICAgICBmb3IgKHZhciB0b3BpYyBpbiBwYWlycykge1xuICAgICAgICBpZiAoIXBhaXJzLmhhc093blByb3BlcnR5KHRvcGljKSkgY29udGludWU7XG4gICAgICAgIHN1YnNjcmlwdGlvbnNbdG9waWNdID0gc3Vic2NyaWJlKHRvcGljLCBwYWlyc1t0b3BpY10pO1xuICAgICAgfVxuICAgICAgcmV0dXJuIHN1YnNjcmlwdGlvbnM7XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIGhpdGNoIChzdWJzY3JpYmVyLCB0b3BpYykge1xuICAgICAgcmV0dXJuIHN1YnNjcmliZSh0b3BpYywgc3Vic2NyaWJlclt0b3BpY10sIHN1YnNjcmliZXIpO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBoaXRjaE11bHRpcGxlIChzdWJzY3JpYmVyLCB0b3BpY3MpIHtcbiAgICAgIHZhciBzdWJzY3JpcHRpb25zID0gW107XG4gICAgICBmb3IgKHZhciBpID0gMCwgbCA9IHRvcGljcy5sZW5ndGg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgc3Vic2NyaXB0aW9ucy5wdXNoKCBoaXRjaChzdWJzY3JpYmVyLCB0b3BpY3NbaV0pICk7XG4gICAgICB9XG4gICAgICByZXR1cm4gc3Vic2NyaXB0aW9ucztcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gZXJhc2UgKGFyciwgdmljdGltKSB7XG4gICAgICBmb3IgKHZhciBpID0gMCwgbCA9IGFyci5sZW5ndGg7IGkgPCBsOyBpKyspe1xuICAgICAgICBpZiAoYXJyW2ldID09PSB2aWN0aW0pIGFyci5zcGxpY2UoaSwgMSk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgcmV0dXJuIG9iajtcbiAgfTtcblxuICAvLyBwdWJsaXNoZXIgaXMgYSBwdWJsaXNoZXIsIHNvIG1ldGEgLi4uXG4gIHJldHVybiBwdWJsaXNoZXIocHVibGlzaGVyKTtcbn0pKTtcbiJdfQ==
