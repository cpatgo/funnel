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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9pbWFnZXMuanMiLCJqcy9tb2R1bGVzL2FjY291bnQuanMiLCJqcy9tb2R1bGVzL2J1aWxkZXIuanMiLCJqcy9tb2R1bGVzL2NhbnZhc0VsZW1lbnQuanMiLCJqcy9tb2R1bGVzL2NvbmZpZy5qcyIsImpzL21vZHVsZXMvaW1hZ2VMaWJyYXJ5LmpzIiwianMvbW9kdWxlcy9zdHlsZWVkaXRvci5qcyIsImpzL21vZHVsZXMvdWkuanMiLCJqcy9tb2R1bGVzL3V0aWxzLmpzIiwianMvdmVuZG9yL3B1Ymxpc2hlci5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ1RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3RKQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNsaUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzNIQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN6REE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUMxTUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ2ptQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNoSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNuQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHRyZXF1aXJlKCcuL21vZHVsZXMvdWknKTtcblx0cmVxdWlyZSgnLi9tb2R1bGVzL2J1aWxkZXInKTtcblx0cmVxdWlyZSgnLi9tb2R1bGVzL2NvbmZpZycpO1xuXHRyZXF1aXJlKCcuL21vZHVsZXMvaW1hZ2VMaWJyYXJ5Jyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9hY2NvdW50Jyk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0dmFyIGFwcFVJID0gcmVxdWlyZSgnLi91aS5qcycpLmFwcFVJO1xuXG5cdHZhciBhY2NvdW50ID0ge1xuICAgICAgICBcbiAgICAgICAgYnV0dG9uVXBkYXRlQWNjb3VudERldGFpbHM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhY2NvdW50RGV0YWlsc1N1Ym1pdCcpLFxuICAgICAgICBidXR0b25VcGRhdGVMb2dpbkRldGFpbHM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhY2NvdW50TG9naW5TdWJtaXQnKSxcbiAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uVXBkYXRlQWNjb3VudERldGFpbHMpLm9uKCdjbGljaycsIHRoaXMudXBkYXRlQWNjb3VudERldGFpbHMpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblVwZGF0ZUxvZ2luRGV0YWlscykub24oJ2NsaWNrJywgdGhpcy51cGRhdGVMb2dpbkRldGFpbHMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwZGF0ZXMgYWNjb3VudCBkZXRhaWxzXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZUFjY291bnREZXRhaWxzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hbGwgZmllbGRzIGZpbGxlZCBpbj9cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjbGFzdG5hbWUnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNsYXN0bmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2xhc3RuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG5cdFx0XG4gICAgICAgICAgICBpZiggYWxsR29vZCA9PT0gMSApIHtcblxuICAgICAgICAgICAgICAgIHZhciB0aGVCdXR0b24gPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2hvdyBsb2FkZXJcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5sb2FkZXInKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBhbGVydHNcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5hbGVydHMgPiAqJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1widXNlcnMvdWFjY291bnRcIixcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bvc3QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiAkKCcjYWNjb3VudF9kZXRhaWxzJykuc2VyaWFsaXplKClcbiAgICAgICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAgICAgdGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9oaWRlIGxvYWRlclxuICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5sb2FkZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vc3VjY2Vzc1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7IFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmFsZXJ0cyA+IConKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24gKCkgeyAkKHRoaXMpLnJlbW92ZSgpOyB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sIDMwMDApO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGRhdGVzIGFjY291bnQgbG9naW4gZGV0YWlsc1xuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVMb2dpbkRldGFpbHM6IGZ1bmN0aW9uKCkge1xuXHRcdFx0XG5cdFx0XHRjb25zb2xlLmxvZyhhcHBVSSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDA7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggYWxsR29vZCA9PT0gMSApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgdGhlQnV0dG9uID0gJCh0aGlzKTtcblxuICAgICAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2hvdyBsb2FkZXJcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAubG9hZGVyJykuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgYWxlcnRzXG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmFsZXJ0cyA+IConKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJ1c2Vycy91bG9naW5cIixcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bvc3QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiAkKCcjYWNjb3VudF9sb2dpbicpLnNlcmlhbGl6ZSgpXG4gICAgICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXQpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgICAgIHRoZUJ1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vaGlkZSBsb2FkZXJcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmxvYWRlcicpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuXHRcdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL3N1Y2Nlc3NcbiAgICAgICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkgeyBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAuYWxlcnRzID4gKicpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbiAoKSB7ICQodGhpcykucmVtb3ZlKCk7IH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSwgMzAwMCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcbiAgICBcbiAgICBhY2NvdW50LmluaXQoKTtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuICAgIHZhciBzaXRlQnVpbGRlclV0aWxzID0gcmVxdWlyZSgnLi91dGlscy5qcycpO1xuICAgIHZhciBiQ29uZmlnID0gcmVxdWlyZSgnLi9jb25maWcuanMnKTtcbiAgICB2YXIgYXBwVUkgPSByZXF1aXJlKCcuL3VpLmpzJykuYXBwVUk7XG4gICAgdmFyIHB1Ymxpc2hlciA9IHJlcXVpcmUoJy4uL3ZlbmRvci9wdWJsaXNoZXInKTtcbiAgICB2YXIgZm9ybV9pZCA9IDA7XG5cblx0IC8qXG4gICAgICAgIEJhc2ljIEJ1aWxkZXIgVUkgaW5pdGlhbGlzYXRpb25cbiAgICAqL1xuICAgIHZhciBidWlsZGVyVUkgPSB7XG4gICAgICAgIFxuICAgICAgICBhbGxCbG9ja3M6IHt9LCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hvbGRzIGFsbCBibG9ja3MgbG9hZGVkIGZyb20gdGhlIHNlcnZlclxuICAgICAgICBtZW51V3JhcHBlcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21lbnUnKSxcbiAgICAgICAgcHJpbWFyeVNpZGVNZW51V3JhcHBlcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21haW4nKSxcbiAgICAgICAgYnV0dG9uQmFjazogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhY2tCdXR0b24nKSxcbiAgICAgICAgYnV0dG9uQmFja0NvbmZpcm06IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsZWF2ZVBhZ2VCdXR0b24nKSxcbiAgICAgICAgXG4gICAgICAgIGFjZUVkaXRvcnM6IHt9LFxuICAgICAgICBmcmFtZUNvbnRlbnRzOiAnJywgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vaG9sZHMgZnJhbWUgY29udGVudHNcbiAgICAgICAgdGVtcGxhdGVJRDogMCwgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hvbGRzIHRoZSB0ZW1wbGF0ZSBJRCBmb3IgYSBwYWdlICg/Pz8pXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIG1vZGFsRGVsZXRlQmxvY2s6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVCbG9jaycpLFxuICAgICAgICBtb2RhbFJlc2V0QmxvY2s6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdyZXNldEJsb2NrJyksXG4gICAgICAgIG1vZGFsRGVsZXRlUGFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZVBhZ2UnKSxcbiAgICAgICAgYnV0dG9uRGVsZXRlUGFnZUNvbmZpcm06IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVQYWdlQ29uZmlybScpLFxuICAgICAgICBcbiAgICAgICAgZHJvcGRvd25QYWdlTGlua3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzRHJvcGRvd24nKSxcblxuICAgICAgICBwYWdlSW5Vcmw6IG51bGwsXG4gICAgICAgIFxuICAgICAgICB0ZW1wRnJhbWU6IHt9LFxuXG4gICAgICAgIGN1cnJlbnRSZXNwb25zaXZlTW9kZToge30sXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9hZCBibG9ja3NcbiAgICAgICAgICAgICQuZ2V0SlNPTihhcHBVSS5iYXNlVXJsKydlbGVtZW50cy5qc29uP3Y9MTIzNDU2NzgnLCBmdW5jdGlvbihkYXRhKXsgYnVpbGRlclVJLmFsbEJsb2NrcyA9IGRhdGE7IGJ1aWxkZXJVSS5pbXBsZW1lbnRCbG9ja3MoKTsgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2l0ZWJhciBob3ZlciBhbmltYXRpb24gYWN0aW9uXG4gICAgICAgICAgICAkKHRoaXMubWVudVdyYXBwZXIpLm9uKCdtb3VzZWVudGVyJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnN0b3AoKS5hbmltYXRlKHsnbGVmdCc6ICcwcHgnfSwgNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnN0b3AoKS5hbmltYXRlKHsnbGVmdCc6ICctMTkwcHgnfSwgNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjbWVudSAjbWFpbiBhJykucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICQoJy5tZW51IC5zZWNvbmQnKS5zdG9wKCkuYW5pbWF0ZSh7XG4gICAgICAgICAgICAgICAgICAgIHdpZHRoOiAwXG4gICAgICAgICAgICAgICAgfSwgNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcHJldmVudCBjbGljayBldmVudCBvbiBhbmNvcnMgaW4gdGhlIGJsb2NrIHNlY3Rpb24gb2YgdGhlIHNpZGViYXJcbiAgICAgICAgICAgICQodGhpcy5wcmltYXJ5U2lkZU1lbnVXcmFwcGVyKS5vbignY2xpY2snLCAnYTpub3QoLmFjdGlvbkJ1dHRvbnMpJywgZnVuY3Rpb24oZSl7ZS5wcmV2ZW50RGVmYXVsdCgpO30pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uQmFjaykub24oJ2NsaWNrJywgdGhpcy5iYWNrQnV0dG9uKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25CYWNrQ29uZmlybSkub24oJ2NsaWNrJywgdGhpcy5iYWNrQnV0dG9uQ29uZmlybSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbm90aWZ5IHRoZSB1c2VyIG9mIHBlbmRpbmcgY2huYWdlcyB3aGVuIGNsaWNraW5nIHRoZSBiYWNrIGJ1dHRvblxuICAgICAgICAgICAgJCh3aW5kb3cpLmJpbmQoJ2JlZm9yZXVubG9hZCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYoIHNpdGUucGVuZGluZ0NoYW5nZXMgPT09IHRydWUgKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnWW91ciBzaXRlIGNvbnRhaW5zIGNoYW5nZWQgd2hpY2ggaGF2ZW5cXCd0IGJlZW4gc2F2ZWQgeWV0LiBBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gbGVhdmU/JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9VUkwgcGFyYW1ldGVyc1xuICAgICAgICAgICAgYnVpbGRlclVJLnBhZ2VJblVybCA9IHNpdGVCdWlsZGVyVXRpbHMuZ2V0UGFyYW1ldGVyQnlOYW1lKCdwJyk7XG5cbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYnVpbGRzIHRoZSBibG9ja3MgaW50byB0aGUgc2l0ZSBiYXJcbiAgICAgICAgKi9cbiAgICAgICAgaW1wbGVtZW50QmxvY2tzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgdmFyIG5ld0l0ZW0sIGxvYWRlckZ1bmN0aW9uO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBrZXkgaW4gdGhpcy5hbGxCbG9ja3MuZWxlbWVudHMgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIG5pY2VLZXkgPSBrZXkudG9Mb3dlckNhc2UoKS5yZXBsYWNlKFwiIFwiLCBcIl9cIik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnPGxpPjxhIGhyZWY9XCJcIiBpZD1cIicrbmljZUtleSsnXCI+JytrZXkrJzwvYT48L2xpPicpLmFwcGVuZFRvKCcjbWVudSAjbWFpbiB1bCNlbGVtZW50Q2F0cycpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XS5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnRodW1ibmFpbCA9PT0gbnVsbCApIHsvL3dlJ2xsIG5lZWQgYW4gaWZyYW1lXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vYnVpbGQgdXMgc29tZSBpZnJhbWVzIVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsb2FkZXJGdW5jdGlvbiA9ICdkYXRhLWxvYWRlcmZ1bmN0aW9uPVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uKydcIic7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0gPSAkKCc8bGkgY2xhc3M9XCJlbGVtZW50ICcrbmljZUtleSsnXCI+PGlmcmFtZSBzcmM9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIHNjcm9sbGluZz1cIm5vXCIgc2FuZGJveD1cImFsbG93LXNhbWUtb3JpZ2luXCI+PC9pZnJhbWU+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpZnJhbWUgc3JjPVwiYWJvdXQ6YmxhbmtcIiBzY3JvbGxpbmc9XCJub1wiPjwvaWZyYW1lPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdzcmMnLCBhcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udXJsKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7Ly93ZSd2ZSBnb3QgYSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbiApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbG9hZGVyRnVuY3Rpb24gPSAnZGF0YS1sb2FkZXJmdW5jdGlvbj1cIicrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbisnXCInO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpbWcgc3JjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udGh1bWJuYWlsKydcIiBkYXRhLXNyY2M9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIGRhdGEtaGVpZ2h0PVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCsnXCIgZGF0YS1zYW5kYm94PVwiXCIgJytsb2FkZXJGdW5jdGlvbisnPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpbWcgc3JjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udGh1bWJuYWlsKydcIiBkYXRhLXNyY2M9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIGRhdGEtaGVpZ2h0PVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCsnXCI+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0uYXBwZW5kVG8oJyNtZW51ICNzZWNvbmQgdWwjZWxlbWVudHMnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3pvb21lciB3b3Jrc1xuXG4gICAgICAgICAgICAgICAgICAgIHZhciB0aGVIZWlnaHQ7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5oZWlnaHQgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZUhlaWdodCA9IHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uaGVpZ2h0KjAuMjU7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVIZWlnaHQgPSAnYXV0byc7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS56b29tZXIoe1xuICAgICAgICAgICAgICAgICAgICAgICAgem9vbTogMC4yNSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHdpZHRoOiAyNzAsXG4gICAgICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IHRoZUhlaWdodCxcbiAgICAgICAgICAgICAgICAgICAgICAgIG1lc3NhZ2U6IFwiRHJhZyZEcm9wIE1lIVwiXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2RyYWdnYWJsZXNcbiAgICAgICAgICAgIGJ1aWxkZXJVSS5tYWtlRHJhZ2dhYmxlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBldmVudCBoYW5kbGVyIGZvciB3aGVuIHRoZSBiYWNrIGxpbmsgaXMgY2xpY2tlZFxuICAgICAgICAqL1xuICAgICAgICBiYWNrQnV0dG9uOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHNpdGUucGVuZGluZ0NoYW5nZXMgPT09IHRydWUgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2JhY2tNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGJ1dHRvbiBmb3IgY29uZmlybWluZyBsZWF2aW5nIHRoZSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIGJhY2tCdXR0b25Db25maXJtOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5wZW5kaW5nQ2hhbmdlcyA9IGZhbHNlOy8vcHJldmVudCB0aGUgSlMgYWxlcnQgYWZ0ZXIgY29uZmlybWluZyB1c2VyIHdhbnRzIHRvIGxlYXZlXG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIG1ha2VzIHRoZSBibG9ja3MgYW5kIHRlbXBsYXRlcyBpbiB0aGUgc2lkZWJhciBkcmFnZ2FibGUgb250byB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIG1ha2VEcmFnZ2FibGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCcjZWxlbWVudHMgbGksICN0ZW1wbGF0ZXMgbGknKS5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmRyYWdnYWJsZSh7XG4gICAgICAgICAgICAgICAgICAgIGhlbHBlcjogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gJCgnPGRpdiBzdHlsZT1cImhlaWdodDogMTAwcHg7IHdpZHRoOiAzMDBweDsgYmFja2dyb3VuZDogI0Y5RkFGQTsgYm94LXNoYWRvdzogNXB4IDVweCAxcHggcmdiYSgwLDAsMCwwLjEpOyB0ZXh0LWFsaWduOiBjZW50ZXI7IGxpbmUtaGVpZ2h0OiAxMDBweDsgZm9udC1zaXplOiAyOHB4OyBjb2xvcjogIzE2QTA4NVwiPjxzcGFuIGNsYXNzPVwiZnVpLWxpc3RcIj48L3NwYW4+PC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHJldmVydDogJ2ludmFsaWQnLFxuICAgICAgICAgICAgICAgICAgICBhcHBlbmRUbzogJ2JvZHknLFxuICAgICAgICAgICAgICAgICAgICBjb25uZWN0VG9Tb3J0YWJsZTogJyNwYWdlTGlzdCA+IHVsJyxcbiAgICAgICAgICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUubW92ZU1vZGUoJ29uJyk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN0b3A6IGZ1bmN0aW9uICgpIHt9XG4gICAgICAgICAgICAgICAgfSk7IFxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI2VsZW1lbnRzIGxpIGEnKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS51bmJpbmQoJ2NsaWNrJykuYmluZCgnY2xpY2snLCBmdW5jdGlvbihlKXtcbiAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBJbXBsZW1lbnRzIHRoZSBzaXRlIG9uIHRoZSBjYW52YXMsIGNhbGxlZCBmcm9tIHRoZSBTaXRlIG9iamVjdCB3aGVuIHRoZSBzaXRlRGF0YSBoYXMgY29tcGxldGVkIGxvYWRpbmdcbiAgICAgICAgKi9cbiAgICAgICAgcG9wdWxhdGVDYW52YXM6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICB2YXIgaSxcbiAgICAgICAgICAgICAgICBjb3VudGVyID0gMTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sb29wIHRocm91Z2ggdGhlIHBhZ2VzXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggaSBpbiBzaXRlLnBhZ2VzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBuZXdQYWdlID0gbmV3IFBhZ2UoaSwgc2l0ZS5wYWdlc1tpXSwgY291bnRlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGNvdW50ZXIrKztcblxuICAgICAgICAgICAgICAgIC8vc2V0IHRoaXMgcGFnZSBhcyBhY3RpdmU/XG4gICAgICAgICAgICAgICAgaWYoIGJ1aWxkZXJVSS5wYWdlSW5VcmwgPT09IGkgKSB7XG4gICAgICAgICAgICAgICAgICAgIG5ld1BhZ2Uuc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYWN0aXZhdGUgdGhlIGZpcnN0IHBhZ2VcbiAgICAgICAgICAgIGlmKHNpdGUuc2l0ZVBhZ2VzLmxlbmd0aCA+IDAgJiYgYnVpbGRlclVJLnBhZ2VJblVybCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzWzBdLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIENhbnZhcyBsb2FkaW5nIG9uL29mZlxuICAgICAgICAqL1xuICAgICAgICBjYW52YXNMb2FkaW5nOiBmdW5jdGlvbiAodmFsdWUpIHtcblxuICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gJ29uJyAmJiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJykucXVlcnlTZWxlY3RvckFsbCgnI2NhbnZhc092ZXJsYXknKS5sZW5ndGggPT09IDAgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgb3ZlcmxheSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuXG4gICAgICAgICAgICAgICAgb3ZlcmxheS5zdHlsZS5kaXNwbGF5ID0gJ2ZsZXgnO1xuICAgICAgICAgICAgICAgICQob3ZlcmxheSkuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG92ZXJsYXkuaWQgPSAnY2FudmFzT3ZlcmxheSc7XG5cbiAgICAgICAgICAgICAgICBvdmVybGF5LmlubmVySFRNTCA9ICc8ZGl2IGNsYXNzPVwibG9hZGVyXCI+PHNwYW4+ezwvc3Bhbj48c3Bhbj59PC9zcGFuPjwvZGl2Pic7XG5cbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJykuYXBwZW5kQ2hpbGQob3ZlcmxheSk7XG5cbiAgICAgICAgICAgICAgICAkKCcjY2FudmFzT3ZlcmxheScpLmZhZGVJbig1MDApO1xuXG4gICAgICAgICAgICB9IGVsc2UgaWYgKCB2YWx1ZSA9PT0gJ29mZicgJiYgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZyYW1lV3JhcHBlcicpLnF1ZXJ5U2VsZWN0b3JBbGwoJyNjYW52YXNPdmVybGF5JykubGVuZ3RoID09PSAxICkge1xuXG4gICAgICAgICAgICAgICAgc2l0ZS5sb2FkZWQoKTtcblxuICAgICAgICAgICAgICAgICQoJyNjYW52YXNPdmVybGF5JykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgIGZvcm1faWQgPSAkKCcjZm9ybUlEJykudmFsKCk7XG4gICAgICAgICAgICAgICAgaWYoZm9ybV9pZCAhPT0gMCkge1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnF1aWNrX2xvYWRfZm9ybSgpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcblxuICAgIC8qXG4gICAgICAgIFBhZ2UgY29uc3RydWN0b3JcbiAgICAqL1xuICAgIGZ1bmN0aW9uIFBhZ2UgKHBhZ2VOYW1lLCBwYWdlLCBjb3VudGVyKSB7XG4gICAgXG4gICAgICAgIHRoaXMubmFtZSA9IHBhZ2VOYW1lIHx8IFwiXCI7XG4gICAgICAgIHRoaXMucGFnZUlEID0gcGFnZS5wYWdlX2lkIHx8IDA7XG4gICAgICAgIHRoaXMuYmxvY2tzID0gW107XG4gICAgICAgIHRoaXMucGFyZW50VUwgPSB7fTsgLy9wYXJlbnQgVUwgb24gdGhlIGNhbnZhc1xuICAgICAgICB0aGlzLnN0YXR1cyA9ICcnOy8vJycsICduZXcnIG9yICdjaGFuZ2VkJ1xuICAgICAgICB0aGlzLnNjcmlwdHMgPSBbXTsvL3RyYWNrcyBzY3JpcHQgVVJMcyB1c2VkIG9uIHRoaXMgcGFnZVxuICAgICAgICBcbiAgICAgICAgdGhpcy5wYWdlU2V0dGluZ3MgPSB7XG4gICAgICAgICAgICB0aXRsZTogcGFnZS5wYWdlc190aXRsZSB8fCAnJyxcbiAgICAgICAgICAgIG1ldGFfZGVzY3JpcHRpb246IHBhZ2UubWV0YV9kZXNjcmlwdGlvbiB8fCAnJyxcbiAgICAgICAgICAgIG1ldGFfa2V5d29yZHM6IHBhZ2UubWV0YV9rZXl3b3JkcyB8fCAnJyxcbiAgICAgICAgICAgIGhlYWRlcl9pbmNsdWRlczogcGFnZS5oZWFkZXJfaW5jbHVkZXMgfHwgJycsXG4gICAgICAgICAgICBwYWdlX2NzczogcGFnZS5wYWdlX2NzcyB8fCAnJ1xuICAgICAgICB9O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICB0aGlzLnBhZ2VNZW51VGVtcGxhdGUgPSAnPGEgaHJlZj1cIlwiIGNsYXNzPVwibWVudUl0ZW1MaW5rXCI+cGFnZTwvYT48c3BhbiBjbGFzcz1cInBhZ2VCdXR0b25zXCI+PGEgaHJlZj1cIlwiIGNsYXNzPVwiZmlsZUVkaXQgZnVpLW5ld1wiPjwvYT48YSBocmVmPVwiXCIgY2xhc3M9XCJmaWxlRGVsIGZ1aS1jcm9zc1wiPjxhIGNsYXNzPVwiYnRuIGJ0bi14cyBidG4tcHJpbWFyeSBidG4tZW1ib3NzZWQgZmlsZVNhdmUgZnVpLWNoZWNrXCIgaHJlZj1cIiNcIj48L2E+PC9zcGFuPjwvYT48L3NwYW4+JztcbiAgICAgICAgXG4gICAgICAgIHRoaXMubWVudUl0ZW0gPSB7fTsvL3JlZmVyZW5jZSB0byB0aGUgcGFnZXMgbWVudSBpdGVtIGZvciB0aGlzIHBhZ2UgaW5zdGFuY2VcbiAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbSA9IHt9Oy8vcmVmZXJlbmNlIHRvIHRoZSBsaW5rcyBkcm9wZG93biBpdGVtIGZvciB0aGlzIHBhZ2UgaW5zdGFuY2VcbiAgICAgICAgXG4gICAgICAgIHRoaXMucGFyZW50VUwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdVTCcpO1xuICAgICAgICB0aGlzLnBhcmVudFVMLnNldEF0dHJpYnV0ZSgnaWQnLCBcInBhZ2VcIitjb3VudGVyKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIG1ha2VzIHRoZSBjbGlja2VkIHBhZ2UgYWN0aXZlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2VsZWN0UGFnZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKCdzZWxlY3Q6Jyk7XG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKHRoaXMucGFnZVNldHRpbmdzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9tYXJrIHRoZSBtZW51IGl0ZW0gYXMgYWN0aXZlXG4gICAgICAgICAgICBzaXRlLmRlQWN0aXZhdGVBbGwoKTtcbiAgICAgICAgICAgICQodGhpcy5tZW51SXRlbSkuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xldCBTaXRlIGtub3cgd2hpY2ggcGFnZSBpcyBjdXJyZW50bHkgYWN0aXZlXG4gICAgICAgICAgICBzaXRlLnNldEFjdGl2ZSh0aGlzKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kaXNwbGF5IHRoZSBuYW1lIG9mIHRoZSBhY3RpdmUgcGFnZSBvbiB0aGUgY2FudmFzXG4gICAgICAgICAgICBzaXRlLnBhZ2VUaXRsZS5pbm5lckhUTUwgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9hZCB0aGUgcGFnZSBzZXR0aW5ncyBpbnRvIHRoZSBwYWdlIHNldHRpbmdzIG1vZGFsXG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzVGl0bGUudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy50aXRsZTtcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NNZXRhRGVzY3JpcHRpb24udmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5tZXRhX2Rlc2NyaXB0aW9uO1xuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFLZXl3b3Jkcy52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLm1ldGFfa2V5d29yZHM7XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzSW5jbHVkZXMudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5oZWFkZXJfaW5jbHVkZXM7XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzUGFnZUNzcy52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLnBhZ2VfY3NzO1xuICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vdHJpZ2dlciBjdXN0b20gZXZlbnRcbiAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdjaGFuZ2VQYWdlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVzZXQgdGhlIGhlaWdodHMgZm9yIHRoZSBibG9ja3Mgb24gdGhlIGN1cnJlbnQgcGFnZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggT2JqZWN0LmtleXModGhpcy5ibG9ja3NbaV0uZnJhbWVEb2N1bWVudCkubGVuZ3RoID4gMCApe1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrc1tpXS5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2hvdyB0aGUgZW1wdHkgbWVzc2FnZT9cbiAgICAgICAgICAgIHRoaXMuaXNFbXB0eSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2hhbmdlZCB0aGUgbG9jYXRpb24vb3JkZXIgb2YgYSBibG9jayB3aXRoaW4gYSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2V0UG9zaXRpb24gPSBmdW5jdGlvbihmcmFtZUlELCBuZXdQb3MpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy93ZSdsbCBuZWVkIHRoZSBibG9jayBvYmplY3QgY29ubmVjdGVkIHRvIGlmcmFtZSB3aXRoIGZyYW1lSURcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKHZhciBpIGluIHRoaXMuYmxvY2tzKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzW2ldLmZyYW1lLmdldEF0dHJpYnV0ZSgnaWQnKSA9PT0gZnJhbWVJRCApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vY2hhbmdlIHRoZSBwb3NpdGlvbiBvZiB0aGlzIGJsb2NrIGluIHRoZSBibG9ja3MgYXJyYXlcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ibG9ja3Muc3BsaWNlKG5ld1BvcywgMCwgdGhpcy5ibG9ja3Muc3BsaWNlKGksIDEpWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlIGJsb2NrIGZyb20gYmxvY2tzIGFycmF5XG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZGVsZXRlQmxvY2sgPSBmdW5jdGlvbihibG9jaykge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBmcm9tIGJsb2NrcyBhcnJheVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5ibG9ja3NbaV0gPT09IGJsb2NrICkge1xuICAgICAgICAgICAgICAgICAgICAvL2ZvdW5kIGl0LCByZW1vdmUgZnJvbSBibG9ja3MgYXJyYXlcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ibG9ja3Muc3BsaWNlKGksIDEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHRvZ2dsZXMgYWxsIGJsb2NrIGZyYW1lQ292ZXJzIG9uIHRoaXMgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnRvZ2dsZUZyYW1lQ292ZXJzID0gZnVuY3Rpb24ob25Pck9mZikge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5ibG9ja3NbaV0udG9nZ2xlQ292ZXIob25Pck9mZik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzZXR1cCBmb3IgZWRpdGluZyBhIHBhZ2UgbmFtZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmVkaXRQYWdlTmFtZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggIXRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0JykgKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2hpZGUgdGhlIGxpbmtcbiAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EubWVudUl0ZW1MaW5rJykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vaW5zZXJ0IHRoZSBpbnB1dCBmaWVsZFxuICAgICAgICAgICAgICAgIHZhciBuZXdJbnB1dCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQudHlwZSA9ICd0ZXh0JztcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAncGFnZScpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgndmFsdWUnLCB0aGlzLm5hbWUpO1xuICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0uaW5zZXJ0QmVmb3JlKG5ld0lucHV0LCB0aGlzLm1lbnVJdGVtLmZpcnN0Q2hpbGQpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5mb2N1cygpO1xuICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgdG1wU3RyID0gbmV3SW5wdXQuZ2V0QXR0cmlidXRlKCd2YWx1ZScpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgndmFsdWUnLCAnJyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRtcFN0cik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QuYWRkKCdlZGl0Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIFVwZGF0ZXMgdGhpcyBwYWdlJ3MgbmFtZSAoZXZlbnQgaGFuZGxlciBmb3IgdGhlIHNhdmUgYnV0dG9uKVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnVwZGF0ZVBhZ2VOYW1lRXZlbnQgPSBmdW5jdGlvbihlbCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXQnKSApIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZWwgaXMgdGhlIGNsaWNrZWQgYnV0dG9uLCB3ZSdsbCBuZWVkIGFjY2VzcyB0byB0aGUgaW5wdXRcbiAgICAgICAgICAgICAgICB2YXIgdGhlSW5wdXQgPSB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0W25hbWU9XCJwYWdlXCJdJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9tYWtlIHN1cmUgdGhlIHBhZ2UncyBuYW1lIGlzIE9LXG4gICAgICAgICAgICAgICAgaWYoIHNpdGUuY2hlY2tQYWdlTmFtZSh0aGVJbnB1dC52YWx1ZSkgKSB7XG4gICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZSA9IHNpdGUucHJlcFBhZ2VOYW1lKCB0aGVJbnB1dC52YWx1ZSApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignaW5wdXRbbmFtZT1cInBhZ2VcIl0nKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLm1lbnVJdGVtTGluaycpLmlubmVySFRNTCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLm1lbnVJdGVtTGluaycpLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LnJlbW92ZSgnZWRpdCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3VwZGF0ZSB0aGUgbGlua3MgZHJvcGRvd24gaXRlbVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnRleHQgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0uc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRoaXMubmFtZStcIi5odG1sXCIpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgdGhlIHBhZ2UgbmFtZSBvbiB0aGUgY2FudmFzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUucGFnZVRpdGxlLmlubmVySFRNTCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NoYW5nZWQgcGFnZSB0aXRsZSwgd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBhbGVydChzaXRlLnBhZ2VOYW1lRXJyb3IpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGVzIHRoaXMgZW50aXJlIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5kZWxldGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kZWxldGUgZnJvbSB0aGUgU2l0ZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiBzaXRlLnNpdGVQYWdlcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggc2l0ZS5zaXRlUGFnZXNbaV0gPT09IHRoaXMgKSB7Ly9nb3QgYSBtYXRjaCFcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIGZyb20gc2l0ZS5zaXRlUGFnZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXMuc3BsaWNlKGksIDEpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldGUgZnJvbSBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRVTC5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vYWRkIHRvIGRlbGV0ZWQgcGFnZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5wYWdlc1RvRGVsZXRlLnB1c2godGhpcy5uYW1lKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBwYWdlJ3MgbWVudSBpdGVtXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0IHRoZSBwYWdlcyBsaW5rIGRyb3Bkb3duIGl0ZW1cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vYWN0aXZhdGUgdGhlIGZpcnN0IHBhZ2VcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXNbMF0uc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9wYWdlIHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNoZWNrcyBpZiB0aGUgcGFnZSBpcyBlbXB0eSwgaWYgc28gc2hvdyB0aGUgJ2VtcHR5JyBtZXNzYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaXNFbXB0eSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGhpcy5ibG9ja3MubGVuZ3RoID09PSAwICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUubWVzc2FnZVN0YXJ0LnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgIHNpdGUuZGl2RnJhbWVXcmFwcGVyLmNsYXNzTGlzdC5hZGQoJ2VtcHR5Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzaXRlLm1lc3NhZ2VTdGFydC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIHNpdGUuZGl2RnJhbWVXcmFwcGVyLmNsYXNzTGlzdC5yZW1vdmUoJ2VtcHR5Jyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcHJlcHMvc3RyaXBzIHRoaXMgcGFnZSBkYXRhIGZvciBhIHBlbmRpbmcgYWpheCByZXF1ZXN0XG4gICAgICAgICovXG4gICAgICAgIHRoaXMucHJlcEZvclNhdmUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2UgPSB7fTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBwYWdlLm5hbWUgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICBwYWdlLnBhZ2VTZXR0aW5ncyA9IHRoaXMucGFnZVNldHRpbmdzO1xuICAgICAgICAgICAgcGFnZS5zdGF0dXMgPSB0aGlzLnN0YXR1cztcbiAgICAgICAgICAgIHBhZ2UucGFnZUlEID0gdGhpcy5wYWdlSUQ7XG4gICAgICAgICAgICBwYWdlLmJsb2NrcyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcHJvY2VzcyB0aGUgYmxvY2tzXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCB0aGlzLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBibG9jayA9IHt9O1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzW3hdLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLmZyYW1lQ29udGVudCA9IFwiPGh0bWw+XCIrJCgnI3NhbmRib3hlcyAjJyt0aGlzLmJsb2Nrc1t4XS5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJ2h0bWwnKS5odG1sKCkrXCI8L2h0bWw+XCI7XG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLnNhbmRib3ggPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5sb2FkZXJGdW5jdGlvbiA9IHRoaXMuYmxvY2tzW3hdLnNhbmRib3hfbG9hZGVyO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBibG9jay5mcmFtZUNvbnRlbnQgPSB0aGlzLmJsb2Nrc1t4XS5nZXRTb3VyY2UoKTtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2suc2FuZGJveCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5sb2FkZXJGdW5jdGlvbiA9ICcnO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmZyYW1lSGVpZ2h0ID0gdGhpcy5ibG9ja3NbeF0uZnJhbWVIZWlnaHQ7XG4gICAgICAgICAgICAgICAgYmxvY2sub3JpZ2luYWxVcmwgPSB0aGlzLmJsb2Nrc1t4XS5vcmlnaW5hbFVybDtcbiAgICAgICAgICAgICAgICBpZiAoIHRoaXMuYmxvY2tzW3hdLmdsb2JhbCApIGJsb2NrLmZyYW1lc19nbG9iYWwgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHBhZ2UuYmxvY2tzLnB1c2goYmxvY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBwYWdlO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGdlbmVyYXRlcyB0aGUgZnVsbCBwYWdlLCB1c2luZyBza2VsZXRvbi5odG1sXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZnVsbFBhZ2UgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2UgPSB0aGlzOy8vcmVmZXJlbmNlIHRvIHNlbGYgZm9yIGxhdGVyXG4gICAgICAgICAgICBwYWdlLnNjcmlwdHMgPSBbXTsvL21ha2Ugc3VyZSBpdCdzIGVtcHR5LCB3ZSdsbCBzdG9yZSBzY3JpcHQgVVJMcyBpbiB0aGVyZSBsYXRlclxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgbmV3RG9jTWFpblBhcmVudCA9ICQoJ2lmcmFtZSNza2VsZXRvbicpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZW1wdHkgb3V0IHRoZSBza2VsZXRvbiBmaXJzdFxuICAgICAgICAgICAgJCgnaWZyYW1lI3NrZWxldG9uJykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5odG1sKCcnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIHNjcmlwdCB0YWdzXG4gICAgICAgICAgICAkKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoICdzY3JpcHQnICkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdmFyIHRoZUNvbnRlbnRzO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZ3JhYiB0aGUgYmxvY2sgY29udGVudFxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmJsb2Nrc1tpXS5zYW5kYm94ICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMgPSAkKCcjc2FuZGJveGVzICMnK3RoaXMuYmxvY2tzW2ldLnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMgPSAkKHRoaXMuYmxvY2tzW2ldLmZyYW1lRG9jdW1lbnQuYm9keSkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIHZpZGVvIGZyYW1lQ292ZXJzXG4gICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCgnLmZyYW1lQ292ZXInKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSB2aWRlbyBmcmFtZVdyYXBwZXJzXG4gICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCgnLnZpZGVvV3JhcHBlcicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHZhciBjbnQgPSAkKHRoaXMpLmNvbnRlbnRzKCk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykucmVwbGFjZVdpdGgoY250KTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgc3R5bGUgbGVmdG92ZXJzIGZyb20gdGhlIHN0eWxlIGVkaXRvclxuICAgICAgICAgICAgICAgIGZvciggdmFyIGtleSBpbiBiQ29uZmlnLmVkaXRhYmxlSXRlbXMgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoIGtleSApLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdkYXRhLXNlbGVjdG9yJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdvdXRsaW5lJywgJycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ291dGxpbmUtb2Zmc2V0JywgJycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ2N1cnNvcicsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykuYXR0cignc3R5bGUnKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgc3R5bGUgbGVmdG92ZXJzIGZyb20gdGhlIGNvbnRlbnQgZWRpdG9yXG4gICAgICAgICAgICAgICAgZm9yICggdmFyIHggPSAwOyB4IDwgYkNvbmZpZy5lZGl0YWJsZUNvbnRlbnQubGVuZ3RoOyArK3gpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoIGJDb25maWcuZWRpdGFibGVDb250ZW50W3hdICkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUF0dHIoJ2RhdGEtc2VsZWN0b3InKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2FwcGVuZCB0byBET00gaW4gdGhlIHNrZWxldG9uXG4gICAgICAgICAgICAgICAgbmV3RG9jTWFpblBhcmVudC5hcHBlbmQoICQodGhlQ29udGVudHMuaHRtbCgpKSApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZG8gd2UgbmVlZCB0byBpbmplY3QgYW55IHNjcmlwdHM/XG4gICAgICAgICAgICAgICAgdmFyIHNjcmlwdHMgPSAkKHRoaXMuYmxvY2tzW2ldLmZyYW1lRG9jdW1lbnQuYm9keSkuZmluZCgnc2NyaXB0Jyk7XG4gICAgICAgICAgICAgICAgdmFyIHRoZUlmcmFtZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwic2tlbGV0b25cIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBzY3JpcHRzLnNpemUoKSA+IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBzY3JpcHRzLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHNjcmlwdDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykudGV4dCgpICE9PSAnJyApIHsvL3NjcmlwdCB0YWdzIHdpdGggY29udGVudFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdCA9IHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzY3JpcHRcIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQuaW5uZXJIVE1MID0gJCh0aGlzKS50ZXh0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChzY3JpcHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoICQodGhpcykuYXR0cignc3JjJykgIT09IG51bGwgJiYgcGFnZS5zY3JpcHRzLmluZGV4T2YoJCh0aGlzKS5hdHRyKCdzcmMnKSkgPT09IC0xICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vdXNlIGluZGV4T2YgdG8gbWFrZSBzdXJlIGVhY2ggc2NyaXB0IG9ubHkgYXBwZWFycyBvbiB0aGUgcHJvZHVjZWQgcGFnZSBvbmNlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0ID0gdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNjcmlwdFwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQudHlwZSA9ICd0ZXh0L2phdmFzY3JpcHQnO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC5zcmMgPSAkKHRoaXMpLmF0dHIoJ3NyYycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc2NyaXB0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYWdlLnNjcmlwdHMucHVzaCgkKHRoaXMpLmF0dHIoJ3NyYycpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBDaGVja3MgaWYgYWxsIGJsb2NrcyBvbiB0aGlzIHBhZ2UgaGF2ZSBmaW5pc2hlZCBsb2FkaW5nXG4gICAgICAgICovXG4gICAgICAgIHRoaXMubG9hZGVkID0gZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPHRoaXMuYmxvY2tzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCAhdGhpcy5ibG9ja3NbaV0ubG9hZGVkICkgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNsZWFyIG91dCB0aGlzIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jbGVhciA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYmxvY2sgPSB0aGlzLmJsb2Nrcy5wb3AoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgd2hpbGUoIGJsb2NrICE9PSB1bmRlZmluZWQgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suZGVsZXRlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2sgPSB0aGlzLmJsb2Nrcy5wb3AoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEhlaWdodCBhZGp1c3RtZW50IGZvciBhbGwgYmxvY2tzIG9uIHRoZSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgZm9yICggdmFyIGkgPSAwOyBpIDwgdGhpcy5ibG9ja3MubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5ibG9ja3NbaV0uaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG4gICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8vbG9vcCB0aHJvdWdoIHRoZSBmcmFtZXMvYmxvY2tzXG4gICAgICAgIFxuICAgICAgICBpZiggcGFnZS5oYXNPd25Qcm9wZXJ0eSgnYmxvY2tzJykgKSB7XG4gICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCBwYWdlLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vY3JlYXRlIG5ldyBCbG9ja1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIG5ld0Jsb2NrID0gbmV3IEJsb2NrKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBwYWdlLmJsb2Nrc1t4XS5zcmMgPSBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvZ2V0ZnJhbWUvXCIrcGFnZS5ibG9ja3NbeF0uZnJhbWVzX2lkO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2FuZGJveGVkIGJsb2NrP1xuICAgICAgICAgICAgICAgIGlmKCBwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfc2FuZGJveCA9PT0gJzEnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLnNhbmRib3ggPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5zYW5kYm94X2xvYWRlciA9IHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19sb2FkZXJmdW5jdGlvbjtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgbmV3QmxvY2suZnJhbWVJRCA9IHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19pZDtcbiAgICAgICAgICAgICAgICBpZiAoIHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19nbG9iYWwgPT09ICcxJyApIG5ld0Jsb2NrLmdsb2JhbCA9IHRydWU7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlUGFyZW50TEkocGFnZS5ibG9ja3NbeF0uZnJhbWVzX2hlaWdodCk7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlRnJhbWUocGFnZS5ibG9ja3NbeF0pO1xuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZUZyYW1lQ292ZXIoKTtcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5pbnNlcnRCbG9ja0ludG9Eb20odGhpcy5wYXJlbnRVTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vYWRkIHRoZSBibG9jayB0byB0aGUgbmV3IHBhZ2VcbiAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrcy5wdXNoKG5ld0Jsb2NrKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgICAgIFxuICAgICAgICAvL2FkZCB0aGlzIHBhZ2UgdG8gdGhlIHNpdGUgb2JqZWN0XG4gICAgICAgIHNpdGUuc2l0ZVBhZ2VzLnB1c2goIHRoaXMgKTtcbiAgICAgICAgXG4gICAgICAgIC8vcGxhbnQgdGhlIG5ldyBVTCBpbiB0aGUgRE9NIChvbiB0aGUgY2FudmFzKVxuICAgICAgICBzaXRlLmRpdkNhbnZhcy5hcHBlbmRDaGlsZCh0aGlzLnBhcmVudFVMKTtcbiAgICAgICAgXG4gICAgICAgIC8vbWFrZSB0aGUgYmxvY2tzL2ZyYW1lcyBpbiBlYWNoIHBhZ2Ugc29ydGFibGVcbiAgICAgICAgXG4gICAgICAgIHZhciB0aGVQYWdlID0gdGhpcztcbiAgICAgICAgXG4gICAgICAgICQodGhpcy5wYXJlbnRVTCkuc29ydGFibGUoe1xuICAgICAgICAgICAgcmV2ZXJ0OiB0cnVlLFxuICAgICAgICAgICAgcGxhY2Vob2xkZXI6IFwiZHJvcC1ob3ZlclwiLFxuICAgICAgICAgICAgaGFuZGxlOiAnLmRyYWdCbG9jaycsXG4gICAgICAgICAgICBjYW5jZWw6ICcnLFxuICAgICAgICAgICAgc3RvcDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHNpdGUubW92ZU1vZGUoJ29mZicpO1xuICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgaWYgKCAhc2l0ZS5sb2FkZWQoKSApIGJ1aWxkZXJVSS5jYW52YXNMb2FkaW5nKCdvbicpO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGJlZm9yZVN0b3A6IGZ1bmN0aW9uKGV2ZW50LCB1aSl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy90ZW1wbGF0ZSBvciByZWd1bGFyIGJsb2NrP1xuICAgICAgICAgICAgICAgIHZhciBhdHRyID0gdWkuaXRlbS5hdHRyKCdkYXRhLWZyYW1lcycpO1xuXG4gICAgICAgICAgICAgICAgdmFyIG5ld0Jsb2NrO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGF0dHIgIT09IHR5cGVvZiB1bmRlZmluZWQgJiYgYXR0ciAhPT0gZmFsc2UpIHsvL3RlbXBsYXRlLCBidWlsZCBpdFxuICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJCgnI3N0YXJ0JykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NsZWFyIG91dCBhbGwgYmxvY2tzIG9uIHRoaXMgcGFnZSAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlUGFnZS5jbGVhcigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9jcmVhdGUgdGhlIG5ldyBmcmFtZXNcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZyYW1lSURzID0gdWkuaXRlbS5hdHRyKCdkYXRhLWZyYW1lcycpLnNwbGl0KCctJyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBoZWlnaHRzID0gdWkuaXRlbS5hdHRyKCdkYXRhLWhlaWdodHMnKS5zcGxpdCgnLScpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgdXJscyA9IHVpLml0ZW0uYXR0cignZGF0YS1vcmlnaW5hbHVybHMnKS5zcGxpdCgnLScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgZnJhbWVJRHMubGVuZ3RoOyB4KyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2sgPSBuZXcgQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZVBhcmVudExJKGhlaWdodHNbeF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZnJhbWVEYXRhID0ge307XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5zcmMgPSBhcHBVSS5zaXRlVXJsKydzaXRlcy9nZXRmcmFtZS8nK2ZyYW1lSURzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19vcmlnaW5hbF91cmwgPSBhcHBVSS5zaXRlVXJsKydzaXRlcy9nZXRmcmFtZS8nK2ZyYW1lSURzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19oZWlnaHQgPSBoZWlnaHRzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZSggZnJhbWVEYXRhICk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZUNvdmVyKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5pbnNlcnRCbG9ja0ludG9Eb20odGhlUGFnZS5wYXJlbnRVTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vYWRkIHRoZSBibG9jayB0byB0aGUgbmV3IHBhZ2VcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZVBhZ2UuYmxvY2tzLnB1c2gobmV3QmxvY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2Ryb3BwZWQgZWxlbWVudCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3NldCB0aGUgdGVtcGF0ZUlEXG4gICAgICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS50ZW1wbGF0ZUlEID0gdWkuaXRlbS5hdHRyKCdkYXRhLXBhZ2VpZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vbWFrZSBzdXJlIG5vdGhpbmcgZ2V0cyBkcm9wcGVkIGluIHRoZSBsc2l0XG4gICAgICAgICAgICAgICAgICAgIHVpLml0ZW0uaHRtbChudWxsKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0ZSBkcmFnIHBsYWNlIGhvbGRlclxuICAgICAgICAgICAgICAgICAgICAkKCdib2R5IC51aS1zb3J0YWJsZS1oZWxwZXInKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHsvL3JlZ3VsYXIgYmxvY2tcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9hcmUgd2UgZGVhbGluZyB3aXRoIGEgbmV3IGJsb2NrIGJlaW5nIGRyb3BwZWQgb250byB0aGUgY2FudmFzLCBvciBhIHJlb3JkZXJpbmcgb2cgYmxvY2tzIGFscmVhZHkgb24gdGhlIGNhbnZhcz9cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnLmZyYW1lQ292ZXIgPiBidXR0b24nKS5zaXplKCkgPiAwICkgey8vcmUtb3JkZXJpbmcgb2YgYmxvY2tzIG9uIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vbm8gbmVlZCB0byBjcmVhdGUgYSBuZXcgYmxvY2sgb2JqZWN0LCB3ZSBzaW1wbHkgbmVlZCB0byBtYWtlIHN1cmUgdGhlIHBvc2l0aW9uIG9mIHRoZSBleGlzdGluZyBibG9jayBpbiB0aGUgU2l0ZSBvYmplY3RcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vaXMgY2hhbmdlZCB0byByZWZsZWN0IHRoZSBuZXcgcG9zaXRpb24gb2YgdGhlIGJsb2NrIG9uIHRoIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBmcmFtZUlEID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld1BvcyA9IHVpLml0ZW0uaW5kZXgoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2Uuc2V0UG9zaXRpb24oZnJhbWVJRCwgbmV3UG9zKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHsvL25ldyBibG9jayBvbiBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9uZXcgYmxvY2sgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2sgPSBuZXcgQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5wbGFjZU9uQ2FudmFzKHVpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBzdGFydDogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuXG4gICAgICAgICAgICAgICAgc2l0ZS5tb3ZlTW9kZSgnb24nKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnLmZyYW1lQ292ZXInKS5zaXplKCkgIT09IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS5mcmFtZUNvbnRlbnRzID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmh0bWwoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBvdmVyOiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjc3RhcnQnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBcbiAgICAgICAgLy9hZGQgdG8gdGhlIHBhZ2VzIG1lbnVcbiAgICAgICAgdGhpcy5tZW51SXRlbSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0xJJyk7XG4gICAgICAgIHRoaXMubWVudUl0ZW0uaW5uZXJIVE1MID0gdGhpcy5wYWdlTWVudVRlbXBsYXRlO1xuICAgICAgICBcbiAgICAgICAgJCh0aGlzLm1lbnVJdGVtKS5maW5kKCdhOmZpcnN0JykudGV4dChwYWdlTmFtZSkuYXR0cignaHJlZicsICcjcGFnZScrY291bnRlcik7XG4gICAgICAgIFxuICAgICAgICB2YXIgdGhlTGluayA9ICQodGhpcy5tZW51SXRlbSkuZmluZCgnYTpmaXJzdCcpLmdldCgwKTtcbiAgICAgICAgXG4gICAgICAgIC8vYmluZCBzb21lIGV2ZW50c1xuICAgICAgICB0aGlzLm1lbnVJdGVtLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLmZpbGVFZGl0JykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5maWxlU2F2ZScpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EuZmlsZURlbCcpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICBcbiAgICAgICAgLy9hZGQgdG8gdGhlIHBhZ2UgbGluayBkcm9wZG93blxuICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0uc2V0QXR0cmlidXRlKCd2YWx1ZScsIHBhZ2VOYW1lK1wiLmh0bWxcIik7XG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0udGV4dCA9IHBhZ2VOYW1lO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICBidWlsZGVyVUkuZHJvcGRvd25QYWdlTGlua3MuYXBwZW5kQ2hpbGQoIHRoaXMubGlua3NEcm9wZG93bkl0ZW0gKTtcbiAgICAgICAgXG4gICAgICAgIHNpdGUucGFnZXNNZW51LmFwcGVuZENoaWxkKHRoaXMubWVudUl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICB9XG4gICAgXG4gICAgUGFnZS5wcm90b3R5cGUuaGFuZGxlRXZlbnQgPSBmdW5jdGlvbihldmVudCkge1xuICAgICAgICBzd2l0Y2ggKGV2ZW50LnR5cGUpIHtcbiAgICAgICAgICAgIGNhc2UgXCJjbGlja1wiOiBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbGVFZGl0JykgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWRpdFBhZ2VOYW1lKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZmlsZVNhdmUnKSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy51cGRhdGVQYWdlTmFtZUV2ZW50KGV2ZW50LnRhcmdldCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaWxlRGVsJykgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB2YXIgdGhlUGFnZSA9IHRoaXM7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkubW9kYWwoJ3Nob3cnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkub2ZmKCdjbGljaycsICcjZGVsZXRlUGFnZUNvbmZpcm0nKS5vbignY2xpY2snLCAnI2RlbGV0ZVBhZ2VDb25maXJtJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZVBhZ2UuZGVsZXRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgfTtcblxuXG4gICAgLypcbiAgICAgICAgQmxvY2sgY29uc3RydWN0b3JcbiAgICAqL1xuICAgIGZ1bmN0aW9uIEJsb2NrICgpIHtcbiAgICAgICAgXG4gICAgICAgIHRoaXMuZnJhbWVJRCA9IDA7XG4gICAgICAgIHRoaXMubG9hZGVkID0gZmFsc2U7XG4gICAgICAgIHRoaXMuc2FuZGJveCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnNhbmRib3hfbG9hZGVyID0gJyc7XG4gICAgICAgIHRoaXMuc3RhdHVzID0gJyc7Ly8nJywgJ2NoYW5nZWQnIG9yICduZXcnXG4gICAgICAgIHRoaXMuZ2xvYmFsID0gZmFsc2U7XG4gICAgICAgIHRoaXMub3JpZ2luYWxVcmwgPSAnJztcbiAgICAgICAgXG4gICAgICAgIHRoaXMucGFyZW50TEkgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZUNvdmVyID0ge307XG4gICAgICAgIHRoaXMuZnJhbWUgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50ID0ge307XG4gICAgICAgIHRoaXMuZnJhbWVIZWlnaHQgPSAwO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5hbm5vdCA9IHt9O1xuICAgICAgICB0aGlzLmFubm90VGltZW91dCA9IHt9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNyZWF0ZXMgdGhlIHBhcmVudCBjb250YWluZXIgKExJKVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNyZWF0ZVBhcmVudExJID0gZnVuY3Rpb24oaGVpZ2h0KSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMSScpO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgIC8vdGhpcy5wYXJlbnRMSS5zZXRBdHRyaWJ1dGUoJ3N0eWxlJywgJ2hlaWdodDogJytoZWlnaHQrJ3B4Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjcmVhdGVzIHRoZSBpZnJhbWUgb24gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lID0gZnVuY3Rpb24oZnJhbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5mcmFtZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0lGUkFNRScpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2ZyYW1lYm9yZGVyJywgMCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnc2Nyb2xsaW5nJywgMCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnc3JjJywgZnJhbWUuc3JjKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLW9yaWdpbmFsdXJsJywgZnJhbWUuZnJhbWVzX29yaWdpbmFsX3VybCk7XG4gICAgICAgICAgICB0aGlzLm9yaWdpbmFsVXJsID0gZnJhbWUuZnJhbWVzX29yaWdpbmFsX3VybDtcbiAgICAgICAgICAgIC8vdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtaGVpZ2h0JywgZnJhbWUuZnJhbWVzX2hlaWdodCk7XG4gICAgICAgICAgICAvL3RoaXMuZnJhbWVIZWlnaHQgPSBmcmFtZS5mcmFtZXNfaGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZnJhbWUpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuZGJveD9cbiAgICAgICAgICAgIGlmKCB0aGlzLnNhbmRib3ggIT09IGZhbHNlICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLWxvYWRlcmZ1bmN0aW9uJywgdGhpcy5zYW5kYm94X2xvYWRlcik7XG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtc2FuZGJveCcsIHRoaXMuc2FuZGJveCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZWNyZWF0ZSB0aGUgc2FuZGJveGVkIGlmcmFtZSBlbHNld2hlcmVcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveGVkRnJhbWUgPSAkKCc8aWZyYW1lIHNyYz1cIicrZnJhbWUuc3JjKydcIiBpZD1cIicrdGhpcy5zYW5kYm94KydcIiBzYW5kYm94PVwiYWxsb3ctc2FtZS1vcmlnaW5cIj48L2lmcmFtZT4nKTtcbiAgICAgICAgICAgICAgICAkKCcjc2FuZGJveGVzJykuYXBwZW5kKCBzYW5kYm94ZWRGcmFtZSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGluc2VydCB0aGUgaWZyYW1lIGludG8gdGhlIERPTSBvbiB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaW5zZXJ0QmxvY2tJbnRvRG9tID0gZnVuY3Rpb24odGhlVUwpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCh0aGlzLmZyYW1lKTtcbiAgICAgICAgICAgIHRoZVVMLmFwcGVuZENoaWxkKCB0aGlzLnBhcmVudExJICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIHRoaXMsIGZhbHNlKTtcblxuICAgICAgICAgICAgYnVpbGRlclVJLmNhbnZhc0xvYWRpbmcoJ29uJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2V0cyB0aGUgZnJhbWUgZG9jdW1lbnQgZm9yIHRoZSBibG9jaydzIGlmcmFtZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNldEZyYW1lRG9jdW1lbnQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zZXQgdGhlIGZyYW1lIGRvY3VtZW50IGFzIHdlbGxcbiAgICAgICAgICAgIGlmKCB0aGlzLmZyYW1lLmNvbnRlbnREb2N1bWVudCApIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQgPSB0aGlzLmZyYW1lLmNvbnRlbnREb2N1bWVudDsgICBcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50ID0gdGhpcy5mcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3RoaXMuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY3JlYXRlcyB0aGUgZnJhbWUgY292ZXIgYW5kIGJsb2NrIGFjdGlvbiBidXR0b25cbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jcmVhdGVGcmFtZUNvdmVyID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnVpbGQgdGhlIGZyYW1lIGNvdmVyIGFuZCBibG9jayBhY3Rpb24gYnV0dG9uc1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuY2xhc3NMaXN0LmFkZCgnZnJhbWVDb3ZlcicpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmNsYXNzTGlzdC5hZGQoJ2ZyZXNoJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGRlbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgZGVsQnV0dG9uLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnYnRuIGJ0bi1pbnZlcnNlIGJ0bi1zbSBkZWxldGVCbG9jaycpO1xuICAgICAgICAgICAgZGVsQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGRlbEJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmdWktdHJhc2hcIj48L2k+IDxzcGFuPnJlbW92ZTwvc3Bhbj4nO1xuICAgICAgICAgICAgZGVsQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciByZXNldEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgcmVzZXRCdXR0b24uc2V0QXR0cmlidXRlKCdjbGFzcycsICdidG4gYnRuLWludmVyc2UgYnRuLXNtIHJlc2V0QmxvY2snKTtcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLXJlZnJlc2hcIj48L2k+IDxzcGFuPnJlc2V0PC9zcGFuPic7XG4gICAgICAgICAgICByZXNldEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgaHRtbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgaHRtbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gaHRtbEJsb2NrJyk7XG4gICAgICAgICAgICBodG1sQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGh0bWxCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtY29kZVwiPjwvaT4gPHNwYW4+c291cmNlPC9zcGFuPic7XG4gICAgICAgICAgICBodG1sQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuXG4gICAgICAgICAgICB2YXIgZHJhZ0J1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgZHJhZ0J1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gZHJhZ0Jsb2NrJyk7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGRyYWdCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtYXJyb3dzXCI+PC9pPiA8c3Bhbj5Nb3ZlPC9zcGFuPic7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuXG4gICAgICAgICAgICB2YXIgZ2xvYmFsTGFiZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMQUJFTCcpO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuY2xhc3NMaXN0LmFkZCgnY2hlY2tib3gnKTtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmNsYXNzTGlzdC5hZGQoJ3ByaW1hcnknKTtcbiAgICAgICAgICAgIHZhciBnbG9iYWxDaGVja2JveCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0lOUFVUJyk7XG4gICAgICAgICAgICBnbG9iYWxDaGVja2JveC50eXBlID0gJ2NoZWNrYm94JztcbiAgICAgICAgICAgIGdsb2JhbENoZWNrYm94LnNldEF0dHJpYnV0ZSgnZGF0YS10b2dnbGUnLCAnY2hlY2tib3gnKTtcbiAgICAgICAgICAgIGdsb2JhbENoZWNrYm94LmNoZWNrZWQgPSB0aGlzLmdsb2JhbDtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmFwcGVuZENoaWxkKGdsb2JhbENoZWNrYm94KTtcbiAgICAgICAgICAgIHZhciBnbG9iYWxUZXh0ID0gZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUoJ0dsb2JhbCcpO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuYXBwZW5kQ2hpbGQoZ2xvYmFsVGV4dCk7XG5cbiAgICAgICAgICAgIHZhciB0cmlnZ2VyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgdHJpZ2dlci5jbGFzc0xpc3QuYWRkKCdmdWktZ2VhcicpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChkZWxCdXR0b24pO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKHJlc2V0QnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChodG1sQnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChkcmFnQnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChnbG9iYWxMYWJlbCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQodHJpZ2dlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmFwcGVuZENoaWxkKHRoaXMuZnJhbWVDb3Zlcik7XG5cbiAgICAgICAgICAgIHZhciB0aGVCbG9jayA9IHRoaXM7XG5cbiAgICAgICAgICAgICQoZ2xvYmFsQ2hlY2tib3gpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICAgICAgdGhlQmxvY2sudG9nZ2xlR2xvYmFsKGUpO1xuXG4gICAgICAgICAgICB9KS5yYWRpb2NoZWNrKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnRvZ2dsZUdsb2JhbCA9IGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgIGlmICggZS5jdXJyZW50VGFyZ2V0LmNoZWNrZWQgKSB0aGlzLmdsb2JhbCA9IHRydWU7XG4gICAgICAgICAgICBlbHNlIHRoaXMuZ2xvYmFsID0gZmFsc2U7XG5cbiAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgY29uc29sZS5sb2codGhpcyk7XG5cbiAgICAgICAgfTtcblxuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBhdXRvbWF0aWNhbGx5IGNvcnJlY3RzIHRoZSBoZWlnaHQgb2YgdGhlIGJsb2NrJ3MgaWZyYW1lIGRlcGVuZGluZyBvbiBpdHMgY29udGVudFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmhlaWdodEFkanVzdG1lbnQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYgKCBPYmplY3Qua2V5cyh0aGlzLmZyYW1lRG9jdW1lbnQpLmxlbmd0aCAhPT0gMCApIHtcblxuICAgICAgICAgICAgICAgIHZhciBoZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgIGJvZHlIZWlnaHQgPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5vZmZzZXRIZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgIHBhZ2VDb250YWluZXJIZWlnaHQgPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5vZmZzZXRIZWlnaHQ7XG5cbiAgICAgICAgICAgICAgICBpZiAoIGJvZHlIZWlnaHQgPiBwYWdlQ29udGFpbmVySGVpZ2h0ICYmICF0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5jbGFzc0xpc3QuY29udGFpbnMoIGJDb25maWcuYm9keVBhZGRpbmdDbGFzcyApICkgaGVpZ2h0ID0gcGFnZUNvbnRhaW5lckhlaWdodDtcbiAgICAgICAgICAgICAgICBlbHNlIGhlaWdodCA9IGJvZHlIZWlnaHQ7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lLnN0eWxlLmhlaWdodCA9IGhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5zdHlsZS5oZWlnaHQgPSBoZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgICAgIC8vdGhpcy5mcmFtZUNvdmVyLnN0eWxlLmhlaWdodCA9IGhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZUhlaWdodCA9IGhlaWdodDtcblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZXMgYSBibG9ja1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmRlbGV0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBmcm9tIERPTS9jYW52YXMgd2l0aCBhIG5pY2UgYW5pbWF0aW9uXG4gICAgICAgICAgICAkKHRoaXMuZnJhbWUucGFyZW50Tm9kZSkuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5pc0VtcHR5KCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgZnJvbSBibG9ja3MgYXJyYXkgaW4gdGhlIGFjdGl2ZSBwYWdlXG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UuZGVsZXRlQmxvY2sodGhpcyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuYm94XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5iZG94ICkge1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCB0aGlzLnNhbmRib3ggKS5yZW1vdmUoKTsgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbGVtZW50IHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVzZXRzIGEgYmxvY2sgdG8gaXQncyBvcmlnbmFsIHN0YXRlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMucmVzZXQgPSBmdW5jdGlvbiAoZmlyZUV2ZW50KSB7XG5cbiAgICAgICAgICAgIGlmICggdHlwZW9mIGZpcmVFdmVudCA9PT0gJ3VuZGVmaW5lZCcpIGZpcmVFdmVudCA9IHRydWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVzZXQgZnJhbWUgYnkgcmVsb2FkaW5nIGl0XG4gICAgICAgICAgICB0aGlzLmZyYW1lLmNvbnRlbnRXaW5kb3cubG9jYXRpb24gPSB0aGlzLmZyYW1lLmdldEF0dHJpYnV0ZSgnZGF0YS1vcmlnaW5hbHVybCcpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NhbmRib3g/XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94RnJhbWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLnNhbmRib3gpLmNvbnRlbnRXaW5kb3cubG9jYXRpb24ucmVsb2FkKCk7ICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbGVtZW50IHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb24nKTtcblxuICAgICAgICAgICAgaWYgKCBmaXJlRXZlbnQgKSBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHRoaXMsICdyZWxvYWQnKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBsYXVuY2hlcyB0aGUgc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc291cmNlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vaGlkZSB0aGUgaWZyYW1lXG4gICAgICAgICAgICB0aGlzLmZyYW1lLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGlzYWJsZSBzb3J0YWJsZSBvbiB0aGUgcGFyZW50TElcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlKS5zb3J0YWJsZSgnZGlzYWJsZScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1aWx0IGVkaXRvciBlbGVtZW50XG4gICAgICAgICAgICB2YXIgdGhlRWRpdG9yID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICB0aGVFZGl0b3IuY2xhc3NMaXN0LmFkZCgnYWNlRWRpdG9yJyk7XG4gICAgICAgICAgICAkKHRoZUVkaXRvcikudW5pcXVlSWQoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCh0aGVFZGl0b3IpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1aWxkIGFuZCBhcHBlbmQgZXJyb3IgZHJhd2VyXG4gICAgICAgICAgICB2YXIgbmV3TEkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMSScpO1xuICAgICAgICAgICAgdmFyIGVycm9yRHJhd2VyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICBlcnJvckRyYXdlci5jbGFzc0xpc3QuYWRkKCdlcnJvckRyYXdlcicpO1xuICAgICAgICAgICAgZXJyb3JEcmF3ZXIuc2V0QXR0cmlidXRlKCdpZCcsICdkaXZfZXJyb3JEcmF3ZXInKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLmlubmVySFRNTCA9ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4teHMgYnRuLWVtYm9zc2VkIGJ0bi1kZWZhdWx0IGJ1dHRvbl9jbGVhckVycm9yRHJhd2VyXCIgaWQ9XCJidXR0b25fY2xlYXJFcnJvckRyYXdlclwiPkNMRUFSPC9idXR0b24+JztcbiAgICAgICAgICAgIG5ld0xJLmFwcGVuZENoaWxkKGVycm9yRHJhd2VyKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvbicpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlLmluc2VydEJlZm9yZShuZXdMSSwgdGhpcy5wYXJlbnRMSS5uZXh0U2libGluZyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGFjZS5jb25maWcuc2V0KFwiYmFzZVBhdGhcIiwgXCIvanMvdmVuZG9yL2FjZVwiKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHRoZUlkID0gdGhlRWRpdG9yLmdldEF0dHJpYnV0ZSgnaWQnKTtcbiAgICAgICAgICAgIHZhciBlZGl0b3IgPSBhY2UuZWRpdCggdGhlSWQgKTtcblxuICAgICAgICAgICAgLy9lZGl0b3IuZ2V0U2Vzc2lvbigpLnNldFVzZVdyYXBNb2RlKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZUNvbnRhaW5lciA9IHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKTtcbiAgICAgICAgICAgIHZhciB0aGVIVE1MID0gcGFnZUNvbnRhaW5lci5pbm5lckhUTUw7XG4gICAgICAgICAgICBcblxuICAgICAgICAgICAgZWRpdG9yLnNldFZhbHVlKCB0aGVIVE1MICk7XG4gICAgICAgICAgICBlZGl0b3Iuc2V0VGhlbWUoXCJhY2UvdGhlbWUvdHdpbGlnaHRcIik7XG4gICAgICAgICAgICBlZGl0b3IuZ2V0U2Vzc2lvbigpLnNldE1vZGUoXCJhY2UvbW9kZS9odG1sXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYmxvY2sgPSB0aGlzO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGVkaXRvci5nZXRTZXNzaW9uKCkub24oXCJjaGFuZ2VBbm5vdGF0aW9uXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suYW5ub3QgPSBlZGl0b3IuZ2V0U2Vzc2lvbigpLmdldEFubm90YXRpb25zKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KGJsb2NrLmFubm90VGltZW91dCk7XG5cbiAgICAgICAgICAgICAgICB2YXIgdGltZW91dENvdW50O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCAkKCcjZGl2X2Vycm9yRHJhd2VyIHAnKS5zaXplKCkgPT09IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgIHRpbWVvdXRDb3VudCA9IGJDb25maWcuc291cmNlQ29kZUVkaXRTeW50YXhEZWxheTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aW1lb3V0Q291bnQgPSAxMDA7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmFubm90VGltZW91dCA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBmb3IgKHZhciBrZXkgaW4gYmxvY2suYW5ub3Qpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChibG9jay5hbm5vdC5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiggYmxvY2suYW5ub3Rba2V5XS50ZXh0ICE9PSBcIlN0YXJ0IHRhZyBzZWVuIHdpdGhvdXQgc2VlaW5nIGEgZG9jdHlwZSBmaXJzdC4gRXhwZWN0ZWQgZS5nLiA8IURPQ1RZUEUgaHRtbD4uXCIgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdMaW5lID0gJCgnPHA+PC9wPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3S2V5ID0gJCgnPGI+JytibG9jay5hbm5vdFtrZXldLnR5cGUrJzogPC9iPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3SW5mbyA9ICQoJzxzcGFuPiAnK2Jsb2NrLmFubm90W2tleV0udGV4dCArIFwib24gbGluZSBcIiArIFwiIDxiPlwiICsgYmxvY2suYW5ub3Rba2V5XS5yb3crJzwvYj48L3NwYW4+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0xpbmUuYXBwZW5kKCBuZXdLZXkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3TGluZS5hcHBlbmQoIG5ld0luZm8gKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5hcHBlbmQoIG5ld0xpbmUgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5jc3MoJ2Rpc3BsYXknKSA9PT0gJ25vbmUnICYmICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5maW5kKCdwJykuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5zbGlkZURvd24oKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSwgdGltZW91dENvdW50KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnV0dG9uc1xuICAgICAgICAgICAgdmFyIGNhbmNlbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4tZGFuZ2VyJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uY2xhc3NMaXN0LmFkZCgnZWRpdENhbmNlbEJ1dHRvbicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1zbScpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZ1aS1jcm9zc1wiPjwvaT4gPHNwYW4+Q2FuY2VsPC9zcGFuPic7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBzYXZlQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1wcmltYXJ5Jyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2VkaXRTYXZlQnV0dG9uJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1zbScpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmdWktY2hlY2tcIj48L2k+IDxzcGFuPlNhdmU8L3NwYW4+JztcbiAgICAgICAgICAgIHNhdmVCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBidXR0b25XcmFwcGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICBidXR0b25XcmFwcGVyLmNsYXNzTGlzdC5hZGQoJ2VkaXRvckJ1dHRvbnMnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgYnV0dG9uV3JhcHBlci5hcHBlbmRDaGlsZCggY2FuY2VsQnV0dG9uICk7XG4gICAgICAgICAgICBidXR0b25XcmFwcGVyLmFwcGVuZENoaWxkKCBzYXZlQnV0dG9uICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuYXBwZW5kQ2hpbGQoIGJ1dHRvbldyYXBwZXIgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgYnVpbGRlclVJLmFjZUVkaXRvcnNbIHRoZUlkIF0gPSBlZGl0b3I7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2FuY2VscyB0aGUgYmxvY2sgc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY2FuY2VsU291cmNlQmxvY2sgPSBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgLy9lbmFibGUgZHJhZ2dhYmxlIG9uIHRoZSBMSVxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnBhcmVudE5vZGUpLnNvcnRhYmxlKCdlbmFibGUnKTtcblx0XHRcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlcnJvckRyYXdlclxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLm5leHRTaWJsaW5nKS5yZW1vdmUoKTtcbiAgICAgICAgXG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgZWRpdG9yXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5hY2VFZGl0b3InKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICQodGhpcy5mcmFtZSkuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZWRpdG9yQnV0dG9ucycpKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlcyB0aGUgYmxvY2tzIHNvdXJjZSBjb2RlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2F2ZVNvdXJjZUJsb2NrID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZW5hYmxlIGRyYWdnYWJsZSBvbiB0aGUgTElcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlKS5zb3J0YWJsZSgnZW5hYmxlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciB0aGVJZCA9IHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmFjZUVkaXRvcicpLmdldEF0dHJpYnV0ZSgnaWQnKTtcbiAgICAgICAgICAgIHZhciB0aGVDb250ZW50ID0gYnVpbGRlclVJLmFjZUVkaXRvcnNbdGhlSWRdLmdldFZhbHVlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlcnJvckRyYXdlclxuICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2Rpdl9lcnJvckRyYXdlcicpLnBhcmVudE5vZGUucmVtb3ZlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlZGl0b3JcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmFjZUVkaXRvcicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3VwZGF0ZSB0aGUgZnJhbWUncyBjb250ZW50XG4gICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvciggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuaW5uZXJIVE1MID0gdGhlQ29udGVudDtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuZGJveGVkP1xuICAgICAgICAgICAgaWYoIHRoaXMuc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveEZyYW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIHRoaXMuc2FuZGJveCApO1xuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94RnJhbWVEb2N1bWVudCA9IHNhbmRib3hGcmFtZS5jb250ZW50RG9jdW1lbnQgfHwgc2FuZGJveEZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYnVpbGRlclVJLnRlbXBGcmFtZSA9IHNhbmRib3hGcmFtZTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzYW5kYm94RnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5pbm5lckhUTUwgPSB0aGVDb250ZW50O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2RvIHdlIG5lZWQgdG8gZXhlY3V0ZSBhIGxvYWRlciBmdW5jdGlvbj9cbiAgICAgICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94X2xvYWRlciAhPT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvKlxuICAgICAgICAgICAgICAgICAgICB2YXIgY29kZVRvRXhlY3V0ZSA9IFwic2FuZGJveEZyYW1lLmNvbnRlbnRXaW5kb3cuXCIrdGhpcy5zYW5kYm94X2xvYWRlcitcIigpXCI7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0bXBGdW5jID0gbmV3IEZ1bmN0aW9uKGNvZGVUb0V4ZWN1dGUpO1xuICAgICAgICAgICAgICAgICAgICB0bXBGdW5jKCk7XG4gICAgICAgICAgICAgICAgICAgICovXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5lZGl0b3JCdXR0b25zJykpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hZGp1c3QgaGVpZ2h0IG9mIHRoZSBmcmFtZVxuICAgICAgICAgICAgdGhpcy5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbmV3IHBhZ2UgYWRkZWQsIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYmxvY2sgaGFzIGNoYW5nZWRcbiAgICAgICAgICAgIHRoaXMuc3RhdHVzID0gJ2NoYW5nZWQnO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHRoaXMsICdjaGFuZ2UnKTtcbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrTG9hZGVkJywgdGhpcyk7XG5cbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2xlYXJzIG91dCB0aGUgZXJyb3IgZHJhd2VyXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY2xlYXJFcnJvckRyYXdlciA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcHMgPSB0aGlzLnBhcmVudExJLm5leHRTaWJsaW5nLnF1ZXJ5U2VsZWN0b3JBbGwoJ3AnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSA9IDA7IGkgPCBwcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICBwc1tpXS5yZW1vdmUoKTsgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHRvZ2dsZXMgdGhlIHZpc2liaWxpdHkgb2YgdGhpcyBibG9jaydzIGZyYW1lQ292ZXJcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy50b2dnbGVDb3ZlciA9IGZ1bmN0aW9uKG9uT3JPZmYpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIG9uT3JPZmYgPT09ICdPbicgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZnJhbWVDb3ZlcicpLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIGlmKCBvbk9yT2ZmID09PSAnT2ZmJyApIHtcbiAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5mcmFtZUNvdmVyJykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXR1cm5zIHRoZSBmdWxsIHNvdXJjZSBjb2RlIG9mIHRoZSBibG9jaydzIGZyYW1lXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZ2V0U291cmNlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBzb3VyY2UgPSBcIjxodG1sPlwiO1xuICAgICAgICAgICAgc291cmNlICs9IHRoaXMuZnJhbWVEb2N1bWVudC5oZWFkLm91dGVySFRNTDtcbiAgICAgICAgICAgIHNvdXJjZSArPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5vdXRlckhUTUw7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBzb3VyY2U7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcGxhY2VzIGEgZHJhZ2dlZC9kcm9wcGVkIGJsb2NrIGZyb20gdGhlIGxlZnQgc2lkZWJhciBvbnRvIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5wbGFjZU9uQ2FudmFzID0gZnVuY3Rpb24odWkpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9mcmFtZSBkYXRhLCB3ZSdsbCBuZWVkIHRoaXMgYmVmb3JlIG1lc3Npbmcgd2l0aCB0aGUgaXRlbSdzIGNvbnRlbnQgSFRNTFxuICAgICAgICAgICAgdmFyIGZyYW1lRGF0YSA9IHt9LCBhdHRyO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnaWZyYW1lJykuc2l6ZSgpID4gMCApIHsvL2lmcmFtZSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLnNyYyA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19vcmlnaW5hbF91cmwgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ3NyYycpO1xuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfaGVpZ2h0ID0gdWkuaXRlbS5oZWlnaHQoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zYW5kYm94ZWQgYmxvY2s/XG4gICAgICAgICAgICAgICAgYXR0ciA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc2FuZGJveCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGF0dHIgIT09IHR5cGVvZiB1bmRlZmluZWQgJiYgYXR0ciAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94ID0gc2l0ZUJ1aWxkZXJVdGlscy5nZXRSYW5kb21BcmJpdHJhcnkoMTAwMDAsIDEwMDAwMDAwMDApO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNhbmRib3hfbG9hZGVyID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdkYXRhLWxvYWRlcmZ1bmN0aW9uJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHsvL2ltYWdlIHRodW1ibmFpbFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuc3JjID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLXNyY2MnKTtcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX29yaWdpbmFsX3VybCA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1zcmNjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19oZWlnaHQgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtaGVpZ2h0Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3NhbmRib3hlZCBibG9jaz9cbiAgICAgICAgICAgICAgICBhdHRyID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLXNhbmRib3gnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2FuZGJveCA9IHNpdGVCdWlsZGVyVXRpbHMuZ2V0UmFuZG9tQXJiaXRyYXJ5KDEwMDAwLCAxMDAwMDAwMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94X2xvYWRlciA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1sb2FkZXJmdW5jdGlvbicpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9ICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY3JlYXRlIHRoZSBuZXcgYmxvY2sgb2JqZWN0XG4gICAgICAgICAgICB0aGlzLmZyYW1lSUQgPSAwO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSSA9IHVpLml0ZW0uZ2V0KDApO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5pbm5lckhUTUwgPSAnJztcbiAgICAgICAgICAgIHRoaXMuc3RhdHVzID0gJ25ldyc7XG4gICAgICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lKGZyYW1lRGF0YSk7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnN0eWxlLmhlaWdodCA9IHRoaXMuZnJhbWVIZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgdGhpcy5jcmVhdGVGcmFtZUNvdmVyKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmZyYW1lLmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vaW5zZXJ0IHRoZSBjcmVhdGVkIGlmcmFtZVxuICAgICAgICAgICAgdWkuaXRlbS5hcHBlbmQoJCh0aGlzLmZyYW1lKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2FkZCB0aGUgYmxvY2sgdG8gdGhlIGN1cnJlbnQgcGFnZVxuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLmJsb2Nrcy5zcGxpY2UodWkuaXRlbS5pbmRleCgpLCAwLCB0aGlzKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLnRyaWdnZXIoJ2NhbnZhc3VwZGF0ZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Ryb3BwZWQgZWxlbWVudCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpbmplY3RzIGV4dGVybmFsIEpTIChkZWZpbmVkIGluIGNvbmZpZy5qcykgaW50byB0aGUgYmxvY2tcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5sb2FkSmF2YXNjcmlwdCA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgdmFyIGksXG4gICAgICAgICAgICAgICAgb2xkLFxuICAgICAgICAgICAgICAgIG5ld1NjcmlwdDtcblxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIG9uZXNcbiAgICAgICAgICAgIG9sZCA9IHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdzY3JpcHQuYnVpbGRlcicpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IG9sZC5sZW5ndGg7IGkrKyApIG9sZFtpXS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgLy9pbmplY3RcbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgYkNvbmZpZy5leHRlcm5hbEpTLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIG5ld1NjcmlwdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ1NDUklQVCcpO1xuICAgICAgICAgICAgICAgIG5ld1NjcmlwdC5jbGFzc0xpc3QuYWRkKCdidWlsZGVyJyk7XG4gICAgICAgICAgICAgICAgbmV3U2NyaXB0LnNyYyA9IGJDb25maWcuZXh0ZXJuYWxKU1tpXTtcblxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdib2R5JykuYXBwZW5kQ2hpbGQobmV3U2NyaXB0KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2hlY2tzIGlmIHRoaXMgYmxvY2sgaGFzIGV4dGVybmFsIHN0eWxlc2hlZXRcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5oYXNFeHRlcm5hbENTUyA9IGZ1bmN0aW9uIChzcmMpIHtcblxuICAgICAgICAgICAgdmFyIGV4dGVybmFsQ3NzLFxuICAgICAgICAgICAgICAgIHg7XG5cbiAgICAgICAgICAgIGV4dGVybmFsQ3NzID0gdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpbmtbaHJlZio9XCInICsgc3JjICsgJ1wiXScpO1xuXG4gICAgICAgICAgICByZXR1cm4gZXh0ZXJuYWxDc3MubGVuZ3RoICE9PSAwO1xuXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgIH1cbiAgICBcbiAgICBCbG9jay5wcm90b3R5cGUuaGFuZGxlRXZlbnQgPSBmdW5jdGlvbihldmVudCkge1xuICAgICAgICBzd2l0Y2ggKGV2ZW50LnR5cGUpIHtcbiAgICAgICAgICAgIGNhc2UgXCJsb2FkXCI6IFxuICAgICAgICAgICAgICAgIHRoaXMuc2V0RnJhbWVEb2N1bWVudCgpO1xuICAgICAgICAgICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgIHRoaXMubG9hZEphdmFzY3JpcHQoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMuZnJhbWVDb3ZlcikucmVtb3ZlQ2xhc3MoJ2ZyZXNoJywgNTAwKTtcblxuICAgICAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrTG9hZGVkJywgdGhpcyk7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmxvYWRlZCA9IHRydWU7XG5cbiAgICAgICAgICAgICAgICBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb2ZmJyk7XG5cbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGNhc2UgXCJjbGlja1wiOlxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciB0aGVCbG9jayA9IHRoaXM7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9maWd1cmUgb3V0IHdoYXQgdG8gZG8gbmV4dFxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdkZWxldGVCbG9jaycpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZGVsZXRlQmxvY2snKSApIHsvL2RlbGV0ZSB0aGlzIGJsb2NrXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZUJsb2NrKS5tb2RhbCgnc2hvdycpOyAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZUJsb2NrKS5vZmYoJ2NsaWNrJywgJyNkZWxldGVCbG9ja0NvbmZpcm0nKS5vbignY2xpY2snLCAnI2RlbGV0ZUJsb2NrQ29uZmlybScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5kZWxldGUoZXZlbnQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVCbG9jaykubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygncmVzZXRCbG9jaycpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygncmVzZXRCbG9jaycpICkgey8vcmVzZXQgdGhlIGJsb2NrXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbFJlc2V0QmxvY2spLm1vZGFsKCdzaG93Jyk7IFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxSZXNldEJsb2NrKS5vZmYoJ2NsaWNrJywgJyNyZXNldEJsb2NrQ29uZmlybScpLm9uKCdjbGljaycsICcjcmVzZXRCbG9ja0NvbmZpcm0nLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2sucmVzZXQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsUmVzZXRCbG9jaykubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnaHRtbEJsb2NrJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdodG1sQmxvY2snKSApIHsvL3NvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suc291cmNlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZWRpdENhbmNlbEJ1dHRvbicpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZWRpdENhbmNlbEJ1dHRvbicpICkgey8vY2FuY2VsIHNvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suY2FuY2VsU291cmNlQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0U2F2ZUJ1dHRvbicpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZWRpdFNhdmVCdXR0b24nKSApIHsvL3NhdmUgc291cmNlIGNvZGVcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLnNhdmVTb3VyY2VCbG9jaygpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2J1dHRvbl9jbGVhckVycm9yRHJhd2VyJykgKSB7Ly9jbGVhciBlcnJvciBkcmF3ZXJcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLmNsZWFyRXJyb3JEcmF3ZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgfTtcblxuXG4gICAgLypcbiAgICAgICAgU2l0ZSBvYmplY3QgbGl0ZXJhbFxuICAgICovXG4gICAgLypqc2hpbnQgLVcwMDMgKi9cbiAgICB2YXIgc2l0ZSA9IHtcbiAgICAgICAgXG4gICAgICAgIHBlbmRpbmdDaGFuZ2VzOiBmYWxzZSwgICAgICAvL3BlbmRpbmcgY2hhbmdlcyBvciBubz9cbiAgICAgICAgcGFnZXM6IHt9LCAgICAgICAgICAgICAgICAgIC8vYXJyYXkgY29udGFpbmluZyBhbGwgcGFnZXMsIGluY2x1ZGluZyB0aGUgY2hpbGQgZnJhbWVzLCBsb2FkZWQgZnJvbSB0aGUgc2VydmVyIG9uIHBhZ2UgbG9hZFxuICAgICAgICBpc19hZG1pbjogMCwgICAgICAgICAgICAgICAgLy8wIGZvciBub24tYWRtaW4sIDEgZm9yIGFkbWluXG4gICAgICAgIGRhdGE6IHt9LCAgICAgICAgICAgICAgICAgICAvL2NvbnRhaW5lciBmb3IgYWpheCBsb2FkZWQgc2l0ZSBkYXRhXG4gICAgICAgIHBhZ2VzVG9EZWxldGU6IFtdLCAgICAgICAgICAvL2NvbnRhaW5zIHBhZ2VzIHRvIGJlIGRlbGV0ZWRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgc2l0ZVBhZ2VzOiBbXSwgICAgICAgICAgICAgIC8vdGhpcyBpcyB0aGUgb25seSB2YXIgY29udGFpbmluZyB0aGUgcmVjZW50IGNhbnZhcyBjb250ZW50c1xuICAgICAgICBcbiAgICAgICAgc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXI6IHt9LCAgICAgLy9jb250YWlucyB0aGUgc2l0ZSBkYXRhIHJlYWR5IHRvIGJlIHNlbnQgdG8gdGhlIHNlcnZlclxuICAgICAgICBcbiAgICAgICAgYWN0aXZlUGFnZToge30sICAgICAgICAgICAgIC8vaG9sZHMgYSByZWZlcmVuY2UgdG8gdGhlIHBhZ2UgY3VycmVudGx5IG9wZW4gb24gdGhlIGNhbnZhc1xuICAgICAgICBcbiAgICAgICAgcGFnZVRpdGxlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZVRpdGxlJyksLy9ob2xkcyB0aGUgcGFnZSB0aXRsZSBvZiB0aGUgY3VycmVudCBwYWdlIG9uIHRoZSBjYW52YXNcbiAgICAgICAgXG4gICAgICAgIGRpdkNhbnZhczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VMaXN0JyksLy9ESVYgY29udGFpbmluZyBhbGwgcGFnZXMgb24gdGhlIGNhbnZhc1xuICAgICAgICBcbiAgICAgICAgcGFnZXNNZW51OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZXMnKSwgLy9VTCBjb250YWluaW5nIHRoZSBwYWdlcyBtZW51IGluIHRoZSBzaWRlYmFyXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIGJ1dHRvbk5ld1BhZ2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhZGRQYWdlJyksXG4gICAgICAgIGxpTmV3UGFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ25ld1BhZ2VMSScpLFxuICAgICAgICBcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NUaXRsZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX3RpdGxlJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzTWV0YURlc2NyaXB0aW9uOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfbWV0YURlc2NyaXB0aW9uJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzTWV0YUtleXdvcmRzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfbWV0YUtleXdvcmRzJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzSW5jbHVkZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9oZWFkZXJJbmNsdWRlcycpLFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc1BhZ2VDc3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9oZWFkZXJDc3MnKSxcbiAgICAgICAgXG4gICAgICAgIGJ1dHRvblN1Ym1pdFBhZ2VTZXR0aW5nczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VTZXR0aW5nc1N1Ym1pdHRCdXR0b24nKSxcbiAgICAgICAgXG4gICAgICAgIG1vZGFsUGFnZVNldHRpbmdzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZVNldHRpbmdzTW9kYWwnKSxcbiAgICAgICAgXG4gICAgICAgIGJ1dHRvblNhdmU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzYXZlUGFnZScpLFxuICAgICAgICBcbiAgICAgICAgbWVzc2FnZVN0YXJ0OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc3RhcnQnKSxcbiAgICAgICAgZGl2RnJhbWVXcmFwcGVyOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJyksXG4gICAgICAgIFxuICAgICAgICBza2VsZXRvbjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NrZWxldG9uJyksXG5cdFx0XG5cdFx0YXV0b1NhdmVUaW1lcjoge30sXG4gICAgICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJC5nZXRKU09OKGFwcFVJLnNpdGVVcmwrXCJzaXRlcy9zaXRlRGF0YVwiLCBmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggZGF0YS5zaXRlICE9PSB1bmRlZmluZWQgKSB7XG4gICAgICAgICAgICAgICAgICAgIHNpdGUuZGF0YSA9IGRhdGEuc2l0ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYoIGRhdGEucGFnZXMgIT09IHVuZGVmaW5lZCApIHtcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5wYWdlcyA9IGRhdGEucGFnZXM7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUuaXNfYWRtaW4gPSBkYXRhLmlzX2FkbWluO1xuICAgICAgICAgICAgICAgIFxuXHRcdFx0XHRpZiggJCgnI3BhZ2VMaXN0Jykuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICBcdGJ1aWxkZXJVSS5wb3B1bGF0ZUNhbnZhcygpO1xuXHRcdFx0XHR9XG5cbiAgICAgICAgICAgICAgICBpZiggZGF0YS5zaXRlLnZpZXdtb2RlICkge1xuICAgICAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25TZXRNb2RlJywgZGF0YS5zaXRlLnZpZXdtb2RlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9maXJlIGN1c3RvbSBldmVudFxuICAgICAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdzaXRlRGF0YUxvYWRlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5idXR0b25OZXdQYWdlKS5vbignY2xpY2snLCBzaXRlLm5ld1BhZ2UpO1xuICAgICAgICAgICAgJCh0aGlzLm1vZGFsUGFnZVNldHRpbmdzKS5vbignc2hvdy5icy5tb2RhbCcsIHNpdGUubG9hZFBhZ2VTZXR0aW5ncyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU3VibWl0UGFnZVNldHRpbmdzKS5vbignY2xpY2snLCBzaXRlLnVwZGF0ZVBhZ2VTZXR0aW5ncyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU2F2ZSkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXtzaXRlLnNhdmUodHJ1ZSk7fSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYXV0byBzYXZlIHRpbWUgXG4gICAgICAgICAgICB0aGlzLmF1dG9TYXZlVGltZXIgPSBzZXRUaW1lb3V0KHNpdGUuYXV0b1NhdmUsIGJDb25maWcuYXV0b1NhdmVUaW1lb3V0KTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnN1YnNjcmliZSgnb25CbG9ja0NoYW5nZScsIGZ1bmN0aW9uIChibG9jaywgdHlwZSkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCBibG9jay5nbG9iYWwgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZm9yICggdmFyIGkgPSAwOyBpIDwgc2l0ZS5zaXRlUGFnZXMubGVuZ3RoOyBpKysgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGZvciAoIHZhciB5ID0gMDsgeSA8IHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrcy5sZW5ndGg7IHkgKysgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XSAhPT0gYmxvY2sgJiYgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldLm9yaWdpbmFsVXJsID09PSBibG9jay5vcmlnaW5hbFVybCAmJiBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0uZ2xvYmFsICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICggdHlwZSA9PT0gJ2NoYW5nZScgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5mcmFtZURvY3VtZW50LmJvZHkgPSBibG9jay5mcmFtZURvY3VtZW50LmJvZHkuY2xvbmVOb2RlKHRydWUpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0xvYWRlZCcsIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmICggdHlwZSA9PT0gJ3JlbG9hZCcgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5yZXNldChmYWxzZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIGF1dG9TYXZlOiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBpZihzaXRlLnBlbmRpbmdDaGFuZ2VzKSB7XG4gICAgICAgICAgICAgICAgc2l0ZS5zYXZlKGZhbHNlKTtcbiAgICAgICAgICAgIH1cblx0XHRcdFxuXHRcdFx0d2luZG93LmNsZWFySW50ZXJ2YWwodGhpcy5hdXRvU2F2ZVRpbWVyKTtcbiAgICAgICAgICAgIHRoaXMuYXV0b1NhdmVUaW1lciA9IHNldFRpbWVvdXQoc2l0ZS5hdXRvU2F2ZSwgYkNvbmZpZy5hdXRvU2F2ZVRpbWVvdXQpO1xuICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgc2V0UGVuZGluZ0NoYW5nZXM6IGZ1bmN0aW9uKHZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGVuZGluZ0NoYW5nZXMgPSB2YWx1ZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHZhbHVlID09PSB0cnVlICkge1xuXHRcdFx0XHRcblx0XHRcdFx0Ly9yZXNldCB0aW1lclxuXHRcdFx0XHR3aW5kb3cuY2xlYXJJbnRlcnZhbCh0aGlzLmF1dG9TYXZlVGltZXIpO1xuICAgICAgICAgICAgXHR0aGlzLmF1dG9TYXZlVGltZXIgPSBzZXRUaW1lb3V0KHNpdGUuYXV0b1NhdmUsIGJDb25maWcuYXV0b1NhdmVUaW1lb3V0KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjc2F2ZVBhZ2UgLmJMYWJlbCcpLnRleHQoXCJTYXZlIG5vdyAoISlcIik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHNpdGUuYWN0aXZlUGFnZS5zdGF0dXMgIT09ICduZXcnICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2Uuc3RhdHVzID0gJ2NoYW5nZWQnO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG5cdFx0XHRcbiAgICAgICAgICAgIH0gZWxzZSB7XG5cdFxuICAgICAgICAgICAgICAgICQoJyNzYXZlUGFnZSAuYkxhYmVsJykudGV4dChcIk5vdGhpbmcgdG8gc2F2ZVwiKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgc2l0ZS51cGRhdGVQYWdlU3RhdHVzKCcnKTtcblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgXG4gICAgICAgIHNhdmU6IGZ1bmN0aW9uKHNob3dDb25maXJtTW9kYWwpIHtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmVmb3JlU2F2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2ZpcmUgY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignYmVmb3JlU2F2ZScpO1xuXG4gICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAkKFwiYSNzYXZlUGFnZVwiKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XG4gICAgICAgICAgICAvL3JlbW92ZSBvbGQgYWxlcnRzXG4gICAgICAgICAgICAkKCcjZXJyb3JNb2RhbCAubW9kYWwtYm9keSA+ICosICNzdWNjZXNzTW9kYWwgLm1vZGFsLWJvZHkgPiAqJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcblx0XG4gICAgICAgICAgICBzaXRlLnByZXBGb3JTYXZlKGZhbHNlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHNlcnZlckRhdGEgPSB7fTtcbiAgICAgICAgICAgIHNlcnZlckRhdGEucGFnZXMgPSB0aGlzLnNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyO1xuICAgICAgICAgICAgaWYoIHRoaXMucGFnZXNUb0RlbGV0ZS5sZW5ndGggPiAwICkge1xuICAgICAgICAgICAgICAgIHNlcnZlckRhdGEudG9EZWxldGUgPSB0aGlzLnBhZ2VzVG9EZWxldGU7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHNlcnZlckRhdGEuc2l0ZURhdGEgPSB0aGlzLmRhdGE7XG5cbiAgICAgICAgICAgIC8vc3RvcmUgY3VycmVudCByZXNwb25zaXZlIG1vZGUgYXMgd2VsbFxuICAgICAgICAgICAgc2VydmVyRGF0YS5zaXRlRGF0YS5yZXNwb25zaXZlTW9kZSA9IGJ1aWxkZXJVSS5jdXJyZW50UmVzcG9uc2l2ZU1vZGU7XG5cbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvc2F2ZVwiLFxuICAgICAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcbiAgICAgICAgICAgICAgICBkYXRhOiBzZXJ2ZXJEYXRhLFxuICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXMpe1xuXHRcbiAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKFwiYSNzYXZlUGFnZVwiKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XG4gICAgICAgICAgICAgICAgaWYoIHJlcy5yZXNwb25zZUNvZGUgPT09IDAgKSB7XG5cdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgaWYoIHNob3dDb25maXJtTW9kYWwgKSB7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2Vycm9yTW9kYWwgLm1vZGFsLWJvZHknKS5hcHBlbmQoICQocmVzLnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNlcnJvck1vZGFsJykubW9kYWwoJ3Nob3cnKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIH1cblx0XHRcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIHJlcy5yZXNwb25zZUNvZGUgPT09IDEgKSB7XG5cdFx0XG4gICAgICAgICAgICAgICAgICAgIGlmKCBzaG93Q29uZmlybU1vZGFsICkge1xuXHRcdFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3N1Y2Nlc3NNb2RhbCAubW9kYWwtYm9keScpLmFwcGVuZCggJChyZXMucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3N1Y2Nlc3NNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICB9XG5cdFx0XHRcblx0XHRcdFxuICAgICAgICAgICAgICAgICAgICAvL25vIG1vcmUgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXMoZmFsc2UpO1xuXHRcdFx0XG5cbiAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgcmV2aXNpb25zP1xuICAgICAgICAgICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignY2hhbmdlUGFnZScpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHByZXBzIHRoZSBzaXRlIGRhdGEgYmVmb3JlIHNlbmRpbmcgaXQgdG8gdGhlIHNlcnZlclxuICAgICAgICAqL1xuICAgICAgICBwcmVwRm9yU2F2ZTogZnVuY3Rpb24odGVtcGxhdGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlciA9IHt9O1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGVtcGxhdGUgKSB7Ly9zYXZpbmcgdGVtcGxhdGUsIG9ubHkgdGhlIGFjdGl2ZVBhZ2UgaXMgbmVlZGVkXG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlclt0aGlzLmFjdGl2ZVBhZ2UubmFtZV0gPSB0aGlzLmFjdGl2ZVBhZ2UucHJlcEZvclNhdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UuZnVsbFBhZ2UoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7Ly9yZWd1bGFyIHNhdmVcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZmluZCB0aGUgcGFnZXMgd2hpY2ggbmVlZCB0byBiZSBzZW5kIHRvIHRoZSBzZXJ2ZXJcbiAgICAgICAgICAgICAgICBmb3IoIHZhciBpID0gMDsgaSA8IHRoaXMuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuc2l0ZVBhZ2VzW2ldLnN0YXR1cyAhPT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXJbdGhpcy5zaXRlUGFnZXNbaV0ubmFtZV0gPSB0aGlzLnNpdGVQYWdlc1tpXS5wcmVwRm9yU2F2ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzZXRzIGEgcGFnZSBhcyB0aGUgYWN0aXZlIG9uZVxuICAgICAgICAqL1xuICAgICAgICBzZXRBY3RpdmU6IGZ1bmN0aW9uKHBhZ2UpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZWZlcmVuY2UgdG8gdGhlIGFjdGl2ZSBwYWdlXG4gICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UgPSBwYWdlO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2hpZGUgb3RoZXIgcGFnZXNcbiAgICAgICAgICAgIGZvcih2YXIgaSBpbiB0aGlzLnNpdGVQYWdlcykge1xuICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzW2ldLnBhcmVudFVMLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7ICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGlzcGxheSBhY3RpdmUgb25lXG4gICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UucGFyZW50VUwuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGUtYWN0aXZlIGFsbCBwYWdlIG1lbnUgaXRlbXNcbiAgICAgICAgKi9cbiAgICAgICAgZGVBY3RpdmF0ZUFsbDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlcyA9IHRoaXMucGFnZXNNZW51LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgPSAwOyBpIDwgcGFnZXMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgcGFnZXNbaV0uY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYWRkcyBhIG5ldyBwYWdlIHRvIHRoZSBzaXRlXG4gICAgICAgICovXG4gICAgICAgIG5ld1BhZ2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLmRlQWN0aXZhdGVBbGwoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9jcmVhdGUgdGhlIG5ldyBwYWdlIGluc3RhbmNlXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlRGF0YSA9IFtdO1xuICAgICAgICAgICAgdmFyIHRlbXAgPSB7XG4gICAgICAgICAgICAgICAgcGFnZXNfaWQ6IDBcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBwYWdlRGF0YVswXSA9IHRlbXA7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBuZXdQYWdlTmFtZSA9ICdwYWdlJysoc2l0ZS5zaXRlUGFnZXMubGVuZ3RoKzEpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgbmV3UGFnZSA9IG5ldyBQYWdlKG5ld1BhZ2VOYW1lLCBwYWdlRGF0YSwgc2l0ZS5zaXRlUGFnZXMubGVuZ3RoKzEpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBuZXdQYWdlLnN0YXR1cyA9ICduZXcnO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBuZXdQYWdlLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgIG5ld1BhZ2UuZWRpdFBhZ2VOYW1lKCk7XG4gICAgICAgIFxuICAgICAgICAgICAgbmV3UGFnZS5pc0VtcHR5KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2hlY2tzIGlmIHRoZSBuYW1lIG9mIGEgcGFnZSBpcyBhbGxvd2VkXG4gICAgICAgICovXG4gICAgICAgIGNoZWNrUGFnZU5hbWU6IGZ1bmN0aW9uKHBhZ2VOYW1lKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbWFrZSBzdXJlIHRoZSBuYW1lIGlzIHVuaXF1ZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLnNpdGVQYWdlcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5zaXRlUGFnZXNbaV0ubmFtZSA9PT0gcGFnZU5hbWUgJiYgdGhpcy5hY3RpdmVQYWdlICE9PSB0aGlzLnNpdGVQYWdlc1tpXSApIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYWdlTmFtZUVycm9yID0gXCJUaGUgcGFnZSBuYW1lIG11c3QgYmUgdW5pcXVlLlwiO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfSAgIFxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZW1vdmVzIHVuYWxsb3dlZCBjaGFyYWN0ZXJzIGZyb20gdGhlIHBhZ2UgbmFtZVxuICAgICAgICAqL1xuICAgICAgICBwcmVwUGFnZU5hbWU6IGZ1bmN0aW9uKHBhZ2VOYW1lKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHBhZ2VOYW1lID0gcGFnZU5hbWUucmVwbGFjZSgnICcsICcnKTtcbiAgICAgICAgICAgIHBhZ2VOYW1lID0gcGFnZU5hbWUucmVwbGFjZSgvWz8qIS58JiM7JCVAXCI8PigpKyxdL2csIFwiXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gcGFnZU5hbWU7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2F2ZSBwYWdlIHNldHRpbmdzIGZvciB0aGUgY3VycmVudCBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZVBhZ2VTZXR0aW5nczogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MudGl0bGUgPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzVGl0bGUudmFsdWU7XG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLm1ldGFfZGVzY3JpcHRpb24gPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzTWV0YURlc2NyaXB0aW9uLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5tZXRhX2tleXdvcmRzID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFLZXl3b3Jkcy52YWx1ZTtcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MuaGVhZGVyX2luY2x1ZGVzID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc0luY2x1ZGVzLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5wYWdlX2NzcyA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NQYWdlQ3NzLnZhbHVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHNpdGUubW9kYWxQYWdlU2V0dGluZ3MpLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlIHBhZ2Ugc3RhdHVzZXNcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlUGFnZVN0YXR1czogZnVuY3Rpb24oc3RhdHVzKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5zaXRlUGFnZXMgKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNbaV0uc3RhdHVzID0gc3RhdHVzOyAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2hlY2tzIGFsbCB0aGUgYmxvY2tzIGluIHRoaXMgc2l0ZSBoYXZlIGZpbmlzaGVkIGxvYWRpbmdcbiAgICAgICAgKi9cbiAgICAgICAgbG9hZGVkOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHZhciBpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IHRoaXMuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCAhdGhpcy5zaXRlUGFnZXNbaV0ubG9hZGVkKCkgKSByZXR1cm4gZmFsc2U7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBNYWtlIGV2ZXJ5IGJsb2NrIGhhdmUgYW4gb3ZlcmxheSBkdXJpbmcgZHJhZ2dpbmcgdG8gcHJldmVudCBtb3VzZSBldmVudCBpc3N1ZXNcbiAgICAgICAgKi9cbiAgICAgICAgbW92ZU1vZGU6IGZ1bmN0aW9uICh2YWx1ZSkge1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCB0aGlzLmFjdGl2ZVBhZ2UuYmxvY2tzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gJ29uJyApIHRoaXMuYWN0aXZlUGFnZS5ibG9ja3NbaV0uZnJhbWVDb3Zlci5jbGFzc0xpc3QuYWRkKCdtb3ZlJyk7XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAoIHZhbHVlID09PSAnb2ZmJyApIHRoaXMuYWN0aXZlUGFnZS5ibG9ja3NbaV0uZnJhbWVDb3Zlci5jbGFzc0xpc3QucmVtb3ZlKCdtb3ZlJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBHZXQgZm9ybSBmcm9tIEFFTVxuICAgICAgICAqL1xuICAgICAgICBxdWlja19sb2FkX2Zvcm06IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKCQoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2JykubGVuZ3RoKXtcbiAgICAgICAgICAgICAgICBqUXVlcnkuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6IFwicG9zdFwiLFxuICAgICAgICAgICAgICAgICAgICB1cmw6IFwiL3NpdGVzL2ZldGNoRm9ybVwiLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAnZm9ybUlEJzpmb3JtX2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnanNvbicsXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3M6ZnVuY3Rpb24ocmVzdWx0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihyZXN1bHQudHlwZSA9PT0gJ3N1Y2Nlc3MnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGpoZWlnaHQgPSBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmhlaWdodCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpoZWlnaHQgKz0gMTAwO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuZmluZCgnI3VzZXJfZm9ybV9kaXYnKS5odG1sKHJlc3VsdC5odG1sKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2X3JlbW92ZScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScsIHdpbmRvdy5wYXJlbnQuZG9jdW1lbnQpLmhlaWdodChqaGVpZ2h0KydweCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGVycm9yOiBmdW5jdGlvbihlcnJvclRocm93bil7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhlcnJvclRocm93bik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIFxuICAgIH07XG5cbiAgICBidWlsZGVyVUkuaW5pdCgpOyBzaXRlLmluaXQoKTtcblxuICAgIFxuICAgIC8vKioqKiBFWFBPUlRTXG4gICAgbW9kdWxlLmV4cG9ydHMuc2l0ZSA9IHNpdGU7XG4gICAgbW9kdWxlLmV4cG9ydHMuYnVpbGRlclVJID0gYnVpbGRlclVJO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG4gICAgXCJ1c2Ugc3RyaWN0XCI7XG5cbiAgICB2YXIgc2l0ZUJ1aWxkZXIgPSByZXF1aXJlKCcuL2J1aWxkZXIuanMnKTtcblxuICAgIC8qXG4gICAgICAgIGNvbnN0cnVjdG9yIGZ1bmN0aW9uIGZvciBFbGVtZW50XG4gICAgKi9cbiAgICBtb2R1bGUuZXhwb3J0cy5FbGVtZW50ID0gZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsO1xuICAgICAgICB0aGlzLnNhbmRib3ggPSBmYWxzZTtcbiAgICAgICAgdGhpcy5wYXJlbnRGcmFtZSA9IHt9O1xuICAgICAgICB0aGlzLnBhcmVudEJsb2NrID0ge307Ly9yZWZlcmVuY2UgdG8gdGhlIHBhcmVudCBibG9jayBlbGVtZW50XG4gICAgICAgIHRoaXMuZWRpdGFibGVBdHRyaWJ1dGVzID0gW107XG4gICAgICAgIFxuICAgICAgICAvL21ha2UgY3VycmVudCBlbGVtZW50IGFjdGl2ZS9vcGVuIChiZWluZyB3b3JrZWQgb24pXG4gICAgICAgIHRoaXMuc2V0T3BlbiA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkub2ZmKCdtb3VzZWVudGVyIG1vdXNlbGVhdmUgY2xpY2snKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkuY3NzKHsnb3V0bGluZSc6ICcycHggc29saWQgcmdiYSgyMzMsOTQsOTQsMC41KScsICdvdXRsaW5lLW9mZnNldCc6Jy0ycHgnLCAnY3Vyc29yJzogJ3BvaW50ZXInfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8vc2V0cyB1cCBob3ZlciBhbmQgY2xpY2sgZXZlbnRzLCBtYWtpbmcgdGhlIGVsZW1lbnQgYWN0aXZlIG9uIHRoZSBjYW52YXNcbiAgICAgICAgdGhpcy5hY3RpdmF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgZWxlbWVudCA9IHRoaXM7XG5cbiAgICAgICAgICAgIC8vZGF0YSBhdHRyaWJ1dGVzIGZvciBjb2xvclxuICAgICAgICAgICAgaWYgKCB0aGlzLmVsZW1lbnQudGFnTmFtZSA9PT0gJ0EnICkgJCh0aGlzLmVsZW1lbnQpLmRhdGEoJ2NvbG9yJywgZ2V0Q29tcHV0ZWRTdHlsZSh0aGlzLmVsZW1lbnQpLmNvbG9yKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLmNzcyh7J291dGxpbmUnOiAnbm9uZScsICdjdXJzb3InOiAnJ30pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkub24oJ21vdXNlZW50ZXInLCBmdW5jdGlvbihlKSB7XG5cbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoeydvdXRsaW5lJzogJzJweCBzb2xpZCByZ2JhKDIzMyw5NCw5NCwwLjUpJywgJ291dGxpbmUtb2Zmc2V0JzogJy0ycHgnLCAnY3Vyc29yJzogJ3BvaW50ZXInfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoeydvdXRsaW5lJzogJycsICdjdXJzb3InOiAnJywgJ291dGxpbmUtb2Zmc2V0JzogJyd9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSkub24oJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGVsZW1lbnQuY2xpY2tIYW5kbGVyKHRoaXMpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgdGhpcy5kZWFjdGl2YXRlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5vZmYoJ21vdXNlZW50ZXIgbW91c2VsZWF2ZSBjbGljaycpO1xuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLmNzcyh7J291dGxpbmUnOiAnbm9uZScsICdjdXJzb3InOiAnaW5oZXJpdCd9KTtcblxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLy9yZW1vdmVzIHRoZSBlbGVtZW50cyBvdXRsaW5lXG4gICAgICAgIHRoaXMucmVtb3ZlT3V0bGluZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkuY3NzKHsnb3V0bGluZSc6ICdub25lJywgJ2N1cnNvcic6ICdpbmhlcml0J30pO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvL3NldHMgdGhlIHBhcmVudCBpZnJhbWVcbiAgICAgICAgdGhpcy5zZXRQYXJlbnRGcmFtZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgZG9jID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgICAgICB2YXIgdyA9IGRvYy5kZWZhdWx0VmlldyB8fCBkb2MucGFyZW50V2luZG93O1xuICAgICAgICAgICAgdmFyIGZyYW1lcyA9IHcucGFyZW50LmRvY3VtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKCdpZnJhbWUnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yICh2YXIgaT0gZnJhbWVzLmxlbmd0aDsgaS0tPjA7KSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIGZyYW1lPSBmcmFtZXNbaV07XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGQ9IGZyYW1lLmNvbnRlbnREb2N1bWVudCB8fCBmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuICAgICAgICAgICAgICAgICAgICBpZiAoZD09PWRvYylcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMucGFyZW50RnJhbWUgPSBmcmFtZTtcbiAgICAgICAgICAgICAgICB9IGNhdGNoKGUpIHt9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8vc2V0cyB0aGlzIGVsZW1lbnQncyBwYXJlbnQgYmxvY2sgcmVmZXJlbmNlXG4gICAgICAgIHRoaXMuc2V0UGFyZW50QmxvY2sgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sb29wIHRocm91Z2ggYWxsIHRoZSBibG9ja3Mgb24gdGhlIGNhbnZhc1xuICAgICAgICAgICAgZm9yKCB2YXIgaSA9IDA7IGkgPCBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3MubGVuZ3RoOyB4KysgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vaWYgdGhlIGJsb2NrJ3MgZnJhbWUgbWF0Y2hlcyB0aGlzIGVsZW1lbnQncyBwYXJlbnQgZnJhbWVcbiAgICAgICAgICAgICAgICAgICAgaWYoIHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t4XS5mcmFtZSA9PT0gdGhpcy5wYXJlbnRGcmFtZSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vY3JlYXRlIGEgcmVmZXJlbmNlIHRvIHRoYXQgYmxvY2sgYW5kIHN0b3JlIGl0IGluIHRoaXMucGFyZW50QmxvY2tcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMucGFyZW50QmxvY2sgPSBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeF07XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICB0aGlzLnNldFBhcmVudEZyYW1lKCk7XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgaXMgdGhpcyBibG9jayBzYW5kYm94ZWQ/XG4gICAgICAgICovXG4gICAgICAgIFxuICAgICAgICBpZiggdGhpcy5wYXJlbnRGcmFtZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc2FuZGJveCcpICkge1xuICAgICAgICAgICAgdGhpcy5zYW5kYm94ID0gdGhpcy5wYXJlbnRGcmFtZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc2FuZGJveCcpOyAgIFxuICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgfTtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcbiAgICAgICAgXG4gICAgbW9kdWxlLmV4cG9ydHMucGFnZUNvbnRhaW5lciA9IFwiI3BhZ2VcIjtcblxuICAgIG1vZHVsZS5leHBvcnRzLmJvZHlQYWRkaW5nQ2xhc3MgPSBcImJQYWRkaW5nXCI7XG4gICAgXG4gICAgbW9kdWxlLmV4cG9ydHMuZWRpdGFibGVJdGVtcyA9IHtcbiAgICAgICAgJ3NwYW4uZmEnOiBbJ2NvbG9yJywgJ2ZvbnQtc2l6ZSddLFxuICAgICAgICAnLmJnLmJnMSc6IFsnYmFja2dyb3VuZC1jb2xvciddLFxuICAgICAgICAnbmF2IGEnOiBbJ2NvbG9yJywgJ2ZvbnQtd2VpZ2h0JywgJ3RleHQtdHJhbnNmb3JtJ10sXG4gICAgICAgICdpbWcnOiBbJ2JvcmRlci10b3AtbGVmdC1yYWRpdXMnLCAnYm9yZGVyLXRvcC1yaWdodC1yYWRpdXMnLCAnYm9yZGVyLWJvdHRvbS1sZWZ0LXJhZGl1cycsICdib3JkZXItYm90dG9tLXJpZ2h0LXJhZGl1cycsICdib3JkZXItY29sb3InLCAnYm9yZGVyLXN0eWxlJywgJ2JvcmRlci13aWR0aCddLFxuICAgICAgICAnaHIuZGFzaGVkJzogWydib3JkZXItY29sb3InLCAnYm9yZGVyLXdpZHRoJ10sXG4gICAgICAgICcuZGl2aWRlciA+IHNwYW4nOiBbJ2NvbG9yJywgJ2ZvbnQtc2l6ZSddLFxuICAgICAgICAnaHIuc2hhZG93RG93bic6IFsnbWFyZ2luLXRvcCcsICdtYXJnaW4tYm90dG9tJ10sXG4gICAgICAgICcuZm9vdGVyIGEnOiBbJ2NvbG9yJ10sXG4gICAgICAgICcuc29jaWFsIGEnOiBbJ2NvbG9yJ10sXG4gICAgICAgICcuYmcuYmcxLCAuYmcuYmcyLCAuaGVhZGVyMTAsIC5oZWFkZXIxMSc6IFsnYmFja2dyb3VuZC1pbWFnZScsICdiYWNrZ3JvdW5kLWNvbG9yJ10sXG4gICAgICAgICcuZnJhbWVDb3Zlcic6IFtdLFxuICAgICAgICAnLmVkaXRDb250ZW50JzogWydjb250ZW50JywgJ2NvbG9yJywgJ2ZvbnQtc2l6ZScsICdiYWNrZ3JvdW5kLWNvbG9yJywgJ2ZvbnQtZmFtaWx5J10sXG4gICAgICAgICdhLmJ0biwgYnV0dG9uLmJ0bic6IFsnYm9yZGVyLXJhZGl1cycsICdmb250LXNpemUnLCAnYmFja2dyb3VuZC1jb2xvciddLFxuICAgICAgICAnI3ByaWNpbmdfdGFibGUyIC5wcmljaW5nMiAuYm90dG9tIGxpJzogWydjb250ZW50J11cbiAgICB9O1xuICAgIFxuICAgIG1vZHVsZS5leHBvcnRzLmVkaXRhYmxlSXRlbU9wdGlvbnMgPSB7XG4gICAgICAgICduYXYgYSA6IGZvbnQtd2VpZ2h0JzogWyc0MDAnLCAnNzAwJ10sXG4gICAgICAgICdhLmJ0biA6IGJvcmRlci1yYWRpdXMnOiBbJzBweCcsICc0cHgnLCAnMTBweCddLFxuICAgICAgICAnaW1nIDogYm9yZGVyLXN0eWxlJzogWydub25lJywgJ2RvdHRlZCcsICdkYXNoZWQnLCAnc29saWQnXSxcbiAgICAgICAgJ2ltZyA6IGJvcmRlci13aWR0aCc6IFsnMXB4JywgJzJweCcsICczcHgnLCAnNHB4J10sXG4gICAgICAgICdoMSwgaDIsIGgzLCBoNCwgaDUsIHAgOiBmb250LWZhbWlseSc6IFsnZGVmYXVsdCcsICdMYXRvJywgJ0hlbHZldGljYScsICdBcmlhbCcsICdUaW1lcyBOZXcgUm9tYW4nXSxcbiAgICAgICAgJ2gyIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ10sXG4gICAgICAgICdoMyA6IGZvbnQtZmFtaWx5JzogWydkZWZhdWx0JywgJ0xhdG8nLCAnSGVsdmV0aWNhJywgJ0FyaWFsJywgJ1RpbWVzIE5ldyBSb21hbiddLFxuICAgICAgICAncCA6IGZvbnQtZmFtaWx5JzogWydkZWZhdWx0JywgJ0xhdG8nLCAnSGVsdmV0aWNhJywgJ0FyaWFsJywgJ1RpbWVzIE5ldyBSb21hbiddXG4gICAgfTtcblxuICAgIG1vZHVsZS5leHBvcnRzLnJlc3BvbnNpdmVNb2RlcyA9IHtcbiAgICAgICAgZGVza3RvcDogJzk3JScsXG4gICAgICAgIG1vYmlsZTogJzQ4MHB4JyxcbiAgICAgICAgdGFibGV0OiAnMTAyNHB4J1xuICAgIH07XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5lZGl0YWJsZUNvbnRlbnQgPSBbJy5lZGl0Q29udGVudCcsICcubmF2YmFyIGEnLCAnYnV0dG9uJywgJ2EuYnRuJywgJy5mb290ZXIgYTpub3QoLmZhKScsICcudGFibGVXcmFwcGVyJywgJ2gxJywgJ2gyJ107XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5hdXRvU2F2ZVRpbWVvdXQgPSAzMDAwMDA7XG4gICAgXG4gICAgbW9kdWxlLmV4cG9ydHMuc291cmNlQ29kZUVkaXRTeW50YXhEZWxheSA9IDEwMDAwO1xuXG4gICAgbW9kdWxlLmV4cG9ydHMubWVkaXVtQ3NzVXJscyA9IFtcbiAgICAgICAgJy8vY2RuLmpzZGVsaXZyLm5ldC9tZWRpdW0tZWRpdG9yL2xhdGVzdC9jc3MvbWVkaXVtLWVkaXRvci5taW4uY3NzJyxcbiAgICAgICAgJy9jc3MvbWVkaXVtLWJvb3RzdHJhcC5jc3MnXG4gICAgXTtcbiAgICBtb2R1bGUuZXhwb3J0cy5tZWRpdW1CdXR0b25zID0gWydib2xkJywgJ2l0YWxpYycsICd1bmRlcmxpbmUnLCAnYW5jaG9yJywgJ29yZGVyZWRsaXN0JywgJ3Vub3JkZXJlZGxpc3QnLCAnaDEnLCAnaDInLCAnaDMnLCAnaDQnLCAncmVtb3ZlRm9ybWF0J107XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5leHRlcm5hbEpTID0gW1xuICAgICAgICAnanMvYnVpbGRlcl9pbl9ibG9jay5qcydcbiAgICBdO1xuICAgICAgICAgICAgICAgICAgICBcbn0oKSk7IiwiKGZ1bmN0aW9uICgpe1xuXHRcInVzZSBzdHJpY3RcIjtcblxuICAgIHZhciBiQ29uZmlnID0gcmVxdWlyZSgnLi9jb25maWcuanMnKTtcbiAgICB2YXIgc2l0ZUJ1aWxkZXIgPSByZXF1aXJlKCcuL2J1aWxkZXIuanMnKTtcbiAgICB2YXIgZWRpdG9yID0gcmVxdWlyZSgnLi9zdHlsZWVkaXRvci5qcycpLnN0eWxlZWRpdG9yO1xuICAgIHZhciBhcHBVSSA9IHJlcXVpcmUoJy4vdWkuanMnKS5hcHBVSTtcblxuICAgIHZhciBpbWFnZUxpYnJhcnkgPSB7XG4gICAgICAgIFxuICAgICAgICBpbWFnZU1vZGFsOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW1hZ2VNb2RhbCcpLFxuICAgICAgICBpbnB1dEltYWdlVXBsb2FkOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW1hZ2VGaWxlJyksXG4gICAgICAgIGJ1dHRvblVwbG9hZEltYWdlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXBsb2FkSW1hZ2VCdXR0b24nKSxcbiAgICAgICAgaW1hZ2VMaWJyYXJ5TGlua3M6IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5pbWFnZXMgPiAuaW1hZ2UgLmJ1dHRvbnMgLmJ0bi1wcmltYXJ5LCAuaW1hZ2VzIC5pbWFnZVdyYXAgPiBhJyksLy91c2VkIGluIHRoZSBsaWJyYXJ5LCBvdXRzaWRlIHRoZSBidWlsZGVyIFVJXG4gICAgICAgIG15SW1hZ2VzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbXlJbWFnZXMnKSwvL3VzZWQgaW4gdGhlIGltYWdlIGxpYnJhcnksIG91dHNpZGUgdGhlIGJ1aWxkZXIgVUlcbiAgICBcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmltYWdlTW9kYWwpLm9uKCdzaG93LmJzLm1vZGFsJywgdGhpcy5pbWFnZUxpYnJhcnkpO1xuICAgICAgICAgICAgJCh0aGlzLmlucHV0SW1hZ2VVcGxvYWQpLm9uKCdjaGFuZ2UnLCB0aGlzLmltYWdlSW5wdXRDaGFuZ2UpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblVwbG9hZEltYWdlKS5vbignY2xpY2snLCB0aGlzLnVwbG9hZEltYWdlKTtcbiAgICAgICAgICAgICQodGhpcy5pbWFnZUxpYnJhcnlMaW5rcykub24oJ2NsaWNrJywgdGhpcy5pbWFnZUluTW9kYWwpO1xuICAgICAgICAgICAgJCh0aGlzLm15SW1hZ2VzKS5vbignY2xpY2snLCAnLmJ1dHRvbnMgLmJ0bi1kYW5nZXInLCB0aGlzLmRlbGV0ZUltYWdlKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpbWFnZSBsaWJyYXJ5IG1vZGFsXG4gICAgICAgICovXG4gICAgICAgIGltYWdlTGlicmFyeTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcdFx0XHRcbiAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykub2ZmKCdjbGljaycsICcuaW1hZ2UgYnV0dG9uLnVzZUltYWdlJyk7XG5cdFx0XHRcbiAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykub24oJ2NsaWNrJywgJy5pbWFnZSBidXR0b24udXNlSW1hZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vdXBkYXRlIGxpdmUgaW1hZ2VcbiAgICAgICAgICAgICAgICAkKGVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ3NyYycsICQodGhpcykuYXR0cignZGF0YS11cmwnKSk7XG5cbiAgICAgICAgICAgICAgICAvL3VwZGF0ZSBpbWFnZSBVUkwgZmllbGRcbiAgICAgICAgICAgICAgICAkKCdpbnB1dCNpbWFnZVVSTCcpLnZhbCggJCh0aGlzKS5hdHRyKCdkYXRhLXVybCcpICk7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgIC8vaGlkZSBtb2RhbFxuICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykubW9kYWwoJ2hpZGUnKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgLy9oZWlnaHQgYWRqdXN0bWVudCBvZiB0aGUgaWZyYW1lIGhlaWdodEFkanVzdG1lbnRcblx0XHRcdFx0ZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2suaGVpZ2h0QWRqdXN0bWVudCgpO1x0XHRcdFx0XHRcdFx0XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cdFx0XHRcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnVuYmluZCgnY2xpY2snKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgaW1hZ2UgdXBsb2FkIGlucHV0IGNoYW5lZyBldmVudCBoYW5kbGVyXG4gICAgICAgICovXG4gICAgICAgIGltYWdlSW5wdXRDaGFuZ2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCh0aGlzKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgLy9ubyBmaWxlLCBkaXNhYmxlIHN1Ym1pdCBidXR0b25cbiAgICAgICAgICAgICAgICAkKCdidXR0b24jdXBsb2FkSW1hZ2VCdXR0b24nKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgLy9nb3QgYSBmaWxlLCBlbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgJCgnYnV0dG9uI3VwbG9hZEltYWdlQnV0dG9uJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBsb2FkIGFuIGltYWdlIHRvIHRoZSBpbWFnZSBsaWJyYXJ5XG4gICAgICAgICovXG4gICAgICAgIHVwbG9hZEltYWdlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJ2lucHV0I2ltYWdlRmlsZScpLnZhbCgpICE9PSAnJyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBvbGQgYWxlcnRzXG4gICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLm1vZGFsLWFsZXJ0cyA+IConKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgJCgnYnV0dG9uI3VwbG9hZEltYWdlQnV0dG9uJykuYWRkQ2xhc3MoJ2Rpc2FibGUnKTtcblxuICAgICAgICAgICAgICAgIC8vc2hvdyBsb2FkZXJcbiAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAubG9hZGVyJykuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIGZvcm0gPSAkKCdmb3JtI2ltYWdlVXBsb2FkRm9ybScpO1xuICAgICAgICAgICAgICAgIHZhciBmb3JtZGF0YSA9IGZhbHNlO1xuXG4gICAgICAgICAgICAgICAgaWYgKHdpbmRvdy5Gb3JtRGF0YSl7XG4gICAgICAgICAgICAgICAgICAgIGZvcm1kYXRhID0gbmV3IEZvcm1EYXRhKGZvcm1bMF0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgZm9ybUFjdGlvbiA9IGZvcm0uYXR0cignYWN0aW9uJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsIDogZm9ybUFjdGlvbixcbiAgICAgICAgICAgICAgICAgICAgZGF0YSA6IGZvcm1kYXRhID8gZm9ybWRhdGEgOiBmb3JtLnNlcmlhbGl6ZSgpLFxuICAgICAgICAgICAgICAgICAgICBjYWNoZSA6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBjb250ZW50VHlwZSA6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBwcm9jZXNzRGF0YSA6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICAgICAgICAgIHR5cGUgOiAnUE9TVCdcbiAgICAgICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAgICAgJCgnYnV0dG9uI3VwbG9hZEltYWdlQnV0dG9uJykuYWRkQ2xhc3MoJ2Rpc2FibGUnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vaGlkZSBsb2FkZXJcbiAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLmxvYWRlcicpLmZhZGVPdXQoNTAwKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAwICkgey8vZXJyb3JcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLm1vZGFsLWFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuXHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL3N1Y2Nlc3NcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9hcHBlbmQgbXkgaW1hZ2VcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNteUltYWdlc1RhYiA+IConKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNteUltYWdlc1RhYicpLmFwcGVuZCggJChyZXQubXlJbWFnZXMpICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAubW9kYWwtYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXskKCcjaW1hZ2VNb2RhbCAubW9kYWwtYWxlcnRzID4gKicpLmZhZGVPdXQoNTAwKTt9LCAzMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgIGFsZXJ0KCdObyBpbWFnZSBzZWxlY3RlZCcpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGlzcGxheXMgaW1hZ2UgaW4gbW9kYWxcbiAgICAgICAgKi9cbiAgICAgICAgaW1hZ2VJbk1vZGFsOiBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICBcdFx0XG4gICAgXHRcdHZhciB0aGVTcmMgPSAkKHRoaXMpLmNsb3Nlc3QoJy5pbWFnZScpLmZpbmQoJ2ltZycpLmF0dHIoJ3NyYycpO1xuICAgIFx0XHRcbiAgICBcdFx0JCgnaW1nI3RoZVBpYycpLmF0dHIoJ3NyYycsIHRoZVNyYyk7XG4gICAgXHRcdFxuICAgIFx0XHQkKCcjdmlld1BpYycpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlcyBhbiBpbWFnZSBmcm9tIHRoZSBsaWJyYXJ5XG4gICAgICAgICovXG4gICAgICAgIGRlbGV0ZUltYWdlOiBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICBcdFx0XG4gICAgXHRcdHZhciB0b0RlbCA9ICQodGhpcykuY2xvc2VzdCgnLmltYWdlJyk7XG4gICAgXHRcdHZhciB0aGVVUkwgPSAkKHRoaXMpLmF0dHIoJ2RhdGEtaW1nJyk7XG4gICAgXHRcdFxuICAgIFx0XHQkKCcjZGVsZXRlSW1hZ2VNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG4gICAgXHRcdFxuICAgIFx0XHQkKCdidXR0b24jZGVsZXRlSW1hZ2VCdXR0b24nKS5jbGljayhmdW5jdGlvbigpe1xuICAgIFx0XHRcbiAgICBcdFx0XHQkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdHZhciB0aGVCdXR0b24gPSAkKHRoaXMpO1xuICAgIFx0XHRcbiAgICBcdFx0XHQkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJhc3NldHMvZGVsSW1hZ2VcIixcbiAgICBcdFx0XHRcdGRhdGE6IHtmaWxlOiB0aGVVUkx9LFxuICAgIFx0XHRcdFx0dHlwZTogJ3Bvc3QnXG4gICAgXHRcdFx0fSkuZG9uZShmdW5jdGlvbigpe1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdFx0dGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHQkKCcjZGVsZXRlSW1hZ2VNb2RhbCcpLm1vZGFsKCdoaWRlJyk7XG4gICAgXHRcdFx0XHRcbiAgICBcdFx0XHRcdHRvRGVsLmZhZGVPdXQoODAwLCBmdW5jdGlvbigpe1xuICAgIFx0XHRcdFx0XHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQkKHRoaXMpLnJlbW92ZSgpO1xuICAgIFx0XHRcdFx0XHRcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHR9KTtcbiAgICBcdFx0XHRcbiAgICBcdFx0XHR9KTtcbiAgICBcdFx0XG4gICAgXHRcdFxuICAgIFx0XHR9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgICAgIFxuICAgIH07XG4gICAgXG4gICAgaW1hZ2VMaWJyYXJ5LmluaXQoKTtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCl7XG5cdFwidXNlIHN0cmljdFwiO1xuXG5cdHZhciBjYW52YXNFbGVtZW50ID0gcmVxdWlyZSgnLi9jYW52YXNFbGVtZW50LmpzJykuRWxlbWVudDtcblx0dmFyIGJDb25maWcgPSByZXF1aXJlKCcuL2NvbmZpZy5qcycpO1xuXHR2YXIgc2l0ZUJ1aWxkZXIgPSByZXF1aXJlKCcuL2J1aWxkZXIuanMnKTtcbiAgICB2YXIgcHVibGlzaGVyID0gcmVxdWlyZSgnLi4vdmVuZG9yL3B1Ymxpc2hlcicpO1xuXG4gICAgdmFyIHN0eWxlZWRpdG9yID0ge1xuXG4gICAgICAgIGJ1dHRvblNhdmVDaGFuZ2VzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2F2ZVN0eWxpbmcnKSxcbiAgICAgICAgYWN0aXZlRWxlbWVudDoge30sIC8vaG9sZHMgdGhlIGVsZW1lbnQgY3VycmVudHkgYmVpbmcgZWRpdGVkXG4gICAgICAgIGFsbFN0eWxlSXRlbXNPbkNhbnZhczogW10sXG4gICAgICAgIF9vbGRJY29uOiBbXSxcbiAgICAgICAgc3R5bGVFZGl0b3I6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzdHlsZUVkaXRvcicpLFxuICAgICAgICBmb3JtU3R5bGU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzdHlsaW5nRm9ybScpLFxuICAgICAgICBidXR0b25SZW1vdmVFbGVtZW50OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGVsZXRlRWxlbWVudENvbmZpcm0nKSxcbiAgICAgICAgYnV0dG9uQ2xvbmVFbGVtZW50OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2xvbmVFbGVtZW50QnV0dG9uJyksXG4gICAgICAgIGJ1dHRvblJlc2V0RWxlbWVudDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Jlc2V0U3R5bGVCdXR0b24nKSxcbiAgICAgICAgc2VsZWN0TGlua3NJbmVybmFsOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW50ZXJuYWxMaW5rc0Ryb3Bkb3duJyksXG4gICAgICAgIHNlbGVjdExpbmtzUGFnZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlTGlua3NEcm9wZG93bicpLFxuICAgICAgICB2aWRlb0lucHV0WW91dHViZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3lvdXR1YmVJRCcpLFxuICAgICAgICB2aWRlb0lucHV0VmltZW86IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd2aW1lb0lEJyksXG4gICAgICAgIGlucHV0Q3VzdG9tTGluazogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludGVybmFsTGlua3NDdXN0b20nKSxcbiAgICAgICAgbGlua0ltYWdlOiBudWxsLFxuICAgICAgICBsaW5rSWNvbjogbnVsbCxcbiAgICAgICAgaW5wdXRMaW5rVGV4dDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xpbmtUZXh0JyksXG4gICAgICAgIHNlbGVjdEljb25zOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaWNvbnMnKSxcbiAgICAgICAgYnV0dG9uRGV0YWlsc0FwcGxpZWRIaWRlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGV0YWlsc0FwcGxpZWRNZXNzYWdlSGlkZScpLFxuICAgICAgICBidXR0b25DbG9zZVN0eWxlRWRpdG9yOiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjc3R5bGVFZGl0b3IgPiBhLmNsb3NlJyksXG4gICAgICAgIHVsUGFnZUxpc3Q6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlTGlzdCcpLFxuICAgICAgICByZXNwb25zaXZlVG9nZ2xlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncmVzcG9uc2l2ZVRvZ2dsZScpLFxuICAgICAgICB0aGVTY3JlZW46IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzY3JlZW4nKSxcblxuICAgICAgICBpbml0OiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnN1YnNjcmliZSgnY2xvc2VTdHlsZUVkaXRvcicsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5jbG9zZVN0eWxlRWRpdG9yKCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnN1YnNjcmliZSgnb25CbG9ja0xvYWRlZCcsIGZ1bmN0aW9uIChibG9jaykge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNldHVwQ2FudmFzRWxlbWVudHMoYmxvY2spO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5zdWJzY3JpYmUoJ29uU2V0TW9kZScsIGZ1bmN0aW9uIChtb2RlKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzcG9uc2l2ZU1vZGVDaGFuZ2UobW9kZSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy9ldmVudHNcbiAgICAgICAgICAgICQodGhpcy5idXR0b25TYXZlQ2hhbmdlcykub24oJ2NsaWNrJywgdGhpcy51cGRhdGVTdHlsaW5nKTtcbiAgICAgICAgICAgICQodGhpcy5mb3JtU3R5bGUpLm9uKCdmb2N1cycsICdpbnB1dCcsIHRoaXMuYW5pbWF0ZVN0eWxlSW5wdXRJbikub24oJ2JsdXInLCAnaW5wdXQnLCB0aGlzLmFuaW1hdGVTdHlsZUlucHV0T3V0KTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25SZW1vdmVFbGVtZW50KS5vbignY2xpY2snLCB0aGlzLmRlbGV0ZUVsZW1lbnQpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbkNsb25lRWxlbWVudCkub24oJ2NsaWNrJywgdGhpcy5jbG9uZUVsZW1lbnQpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblJlc2V0RWxlbWVudCkub24oJ2NsaWNrJywgdGhpcy5yZXNldEVsZW1lbnQpO1xuICAgICAgICAgICAgJCh0aGlzLnZpZGVvSW5wdXRZb3V0dWJlKS5vbignZm9jdXMnLCBmdW5jdGlvbigpeyAkKHN0eWxlZWRpdG9yLnZpZGVvSW5wdXRWaW1lbykudmFsKCcnKTsgfSk7XG4gICAgICAgICAgICAkKHRoaXMudmlkZW9JbnB1dFZpbWVvKS5vbignZm9jdXMnLCBmdW5jdGlvbigpeyAkKHN0eWxlZWRpdG9yLnZpZGVvSW5wdXRZb3V0dWJlKS52YWwoJycpOyB9KTtcbiAgICAgICAgICAgICQodGhpcy5pbnB1dEN1c3RvbUxpbmspLm9uKCdmb2N1cycsIHRoaXMucmVzZXRTZWxlY3RBbGxMaW5rcyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uRGV0YWlsc0FwcGxpZWRIaWRlKS5vbignY2xpY2snLCBmdW5jdGlvbigpeyQodGhpcykucGFyZW50KCkuZmFkZU91dCg1MDApO30pO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbkNsb3NlU3R5bGVFZGl0b3IpLm9uKCdjbGljaycsIHRoaXMuY2xvc2VTdHlsZUVkaXRvcik7XG4gICAgICAgICAgICAkKHRoaXMuaW5wdXRDdXN0b21MaW5rKS5vbignZm9jdXMnLCB0aGlzLmlucHV0Q3VzdG9tTGlua0ZvY3VzKS5vbignYmx1cicsIHRoaXMuaW5wdXRDdXN0b21MaW5rQmx1cik7XG4gICAgICAgICAgICAkKGRvY3VtZW50KS5vbignbW9kZUNvbnRlbnQgbW9kZUJsb2NrcycsICdib2R5JywgdGhpcy5kZUFjdGl2YXRlTW9kZSk7XG5cbiAgICAgICAgICAgIC8vY2hvc2VuIGZvbnQtYXdlc29tZSBkcm9wZG93blxuICAgICAgICAgICAgJCh0aGlzLnNlbGVjdEljb25zKS5jaG9zZW4oeydzZWFyY2hfY29udGFpbnMnOiB0cnVlfSk7XG5cbiAgICAgICAgICAgIC8vY2hlY2sgaWYgZm9ybURhdGEgaXMgc3VwcG9ydGVkXG4gICAgICAgICAgICBpZiAoIXdpbmRvdy5Gb3JtRGF0YSl7XG4gICAgICAgICAgICAgICAgdGhpcy5oaWRlRmlsZVVwbG9hZHMoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy9saXN0ZW4gZm9yIHRoZSBiZWZvcmVTYXZlIGV2ZW50XG4gICAgICAgICAgICAkKCdib2R5Jykub24oJ2JlZm9yZVNhdmUnLCB0aGlzLmNsb3NlU3R5bGVFZGl0b3IpO1xuXG4gICAgICAgICAgICAvL3Jlc3BvbnNpdmUgdG9nZ2xlXG4gICAgICAgICAgICAkKHRoaXMucmVzcG9uc2l2ZVRvZ2dsZSkub24oJ2NsaWNrJywgJ2EnLCB0aGlzLnRvZ2dsZVJlc3BvbnNpdmVDbGljayk7XG5cbiAgICAgICAgICAgIC8vc2V0IHRoZSBkZWZhdWx0IHJlc3BvbnNpdmUgbW9kZVxuICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuYnVpbGRlclVJLmN1cnJlbnRSZXNwb25zaXZlTW9kZSA9IE9iamVjdC5rZXlzKGJDb25maWcucmVzcG9uc2l2ZU1vZGVzKVswXTtcblxuICAgICAgICB9LFxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBFdmVudCBoYW5kbGVyIGZvciByZXNwb25zaXZlIG1vZGUgbGlua3NcbiAgICAgICAgKi9cbiAgICAgICAgdG9nZ2xlUmVzcG9uc2l2ZUNsaWNrOiBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc3BvbnNpdmVNb2RlQ2hhbmdlKHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLXJlc3BvbnNpdmUnKSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBUb2dnbGVzIHRoZSByZXNwb25zaXZlIG1vZGVcbiAgICAgICAgKi9cbiAgICAgICAgcmVzcG9uc2l2ZU1vZGVDaGFuZ2U6IGZ1bmN0aW9uIChtb2RlKSB7XG5cbiAgICAgICAgICAgIHZhciBsaW5rcyxcbiAgICAgICAgICAgICAgICBpO1xuXG4gICAgICAgICAgICAvL1VJIHN0dWZmXG4gICAgICAgICAgICBsaW5rcyA9IHN0eWxlZWRpdG9yLnJlc3BvbnNpdmVUb2dnbGUucXVlcnlTZWxlY3RvckFsbCgnbGknKTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCBsaW5rcy5sZW5ndGg7IGkrKyApIGxpbmtzW2ldLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuXG4gICAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2RhdGEtcmVzcG9uc2l2ZT1cIicgKyBtb2RlICsgJ1wiXScpLnBhcmVudE5vZGUuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG5cblxuICAgICAgICAgICAgZm9yICggdmFyIGtleSBpbiBiQ29uZmlnLnJlc3BvbnNpdmVNb2RlcyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggYkNvbmZpZy5yZXNwb25zaXZlTW9kZXMuaGFzT3duUHJvcGVydHkoa2V5KSApIHRoaXMudGhlU2NyZWVuLmNsYXNzTGlzdC5yZW1vdmUoa2V5KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIGJDb25maWcucmVzcG9uc2l2ZU1vZGVzW21vZGVdICkge1xuXG4gICAgICAgICAgICAgICAgdGhpcy50aGVTY3JlZW4uY2xhc3NMaXN0LmFkZChtb2RlKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMudGhlU2NyZWVuKS5hbmltYXRlKHt3aWR0aDogYkNvbmZpZy5yZXNwb25zaXZlTW9kZXNbbW9kZV19LCA2NTAsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgLy9oZWlnaHQgYWRqdXN0bWVudFxuICAgICAgICAgICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLmFjdGl2ZVBhZ2UuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLmJ1aWxkZXJVSS5jdXJyZW50UmVzcG9uc2l2ZU1vZGUgPSBtb2RlO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQWN0aXZhdGVzIHN0eWxlIGVkaXRvciBtb2RlXG4gICAgICAgICovXG4gICAgICAgIHNldHVwQ2FudmFzRWxlbWVudHM6IGZ1bmN0aW9uKGJsb2NrKSB7XG5cbiAgICAgICAgICAgIGlmICggYmxvY2sgPT09IHVuZGVmaW5lZCApIHJldHVybiBmYWxzZTtcblxuICAgICAgICAgICAgdmFyIGk7XG5cbiAgICAgICAgICAgIC8vY3JlYXRlIGFuIG9iamVjdCBmb3IgZXZlcnkgZWRpdGFibGUgZWxlbWVudCBvbiB0aGUgY2FudmFzIGFuZCBzZXR1cCBpdCdzIGV2ZW50c1xuXG4gICAgICAgICAgICBmb3IoIHZhciBrZXkgaW4gYkNvbmZpZy5lZGl0YWJsZUl0ZW1zICkge1xuXG4gICAgICAgICAgICAgICAgJChibG9jay5mcmFtZSkuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKyAnICcrIGtleSApLmVhY2goZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNldHVwQ2FudmFzRWxlbWVudHNPbkVsZW1lbnQodGhpcywga2V5KTtcblxuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBTZXRzIHVwIGNhbnZhcyBlbGVtZW50cyBvbiBlbGVtZW50XG4gICAgICAgICovXG4gICAgICAgIHNldHVwQ2FudmFzRWxlbWVudHNPbkVsZW1lbnQ6IGZ1bmN0aW9uIChlbGVtZW50LCBrZXkpIHtcblxuICAgICAgICAgICAgLy9FbGVtZW50IG9iamVjdCBleHRlbnRpb25cbiAgICAgICAgICAgIGNhbnZhc0VsZW1lbnQucHJvdG90eXBlLmNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGVsKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc3R5bGVDbGljayh0aGlzKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBuZXdFbGVtZW50ID0gbmV3IGNhbnZhc0VsZW1lbnQoZWxlbWVudCk7XG5cbiAgICAgICAgICAgIG5ld0VsZW1lbnQuZWRpdGFibGVBdHRyaWJ1dGVzID0gYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW2tleV07XG4gICAgICAgICAgICBuZXdFbGVtZW50LnNldFBhcmVudEJsb2NrKCk7XG4gICAgICAgICAgICBuZXdFbGVtZW50LmFjdGl2YXRlKCk7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFsbFN0eWxlSXRlbXNPbkNhbnZhcy5wdXNoKCBuZXdFbGVtZW50ICk7XG5cbiAgICAgICAgICAgIGlmICggdHlwZW9mIGtleSAhPT0gdW5kZWZpbmVkICkgJChlbGVtZW50KS5hdHRyKCdkYXRhLXNlbGVjdG9yJywga2V5KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEV2ZW50IGhhbmRsZXIgZm9yIHdoZW4gdGhlIHN0eWxlIGVkaXRvciBpcyBlbnZva2VkIG9uIGFuIGl0ZW1cbiAgICAgICAgKi9cbiAgICAgICAgc3R5bGVDbGljazogZnVuY3Rpb24oZWxlbWVudCkge1xuXG4gICAgICAgICAgICAvL2lmIHdlIGhhdmUgYW4gYWN0aXZlIGVsZW1lbnQsIG1ha2UgaXQgdW5hY3RpdmVcbiAgICAgICAgICAgIGlmKCBPYmplY3Qua2V5cyh0aGlzLmFjdGl2ZUVsZW1lbnQpLmxlbmd0aCAhPT0gMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuYWN0aXZlRWxlbWVudC5hY3RpdmF0ZSgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL3NldCB0aGUgYWN0aXZlIGVsZW1lbnRcbiAgICAgICAgICAgIHRoaXMuYWN0aXZlRWxlbWVudCA9IGVsZW1lbnQ7XG5cbiAgICAgICAgICAgIC8vdW5iaW5kIGhvdmVyIGFuZCBjbGljayBldmVudHMgYW5kIG1ha2UgdGhpcyBpdGVtIGFjdGl2ZVxuICAgICAgICAgICAgdGhpcy5hY3RpdmVFbGVtZW50LnNldE9wZW4oKTtcblxuICAgICAgICAgICAgdmFyIHRoZVNlbGVjdG9yID0gJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignZGF0YS1zZWxlY3RvcicpO1xuXG4gICAgICAgICAgICAkKCcjZWRpdGluZ0VsZW1lbnQnKS50ZXh0KCB0aGVTZWxlY3RvciApO1xuXG4gICAgICAgICAgICAvL2FjdGl2YXRlIGZpcnN0IHRhYlxuICAgICAgICAgICAgJCgnI2RldGFpbFRhYnMgYTpmaXJzdCcpLmNsaWNrKCk7XG5cbiAgICAgICAgICAgIC8vaGlkZSBhbGwgYnkgZGVmYXVsdFxuICAgICAgICAgICAgJCgndWwjZGV0YWlsVGFicyBsaTpndCgwKScpLmhpZGUoKTtcblxuICAgICAgICAgICAgLy9jb250ZW50IGVkaXRvcj9cbiAgICAgICAgICAgIGZvciggdmFyIGl0ZW0gaW4gYkNvbmZpZy5lZGl0YWJsZUl0ZW1zICkge1xuXG4gICAgICAgICAgICAgICAgaWYoIGJDb25maWcuZWRpdGFibGVJdGVtcy5oYXNPd25Qcm9wZXJ0eShpdGVtKSAmJiBpdGVtID09PSB0aGVTZWxlY3RvciApIHtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoIGJDb25maWcuZWRpdGFibGVJdGVtc1tpdGVtXS5pbmRleE9mKCdjb250ZW50JykgIT09IC0xICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2VkaXQgY29udGVudFxuICAgICAgICAgICAgICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQ2xpY2tDb250ZW50JywgZWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy93aGF0IGFyZSB3ZSBkZWFsaW5nIHdpdGg/XG4gICAgICAgICAgICBpZiggJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpID09PSAnQScgfHwgJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmVkaXRMaW5rKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgfVxuXG5cdFx0XHRpZiggJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpID09PSAnSU1HJyApe1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lZGl0SW1hZ2UodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICB9XG5cblx0XHRcdGlmKCAkKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdkYXRhLXR5cGUnKSA9PT0gJ3ZpZGVvJyApIHtcblxuICAgICAgICAgICAgICAgIHRoaXMuZWRpdFZpZGVvKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgfVxuXG5cdFx0XHRpZiggJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuaGFzQ2xhc3MoJ2ZhJykgKSB7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmVkaXRJY29uKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL2xvYWQgdGhlIGF0dHJpYnV0ZXNcbiAgICAgICAgICAgIHRoaXMuYnVpbGRlU3R5bGVFbGVtZW50cyh0aGVTZWxlY3Rvcik7XG5cbiAgICAgICAgICAgIC8vb3BlbiBzaWRlIHBhbmVsXG4gICAgICAgICAgICB0aGlzLnRvZ2dsZVNpZGVQYW5lbCgnb3BlbicpO1xuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkeW5hbWljYWxseSBnZW5lcmF0ZXMgdGhlIGZvcm0gZmllbGRzIGZvciBlZGl0aW5nIGFuIGVsZW1lbnRzIHN0eWxlIGF0dHJpYnV0ZXNcbiAgICAgICAgKi9cbiAgICAgICAgYnVpbGRlU3R5bGVFbGVtZW50czogZnVuY3Rpb24odGhlU2VsZWN0b3IpIHtcblxuICAgICAgICAgICAgLy9kZWxldGUgdGhlIG9sZCBvbmVzIGZpcnN0XG4gICAgICAgICAgICAkKCcjc3R5bGVFbGVtZW50cyA+ICo6bm90KCNzdHlsZUVsVGVtcGxhdGUpJykuZWFjaChmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGZvciggdmFyIHg9MDsgeDxiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdLmxlbmd0aDsgeCsrICkge1xuXG4gICAgICAgICAgICAgICAgLy9jcmVhdGUgc3R5bGUgZWxlbWVudHNcbiAgICAgICAgICAgICAgICB2YXIgbmV3U3R5bGVFbCA9ICQoJyNzdHlsZUVsVGVtcGxhdGUnKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuYXR0cignaWQnLCAnJyk7XG4gICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5maW5kKCcuY29udHJvbC1sYWJlbCcpLnRleHQoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0rXCI6XCIgKTtcblxuICAgICAgICAgICAgICAgIGlmKCB0aGVTZWxlY3RvciArIFwiIDogXCIgKyBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdIGluIGJDb25maWcuZWRpdGFibGVJdGVtT3B0aW9ucykgey8vd2UndmUgZ290IGEgZHJvcGRvd24gaW5zdGVhZCBvZiBvcGVuIHRleHQgaW5wdXRcblxuICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmZpbmQoJ2lucHV0JykucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIG5ld0Ryb3BEb3duID0gJCgnPHNlbGVjdCBjbGFzcz1cImZvcm0tY29udHJvbCBzZWxlY3Qgc2VsZWN0LXByaW1hcnkgYnRuLWJsb2NrIHNlbGVjdC1zbVwiPjwvc2VsZWN0PicpO1xuICAgICAgICAgICAgICAgICAgICBuZXdEcm9wRG93bi5hdHRyKCduYW1lJywgYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSk7XG5cblxuICAgICAgICAgICAgICAgICAgICBmb3IoIHZhciB6PTA7IHo8YkNvbmZpZy5lZGl0YWJsZUl0ZW1PcHRpb25zWyB0aGVTZWxlY3RvcitcIiA6IFwiK2JDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gXS5sZW5ndGg7IHorKyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld09wdGlvbiA9ICQoJzxvcHRpb24gdmFsdWU9XCInK2JDb25maWcuZWRpdGFibGVJdGVtT3B0aW9uc1t0aGVTZWxlY3RvcitcIiA6IFwiK2JDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF1dW3pdKydcIj4nK2JDb25maWcuZWRpdGFibGVJdGVtT3B0aW9uc1t0aGVTZWxlY3RvcitcIiA6IFwiK2JDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF1dW3pdKyc8L29wdGlvbj4nKTtcblxuXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggYkNvbmZpZy5lZGl0YWJsZUl0ZW1PcHRpb25zW3RoZVNlbGVjdG9yK1wiIDogXCIrYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XV1bel0gPT09ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2N1cnJlbnQgdmFsdWUsIG1hcmtlZCBhcyBzZWxlY3RlZFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld09wdGlvbi5hdHRyKCdzZWxlY3RlZCcsICd0cnVlJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3RHJvcERvd24uYXBwZW5kKCBuZXdPcHRpb24gKTtcblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5hcHBlbmQoIG5ld0Ryb3BEb3duICk7XG4gICAgICAgICAgICAgICAgICAgIG5ld0Ryb3BEb3duLnNlbGVjdDIoKTtcblxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5maW5kKCdpbnB1dCcpLnZhbCggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICkuYXR0cignbmFtZScsIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0pO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdID09PSAnYmFja2dyb3VuZC1pbWFnZScgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuZmluZCgnaW5wdXQnKS5iaW5kKCdmb2N1cycsIGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGhlSW5wdXQgPSAkKHRoaXMpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5pbWFnZSBidXR0b24udXNlSW1hZ2UnKS51bmJpbmQoJ2NsaWNrJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5vbignY2xpY2snLCAnLmltYWdlIGJ1dHRvbi51c2VJbWFnZScsIGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcygnYmFja2dyb3VuZC1pbWFnZScsICAndXJsKFwiJyskKHRoaXMpLmF0dHIoJ2RhdGEtdXJsJykrJ1wiKScpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vdXBkYXRlIGxpdmUgaW1hZ2VcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhlSW5wdXQudmFsKCAndXJsKFwiJyskKHRoaXMpLmF0dHIoJ2RhdGEtdXJsJykrJ1wiKScgKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hpZGUgbW9kYWxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwnKS5tb2RhbCgnaGlkZScpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XS5pbmRleE9mKFwiY29sb3JcIikgPiAtMSApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSAhPT0gJ3RyYW5zcGFyZW50JyAmJiAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgIT09ICdub25lJyAmJiAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgIT09ICcnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC52YWwoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSApO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuZmluZCgnaW5wdXQnKS5zcGVjdHJ1bSh7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcHJlZmVycmVkRm9ybWF0OiBcImhleFwiLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNob3dQYWxldHRlOiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFsbG93RW1wdHk6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2hvd0lucHV0OiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhbGV0dGU6IFtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiIzAwMFwiLFwiIzQ0NFwiLFwiIzY2NlwiLFwiIzk5OVwiLFwiI2NjY1wiLFwiI2VlZVwiLFwiI2YzZjNmM1wiLFwiI2ZmZlwiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiI2YwMFwiLFwiI2Y5MFwiLFwiI2ZmMFwiLFwiIzBmMFwiLFwiIzBmZlwiLFwiIzAwZlwiLFwiIzkwZlwiLFwiI2YwZlwiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiI2Y0Y2NjY1wiLFwiI2ZjZTVjZFwiLFwiI2ZmZjJjY1wiLFwiI2Q5ZWFkM1wiLFwiI2QwZTBlM1wiLFwiI2NmZTJmM1wiLFwiI2Q5ZDJlOVwiLFwiI2VhZDFkY1wiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiI2VhOTk5OVwiLFwiI2Y5Y2I5Y1wiLFwiI2ZmZTU5OVwiLFwiI2I2ZDdhOFwiLFwiI2EyYzRjOVwiLFwiIzlmYzVlOFwiLFwiI2I0YTdkNlwiLFwiI2Q1YTZiZFwiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiI2UwNjY2NlwiLFwiI2Y2YjI2YlwiLFwiI2ZmZDk2NlwiLFwiIzkzYzQ3ZFwiLFwiIzc2YTVhZlwiLFwiIzZmYThkY1wiLFwiIzhlN2NjM1wiLFwiI2MyN2JhMFwiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiI2MwMFwiLFwiI2U2OTEzOFwiLFwiI2YxYzIzMlwiLFwiIzZhYTg0ZlwiLFwiIzQ1ODE4ZVwiLFwiIzNkODVjNlwiLFwiIzY3NGVhN1wiLFwiI2E2NGQ3OVwiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiIzkwMFwiLFwiI2I0NWYwNlwiLFwiI2JmOTAwMFwiLFwiIzM4NzYxZFwiLFwiIzEzNGY1Y1wiLFwiIzBiNTM5NFwiLFwiIzM1MWM3NVwiLFwiIzc0MWI0N1wiXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgW1wiIzYwMFwiLFwiIzc4M2YwNFwiLFwiIzdmNjAwMFwiLFwiIzI3NGUxM1wiLFwiIzBjMzQzZFwiLFwiIzA3Mzc2M1wiLFwiIzIwMTI0ZFwiLFwiIzRjMTEzMFwiXVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIF1cbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuY3NzKCdkaXNwbGF5JywgJ2Jsb2NrJyk7XG5cbiAgICAgICAgICAgICAgICAkKCcjc3R5bGVFbGVtZW50cycpLmFwcGVuZCggbmV3U3R5bGVFbCApO1xuXG4gICAgICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yIGZvcm0jc3R5bGluZ0Zvcm0nKS5oZWlnaHQoJ2F1dG8nKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQXBwbGllcyB1cGRhdGVkIHN0eWxpbmcgdG8gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVTdHlsaW5nOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgdmFyIGVsZW1lbnRJRCxcbiAgICAgICAgICAgICAgICBsZW5ndGg7XG5cbiAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvciAjdGFiMSAuZm9ybS1ncm91cDpub3QoI3N0eWxlRWxUZW1wbGF0ZSkgaW5wdXQsICNzdHlsZUVkaXRvciAjdGFiMSAuZm9ybS1ncm91cDpub3QoI3N0eWxlRWxUZW1wbGF0ZSkgc2VsZWN0JykuZWFjaChmdW5jdGlvbigpe1xuXG5cdFx0XHRcdGlmKCAkKHRoaXMpLmF0dHIoJ25hbWUnKSAhPT0gdW5kZWZpbmVkICkge1xuXG4gICAgICAgICAgICAgICAgXHQkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCAkKHRoaXMpLmF0dHIoJ25hbWUnKSwgICQodGhpcykudmFsKCkpO1xuXG5cdFx0XHRcdH1cblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkuY3NzKCAkKHRoaXMpLmF0dHIoJ25hbWUnKSwgICQodGhpcykudmFsKCkgKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvL2xpbmtzXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkge1xuXG4gICAgICAgICAgICAgICAgLy9jaGFuZ2UgdGhlIGhyZWYgcHJvcD9cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuaHJlZiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzQ3VzdG9tJykudmFsdWU7XG5cbiAgICAgICAgICAgICAgICBsZW5ndGggPSBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2hpbGROb2Rlcy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9kb2VzIHRoZSBsaW5rIGNvbnRhaW4gYW4gaW1hZ2U/XG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmxpbmtJbWFnZSApIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jaGlsZE5vZGVzW2xlbmd0aC0xXS5ub2RlVmFsdWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGlua1RleHQnKS52YWx1ZTtcbiAgICAgICAgICAgICAgICBlbHNlIGlmICggc3R5bGVlZGl0b3IubGlua0ljb24gKSBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2hpbGROb2Rlc1tsZW5ndGgtMV0ubm9kZVZhbHVlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xpbmtUZXh0JykudmFsdWU7XG4gICAgICAgICAgICAgICAgZWxzZSBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuaW5uZXJUZXh0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xpbmtUZXh0JykudmFsdWU7XG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuXG4gICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLmF0dHIoJ2hyZWYnLCAkKCdpbnB1dCNpbnRlcm5hbExpbmtzQ3VzdG9tJykudmFsKCkpO1xuXG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7XG5cbiAgICAgICAgICAgICAgICAvL2NoYW5nZSB0aGUgaHJlZiBwcm9wP1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5wYXJlbnROb2RlLmhyZWYgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW50ZXJuYWxMaW5rc0N1c3RvbScpLnZhbHVlO1xuXG4gICAgICAgICAgICAgICAgbGVuZ3RoID0gc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNoaWxkTm9kZXMubGVuZ3RoO1xuICAgICAgICAgICAgICAgIFxuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5wYXJlbnQoKS5hdHRyKCdocmVmJywgJCgnaW5wdXQjaW50ZXJuYWxMaW5rc0N1c3RvbScpLnZhbCgpKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy9pY29uc1xuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5oYXNDbGFzcygnZmEnKSApIHtcblxuICAgICAgICAgICAgICAgIC8vb3V0IHdpdGggdGhlIG9sZCwgaW4gd2l0aCB0aGUgbmV3IDopXG4gICAgICAgICAgICAgICAgLy9nZXQgaWNvbiBjbGFzcyBuYW1lLCBzdGFydGluZyB3aXRoIGZhLVxuICAgICAgICAgICAgICAgIHZhciBnZXQgPSAkLmdyZXAoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNsYXNzTmFtZS5zcGxpdChcIiBcIiksIGZ1bmN0aW9uKHYsIGkpe1xuXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2LmluZGV4T2YoJ2ZhLScpID09PSAwO1xuXG4gICAgICAgICAgICAgICAgfSkuam9pbigpO1xuXG4gICAgICAgICAgICAgICAgLy9pZiB0aGUgaWNvbnMgaXMgYmVpbmcgY2hhbmdlZCwgc2F2ZSB0aGUgb2xkIG9uZSBzbyB3ZSBjYW4gcmVzZXQgaXQgaWYgbmVlZGVkXG5cbiAgICAgICAgICAgICAgICBpZiggZ2V0ICE9PSAkKCdzZWxlY3QjaWNvbnMnKS52YWwoKSApIHtcblxuICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkudW5pcXVlSWQoKTtcbiAgICAgICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuX29sZEljb25bJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyldID0gZ2V0O1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnJlbW92ZUNsYXNzKCBnZXQgKS5hZGRDbGFzcyggJCgnc2VsZWN0I2ljb25zJykudmFsKCkgKTtcblxuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkucmVtb3ZlQ2xhc3MoIGdldCApLmFkZENsYXNzKCAkKCdzZWxlY3QjaWNvbnMnKS52YWwoKSApO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL3ZpZGVvIFVSTFxuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdkYXRhLXR5cGUnKSA9PT0gJ3ZpZGVvJyApIHtcblxuICAgICAgICAgICAgICAgIGlmKCAkKCdpbnB1dCN5b3V0dWJlSUQnKS52YWwoKSAhPT0gJycgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByZXYoKS5hdHRyKCdzcmMnLCBcIi8vd3d3LnlvdXR1YmUuY29tL2VtYmVkL1wiKyQoJyN2aWRlb19UYWIgaW5wdXQjeW91dHViZUlEJykudmFsKCkpO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCAkKCdpbnB1dCN2aW1lb0lEJykudmFsKCkgIT09ICcnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcmV2KCkuYXR0cignc3JjJywgXCIvL3BsYXllci52aW1lby5jb20vdmlkZW8vXCIrJCgnI3ZpZGVvX1RhYiBpbnB1dCN2aW1lb0lEJykudmFsKCkrXCI/dGl0bGU9MCZhbXA7YnlsaW5lPTAmYW1wO3BvcnRyYWl0PTBcIik7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKCAkKCdpbnB1dCN5b3V0dWJlSUQnKS52YWwoKSAhPT0gJycgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLnByZXYoKS5hdHRyKCdzcmMnLCBcIi8vd3d3LnlvdXR1YmUuY29tL2VtYmVkL1wiKyQoJyN2aWRlb19UYWIgaW5wdXQjeW91dHViZUlEJykudmFsKCkpO1xuXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggJCgnaW5wdXQjdmltZW9JRCcpLnZhbCgpICE9PSAnJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkucHJldigpLmF0dHIoJ3NyYycsIFwiLy9wbGF5ZXIudmltZW8uY29tL3ZpZGVvL1wiKyQoJyN2aWRlb19UYWIgaW5wdXQjdmltZW9JRCcpLnZhbCgpK1wiP3RpdGxlPTAmYW1wO2J5bGluZT0wJmFtcDtwb3J0cmFpdD0wXCIpO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgJCgnI2RldGFpbHNBcHBsaWVkTWVzc2FnZScpLmZhZGVJbig2MDAsIGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7ICQoJyNkZXRhaWxzQXBwbGllZE1lc3NhZ2UnKS5mYWRlT3V0KDEwMDApOyB9LCAzMDAwKTtcblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIC8vYWRqdXN0IGZyYW1lIGhlaWdodFxuICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jay5oZWlnaHRBZGp1c3RtZW50KCk7XG5cblxuICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2ssICdjaGFuZ2UnKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIG9uIGZvY3VzLCB3ZSdsbCBtYWtlIHRoZSBpbnB1dCBmaWVsZHMgd2lkZXJcbiAgICAgICAgKi9cbiAgICAgICAgYW5pbWF0ZVN0eWxlSW5wdXRJbjogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQodGhpcykuY3NzKCdwb3NpdGlvbicsICdhYnNvbHV0ZScpO1xuICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ3JpZ2h0JywgJzBweCcpO1xuICAgICAgICAgICAgJCh0aGlzKS5hbmltYXRlKHsnd2lkdGgnOiAnMTAwJSd9LCA1MDApO1xuICAgICAgICAgICAgJCh0aGlzKS5mb2N1cyhmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHRoaXMuc2VsZWN0KCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIG9uIGJsdXIsIHdlJ2xsIHJldmVydCB0aGUgaW5wdXQgZmllbGRzIHRvIHRoZWlyIG9yaWdpbmFsIHNpemVcbiAgICAgICAgKi9cbiAgICAgICAgYW5pbWF0ZVN0eWxlSW5wdXRPdXQ6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKHRoaXMpLmFuaW1hdGUoeyd3aWR0aCc6ICc0MiUnfSwgNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdwb3NpdGlvbicsICdyZWxhdGl2ZScpO1xuICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdyaWdodCcsICdhdXRvJyk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGJ1aWxkcyB0aGUgZHJvcGRvd24gd2l0aCAjYmxvY2tzIG9uIHRoaXMgcGFnZVxuICAgICAgICAqL1xuICAgICAgICBidWlsZEJsb2Nrc0Ryb3Bkb3duOiBmdW5jdGlvbiAoY3VycmVudFZhbCkge1xuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkuc2VsZWN0MignZGVzdHJveScpO1xuXG4gICAgICAgICAgICBpZiggdHlwZW9mIGN1cnJlbnRWYWwgPT09ICd1bmRlZmluZWQnICkgY3VycmVudFZhbCA9IG51bGw7XG5cbiAgICAgICAgICAgIHZhciB4LFxuICAgICAgICAgICAgICAgIG5ld09wdGlvbjtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLmlubmVySFRNTCA9ICcnO1xuXG4gICAgICAgICAgICBuZXdPcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdPUFRJT04nKTtcbiAgICAgICAgICAgIG5ld09wdGlvbi5pbm5lclRleHQgPSBcIkNob29zZSBhIGJsb2NrXCI7XG4gICAgICAgICAgICBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCd2YWx1ZScsICcjJyk7XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwuYXBwZW5kQ2hpbGQobmV3T3B0aW9uKTtcblxuICAgICAgICAgICAgZm9yICggeCA9IDA7IHggPCBzaXRlQnVpbGRlci5zaXRlLmFjdGl2ZVBhZ2UuYmxvY2tzLmxlbmd0aDsgeCsrICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIGZyYW1lRG9jID0gc2l0ZUJ1aWxkZXIuc2l0ZS5hY3RpdmVQYWdlLmJsb2Nrc1t4XS5mcmFtZURvY3VtZW50O1xuICAgICAgICAgICAgICAgIHZhciBwYWdlQ29udGFpbmVyICA9IGZyYW1lRG9jLnF1ZXJ5U2VsZWN0b3IoYkNvbmZpZy5wYWdlQ29udGFpbmVyKTtcbiAgICAgICAgICAgICAgICB2YXIgdGhlSUQgPSBwYWdlQ29udGFpbmVyLmNoaWxkcmVuWzBdLmlkO1xuXG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uLmlubmVyVGV4dCA9ICcjJyArIHRoZUlEO1xuICAgICAgICAgICAgICAgIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgJyMnICsgdGhlSUQpO1xuICAgICAgICAgICAgICAgIGlmKCBjdXJyZW50VmFsID09PSAnIycgKyB0aGVJRCApIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3NlbGVjdGVkJywgdHJ1ZSk7XG5cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwuYXBwZW5kQ2hpbGQobmV3T3B0aW9uKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkuc2VsZWN0MigpO1xuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLnRyaWdnZXIoJ2NoYW5nZScpO1xuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkub2ZmKCdjaGFuZ2UnKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmlucHV0Q3VzdG9tTGluay52YWx1ZSA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzZXRQYWdlRHJvcGRvd24oKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgYmx1ciBldmVudCBoYW5kbGVyIGZvciB0aGUgY3VzdG9tIGxpbmsgaW5wdXRcbiAgICAgICAgKi9cbiAgICAgICAgaW5wdXRDdXN0b21MaW5rQmx1cjogZnVuY3Rpb24gKGUpIHtcblxuICAgICAgICAgICAgdmFyIHZhbHVlID0gZS50YXJnZXQudmFsdWUsXG4gICAgICAgICAgICAgICAgeDtcblxuICAgICAgICAgICAgLy9wYWdlcyBtYXRjaD9cbiAgICAgICAgICAgIGZvciAoIHggPSAwOyB4IDwgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5xdWVyeVNlbGVjdG9yQWxsKCdvcHRpb24nKS5sZW5ndGg7IHgrKyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggdmFsdWUgPT09IHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMucXVlcnlTZWxlY3RvckFsbCgnb3B0aW9uJylbeF0udmFsdWUgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5zZWxlY3RlZEluZGV4ID0geDtcbiAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS50cmlnZ2VyKCdjaGFuZ2UnKS5zZWxlY3QyKCk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy9ibG9ja3MgbWF0Y2g/XG4gICAgICAgICAgICBmb3IgKCB4ID0gMDsgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLnF1ZXJ5U2VsZWN0b3JBbGwoJ29wdGlvbicpLmxlbmd0aDsgeCsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLnF1ZXJ5U2VsZWN0b3JBbGwoJ29wdGlvbicpW3hdLnZhbHVlICkge1xuXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5zZWxlY3RlZEluZGV4ID0geDtcbiAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLnRyaWdnZXIoJ2NoYW5nZScpLnNlbGVjdDIoKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgZm9jdXMgZXZlbnQgaGFuZGxlciBmb3IgdGhlIGN1c3RvbSBsaW5rIGlucHV0XG4gICAgICAgICovXG4gICAgICAgIGlucHV0Q3VzdG9tTGlua0ZvY3VzOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc2V0UGFnZURyb3Bkb3duKCk7XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNldEJsb2NrRHJvcGRvd24oKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGJ1aWxkcyB0aGUgZHJvcGRvd24gd2l0aCBwYWdlcyB0byBsaW5rIHRvXG4gICAgICAgICovXG4gICAgICAgIGJ1aWxkUGFnZXNEcm9wZG93bjogZnVuY3Rpb24gKGN1cnJlbnRWYWwpIHtcblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS5zZWxlY3QyKCdkZXN0cm95Jyk7XG5cbiAgICAgICAgICAgIGlmKCB0eXBlb2YgY3VycmVudFZhbCA9PT0gJ3VuZGVmaW5lZCcgKSBjdXJyZW50VmFsID0gbnVsbDtcblxuICAgICAgICAgICAgdmFyIHgsXG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uO1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLmlubmVySFRNTCA9ICcnO1xuXG4gICAgICAgICAgICBuZXdPcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdPUFRJT04nKTtcbiAgICAgICAgICAgIG5ld09wdGlvbi5pbm5lclRleHQgPSBcIkNob29zZSBhIHBhZ2VcIjtcbiAgICAgICAgICAgIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgJyMnKTtcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMuYXBwZW5kQ2hpbGQobmV3T3B0aW9uKTtcblxuICAgICAgICAgICAgZm9yKCB4ID0gMDsgeCA8IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzLmxlbmd0aDsgeCsrICkge1xuXG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uLmlubmVyVGV4dCA9IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW3hdLm5hbWU7XG4gICAgICAgICAgICAgICAgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgndmFsdWUnLCBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1t4XS5uYW1lICsgJy5odG1sJyk7XG4gICAgICAgICAgICAgICAgaWYoIGN1cnJlbnRWYWwgPT09IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzW3hdLm5hbWUgKyAnLmh0bWwnKSBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCdzZWxlY3RlZCcsIHRydWUpO1xuXG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5hcHBlbmRDaGlsZChuZXdPcHRpb24pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykuc2VsZWN0MigpO1xuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS50cmlnZ2VyKCdjaGFuZ2UnKTtcblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS5vZmYoJ2NoYW5nZScpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuaW5wdXRDdXN0b21MaW5rLnZhbHVlID0gdGhpcy52YWx1ZTtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNldEJsb2NrRHJvcGRvd24oKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVzZXQgdGhlIGJsb2NrIGxpbmsgZHJvcGRvd25cbiAgICAgICAgKi9cbiAgICAgICAgcmVzZXRCbG9ja0Ryb3Bkb3duOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5zZWxlY3RlZEluZGV4ID0gMDtcbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS5zZWxlY3QyKCdkZXN0cm95Jykuc2VsZWN0MigpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVzZXQgdGhlIHBhZ2UgbGluayBkcm9wZG93blxuICAgICAgICAqL1xuICAgICAgICByZXNldFBhZ2VEcm9wZG93bjogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLnNlbGVjdGVkSW5kZXggPSAwO1xuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzKS5zZWxlY3QyKCdkZXN0cm95Jykuc2VsZWN0MigpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgd2hlbiB0aGUgY2xpY2tlZCBlbGVtZW50IGlzIGFuIGFuY2hvciB0YWcgKG9yIGhhcyBhIHBhcmVudCBhbmNob3IgdGFnKVxuICAgICAgICAqL1xuICAgICAgICBlZGl0TGluazogZnVuY3Rpb24oZWwpIHtcblxuICAgICAgICAgICAgdmFyIHRoZUhyZWY7XG5cbiAgICAgICAgICAgICQoJ2EjbGlua19MaW5rJykucGFyZW50KCkuc2hvdygpO1xuXG4gICAgICAgICAgICAvL3NldCB0aGVIcmVmXG4gICAgICAgICAgICBpZiggJChlbCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7XG5cbiAgICAgICAgICAgICAgICB0aGVIcmVmID0gJChlbCkuYXR0cignaHJlZicpO1xuXG4gICAgICAgICAgICB9IGVsc2UgaWYoICQoZWwpLnBhcmVudCgpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkge1xuXG4gICAgICAgICAgICAgICAgdGhlSHJlZiA9ICQoZWwpLnBhcmVudCgpLmF0dHIoJ2hyZWYnKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5idWlsZFBhZ2VzRHJvcGRvd24odGhlSHJlZik7XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5idWlsZEJsb2Nrc0Ryb3Bkb3duKHRoZUhyZWYpO1xuICAgICAgICAgICAgc3R5bGVlZGl0b3IuaW5wdXRDdXN0b21MaW5rLnZhbHVlID0gdGhlSHJlZjtcblxuICAgICAgICAgICAgLy9ncmFiIGFuIGltYWdlP1xuICAgICAgICAgICAgaWYgKCBlbC5xdWVyeVNlbGVjdG9yKCdpbWcnKSApIHN0eWxlZWRpdG9yLmxpbmtJbWFnZSA9IGVsLnF1ZXJ5U2VsZWN0b3IoJ2ltZycpO1xuICAgICAgICAgICAgZWxzZSBzdHlsZWVkaXRvci5saW5rSW1hZ2UgPSBudWxsO1xuXG4gICAgICAgICAgICAvL2dyYWIgYW4gaWNvbj9cbiAgICAgICAgICAgIGlmICggZWwucXVlcnlTZWxlY3RvcignLmZhJykgKSBzdHlsZWVkaXRvci5saW5rSWNvbiA9IGVsLnF1ZXJ5U2VsZWN0b3IoJy5mYScpLmNsb25lTm9kZSh0cnVlKTtcbiAgICAgICAgICAgIGVsc2Ugc3R5bGVlZGl0b3IubGlua0ljb24gPSBudWxsO1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5pbnB1dExpbmtUZXh0LnZhbHVlID0gZWwuaW5uZXJUZXh0O1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgd2hlbiB0aGUgY2xpY2tlZCBlbGVtZW50IGlzIGFuIGltYWdlXG4gICAgICAgICovXG4gICAgICAgIGVkaXRJbWFnZTogZnVuY3Rpb24oZWwpIHtcblxuICAgICAgICAgICAgJCgnYSNpbWdfTGluaycpLnBhcmVudCgpLnNob3coKTtcblxuICAgICAgICAgICAgLy9zZXQgdGhlIGN1cnJlbnQgU1JDXG4gICAgICAgICAgICAkKCcuaW1hZ2VGaWxlVGFiJykuZmluZCgnaW5wdXQjaW1hZ2VVUkwnKS52YWwoICQoZWwpLmF0dHIoJ3NyYycpICk7XG5cbiAgICAgICAgICAgIC8vcmVzZXQgdGhlIGZpbGUgdXBsb2FkXG4gICAgICAgICAgICAkKCcuaW1hZ2VGaWxlVGFiJykuZmluZCgnYS5maWxlaW5wdXQtZXhpc3RzJykuY2xpY2soKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHdoZW4gdGhlIGNsaWNrZWQgZWxlbWVudCBpcyBhIHZpZGVvIGVsZW1lbnRcbiAgICAgICAgKi9cbiAgICAgICAgZWRpdFZpZGVvOiBmdW5jdGlvbihlbCkge1xuXG4gICAgICAgICAgICB2YXIgbWF0Y2hSZXN1bHRzO1xuXG4gICAgICAgICAgICAkKCdhI3ZpZGVvX0xpbmsnKS5wYXJlbnQoKS5zaG93KCk7XG4gICAgICAgICAgICAkKCdhI3ZpZGVvX0xpbmsnKS5jbGljaygpO1xuXG4gICAgICAgICAgICAvL2luamVjdCBjdXJyZW50IHZpZGVvIElELGNoZWNrIGlmIHdlJ3JlIGRlYWxpbmcgd2l0aCBZb3V0dWJlIG9yIFZpbWVvXG5cbiAgICAgICAgICAgIGlmKCAkKGVsKS5wcmV2KCkuYXR0cignc3JjJykuaW5kZXhPZihcInZpbWVvLmNvbVwiKSA+IC0xICkgey8vdmltZW9cblxuICAgICAgICAgICAgICAgIG1hdGNoUmVzdWx0cyA9ICQoZWwpLnByZXYoKS5hdHRyKCdzcmMnKS5tYXRjaCgvcGxheWVyXFwudmltZW9cXC5jb21cXC92aWRlb1xcLyhbMC05XSopLyk7XG5cbiAgICAgICAgICAgICAgICAkKCcjdmlkZW9fVGFiIGlucHV0I3ZpbWVvSUQnKS52YWwoIG1hdGNoUmVzdWx0c1ttYXRjaFJlc3VsdHMubGVuZ3RoLTFdICk7XG4gICAgICAgICAgICAgICAgJCgnI3ZpZGVvX1RhYiBpbnB1dCN5b3V0dWJlSUQnKS52YWwoJycpO1xuXG4gICAgICAgICAgICB9IGVsc2Ugey8veW91dHViZVxuXG4gICAgICAgICAgICAgICAgLy90ZW1wID0gJChlbCkucHJldigpLmF0dHIoJ3NyYycpLnNwbGl0KCcvJyk7XG4gICAgICAgICAgICAgICAgdmFyIHJlZ0V4cCA9IC8uKig/OnlvdXR1LmJlXFwvfHZcXC98dVxcL1xcd1xcL3xlbWJlZFxcL3x3YXRjaFxcP3Y9KShbXiNcXCZcXD9dKikuKi87XG4gICAgICAgICAgICAgICAgbWF0Y2hSZXN1bHRzID0gJChlbCkucHJldigpLmF0dHIoJ3NyYycpLm1hdGNoKHJlZ0V4cCk7XG5cbiAgICAgICAgICAgICAgICAkKCcjdmlkZW9fVGFiIGlucHV0I3lvdXR1YmVJRCcpLnZhbCggbWF0Y2hSZXN1bHRzWzFdICk7XG4gICAgICAgICAgICAgICAgJCgnI3ZpZGVvX1RhYiBpbnB1dCN2aW1lb0lEJykudmFsKCcnKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgd2hlbiB0aGUgY2xpY2tlZCBlbGVtZW50IGlzIGFuIGZhIGljb25cbiAgICAgICAgKi9cbiAgICAgICAgZWRpdEljb246IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKCdhI2ljb25fTGluaycpLnBhcmVudCgpLnNob3coKTtcblxuICAgICAgICAgICAgLy9nZXQgaWNvbiBjbGFzcyBuYW1lLCBzdGFydGluZyB3aXRoIGZhLVxuICAgICAgICAgICAgdmFyIGdldCA9ICQuZ3JlcCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jbGFzc05hbWUuc3BsaXQoXCIgXCIpLCBmdW5jdGlvbih2LCBpKXtcblxuICAgICAgICAgICAgICAgIHJldHVybiB2LmluZGV4T2YoJ2ZhLScpID09PSAwO1xuXG4gICAgICAgICAgICB9KS5qb2luKCk7XG5cbiAgICAgICAgICAgICQoJ3NlbGVjdCNpY29ucyBvcHRpb24nKS5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICBpZiggJCh0aGlzKS52YWwoKSA9PT0gZ2V0ICkge1xuXG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuYXR0cignc2VsZWN0ZWQnLCB0cnVlKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjaWNvbnMnKS50cmlnZ2VyKCdjaG9zZW46dXBkYXRlZCcpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZSBzZWxlY3RlZCBlbGVtZW50XG4gICAgICAgICovXG4gICAgICAgIGRlbGV0ZUVsZW1lbnQ6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CZWZvcmVEZWxldGUnKTtcblxuICAgICAgICAgICAgdmFyIHRvRGVsO1xuXG4gICAgICAgICAgICAvL2RldGVybWluZSB3aGF0IHRvIGRlbGV0ZVxuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHsvL2FuY29yXG5cbiAgICAgICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLnByb3AoJ3RhZ05hbWUnKSA9PT0nTEknICkgey8vY2xvbmUgdGhlIExJXG5cbiAgICAgICAgICAgICAgICAgICAgdG9EZWwgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCk7XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgICAgIHRvRGVsID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9IGVsc2UgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgPT09ICdJTUcnICkgey8vaW1hZ2VcblxuICAgICAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7Ly9jbG9uZSB0aGUgQVxuXG4gICAgICAgICAgICAgICAgICAgIHRvRGVsID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICB0b0RlbCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfSBlbHNlIHsvL2V2ZXJ5dGhpbmcgZWxzZVxuXG4gICAgICAgICAgICAgICAgdG9EZWwgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICB0b0RlbC5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgIHZhciByYW5kb21FbCA9ICQodGhpcykuY2xvc2VzdCgnYm9keScpLmZpbmQoJyo6Zmlyc3QnKTtcblxuICAgICAgICAgICAgICAgIHRvRGVsLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgdmFyIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuXG4gICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jay5oZWlnaHRBZGp1c3RtZW50KCk7XG5cbiAgICAgICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgJCgnI2RlbGV0ZUVsZW1lbnQnKS5tb2RhbCgnaGlkZScpO1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5jbG9zZVN0eWxlRWRpdG9yKCk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jaywgJ2NoYW5nZScpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgY2xvbmVzIHRoZSBzZWxlY3RlZCBlbGVtZW50XG4gICAgICAgICovXG4gICAgICAgIGNsb25lRWxlbWVudDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJlZm9yZUNsb25lJyk7XG5cbiAgICAgICAgICAgIHZhciB0aGVDbG9uZSwgdGhlQ2xvbmUyLCB0aGVPbmUsIGNsb25lZCwgY2xvbmVQYXJlbnQsIGVsZW1lbnRJRDtcblxuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5oYXNDbGFzcygncHJvcENsb25lJykgKSB7Ly9jbG9uZSB0aGUgcGFyZW50IGVsZW1lbnRcblxuICAgICAgICAgICAgICAgIHRoZUNsb25lID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUuZmluZCggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSApLmF0dHIoJ3N0eWxlJywgJycpO1xuXG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUyID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUyLmZpbmQoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgKS5hdHRyKCdzdHlsZScsICcnKTtcblxuICAgICAgICAgICAgICAgIHRoZU9uZSA9IHRoZUNsb25lLmZpbmQoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgKTtcbiAgICAgICAgICAgICAgICBjbG9uZWQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCk7XG5cbiAgICAgICAgICAgICAgICBjbG9uZVBhcmVudCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5wYXJlbnQoKTtcblxuICAgICAgICAgICAgfSBlbHNlIHsvL2Nsb25lIHRoZSBlbGVtZW50IGl0c2VsZlxuXG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY2xvbmUoKTtcblxuICAgICAgICAgICAgICAgIHRoZUNsb25lLmF0dHIoJ3N0eWxlJywgJycpO1xuXG4gICAgICAgICAgICAgICAgLyppZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICB0aGVDbG9uZS5hdHRyKCdpZCcsICcnKS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgICAgIH0qL1xuXG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUyID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNsb25lKCk7XG4gICAgICAgICAgICAgICAgdGhlQ2xvbmUyLmF0dHIoJ3N0eWxlJywgJycpO1xuXG4gICAgICAgICAgICAgICAgLypcbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICB0aGVDbG9uZTIuYXR0cignaWQnLCB0aGVDbG9uZS5hdHRyKCdpZCcpKTtcbiAgICAgICAgICAgICAgICB9Ki9cblxuICAgICAgICAgICAgICAgIHRoZU9uZSA9IHRoZUNsb25lO1xuICAgICAgICAgICAgICAgIGNsb25lZCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgICAgIGNsb25lUGFyZW50ID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNsb25lZC5hZnRlciggdGhlQ2xvbmUgKTtcblxuICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG4gICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkuYWZ0ZXIoIHRoZUNsb25lMiApO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIC8vbWFrZSBzdXJlIHRoZSBuZXcgZWxlbWVudCBnZXRzIHRoZSBwcm9wZXIgZXZlbnRzIHNldCBvbiBpdFxuICAgICAgICAgICAgdmFyIG5ld0VsZW1lbnQgPSBuZXcgY2FudmFzRWxlbWVudCh0aGVPbmUuZ2V0KDApKTtcbiAgICAgICAgICAgIG5ld0VsZW1lbnQuYWN0aXZhdGUoKTtcblxuICAgICAgICAgICAgLy9wb3NzaWJsZSBoZWlnaHQgYWRqdXN0bWVudHNcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2suaGVpZ2h0QWRqdXN0bWVudCgpO1xuXG4gICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jaywgJ2NoYW5nZScpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVzZXRzIHRoZSBhY3RpdmUgZWxlbWVudFxuICAgICAgICAqL1xuICAgICAgICByZXNldEVsZW1lbnQ6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNsb3Nlc3QoJ2JvZHknKS53aWR0aCgpICE9PSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkud2lkdGgoKSApIHtcblxuICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdzdHlsZScsICcnKS5jc3MoeydvdXRsaW5lJzogJzNweCBkYXNoZWQgcmVkJywgJ2N1cnNvcic6ICdwb2ludGVyJ30pO1xuXG4gICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ3N0eWxlJywgJycpLmNzcyh7J291dGxpbmUnOiAnM3B4IGRhc2hlZCByZWQnLCAnb3V0bGluZS1vZmZzZXQnOictM3B4JywgJ2N1cnNvcic6ICdwb2ludGVyJ30pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgIHZhciBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcbiAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5hdHRyKCdzdHlsZScsICcnKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3IgZm9ybSNzdHlsaW5nRm9ybScpLmhlaWdodCggJCgnI3N0eWxlRWRpdG9yIGZvcm0jc3R5bGluZ0Zvcm0nKS5oZWlnaHQoKStcInB4XCIgKTtcblxuICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yIGZvcm0jc3R5bGluZ0Zvcm0gLmZvcm0tZ3JvdXA6bm90KCNzdHlsZUVsVGVtcGxhdGUpJykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICB9KTtcblxuXG4gICAgICAgICAgICAvL3Jlc2V0IGljb25cblxuICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLl9vbGRJY29uWyQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpXSAhPT0gbnVsbCApIHtcblxuICAgICAgICAgICAgICAgIHZhciBnZXQgPSAkLmdyZXAoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNsYXNzTmFtZS5zcGxpdChcIiBcIiksIGZ1bmN0aW9uKHYsIGkpe1xuXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2LmluZGV4T2YoJ2ZhLScpID09PSAwO1xuXG4gICAgICAgICAgICAgICAgfSkuam9pbigpO1xuXG4gICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnJlbW92ZUNsYXNzKCBnZXQgKS5hZGRDbGFzcyggc3R5bGVlZGl0b3IuX29sZEljb25bJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyldICk7XG5cbiAgICAgICAgICAgICAgICAkKCdzZWxlY3QjaWNvbnMgb3B0aW9uJykuZWFjaChmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLnZhbCgpID09PSBzdHlsZWVkaXRvci5fb2xkSWNvblskKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKV0gKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuYXR0cignc2VsZWN0ZWQnLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpY29ucycpLnRyaWdnZXIoJ2Nob3Nlbjp1cGRhdGVkJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgc2V0VGltZW91dCggZnVuY3Rpb24oKXtzdHlsZWVkaXRvci5idWlsZGVTdHlsZUVsZW1lbnRzKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignZGF0YS1zZWxlY3RvcicpICk7fSwgNTUwKTtcblxuICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLCAnY2hhbmdlJyk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIHJlc2V0U2VsZWN0TGlua3NQYWdlczogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQoJyNpbnRlcm5hbExpbmtzRHJvcGRvd24nKS5zZWxlY3QyKCd2YWwnLCAnIycpO1xuXG4gICAgICAgIH0sXG5cbiAgICAgICAgcmVzZXRTZWxlY3RMaW5rc0ludGVybmFsOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCgnI3BhZ2VMaW5rc0Ryb3Bkb3duJykuc2VsZWN0MigndmFsJywgJyMnKTtcblxuICAgICAgICB9LFxuXG4gICAgICAgIHJlc2V0U2VsZWN0QWxsTGlua3M6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKCcjaW50ZXJuYWxMaW5rc0Ryb3Bkb3duJykuc2VsZWN0MigndmFsJywgJyMnKTtcbiAgICAgICAgICAgICQoJyNwYWdlTGlua3NEcm9wZG93bicpLnNlbGVjdDIoJ3ZhbCcsICcjJyk7XG4gICAgICAgICAgICB0aGlzLnNlbGVjdCgpO1xuXG4gICAgICAgIH0sXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGhpZGVzIGZpbGUgdXBsb2FkIGZvcm1zXG4gICAgICAgICovXG4gICAgICAgIGhpZGVGaWxlVXBsb2FkczogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQoJ2Zvcm0jaW1hZ2VVcGxvYWRGb3JtJykuaGlkZSgpO1xuICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgI3VwbG9hZFRhYkxJJykuaGlkZSgpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgY2xvc2VzIHRoZSBzdHlsZSBlZGl0b3JcbiAgICAgICAgKi9cbiAgICAgICAgY2xvc2VTdHlsZUVkaXRvcjogZnVuY3Rpb24gKGUpIHtcblxuICAgICAgICAgICAgaWYgKCBlICE9PSB1bmRlZmluZWQgKSBlLnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgICAgIGlmICggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lZGl0YWJsZUF0dHJpYnV0ZXMgJiYgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lZGl0YWJsZUF0dHJpYnV0ZXMuaW5kZXhPZignY29udGVudCcpID09PSAtMSApIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnJlbW92ZU91dGxpbmUoKTtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmFjdGl2YXRlKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmKCAkKCcjc3R5bGVFZGl0b3InKS5jc3MoJ2xlZnQnKSA9PT0gJzBweCcgKSB7XG5cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci50b2dnbGVTaWRlUGFuZWwoJ2Nsb3NlJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHRvZ2dsZXMgdGhlIHNpZGUgcGFuZWxcbiAgICAgICAgKi9cbiAgICAgICAgdG9nZ2xlU2lkZVBhbmVsOiBmdW5jdGlvbih2YWwpIHtcblxuICAgICAgICAgICAgaWYoIHZhbCA9PT0gJ29wZW4nICYmICQoJyNzdHlsZUVkaXRvcicpLmNzcygnbGVmdCcpID09PSAnLTMwMHB4JyApIHtcbiAgICAgICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3InKS5hbmltYXRlKHsnbGVmdCc6ICcwcHgnfSwgMjUwKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZiggdmFsID09PSAnY2xvc2UnICYmICQoJyNzdHlsZUVkaXRvcicpLmNzcygnbGVmdCcpID09PSAnMHB4JyApIHtcbiAgICAgICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3InKS5hbmltYXRlKHsnbGVmdCc6ICctMzAwcHgnfSwgMjUwKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG4gICAgfTtcblxuICAgIHN0eWxlZWRpdG9yLmluaXQoKTtcblxuICAgIGV4cG9ydHMuc3R5bGVlZGl0b3IgPSBzdHlsZWVkaXRvcjtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuXG4vKiBnbG9iYWxzIHNpdGVVcmw6ZmFsc2UsIGJhc2VVcmw6ZmFsc2UgKi9cbiAgICBcInVzZSBzdHJpY3RcIjtcbiAgICAgICAgXG4gICAgdmFyIGFwcFVJID0ge1xuICAgICAgICBcbiAgICAgICAgZmlyc3RNZW51V2lkdGg6IDE5MCxcbiAgICAgICAgc2Vjb25kTWVudVdpZHRoOiAzMDAsXG4gICAgICAgIGxvYWRlckFuaW1hdGlvbjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xvYWRlcicpLFxuICAgICAgICBzZWNvbmRNZW51VHJpZ2dlckNvbnRhaW5lcnM6ICQoJyNtZW51ICNtYWluICNlbGVtZW50Q2F0cywgI21lbnUgI21haW4gI3RlbXBsYXRlc1VsJyksXG4gICAgICAgIHNpdGVVcmw6IHNpdGVVcmwsXG4gICAgICAgIGJhc2VVcmw6IGJhc2VVcmwsXG4gICAgICAgIFxuICAgICAgICBzZXR1cDogZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gRmFkZSB0aGUgbG9hZGVyIGFuaW1hdGlvblxuICAgICAgICAgICAgJChhcHBVSS5sb2FkZXJBbmltYXRpb24pLmZhZGVPdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKCcjbWVudScpLmFuaW1hdGUoeydsZWZ0JzogLWFwcFVJLmZpcnN0TWVudVdpZHRofSwgMTAwMCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVGFic1xuICAgICAgICAgICAgJChcIi5uYXYtdGFicyBhXCIpLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICQodGhpcykudGFiKFwic2hvd1wiKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKFwic2VsZWN0LnNlbGVjdFwiKS5zZWxlY3QyKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoJzpyYWRpbywgOmNoZWNrYm94JykucmFkaW9jaGVjaygpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBUb29sdGlwc1xuICAgICAgICAgICAgJChcIltkYXRhLXRvZ2dsZT10b29sdGlwXVwiKS50b29sdGlwKFwiaGlkZVwiKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVGFibGU6IFRvZ2dsZSBhbGwgY2hlY2tib3hlc1xuICAgICAgICAgICAgJCgnLnRhYmxlIC50b2dnbGUtYWxsIDpjaGVja2JveCcpLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgIHZhciBjaCA9ICR0aGlzLnByb3AoJ2NoZWNrZWQnKTtcbiAgICAgICAgICAgICAgICAkdGhpcy5jbG9zZXN0KCcudGFibGUnKS5maW5kKCd0Ym9keSA6Y2hlY2tib3gnKS5yYWRpb2NoZWNrKCFjaCA/ICd1bmNoZWNrJyA6ICdjaGVjaycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIEFkZCBzdHlsZSBjbGFzcyBuYW1lIHRvIGEgdG9vbHRpcHNcbiAgICAgICAgICAgICQoXCIudG9vbHRpcFwiKS5hZGRDbGFzcyhmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBpZiAoJCh0aGlzKS5wcmV2KCkuYXR0cihcImRhdGEtdG9vbHRpcC1zdHlsZVwiKSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gXCJ0b29sdGlwLVwiICsgJCh0aGlzKS5wcmV2KCkuYXR0cihcImRhdGEtdG9vbHRpcC1zdHlsZVwiKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJChcIi5idG4tZ3JvdXBcIikub24oJ2NsaWNrJywgXCJhXCIsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuc2libGluZ3MoKS5yZW1vdmVDbGFzcyhcImFjdGl2ZVwiKS5lbmQoKS5hZGRDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBGb2N1cyBzdGF0ZSBmb3IgYXBwZW5kL3ByZXBlbmQgaW5wdXRzXG4gICAgICAgICAgICAkKCcuaW5wdXQtZ3JvdXAnKS5vbignZm9jdXMnLCAnLmZvcm0tY29udHJvbCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNsb3Nlc3QoJy5pbnB1dC1ncm91cCwgLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnZm9jdXMnKTtcbiAgICAgICAgICAgIH0pLm9uKCdibHVyJywgJy5mb3JtLWNvbnRyb2wnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jbG9zZXN0KCcuaW5wdXQtZ3JvdXAsIC5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2ZvY3VzJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVGFibGU6IFRvZ2dsZSBhbGwgY2hlY2tib3hlc1xuICAgICAgICAgICAgJCgnLnRhYmxlIC50b2dnbGUtYWxsJykub24oJ2NsaWNrJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNoID0gJCh0aGlzKS5maW5kKCc6Y2hlY2tib3gnKS5wcm9wKCdjaGVja2VkJyk7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jbG9zZXN0KCcudGFibGUnKS5maW5kKCd0Ym9keSA6Y2hlY2tib3gnKS5jaGVja2JveCghY2ggPyAnY2hlY2snIDogJ3VuY2hlY2snKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBUYWJsZTogQWRkIGNsYXNzIHJvdyBzZWxlY3RlZFxuICAgICAgICAgICAgJCgnLnRhYmxlIHRib2R5IDpjaGVja2JveCcpLm9uKCdjaGVjayB1bmNoZWNrIHRvZ2dsZScsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKVxuICAgICAgICAgICAgICAgICwgY2hlY2sgPSAkdGhpcy5wcm9wKCdjaGVja2VkJylcbiAgICAgICAgICAgICAgICAsIHRvZ2dsZSA9IGUudHlwZSA9PT0gJ3RvZ2dsZSdcbiAgICAgICAgICAgICAgICAsIGNoZWNrYm94ZXMgPSAkKCcudGFibGUgdGJvZHkgOmNoZWNrYm94JylcbiAgICAgICAgICAgICAgICAsIGNoZWNrQWxsID0gY2hlY2tib3hlcy5sZW5ndGggPT09IGNoZWNrYm94ZXMuZmlsdGVyKCc6Y2hlY2tlZCcpLmxlbmd0aDtcblxuICAgICAgICAgICAgICAgICR0aGlzLmNsb3Nlc3QoJ3RyJylbY2hlY2sgPyAnYWRkQ2xhc3MnIDogJ3JlbW92ZUNsYXNzJ10oJ3NlbGVjdGVkLXJvdycpO1xuICAgICAgICAgICAgICAgIGlmICh0b2dnbGUpICR0aGlzLmNsb3Nlc3QoJy50YWJsZScpLmZpbmQoJy50b2dnbGUtYWxsIDpjaGVja2JveCcpLmNoZWNrYm94KGNoZWNrQWxsID8gJ2NoZWNrJyA6ICd1bmNoZWNrJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gU3dpdGNoXG4gICAgICAgICAgICAkKFwiW2RhdGEtdG9nZ2xlPSdzd2l0Y2gnXVwiKS53cmFwKCc8ZGl2IGNsYXNzPVwic3dpdGNoXCIgLz4nKS5wYXJlbnQoKS5ib290c3RyYXBTd2l0Y2goKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgYXBwVUkuc2Vjb25kTWVudVRyaWdnZXJDb250YWluZXJzLm9uKCdjbGljaycsICdhOm5vdCguYnRuKScsIGFwcFVJLnNlY29uZE1lbnVBbmltYXRpb24pO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBzZWNvbmRNZW51QW5pbWF0aW9uOiBmdW5jdGlvbigpe1xuICAgICAgICBcbiAgICAgICAgICAgICQoJyNtZW51ICNtYWluIGEnKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdhY3RpdmUnKTtcblx0XG4gICAgICAgICAgICAvL3Nob3cgb25seSB0aGUgcmlnaHQgZWxlbWVudHNcbiAgICAgICAgICAgICQoJyNtZW51ICNzZWNvbmQgdWwgbGknKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kIHVsIGxpLicrJCh0aGlzKS5hdHRyKCdpZCcpKS5zaG93KCk7XG5cbiAgICAgICAgICAgIGlmKCAkKHRoaXMpLmF0dHIoJ2lkJykgPT09ICdhbGwnICkge1xuICAgICAgICAgICAgICAgICQoJyNtZW51ICNzZWNvbmQgdWwjZWxlbWVudHMgbGknKS5zaG93KCk7XHRcdFxuICAgICAgICAgICAgfVxuXHRcbiAgICAgICAgICAgICQoJy5tZW51IC5zZWNvbmQnKS5jc3MoJ2Rpc3BsYXknLCAnYmxvY2snKS5zdG9wKCkuYW5pbWF0ZSh7XG4gICAgICAgICAgICAgICAgd2lkdGg6IGFwcFVJLnNlY29uZE1lbnVXaWR0aFxuICAgICAgICAgICAgfSwgNTAwKTtcdFxuICAgICAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgICAgIFxuICAgIH07XG4gICAgXG4gICAgLy9pbml0aWF0ZSB0aGUgVUlcbiAgICBhcHBVSS5zZXR1cCgpO1xuXG5cbiAgICAvLyoqKiogRVhQT1JUU1xuICAgIG1vZHVsZS5leHBvcnRzLmFwcFVJID0gYXBwVUk7XG4gICAgXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG4gICAgXCJ1c2Ugc3RyaWN0XCI7XG4gICAgXG4gICAgZXhwb3J0cy5nZXRSYW5kb21BcmJpdHJhcnkgPSBmdW5jdGlvbihtaW4sIG1heCkge1xuICAgICAgICByZXR1cm4gTWF0aC5mbG9vcihNYXRoLnJhbmRvbSgpICogKG1heCAtIG1pbikgKyBtaW4pO1xuICAgIH07XG5cbiAgICBleHBvcnRzLmdldFBhcmFtZXRlckJ5TmFtZSA9IGZ1bmN0aW9uIChuYW1lLCB1cmwpIHtcblxuICAgICAgICBpZiAoIXVybCkgdXJsID0gd2luZG93LmxvY2F0aW9uLmhyZWY7XG4gICAgICAgIG5hbWUgPSBuYW1lLnJlcGxhY2UoL1tcXFtcXF1dL2csIFwiXFxcXCQmXCIpO1xuICAgICAgICB2YXIgcmVnZXggPSBuZXcgUmVnRXhwKFwiWz8mXVwiICsgbmFtZSArIFwiKD0oW14mI10qKXwmfCN8JClcIiksXG4gICAgICAgICAgICByZXN1bHRzID0gcmVnZXguZXhlYyh1cmwpO1xuICAgICAgICBpZiAoIXJlc3VsdHMpIHJldHVybiBudWxsO1xuICAgICAgICBpZiAoIXJlc3VsdHNbMl0pIHJldHVybiAnJztcbiAgICAgICAgcmV0dXJuIGRlY29kZVVSSUNvbXBvbmVudChyZXN1bHRzWzJdLnJlcGxhY2UoL1xcKy9nLCBcIiBcIikpO1xuICAgICAgICBcbiAgICB9O1xuICAgIFxufSgpKTsiLCIvKiFcbiAqIHB1Ymxpc2hlci5qcyAtIChjKSBSeWFuIEZsb3JlbmNlIDIwMTFcbiAqIGdpdGh1Yi5jb20vcnBmbG9yZW5jZS9wdWJsaXNoZXIuanNcbiAqIE1JVCBMaWNlbnNlXG4qL1xuXG4vLyBVTUQgQm9pbGVycGxhdGUgXFxvLyAmJiBEOlxuKGZ1bmN0aW9uIChyb290LCBmYWN0b3J5KSB7XG4gIGlmICh0eXBlb2YgZXhwb3J0cyA9PT0gJ29iamVjdCcpIHtcbiAgICBtb2R1bGUuZXhwb3J0cyA9IGZhY3RvcnkoKTsgLy8gbm9kZVxuICB9IGVsc2UgaWYgKHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCkge1xuICAgIGRlZmluZShmYWN0b3J5KTsgLy8gYW1kXG4gIH0gZWxzZSB7XG4gICAgLy8gd2luZG93IHdpdGggbm9Db25mbGljdFxuICAgIHZhciBfcHVibGlzaGVyID0gcm9vdC5wdWJsaXNoZXI7XG4gICAgdmFyIHB1Ymxpc2hlciA9IHJvb3QucHVibGlzaGVyID0gZmFjdG9yeSgpO1xuICAgIHJvb3QucHVibGlzaGVyLm5vQ29uZmxpY3QgPSBmdW5jdGlvbiAoKSB7XG4gICAgICByb290LnB1Ymxpc2hlciA9IF9wdWJsaXNoZXI7XG4gICAgICByZXR1cm4gcHVibGlzaGVyO1xuICAgIH1cbiAgfVxufSh0aGlzLCBmdW5jdGlvbiAoKSB7XG5cbiAgdmFyIHB1Ymxpc2hlciA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICB2YXIgdG9waWNzID0ge307XG4gICAgb2JqID0gb2JqIHx8IHt9O1xuXG4gICAgb2JqLnB1Ymxpc2ggPSBmdW5jdGlvbiAodG9waWMvKiwgbWVzc2FnZXMuLi4qLykge1xuICAgICAgaWYgKCF0b3BpY3NbdG9waWNdKSByZXR1cm4gb2JqO1xuICAgICAgdmFyIG1lc3NhZ2VzID0gW10uc2xpY2UuY2FsbChhcmd1bWVudHMsIDEpO1xuICAgICAgZm9yICh2YXIgaSA9IDAsIGwgPSB0b3BpY3NbdG9waWNdLmxlbmd0aDsgaSA8IGw7IGkrKykge1xuICAgICAgICB0b3BpY3NbdG9waWNdW2ldLmhhbmRsZXIuYXBwbHkodG9waWNzW3RvcGljXVtpXS5jb250ZXh0LCBtZXNzYWdlcyk7XG4gICAgICB9XG4gICAgICByZXR1cm4gb2JqO1xuICAgIH07XG5cbiAgICBvYmouc3Vic2NyaWJlID0gZnVuY3Rpb24gKHRvcGljT3JTdWJzY3JpYmVyLCBoYW5kbGVyT3JUb3BpY3MpIHtcbiAgICAgIHZhciBmaXJzdFR5cGUgPSB0eXBlb2YgdG9waWNPclN1YnNjcmliZXI7XG5cbiAgICAgIGlmIChmaXJzdFR5cGUgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgIHJldHVybiBzdWJzY3JpYmUuYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICAgIH1cblxuICAgICAgaWYgKGZpcnN0VHlwZSA9PT0gJ29iamVjdCcgJiYgIWhhbmRsZXJPclRvcGljcykge1xuICAgICAgICByZXR1cm4gc3Vic2NyaWJlTXVsdGlwbGUuYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICAgIH1cblxuICAgICAgaWYgKHR5cGVvZiBoYW5kbGVyT3JUb3BpY3MgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgIHJldHVybiBoaXRjaC5hcHBseShudWxsLCBhcmd1bWVudHMpO1xuICAgICAgfVxuXG4gICAgICByZXR1cm4gaGl0Y2hNdWx0aXBsZS5hcHBseShudWxsLCBhcmd1bWVudHMpO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBzdWJzY3JpYmUgKHRvcGljLCBoYW5kbGVyLCBjb250ZXh0KSB7XG4gICAgICB2YXIgcmVmZXJlbmNlID0geyBoYW5kbGVyOiBoYW5kbGVyLCBjb250ZXh0OiBjb250ZXh0IHx8IG9iaiB9O1xuICAgICAgdG9waWMgPSB0b3BpY3NbdG9waWNdIHx8ICh0b3BpY3NbdG9waWNdID0gW10pO1xuICAgICAgdG9waWMucHVzaChyZWZlcmVuY2UpO1xuICAgICAgcmV0dXJuIHtcbiAgICAgICAgYXR0YWNoOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgdG9waWMucHVzaChyZWZlcmVuY2UpO1xuICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9LFxuICAgICAgICBkZXRhY2g6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBlcmFzZSh0b3BpYywgcmVmZXJlbmNlKTtcbiAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfVxuICAgICAgfTtcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gc3Vic2NyaWJlTXVsdGlwbGUgKHBhaXJzKSB7XG4gICAgICB2YXIgc3Vic2NyaXB0aW9ucyA9IHt9O1xuICAgICAgZm9yICh2YXIgdG9waWMgaW4gcGFpcnMpIHtcbiAgICAgICAgaWYgKCFwYWlycy5oYXNPd25Qcm9wZXJ0eSh0b3BpYykpIGNvbnRpbnVlO1xuICAgICAgICBzdWJzY3JpcHRpb25zW3RvcGljXSA9IHN1YnNjcmliZSh0b3BpYywgcGFpcnNbdG9waWNdKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBzdWJzY3JpcHRpb25zO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBoaXRjaCAoc3Vic2NyaWJlciwgdG9waWMpIHtcbiAgICAgIHJldHVybiBzdWJzY3JpYmUodG9waWMsIHN1YnNjcmliZXJbdG9waWNdLCBzdWJzY3JpYmVyKTtcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gaGl0Y2hNdWx0aXBsZSAoc3Vic2NyaWJlciwgdG9waWNzKSB7XG4gICAgICB2YXIgc3Vic2NyaXB0aW9ucyA9IFtdO1xuICAgICAgZm9yICh2YXIgaSA9IDAsIGwgPSB0b3BpY3MubGVuZ3RoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgIHN1YnNjcmlwdGlvbnMucHVzaCggaGl0Y2goc3Vic2NyaWJlciwgdG9waWNzW2ldKSApO1xuICAgICAgfVxuICAgICAgcmV0dXJuIHN1YnNjcmlwdGlvbnM7XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIGVyYXNlIChhcnIsIHZpY3RpbSkge1xuICAgICAgZm9yICh2YXIgaSA9IDAsIGwgPSBhcnIubGVuZ3RoOyBpIDwgbDsgaSsrKXtcbiAgICAgICAgaWYgKGFycltpXSA9PT0gdmljdGltKSBhcnIuc3BsaWNlKGksIDEpO1xuICAgICAgfVxuICAgIH1cblxuICAgIHJldHVybiBvYmo7XG4gIH07XG5cbiAgLy8gcHVibGlzaGVyIGlzIGEgcHVibGlzaGVyLCBzbyBtZXRhIC4uLlxuICByZXR1cm4gcHVibGlzaGVyKHB1Ymxpc2hlcik7XG59KSk7XG4iXX0=
