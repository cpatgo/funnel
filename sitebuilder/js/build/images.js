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
                    url: "https://glchub.com/aem/manage/templates/classic/ajax/api.php",
                    data: {
                        'action':'get_form_by_id',
                        'form_id':form_id
                    },
                    dataType: 'json',
                    success:function(result) {
                        if(result.type === 'success') {
                            var jheight = jQuery('#pageList iframe').contents().height();
                            jheight += 100;
                            jQuery('#pageList iframe').contents().find('#user_form_div').html(result.message.html);
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9pbWFnZXMuanMiLCJqcy9tb2R1bGVzL2FjY291bnQuanMiLCJqcy9tb2R1bGVzL2J1aWxkZXIuanMiLCJqcy9tb2R1bGVzL2NhbnZhc0VsZW1lbnQuanMiLCJqcy9tb2R1bGVzL2NvbmZpZy5qcyIsImpzL21vZHVsZXMvaW1hZ2VMaWJyYXJ5LmpzIiwianMvbW9kdWxlcy9zdHlsZWVkaXRvci5qcyIsImpzL21vZHVsZXMvdWkuanMiLCJqcy9tb2R1bGVzL3V0aWxzLmpzIiwianMvdmVuZG9yL3B1Ymxpc2hlci5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ1RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3RKQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNsaUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzNIQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN6REE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUMxTUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ2ptQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNoSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNuQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHRyZXF1aXJlKCcuL21vZHVsZXMvdWknKTtcblx0cmVxdWlyZSgnLi9tb2R1bGVzL2J1aWxkZXInKTtcblx0cmVxdWlyZSgnLi9tb2R1bGVzL2NvbmZpZycpO1xuXHRyZXF1aXJlKCcuL21vZHVsZXMvaW1hZ2VMaWJyYXJ5Jyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9hY2NvdW50Jyk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0dmFyIGFwcFVJID0gcmVxdWlyZSgnLi91aS5qcycpLmFwcFVJO1xuXG5cdHZhciBhY2NvdW50ID0ge1xuICAgICAgICBcbiAgICAgICAgYnV0dG9uVXBkYXRlQWNjb3VudERldGFpbHM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhY2NvdW50RGV0YWlsc1N1Ym1pdCcpLFxuICAgICAgICBidXR0b25VcGRhdGVMb2dpbkRldGFpbHM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhY2NvdW50TG9naW5TdWJtaXQnKSxcbiAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uVXBkYXRlQWNjb3VudERldGFpbHMpLm9uKCdjbGljaycsIHRoaXMudXBkYXRlQWNjb3VudERldGFpbHMpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblVwZGF0ZUxvZ2luRGV0YWlscykub24oJ2NsaWNrJywgdGhpcy51cGRhdGVMb2dpbkRldGFpbHMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwZGF0ZXMgYWNjb3VudCBkZXRhaWxzXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZUFjY291bnREZXRhaWxzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hbGwgZmllbGRzIGZpbGxlZCBpbj9cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNmaXJzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjbGFzdG5hbWUnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNsYXN0bmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2xhc3RuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG5cdFx0XG4gICAgICAgICAgICBpZiggYWxsR29vZCA9PT0gMSApIHtcblxuICAgICAgICAgICAgICAgIHZhciB0aGVCdXR0b24gPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2hvdyBsb2FkZXJcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5sb2FkZXInKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBhbGVydHNcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5hbGVydHMgPiAqJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1widXNlcnMvdWFjY291bnRcIixcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bvc3QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiAkKCcjYWNjb3VudF9kZXRhaWxzJykuc2VyaWFsaXplKClcbiAgICAgICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAgICAgdGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9oaWRlIGxvYWRlclxuICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIC5sb2FkZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vc3VjY2Vzc1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7IFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmFsZXJ0cyA+IConKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24gKCkgeyAkKHRoaXMpLnJlbW92ZSgpOyB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sIDMwMDApO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGRhdGVzIGFjY291bnQgbG9naW4gZGV0YWlsc1xuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVMb2dpbkRldGFpbHM6IGZ1bmN0aW9uKCkge1xuXHRcdFx0XG5cdFx0XHRjb25zb2xlLmxvZyhhcHBVSSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDA7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I2VtYWlsJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiBpbnB1dCNwYXNzd29yZCcpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggYWxsR29vZCA9PT0gMSApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgdGhlQnV0dG9uID0gJCh0aGlzKTtcblxuICAgICAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2hvdyBsb2FkZXJcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAubG9hZGVyJykuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgYWxlcnRzXG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmFsZXJ0cyA+IConKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJ1c2Vycy91bG9naW5cIixcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bvc3QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiAkKCcjYWNjb3VudF9sb2dpbicpLnNlcmlhbGl6ZSgpXG4gICAgICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXQpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgICAgIHRoZUJ1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vaGlkZSBsb2FkZXJcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmxvYWRlcicpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gLmFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuXHRcdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL3N1Y2Nlc3NcbiAgICAgICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkgeyBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAuYWxlcnRzID4gKicpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbiAoKSB7ICQodGhpcykucmVtb3ZlKCk7IH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSwgMzAwMCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcbiAgICBcbiAgICBhY2NvdW50LmluaXQoKTtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuICAgIHZhciBzaXRlQnVpbGRlclV0aWxzID0gcmVxdWlyZSgnLi91dGlscy5qcycpO1xuICAgIHZhciBiQ29uZmlnID0gcmVxdWlyZSgnLi9jb25maWcuanMnKTtcbiAgICB2YXIgYXBwVUkgPSByZXF1aXJlKCcuL3VpLmpzJykuYXBwVUk7XG4gICAgdmFyIHB1Ymxpc2hlciA9IHJlcXVpcmUoJy4uL3ZlbmRvci9wdWJsaXNoZXInKTtcbiAgICB2YXIgZm9ybV9pZCA9IDA7XG5cblx0IC8qXG4gICAgICAgIEJhc2ljIEJ1aWxkZXIgVUkgaW5pdGlhbGlzYXRpb25cbiAgICAqL1xuICAgIHZhciBidWlsZGVyVUkgPSB7XG4gICAgICAgIFxuICAgICAgICBhbGxCbG9ja3M6IHt9LCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hvbGRzIGFsbCBibG9ja3MgbG9hZGVkIGZyb20gdGhlIHNlcnZlclxuICAgICAgICBtZW51V3JhcHBlcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21lbnUnKSxcbiAgICAgICAgcHJpbWFyeVNpZGVNZW51V3JhcHBlcjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21haW4nKSxcbiAgICAgICAgYnV0dG9uQmFjazogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhY2tCdXR0b24nKSxcbiAgICAgICAgYnV0dG9uQmFja0NvbmZpcm06IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsZWF2ZVBhZ2VCdXR0b24nKSxcbiAgICAgICAgXG4gICAgICAgIGFjZUVkaXRvcnM6IHt9LFxuICAgICAgICBmcmFtZUNvbnRlbnRzOiAnJywgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vaG9sZHMgZnJhbWUgY29udGVudHNcbiAgICAgICAgdGVtcGxhdGVJRDogMCwgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL2hvbGRzIHRoZSB0ZW1wbGF0ZSBJRCBmb3IgYSBwYWdlICg/Pz8pXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIG1vZGFsRGVsZXRlQmxvY2s6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVCbG9jaycpLFxuICAgICAgICBtb2RhbFJlc2V0QmxvY2s6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdyZXNldEJsb2NrJyksXG4gICAgICAgIG1vZGFsRGVsZXRlUGFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZVBhZ2UnKSxcbiAgICAgICAgYnV0dG9uRGVsZXRlUGFnZUNvbmZpcm06IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkZWxldGVQYWdlQ29uZmlybScpLFxuICAgICAgICBcbiAgICAgICAgZHJvcGRvd25QYWdlTGlua3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzRHJvcGRvd24nKSxcblxuICAgICAgICBwYWdlSW5Vcmw6IG51bGwsXG4gICAgICAgIFxuICAgICAgICB0ZW1wRnJhbWU6IHt9LFxuXG4gICAgICAgIGN1cnJlbnRSZXNwb25zaXZlTW9kZToge30sXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9hZCBibG9ja3NcbiAgICAgICAgICAgICQuZ2V0SlNPTihhcHBVSS5iYXNlVXJsKydlbGVtZW50cy5qc29uP3Y9MTIzNDU2NzgnLCBmdW5jdGlvbihkYXRhKXsgYnVpbGRlclVJLmFsbEJsb2NrcyA9IGRhdGE7IGJ1aWxkZXJVSS5pbXBsZW1lbnRCbG9ja3MoKTsgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2l0ZWJhciBob3ZlciBhbmltYXRpb24gYWN0aW9uXG4gICAgICAgICAgICAkKHRoaXMubWVudVdyYXBwZXIpLm9uKCdtb3VzZWVudGVyJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnN0b3AoKS5hbmltYXRlKHsnbGVmdCc6ICcwcHgnfSwgNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnN0b3AoKS5hbmltYXRlKHsnbGVmdCc6ICctMTkwcHgnfSwgNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjbWVudSAjbWFpbiBhJykucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICQoJy5tZW51IC5zZWNvbmQnKS5zdG9wKCkuYW5pbWF0ZSh7XG4gICAgICAgICAgICAgICAgICAgIHdpZHRoOiAwXG4gICAgICAgICAgICAgICAgfSwgNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcHJldmVudCBjbGljayBldmVudCBvbiBhbmNvcnMgaW4gdGhlIGJsb2NrIHNlY3Rpb24gb2YgdGhlIHNpZGViYXJcbiAgICAgICAgICAgICQodGhpcy5wcmltYXJ5U2lkZU1lbnVXcmFwcGVyKS5vbignY2xpY2snLCAnYTpub3QoLmFjdGlvbkJ1dHRvbnMpJywgZnVuY3Rpb24oZSl7ZS5wcmV2ZW50RGVmYXVsdCgpO30pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uQmFjaykub24oJ2NsaWNrJywgdGhpcy5iYWNrQnV0dG9uKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25CYWNrQ29uZmlybSkub24oJ2NsaWNrJywgdGhpcy5iYWNrQnV0dG9uQ29uZmlybSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbm90aWZ5IHRoZSB1c2VyIG9mIHBlbmRpbmcgY2huYWdlcyB3aGVuIGNsaWNraW5nIHRoZSBiYWNrIGJ1dHRvblxuICAgICAgICAgICAgJCh3aW5kb3cpLmJpbmQoJ2JlZm9yZXVubG9hZCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYoIHNpdGUucGVuZGluZ0NoYW5nZXMgPT09IHRydWUgKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnWW91ciBzaXRlIGNvbnRhaW5zIGNoYW5nZWQgd2hpY2ggaGF2ZW5cXCd0IGJlZW4gc2F2ZWQgeWV0LiBBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gbGVhdmU/JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9VUkwgcGFyYW1ldGVyc1xuICAgICAgICAgICAgYnVpbGRlclVJLnBhZ2VJblVybCA9IHNpdGVCdWlsZGVyVXRpbHMuZ2V0UGFyYW1ldGVyQnlOYW1lKCdwJyk7XG5cbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYnVpbGRzIHRoZSBibG9ja3MgaW50byB0aGUgc2l0ZSBiYXJcbiAgICAgICAgKi9cbiAgICAgICAgaW1wbGVtZW50QmxvY2tzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgdmFyIG5ld0l0ZW0sIGxvYWRlckZ1bmN0aW9uO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBrZXkgaW4gdGhpcy5hbGxCbG9ja3MuZWxlbWVudHMgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIG5pY2VLZXkgPSBrZXkudG9Mb3dlckNhc2UoKS5yZXBsYWNlKFwiIFwiLCBcIl9cIik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnPGxpPjxhIGhyZWY9XCJcIiBpZD1cIicrbmljZUtleSsnXCI+JytrZXkrJzwvYT48L2xpPicpLmFwcGVuZFRvKCcjbWVudSAjbWFpbiB1bCNlbGVtZW50Q2F0cycpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XS5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnRodW1ibmFpbCA9PT0gbnVsbCApIHsvL3dlJ2xsIG5lZWQgYW4gaWZyYW1lXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vYnVpbGQgdXMgc29tZSBpZnJhbWVzIVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsb2FkZXJGdW5jdGlvbiA9ICdkYXRhLWxvYWRlcmZ1bmN0aW9uPVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uKydcIic7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0gPSAkKCc8bGkgY2xhc3M9XCJlbGVtZW50ICcrbmljZUtleSsnXCI+PGlmcmFtZSBzcmM9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIHNjcm9sbGluZz1cIm5vXCIgc2FuZGJveD1cImFsbG93LXNhbWUtb3JpZ2luXCI+PC9pZnJhbWU+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpZnJhbWUgc3JjPVwiYWJvdXQ6YmxhbmtcIiBzY3JvbGxpbmc9XCJub1wiPjwvaWZyYW1lPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdzcmMnLCBhcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udXJsKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7Ly93ZSd2ZSBnb3QgYSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbiApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbG9hZGVyRnVuY3Rpb24gPSAnZGF0YS1sb2FkZXJmdW5jdGlvbj1cIicrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5sb2FkZXJGdW5jdGlvbisnXCInO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpbWcgc3JjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udGh1bWJuYWlsKydcIiBkYXRhLXNyY2M9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIGRhdGEtaGVpZ2h0PVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCsnXCIgZGF0YS1zYW5kYm94PVwiXCIgJytsb2FkZXJGdW5jdGlvbisnPjwvbGk+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtID0gJCgnPGxpIGNsYXNzPVwiZWxlbWVudCAnK25pY2VLZXkrJ1wiPjxpbWcgc3JjPVwiJythcHBVSS5iYXNlVXJsK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udGh1bWJuYWlsKydcIiBkYXRhLXNyY2M9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwrJ1wiIGRhdGEtaGVpZ2h0PVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCsnXCI+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0uYXBwZW5kVG8oJyNtZW51ICNzZWNvbmQgdWwjZWxlbWVudHMnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3pvb21lciB3b3Jrc1xuXG4gICAgICAgICAgICAgICAgICAgIHZhciB0aGVIZWlnaHQ7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5oZWlnaHQgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZUhlaWdodCA9IHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uaGVpZ2h0KjAuMjU7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVIZWlnaHQgPSAnYXV0byc7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5maW5kKCdpZnJhbWUnKS56b29tZXIoe1xuICAgICAgICAgICAgICAgICAgICAgICAgem9vbTogMC4yNSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHdpZHRoOiAyNzAsXG4gICAgICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IHRoZUhlaWdodCxcbiAgICAgICAgICAgICAgICAgICAgICAgIG1lc3NhZ2U6IFwiRHJhZyZEcm9wIE1lIVwiXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2RyYWdnYWJsZXNcbiAgICAgICAgICAgIGJ1aWxkZXJVSS5tYWtlRHJhZ2dhYmxlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBldmVudCBoYW5kbGVyIGZvciB3aGVuIHRoZSBiYWNrIGxpbmsgaXMgY2xpY2tlZFxuICAgICAgICAqL1xuICAgICAgICBiYWNrQnV0dG9uOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHNpdGUucGVuZGluZ0NoYW5nZXMgPT09IHRydWUgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2JhY2tNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGJ1dHRvbiBmb3IgY29uZmlybWluZyBsZWF2aW5nIHRoZSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIGJhY2tCdXR0b25Db25maXJtOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5wZW5kaW5nQ2hhbmdlcyA9IGZhbHNlOy8vcHJldmVudCB0aGUgSlMgYWxlcnQgYWZ0ZXIgY29uZmlybWluZyB1c2VyIHdhbnRzIHRvIGxlYXZlXG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIG1ha2VzIHRoZSBibG9ja3MgYW5kIHRlbXBsYXRlcyBpbiB0aGUgc2lkZWJhciBkcmFnZ2FibGUgb250byB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIG1ha2VEcmFnZ2FibGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCcjZWxlbWVudHMgbGksICN0ZW1wbGF0ZXMgbGknKS5lYWNoKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAkKHRoaXMpLmRyYWdnYWJsZSh7XG4gICAgICAgICAgICAgICAgICAgIGhlbHBlcjogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gJCgnPGRpdiBzdHlsZT1cImhlaWdodDogMTAwcHg7IHdpZHRoOiAzMDBweDsgYmFja2dyb3VuZDogI0Y5RkFGQTsgYm94LXNoYWRvdzogNXB4IDVweCAxcHggcmdiYSgwLDAsMCwwLjEpOyB0ZXh0LWFsaWduOiBjZW50ZXI7IGxpbmUtaGVpZ2h0OiAxMDBweDsgZm9udC1zaXplOiAyOHB4OyBjb2xvcjogIzE2QTA4NVwiPjxzcGFuIGNsYXNzPVwiZnVpLWxpc3RcIj48L3NwYW4+PC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHJldmVydDogJ2ludmFsaWQnLFxuICAgICAgICAgICAgICAgICAgICBhcHBlbmRUbzogJ2JvZHknLFxuICAgICAgICAgICAgICAgICAgICBjb25uZWN0VG9Tb3J0YWJsZTogJyNwYWdlTGlzdCA+IHVsJyxcbiAgICAgICAgICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUubW92ZU1vZGUoJ29uJyk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN0b3A6IGZ1bmN0aW9uICgpIHt9XG4gICAgICAgICAgICAgICAgfSk7IFxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI2VsZW1lbnRzIGxpIGEnKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS51bmJpbmQoJ2NsaWNrJykuYmluZCgnY2xpY2snLCBmdW5jdGlvbihlKXtcbiAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBJbXBsZW1lbnRzIHRoZSBzaXRlIG9uIHRoZSBjYW52YXMsIGNhbGxlZCBmcm9tIHRoZSBTaXRlIG9iamVjdCB3aGVuIHRoZSBzaXRlRGF0YSBoYXMgY29tcGxldGVkIGxvYWRpbmdcbiAgICAgICAgKi9cbiAgICAgICAgcG9wdWxhdGVDYW52YXM6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICB2YXIgaSxcbiAgICAgICAgICAgICAgICBjb3VudGVyID0gMTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sb29wIHRocm91Z2ggdGhlIHBhZ2VzXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggaSBpbiBzaXRlLnBhZ2VzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBuZXdQYWdlID0gbmV3IFBhZ2UoaSwgc2l0ZS5wYWdlc1tpXSwgY291bnRlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGNvdW50ZXIrKztcblxuICAgICAgICAgICAgICAgIC8vc2V0IHRoaXMgcGFnZSBhcyBhY3RpdmU/XG4gICAgICAgICAgICAgICAgaWYoIGJ1aWxkZXJVSS5wYWdlSW5VcmwgPT09IGkgKSB7XG4gICAgICAgICAgICAgICAgICAgIG5ld1BhZ2Uuc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYWN0aXZhdGUgdGhlIGZpcnN0IHBhZ2VcbiAgICAgICAgICAgIGlmKHNpdGUuc2l0ZVBhZ2VzLmxlbmd0aCA+IDAgJiYgYnVpbGRlclVJLnBhZ2VJblVybCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzWzBdLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIENhbnZhcyBsb2FkaW5nIG9uL29mZlxuICAgICAgICAqL1xuICAgICAgICBjYW52YXNMb2FkaW5nOiBmdW5jdGlvbiAodmFsdWUpIHtcblxuICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gJ29uJyAmJiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJykucXVlcnlTZWxlY3RvckFsbCgnI2NhbnZhc092ZXJsYXknKS5sZW5ndGggPT09IDAgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgb3ZlcmxheSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0RJVicpO1xuXG4gICAgICAgICAgICAgICAgb3ZlcmxheS5zdHlsZS5kaXNwbGF5ID0gJ2ZsZXgnO1xuICAgICAgICAgICAgICAgICQob3ZlcmxheSkuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG92ZXJsYXkuaWQgPSAnY2FudmFzT3ZlcmxheSc7XG5cbiAgICAgICAgICAgICAgICBvdmVybGF5LmlubmVySFRNTCA9ICc8ZGl2IGNsYXNzPVwibG9hZGVyXCI+PHNwYW4+ezwvc3Bhbj48c3Bhbj59PC9zcGFuPjwvZGl2Pic7XG5cbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJykuYXBwZW5kQ2hpbGQob3ZlcmxheSk7XG5cbiAgICAgICAgICAgICAgICAkKCcjY2FudmFzT3ZlcmxheScpLmZhZGVJbig1MDApO1xuXG4gICAgICAgICAgICB9IGVsc2UgaWYgKCB2YWx1ZSA9PT0gJ29mZicgJiYgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZyYW1lV3JhcHBlcicpLnF1ZXJ5U2VsZWN0b3JBbGwoJyNjYW52YXNPdmVybGF5JykubGVuZ3RoID09PSAxICkge1xuXG4gICAgICAgICAgICAgICAgc2l0ZS5sb2FkZWQoKTtcblxuICAgICAgICAgICAgICAgICQoJyNjYW52YXNPdmVybGF5JykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgIGZvcm1faWQgPSAkKCcjZm9ybUlEJykudmFsKCk7XG4gICAgICAgICAgICAgICAgaWYoZm9ybV9pZCAhPT0gMCkge1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnF1aWNrX2xvYWRfZm9ybSgpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcblxuICAgIC8qXG4gICAgICAgIFBhZ2UgY29uc3RydWN0b3JcbiAgICAqL1xuICAgIGZ1bmN0aW9uIFBhZ2UgKHBhZ2VOYW1lLCBwYWdlLCBjb3VudGVyKSB7XG4gICAgXG4gICAgICAgIHRoaXMubmFtZSA9IHBhZ2VOYW1lIHx8IFwiXCI7XG4gICAgICAgIHRoaXMucGFnZUlEID0gcGFnZS5wYWdlX2lkIHx8IDA7XG4gICAgICAgIHRoaXMuYmxvY2tzID0gW107XG4gICAgICAgIHRoaXMucGFyZW50VUwgPSB7fTsgLy9wYXJlbnQgVUwgb24gdGhlIGNhbnZhc1xuICAgICAgICB0aGlzLnN0YXR1cyA9ICcnOy8vJycsICduZXcnIG9yICdjaGFuZ2VkJ1xuICAgICAgICB0aGlzLnNjcmlwdHMgPSBbXTsvL3RyYWNrcyBzY3JpcHQgVVJMcyB1c2VkIG9uIHRoaXMgcGFnZVxuICAgICAgICBcbiAgICAgICAgdGhpcy5wYWdlU2V0dGluZ3MgPSB7XG4gICAgICAgICAgICB0aXRsZTogcGFnZS5wYWdlc190aXRsZSB8fCAnJyxcbiAgICAgICAgICAgIG1ldGFfZGVzY3JpcHRpb246IHBhZ2UubWV0YV9kZXNjcmlwdGlvbiB8fCAnJyxcbiAgICAgICAgICAgIG1ldGFfa2V5d29yZHM6IHBhZ2UubWV0YV9rZXl3b3JkcyB8fCAnJyxcbiAgICAgICAgICAgIGhlYWRlcl9pbmNsdWRlczogcGFnZS5oZWFkZXJfaW5jbHVkZXMgfHwgJycsXG4gICAgICAgICAgICBwYWdlX2NzczogcGFnZS5wYWdlX2NzcyB8fCAnJ1xuICAgICAgICB9O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICB0aGlzLnBhZ2VNZW51VGVtcGxhdGUgPSAnPGEgaHJlZj1cIlwiIGNsYXNzPVwibWVudUl0ZW1MaW5rXCI+cGFnZTwvYT48c3BhbiBjbGFzcz1cInBhZ2VCdXR0b25zXCI+PGEgaHJlZj1cIlwiIGNsYXNzPVwiZmlsZUVkaXQgZnVpLW5ld1wiPjwvYT48YSBocmVmPVwiXCIgY2xhc3M9XCJmaWxlRGVsIGZ1aS1jcm9zc1wiPjxhIGNsYXNzPVwiYnRuIGJ0bi14cyBidG4tcHJpbWFyeSBidG4tZW1ib3NzZWQgZmlsZVNhdmUgZnVpLWNoZWNrXCIgaHJlZj1cIiNcIj48L2E+PC9zcGFuPjwvYT48L3NwYW4+JztcbiAgICAgICAgXG4gICAgICAgIHRoaXMubWVudUl0ZW0gPSB7fTsvL3JlZmVyZW5jZSB0byB0aGUgcGFnZXMgbWVudSBpdGVtIGZvciB0aGlzIHBhZ2UgaW5zdGFuY2VcbiAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbSA9IHt9Oy8vcmVmZXJlbmNlIHRvIHRoZSBsaW5rcyBkcm9wZG93biBpdGVtIGZvciB0aGlzIHBhZ2UgaW5zdGFuY2VcbiAgICAgICAgXG4gICAgICAgIHRoaXMucGFyZW50VUwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdVTCcpO1xuICAgICAgICB0aGlzLnBhcmVudFVMLnNldEF0dHJpYnV0ZSgnaWQnLCBcInBhZ2VcIitjb3VudGVyKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIG1ha2VzIHRoZSBjbGlja2VkIHBhZ2UgYWN0aXZlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2VsZWN0UGFnZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKCdzZWxlY3Q6Jyk7XG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKHRoaXMucGFnZVNldHRpbmdzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9tYXJrIHRoZSBtZW51IGl0ZW0gYXMgYWN0aXZlXG4gICAgICAgICAgICBzaXRlLmRlQWN0aXZhdGVBbGwoKTtcbiAgICAgICAgICAgICQodGhpcy5tZW51SXRlbSkuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xldCBTaXRlIGtub3cgd2hpY2ggcGFnZSBpcyBjdXJyZW50bHkgYWN0aXZlXG4gICAgICAgICAgICBzaXRlLnNldEFjdGl2ZSh0aGlzKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kaXNwbGF5IHRoZSBuYW1lIG9mIHRoZSBhY3RpdmUgcGFnZSBvbiB0aGUgY2FudmFzXG4gICAgICAgICAgICBzaXRlLnBhZ2VUaXRsZS5pbm5lckhUTUwgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9hZCB0aGUgcGFnZSBzZXR0aW5ncyBpbnRvIHRoZSBwYWdlIHNldHRpbmdzIG1vZGFsXG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzVGl0bGUudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy50aXRsZTtcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NNZXRhRGVzY3JpcHRpb24udmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5tZXRhX2Rlc2NyaXB0aW9uO1xuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFLZXl3b3Jkcy52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLm1ldGFfa2V5d29yZHM7XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzSW5jbHVkZXMudmFsdWUgPSB0aGlzLnBhZ2VTZXR0aW5ncy5oZWFkZXJfaW5jbHVkZXM7XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzUGFnZUNzcy52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLnBhZ2VfY3NzO1xuICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vdHJpZ2dlciBjdXN0b20gZXZlbnRcbiAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdjaGFuZ2VQYWdlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVzZXQgdGhlIGhlaWdodHMgZm9yIHRoZSBibG9ja3Mgb24gdGhlIGN1cnJlbnQgcGFnZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggT2JqZWN0LmtleXModGhpcy5ibG9ja3NbaV0uZnJhbWVEb2N1bWVudCkubGVuZ3RoID4gMCApe1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrc1tpXS5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2hvdyB0aGUgZW1wdHkgbWVzc2FnZT9cbiAgICAgICAgICAgIHRoaXMuaXNFbXB0eSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2hhbmdlZCB0aGUgbG9jYXRpb24vb3JkZXIgb2YgYSBibG9jayB3aXRoaW4gYSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2V0UG9zaXRpb24gPSBmdW5jdGlvbihmcmFtZUlELCBuZXdQb3MpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy93ZSdsbCBuZWVkIHRoZSBibG9jayBvYmplY3QgY29ubmVjdGVkIHRvIGlmcmFtZSB3aXRoIGZyYW1lSURcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKHZhciBpIGluIHRoaXMuYmxvY2tzKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzW2ldLmZyYW1lLmdldEF0dHJpYnV0ZSgnaWQnKSA9PT0gZnJhbWVJRCApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vY2hhbmdlIHRoZSBwb3NpdGlvbiBvZiB0aGlzIGJsb2NrIGluIHRoZSBibG9ja3MgYXJyYXlcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ibG9ja3Muc3BsaWNlKG5ld1BvcywgMCwgdGhpcy5ibG9ja3Muc3BsaWNlKGksIDEpWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlIGJsb2NrIGZyb20gYmxvY2tzIGFycmF5XG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZGVsZXRlQmxvY2sgPSBmdW5jdGlvbihibG9jaykge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBmcm9tIGJsb2NrcyBhcnJheVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLmJsb2NrcyApIHtcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5ibG9ja3NbaV0gPT09IGJsb2NrICkge1xuICAgICAgICAgICAgICAgICAgICAvL2ZvdW5kIGl0LCByZW1vdmUgZnJvbSBibG9ja3MgYXJyYXlcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ibG9ja3Muc3BsaWNlKGksIDEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHRvZ2dsZXMgYWxsIGJsb2NrIGZyYW1lQ292ZXJzIG9uIHRoaXMgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnRvZ2dsZUZyYW1lQ292ZXJzID0gZnVuY3Rpb24ob25Pck9mZikge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5ibG9ja3NbaV0udG9nZ2xlQ292ZXIob25Pck9mZik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzZXR1cCBmb3IgZWRpdGluZyBhIHBhZ2UgbmFtZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmVkaXRQYWdlTmFtZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggIXRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0JykgKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2hpZGUgdGhlIGxpbmtcbiAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EubWVudUl0ZW1MaW5rJykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vaW5zZXJ0IHRoZSBpbnB1dCBmaWVsZFxuICAgICAgICAgICAgICAgIHZhciBuZXdJbnB1dCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQudHlwZSA9ICd0ZXh0JztcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAncGFnZScpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgndmFsdWUnLCB0aGlzLm5hbWUpO1xuICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0uaW5zZXJ0QmVmb3JlKG5ld0lucHV0LCB0aGlzLm1lbnVJdGVtLmZpcnN0Q2hpbGQpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5mb2N1cygpO1xuICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgdG1wU3RyID0gbmV3SW5wdXQuZ2V0QXR0cmlidXRlKCd2YWx1ZScpO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgndmFsdWUnLCAnJyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRtcFN0cik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QuYWRkKCdlZGl0Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIFVwZGF0ZXMgdGhpcyBwYWdlJ3MgbmFtZSAoZXZlbnQgaGFuZGxlciBmb3IgdGhlIHNhdmUgYnV0dG9uKVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnVwZGF0ZVBhZ2VOYW1lRXZlbnQgPSBmdW5jdGlvbihlbCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXQnKSApIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZWwgaXMgdGhlIGNsaWNrZWQgYnV0dG9uLCB3ZSdsbCBuZWVkIGFjY2VzcyB0byB0aGUgaW5wdXRcbiAgICAgICAgICAgICAgICB2YXIgdGhlSW5wdXQgPSB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0W25hbWU9XCJwYWdlXCJdJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9tYWtlIHN1cmUgdGhlIHBhZ2UncyBuYW1lIGlzIE9LXG4gICAgICAgICAgICAgICAgaWYoIHNpdGUuY2hlY2tQYWdlTmFtZSh0aGVJbnB1dC52YWx1ZSkgKSB7XG4gICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZSA9IHNpdGUucHJlcFBhZ2VOYW1lKCB0aGVJbnB1dC52YWx1ZSApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignaW5wdXRbbmFtZT1cInBhZ2VcIl0nKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLm1lbnVJdGVtTGluaycpLmlubmVySFRNTCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLm1lbnVJdGVtTGluaycpLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0uY2xhc3NMaXN0LnJlbW92ZSgnZWRpdCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3VwZGF0ZSB0aGUgbGlua3MgZHJvcGRvd24gaXRlbVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnRleHQgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0uc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRoaXMubmFtZStcIi5odG1sXCIpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgdGhlIHBhZ2UgbmFtZSBvbiB0aGUgY2FudmFzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUucGFnZVRpdGxlLmlubmVySFRNTCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NoYW5nZWQgcGFnZSB0aXRsZSwgd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBhbGVydChzaXRlLnBhZ2VOYW1lRXJyb3IpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGVzIHRoaXMgZW50aXJlIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5kZWxldGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kZWxldGUgZnJvbSB0aGUgU2l0ZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiBzaXRlLnNpdGVQYWdlcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggc2l0ZS5zaXRlUGFnZXNbaV0gPT09IHRoaXMgKSB7Ly9nb3QgYSBtYXRjaCFcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIGZyb20gc2l0ZS5zaXRlUGFnZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXMuc3BsaWNlKGksIDEpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldGUgZnJvbSBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRVTC5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vYWRkIHRvIGRlbGV0ZWQgcGFnZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5wYWdlc1RvRGVsZXRlLnB1c2godGhpcy5uYW1lKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBwYWdlJ3MgbWVudSBpdGVtXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0IHRoZSBwYWdlcyBsaW5rIGRyb3Bkb3duIGl0ZW1cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vYWN0aXZhdGUgdGhlIGZpcnN0IHBhZ2VcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXNbMF0uc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9wYWdlIHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNoZWNrcyBpZiB0aGUgcGFnZSBpcyBlbXB0eSwgaWYgc28gc2hvdyB0aGUgJ2VtcHR5JyBtZXNzYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaXNFbXB0eSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGhpcy5ibG9ja3MubGVuZ3RoID09PSAwICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUubWVzc2FnZVN0YXJ0LnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgIHNpdGUuZGl2RnJhbWVXcmFwcGVyLmNsYXNzTGlzdC5hZGQoJ2VtcHR5Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzaXRlLm1lc3NhZ2VTdGFydC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIHNpdGUuZGl2RnJhbWVXcmFwcGVyLmNsYXNzTGlzdC5yZW1vdmUoJ2VtcHR5Jyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcHJlcHMvc3RyaXBzIHRoaXMgcGFnZSBkYXRhIGZvciBhIHBlbmRpbmcgYWpheCByZXF1ZXN0XG4gICAgICAgICovXG4gICAgICAgIHRoaXMucHJlcEZvclNhdmUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2UgPSB7fTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBwYWdlLm5hbWUgPSB0aGlzLm5hbWU7XG4gICAgICAgICAgICBwYWdlLnBhZ2VTZXR0aW5ncyA9IHRoaXMucGFnZVNldHRpbmdzO1xuICAgICAgICAgICAgcGFnZS5zdGF0dXMgPSB0aGlzLnN0YXR1cztcbiAgICAgICAgICAgIHBhZ2UucGFnZUlEID0gdGhpcy5wYWdlSUQ7XG4gICAgICAgICAgICBwYWdlLmJsb2NrcyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcHJvY2VzcyB0aGUgYmxvY2tzXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCB0aGlzLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBibG9jayA9IHt9O1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHRoaXMuYmxvY2tzW3hdLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLmZyYW1lQ29udGVudCA9IFwiPGh0bWw+XCIrJCgnI3NhbmRib3hlcyAjJyt0aGlzLmJsb2Nrc1t4XS5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJ2h0bWwnKS5odG1sKCkrXCI8L2h0bWw+XCI7XG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLnNhbmRib3ggPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5sb2FkZXJGdW5jdGlvbiA9IHRoaXMuYmxvY2tzW3hdLnNhbmRib3hfbG9hZGVyO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBibG9jay5mcmFtZUNvbnRlbnQgPSB0aGlzLmJsb2Nrc1t4XS5nZXRTb3VyY2UoKTtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2suc2FuZGJveCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5sb2FkZXJGdW5jdGlvbiA9ICcnO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmZyYW1lSGVpZ2h0ID0gdGhpcy5ibG9ja3NbeF0uZnJhbWVIZWlnaHQ7XG4gICAgICAgICAgICAgICAgYmxvY2sub3JpZ2luYWxVcmwgPSB0aGlzLmJsb2Nrc1t4XS5vcmlnaW5hbFVybDtcbiAgICAgICAgICAgICAgICBpZiAoIHRoaXMuYmxvY2tzW3hdLmdsb2JhbCApIGJsb2NrLmZyYW1lc19nbG9iYWwgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHBhZ2UuYmxvY2tzLnB1c2goYmxvY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBwYWdlO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGdlbmVyYXRlcyB0aGUgZnVsbCBwYWdlLCB1c2luZyBza2VsZXRvbi5odG1sXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZnVsbFBhZ2UgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2UgPSB0aGlzOy8vcmVmZXJlbmNlIHRvIHNlbGYgZm9yIGxhdGVyXG4gICAgICAgICAgICBwYWdlLnNjcmlwdHMgPSBbXTsvL21ha2Ugc3VyZSBpdCdzIGVtcHR5LCB3ZSdsbCBzdG9yZSBzY3JpcHQgVVJMcyBpbiB0aGVyZSBsYXRlclxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgbmV3RG9jTWFpblBhcmVudCA9ICQoJ2lmcmFtZSNza2VsZXRvbicpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZW1wdHkgb3V0IHRoZSBza2VsZXRvbiBmaXJzdFxuICAgICAgICAgICAgJCgnaWZyYW1lI3NrZWxldG9uJykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5odG1sKCcnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIHNjcmlwdCB0YWdzXG4gICAgICAgICAgICAkKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoICdzY3JpcHQnICkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdmFyIHRoZUNvbnRlbnRzO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZ3JhYiB0aGUgYmxvY2sgY29udGVudFxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmJsb2Nrc1tpXS5zYW5kYm94ICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMgPSAkKCcjc2FuZGJveGVzICMnK3RoaXMuYmxvY2tzW2ldLnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMgPSAkKHRoaXMuYmxvY2tzW2ldLmZyYW1lRG9jdW1lbnQuYm9keSkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIHZpZGVvIGZyYW1lQ292ZXJzXG4gICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCgnLmZyYW1lQ292ZXInKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSB2aWRlbyBmcmFtZVdyYXBwZXJzXG4gICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCgnLnZpZGVvV3JhcHBlcicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHZhciBjbnQgPSAkKHRoaXMpLmNvbnRlbnRzKCk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykucmVwbGFjZVdpdGgoY250KTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgc3R5bGUgbGVmdG92ZXJzIGZyb20gdGhlIHN0eWxlIGVkaXRvclxuICAgICAgICAgICAgICAgIGZvciggdmFyIGtleSBpbiBiQ29uZmlnLmVkaXRhYmxlSXRlbXMgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoIGtleSApLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdkYXRhLXNlbGVjdG9yJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY3NzKCdvdXRsaW5lJywgJycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ291dGxpbmUtb2Zmc2V0JywgJycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ2N1cnNvcicsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykuYXR0cignc3R5bGUnKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgc3R5bGUgbGVmdG92ZXJzIGZyb20gdGhlIGNvbnRlbnQgZWRpdG9yXG4gICAgICAgICAgICAgICAgZm9yICggdmFyIHggPSAwOyB4IDwgYkNvbmZpZy5lZGl0YWJsZUNvbnRlbnQubGVuZ3RoOyArK3gpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzLmZpbmQoIGJDb25maWcuZWRpdGFibGVDb250ZW50W3hdICkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUF0dHIoJ2RhdGEtc2VsZWN0b3InKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2FwcGVuZCB0byBET00gaW4gdGhlIHNrZWxldG9uXG4gICAgICAgICAgICAgICAgbmV3RG9jTWFpblBhcmVudC5hcHBlbmQoICQodGhlQ29udGVudHMuaHRtbCgpKSApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZG8gd2UgbmVlZCB0byBpbmplY3QgYW55IHNjcmlwdHM/XG4gICAgICAgICAgICAgICAgdmFyIHNjcmlwdHMgPSAkKHRoaXMuYmxvY2tzW2ldLmZyYW1lRG9jdW1lbnQuYm9keSkuZmluZCgnc2NyaXB0Jyk7XG4gICAgICAgICAgICAgICAgdmFyIHRoZUlmcmFtZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwic2tlbGV0b25cIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBzY3JpcHRzLnNpemUoKSA+IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBzY3JpcHRzLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHNjcmlwdDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykudGV4dCgpICE9PSAnJyApIHsvL3NjcmlwdCB0YWdzIHdpdGggY29udGVudFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdCA9IHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzY3JpcHRcIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQuaW5uZXJIVE1MID0gJCh0aGlzKS50ZXh0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChzY3JpcHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoICQodGhpcykuYXR0cignc3JjJykgIT09IG51bGwgJiYgcGFnZS5zY3JpcHRzLmluZGV4T2YoJCh0aGlzKS5hdHRyKCdzcmMnKSkgPT09IC0xICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vdXNlIGluZGV4T2YgdG8gbWFrZSBzdXJlIGVhY2ggc2NyaXB0IG9ubHkgYXBwZWFycyBvbiB0aGUgcHJvZHVjZWQgcGFnZSBvbmNlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0ID0gdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNjcmlwdFwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQudHlwZSA9ICd0ZXh0L2phdmFzY3JpcHQnO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC5zcmMgPSAkKHRoaXMpLmF0dHIoJ3NyYycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc2NyaXB0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYWdlLnNjcmlwdHMucHVzaCgkKHRoaXMpLmF0dHIoJ3NyYycpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBDaGVja3MgaWYgYWxsIGJsb2NrcyBvbiB0aGlzIHBhZ2UgaGF2ZSBmaW5pc2hlZCBsb2FkaW5nXG4gICAgICAgICovXG4gICAgICAgIHRoaXMubG9hZGVkID0gZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPHRoaXMuYmxvY2tzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCAhdGhpcy5ibG9ja3NbaV0ubG9hZGVkICkgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNsZWFyIG91dCB0aGlzIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jbGVhciA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYmxvY2sgPSB0aGlzLmJsb2Nrcy5wb3AoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgd2hpbGUoIGJsb2NrICE9PSB1bmRlZmluZWQgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suZGVsZXRlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2sgPSB0aGlzLmJsb2Nrcy5wb3AoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEhlaWdodCBhZGp1c3RtZW50IGZvciBhbGwgYmxvY2tzIG9uIHRoZSBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgZm9yICggdmFyIGkgPSAwOyBpIDwgdGhpcy5ibG9ja3MubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5ibG9ja3NbaV0uaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG4gICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8vbG9vcCB0aHJvdWdoIHRoZSBmcmFtZXMvYmxvY2tzXG4gICAgICAgIFxuICAgICAgICBpZiggcGFnZS5oYXNPd25Qcm9wZXJ0eSgnYmxvY2tzJykgKSB7XG4gICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCBwYWdlLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vY3JlYXRlIG5ldyBCbG9ja1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIG5ld0Jsb2NrID0gbmV3IEJsb2NrKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBwYWdlLmJsb2Nrc1t4XS5zcmMgPSBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvZ2V0ZnJhbWUvXCIrcGFnZS5ibG9ja3NbeF0uZnJhbWVzX2lkO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2FuZGJveGVkIGJsb2NrP1xuICAgICAgICAgICAgICAgIGlmKCBwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfc2FuZGJveCA9PT0gJzEnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLnNhbmRib3ggPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5zYW5kYm94X2xvYWRlciA9IHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19sb2FkZXJmdW5jdGlvbjtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgbmV3QmxvY2suZnJhbWVJRCA9IHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19pZDtcbiAgICAgICAgICAgICAgICBpZiAoIHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19nbG9iYWwgPT09ICcxJyApIG5ld0Jsb2NrLmdsb2JhbCA9IHRydWU7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlUGFyZW50TEkocGFnZS5ibG9ja3NbeF0uZnJhbWVzX2hlaWdodCk7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlRnJhbWUocGFnZS5ibG9ja3NbeF0pO1xuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZUZyYW1lQ292ZXIoKTtcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5pbnNlcnRCbG9ja0ludG9Eb20odGhpcy5wYXJlbnRVTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vYWRkIHRoZSBibG9jayB0byB0aGUgbmV3IHBhZ2VcbiAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrcy5wdXNoKG5ld0Jsb2NrKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgICAgIFxuICAgICAgICAvL2FkZCB0aGlzIHBhZ2UgdG8gdGhlIHNpdGUgb2JqZWN0XG4gICAgICAgIHNpdGUuc2l0ZVBhZ2VzLnB1c2goIHRoaXMgKTtcbiAgICAgICAgXG4gICAgICAgIC8vcGxhbnQgdGhlIG5ldyBVTCBpbiB0aGUgRE9NIChvbiB0aGUgY2FudmFzKVxuICAgICAgICBzaXRlLmRpdkNhbnZhcy5hcHBlbmRDaGlsZCh0aGlzLnBhcmVudFVMKTtcbiAgICAgICAgXG4gICAgICAgIC8vbWFrZSB0aGUgYmxvY2tzL2ZyYW1lcyBpbiBlYWNoIHBhZ2Ugc29ydGFibGVcbiAgICAgICAgXG4gICAgICAgIHZhciB0aGVQYWdlID0gdGhpcztcbiAgICAgICAgXG4gICAgICAgICQodGhpcy5wYXJlbnRVTCkuc29ydGFibGUoe1xuICAgICAgICAgICAgcmV2ZXJ0OiB0cnVlLFxuICAgICAgICAgICAgcGxhY2Vob2xkZXI6IFwiZHJvcC1ob3ZlclwiLFxuICAgICAgICAgICAgaGFuZGxlOiAnLmRyYWdCbG9jaycsXG4gICAgICAgICAgICBjYW5jZWw6ICcnLFxuICAgICAgICAgICAgc3RvcDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHNpdGUubW92ZU1vZGUoJ29mZicpO1xuICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgaWYgKCAhc2l0ZS5sb2FkZWQoKSApIGJ1aWxkZXJVSS5jYW52YXNMb2FkaW5nKCdvbicpO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGJlZm9yZVN0b3A6IGZ1bmN0aW9uKGV2ZW50LCB1aSl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy90ZW1wbGF0ZSBvciByZWd1bGFyIGJsb2NrP1xuICAgICAgICAgICAgICAgIHZhciBhdHRyID0gdWkuaXRlbS5hdHRyKCdkYXRhLWZyYW1lcycpO1xuXG4gICAgICAgICAgICAgICAgdmFyIG5ld0Jsb2NrO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGF0dHIgIT09IHR5cGVvZiB1bmRlZmluZWQgJiYgYXR0ciAhPT0gZmFsc2UpIHsvL3RlbXBsYXRlLCBidWlsZCBpdFxuICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJCgnI3N0YXJ0JykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NsZWFyIG91dCBhbGwgYmxvY2tzIG9uIHRoaXMgcGFnZSAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlUGFnZS5jbGVhcigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9jcmVhdGUgdGhlIG5ldyBmcmFtZXNcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZyYW1lSURzID0gdWkuaXRlbS5hdHRyKCdkYXRhLWZyYW1lcycpLnNwbGl0KCctJyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBoZWlnaHRzID0gdWkuaXRlbS5hdHRyKCdkYXRhLWhlaWdodHMnKS5zcGxpdCgnLScpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgdXJscyA9IHVpLml0ZW0uYXR0cignZGF0YS1vcmlnaW5hbHVybHMnKS5zcGxpdCgnLScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgZnJhbWVJRHMubGVuZ3RoOyB4KyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2sgPSBuZXcgQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZVBhcmVudExJKGhlaWdodHNbeF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZnJhbWVEYXRhID0ge307XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5zcmMgPSBhcHBVSS5zaXRlVXJsKydzaXRlcy9nZXRmcmFtZS8nK2ZyYW1lSURzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19vcmlnaW5hbF91cmwgPSBhcHBVSS5zaXRlVXJsKydzaXRlcy9nZXRmcmFtZS8nK2ZyYW1lSURzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19oZWlnaHQgPSBoZWlnaHRzW3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZSggZnJhbWVEYXRhICk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZUNvdmVyKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5pbnNlcnRCbG9ja0ludG9Eb20odGhlUGFnZS5wYXJlbnRVTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vYWRkIHRoZSBibG9jayB0byB0aGUgbmV3IHBhZ2VcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZVBhZ2UuYmxvY2tzLnB1c2gobmV3QmxvY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2Ryb3BwZWQgZWxlbWVudCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3NldCB0aGUgdGVtcGF0ZUlEXG4gICAgICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS50ZW1wbGF0ZUlEID0gdWkuaXRlbS5hdHRyKCdkYXRhLXBhZ2VpZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vbWFrZSBzdXJlIG5vdGhpbmcgZ2V0cyBkcm9wcGVkIGluIHRoZSBsc2l0XG4gICAgICAgICAgICAgICAgICAgIHVpLml0ZW0uaHRtbChudWxsKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0ZSBkcmFnIHBsYWNlIGhvbGRlclxuICAgICAgICAgICAgICAgICAgICAkKCdib2R5IC51aS1zb3J0YWJsZS1oZWxwZXInKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHsvL3JlZ3VsYXIgYmxvY2tcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9hcmUgd2UgZGVhbGluZyB3aXRoIGEgbmV3IGJsb2NrIGJlaW5nIGRyb3BwZWQgb250byB0aGUgY2FudmFzLCBvciBhIHJlb3JkZXJpbmcgb2cgYmxvY2tzIGFscmVhZHkgb24gdGhlIGNhbnZhcz9cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnLmZyYW1lQ292ZXIgPiBidXR0b24nKS5zaXplKCkgPiAwICkgey8vcmUtb3JkZXJpbmcgb2YgYmxvY2tzIG9uIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vbm8gbmVlZCB0byBjcmVhdGUgYSBuZXcgYmxvY2sgb2JqZWN0LCB3ZSBzaW1wbHkgbmVlZCB0byBtYWtlIHN1cmUgdGhlIHBvc2l0aW9uIG9mIHRoZSBleGlzdGluZyBibG9jayBpbiB0aGUgU2l0ZSBvYmplY3RcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vaXMgY2hhbmdlZCB0byByZWZsZWN0IHRoZSBuZXcgcG9zaXRpb24gb2YgdGhlIGJsb2NrIG9uIHRoIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBmcmFtZUlEID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld1BvcyA9IHVpLml0ZW0uaW5kZXgoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2Uuc2V0UG9zaXRpb24oZnJhbWVJRCwgbmV3UG9zKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHsvL25ldyBibG9jayBvbiBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9uZXcgYmxvY2sgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2sgPSBuZXcgQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jay5wbGFjZU9uQ2FudmFzKHVpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBzdGFydDogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuXG4gICAgICAgICAgICAgICAgc2l0ZS5tb3ZlTW9kZSgnb24nKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnLmZyYW1lQ292ZXInKS5zaXplKCkgIT09IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS5mcmFtZUNvbnRlbnRzID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmh0bWwoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBvdmVyOiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjc3RhcnQnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBcbiAgICAgICAgLy9hZGQgdG8gdGhlIHBhZ2VzIG1lbnVcbiAgICAgICAgdGhpcy5tZW51SXRlbSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0xJJyk7XG4gICAgICAgIHRoaXMubWVudUl0ZW0uaW5uZXJIVE1MID0gdGhpcy5wYWdlTWVudVRlbXBsYXRlO1xuICAgICAgICBcbiAgICAgICAgJCh0aGlzLm1lbnVJdGVtKS5maW5kKCdhOmZpcnN0JykudGV4dChwYWdlTmFtZSkuYXR0cignaHJlZicsICcjcGFnZScrY291bnRlcik7XG4gICAgICAgIFxuICAgICAgICB2YXIgdGhlTGluayA9ICQodGhpcy5tZW51SXRlbSkuZmluZCgnYTpmaXJzdCcpLmdldCgwKTtcbiAgICAgICAgXG4gICAgICAgIC8vYmluZCBzb21lIGV2ZW50c1xuICAgICAgICB0aGlzLm1lbnVJdGVtLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLmZpbGVFZGl0JykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5maWxlU2F2ZScpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EuZmlsZURlbCcpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICBcbiAgICAgICAgLy9hZGQgdG8gdGhlIHBhZ2UgbGluayBkcm9wZG93blxuICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0uc2V0QXR0cmlidXRlKCd2YWx1ZScsIHBhZ2VOYW1lK1wiLmh0bWxcIik7XG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0udGV4dCA9IHBhZ2VOYW1lO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICBidWlsZGVyVUkuZHJvcGRvd25QYWdlTGlua3MuYXBwZW5kQ2hpbGQoIHRoaXMubGlua3NEcm9wZG93bkl0ZW0gKTtcbiAgICAgICAgXG4gICAgICAgIHNpdGUucGFnZXNNZW51LmFwcGVuZENoaWxkKHRoaXMubWVudUl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICB9XG4gICAgXG4gICAgUGFnZS5wcm90b3R5cGUuaGFuZGxlRXZlbnQgPSBmdW5jdGlvbihldmVudCkge1xuICAgICAgICBzd2l0Y2ggKGV2ZW50LnR5cGUpIHtcbiAgICAgICAgICAgIGNhc2UgXCJjbGlja1wiOiBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbGVFZGl0JykgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWRpdFBhZ2VOYW1lKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZmlsZVNhdmUnKSApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy51cGRhdGVQYWdlTmFtZUV2ZW50KGV2ZW50LnRhcmdldCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaWxlRGVsJykgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB2YXIgdGhlUGFnZSA9IHRoaXM7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkubW9kYWwoJ3Nob3cnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkub2ZmKCdjbGljaycsICcjZGVsZXRlUGFnZUNvbmZpcm0nKS5vbignY2xpY2snLCAnI2RlbGV0ZVBhZ2VDb25maXJtJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZVBhZ2UuZGVsZXRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlUGFnZSkubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgfTtcblxuXG4gICAgLypcbiAgICAgICAgQmxvY2sgY29uc3RydWN0b3JcbiAgICAqL1xuICAgIGZ1bmN0aW9uIEJsb2NrICgpIHtcbiAgICAgICAgXG4gICAgICAgIHRoaXMuZnJhbWVJRCA9IDA7XG4gICAgICAgIHRoaXMubG9hZGVkID0gZmFsc2U7XG4gICAgICAgIHRoaXMuc2FuZGJveCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnNhbmRib3hfbG9hZGVyID0gJyc7XG4gICAgICAgIHRoaXMuc3RhdHVzID0gJyc7Ly8nJywgJ2NoYW5nZWQnIG9yICduZXcnXG4gICAgICAgIHRoaXMuZ2xvYmFsID0gZmFsc2U7XG4gICAgICAgIHRoaXMub3JpZ2luYWxVcmwgPSAnJztcbiAgICAgICAgXG4gICAgICAgIHRoaXMucGFyZW50TEkgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZUNvdmVyID0ge307XG4gICAgICAgIHRoaXMuZnJhbWUgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50ID0ge307XG4gICAgICAgIHRoaXMuZnJhbWVIZWlnaHQgPSAwO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5hbm5vdCA9IHt9O1xuICAgICAgICB0aGlzLmFubm90VGltZW91dCA9IHt9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNyZWF0ZXMgdGhlIHBhcmVudCBjb250YWluZXIgKExJKVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNyZWF0ZVBhcmVudExJID0gZnVuY3Rpb24oaGVpZ2h0KSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMSScpO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgIC8vdGhpcy5wYXJlbnRMSS5zZXRBdHRyaWJ1dGUoJ3N0eWxlJywgJ2hlaWdodDogJytoZWlnaHQrJ3B4Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjcmVhdGVzIHRoZSBpZnJhbWUgb24gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lID0gZnVuY3Rpb24oZnJhbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5mcmFtZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0lGUkFNRScpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2ZyYW1lYm9yZGVyJywgMCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnc2Nyb2xsaW5nJywgMCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnc3JjJywgZnJhbWUuc3JjKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLW9yaWdpbmFsdXJsJywgZnJhbWUuZnJhbWVzX29yaWdpbmFsX3VybCk7XG4gICAgICAgICAgICB0aGlzLm9yaWdpbmFsVXJsID0gZnJhbWUuZnJhbWVzX29yaWdpbmFsX3VybDtcbiAgICAgICAgICAgIC8vdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtaGVpZ2h0JywgZnJhbWUuZnJhbWVzX2hlaWdodCk7XG4gICAgICAgICAgICAvL3RoaXMuZnJhbWVIZWlnaHQgPSBmcmFtZS5mcmFtZXNfaGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZnJhbWUpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuZGJveD9cbiAgICAgICAgICAgIGlmKCB0aGlzLnNhbmRib3ggIT09IGZhbHNlICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdkYXRhLWxvYWRlcmZ1bmN0aW9uJywgdGhpcy5zYW5kYm94X2xvYWRlcik7XG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtc2FuZGJveCcsIHRoaXMuc2FuZGJveCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZWNyZWF0ZSB0aGUgc2FuZGJveGVkIGlmcmFtZSBlbHNld2hlcmVcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveGVkRnJhbWUgPSAkKCc8aWZyYW1lIHNyYz1cIicrZnJhbWUuc3JjKydcIiBpZD1cIicrdGhpcy5zYW5kYm94KydcIiBzYW5kYm94PVwiYWxsb3ctc2FtZS1vcmlnaW5cIj48L2lmcmFtZT4nKTtcbiAgICAgICAgICAgICAgICAkKCcjc2FuZGJveGVzJykuYXBwZW5kKCBzYW5kYm94ZWRGcmFtZSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGluc2VydCB0aGUgaWZyYW1lIGludG8gdGhlIERPTSBvbiB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaW5zZXJ0QmxvY2tJbnRvRG9tID0gZnVuY3Rpb24odGhlVUwpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCh0aGlzLmZyYW1lKTtcbiAgICAgICAgICAgIHRoZVVMLmFwcGVuZENoaWxkKCB0aGlzLnBhcmVudExJICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIHRoaXMsIGZhbHNlKTtcblxuICAgICAgICAgICAgYnVpbGRlclVJLmNhbnZhc0xvYWRpbmcoJ29uJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2V0cyB0aGUgZnJhbWUgZG9jdW1lbnQgZm9yIHRoZSBibG9jaydzIGlmcmFtZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnNldEZyYW1lRG9jdW1lbnQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zZXQgdGhlIGZyYW1lIGRvY3VtZW50IGFzIHdlbGxcbiAgICAgICAgICAgIGlmKCB0aGlzLmZyYW1lLmNvbnRlbnREb2N1bWVudCApIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQgPSB0aGlzLmZyYW1lLmNvbnRlbnREb2N1bWVudDsgICBcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50ID0gdGhpcy5mcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3RoaXMuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY3JlYXRlcyB0aGUgZnJhbWUgY292ZXIgYW5kIGJsb2NrIGFjdGlvbiBidXR0b25cbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jcmVhdGVGcmFtZUNvdmVyID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnVpbGQgdGhlIGZyYW1lIGNvdmVyIGFuZCBibG9jayBhY3Rpb24gYnV0dG9uc1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuY2xhc3NMaXN0LmFkZCgnZnJhbWVDb3ZlcicpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmNsYXNzTGlzdC5hZGQoJ2ZyZXNoJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGRlbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgZGVsQnV0dG9uLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnYnRuIGJ0bi1pbnZlcnNlIGJ0bi1zbSBkZWxldGVCbG9jaycpO1xuICAgICAgICAgICAgZGVsQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGRlbEJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmdWktdHJhc2hcIj48L2k+IDxzcGFuPnJlbW92ZTwvc3Bhbj4nO1xuICAgICAgICAgICAgZGVsQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciByZXNldEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgcmVzZXRCdXR0b24uc2V0QXR0cmlidXRlKCdjbGFzcycsICdidG4gYnRuLWludmVyc2UgYnRuLXNtIHJlc2V0QmxvY2snKTtcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLXJlZnJlc2hcIj48L2k+IDxzcGFuPnJlc2V0PC9zcGFuPic7XG4gICAgICAgICAgICByZXNldEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgaHRtbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgaHRtbEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gaHRtbEJsb2NrJyk7XG4gICAgICAgICAgICBodG1sQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGh0bWxCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtY29kZVwiPjwvaT4gPHNwYW4+c291cmNlPC9zcGFuPic7XG4gICAgICAgICAgICBodG1sQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuXG4gICAgICAgICAgICB2YXIgZHJhZ0J1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgZHJhZ0J1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gZHJhZ0Jsb2NrJyk7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGRyYWdCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtYXJyb3dzXCI+PC9pPiA8c3Bhbj5Nb3ZlPC9zcGFuPic7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuXG4gICAgICAgICAgICB2YXIgZ2xvYmFsTGFiZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMQUJFTCcpO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuY2xhc3NMaXN0LmFkZCgnY2hlY2tib3gnKTtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmNsYXNzTGlzdC5hZGQoJ3ByaW1hcnknKTtcbiAgICAgICAgICAgIHZhciBnbG9iYWxDaGVja2JveCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0lOUFVUJyk7XG4gICAgICAgICAgICBnbG9iYWxDaGVja2JveC50eXBlID0gJ2NoZWNrYm94JztcbiAgICAgICAgICAgIGdsb2JhbENoZWNrYm94LnNldEF0dHJpYnV0ZSgnZGF0YS10b2dnbGUnLCAnY2hlY2tib3gnKTtcbiAgICAgICAgICAgIGdsb2JhbENoZWNrYm94LmNoZWNrZWQgPSB0aGlzLmdsb2JhbDtcbiAgICAgICAgICAgIGdsb2JhbExhYmVsLmFwcGVuZENoaWxkKGdsb2JhbENoZWNrYm94KTtcbiAgICAgICAgICAgIHZhciBnbG9iYWxUZXh0ID0gZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUoJ0dsb2JhbCcpO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuYXBwZW5kQ2hpbGQoZ2xvYmFsVGV4dCk7XG5cbiAgICAgICAgICAgIHZhciB0cmlnZ2VyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgdHJpZ2dlci5jbGFzc0xpc3QuYWRkKCdmdWktZ2VhcicpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChkZWxCdXR0b24pO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKHJlc2V0QnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChodG1sQnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChkcmFnQnV0dG9uKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZChnbG9iYWxMYWJlbCk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQodHJpZ2dlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmFwcGVuZENoaWxkKHRoaXMuZnJhbWVDb3Zlcik7XG5cbiAgICAgICAgICAgIHZhciB0aGVCbG9jayA9IHRoaXM7XG5cbiAgICAgICAgICAgICQoZ2xvYmFsQ2hlY2tib3gpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICAgICAgdGhlQmxvY2sudG9nZ2xlR2xvYmFsKGUpO1xuXG4gICAgICAgICAgICB9KS5yYWRpb2NoZWNrKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnRvZ2dsZUdsb2JhbCA9IGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgIGlmICggZS5jdXJyZW50VGFyZ2V0LmNoZWNrZWQgKSB0aGlzLmdsb2JhbCA9IHRydWU7XG4gICAgICAgICAgICBlbHNlIHRoaXMuZ2xvYmFsID0gZmFsc2U7XG5cbiAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgY29uc29sZS5sb2codGhpcyk7XG5cbiAgICAgICAgfTtcblxuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBhdXRvbWF0aWNhbGx5IGNvcnJlY3RzIHRoZSBoZWlnaHQgb2YgdGhlIGJsb2NrJ3MgaWZyYW1lIGRlcGVuZGluZyBvbiBpdHMgY29udGVudFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmhlaWdodEFkanVzdG1lbnQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYgKCBPYmplY3Qua2V5cyh0aGlzLmZyYW1lRG9jdW1lbnQpLmxlbmd0aCAhPT0gMCApIHtcblxuICAgICAgICAgICAgICAgIHZhciBoZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgIGJvZHlIZWlnaHQgPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5vZmZzZXRIZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgIHBhZ2VDb250YWluZXJIZWlnaHQgPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5vZmZzZXRIZWlnaHQ7XG5cbiAgICAgICAgICAgICAgICBpZiAoIGJvZHlIZWlnaHQgPiBwYWdlQ29udGFpbmVySGVpZ2h0ICYmICF0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5jbGFzc0xpc3QuY29udGFpbnMoIGJDb25maWcuYm9keVBhZGRpbmdDbGFzcyApICkgaGVpZ2h0ID0gcGFnZUNvbnRhaW5lckhlaWdodDtcbiAgICAgICAgICAgICAgICBlbHNlIGhlaWdodCA9IGJvZHlIZWlnaHQ7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lLnN0eWxlLmhlaWdodCA9IGhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5zdHlsZS5oZWlnaHQgPSBoZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgICAgIC8vdGhpcy5mcmFtZUNvdmVyLnN0eWxlLmhlaWdodCA9IGhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZUhlaWdodCA9IGhlaWdodDtcblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZXMgYSBibG9ja1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmRlbGV0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBmcm9tIERPTS9jYW52YXMgd2l0aCBhIG5pY2UgYW5pbWF0aW9uXG4gICAgICAgICAgICAkKHRoaXMuZnJhbWUucGFyZW50Tm9kZSkuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5pc0VtcHR5KCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgZnJvbSBibG9ja3MgYXJyYXkgaW4gdGhlIGFjdGl2ZSBwYWdlXG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UuZGVsZXRlQmxvY2sodGhpcyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuYm94XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5iZG94ICkge1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCB0aGlzLnNhbmRib3ggKS5yZW1vdmUoKTsgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbGVtZW50IHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcmVzZXRzIGEgYmxvY2sgdG8gaXQncyBvcmlnbmFsIHN0YXRlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMucmVzZXQgPSBmdW5jdGlvbiAoZmlyZUV2ZW50KSB7XG5cbiAgICAgICAgICAgIGlmICggdHlwZW9mIGZpcmVFdmVudCA9PT0gJ3VuZGVmaW5lZCcpIGZpcmVFdmVudCA9IHRydWU7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVzZXQgZnJhbWUgYnkgcmVsb2FkaW5nIGl0XG4gICAgICAgICAgICB0aGlzLmZyYW1lLmNvbnRlbnRXaW5kb3cubG9jYXRpb24gPSB0aGlzLmZyYW1lLmdldEF0dHJpYnV0ZSgnZGF0YS1vcmlnaW5hbHVybCcpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NhbmRib3g/XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94RnJhbWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLnNhbmRib3gpLmNvbnRlbnRXaW5kb3cubG9jYXRpb24ucmVsb2FkKCk7ICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbGVtZW50IHdhcyBkZWxldGVkLCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb24nKTtcblxuICAgICAgICAgICAgaWYgKCBmaXJlRXZlbnQgKSBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHRoaXMsICdyZWxvYWQnKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBsYXVuY2hlcyB0aGUgc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc291cmNlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vaGlkZSB0aGUgaWZyYW1lXG4gICAgICAgICAgICB0aGlzLmZyYW1lLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGlzYWJsZSBzb3J0YWJsZSBvbiB0aGUgcGFyZW50TElcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlKS5zb3J0YWJsZSgnZGlzYWJsZScpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1aWx0IGVkaXRvciBlbGVtZW50XG4gICAgICAgICAgICB2YXIgdGhlRWRpdG9yID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICB0aGVFZGl0b3IuY2xhc3NMaXN0LmFkZCgnYWNlRWRpdG9yJyk7XG4gICAgICAgICAgICAkKHRoZUVkaXRvcikudW5pcXVlSWQoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCh0aGVFZGl0b3IpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2J1aWxkIGFuZCBhcHBlbmQgZXJyb3IgZHJhd2VyXG4gICAgICAgICAgICB2YXIgbmV3TEkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdMSScpO1xuICAgICAgICAgICAgdmFyIGVycm9yRHJhd2VyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICBlcnJvckRyYXdlci5jbGFzc0xpc3QuYWRkKCdlcnJvckRyYXdlcicpO1xuICAgICAgICAgICAgZXJyb3JEcmF3ZXIuc2V0QXR0cmlidXRlKCdpZCcsICdkaXZfZXJyb3JEcmF3ZXInKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLmlubmVySFRNTCA9ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4teHMgYnRuLWVtYm9zc2VkIGJ0bi1kZWZhdWx0IGJ1dHRvbl9jbGVhckVycm9yRHJhd2VyXCIgaWQ9XCJidXR0b25fY2xlYXJFcnJvckRyYXdlclwiPkNMRUFSPC9idXR0b24+JztcbiAgICAgICAgICAgIG5ld0xJLmFwcGVuZENoaWxkKGVycm9yRHJhd2VyKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvbicpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlLmluc2VydEJlZm9yZShuZXdMSSwgdGhpcy5wYXJlbnRMSS5uZXh0U2libGluZyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGFjZS5jb25maWcuc2V0KFwiYmFzZVBhdGhcIiwgXCIvanMvdmVuZG9yL2FjZVwiKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHRoZUlkID0gdGhlRWRpdG9yLmdldEF0dHJpYnV0ZSgnaWQnKTtcbiAgICAgICAgICAgIHZhciBlZGl0b3IgPSBhY2UuZWRpdCggdGhlSWQgKTtcblxuICAgICAgICAgICAgLy9lZGl0b3IuZ2V0U2Vzc2lvbigpLnNldFVzZVdyYXBNb2RlKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZUNvbnRhaW5lciA9IHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKTtcbiAgICAgICAgICAgIHZhciB0aGVIVE1MID0gcGFnZUNvbnRhaW5lci5pbm5lckhUTUw7XG4gICAgICAgICAgICBcblxuICAgICAgICAgICAgZWRpdG9yLnNldFZhbHVlKCB0aGVIVE1MICk7XG4gICAgICAgICAgICBlZGl0b3Iuc2V0VGhlbWUoXCJhY2UvdGhlbWUvdHdpbGlnaHRcIik7XG4gICAgICAgICAgICBlZGl0b3IuZ2V0U2Vzc2lvbigpLnNldE1vZGUoXCJhY2UvbW9kZS9odG1sXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYmxvY2sgPSB0aGlzO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGVkaXRvci5nZXRTZXNzaW9uKCkub24oXCJjaGFuZ2VBbm5vdGF0aW9uXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suYW5ub3QgPSBlZGl0b3IuZ2V0U2Vzc2lvbigpLmdldEFubm90YXRpb25zKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KGJsb2NrLmFubm90VGltZW91dCk7XG5cbiAgICAgICAgICAgICAgICB2YXIgdGltZW91dENvdW50O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCAkKCcjZGl2X2Vycm9yRHJhd2VyIHAnKS5zaXplKCkgPT09IDAgKSB7XG4gICAgICAgICAgICAgICAgICAgIHRpbWVvdXRDb3VudCA9IGJDb25maWcuc291cmNlQ29kZUVkaXRTeW50YXhEZWxheTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aW1lb3V0Q291bnQgPSAxMDA7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGJsb2NrLmFubm90VGltZW91dCA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBmb3IgKHZhciBrZXkgaW4gYmxvY2suYW5ub3Qpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChibG9jay5hbm5vdC5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiggYmxvY2suYW5ub3Rba2V5XS50ZXh0ICE9PSBcIlN0YXJ0IHRhZyBzZWVuIHdpdGhvdXQgc2VlaW5nIGEgZG9jdHlwZSBmaXJzdC4gRXhwZWN0ZWQgZS5nLiA8IURPQ1RZUEUgaHRtbD4uXCIgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdMaW5lID0gJCgnPHA+PC9wPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3S2V5ID0gJCgnPGI+JytibG9jay5hbm5vdFtrZXldLnR5cGUrJzogPC9iPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3SW5mbyA9ICQoJzxzcGFuPiAnK2Jsb2NrLmFubm90W2tleV0udGV4dCArIFwib24gbGluZSBcIiArIFwiIDxiPlwiICsgYmxvY2suYW5ub3Rba2V5XS5yb3crJzwvYj48L3NwYW4+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0xpbmUuYXBwZW5kKCBuZXdLZXkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3TGluZS5hcHBlbmQoIG5ld0luZm8gKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5hcHBlbmQoIG5ld0xpbmUgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5jc3MoJ2Rpc3BsYXknKSA9PT0gJ25vbmUnICYmICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5maW5kKCdwJykuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNkaXZfZXJyb3JEcmF3ZXInKS5zbGlkZURvd24oKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSwgdGltZW91dENvdW50KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnV0dG9uc1xuICAgICAgICAgICAgdmFyIGNhbmNlbEJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0JVVFRPTicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4nKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4tZGFuZ2VyJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uY2xhc3NMaXN0LmFkZCgnZWRpdENhbmNlbEJ1dHRvbicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1zbScpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZ1aS1jcm9zc1wiPjwvaT4gPHNwYW4+Q2FuY2VsPC9zcGFuPic7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBzYXZlQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1wcmltYXJ5Jyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2VkaXRTYXZlQnV0dG9uJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1zbScpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmdWktY2hlY2tcIj48L2k+IDxzcGFuPlNhdmU8L3NwYW4+JztcbiAgICAgICAgICAgIHNhdmVCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBidXR0b25XcmFwcGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG4gICAgICAgICAgICBidXR0b25XcmFwcGVyLmNsYXNzTGlzdC5hZGQoJ2VkaXRvckJ1dHRvbnMnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgYnV0dG9uV3JhcHBlci5hcHBlbmRDaGlsZCggY2FuY2VsQnV0dG9uICk7XG4gICAgICAgICAgICBidXR0b25XcmFwcGVyLmFwcGVuZENoaWxkKCBzYXZlQnV0dG9uICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuYXBwZW5kQ2hpbGQoIGJ1dHRvbldyYXBwZXIgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgYnVpbGRlclVJLmFjZUVkaXRvcnNbIHRoZUlkIF0gPSBlZGl0b3I7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2FuY2VscyB0aGUgYmxvY2sgc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY2FuY2VsU291cmNlQmxvY2sgPSBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgLy9lbmFibGUgZHJhZ2dhYmxlIG9uIHRoZSBMSVxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnBhcmVudE5vZGUpLnNvcnRhYmxlKCdlbmFibGUnKTtcblx0XHRcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlcnJvckRyYXdlclxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLm5leHRTaWJsaW5nKS5yZW1vdmUoKTtcbiAgICAgICAgXG4gICAgICAgICAgICAvL2RlbGV0ZSB0aGUgZWRpdG9yXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5hY2VFZGl0b3InKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICQodGhpcy5mcmFtZSkuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZWRpdG9yQnV0dG9ucycpKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlcyB0aGUgYmxvY2tzIHNvdXJjZSBjb2RlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2F2ZVNvdXJjZUJsb2NrID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZW5hYmxlIGRyYWdnYWJsZSBvbiB0aGUgTElcbiAgICAgICAgICAgICQodGhpcy5wYXJlbnRMSS5wYXJlbnROb2RlKS5zb3J0YWJsZSgnZW5hYmxlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciB0aGVJZCA9IHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmFjZUVkaXRvcicpLmdldEF0dHJpYnV0ZSgnaWQnKTtcbiAgICAgICAgICAgIHZhciB0aGVDb250ZW50ID0gYnVpbGRlclVJLmFjZUVkaXRvcnNbdGhlSWRdLmdldFZhbHVlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlcnJvckRyYXdlclxuICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2Rpdl9lcnJvckRyYXdlcicpLnBhcmVudE5vZGUucmVtb3ZlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlZGl0b3JcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmFjZUVkaXRvcicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3VwZGF0ZSB0aGUgZnJhbWUncyBjb250ZW50XG4gICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvciggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuaW5uZXJIVE1MID0gdGhlQ29udGVudDtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuZGJveGVkP1xuICAgICAgICAgICAgaWYoIHRoaXMuc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgc2FuZGJveEZyYW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIHRoaXMuc2FuZGJveCApO1xuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94RnJhbWVEb2N1bWVudCA9IHNhbmRib3hGcmFtZS5jb250ZW50RG9jdW1lbnQgfHwgc2FuZGJveEZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYnVpbGRlclVJLnRlbXBGcmFtZSA9IHNhbmRib3hGcmFtZTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBzYW5kYm94RnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5pbm5lckhUTUwgPSB0aGVDb250ZW50O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2RvIHdlIG5lZWQgdG8gZXhlY3V0ZSBhIGxvYWRlciBmdW5jdGlvbj9cbiAgICAgICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94X2xvYWRlciAhPT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvKlxuICAgICAgICAgICAgICAgICAgICB2YXIgY29kZVRvRXhlY3V0ZSA9IFwic2FuZGJveEZyYW1lLmNvbnRlbnRXaW5kb3cuXCIrdGhpcy5zYW5kYm94X2xvYWRlcitcIigpXCI7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0bXBGdW5jID0gbmV3IEZ1bmN0aW9uKGNvZGVUb0V4ZWN1dGUpO1xuICAgICAgICAgICAgICAgICAgICB0bXBGdW5jKCk7XG4gICAgICAgICAgICAgICAgICAgICovXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5lZGl0b3JCdXR0b25zJykpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hZGp1c3QgaGVpZ2h0IG9mIHRoZSBmcmFtZVxuICAgICAgICAgICAgdGhpcy5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbmV3IHBhZ2UgYWRkZWQsIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYmxvY2sgaGFzIGNoYW5nZWRcbiAgICAgICAgICAgIHRoaXMuc3RhdHVzID0gJ2NoYW5nZWQnO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHRoaXMsICdjaGFuZ2UnKTtcbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrTG9hZGVkJywgdGhpcyk7XG5cbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2xlYXJzIG91dCB0aGUgZXJyb3IgZHJhd2VyXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY2xlYXJFcnJvckRyYXdlciA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcHMgPSB0aGlzLnBhcmVudExJLm5leHRTaWJsaW5nLnF1ZXJ5U2VsZWN0b3JBbGwoJ3AnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSA9IDA7IGkgPCBwcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICBwc1tpXS5yZW1vdmUoKTsgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHRvZ2dsZXMgdGhlIHZpc2liaWxpdHkgb2YgdGhpcyBibG9jaydzIGZyYW1lQ292ZXJcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy50b2dnbGVDb3ZlciA9IGZ1bmN0aW9uKG9uT3JPZmYpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIG9uT3JPZmYgPT09ICdPbicgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuZnJhbWVDb3ZlcicpLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIGlmKCBvbk9yT2ZmID09PSAnT2ZmJyApIHtcbiAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5mcmFtZUNvdmVyJykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXR1cm5zIHRoZSBmdWxsIHNvdXJjZSBjb2RlIG9mIHRoZSBibG9jaydzIGZyYW1lXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZ2V0U291cmNlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBzb3VyY2UgPSBcIjxodG1sPlwiO1xuICAgICAgICAgICAgc291cmNlICs9IHRoaXMuZnJhbWVEb2N1bWVudC5oZWFkLm91dGVySFRNTDtcbiAgICAgICAgICAgIHNvdXJjZSArPSB0aGlzLmZyYW1lRG9jdW1lbnQuYm9keS5vdXRlckhUTUw7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBzb3VyY2U7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcGxhY2VzIGEgZHJhZ2dlZC9kcm9wcGVkIGJsb2NrIGZyb20gdGhlIGxlZnQgc2lkZWJhciBvbnRvIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5wbGFjZU9uQ2FudmFzID0gZnVuY3Rpb24odWkpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9mcmFtZSBkYXRhLCB3ZSdsbCBuZWVkIHRoaXMgYmVmb3JlIG1lc3Npbmcgd2l0aCB0aGUgaXRlbSdzIGNvbnRlbnQgSFRNTFxuICAgICAgICAgICAgdmFyIGZyYW1lRGF0YSA9IHt9LCBhdHRyO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHVpLml0ZW0uZmluZCgnaWZyYW1lJykuc2l6ZSgpID4gMCApIHsvL2lmcmFtZSB0aHVtYm5haWxcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLnNyYyA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19vcmlnaW5hbF91cmwgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ3NyYycpO1xuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfaGVpZ2h0ID0gdWkuaXRlbS5oZWlnaHQoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zYW5kYm94ZWQgYmxvY2s/XG4gICAgICAgICAgICAgICAgYXR0ciA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc2FuZGJveCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGF0dHIgIT09IHR5cGVvZiB1bmRlZmluZWQgJiYgYXR0ciAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94ID0gc2l0ZUJ1aWxkZXJVdGlscy5nZXRSYW5kb21BcmJpdHJhcnkoMTAwMDAsIDEwMDAwMDAwMDApO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNhbmRib3hfbG9hZGVyID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdkYXRhLWxvYWRlcmZ1bmN0aW9uJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHsvL2ltYWdlIHRodW1ibmFpbFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuc3JjID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLXNyY2MnKTtcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX29yaWdpbmFsX3VybCA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1zcmNjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19oZWlnaHQgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtaGVpZ2h0Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3NhbmRib3hlZCBibG9jaz9cbiAgICAgICAgICAgICAgICBhdHRyID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLXNhbmRib3gnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2FuZGJveCA9IHNpdGVCdWlsZGVyVXRpbHMuZ2V0UmFuZG9tQXJiaXRyYXJ5KDEwMDAwLCAxMDAwMDAwMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94X2xvYWRlciA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1sb2FkZXJmdW5jdGlvbicpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9ICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY3JlYXRlIHRoZSBuZXcgYmxvY2sgb2JqZWN0XG4gICAgICAgICAgICB0aGlzLmZyYW1lSUQgPSAwO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSSA9IHVpLml0ZW0uZ2V0KDApO1xuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5pbm5lckhUTUwgPSAnJztcbiAgICAgICAgICAgIHRoaXMuc3RhdHVzID0gJ25ldyc7XG4gICAgICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lKGZyYW1lRGF0YSk7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnN0eWxlLmhlaWdodCA9IHRoaXMuZnJhbWVIZWlnaHQrXCJweFwiO1xuICAgICAgICAgICAgdGhpcy5jcmVhdGVGcmFtZUNvdmVyKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmZyYW1lLmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vaW5zZXJ0IHRoZSBjcmVhdGVkIGlmcmFtZVxuICAgICAgICAgICAgdWkuaXRlbS5hcHBlbmQoJCh0aGlzLmZyYW1lKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2FkZCB0aGUgYmxvY2sgdG8gdGhlIGN1cnJlbnQgcGFnZVxuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLmJsb2Nrcy5zcGxpY2UodWkuaXRlbS5pbmRleCgpLCAwLCB0aGlzKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLnRyaWdnZXIoJ2NhbnZhc3VwZGF0ZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Ryb3BwZWQgZWxlbWVudCwgc28gd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBpbmplY3RzIGV4dGVybmFsIEpTIChkZWZpbmVkIGluIGNvbmZpZy5qcykgaW50byB0aGUgYmxvY2tcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5sb2FkSmF2YXNjcmlwdCA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgdmFyIGksXG4gICAgICAgICAgICAgICAgb2xkLFxuICAgICAgICAgICAgICAgIG5ld1NjcmlwdDtcblxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIG9uZXNcbiAgICAgICAgICAgIG9sZCA9IHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdzY3JpcHQuYnVpbGRlcicpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IG9sZC5sZW5ndGg7IGkrKyApIG9sZFtpXS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgLy9pbmplY3RcbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgYkNvbmZpZy5leHRlcm5hbEpTLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIG5ld1NjcmlwdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ1NDUklQVCcpO1xuICAgICAgICAgICAgICAgIG5ld1NjcmlwdC5jbGFzc0xpc3QuYWRkKCdidWlsZGVyJyk7XG4gICAgICAgICAgICAgICAgbmV3U2NyaXB0LnNyYyA9IGJDb25maWcuZXh0ZXJuYWxKU1tpXTtcblxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdib2R5JykuYXBwZW5kQ2hpbGQobmV3U2NyaXB0KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2hlY2tzIGlmIHRoaXMgYmxvY2sgaGFzIGV4dGVybmFsIHN0eWxlc2hlZXRcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5oYXNFeHRlcm5hbENTUyA9IGZ1bmN0aW9uIChzcmMpIHtcblxuICAgICAgICAgICAgdmFyIGV4dGVybmFsQ3NzLFxuICAgICAgICAgICAgICAgIHg7XG5cbiAgICAgICAgICAgIGV4dGVybmFsQ3NzID0gdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpbmtbaHJlZio9XCInICsgc3JjICsgJ1wiXScpO1xuXG4gICAgICAgICAgICByZXR1cm4gZXh0ZXJuYWxDc3MubGVuZ3RoICE9PSAwO1xuXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgIH1cbiAgICBcbiAgICBCbG9jay5wcm90b3R5cGUuaGFuZGxlRXZlbnQgPSBmdW5jdGlvbihldmVudCkge1xuICAgICAgICBzd2l0Y2ggKGV2ZW50LnR5cGUpIHtcbiAgICAgICAgICAgIGNhc2UgXCJsb2FkXCI6IFxuICAgICAgICAgICAgICAgIHRoaXMuc2V0RnJhbWVEb2N1bWVudCgpO1xuICAgICAgICAgICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCgpO1xuICAgICAgICAgICAgICAgIHRoaXMubG9hZEphdmFzY3JpcHQoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMuZnJhbWVDb3ZlcikucmVtb3ZlQ2xhc3MoJ2ZyZXNoJywgNTAwKTtcblxuICAgICAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrTG9hZGVkJywgdGhpcyk7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmxvYWRlZCA9IHRydWU7XG5cbiAgICAgICAgICAgICAgICBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb2ZmJyk7XG5cbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGNhc2UgXCJjbGlja1wiOlxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciB0aGVCbG9jayA9IHRoaXM7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9maWd1cmUgb3V0IHdoYXQgdG8gZG8gbmV4dFxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdkZWxldGVCbG9jaycpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZGVsZXRlQmxvY2snKSApIHsvL2RlbGV0ZSB0aGlzIGJsb2NrXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZUJsb2NrKS5tb2RhbCgnc2hvdycpOyAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZUJsb2NrKS5vZmYoJ2NsaWNrJywgJyNkZWxldGVCbG9ja0NvbmZpcm0nKS5vbignY2xpY2snLCAnI2RlbGV0ZUJsb2NrQ29uZmlybScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5kZWxldGUoZXZlbnQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVCbG9jaykubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygncmVzZXRCbG9jaycpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygncmVzZXRCbG9jaycpICkgey8vcmVzZXQgdGhlIGJsb2NrXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbFJlc2V0QmxvY2spLm1vZGFsKCdzaG93Jyk7IFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxSZXNldEJsb2NrKS5vZmYoJ2NsaWNrJywgJyNyZXNldEJsb2NrQ29uZmlybScpLm9uKCdjbGljaycsICcjcmVzZXRCbG9ja0NvbmZpcm0nLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2sucmVzZXQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsUmVzZXRCbG9jaykubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnaHRtbEJsb2NrJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdodG1sQmxvY2snKSApIHsvL3NvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suc291cmNlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZWRpdENhbmNlbEJ1dHRvbicpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZWRpdENhbmNlbEJ1dHRvbicpICkgey8vY2FuY2VsIHNvdXJjZSBjb2RlIGVkaXRvclxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suY2FuY2VsU291cmNlQmxvY2soKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0U2F2ZUJ1dHRvbicpIHx8IGV2ZW50LnRhcmdldC5wYXJlbnROb2RlLmNsYXNzTGlzdC5jb250YWlucygnZWRpdFNhdmVCdXR0b24nKSApIHsvL3NhdmUgc291cmNlIGNvZGVcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLnNhdmVTb3VyY2VCbG9jaygpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2J1dHRvbl9jbGVhckVycm9yRHJhd2VyJykgKSB7Ly9jbGVhciBlcnJvciBkcmF3ZXJcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLmNsZWFyRXJyb3JEcmF3ZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICB9XG4gICAgfTtcblxuXG4gICAgLypcbiAgICAgICAgU2l0ZSBvYmplY3QgbGl0ZXJhbFxuICAgICovXG4gICAgLypqc2hpbnQgLVcwMDMgKi9cbiAgICB2YXIgc2l0ZSA9IHtcbiAgICAgICAgXG4gICAgICAgIHBlbmRpbmdDaGFuZ2VzOiBmYWxzZSwgICAgICAvL3BlbmRpbmcgY2hhbmdlcyBvciBubz9cbiAgICAgICAgcGFnZXM6IHt9LCAgICAgICAgICAgICAgICAgIC8vYXJyYXkgY29udGFpbmluZyBhbGwgcGFnZXMsIGluY2x1ZGluZyB0aGUgY2hpbGQgZnJhbWVzLCBsb2FkZWQgZnJvbSB0aGUgc2VydmVyIG9uIHBhZ2UgbG9hZFxuICAgICAgICBpc19hZG1pbjogMCwgICAgICAgICAgICAgICAgLy8wIGZvciBub24tYWRtaW4sIDEgZm9yIGFkbWluXG4gICAgICAgIGRhdGE6IHt9LCAgICAgICAgICAgICAgICAgICAvL2NvbnRhaW5lciBmb3IgYWpheCBsb2FkZWQgc2l0ZSBkYXRhXG4gICAgICAgIHBhZ2VzVG9EZWxldGU6IFtdLCAgICAgICAgICAvL2NvbnRhaW5zIHBhZ2VzIHRvIGJlIGRlbGV0ZWRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgc2l0ZVBhZ2VzOiBbXSwgICAgICAgICAgICAgIC8vdGhpcyBpcyB0aGUgb25seSB2YXIgY29udGFpbmluZyB0aGUgcmVjZW50IGNhbnZhcyBjb250ZW50c1xuICAgICAgICBcbiAgICAgICAgc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXI6IHt9LCAgICAgLy9jb250YWlucyB0aGUgc2l0ZSBkYXRhIHJlYWR5IHRvIGJlIHNlbnQgdG8gdGhlIHNlcnZlclxuICAgICAgICBcbiAgICAgICAgYWN0aXZlUGFnZToge30sICAgICAgICAgICAgIC8vaG9sZHMgYSByZWZlcmVuY2UgdG8gdGhlIHBhZ2UgY3VycmVudGx5IG9wZW4gb24gdGhlIGNhbnZhc1xuICAgICAgICBcbiAgICAgICAgcGFnZVRpdGxlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZVRpdGxlJyksLy9ob2xkcyB0aGUgcGFnZSB0aXRsZSBvZiB0aGUgY3VycmVudCBwYWdlIG9uIHRoZSBjYW52YXNcbiAgICAgICAgXG4gICAgICAgIGRpdkNhbnZhczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VMaXN0JyksLy9ESVYgY29udGFpbmluZyBhbGwgcGFnZXMgb24gdGhlIGNhbnZhc1xuICAgICAgICBcbiAgICAgICAgcGFnZXNNZW51OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZXMnKSwgLy9VTCBjb250YWluaW5nIHRoZSBwYWdlcyBtZW51IGluIHRoZSBzaWRlYmFyXG4gICAgICAgICAgICAgICAgXG4gICAgICAgIGJ1dHRvbk5ld1BhZ2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhZGRQYWdlJyksXG4gICAgICAgIGxpTmV3UGFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ25ld1BhZ2VMSScpLFxuICAgICAgICBcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NUaXRsZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX3RpdGxlJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzTWV0YURlc2NyaXB0aW9uOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfbWV0YURlc2NyaXB0aW9uJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzTWV0YUtleXdvcmRzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfbWV0YUtleXdvcmRzJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzSW5jbHVkZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9oZWFkZXJJbmNsdWRlcycpLFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc1BhZ2VDc3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9oZWFkZXJDc3MnKSxcbiAgICAgICAgXG4gICAgICAgIGJ1dHRvblN1Ym1pdFBhZ2VTZXR0aW5nczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VTZXR0aW5nc1N1Ym1pdHRCdXR0b24nKSxcbiAgICAgICAgXG4gICAgICAgIG1vZGFsUGFnZVNldHRpbmdzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZVNldHRpbmdzTW9kYWwnKSxcbiAgICAgICAgXG4gICAgICAgIGJ1dHRvblNhdmU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzYXZlUGFnZScpLFxuICAgICAgICBcbiAgICAgICAgbWVzc2FnZVN0YXJ0OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc3RhcnQnKSxcbiAgICAgICAgZGl2RnJhbWVXcmFwcGVyOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJyksXG4gICAgICAgIFxuICAgICAgICBza2VsZXRvbjogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NrZWxldG9uJyksXG5cdFx0XG5cdFx0YXV0b1NhdmVUaW1lcjoge30sXG4gICAgICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJC5nZXRKU09OKGFwcFVJLnNpdGVVcmwrXCJzaXRlcy9zaXRlRGF0YVwiLCBmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggZGF0YS5zaXRlICE9PSB1bmRlZmluZWQgKSB7XG4gICAgICAgICAgICAgICAgICAgIHNpdGUuZGF0YSA9IGRhdGEuc2l0ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYoIGRhdGEucGFnZXMgIT09IHVuZGVmaW5lZCApIHtcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5wYWdlcyA9IGRhdGEucGFnZXM7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUuaXNfYWRtaW4gPSBkYXRhLmlzX2FkbWluO1xuICAgICAgICAgICAgICAgIFxuXHRcdFx0XHRpZiggJCgnI3BhZ2VMaXN0Jykuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICBcdGJ1aWxkZXJVSS5wb3B1bGF0ZUNhbnZhcygpO1xuXHRcdFx0XHR9XG5cbiAgICAgICAgICAgICAgICBpZiggZGF0YS5zaXRlLnZpZXdtb2RlICkge1xuICAgICAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25TZXRNb2RlJywgZGF0YS5zaXRlLnZpZXdtb2RlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9maXJlIGN1c3RvbSBldmVudFxuICAgICAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdzaXRlRGF0YUxvYWRlZCcpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5idXR0b25OZXdQYWdlKS5vbignY2xpY2snLCBzaXRlLm5ld1BhZ2UpO1xuICAgICAgICAgICAgJCh0aGlzLm1vZGFsUGFnZVNldHRpbmdzKS5vbignc2hvdy5icy5tb2RhbCcsIHNpdGUubG9hZFBhZ2VTZXR0aW5ncyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU3VibWl0UGFnZVNldHRpbmdzKS5vbignY2xpY2snLCBzaXRlLnVwZGF0ZVBhZ2VTZXR0aW5ncyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU2F2ZSkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXtzaXRlLnNhdmUodHJ1ZSk7fSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYXV0byBzYXZlIHRpbWUgXG4gICAgICAgICAgICB0aGlzLmF1dG9TYXZlVGltZXIgPSBzZXRUaW1lb3V0KHNpdGUuYXV0b1NhdmUsIGJDb25maWcuYXV0b1NhdmVUaW1lb3V0KTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnN1YnNjcmliZSgnb25CbG9ja0NoYW5nZScsIGZ1bmN0aW9uIChibG9jaywgdHlwZSkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCBibG9jay5nbG9iYWwgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZm9yICggdmFyIGkgPSAwOyBpIDwgc2l0ZS5zaXRlUGFnZXMubGVuZ3RoOyBpKysgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGZvciAoIHZhciB5ID0gMDsgeSA8IHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrcy5sZW5ndGg7IHkgKysgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XSAhPT0gYmxvY2sgJiYgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldLm9yaWdpbmFsVXJsID09PSBibG9jay5vcmlnaW5hbFVybCAmJiBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0uZ2xvYmFsICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICggdHlwZSA9PT0gJ2NoYW5nZScgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5mcmFtZURvY3VtZW50LmJvZHkgPSBibG9jay5mcmFtZURvY3VtZW50LmJvZHkuY2xvbmVOb2RlKHRydWUpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0xvYWRlZCcsIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmICggdHlwZSA9PT0gJ3JlbG9hZCcgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5yZXNldChmYWxzZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIGF1dG9TYXZlOiBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBpZihzaXRlLnBlbmRpbmdDaGFuZ2VzKSB7XG4gICAgICAgICAgICAgICAgc2l0ZS5zYXZlKGZhbHNlKTtcbiAgICAgICAgICAgIH1cblx0XHRcdFxuXHRcdFx0d2luZG93LmNsZWFySW50ZXJ2YWwodGhpcy5hdXRvU2F2ZVRpbWVyKTtcbiAgICAgICAgICAgIHRoaXMuYXV0b1NhdmVUaW1lciA9IHNldFRpbWVvdXQoc2l0ZS5hdXRvU2F2ZSwgYkNvbmZpZy5hdXRvU2F2ZVRpbWVvdXQpO1xuICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgc2V0UGVuZGluZ0NoYW5nZXM6IGZ1bmN0aW9uKHZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGVuZGluZ0NoYW5nZXMgPSB2YWx1ZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHZhbHVlID09PSB0cnVlICkge1xuXHRcdFx0XHRcblx0XHRcdFx0Ly9yZXNldCB0aW1lclxuXHRcdFx0XHR3aW5kb3cuY2xlYXJJbnRlcnZhbCh0aGlzLmF1dG9TYXZlVGltZXIpO1xuICAgICAgICAgICAgXHR0aGlzLmF1dG9TYXZlVGltZXIgPSBzZXRUaW1lb3V0KHNpdGUuYXV0b1NhdmUsIGJDb25maWcuYXV0b1NhdmVUaW1lb3V0KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjc2F2ZVBhZ2UgLmJMYWJlbCcpLnRleHQoXCJTYXZlIG5vdyAoISlcIik7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHNpdGUuYWN0aXZlUGFnZS5zdGF0dXMgIT09ICduZXcnICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2Uuc3RhdHVzID0gJ2NoYW5nZWQnO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG5cdFx0XHRcbiAgICAgICAgICAgIH0gZWxzZSB7XG5cdFxuICAgICAgICAgICAgICAgICQoJyNzYXZlUGFnZSAuYkxhYmVsJykudGV4dChcIk5vdGhpbmcgdG8gc2F2ZVwiKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgc2l0ZS51cGRhdGVQYWdlU3RhdHVzKCcnKTtcblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgXG4gICAgICAgIHNhdmU6IGZ1bmN0aW9uKHNob3dDb25maXJtTW9kYWwpIHtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmVmb3JlU2F2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2ZpcmUgY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignYmVmb3JlU2F2ZScpO1xuXG4gICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAkKFwiYSNzYXZlUGFnZVwiKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XG4gICAgICAgICAgICAvL3JlbW92ZSBvbGQgYWxlcnRzXG4gICAgICAgICAgICAkKCcjZXJyb3JNb2RhbCAubW9kYWwtYm9keSA+ICosICNzdWNjZXNzTW9kYWwgLm1vZGFsLWJvZHkgPiAqJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcblx0XG4gICAgICAgICAgICBzaXRlLnByZXBGb3JTYXZlKGZhbHNlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHNlcnZlckRhdGEgPSB7fTtcbiAgICAgICAgICAgIHNlcnZlckRhdGEucGFnZXMgPSB0aGlzLnNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyO1xuICAgICAgICAgICAgaWYoIHRoaXMucGFnZXNUb0RlbGV0ZS5sZW5ndGggPiAwICkge1xuICAgICAgICAgICAgICAgIHNlcnZlckRhdGEudG9EZWxldGUgPSB0aGlzLnBhZ2VzVG9EZWxldGU7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHNlcnZlckRhdGEuc2l0ZURhdGEgPSB0aGlzLmRhdGE7XG5cbiAgICAgICAgICAgIC8vc3RvcmUgY3VycmVudCByZXNwb25zaXZlIG1vZGUgYXMgd2VsbFxuICAgICAgICAgICAgc2VydmVyRGF0YS5zaXRlRGF0YS5yZXNwb25zaXZlTW9kZSA9IGJ1aWxkZXJVSS5jdXJyZW50UmVzcG9uc2l2ZU1vZGU7XG5cbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvc2F2ZVwiLFxuICAgICAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcbiAgICAgICAgICAgICAgICBkYXRhOiBzZXJ2ZXJEYXRhLFxuICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXMpe1xuXHRcbiAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAkKFwiYSNzYXZlUGFnZVwiKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XG4gICAgICAgICAgICAgICAgaWYoIHJlcy5yZXNwb25zZUNvZGUgPT09IDAgKSB7XG5cdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgaWYoIHNob3dDb25maXJtTW9kYWwgKSB7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2Vycm9yTW9kYWwgLm1vZGFsLWJvZHknKS5hcHBlbmQoICQocmVzLnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNlcnJvck1vZGFsJykubW9kYWwoJ3Nob3cnKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIH1cblx0XHRcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIHJlcy5yZXNwb25zZUNvZGUgPT09IDEgKSB7XG5cdFx0XG4gICAgICAgICAgICAgICAgICAgIGlmKCBzaG93Q29uZmlybU1vZGFsICkge1xuXHRcdFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3N1Y2Nlc3NNb2RhbCAubW9kYWwtYm9keScpLmFwcGVuZCggJChyZXMucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3N1Y2Nlc3NNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICB9XG5cdFx0XHRcblx0XHRcdFxuICAgICAgICAgICAgICAgICAgICAvL25vIG1vcmUgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXMoZmFsc2UpO1xuXHRcdFx0XG5cbiAgICAgICAgICAgICAgICAgICAgLy91cGRhdGUgcmV2aXNpb25zP1xuICAgICAgICAgICAgICAgICAgICAkKCdib2R5JykudHJpZ2dlcignY2hhbmdlUGFnZScpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHByZXBzIHRoZSBzaXRlIGRhdGEgYmVmb3JlIHNlbmRpbmcgaXQgdG8gdGhlIHNlcnZlclxuICAgICAgICAqL1xuICAgICAgICBwcmVwRm9yU2F2ZTogZnVuY3Rpb24odGVtcGxhdGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlciA9IHt9O1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdGVtcGxhdGUgKSB7Ly9zYXZpbmcgdGVtcGxhdGUsIG9ubHkgdGhlIGFjdGl2ZVBhZ2UgaXMgbmVlZGVkXG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlclt0aGlzLmFjdGl2ZVBhZ2UubmFtZV0gPSB0aGlzLmFjdGl2ZVBhZ2UucHJlcEZvclNhdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UuZnVsbFBhZ2UoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7Ly9yZWd1bGFyIHNhdmVcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZmluZCB0aGUgcGFnZXMgd2hpY2ggbmVlZCB0byBiZSBzZW5kIHRvIHRoZSBzZXJ2ZXJcbiAgICAgICAgICAgICAgICBmb3IoIHZhciBpID0gMDsgaSA8IHRoaXMuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuc2l0ZVBhZ2VzW2ldLnN0YXR1cyAhPT0gJycgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXJbdGhpcy5zaXRlUGFnZXNbaV0ubmFtZV0gPSB0aGlzLnNpdGVQYWdlc1tpXS5wcmVwRm9yU2F2ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzZXRzIGEgcGFnZSBhcyB0aGUgYWN0aXZlIG9uZVxuICAgICAgICAqL1xuICAgICAgICBzZXRBY3RpdmU6IGZ1bmN0aW9uKHBhZ2UpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZWZlcmVuY2UgdG8gdGhlIGFjdGl2ZSBwYWdlXG4gICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UgPSBwYWdlO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2hpZGUgb3RoZXIgcGFnZXNcbiAgICAgICAgICAgIGZvcih2YXIgaSBpbiB0aGlzLnNpdGVQYWdlcykge1xuICAgICAgICAgICAgICAgIHRoaXMuc2l0ZVBhZ2VzW2ldLnBhcmVudFVMLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7ICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZGlzcGxheSBhY3RpdmUgb25lXG4gICAgICAgICAgICB0aGlzLmFjdGl2ZVBhZ2UucGFyZW50VUwuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGUtYWN0aXZlIGFsbCBwYWdlIG1lbnUgaXRlbXNcbiAgICAgICAgKi9cbiAgICAgICAgZGVBY3RpdmF0ZUFsbDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlcyA9IHRoaXMucGFnZXNNZW51LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgPSAwOyBpIDwgcGFnZXMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgcGFnZXNbaV0uY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYWRkcyBhIG5ldyBwYWdlIHRvIHRoZSBzaXRlXG4gICAgICAgICovXG4gICAgICAgIG5ld1BhZ2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLmRlQWN0aXZhdGVBbGwoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9jcmVhdGUgdGhlIG5ldyBwYWdlIGluc3RhbmNlXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlRGF0YSA9IFtdO1xuICAgICAgICAgICAgdmFyIHRlbXAgPSB7XG4gICAgICAgICAgICAgICAgcGFnZXNfaWQ6IDBcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBwYWdlRGF0YVswXSA9IHRlbXA7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBuZXdQYWdlTmFtZSA9ICdwYWdlJysoc2l0ZS5zaXRlUGFnZXMubGVuZ3RoKzEpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgbmV3UGFnZSA9IG5ldyBQYWdlKG5ld1BhZ2VOYW1lLCBwYWdlRGF0YSwgc2l0ZS5zaXRlUGFnZXMubGVuZ3RoKzEpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBuZXdQYWdlLnN0YXR1cyA9ICduZXcnO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBuZXdQYWdlLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgIG5ld1BhZ2UuZWRpdFBhZ2VOYW1lKCk7XG4gICAgICAgIFxuICAgICAgICAgICAgbmV3UGFnZS5pc0VtcHR5KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2hlY2tzIGlmIHRoZSBuYW1lIG9mIGEgcGFnZSBpcyBhbGxvd2VkXG4gICAgICAgICovXG4gICAgICAgIGNoZWNrUGFnZU5hbWU6IGZ1bmN0aW9uKHBhZ2VOYW1lKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbWFrZSBzdXJlIHRoZSBuYW1lIGlzIHVuaXF1ZVxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLnNpdGVQYWdlcyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5zaXRlUGFnZXNbaV0ubmFtZSA9PT0gcGFnZU5hbWUgJiYgdGhpcy5hY3RpdmVQYWdlICE9PSB0aGlzLnNpdGVQYWdlc1tpXSApIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYWdlTmFtZUVycm9yID0gXCJUaGUgcGFnZSBuYW1lIG11c3QgYmUgdW5pcXVlLlwiO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfSAgIFxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZW1vdmVzIHVuYWxsb3dlZCBjaGFyYWN0ZXJzIGZyb20gdGhlIHBhZ2UgbmFtZVxuICAgICAgICAqL1xuICAgICAgICBwcmVwUGFnZU5hbWU6IGZ1bmN0aW9uKHBhZ2VOYW1lKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHBhZ2VOYW1lID0gcGFnZU5hbWUucmVwbGFjZSgnICcsICcnKTtcbiAgICAgICAgICAgIHBhZ2VOYW1lID0gcGFnZU5hbWUucmVwbGFjZSgvWz8qIS58JiM7JCVAXCI8PigpKyxdL2csIFwiXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICByZXR1cm4gcGFnZU5hbWU7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2F2ZSBwYWdlIHNldHRpbmdzIGZvciB0aGUgY3VycmVudCBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZVBhZ2VTZXR0aW5nczogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MudGl0bGUgPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzVGl0bGUudmFsdWU7XG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLm1ldGFfZGVzY3JpcHRpb24gPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzTWV0YURlc2NyaXB0aW9uLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5tZXRhX2tleXdvcmRzID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFLZXl3b3Jkcy52YWx1ZTtcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MuaGVhZGVyX2luY2x1ZGVzID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc0luY2x1ZGVzLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5wYWdlX2NzcyA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NQYWdlQ3NzLnZhbHVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHNpdGUubW9kYWxQYWdlU2V0dGluZ3MpLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlIHBhZ2Ugc3RhdHVzZXNcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlUGFnZVN0YXR1czogZnVuY3Rpb24oc3RhdHVzKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5zaXRlUGFnZXMgKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNbaV0uc3RhdHVzID0gc3RhdHVzOyAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2hlY2tzIGFsbCB0aGUgYmxvY2tzIGluIHRoaXMgc2l0ZSBoYXZlIGZpbmlzaGVkIGxvYWRpbmdcbiAgICAgICAgKi9cbiAgICAgICAgbG9hZGVkOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHZhciBpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IHRoaXMuc2l0ZVBhZ2VzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCAhdGhpcy5zaXRlUGFnZXNbaV0ubG9hZGVkKCkgKSByZXR1cm4gZmFsc2U7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBNYWtlIGV2ZXJ5IGJsb2NrIGhhdmUgYW4gb3ZlcmxheSBkdXJpbmcgZHJhZ2dpbmcgdG8gcHJldmVudCBtb3VzZSBldmVudCBpc3N1ZXNcbiAgICAgICAgKi9cbiAgICAgICAgbW92ZU1vZGU6IGZ1bmN0aW9uICh2YWx1ZSkge1xuXG4gICAgICAgICAgICB2YXIgaTtcblxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCB0aGlzLmFjdGl2ZVBhZ2UuYmxvY2tzLmxlbmd0aDsgaSsrICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCB2YWx1ZSA9PT0gJ29uJyApIHRoaXMuYWN0aXZlUGFnZS5ibG9ja3NbaV0uZnJhbWVDb3Zlci5jbGFzc0xpc3QuYWRkKCdtb3ZlJyk7XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAoIHZhbHVlID09PSAnb2ZmJyApIHRoaXMuYWN0aXZlUGFnZS5ibG9ja3NbaV0uZnJhbWVDb3Zlci5jbGFzc0xpc3QucmVtb3ZlKCdtb3ZlJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBHZXQgZm9ybSBmcm9tIEFFTVxuICAgICAgICAqL1xuICAgICAgICBxdWlja19sb2FkX2Zvcm06IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKCQoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2JykubGVuZ3RoKXtcbiAgICAgICAgICAgICAgICBqUXVlcnkuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6IFwicG9zdFwiLFxuICAgICAgICAgICAgICAgICAgICB1cmw6IFwiaHR0cHM6Ly9nbGNodWIuY29tL2FlbS9tYW5hZ2UvdGVtcGxhdGVzL2NsYXNzaWMvYWpheC9hcGkucGhwXCIsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdhY3Rpb24nOidnZXRfZm9ybV9ieV9pZCcsXG4gICAgICAgICAgICAgICAgICAgICAgICAnZm9ybV9pZCc6Zm9ybV9pZFxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgICAgICBzdWNjZXNzOmZ1bmN0aW9uKHJlc3VsdCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYocmVzdWx0LnR5cGUgPT09ICdzdWNjZXNzJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBqaGVpZ2h0ID0galF1ZXJ5KCcjcGFnZUxpc3QgaWZyYW1lJykuY29udGVudHMoKS5oZWlnaHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBqaGVpZ2h0ICs9IDEwMDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2JykuaHRtbChyZXN1bHQubWVzc2FnZS5odG1sKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnLCB3aW5kb3cucGFyZW50LmRvY3VtZW50KS5oZWlnaHQoamhlaWdodCsncHgnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBlcnJvcjogZnVuY3Rpb24oZXJyb3JUaHJvd24pe1xuICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coZXJyb3JUaHJvd24pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICBcbiAgICB9O1xuXG4gICAgYnVpbGRlclVJLmluaXQoKTsgc2l0ZS5pbml0KCk7XG5cbiAgICBcbiAgICAvLyoqKiogRVhQT1JUU1xuICAgIG1vZHVsZS5leHBvcnRzLnNpdGUgPSBzaXRlO1xuICAgIG1vZHVsZS5leHBvcnRzLmJ1aWxkZXJVSSA9IGJ1aWxkZXJVSTtcblxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuICAgIFwidXNlIHN0cmljdFwiO1xuXG4gICAgdmFyIHNpdGVCdWlsZGVyID0gcmVxdWlyZSgnLi9idWlsZGVyLmpzJyk7XG5cbiAgICAvKlxuICAgICAgICBjb25zdHJ1Y3RvciBmdW5jdGlvbiBmb3IgRWxlbWVudFxuICAgICovXG4gICAgbW9kdWxlLmV4cG9ydHMuRWxlbWVudCA9IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbDtcbiAgICAgICAgdGhpcy5zYW5kYm94ID0gZmFsc2U7XG4gICAgICAgIHRoaXMucGFyZW50RnJhbWUgPSB7fTtcbiAgICAgICAgdGhpcy5wYXJlbnRCbG9jayA9IHt9Oy8vcmVmZXJlbmNlIHRvIHRoZSBwYXJlbnQgYmxvY2sgZWxlbWVudFxuICAgICAgICB0aGlzLmVkaXRhYmxlQXR0cmlidXRlcyA9IFtdO1xuICAgICAgICBcbiAgICAgICAgLy9tYWtlIGN1cnJlbnQgZWxlbWVudCBhY3RpdmUvb3BlbiAoYmVpbmcgd29ya2VkIG9uKVxuICAgICAgICB0aGlzLnNldE9wZW4gPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLm9mZignbW91c2VlbnRlciBtb3VzZWxlYXZlIGNsaWNrJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLmNzcyh7J291dGxpbmUnOiAnMnB4IHNvbGlkIHJnYmEoMjMzLDk0LDk0LDAuNSknLCAnb3V0bGluZS1vZmZzZXQnOictMnB4JywgJ2N1cnNvcic6ICdwb2ludGVyJ30pO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvL3NldHMgdXAgaG92ZXIgYW5kIGNsaWNrIGV2ZW50cywgbWFraW5nIHRoZSBlbGVtZW50IGFjdGl2ZSBvbiB0aGUgY2FudmFzXG4gICAgICAgIHRoaXMuYWN0aXZhdGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGVsZW1lbnQgPSB0aGlzO1xuXG4gICAgICAgICAgICAvL2RhdGEgYXR0cmlidXRlcyBmb3IgY29sb3JcbiAgICAgICAgICAgIGlmICggdGhpcy5lbGVtZW50LnRhZ05hbWUgPT09ICdBJyApICQodGhpcy5lbGVtZW50KS5kYXRhKCdjb2xvcicsIGdldENvbXB1dGVkU3R5bGUodGhpcy5lbGVtZW50KS5jb2xvcik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5jc3MoeydvdXRsaW5lJzogJ25vbmUnLCAnY3Vyc29yJzogJyd9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLm9uKCdtb3VzZWVudGVyJywgZnVuY3Rpb24oZSkge1xuXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcykuY3NzKHsnb3V0bGluZSc6ICcycHggc29saWQgcmdiYSgyMzMsOTQsOTQsMC41KScsICdvdXRsaW5lLW9mZnNldCc6ICctMnB4JywgJ2N1cnNvcic6ICdwb2ludGVyJ30pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KS5vbignbW91c2VsZWF2ZScsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcykuY3NzKHsnb3V0bGluZSc6ICcnLCAnY3Vyc29yJzogJycsICdvdXRsaW5lLW9mZnNldCc6ICcnfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBlbGVtZW50LmNsaWNrSGFuZGxlcih0aGlzKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIHRoaXMuZGVhY3RpdmF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMuZWxlbWVudCkub2ZmKCdtb3VzZWVudGVyIG1vdXNlbGVhdmUgY2xpY2snKTtcbiAgICAgICAgICAgICQodGhpcy5lbGVtZW50KS5jc3MoeydvdXRsaW5lJzogJ25vbmUnLCAnY3Vyc29yJzogJ2luaGVyaXQnfSk7XG5cbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8vcmVtb3ZlcyB0aGUgZWxlbWVudHMgb3V0bGluZVxuICAgICAgICB0aGlzLnJlbW92ZU91dGxpbmUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmVsZW1lbnQpLmNzcyh7J291dGxpbmUnOiAnbm9uZScsICdjdXJzb3InOiAnaW5oZXJpdCd9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLy9zZXRzIHRoZSBwYXJlbnQgaWZyYW1lXG4gICAgICAgIHRoaXMuc2V0UGFyZW50RnJhbWUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGRvYyA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICAgICAgdmFyIHcgPSBkb2MuZGVmYXVsdFZpZXcgfHwgZG9jLnBhcmVudFdpbmRvdztcbiAgICAgICAgICAgIHZhciBmcmFtZXMgPSB3LnBhcmVudC5kb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnaWZyYW1lJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciAodmFyIGk9IGZyYW1lcy5sZW5ndGg7IGktLT4wOykge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBmcmFtZT0gZnJhbWVzW2ldO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBkPSBmcmFtZS5jb250ZW50RG9jdW1lbnQgfHwgZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudDtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGQ9PT1kb2MpXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnBhcmVudEZyYW1lID0gZnJhbWU7XG4gICAgICAgICAgICAgICAgfSBjYXRjaChlKSB7fVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvL3NldHMgdGhpcyBlbGVtZW50J3MgcGFyZW50IGJsb2NrIHJlZmVyZW5jZVxuICAgICAgICB0aGlzLnNldFBhcmVudEJsb2NrID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9vcCB0aHJvdWdoIGFsbCB0aGUgYmxvY2tzIG9uIHRoZSBjYW52YXNcbiAgICAgICAgICAgIGZvciggdmFyIGkgPSAwOyBpIDwgc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGZvciggdmFyIHggPSAwOyB4IDwgc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzLmxlbmd0aDsgeCsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2lmIHRoZSBibG9jaydzIGZyYW1lIG1hdGNoZXMgdGhpcyBlbGVtZW50J3MgcGFyZW50IGZyYW1lXG4gICAgICAgICAgICAgICAgICAgIGlmKCBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeF0uZnJhbWUgPT09IHRoaXMucGFyZW50RnJhbWUgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvL2NyZWF0ZSBhIHJlZmVyZW5jZSB0byB0aGF0IGJsb2NrIGFuZCBzdG9yZSBpdCBpbiB0aGlzLnBhcmVudEJsb2NrXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnBhcmVudEJsb2NrID0gc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3hdO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgdGhpcy5zZXRQYXJlbnRGcmFtZSgpO1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGlzIHRoaXMgYmxvY2sgc2FuZGJveGVkP1xuICAgICAgICAqL1xuICAgICAgICBcbiAgICAgICAgaWYoIHRoaXMucGFyZW50RnJhbWUuZ2V0QXR0cmlidXRlKCdkYXRhLXNhbmRib3gnKSApIHtcbiAgICAgICAgICAgIHRoaXMuc2FuZGJveCA9IHRoaXMucGFyZW50RnJhbWUuZ2V0QXR0cmlidXRlKCdkYXRhLXNhbmRib3gnKTsgICBcbiAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgIH07XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG4gICAgICAgIFxuICAgIG1vZHVsZS5leHBvcnRzLnBhZ2VDb250YWluZXIgPSBcIiNwYWdlXCI7XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5ib2R5UGFkZGluZ0NsYXNzID0gXCJiUGFkZGluZ1wiO1xuICAgIFxuICAgIG1vZHVsZS5leHBvcnRzLmVkaXRhYmxlSXRlbXMgPSB7XG4gICAgICAgICdzcGFuLmZhJzogWydjb2xvcicsICdmb250LXNpemUnXSxcbiAgICAgICAgJy5iZy5iZzEnOiBbJ2JhY2tncm91bmQtY29sb3InXSxcbiAgICAgICAgJ25hdiBhJzogWydjb2xvcicsICdmb250LXdlaWdodCcsICd0ZXh0LXRyYW5zZm9ybSddLFxuICAgICAgICAnaW1nJzogWydib3JkZXItdG9wLWxlZnQtcmFkaXVzJywgJ2JvcmRlci10b3AtcmlnaHQtcmFkaXVzJywgJ2JvcmRlci1ib3R0b20tbGVmdC1yYWRpdXMnLCAnYm9yZGVyLWJvdHRvbS1yaWdodC1yYWRpdXMnLCAnYm9yZGVyLWNvbG9yJywgJ2JvcmRlci1zdHlsZScsICdib3JkZXItd2lkdGgnXSxcbiAgICAgICAgJ2hyLmRhc2hlZCc6IFsnYm9yZGVyLWNvbG9yJywgJ2JvcmRlci13aWR0aCddLFxuICAgICAgICAnLmRpdmlkZXIgPiBzcGFuJzogWydjb2xvcicsICdmb250LXNpemUnXSxcbiAgICAgICAgJ2hyLnNoYWRvd0Rvd24nOiBbJ21hcmdpbi10b3AnLCAnbWFyZ2luLWJvdHRvbSddLFxuICAgICAgICAnLmZvb3RlciBhJzogWydjb2xvciddLFxuICAgICAgICAnLnNvY2lhbCBhJzogWydjb2xvciddLFxuICAgICAgICAnLmJnLmJnMSwgLmJnLmJnMiwgLmhlYWRlcjEwLCAuaGVhZGVyMTEnOiBbJ2JhY2tncm91bmQtaW1hZ2UnLCAnYmFja2dyb3VuZC1jb2xvciddLFxuICAgICAgICAnLmZyYW1lQ292ZXInOiBbXSxcbiAgICAgICAgJy5lZGl0Q29udGVudCc6IFsnY29udGVudCcsICdjb2xvcicsICdmb250LXNpemUnLCAnYmFja2dyb3VuZC1jb2xvcicsICdmb250LWZhbWlseSddLFxuICAgICAgICAnYS5idG4sIGJ1dHRvbi5idG4nOiBbJ2JvcmRlci1yYWRpdXMnLCAnZm9udC1zaXplJywgJ2JhY2tncm91bmQtY29sb3InXSxcbiAgICAgICAgJyNwcmljaW5nX3RhYmxlMiAucHJpY2luZzIgLmJvdHRvbSBsaSc6IFsnY29udGVudCddXG4gICAgfTtcbiAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5lZGl0YWJsZUl0ZW1PcHRpb25zID0ge1xuICAgICAgICAnbmF2IGEgOiBmb250LXdlaWdodCc6IFsnNDAwJywgJzcwMCddLFxuICAgICAgICAnYS5idG4gOiBib3JkZXItcmFkaXVzJzogWycwcHgnLCAnNHB4JywgJzEwcHgnXSxcbiAgICAgICAgJ2ltZyA6IGJvcmRlci1zdHlsZSc6IFsnbm9uZScsICdkb3R0ZWQnLCAnZGFzaGVkJywgJ3NvbGlkJ10sXG4gICAgICAgICdpbWcgOiBib3JkZXItd2lkdGgnOiBbJzFweCcsICcycHgnLCAnM3B4JywgJzRweCddLFxuICAgICAgICAnaDEsIGgyLCBoMywgaDQsIGg1LCBwIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ10sXG4gICAgICAgICdoMiA6IGZvbnQtZmFtaWx5JzogWydkZWZhdWx0JywgJ0xhdG8nLCAnSGVsdmV0aWNhJywgJ0FyaWFsJywgJ1RpbWVzIE5ldyBSb21hbiddLFxuICAgICAgICAnaDMgOiBmb250LWZhbWlseSc6IFsnZGVmYXVsdCcsICdMYXRvJywgJ0hlbHZldGljYScsICdBcmlhbCcsICdUaW1lcyBOZXcgUm9tYW4nXSxcbiAgICAgICAgJ3AgOiBmb250LWZhbWlseSc6IFsnZGVmYXVsdCcsICdMYXRvJywgJ0hlbHZldGljYScsICdBcmlhbCcsICdUaW1lcyBOZXcgUm9tYW4nXVxuICAgIH07XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5yZXNwb25zaXZlTW9kZXMgPSB7XG4gICAgICAgIGRlc2t0b3A6ICc5NyUnLFxuICAgICAgICBtb2JpbGU6ICc0ODBweCcsXG4gICAgICAgIHRhYmxldDogJzEwMjRweCdcbiAgICB9O1xuXG4gICAgbW9kdWxlLmV4cG9ydHMuZWRpdGFibGVDb250ZW50ID0gWycuZWRpdENvbnRlbnQnLCAnLm5hdmJhciBhJywgJ2J1dHRvbicsICdhLmJ0bicsICcuZm9vdGVyIGE6bm90KC5mYSknLCAnLnRhYmxlV3JhcHBlcicsICdoMScsICdoMiddO1xuXG4gICAgbW9kdWxlLmV4cG9ydHMuYXV0b1NhdmVUaW1lb3V0ID0gMzAwMDAwO1xuICAgIFxuICAgIG1vZHVsZS5leHBvcnRzLnNvdXJjZUNvZGVFZGl0U3ludGF4RGVsYXkgPSAxMDAwMDtcblxuICAgIG1vZHVsZS5leHBvcnRzLm1lZGl1bUNzc1VybHMgPSBbXG4gICAgICAgICcvL2Nkbi5qc2RlbGl2ci5uZXQvbWVkaXVtLWVkaXRvci9sYXRlc3QvY3NzL21lZGl1bS1lZGl0b3IubWluLmNzcycsXG4gICAgICAgICcvY3NzL21lZGl1bS1ib290c3RyYXAuY3NzJ1xuICAgIF07XG4gICAgbW9kdWxlLmV4cG9ydHMubWVkaXVtQnV0dG9ucyA9IFsnYm9sZCcsICdpdGFsaWMnLCAndW5kZXJsaW5lJywgJ2FuY2hvcicsICdvcmRlcmVkbGlzdCcsICd1bm9yZGVyZWRsaXN0JywgJ2gxJywgJ2gyJywgJ2gzJywgJ2g0JywgJ3JlbW92ZUZvcm1hdCddO1xuXG4gICAgbW9kdWxlLmV4cG9ydHMuZXh0ZXJuYWxKUyA9IFtcbiAgICAgICAgJ2pzL2J1aWxkZXJfaW5fYmxvY2suanMnXG4gICAgXTtcbiAgICAgICAgICAgICAgICAgICAgXG59KCkpOyIsIihmdW5jdGlvbiAoKXtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cbiAgICB2YXIgYkNvbmZpZyA9IHJlcXVpcmUoJy4vY29uZmlnLmpzJyk7XG4gICAgdmFyIHNpdGVCdWlsZGVyID0gcmVxdWlyZSgnLi9idWlsZGVyLmpzJyk7XG4gICAgdmFyIGVkaXRvciA9IHJlcXVpcmUoJy4vc3R5bGVlZGl0b3IuanMnKS5zdHlsZWVkaXRvcjtcbiAgICB2YXIgYXBwVUkgPSByZXF1aXJlKCcuL3VpLmpzJykuYXBwVUk7XG5cbiAgICB2YXIgaW1hZ2VMaWJyYXJ5ID0ge1xuICAgICAgICBcbiAgICAgICAgaW1hZ2VNb2RhbDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ltYWdlTW9kYWwnKSxcbiAgICAgICAgaW5wdXRJbWFnZVVwbG9hZDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ltYWdlRmlsZScpLFxuICAgICAgICBidXR0b25VcGxvYWRJbWFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3VwbG9hZEltYWdlQnV0dG9uJyksXG4gICAgICAgIGltYWdlTGlicmFyeUxpbmtzOiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuaW1hZ2VzID4gLmltYWdlIC5idXR0b25zIC5idG4tcHJpbWFyeSwgLmltYWdlcyAuaW1hZ2VXcmFwID4gYScpLC8vdXNlZCBpbiB0aGUgbGlicmFyeSwgb3V0c2lkZSB0aGUgYnVpbGRlciBVSVxuICAgICAgICBteUltYWdlczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ215SW1hZ2VzJyksLy91c2VkIGluIHRoZSBpbWFnZSBsaWJyYXJ5LCBvdXRzaWRlIHRoZSBidWlsZGVyIFVJXG4gICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5pbWFnZU1vZGFsKS5vbignc2hvdy5icy5tb2RhbCcsIHRoaXMuaW1hZ2VMaWJyYXJ5KTtcbiAgICAgICAgICAgICQodGhpcy5pbnB1dEltYWdlVXBsb2FkKS5vbignY2hhbmdlJywgdGhpcy5pbWFnZUlucHV0Q2hhbmdlKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25VcGxvYWRJbWFnZSkub24oJ2NsaWNrJywgdGhpcy51cGxvYWRJbWFnZSk7XG4gICAgICAgICAgICAkKHRoaXMuaW1hZ2VMaWJyYXJ5TGlua3MpLm9uKCdjbGljaycsIHRoaXMuaW1hZ2VJbk1vZGFsKTtcbiAgICAgICAgICAgICQodGhpcy5teUltYWdlcykub24oJ2NsaWNrJywgJy5idXR0b25zIC5idG4tZGFuZ2VyJywgdGhpcy5kZWxldGVJbWFnZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgaW1hZ2UgbGlicmFyeSBtb2RhbFxuICAgICAgICAqL1xuICAgICAgICBpbWFnZUxpYnJhcnk6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXHRcdFx0XG4gICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm9mZignY2xpY2snLCAnLmltYWdlIGJ1dHRvbi51c2VJbWFnZScpO1xuXHRcdFx0XG4gICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm9uKCdjbGljaycsICcuaW1hZ2UgYnV0dG9uLnVzZUltYWdlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3VwZGF0ZSBsaXZlIGltYWdlXG4gICAgICAgICAgICAgICAgJChlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdzcmMnLCAkKHRoaXMpLmF0dHIoJ2RhdGEtdXJsJykpO1xuXG4gICAgICAgICAgICAgICAgLy91cGRhdGUgaW1hZ2UgVVJMIGZpZWxkXG4gICAgICAgICAgICAgICAgJCgnaW5wdXQjaW1hZ2VVUkwnKS52YWwoICQodGhpcykuYXR0cignZGF0YS11cmwnKSApO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAvL2hpZGUgbW9kYWxcbiAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCcpLm1vZGFsKCdoaWRlJyk7XG5cdFx0XHRcdFxuICAgICAgICAgICAgICAgIC8vaGVpZ2h0IGFkanVzdG1lbnQgb2YgdGhlIGlmcmFtZSBoZWlnaHRBZGp1c3RtZW50XG5cdFx0XHRcdGVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLmhlaWdodEFkanVzdG1lbnQoKTtcdFx0XHRcdFx0XHRcdFxuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXHRcdFx0XG4gICAgICAgICAgICAgICAgJCh0aGlzKS51bmJpbmQoJ2NsaWNrJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGltYWdlIHVwbG9hZCBpbnB1dCBjaGFuZWcgZXZlbnQgaGFuZGxlclxuICAgICAgICAqL1xuICAgICAgICBpbWFnZUlucHV0Q2hhbmdlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQodGhpcykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgIC8vbm8gZmlsZSwgZGlzYWJsZSBzdWJtaXQgYnV0dG9uXG4gICAgICAgICAgICAgICAgJCgnYnV0dG9uI3VwbG9hZEltYWdlQnV0dG9uJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIC8vZ290IGEgZmlsZSwgZW5hYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICQoJ2J1dHRvbiN1cGxvYWRJbWFnZUJ1dHRvbicpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwbG9hZCBhbiBpbWFnZSB0byB0aGUgaW1hZ2UgbGlicmFyeVxuICAgICAgICAqL1xuICAgICAgICB1cGxvYWRJbWFnZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCdpbnB1dCNpbWFnZUZpbGUnKS52YWwoKSAhPT0gJycgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgb2xkIGFsZXJ0c1xuICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5tb2RhbC1hbGVydHMgPiAqJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9kaXNhYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICQoJ2J1dHRvbiN1cGxvYWRJbWFnZUJ1dHRvbicpLmFkZENsYXNzKCdkaXNhYmxlJyk7XG5cbiAgICAgICAgICAgICAgICAvL3Nob3cgbG9hZGVyXG4gICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLmxvYWRlcicpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBmb3JtID0gJCgnZm9ybSNpbWFnZVVwbG9hZEZvcm0nKTtcbiAgICAgICAgICAgICAgICB2YXIgZm9ybWRhdGEgPSBmYWxzZTtcblxuICAgICAgICAgICAgICAgIGlmICh3aW5kb3cuRm9ybURhdGEpe1xuICAgICAgICAgICAgICAgICAgICBmb3JtZGF0YSA9IG5ldyBGb3JtRGF0YShmb3JtWzBdKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIGZvcm1BY3Rpb24gPSBmb3JtLmF0dHIoJ2FjdGlvbicpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybCA6IGZvcm1BY3Rpb24sXG4gICAgICAgICAgICAgICAgICAgIGRhdGEgOiBmb3JtZGF0YSA/IGZvcm1kYXRhIDogZm9ybS5zZXJpYWxpemUoKSxcbiAgICAgICAgICAgICAgICAgICAgY2FjaGUgOiBmYWxzZSxcbiAgICAgICAgICAgICAgICAgICAgY29udGVudFR5cGUgOiBmYWxzZSxcbiAgICAgICAgICAgICAgICAgICAgcHJvY2Vzc0RhdGEgOiBmYWxzZSxcbiAgICAgICAgICAgICAgICAgICAgZGF0YVR5cGU6IFwianNvblwiLFxuICAgICAgICAgICAgICAgICAgICB0eXBlIDogJ1BPU1QnXG4gICAgICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXQpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uXG4gICAgICAgICAgICAgICAgICAgICQoJ2J1dHRvbiN1cGxvYWRJbWFnZUJ1dHRvbicpLmFkZENsYXNzKCdkaXNhYmxlJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2hpZGUgbG9hZGVyXG4gICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5sb2FkZXInKS5mYWRlT3V0KDUwMCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMCApIHsvL2Vycm9yXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsIC5tb2RhbC1hbGVydHMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcblx0XHRcdFxuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDEgKSB7Ly9zdWNjZXNzXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vYXBwZW5kIG15IGltYWdlXG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjbXlJbWFnZXNUYWIgPiAqJykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjbXlJbWFnZXNUYWInKS5hcHBlbmQoICQocmV0Lm15SW1hZ2VzKSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2ltYWdlTW9kYWwgLm1vZGFsLWFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7JCgnI2ltYWdlTW9kYWwgLm1vZGFsLWFsZXJ0cyA+IConKS5mYWRlT3V0KDUwMCk7fSwgMzAwMCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICBhbGVydCgnTm8gaW1hZ2Ugc2VsZWN0ZWQnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRpc3BsYXlzIGltYWdlIGluIG1vZGFsXG4gICAgICAgICovXG4gICAgICAgIGltYWdlSW5Nb2RhbDogZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgXHRcdFxuICAgIFx0XHR2YXIgdGhlU3JjID0gJCh0aGlzKS5jbG9zZXN0KCcuaW1hZ2UnKS5maW5kKCdpbWcnKS5hdHRyKCdzcmMnKTtcbiAgICBcdFx0XG4gICAgXHRcdCQoJ2ltZyN0aGVQaWMnKS5hdHRyKCdzcmMnLCB0aGVTcmMpO1xuICAgIFx0XHRcbiAgICBcdFx0JCgnI3ZpZXdQaWMnKS5tb2RhbCgnc2hvdycpO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZXMgYW4gaW1hZ2UgZnJvbSB0aGUgbGlicmFyeVxuICAgICAgICAqL1xuICAgICAgICBkZWxldGVJbWFnZTogZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgXHRcdFxuICAgIFx0XHR2YXIgdG9EZWwgPSAkKHRoaXMpLmNsb3Nlc3QoJy5pbWFnZScpO1xuICAgIFx0XHR2YXIgdGhlVVJMID0gJCh0aGlzKS5hdHRyKCdkYXRhLWltZycpO1xuICAgIFx0XHRcbiAgICBcdFx0JCgnI2RlbGV0ZUltYWdlTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuICAgIFx0XHRcbiAgICBcdFx0JCgnYnV0dG9uI2RlbGV0ZUltYWdlQnV0dG9uJykuY2xpY2soZnVuY3Rpb24oKXtcbiAgICBcdFx0XG4gICAgXHRcdFx0JCh0aGlzKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICBcdFx0XHRcbiAgICBcdFx0XHR2YXIgdGhlQnV0dG9uID0gJCh0aGlzKTtcbiAgICBcdFx0XG4gICAgXHRcdFx0JC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wiYXNzZXRzL2RlbEltYWdlXCIsXG4gICAgXHRcdFx0XHRkYXRhOiB7ZmlsZTogdGhlVVJMfSxcbiAgICBcdFx0XHRcdHR5cGU6ICdwb3N0J1xuICAgIFx0XHRcdH0pLmRvbmUoZnVuY3Rpb24oKXtcbiAgICBcdFx0XHRcbiAgICBcdFx0XHRcdHRoZUJ1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0JCgnI2RlbGV0ZUltYWdlTW9kYWwnKS5tb2RhbCgnaGlkZScpO1xuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHR0b0RlbC5mYWRlT3V0KDgwMCwgZnVuY3Rpb24oKXtcbiAgICBcdFx0XHRcdFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0JCh0aGlzKS5yZW1vdmUoKTtcbiAgICBcdFx0XHRcdFx0XHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0fSk7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0fSk7XG4gICAgXHRcdFxuICAgIFx0XHRcbiAgICBcdFx0fSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuICAgIFxuICAgIGltYWdlTGlicmFyeS5pbml0KCk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpe1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgY2FudmFzRWxlbWVudCA9IHJlcXVpcmUoJy4vY2FudmFzRWxlbWVudC5qcycpLkVsZW1lbnQ7XG5cdHZhciBiQ29uZmlnID0gcmVxdWlyZSgnLi9jb25maWcuanMnKTtcblx0dmFyIHNpdGVCdWlsZGVyID0gcmVxdWlyZSgnLi9idWlsZGVyLmpzJyk7XG4gICAgdmFyIHB1Ymxpc2hlciA9IHJlcXVpcmUoJy4uL3ZlbmRvci9wdWJsaXNoZXInKTtcblxuICAgIHZhciBzdHlsZWVkaXRvciA9IHtcblxuICAgICAgICBidXR0b25TYXZlQ2hhbmdlczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NhdmVTdHlsaW5nJyksXG4gICAgICAgIGFjdGl2ZUVsZW1lbnQ6IHt9LCAvL2hvbGRzIHRoZSBlbGVtZW50IGN1cnJlbnR5IGJlaW5nIGVkaXRlZFxuICAgICAgICBhbGxTdHlsZUl0ZW1zT25DYW52YXM6IFtdLFxuICAgICAgICBfb2xkSWNvbjogW10sXG4gICAgICAgIHN0eWxlRWRpdG9yOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc3R5bGVFZGl0b3InKSxcbiAgICAgICAgZm9ybVN0eWxlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc3R5bGluZ0Zvcm0nKSxcbiAgICAgICAgYnV0dG9uUmVtb3ZlRWxlbWVudDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZUVsZW1lbnRDb25maXJtJyksXG4gICAgICAgIGJ1dHRvbkNsb25lRWxlbWVudDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2Nsb25lRWxlbWVudEJ1dHRvbicpLFxuICAgICAgICBidXR0b25SZXNldEVsZW1lbnQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdyZXNldFN0eWxlQnV0dG9uJyksXG4gICAgICAgIHNlbGVjdExpbmtzSW5lcm5hbDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludGVybmFsTGlua3NEcm9wZG93bicpLFxuICAgICAgICBzZWxlY3RMaW5rc1BhZ2VzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZUxpbmtzRHJvcGRvd24nKSxcbiAgICAgICAgdmlkZW9JbnB1dFlvdXR1YmU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd5b3V0dWJlSUQnKSxcbiAgICAgICAgdmlkZW9JbnB1dFZpbWVvOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndmltZW9JRCcpLFxuICAgICAgICBpbnB1dEN1c3RvbUxpbms6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbnRlcm5hbExpbmtzQ3VzdG9tJyksXG4gICAgICAgIGxpbmtJbWFnZTogbnVsbCxcbiAgICAgICAgbGlua0ljb246IG51bGwsXG4gICAgICAgIGlucHV0TGlua1RleHQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsaW5rVGV4dCcpLFxuICAgICAgICBzZWxlY3RJY29uczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ljb25zJyksXG4gICAgICAgIGJ1dHRvbkRldGFpbHNBcHBsaWVkSGlkZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RldGFpbHNBcHBsaWVkTWVzc2FnZUhpZGUnKSxcbiAgICAgICAgYnV0dG9uQ2xvc2VTdHlsZUVkaXRvcjogZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI3N0eWxlRWRpdG9yID4gYS5jbG9zZScpLFxuICAgICAgICB1bFBhZ2VMaXN0OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZUxpc3QnKSxcbiAgICAgICAgcmVzcG9uc2l2ZVRvZ2dsZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Jlc3BvbnNpdmVUb2dnbGUnKSxcbiAgICAgICAgdGhlU2NyZWVuOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2NyZWVuJyksXG5cbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5zdWJzY3JpYmUoJ2Nsb3NlU3R5bGVFZGl0b3InLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuY2xvc2VTdHlsZUVkaXRvcigpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5zdWJzY3JpYmUoJ29uQmxvY2tMb2FkZWQnLCBmdW5jdGlvbiAoYmxvY2spIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZXR1cENhbnZhc0VsZW1lbnRzKGJsb2NrKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIuc3Vic2NyaWJlKCdvblNldE1vZGUnLCBmdW5jdGlvbiAobW9kZSkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc3BvbnNpdmVNb2RlQ2hhbmdlKG1vZGUpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIC8vZXZlbnRzXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU2F2ZUNoYW5nZXMpLm9uKCdjbGljaycsIHRoaXMudXBkYXRlU3R5bGluZyk7XG4gICAgICAgICAgICAkKHRoaXMuZm9ybVN0eWxlKS5vbignZm9jdXMnLCAnaW5wdXQnLCB0aGlzLmFuaW1hdGVTdHlsZUlucHV0SW4pLm9uKCdibHVyJywgJ2lucHV0JywgdGhpcy5hbmltYXRlU3R5bGVJbnB1dE91dCk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uUmVtb3ZlRWxlbWVudCkub24oJ2NsaWNrJywgdGhpcy5kZWxldGVFbGVtZW50KTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25DbG9uZUVsZW1lbnQpLm9uKCdjbGljaycsIHRoaXMuY2xvbmVFbGVtZW50KTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25SZXNldEVsZW1lbnQpLm9uKCdjbGljaycsIHRoaXMucmVzZXRFbGVtZW50KTtcbiAgICAgICAgICAgICQodGhpcy52aWRlb0lucHV0WW91dHViZSkub24oJ2ZvY3VzJywgZnVuY3Rpb24oKXsgJChzdHlsZWVkaXRvci52aWRlb0lucHV0VmltZW8pLnZhbCgnJyk7IH0pO1xuICAgICAgICAgICAgJCh0aGlzLnZpZGVvSW5wdXRWaW1lbykub24oJ2ZvY3VzJywgZnVuY3Rpb24oKXsgJChzdHlsZWVkaXRvci52aWRlb0lucHV0WW91dHViZSkudmFsKCcnKTsgfSk7XG4gICAgICAgICAgICAkKHRoaXMuaW5wdXRDdXN0b21MaW5rKS5vbignZm9jdXMnLCB0aGlzLnJlc2V0U2VsZWN0QWxsTGlua3MpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbkRldGFpbHNBcHBsaWVkSGlkZSkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXskKHRoaXMpLnBhcmVudCgpLmZhZGVPdXQoNTAwKTt9KTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25DbG9zZVN0eWxlRWRpdG9yKS5vbignY2xpY2snLCB0aGlzLmNsb3NlU3R5bGVFZGl0b3IpO1xuICAgICAgICAgICAgJCh0aGlzLmlucHV0Q3VzdG9tTGluaykub24oJ2ZvY3VzJywgdGhpcy5pbnB1dEN1c3RvbUxpbmtGb2N1cykub24oJ2JsdXInLCB0aGlzLmlucHV0Q3VzdG9tTGlua0JsdXIpO1xuICAgICAgICAgICAgJChkb2N1bWVudCkub24oJ21vZGVDb250ZW50IG1vZGVCbG9ja3MnLCAnYm9keScsIHRoaXMuZGVBY3RpdmF0ZU1vZGUpO1xuXG4gICAgICAgICAgICAvL2Nob3NlbiBmb250LWF3ZXNvbWUgZHJvcGRvd25cbiAgICAgICAgICAgICQodGhpcy5zZWxlY3RJY29ucykuY2hvc2VuKHsnc2VhcmNoX2NvbnRhaW5zJzogdHJ1ZX0pO1xuXG4gICAgICAgICAgICAvL2NoZWNrIGlmIGZvcm1EYXRhIGlzIHN1cHBvcnRlZFxuICAgICAgICAgICAgaWYgKCF3aW5kb3cuRm9ybURhdGEpe1xuICAgICAgICAgICAgICAgIHRoaXMuaGlkZUZpbGVVcGxvYWRzKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vbGlzdGVuIGZvciB0aGUgYmVmb3JlU2F2ZSBldmVudFxuICAgICAgICAgICAgJCgnYm9keScpLm9uKCdiZWZvcmVTYXZlJywgdGhpcy5jbG9zZVN0eWxlRWRpdG9yKTtcblxuICAgICAgICAgICAgLy9yZXNwb25zaXZlIHRvZ2dsZVxuICAgICAgICAgICAgJCh0aGlzLnJlc3BvbnNpdmVUb2dnbGUpLm9uKCdjbGljaycsICdhJywgdGhpcy50b2dnbGVSZXNwb25zaXZlQ2xpY2spO1xuXG4gICAgICAgICAgICAvL3NldCB0aGUgZGVmYXVsdCByZXNwb25zaXZlIG1vZGVcbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLmJ1aWxkZXJVSS5jdXJyZW50UmVzcG9uc2l2ZU1vZGUgPSBPYmplY3Qua2V5cyhiQ29uZmlnLnJlc3BvbnNpdmVNb2RlcylbMF07XG5cbiAgICAgICAgfSxcblxuICAgICAgICAvKlxuICAgICAgICAgICAgRXZlbnQgaGFuZGxlciBmb3IgcmVzcG9uc2l2ZSBtb2RlIGxpbmtzXG4gICAgICAgICovXG4gICAgICAgIHRvZ2dsZVJlc3BvbnNpdmVDbGljazogZnVuY3Rpb24gKGUpIHtcblxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNwb25zaXZlTW9kZUNoYW5nZSh0aGlzLmdldEF0dHJpYnV0ZSgnZGF0YS1yZXNwb25zaXZlJykpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgVG9nZ2xlcyB0aGUgcmVzcG9uc2l2ZSBtb2RlXG4gICAgICAgICovXG4gICAgICAgIHJlc3BvbnNpdmVNb2RlQ2hhbmdlOiBmdW5jdGlvbiAobW9kZSkge1xuXG4gICAgICAgICAgICB2YXIgbGlua3MsXG4gICAgICAgICAgICAgICAgaTtcblxuICAgICAgICAgICAgLy9VSSBzdHVmZlxuICAgICAgICAgICAgbGlua3MgPSBzdHlsZWVkaXRvci5yZXNwb25zaXZlVG9nZ2xlLnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyk7XG5cbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgbGlua3MubGVuZ3RoOyBpKysgKSBsaW5rc1tpXS5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcblxuICAgICAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtkYXRhLXJlc3BvbnNpdmU9XCInICsgbW9kZSArICdcIl0nKS5wYXJlbnROb2RlLmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuXG5cbiAgICAgICAgICAgIGZvciAoIHZhciBrZXkgaW4gYkNvbmZpZy5yZXNwb25zaXZlTW9kZXMgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoIGJDb25maWcucmVzcG9uc2l2ZU1vZGVzLmhhc093blByb3BlcnR5KGtleSkgKSB0aGlzLnRoZVNjcmVlbi5jbGFzc0xpc3QucmVtb3ZlKGtleSk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCBiQ29uZmlnLnJlc3BvbnNpdmVNb2Rlc1ttb2RlXSApIHtcblxuICAgICAgICAgICAgICAgIHRoaXMudGhlU2NyZWVuLmNsYXNzTGlzdC5hZGQobW9kZSk7XG4gICAgICAgICAgICAgICAgJCh0aGlzLnRoZVNjcmVlbikuYW5pbWF0ZSh7d2lkdGg6IGJDb25maWcucmVzcG9uc2l2ZU1vZGVzW21vZGVdfSwgNjUwLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vaGVpZ2h0IGFkanVzdG1lbnRcbiAgICAgICAgICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5hY3RpdmVQYWdlLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBzaXRlQnVpbGRlci5idWlsZGVyVUkuY3VycmVudFJlc3BvbnNpdmVNb2RlID0gbW9kZTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEFjdGl2YXRlcyBzdHlsZSBlZGl0b3IgbW9kZVxuICAgICAgICAqL1xuICAgICAgICBzZXR1cENhbnZhc0VsZW1lbnRzOiBmdW5jdGlvbihibG9jaykge1xuXG4gICAgICAgICAgICBpZiAoIGJsb2NrID09PSB1bmRlZmluZWQgKSByZXR1cm4gZmFsc2U7XG5cbiAgICAgICAgICAgIHZhciBpO1xuXG4gICAgICAgICAgICAvL2NyZWF0ZSBhbiBvYmplY3QgZm9yIGV2ZXJ5IGVkaXRhYmxlIGVsZW1lbnQgb24gdGhlIGNhbnZhcyBhbmQgc2V0dXAgaXQncyBldmVudHNcblxuICAgICAgICAgICAgZm9yKCB2YXIga2V5IGluIGJDb25maWcuZWRpdGFibGVJdGVtcyApIHtcblxuICAgICAgICAgICAgICAgICQoYmxvY2suZnJhbWUpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICsgJyAnKyBrZXkgKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZXR1cENhbnZhc0VsZW1lbnRzT25FbGVtZW50KHRoaXMsIGtleSk7XG5cbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgU2V0cyB1cCBjYW52YXMgZWxlbWVudHMgb24gZWxlbWVudFxuICAgICAgICAqL1xuICAgICAgICBzZXR1cENhbnZhc0VsZW1lbnRzT25FbGVtZW50OiBmdW5jdGlvbiAoZWxlbWVudCwga2V5KSB7XG5cbiAgICAgICAgICAgIC8vRWxlbWVudCBvYmplY3QgZXh0ZW50aW9uXG4gICAgICAgICAgICBjYW52YXNFbGVtZW50LnByb3RvdHlwZS5jbGlja0hhbmRsZXIgPSBmdW5jdGlvbihlbCkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnN0eWxlQ2xpY2sodGhpcyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgbmV3RWxlbWVudCA9IG5ldyBjYW52YXNFbGVtZW50KGVsZW1lbnQpO1xuXG4gICAgICAgICAgICBuZXdFbGVtZW50LmVkaXRhYmxlQXR0cmlidXRlcyA9IGJDb25maWcuZWRpdGFibGVJdGVtc1trZXldO1xuICAgICAgICAgICAgbmV3RWxlbWVudC5zZXRQYXJlbnRCbG9jaygpO1xuICAgICAgICAgICAgbmV3RWxlbWVudC5hY3RpdmF0ZSgpO1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5hbGxTdHlsZUl0ZW1zT25DYW52YXMucHVzaCggbmV3RWxlbWVudCApO1xuXG4gICAgICAgICAgICBpZiAoIHR5cGVvZiBrZXkgIT09IHVuZGVmaW5lZCApICQoZWxlbWVudCkuYXR0cignZGF0YS1zZWxlY3RvcicsIGtleSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBFdmVudCBoYW5kbGVyIGZvciB3aGVuIHRoZSBzdHlsZSBlZGl0b3IgaXMgZW52b2tlZCBvbiBhbiBpdGVtXG4gICAgICAgICovXG4gICAgICAgIHN0eWxlQ2xpY2s6IGZ1bmN0aW9uKGVsZW1lbnQpIHtcblxuICAgICAgICAgICAgLy9pZiB3ZSBoYXZlIGFuIGFjdGl2ZSBlbGVtZW50LCBtYWtlIGl0IHVuYWN0aXZlXG4gICAgICAgICAgICBpZiggT2JqZWN0LmtleXModGhpcy5hY3RpdmVFbGVtZW50KS5sZW5ndGggIT09IDApIHtcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGl2ZUVsZW1lbnQuYWN0aXZhdGUoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy9zZXQgdGhlIGFjdGl2ZSBlbGVtZW50XG4gICAgICAgICAgICB0aGlzLmFjdGl2ZUVsZW1lbnQgPSBlbGVtZW50O1xuXG4gICAgICAgICAgICAvL3VuYmluZCBob3ZlciBhbmQgY2xpY2sgZXZlbnRzIGFuZCBtYWtlIHRoaXMgaXRlbSBhY3RpdmVcbiAgICAgICAgICAgIHRoaXMuYWN0aXZlRWxlbWVudC5zZXRPcGVuKCk7XG5cbiAgICAgICAgICAgIHZhciB0aGVTZWxlY3RvciA9ICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2RhdGEtc2VsZWN0b3InKTtcblxuICAgICAgICAgICAgJCgnI2VkaXRpbmdFbGVtZW50JykudGV4dCggdGhlU2VsZWN0b3IgKTtcblxuICAgICAgICAgICAgLy9hY3RpdmF0ZSBmaXJzdCB0YWJcbiAgICAgICAgICAgICQoJyNkZXRhaWxUYWJzIGE6Zmlyc3QnKS5jbGljaygpO1xuXG4gICAgICAgICAgICAvL2hpZGUgYWxsIGJ5IGRlZmF1bHRcbiAgICAgICAgICAgICQoJ3VsI2RldGFpbFRhYnMgbGk6Z3QoMCknKS5oaWRlKCk7XG5cbiAgICAgICAgICAgIC8vY29udGVudCBlZGl0b3I/XG4gICAgICAgICAgICBmb3IoIHZhciBpdGVtIGluIGJDb25maWcuZWRpdGFibGVJdGVtcyApIHtcblxuICAgICAgICAgICAgICAgIGlmKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXMuaGFzT3duUHJvcGVydHkoaXRlbSkgJiYgaXRlbSA9PT0gdGhlU2VsZWN0b3IgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbaXRlbV0uaW5kZXhPZignY29udGVudCcpICE9PSAtMSApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgLy9lZGl0IGNvbnRlbnRcbiAgICAgICAgICAgICAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkNsaWNrQ29udGVudCcsIGVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vd2hhdCBhcmUgd2UgZGVhbGluZyB3aXRoP1xuICAgICAgICAgICAgaWYoICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnIHx8ICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkge1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lZGl0TGluayh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgIH1cblxuXHRcdFx0aWYoICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0lNRycgKXtcblxuICAgICAgICAgICAgICAgIHRoaXMuZWRpdEltYWdlKHRoaXMuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgfVxuXG5cdFx0XHRpZiggJCh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignZGF0YS10eXBlJykgPT09ICd2aWRlbycgKSB7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmVkaXRWaWRlbyh0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgIH1cblxuXHRcdFx0aWYoICQodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmhhc0NsYXNzKCdmYScpICkge1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lZGl0SWNvbih0aGlzLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy9sb2FkIHRoZSBhdHRyaWJ1dGVzXG4gICAgICAgICAgICB0aGlzLmJ1aWxkZVN0eWxlRWxlbWVudHModGhlU2VsZWN0b3IpO1xuXG4gICAgICAgICAgICAvL29wZW4gc2lkZSBwYW5lbFxuICAgICAgICAgICAgdGhpcy50b2dnbGVTaWRlUGFuZWwoJ29wZW4nKTtcblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgZHluYW1pY2FsbHkgZ2VuZXJhdGVzIHRoZSBmb3JtIGZpZWxkcyBmb3IgZWRpdGluZyBhbiBlbGVtZW50cyBzdHlsZSBhdHRyaWJ1dGVzXG4gICAgICAgICovXG4gICAgICAgIGJ1aWxkZVN0eWxlRWxlbWVudHM6IGZ1bmN0aW9uKHRoZVNlbGVjdG9yKSB7XG5cbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBvbGQgb25lcyBmaXJzdFxuICAgICAgICAgICAgJCgnI3N0eWxlRWxlbWVudHMgPiAqOm5vdCgjc3R5bGVFbFRlbXBsYXRlKScpLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG5cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBmb3IoIHZhciB4PTA7IHg8YkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXS5sZW5ndGg7IHgrKyApIHtcblxuICAgICAgICAgICAgICAgIC8vY3JlYXRlIHN0eWxlIGVsZW1lbnRzXG4gICAgICAgICAgICAgICAgdmFyIG5ld1N0eWxlRWwgPSAkKCcjc3R5bGVFbFRlbXBsYXRlJykuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmF0dHIoJ2lkJywgJycpO1xuICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuZmluZCgnLmNvbnRyb2wtbGFiZWwnKS50ZXh0KCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdK1wiOlwiICk7XG5cbiAgICAgICAgICAgICAgICBpZiggdGhlU2VsZWN0b3IgKyBcIiA6IFwiICsgYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSBpbiBiQ29uZmlnLmVkaXRhYmxlSXRlbU9wdGlvbnMpIHsvL3dlJ3ZlIGdvdCBhIGRyb3Bkb3duIGluc3RlYWQgb2Ygb3BlbiB0ZXh0IGlucHV0XG5cbiAgICAgICAgICAgICAgICAgICAgbmV3U3R5bGVFbC5maW5kKCdpbnB1dCcpLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICAgICAgICAgIHZhciBuZXdEcm9wRG93biA9ICQoJzxzZWxlY3QgY2xhc3M9XCJmb3JtLWNvbnRyb2wgc2VsZWN0IHNlbGVjdC1wcmltYXJ5IGJ0bi1ibG9jayBzZWxlY3Qtc21cIj48L3NlbGVjdD4nKTtcbiAgICAgICAgICAgICAgICAgICAgbmV3RHJvcERvd24uYXR0cignbmFtZScsIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0pO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgZm9yKCB2YXIgej0wOyB6PGJDb25maWcuZWRpdGFibGVJdGVtT3B0aW9uc1sgdGhlU2VsZWN0b3IrXCIgOiBcIitiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdIF0ubGVuZ3RoOyB6KysgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdPcHRpb24gPSAkKCc8b3B0aW9uIHZhbHVlPVwiJytiQ29uZmlnLmVkaXRhYmxlSXRlbU9wdGlvbnNbdGhlU2VsZWN0b3IrXCIgOiBcIitiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdXVt6XSsnXCI+JytiQ29uZmlnLmVkaXRhYmxlSXRlbU9wdGlvbnNbdGhlU2VsZWN0b3IrXCIgOiBcIitiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdXVt6XSsnPC9vcHRpb24+Jyk7XG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIGJDb25maWcuZWRpdGFibGVJdGVtT3B0aW9uc1t0aGVTZWxlY3RvcitcIiA6IFwiK2JDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF1dW3pdID09PSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9jdXJyZW50IHZhbHVlLCBtYXJrZWQgYXMgc2VsZWN0ZWRcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdPcHRpb24uYXR0cignc2VsZWN0ZWQnLCAndHJ1ZScpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Ryb3BEb3duLmFwcGVuZCggbmV3T3B0aW9uICk7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuYXBwZW5kKCBuZXdEcm9wRG93biApO1xuICAgICAgICAgICAgICAgICAgICBuZXdEcm9wRG93bi5zZWxlY3QyKCk7XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwuZmluZCgnaW5wdXQnKS52YWwoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0gKSApLmF0dHIoJ25hbWUnLCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSA9PT0gJ2JhY2tncm91bmQtaW1hZ2UnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmZpbmQoJ2lucHV0JykuYmluZCgnZm9jdXMnLCBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHRoZUlucHV0ID0gJCh0aGlzKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykubW9kYWwoJ3Nob3cnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcjaW1hZ2VNb2RhbCAuaW1hZ2UgYnV0dG9uLnVzZUltYWdlJykudW5iaW5kKCdjbGljaycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykub24oJ2NsaWNrJywgJy5pbWFnZSBidXR0b24udXNlSW1hZ2UnLCBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jc3MoJ2JhY2tncm91bmQtaW1hZ2UnLCAgJ3VybChcIicrJCh0aGlzKS5hdHRyKCdkYXRhLXVybCcpKydcIiknKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL3VwZGF0ZSBsaXZlIGltYWdlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlucHV0LnZhbCggJ3VybChcIicrJCh0aGlzKS5hdHRyKCdkYXRhLXVybCcpKydcIiknICk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9oaWRlIG1vZGFsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsJykubW9kYWwoJ2hpZGUnKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL3dlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGJDb25maWcuZWRpdGFibGVJdGVtc1t0aGVTZWxlY3Rvcl1beF0uaW5kZXhPZihcImNvbG9yXCIpID4gLTEgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgIT09ICd0cmFuc3BhcmVudCcgJiYgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICE9PSAnbm9uZScgJiYgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggYkNvbmZpZy5lZGl0YWJsZUl0ZW1zW3RoZVNlbGVjdG9yXVt4XSApICE9PSAnJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld1N0eWxlRWwudmFsKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuY3NzKCBiQ29uZmlnLmVkaXRhYmxlSXRlbXNbdGhlU2VsZWN0b3JdW3hdICkgKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmZpbmQoJ2lucHV0Jykuc3BlY3RydW0oe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHByZWZlcnJlZEZvcm1hdDogXCJoZXhcIixcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaG93UGFsZXR0ZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhbGxvd0VtcHR5OiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNob3dJbnB1dDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYWxldHRlOiBbXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiMwMDBcIixcIiM0NDRcIixcIiM2NjZcIixcIiM5OTlcIixcIiNjY2NcIixcIiNlZWVcIixcIiNmM2YzZjNcIixcIiNmZmZcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiNmMDBcIixcIiNmOTBcIixcIiNmZjBcIixcIiMwZjBcIixcIiMwZmZcIixcIiMwMGZcIixcIiM5MGZcIixcIiNmMGZcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiNmNGNjY2NcIixcIiNmY2U1Y2RcIixcIiNmZmYyY2NcIixcIiNkOWVhZDNcIixcIiNkMGUwZTNcIixcIiNjZmUyZjNcIixcIiNkOWQyZTlcIixcIiNlYWQxZGNcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiNlYTk5OTlcIixcIiNmOWNiOWNcIixcIiNmZmU1OTlcIixcIiNiNmQ3YThcIixcIiNhMmM0YzlcIixcIiM5ZmM1ZThcIixcIiNiNGE3ZDZcIixcIiNkNWE2YmRcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiNlMDY2NjZcIixcIiNmNmIyNmJcIixcIiNmZmQ5NjZcIixcIiM5M2M0N2RcIixcIiM3NmE1YWZcIixcIiM2ZmE4ZGNcIixcIiM4ZTdjYzNcIixcIiNjMjdiYTBcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiNjMDBcIixcIiNlNjkxMzhcIixcIiNmMWMyMzJcIixcIiM2YWE4NGZcIixcIiM0NTgxOGVcIixcIiMzZDg1YzZcIixcIiM2NzRlYTdcIixcIiNhNjRkNzlcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiM5MDBcIixcIiNiNDVmMDZcIixcIiNiZjkwMDBcIixcIiMzODc2MWRcIixcIiMxMzRmNWNcIixcIiMwYjUzOTRcIixcIiMzNTFjNzVcIixcIiM3NDFiNDdcIl0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFtcIiM2MDBcIixcIiM3ODNmMDRcIixcIiM3ZjYwMDBcIixcIiMyNzRlMTNcIixcIiMwYzM0M2RcIixcIiMwNzM3NjNcIixcIiMyMDEyNGRcIixcIiM0YzExMzBcIl1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBdXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBuZXdTdHlsZUVsLmNzcygnZGlzcGxheScsICdibG9jaycpO1xuXG4gICAgICAgICAgICAgICAgJCgnI3N0eWxlRWxlbWVudHMnKS5hcHBlbmQoIG5ld1N0eWxlRWwgKTtcblxuICAgICAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvciBmb3JtI3N0eWxpbmdGb3JtJykuaGVpZ2h0KCdhdXRvJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEFwcGxpZXMgdXBkYXRlZCBzdHlsaW5nIHRvIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlU3R5bGluZzogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHZhciBlbGVtZW50SUQsXG4gICAgICAgICAgICAgICAgbGVuZ3RoO1xuXG4gICAgICAgICAgICAkKCcjc3R5bGVFZGl0b3IgI3RhYjEgLmZvcm0tZ3JvdXA6bm90KCNzdHlsZUVsVGVtcGxhdGUpIGlucHV0LCAjc3R5bGVFZGl0b3IgI3RhYjEgLmZvcm0tZ3JvdXA6bm90KCNzdHlsZUVsVGVtcGxhdGUpIHNlbGVjdCcpLmVhY2goZnVuY3Rpb24oKXtcblxuXHRcdFx0XHRpZiggJCh0aGlzKS5hdHRyKCduYW1lJykgIT09IHVuZGVmaW5lZCApIHtcblxuICAgICAgICAgICAgICAgIFx0JChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNzcyggJCh0aGlzKS5hdHRyKCduYW1lJyksICAkKHRoaXMpLnZhbCgpKTtcblxuXHRcdFx0XHR9XG5cbiAgICAgICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgICAgICBpZiggc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94ICkge1xuXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuXG4gICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLmNzcyggJCh0aGlzKS5hdHRyKCduYW1lJyksICAkKHRoaXMpLnZhbCgpICk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy9saW5rc1xuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHtcblxuICAgICAgICAgICAgICAgIC8vY2hhbmdlIHRoZSBocmVmIHByb3A/XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmhyZWYgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW50ZXJuYWxMaW5rc0N1c3RvbScpLnZhbHVlO1xuXG4gICAgICAgICAgICAgICAgbGVuZ3RoID0gc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNoaWxkTm9kZXMubGVuZ3RoO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZG9lcyB0aGUgbGluayBjb250YWluIGFuIGltYWdlP1xuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5saW5rSW1hZ2UgKSBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2hpbGROb2Rlc1tsZW5ndGgtMV0ubm9kZVZhbHVlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xpbmtUZXh0JykudmFsdWU7XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAoIHN0eWxlZWRpdG9yLmxpbmtJY29uICkgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmNoaWxkTm9kZXNbbGVuZ3RoLTFdLm5vZGVWYWx1ZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsaW5rVGV4dCcpLnZhbHVlO1xuICAgICAgICAgICAgICAgIGVsc2Ugc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50LmlubmVyVGV4dCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsaW5rVGV4dCcpLnZhbHVlO1xuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5hdHRyKCdocmVmJywgJCgnaW5wdXQjaW50ZXJuYWxMaW5rc0N1c3RvbScpLnZhbCgpKTtcblxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkge1xuXG4gICAgICAgICAgICAgICAgLy9jaGFuZ2UgdGhlIGhyZWYgcHJvcD9cbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQucGFyZW50Tm9kZS5ocmVmID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludGVybmFsTGlua3NDdXN0b20nKS52YWx1ZTtcblxuICAgICAgICAgICAgICAgIGxlbmd0aCA9IHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jaGlsZE5vZGVzLmxlbmd0aDtcbiAgICAgICAgICAgICAgICBcblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkucGFyZW50KCkuYXR0cignaHJlZicsICQoJ2lucHV0I2ludGVybmFsTGlua3NDdXN0b20nKS52YWwoKSk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vaWNvbnNcbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuaGFzQ2xhc3MoJ2ZhJykgKSB7XG5cbiAgICAgICAgICAgICAgICAvL291dCB3aXRoIHRoZSBvbGQsIGluIHdpdGggdGhlIG5ldyA6KVxuICAgICAgICAgICAgICAgIC8vZ2V0IGljb24gY2xhc3MgbmFtZSwgc3RhcnRpbmcgd2l0aCBmYS1cbiAgICAgICAgICAgICAgICB2YXIgZ2V0ID0gJC5ncmVwKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jbGFzc05hbWUuc3BsaXQoXCIgXCIpLCBmdW5jdGlvbih2LCBpKXtcblxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdi5pbmRleE9mKCdmYS0nKSA9PT0gMDtcblxuICAgICAgICAgICAgICAgIH0pLmpvaW4oKTtcblxuICAgICAgICAgICAgICAgIC8vaWYgdGhlIGljb25zIGlzIGJlaW5nIGNoYW5nZWQsIHNhdmUgdGhlIG9sZCBvbmUgc28gd2UgY2FuIHJlc2V0IGl0IGlmIG5lZWRlZFxuXG4gICAgICAgICAgICAgICAgaWYoIGdldCAhPT0gJCgnc2VsZWN0I2ljb25zJykudmFsKCkgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLl9vbGRJY29uWyQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpXSA9IGdldDtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5yZW1vdmVDbGFzcyggZ2V0ICkuYWRkQ2xhc3MoICQoJ3NlbGVjdCNpY29ucycpLnZhbCgpICk7XG5cblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG4gICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLnJlbW92ZUNsYXNzKCBnZXQgKS5hZGRDbGFzcyggJCgnc2VsZWN0I2ljb25zJykudmFsKCkgKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIC8qIEVORCBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy92aWRlbyBVUkxcbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignZGF0YS10eXBlJykgPT09ICd2aWRlbycgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiggJCgnaW5wdXQjeW91dHViZUlEJykudmFsKCkgIT09ICcnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcmV2KCkuYXR0cignc3JjJywgXCIvL3d3dy55b3V0dWJlLmNvbS9lbWJlZC9cIiskKCcjdmlkZW9fVGFiIGlucHV0I3lvdXR1YmVJRCcpLnZhbCgpKTtcblxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggJCgnaW5wdXQjdmltZW9JRCcpLnZhbCgpICE9PSAnJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJldigpLmF0dHIoJ3NyYycsIFwiLy9wbGF5ZXIudmltZW8uY29tL3ZpZGVvL1wiKyQoJyN2aWRlb19UYWIgaW5wdXQjdmltZW9JRCcpLnZhbCgpK1wiP3RpdGxlPTAmYW1wO2J5bGluZT0wJmFtcDtwb3J0cmFpdD0wXCIpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiggJCgnaW5wdXQjeW91dHViZUlEJykudmFsKCkgIT09ICcnICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjJytzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnIycrZWxlbWVudElEKS5wcmV2KCkuYXR0cignc3JjJywgXCIvL3d3dy55b3V0dWJlLmNvbS9lbWJlZC9cIiskKCcjdmlkZW9fVGFiIGlucHV0I3lvdXR1YmVJRCcpLnZhbCgpKTtcblxuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoICQoJ2lucHV0I3ZpbWVvSUQnKS52YWwoKSAhPT0gJycgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLnByZXYoKS5hdHRyKCdzcmMnLCBcIi8vcGxheWVyLnZpbWVvLmNvbS92aWRlby9cIiskKCcjdmlkZW9fVGFiIGlucHV0I3ZpbWVvSUQnKS52YWwoKStcIj90aXRsZT0wJmFtcDtieWxpbmU9MCZhbXA7cG9ydHJhaXQ9MFwiKTtcblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICQoJyNkZXRhaWxzQXBwbGllZE1lc3NhZ2UnKS5mYWRlSW4oNjAwLCBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpeyAkKCcjZGV0YWlsc0FwcGxpZWRNZXNzYWdlJykuZmFkZU91dCgxMDAwKTsgfSwgMzAwMCk7XG5cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvL2FkanVzdCBmcmFtZSBoZWlnaHRcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2suaGVpZ2h0QWRqdXN0bWVudCgpO1xuXG5cbiAgICAgICAgICAgIC8vd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tDaGFuZ2UnLCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLCAnY2hhbmdlJyk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBvbiBmb2N1cywgd2UnbGwgbWFrZSB0aGUgaW5wdXQgZmllbGRzIHdpZGVyXG4gICAgICAgICovXG4gICAgICAgIGFuaW1hdGVTdHlsZUlucHV0SW46IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKHRoaXMpLmNzcygncG9zaXRpb24nLCAnYWJzb2x1dGUnKTtcbiAgICAgICAgICAgICQodGhpcykuY3NzKCdyaWdodCcsICcwcHgnKTtcbiAgICAgICAgICAgICQodGhpcykuYW5pbWF0ZSh7J3dpZHRoJzogJzEwMCUnfSwgNTAwKTtcbiAgICAgICAgICAgICQodGhpcykuZm9jdXMoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB0aGlzLnNlbGVjdCgpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBvbiBibHVyLCB3ZSdsbCByZXZlcnQgdGhlIGlucHV0IGZpZWxkcyB0byB0aGVpciBvcmlnaW5hbCBzaXplXG4gICAgICAgICovXG4gICAgICAgIGFuaW1hdGVTdHlsZUlucHV0T3V0OiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCh0aGlzKS5hbmltYXRlKHsnd2lkdGgnOiAnNDIlJ30sIDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcygncG9zaXRpb24nLCAncmVsYXRpdmUnKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcygncmlnaHQnLCAnYXV0bycpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBidWlsZHMgdGhlIGRyb3Bkb3duIHdpdGggI2Jsb2NrcyBvbiB0aGlzIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgYnVpbGRCbG9ja3NEcm9wZG93bjogZnVuY3Rpb24gKGN1cnJlbnRWYWwpIHtcblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLnNlbGVjdDIoJ2Rlc3Ryb3knKTtcblxuICAgICAgICAgICAgaWYoIHR5cGVvZiBjdXJyZW50VmFsID09PSAndW5kZWZpbmVkJyApIGN1cnJlbnRWYWwgPSBudWxsO1xuXG4gICAgICAgICAgICB2YXIgeCxcbiAgICAgICAgICAgICAgICBuZXdPcHRpb247XG5cbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5pbm5lckhUTUwgPSAnJztcblxuICAgICAgICAgICAgbmV3T3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgICAgICBuZXdPcHRpb24uaW5uZXJUZXh0ID0gXCJDaG9vc2UgYSBibG9ja1wiO1xuICAgICAgICAgICAgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgndmFsdWUnLCAnIycpO1xuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLmFwcGVuZENoaWxkKG5ld09wdGlvbik7XG5cbiAgICAgICAgICAgIGZvciAoIHggPSAwOyB4IDwgc2l0ZUJ1aWxkZXIuc2l0ZS5hY3RpdmVQYWdlLmJsb2Nrcy5sZW5ndGg7IHgrKyApIHtcblxuICAgICAgICAgICAgICAgIHZhciBmcmFtZURvYyA9IHNpdGVCdWlsZGVyLnNpdGUuYWN0aXZlUGFnZS5ibG9ja3NbeF0uZnJhbWVEb2N1bWVudDtcbiAgICAgICAgICAgICAgICB2YXIgcGFnZUNvbnRhaW5lciAgPSBmcmFtZURvYy5xdWVyeVNlbGVjdG9yKGJDb25maWcucGFnZUNvbnRhaW5lcik7XG4gICAgICAgICAgICAgICAgdmFyIHRoZUlEID0gcGFnZUNvbnRhaW5lci5jaGlsZHJlblswXS5pZDtcblxuICAgICAgICAgICAgICAgIG5ld09wdGlvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ09QVElPTicpO1xuICAgICAgICAgICAgICAgIG5ld09wdGlvbi5pbm5lclRleHQgPSAnIycgKyB0aGVJRDtcbiAgICAgICAgICAgICAgICBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCd2YWx1ZScsICcjJyArIHRoZUlEKTtcbiAgICAgICAgICAgICAgICBpZiggY3VycmVudFZhbCA9PT0gJyMnICsgdGhlSUQgKSBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCdzZWxlY3RlZCcsIHRydWUpO1xuXG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsLmFwcGVuZENoaWxkKG5ld09wdGlvbik7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLnNlbGVjdDIoKTtcbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS50cmlnZ2VyKCdjaGFuZ2UnKTtcblxuICAgICAgICAgICAgJChzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwpLm9mZignY2hhbmdlJykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5pbnB1dEN1c3RvbUxpbmsudmFsdWUgPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnJlc2V0UGFnZURyb3Bkb3duKCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGJsdXIgZXZlbnQgaGFuZGxlciBmb3IgdGhlIGN1c3RvbSBsaW5rIGlucHV0XG4gICAgICAgICovXG4gICAgICAgIGlucHV0Q3VzdG9tTGlua0JsdXI6IGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgIHZhciB2YWx1ZSA9IGUudGFyZ2V0LnZhbHVlLFxuICAgICAgICAgICAgICAgIHg7XG5cbiAgICAgICAgICAgIC8vcGFnZXMgbWF0Y2g/XG4gICAgICAgICAgICBmb3IgKCB4ID0gMDsgeCA8IHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMucXVlcnlTZWxlY3RvckFsbCgnb3B0aW9uJykubGVuZ3RoOyB4KysgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoIHZhbHVlID09PSBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLnF1ZXJ5U2VsZWN0b3JBbGwoJ29wdGlvbicpW3hdLnZhbHVlICkge1xuXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMuc2VsZWN0ZWRJbmRleCA9IHg7XG4gICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykudHJpZ2dlcignY2hhbmdlJykuc2VsZWN0MigpO1xuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vYmxvY2tzIG1hdGNoP1xuICAgICAgICAgICAgZm9yICggeCA9IDA7IHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5xdWVyeVNlbGVjdG9yQWxsKCdvcHRpb24nKS5sZW5ndGg7IHgrKyApIHtcblxuICAgICAgICAgICAgICAgIGlmICggdmFsdWUgPT09IHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbC5xdWVyeVNlbGVjdG9yQWxsKCdvcHRpb24nKVt4XS52YWx1ZSApIHtcblxuICAgICAgICAgICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwuc2VsZWN0ZWRJbmRleCA9IHg7XG4gICAgICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NJbmVybmFsKS50cmlnZ2VyKCdjaGFuZ2UnKS5zZWxlY3QyKCk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGZvY3VzIGV2ZW50IGhhbmRsZXIgZm9yIHRoZSBjdXN0b20gbGluayBpbnB1dFxuICAgICAgICAqL1xuICAgICAgICBpbnB1dEN1c3RvbUxpbmtGb2N1czogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5yZXNldFBhZ2VEcm9wZG93bigpO1xuICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzZXRCbG9ja0Ryb3Bkb3duKCk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBidWlsZHMgdGhlIGRyb3Bkb3duIHdpdGggcGFnZXMgdG8gbGluayB0b1xuICAgICAgICAqL1xuICAgICAgICBidWlsZFBhZ2VzRHJvcGRvd246IGZ1bmN0aW9uIChjdXJyZW50VmFsKSB7XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykuc2VsZWN0MignZGVzdHJveScpO1xuXG4gICAgICAgICAgICBpZiggdHlwZW9mIGN1cnJlbnRWYWwgPT09ICd1bmRlZmluZWQnICkgY3VycmVudFZhbCA9IG51bGw7XG5cbiAgICAgICAgICAgIHZhciB4LFxuICAgICAgICAgICAgICAgIG5ld09wdGlvbjtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5pbm5lckhUTUwgPSAnJztcblxuICAgICAgICAgICAgbmV3T3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnT1BUSU9OJyk7XG4gICAgICAgICAgICBuZXdPcHRpb24uaW5uZXJUZXh0ID0gXCJDaG9vc2UgYSBwYWdlXCI7XG4gICAgICAgICAgICBuZXdPcHRpb24uc2V0QXR0cmlidXRlKCd2YWx1ZScsICcjJyk7XG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc1BhZ2VzLmFwcGVuZENoaWxkKG5ld09wdGlvbik7XG5cbiAgICAgICAgICAgIGZvciggeCA9IDA7IHggPCBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlcy5sZW5ndGg7IHgrKyApIHtcblxuICAgICAgICAgICAgICAgIG5ld09wdGlvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ09QVElPTicpO1xuICAgICAgICAgICAgICAgIG5ld09wdGlvbi5pbm5lclRleHQgPSBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1t4XS5uYW1lO1xuICAgICAgICAgICAgICAgIG5ld09wdGlvbi5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgc2l0ZUJ1aWxkZXIuc2l0ZS5zaXRlUGFnZXNbeF0ubmFtZSArICcuaHRtbCcpO1xuICAgICAgICAgICAgICAgIGlmKCBjdXJyZW50VmFsID09PSBzaXRlQnVpbGRlci5zaXRlLnNpdGVQYWdlc1t4XS5uYW1lICsgJy5odG1sJykgbmV3T3B0aW9uLnNldEF0dHJpYnV0ZSgnc2VsZWN0ZWQnLCB0cnVlKTtcblxuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMuYXBwZW5kQ2hpbGQobmV3T3B0aW9uKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzUGFnZXMpLnNlbGVjdDIoKTtcbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykudHJpZ2dlcignY2hhbmdlJyk7XG5cbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykub2ZmKCdjaGFuZ2UnKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmlucHV0Q3VzdG9tTGluay52YWx1ZSA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IucmVzZXRCbG9ja0Ryb3Bkb3duKCk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHJlc2V0IHRoZSBibG9jayBsaW5rIGRyb3Bkb3duXG4gICAgICAgICovXG4gICAgICAgIHJlc2V0QmxvY2tEcm9wZG93bjogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5zZWxlY3RMaW5rc0luZXJuYWwuc2VsZWN0ZWRJbmRleCA9IDA7XG4gICAgICAgICAgICAkKHN0eWxlZWRpdG9yLnNlbGVjdExpbmtzSW5lcm5hbCkuc2VsZWN0MignZGVzdHJveScpLnNlbGVjdDIoKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHJlc2V0IHRoZSBwYWdlIGxpbmsgZHJvcGRvd25cbiAgICAgICAgKi9cbiAgICAgICAgcmVzZXRQYWdlRHJvcGRvd246IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcy5zZWxlY3RlZEluZGV4ID0gMDtcbiAgICAgICAgICAgICQoc3R5bGVlZGl0b3Iuc2VsZWN0TGlua3NQYWdlcykuc2VsZWN0MignZGVzdHJveScpLnNlbGVjdDIoKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHdoZW4gdGhlIGNsaWNrZWQgZWxlbWVudCBpcyBhbiBhbmNob3IgdGFnIChvciBoYXMgYSBwYXJlbnQgYW5jaG9yIHRhZylcbiAgICAgICAgKi9cbiAgICAgICAgZWRpdExpbms6IGZ1bmN0aW9uKGVsKSB7XG5cbiAgICAgICAgICAgIHZhciB0aGVIcmVmO1xuXG4gICAgICAgICAgICAkKCdhI2xpbmtfTGluaycpLnBhcmVudCgpLnNob3coKTtcblxuICAgICAgICAgICAgLy9zZXQgdGhlSHJlZlxuICAgICAgICAgICAgaWYoICQoZWwpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkge1xuXG4gICAgICAgICAgICAgICAgdGhlSHJlZiA9ICQoZWwpLmF0dHIoJ2hyZWYnKTtcblxuICAgICAgICAgICAgfSBlbHNlIGlmKCAkKGVsKS5wYXJlbnQoKS5wcm9wKCd0YWdOYW1lJykgPT09ICdBJyApIHtcblxuICAgICAgICAgICAgICAgIHRoZUhyZWYgPSAkKGVsKS5wYXJlbnQoKS5hdHRyKCdocmVmJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgc3R5bGVlZGl0b3IuYnVpbGRQYWdlc0Ryb3Bkb3duKHRoZUhyZWYpO1xuICAgICAgICAgICAgc3R5bGVlZGl0b3IuYnVpbGRCbG9ja3NEcm9wZG93bih0aGVIcmVmKTtcbiAgICAgICAgICAgIHN0eWxlZWRpdG9yLmlucHV0Q3VzdG9tTGluay52YWx1ZSA9IHRoZUhyZWY7XG5cbiAgICAgICAgICAgIC8vZ3JhYiBhbiBpbWFnZT9cbiAgICAgICAgICAgIGlmICggZWwucXVlcnlTZWxlY3RvcignaW1nJykgKSBzdHlsZWVkaXRvci5saW5rSW1hZ2UgPSBlbC5xdWVyeVNlbGVjdG9yKCdpbWcnKTtcbiAgICAgICAgICAgIGVsc2Ugc3R5bGVlZGl0b3IubGlua0ltYWdlID0gbnVsbDtcblxuICAgICAgICAgICAgLy9ncmFiIGFuIGljb24/XG4gICAgICAgICAgICBpZiAoIGVsLnF1ZXJ5U2VsZWN0b3IoJy5mYScpICkgc3R5bGVlZGl0b3IubGlua0ljb24gPSBlbC5xdWVyeVNlbGVjdG9yKCcuZmEnKS5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICAgICAgICBlbHNlIHN0eWxlZWRpdG9yLmxpbmtJY29uID0gbnVsbDtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3IuaW5wdXRMaW5rVGV4dC52YWx1ZSA9IGVsLmlubmVyVGV4dDtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHdoZW4gdGhlIGNsaWNrZWQgZWxlbWVudCBpcyBhbiBpbWFnZVxuICAgICAgICAqL1xuICAgICAgICBlZGl0SW1hZ2U6IGZ1bmN0aW9uKGVsKSB7XG5cbiAgICAgICAgICAgICQoJ2EjaW1nX0xpbmsnKS5wYXJlbnQoKS5zaG93KCk7XG5cbiAgICAgICAgICAgIC8vc2V0IHRoZSBjdXJyZW50IFNSQ1xuICAgICAgICAgICAgJCgnLmltYWdlRmlsZVRhYicpLmZpbmQoJ2lucHV0I2ltYWdlVVJMJykudmFsKCAkKGVsKS5hdHRyKCdzcmMnKSApO1xuXG4gICAgICAgICAgICAvL3Jlc2V0IHRoZSBmaWxlIHVwbG9hZFxuICAgICAgICAgICAgJCgnLmltYWdlRmlsZVRhYicpLmZpbmQoJ2EuZmlsZWlucHV0LWV4aXN0cycpLmNsaWNrKCk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICB3aGVuIHRoZSBjbGlja2VkIGVsZW1lbnQgaXMgYSB2aWRlbyBlbGVtZW50XG4gICAgICAgICovXG4gICAgICAgIGVkaXRWaWRlbzogZnVuY3Rpb24oZWwpIHtcblxuICAgICAgICAgICAgdmFyIG1hdGNoUmVzdWx0cztcblxuICAgICAgICAgICAgJCgnYSN2aWRlb19MaW5rJykucGFyZW50KCkuc2hvdygpO1xuICAgICAgICAgICAgJCgnYSN2aWRlb19MaW5rJykuY2xpY2soKTtcblxuICAgICAgICAgICAgLy9pbmplY3QgY3VycmVudCB2aWRlbyBJRCxjaGVjayBpZiB3ZSdyZSBkZWFsaW5nIHdpdGggWW91dHViZSBvciBWaW1lb1xuXG4gICAgICAgICAgICBpZiggJChlbCkucHJldigpLmF0dHIoJ3NyYycpLmluZGV4T2YoXCJ2aW1lby5jb21cIikgPiAtMSApIHsvL3ZpbWVvXG5cbiAgICAgICAgICAgICAgICBtYXRjaFJlc3VsdHMgPSAkKGVsKS5wcmV2KCkuYXR0cignc3JjJykubWF0Y2goL3BsYXllclxcLnZpbWVvXFwuY29tXFwvdmlkZW9cXC8oWzAtOV0qKS8pO1xuXG4gICAgICAgICAgICAgICAgJCgnI3ZpZGVvX1RhYiBpbnB1dCN2aW1lb0lEJykudmFsKCBtYXRjaFJlc3VsdHNbbWF0Y2hSZXN1bHRzLmxlbmd0aC0xXSApO1xuICAgICAgICAgICAgICAgICQoJyN2aWRlb19UYWIgaW5wdXQjeW91dHViZUlEJykudmFsKCcnKTtcblxuICAgICAgICAgICAgfSBlbHNlIHsvL3lvdXR1YmVcblxuICAgICAgICAgICAgICAgIC8vdGVtcCA9ICQoZWwpLnByZXYoKS5hdHRyKCdzcmMnKS5zcGxpdCgnLycpO1xuICAgICAgICAgICAgICAgIHZhciByZWdFeHAgPSAvLiooPzp5b3V0dS5iZVxcL3x2XFwvfHVcXC9cXHdcXC98ZW1iZWRcXC98d2F0Y2hcXD92PSkoW14jXFwmXFw/XSopLiovO1xuICAgICAgICAgICAgICAgIG1hdGNoUmVzdWx0cyA9ICQoZWwpLnByZXYoKS5hdHRyKCdzcmMnKS5tYXRjaChyZWdFeHApO1xuXG4gICAgICAgICAgICAgICAgJCgnI3ZpZGVvX1RhYiBpbnB1dCN5b3V0dWJlSUQnKS52YWwoIG1hdGNoUmVzdWx0c1sxXSApO1xuICAgICAgICAgICAgICAgICQoJyN2aWRlb19UYWIgaW5wdXQjdmltZW9JRCcpLnZhbCgnJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHdoZW4gdGhlIGNsaWNrZWQgZWxlbWVudCBpcyBhbiBmYSBpY29uXG4gICAgICAgICovXG4gICAgICAgIGVkaXRJY29uOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCgnYSNpY29uX0xpbmsnKS5wYXJlbnQoKS5zaG93KCk7XG5cbiAgICAgICAgICAgIC8vZ2V0IGljb24gY2xhc3MgbmFtZSwgc3RhcnRpbmcgd2l0aCBmYS1cbiAgICAgICAgICAgIHZhciBnZXQgPSAkLmdyZXAodGhpcy5hY3RpdmVFbGVtZW50LmVsZW1lbnQuY2xhc3NOYW1lLnNwbGl0KFwiIFwiKSwgZnVuY3Rpb24odiwgaSl7XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gdi5pbmRleE9mKCdmYS0nKSA9PT0gMDtcblxuICAgICAgICAgICAgfSkuam9pbigpO1xuXG4gICAgICAgICAgICAkKCdzZWxlY3QjaWNvbnMgb3B0aW9uJykuZWFjaChmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgaWYoICQodGhpcykudmFsKCkgPT09IGdldCApIHtcblxuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmF0dHIoJ3NlbGVjdGVkJywgdHJ1ZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgJCgnI2ljb25zJykudHJpZ2dlcignY2hvc2VuOnVwZGF0ZWQnKTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGUgc2VsZWN0ZWQgZWxlbWVudFxuICAgICAgICAqL1xuICAgICAgICBkZWxldGVFbGVtZW50OiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmVmb3JlRGVsZXRlJyk7XG5cbiAgICAgICAgICAgIHZhciB0b0RlbDtcblxuICAgICAgICAgICAgLy9kZXRlcm1pbmUgd2hhdCB0byBkZWxldGVcbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpID09PSAnQScgKSB7Ly9hbmNvclxuXG4gICAgICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5wcm9wKCd0YWdOYW1lJykgPT09J0xJJyApIHsvL2Nsb25lIHRoZSBMSVxuXG4gICAgICAgICAgICAgICAgICAgIHRvRGVsID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICB0b0RlbCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KTtcblxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfSBlbHNlIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpID09PSAnSU1HJyApIHsvL2ltYWdlXG5cbiAgICAgICAgICAgICAgICBpZiggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpLnByb3AoJ3RhZ05hbWUnKSA9PT0gJ0EnICkgey8vY2xvbmUgdGhlIEFcblxuICAgICAgICAgICAgICAgICAgICB0b0RlbCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKTtcblxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAgICAgdG9EZWwgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0gZWxzZSB7Ly9ldmVyeXRoaW5nIGVsc2VcblxuICAgICAgICAgICAgICAgIHRvRGVsID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgdG9EZWwuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICB2YXIgcmFuZG9tRWwgPSAkKHRoaXMpLmNsb3Nlc3QoJ2JvZHknKS5maW5kKCcqOmZpcnN0Jyk7XG5cbiAgICAgICAgICAgICAgICB0b0RlbC5yZW1vdmUoKTtcblxuICAgICAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIHZhciBlbGVtZW50SUQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKTtcblxuICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgICAgIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2suaGVpZ2h0QWRqdXN0bWVudCgpO1xuXG4gICAgICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgc2l0ZUJ1aWxkZXIuc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICQoJyNkZWxldGVFbGVtZW50JykubW9kYWwoJ2hpZGUnKTtcblxuICAgICAgICAgICAgc3R5bGVlZGl0b3IuY2xvc2VTdHlsZUVkaXRvcigpO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2ssICdjaGFuZ2UnKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGNsb25lcyB0aGUgc2VsZWN0ZWQgZWxlbWVudFxuICAgICAgICAqL1xuICAgICAgICBjbG9uZUVsZW1lbnQ6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CZWZvcmVDbG9uZScpO1xuXG4gICAgICAgICAgICB2YXIgdGhlQ2xvbmUsIHRoZUNsb25lMiwgdGhlT25lLCBjbG9uZWQsIGNsb25lUGFyZW50LCBlbGVtZW50SUQ7XG5cbiAgICAgICAgICAgIGlmKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkuaGFzQ2xhc3MoJ3Byb3BDbG9uZScpICkgey8vY2xvbmUgdGhlIHBhcmVudCBlbGVtZW50XG5cbiAgICAgICAgICAgICAgICB0aGVDbG9uZSA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgIHRoZUNsb25lLmZpbmQoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wcm9wKCd0YWdOYW1lJykgKS5hdHRyKCdzdHlsZScsICcnKTtcblxuICAgICAgICAgICAgICAgIHRoZUNsb25lMiA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgIHRoZUNsb25lMi5maW5kKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpICkuYXR0cignc3R5bGUnLCAnJyk7XG5cbiAgICAgICAgICAgICAgICB0aGVPbmUgPSB0aGVDbG9uZS5maW5kKCAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucHJvcCgndGFnTmFtZScpICk7XG4gICAgICAgICAgICAgICAgY2xvbmVkID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLnBhcmVudCgpO1xuXG4gICAgICAgICAgICAgICAgY2xvbmVQYXJlbnQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkucGFyZW50KCkucGFyZW50KCk7XG5cbiAgICAgICAgICAgIH0gZWxzZSB7Ly9jbG9uZSB0aGUgZWxlbWVudCBpdHNlbGZcblxuICAgICAgICAgICAgICAgIHRoZUNsb25lID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmNsb25lKCk7XG5cbiAgICAgICAgICAgICAgICB0aGVDbG9uZS5hdHRyKCdzdHlsZScsICcnKTtcblxuICAgICAgICAgICAgICAgIC8qaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgdGhlQ2xvbmUuYXR0cignaWQnLCAnJykudW5pcXVlSWQoKTtcbiAgICAgICAgICAgICAgICB9Ki9cblxuICAgICAgICAgICAgICAgIHRoZUNsb25lMiA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgIHRoZUNsb25lMi5hdHRyKCdzdHlsZScsICcnKTtcblxuICAgICAgICAgICAgICAgIC8qXG4gICAgICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgdGhlQ2xvbmUyLmF0dHIoJ2lkJywgdGhlQ2xvbmUuYXR0cignaWQnKSk7XG4gICAgICAgICAgICAgICAgfSovXG5cbiAgICAgICAgICAgICAgICB0aGVPbmUgPSB0aGVDbG9uZTtcbiAgICAgICAgICAgICAgICBjbG9uZWQgPSAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCk7XG5cbiAgICAgICAgICAgICAgICBjbG9uZVBhcmVudCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5wYXJlbnQoKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjbG9uZWQuYWZ0ZXIoIHRoZUNsb25lICk7XG5cbiAgICAgICAgICAgIC8qIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgaWYoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCApIHtcblxuICAgICAgICAgICAgICAgIGVsZW1lbnRJRCA9ICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICQoJyMnK3N0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuc2FuZGJveCkuY29udGVudHMoKS5maW5kKCcjJytlbGVtZW50SUQpLmFmdGVyKCB0aGVDbG9uZTIgKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvKiBFTkQgU0FOREJPWCAqL1xuXG4gICAgICAgICAgICAvL21ha2Ugc3VyZSB0aGUgbmV3IGVsZW1lbnQgZ2V0cyB0aGUgcHJvcGVyIGV2ZW50cyBzZXQgb24gaXRcbiAgICAgICAgICAgIHZhciBuZXdFbGVtZW50ID0gbmV3IGNhbnZhc0VsZW1lbnQodGhlT25lLmdldCgwKSk7XG4gICAgICAgICAgICBuZXdFbGVtZW50LmFjdGl2YXRlKCk7XG5cbiAgICAgICAgICAgIC8vcG9zc2libGUgaGVpZ2h0IGFkanVzdG1lbnRzXG4gICAgICAgICAgICBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnBhcmVudEJsb2NrLmhlaWdodEFkanVzdG1lbnQoKTtcblxuICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlQnVpbGRlci5zaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CbG9ja0NoYW5nZScsIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQucGFyZW50QmxvY2ssICdjaGFuZ2UnKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIHJlc2V0cyB0aGUgYWN0aXZlIGVsZW1lbnRcbiAgICAgICAgKi9cbiAgICAgICAgcmVzZXRFbGVtZW50OiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgaWYoICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5jbG9zZXN0KCdib2R5Jykud2lkdGgoKSAhPT0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLndpZHRoKCkgKSB7XG5cbiAgICAgICAgICAgICAgICAkKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignc3R5bGUnLCAnJykuY3NzKHsnb3V0bGluZSc6ICczcHggZGFzaGVkIHJlZCcsICdjdXJzb3InOiAncG9pbnRlcid9KTtcblxuICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdzdHlsZScsICcnKS5jc3MoeydvdXRsaW5lJzogJzNweCBkYXNoZWQgcmVkJywgJ291dGxpbmUtb2Zmc2V0JzonLTNweCcsICdjdXJzb3InOiAncG9pbnRlcid9KTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvKiBTQU5EQk9YICovXG5cbiAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LnNhbmRib3ggKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgZWxlbWVudElEID0gJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyk7XG4gICAgICAgICAgICAgICAgJCgnIycrc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5zYW5kYm94KS5jb250ZW50cygpLmZpbmQoJyMnK2VsZW1lbnRJRCkuYXR0cignc3R5bGUnLCAnJyk7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLyogRU5EIFNBTkRCT1ggKi9cblxuICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yIGZvcm0jc3R5bGluZ0Zvcm0nKS5oZWlnaHQoICQoJyNzdHlsZUVkaXRvciBmb3JtI3N0eWxpbmdGb3JtJykuaGVpZ2h0KCkrXCJweFwiICk7XG5cbiAgICAgICAgICAgICQoJyNzdHlsZUVkaXRvciBmb3JtI3N0eWxpbmdGb3JtIC5mb3JtLWdyb3VwOm5vdCgjc3R5bGVFbFRlbXBsYXRlKScpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgfSk7XG5cblxuICAgICAgICAgICAgLy9yZXNldCBpY29uXG5cbiAgICAgICAgICAgIGlmKCBzdHlsZWVkaXRvci5fb2xkSWNvblskKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudCkuYXR0cignaWQnKV0gIT09IG51bGwgKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgZ2V0ID0gJC5ncmVwKHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWxlbWVudC5jbGFzc05hbWUuc3BsaXQoXCIgXCIpLCBmdW5jdGlvbih2LCBpKXtcblxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdi5pbmRleE9mKCdmYS0nKSA9PT0gMDtcblxuICAgICAgICAgICAgICAgIH0pLmpvaW4oKTtcblxuICAgICAgICAgICAgICAgICQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5yZW1vdmVDbGFzcyggZ2V0ICkuYWRkQ2xhc3MoIHN0eWxlZWRpdG9yLl9vbGRJY29uWyQoc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5lbGVtZW50KS5hdHRyKCdpZCcpXSApO1xuXG4gICAgICAgICAgICAgICAgJCgnc2VsZWN0I2ljb25zIG9wdGlvbicpLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICBpZiggJCh0aGlzKS52YWwoKSA9PT0gc3R5bGVlZGl0b3IuX29sZEljb25bJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2lkJyldICkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmF0dHIoJ3NlbGVjdGVkJywgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjaWNvbnMnKS50cmlnZ2VyKCdjaG9zZW46dXBkYXRlZCcpO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHNldFRpbWVvdXQoIGZ1bmN0aW9uKCl7c3R5bGVlZGl0b3IuYnVpbGRlU3R5bGVFbGVtZW50cyggJChzdHlsZWVkaXRvci5hY3RpdmVFbGVtZW50LmVsZW1lbnQpLmF0dHIoJ2RhdGEtc2VsZWN0b3InKSApO30sIDU1MCk7XG5cbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5wYXJlbnRCbG9jaywgJ2NoYW5nZScpO1xuXG4gICAgICAgIH0sXG5cblxuICAgICAgICByZXNldFNlbGVjdExpbmtzUGFnZXM6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKCcjaW50ZXJuYWxMaW5rc0Ryb3Bkb3duJykuc2VsZWN0MigndmFsJywgJyMnKTtcblxuICAgICAgICB9LFxuXG4gICAgICAgIHJlc2V0U2VsZWN0TGlua3NJbnRlcm5hbDogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgICQoJyNwYWdlTGlua3NEcm9wZG93bicpLnNlbGVjdDIoJ3ZhbCcsICcjJyk7XG5cbiAgICAgICAgfSxcblxuICAgICAgICByZXNldFNlbGVjdEFsbExpbmtzOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgJCgnI2ludGVybmFsTGlua3NEcm9wZG93bicpLnNlbGVjdDIoJ3ZhbCcsICcjJyk7XG4gICAgICAgICAgICAkKCcjcGFnZUxpbmtzRHJvcGRvd24nKS5zZWxlY3QyKCd2YWwnLCAnIycpO1xuICAgICAgICAgICAgdGhpcy5zZWxlY3QoKTtcblxuICAgICAgICB9LFxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBoaWRlcyBmaWxlIHVwbG9hZCBmb3Jtc1xuICAgICAgICAqL1xuICAgICAgICBoaWRlRmlsZVVwbG9hZHM6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAkKCdmb3JtI2ltYWdlVXBsb2FkRm9ybScpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJyNpbWFnZU1vZGFsICN1cGxvYWRUYWJMSScpLmhpZGUoKTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGNsb3NlcyB0aGUgc3R5bGUgZWRpdG9yXG4gICAgICAgICovXG4gICAgICAgIGNsb3NlU3R5bGVFZGl0b3I6IGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgIGlmICggZSAhPT0gdW5kZWZpbmVkICkgZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICAgICBpZiAoIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWRpdGFibGVBdHRyaWJ1dGVzICYmIHN0eWxlZWRpdG9yLmFjdGl2ZUVsZW1lbnQuZWRpdGFibGVBdHRyaWJ1dGVzLmluZGV4T2YoJ2NvbnRlbnQnKSA9PT0gLTEgKSB7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5yZW1vdmVPdXRsaW5lKCk7XG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IuYWN0aXZlRWxlbWVudC5hY3RpdmF0ZSgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiggJCgnI3N0eWxlRWRpdG9yJykuY3NzKCdsZWZ0JykgPT09ICcwcHgnICkge1xuXG4gICAgICAgICAgICAgICAgc3R5bGVlZGl0b3IudG9nZ2xlU2lkZVBhbmVsKCdjbG9zZScpO1xuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICB0b2dnbGVzIHRoZSBzaWRlIHBhbmVsXG4gICAgICAgICovXG4gICAgICAgIHRvZ2dsZVNpZGVQYW5lbDogZnVuY3Rpb24odmFsKSB7XG5cbiAgICAgICAgICAgIGlmKCB2YWwgPT09ICdvcGVuJyAmJiAkKCcjc3R5bGVFZGl0b3InKS5jc3MoJ2xlZnQnKSA9PT0gJy0zMDBweCcgKSB7XG4gICAgICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yJykuYW5pbWF0ZSh7J2xlZnQnOiAnMHB4J30sIDI1MCk7XG4gICAgICAgICAgICB9IGVsc2UgaWYoIHZhbCA9PT0gJ2Nsb3NlJyAmJiAkKCcjc3R5bGVFZGl0b3InKS5jc3MoJ2xlZnQnKSA9PT0gJzBweCcgKSB7XG4gICAgICAgICAgICAgICAgJCgnI3N0eWxlRWRpdG9yJykuYW5pbWF0ZSh7J2xlZnQnOiAnLTMwMHB4J30sIDI1MCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSxcblxuICAgIH07XG5cbiAgICBzdHlsZWVkaXRvci5pbml0KCk7XG5cbiAgICBleHBvcnRzLnN0eWxlZWRpdG9yID0gc3R5bGVlZGl0b3I7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblxuLyogZ2xvYmFscyBzaXRlVXJsOmZhbHNlLCBiYXNlVXJsOmZhbHNlICovXG4gICAgXCJ1c2Ugc3RyaWN0XCI7XG4gICAgICAgIFxuICAgIHZhciBhcHBVSSA9IHtcbiAgICAgICAgXG4gICAgICAgIGZpcnN0TWVudVdpZHRoOiAxOTAsXG4gICAgICAgIHNlY29uZE1lbnVXaWR0aDogMzAwLFxuICAgICAgICBsb2FkZXJBbmltYXRpb246IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsb2FkZXInKSxcbiAgICAgICAgc2Vjb25kTWVudVRyaWdnZXJDb250YWluZXJzOiAkKCcjbWVudSAjbWFpbiAjZWxlbWVudENhdHMsICNtZW51ICNtYWluICN0ZW1wbGF0ZXNVbCcpLFxuICAgICAgICBzaXRlVXJsOiBzaXRlVXJsLFxuICAgICAgICBiYXNlVXJsOiBiYXNlVXJsLFxuICAgICAgICBcbiAgICAgICAgc2V0dXA6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIEZhZGUgdGhlIGxvYWRlciBhbmltYXRpb25cbiAgICAgICAgICAgICQoYXBwVUkubG9hZGVyQW5pbWF0aW9uKS5mYWRlT3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCgnI21lbnUnKS5hbmltYXRlKHsnbGVmdCc6IC1hcHBVSS5maXJzdE1lbnVXaWR0aH0sIDEwMDApO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYnNcbiAgICAgICAgICAgICQoXCIubmF2LXRhYnMgYVwiKS5vbignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnRhYihcInNob3dcIik7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJChcInNlbGVjdC5zZWxlY3RcIikuc2VsZWN0MigpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCc6cmFkaW8sIDpjaGVja2JveCcpLnJhZGlvY2hlY2soKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVG9vbHRpcHNcbiAgICAgICAgICAgICQoXCJbZGF0YS10b2dnbGU9dG9vbHRpcF1cIikudG9vbHRpcChcImhpZGVcIik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYmxlOiBUb2dnbGUgYWxsIGNoZWNrYm94ZXNcbiAgICAgICAgICAgICQoJy50YWJsZSAudG9nZ2xlLWFsbCA6Y2hlY2tib3gnKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgICAgICAgICB2YXIgY2ggPSAkdGhpcy5wcm9wKCdjaGVja2VkJyk7XG4gICAgICAgICAgICAgICAgJHRoaXMuY2xvc2VzdCgnLnRhYmxlJykuZmluZCgndGJvZHkgOmNoZWNrYm94JykucmFkaW9jaGVjayghY2ggPyAndW5jaGVjaycgOiAnY2hlY2snKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBBZGQgc3R5bGUgY2xhc3MgbmFtZSB0byBhIHRvb2x0aXBzXG4gICAgICAgICAgICAkKFwiLnRvb2x0aXBcIikuYWRkQ2xhc3MoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgaWYgKCQodGhpcykucHJldigpLmF0dHIoXCJkYXRhLXRvb2x0aXAtc3R5bGVcIikpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIFwidG9vbHRpcC1cIiArICQodGhpcykucHJldigpLmF0dHIoXCJkYXRhLXRvb2x0aXAtc3R5bGVcIik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoXCIuYnRuLWdyb3VwXCIpLm9uKCdjbGljaycsIFwiYVwiLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnNpYmxpbmdzKCkucmVtb3ZlQ2xhc3MoXCJhY3RpdmVcIikuZW5kKCkuYWRkQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gRm9jdXMgc3RhdGUgZm9yIGFwcGVuZC9wcmVwZW5kIGlucHV0c1xuICAgICAgICAgICAgJCgnLmlucHV0LWdyb3VwJykub24oJ2ZvY3VzJywgJy5mb3JtLWNvbnRyb2wnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jbG9zZXN0KCcuaW5wdXQtZ3JvdXAsIC5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2ZvY3VzJyk7XG4gICAgICAgICAgICB9KS5vbignYmx1cicsICcuZm9ybS1jb250cm9sJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgnLmlucHV0LWdyb3VwLCAuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdmb2N1cycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYmxlOiBUb2dnbGUgYWxsIGNoZWNrYm94ZXNcbiAgICAgICAgICAgICQoJy50YWJsZSAudG9nZ2xlLWFsbCcpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHZhciBjaCA9ICQodGhpcykuZmluZCgnOmNoZWNrYm94JykucHJvcCgnY2hlY2tlZCcpO1xuICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgnLnRhYmxlJykuZmluZCgndGJvZHkgOmNoZWNrYm94JykuY2hlY2tib3goIWNoID8gJ2NoZWNrJyA6ICd1bmNoZWNrJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVGFibGU6IEFkZCBjbGFzcyByb3cgc2VsZWN0ZWRcbiAgICAgICAgICAgICQoJy50YWJsZSB0Ym9keSA6Y2hlY2tib3gnKS5vbignY2hlY2sgdW5jaGVjayB0b2dnbGUnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIHZhciAkdGhpcyA9ICQodGhpcylcbiAgICAgICAgICAgICAgICAsIGNoZWNrID0gJHRoaXMucHJvcCgnY2hlY2tlZCcpXG4gICAgICAgICAgICAgICAgLCB0b2dnbGUgPSBlLnR5cGUgPT09ICd0b2dnbGUnXG4gICAgICAgICAgICAgICAgLCBjaGVja2JveGVzID0gJCgnLnRhYmxlIHRib2R5IDpjaGVja2JveCcpXG4gICAgICAgICAgICAgICAgLCBjaGVja0FsbCA9IGNoZWNrYm94ZXMubGVuZ3RoID09PSBjaGVja2JveGVzLmZpbHRlcignOmNoZWNrZWQnKS5sZW5ndGg7XG5cbiAgICAgICAgICAgICAgICAkdGhpcy5jbG9zZXN0KCd0cicpW2NoZWNrID8gJ2FkZENsYXNzJyA6ICdyZW1vdmVDbGFzcyddKCdzZWxlY3RlZC1yb3cnKTtcbiAgICAgICAgICAgICAgICBpZiAodG9nZ2xlKSAkdGhpcy5jbG9zZXN0KCcudGFibGUnKS5maW5kKCcudG9nZ2xlLWFsbCA6Y2hlY2tib3gnKS5jaGVja2JveChjaGVja0FsbCA/ICdjaGVjaycgOiAndW5jaGVjaycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFN3aXRjaFxuICAgICAgICAgICAgJChcIltkYXRhLXRvZ2dsZT0nc3dpdGNoJ11cIikud3JhcCgnPGRpdiBjbGFzcz1cInN3aXRjaFwiIC8+JykucGFyZW50KCkuYm9vdHN0cmFwU3dpdGNoKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGFwcFVJLnNlY29uZE1lbnVUcmlnZ2VyQ29udGFpbmVycy5vbignY2xpY2snLCAnYTpub3QoLmJ0biknLCBhcHBVSS5zZWNvbmRNZW51QW5pbWF0aW9uKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgc2Vjb25kTWVudUFuaW1hdGlvbjogZnVuY3Rpb24oKXtcbiAgICAgICAgXG4gICAgICAgICAgICAkKCcjbWVudSAjbWFpbiBhJykucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgJCh0aGlzKS5hZGRDbGFzcygnYWN0aXZlJyk7XG5cdFxuICAgICAgICAgICAgLy9zaG93IG9ubHkgdGhlIHJpZ2h0IGVsZW1lbnRzXG4gICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kIHVsIGxpJykuaGlkZSgpO1xuICAgICAgICAgICAgJCgnI21lbnUgI3NlY29uZCB1bCBsaS4nKyQodGhpcykuYXR0cignaWQnKSkuc2hvdygpO1xuXG4gICAgICAgICAgICBpZiggJCh0aGlzKS5hdHRyKCdpZCcpID09PSAnYWxsJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kIHVsI2VsZW1lbnRzIGxpJykuc2hvdygpO1x0XHRcbiAgICAgICAgICAgIH1cblx0XG4gICAgICAgICAgICAkKCcubWVudSAuc2Vjb25kJykuY3NzKCdkaXNwbGF5JywgJ2Jsb2NrJykuc3RvcCgpLmFuaW1hdGUoe1xuICAgICAgICAgICAgICAgIHdpZHRoOiBhcHBVSS5zZWNvbmRNZW51V2lkdGhcbiAgICAgICAgICAgIH0sIDUwMCk7XHRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuICAgIFxuICAgIC8vaW5pdGlhdGUgdGhlIFVJXG4gICAgYXBwVUkuc2V0dXAoKTtcblxuXG4gICAgLy8qKioqIEVYUE9SVFNcbiAgICBtb2R1bGUuZXhwb3J0cy5hcHBVSSA9IGFwcFVJO1xuICAgIFxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuICAgIFwidXNlIHN0cmljdFwiO1xuICAgIFxuICAgIGV4cG9ydHMuZ2V0UmFuZG9tQXJiaXRyYXJ5ID0gZnVuY3Rpb24obWluLCBtYXgpIHtcbiAgICAgICAgcmV0dXJuIE1hdGguZmxvb3IoTWF0aC5yYW5kb20oKSAqIChtYXggLSBtaW4pICsgbWluKTtcbiAgICB9O1xuXG4gICAgZXhwb3J0cy5nZXRQYXJhbWV0ZXJCeU5hbWUgPSBmdW5jdGlvbiAobmFtZSwgdXJsKSB7XG5cbiAgICAgICAgaWYgKCF1cmwpIHVybCA9IHdpbmRvdy5sb2NhdGlvbi5ocmVmO1xuICAgICAgICBuYW1lID0gbmFtZS5yZXBsYWNlKC9bXFxbXFxdXS9nLCBcIlxcXFwkJlwiKTtcbiAgICAgICAgdmFyIHJlZ2V4ID0gbmV3IFJlZ0V4cChcIls/Jl1cIiArIG5hbWUgKyBcIig9KFteJiNdKil8JnwjfCQpXCIpLFxuICAgICAgICAgICAgcmVzdWx0cyA9IHJlZ2V4LmV4ZWModXJsKTtcbiAgICAgICAgaWYgKCFyZXN1bHRzKSByZXR1cm4gbnVsbDtcbiAgICAgICAgaWYgKCFyZXN1bHRzWzJdKSByZXR1cm4gJyc7XG4gICAgICAgIHJldHVybiBkZWNvZGVVUklDb21wb25lbnQocmVzdWx0c1syXS5yZXBsYWNlKC9cXCsvZywgXCIgXCIpKTtcbiAgICAgICAgXG4gICAgfTtcbiAgICBcbn0oKSk7IiwiLyohXG4gKiBwdWJsaXNoZXIuanMgLSAoYykgUnlhbiBGbG9yZW5jZSAyMDExXG4gKiBnaXRodWIuY29tL3JwZmxvcmVuY2UvcHVibGlzaGVyLmpzXG4gKiBNSVQgTGljZW5zZVxuKi9cblxuLy8gVU1EIEJvaWxlcnBsYXRlIFxcby8gJiYgRDpcbihmdW5jdGlvbiAocm9vdCwgZmFjdG9yeSkge1xuICBpZiAodHlwZW9mIGV4cG9ydHMgPT09ICdvYmplY3QnKSB7XG4gICAgbW9kdWxlLmV4cG9ydHMgPSBmYWN0b3J5KCk7IC8vIG5vZGVcbiAgfSBlbHNlIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICBkZWZpbmUoZmFjdG9yeSk7IC8vIGFtZFxuICB9IGVsc2Uge1xuICAgIC8vIHdpbmRvdyB3aXRoIG5vQ29uZmxpY3RcbiAgICB2YXIgX3B1Ymxpc2hlciA9IHJvb3QucHVibGlzaGVyO1xuICAgIHZhciBwdWJsaXNoZXIgPSByb290LnB1Ymxpc2hlciA9IGZhY3RvcnkoKTtcbiAgICByb290LnB1Ymxpc2hlci5ub0NvbmZsaWN0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgcm9vdC5wdWJsaXNoZXIgPSBfcHVibGlzaGVyO1xuICAgICAgcmV0dXJuIHB1Ymxpc2hlcjtcbiAgICB9XG4gIH1cbn0odGhpcywgZnVuY3Rpb24gKCkge1xuXG4gIHZhciBwdWJsaXNoZXIgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgdmFyIHRvcGljcyA9IHt9O1xuICAgIG9iaiA9IG9iaiB8fCB7fTtcblxuICAgIG9iai5wdWJsaXNoID0gZnVuY3Rpb24gKHRvcGljLyosIG1lc3NhZ2VzLi4uKi8pIHtcbiAgICAgIGlmICghdG9waWNzW3RvcGljXSkgcmV0dXJuIG9iajtcbiAgICAgIHZhciBtZXNzYWdlcyA9IFtdLnNsaWNlLmNhbGwoYXJndW1lbnRzLCAxKTtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gdG9waWNzW3RvcGljXS5sZW5ndGg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgdG9waWNzW3RvcGljXVtpXS5oYW5kbGVyLmFwcGx5KHRvcGljc1t0b3BpY11baV0uY29udGV4dCwgbWVzc2FnZXMpO1xuICAgICAgfVxuICAgICAgcmV0dXJuIG9iajtcbiAgICB9O1xuXG4gICAgb2JqLnN1YnNjcmliZSA9IGZ1bmN0aW9uICh0b3BpY09yU3Vic2NyaWJlciwgaGFuZGxlck9yVG9waWNzKSB7XG4gICAgICB2YXIgZmlyc3RUeXBlID0gdHlwZW9mIHRvcGljT3JTdWJzY3JpYmVyO1xuXG4gICAgICBpZiAoZmlyc3RUeXBlID09PSAnc3RyaW5nJykge1xuICAgICAgICByZXR1cm4gc3Vic2NyaWJlLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgICB9XG5cbiAgICAgIGlmIChmaXJzdFR5cGUgPT09ICdvYmplY3QnICYmICFoYW5kbGVyT3JUb3BpY3MpIHtcbiAgICAgICAgcmV0dXJuIHN1YnNjcmliZU11bHRpcGxlLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgICB9XG5cbiAgICAgIGlmICh0eXBlb2YgaGFuZGxlck9yVG9waWNzID09PSAnc3RyaW5nJykge1xuICAgICAgICByZXR1cm4gaGl0Y2guYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuIGhpdGNoTXVsdGlwbGUuYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gc3Vic2NyaWJlICh0b3BpYywgaGFuZGxlciwgY29udGV4dCkge1xuICAgICAgdmFyIHJlZmVyZW5jZSA9IHsgaGFuZGxlcjogaGFuZGxlciwgY29udGV4dDogY29udGV4dCB8fCBvYmogfTtcbiAgICAgIHRvcGljID0gdG9waWNzW3RvcGljXSB8fCAodG9waWNzW3RvcGljXSA9IFtdKTtcbiAgICAgIHRvcGljLnB1c2gocmVmZXJlbmNlKTtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIGF0dGFjaDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgIHRvcGljLnB1c2gocmVmZXJlbmNlKTtcbiAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfSxcbiAgICAgICAgZGV0YWNoOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgZXJhc2UodG9waWMsIHJlZmVyZW5jZSk7XG4gICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH1cbiAgICAgIH07XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIHN1YnNjcmliZU11bHRpcGxlIChwYWlycykge1xuICAgICAgdmFyIHN1YnNjcmlwdGlvbnMgPSB7fTtcbiAgICAgIGZvciAodmFyIHRvcGljIGluIHBhaXJzKSB7XG4gICAgICAgIGlmICghcGFpcnMuaGFzT3duUHJvcGVydHkodG9waWMpKSBjb250aW51ZTtcbiAgICAgICAgc3Vic2NyaXB0aW9uc1t0b3BpY10gPSBzdWJzY3JpYmUodG9waWMsIHBhaXJzW3RvcGljXSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gc3Vic2NyaXB0aW9ucztcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gaGl0Y2ggKHN1YnNjcmliZXIsIHRvcGljKSB7XG4gICAgICByZXR1cm4gc3Vic2NyaWJlKHRvcGljLCBzdWJzY3JpYmVyW3RvcGljXSwgc3Vic2NyaWJlcik7XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIGhpdGNoTXVsdGlwbGUgKHN1YnNjcmliZXIsIHRvcGljcykge1xuICAgICAgdmFyIHN1YnNjcmlwdGlvbnMgPSBbXTtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gdG9waWNzLmxlbmd0aDsgaSA8IGw7IGkrKykge1xuICAgICAgICBzdWJzY3JpcHRpb25zLnB1c2goIGhpdGNoKHN1YnNjcmliZXIsIHRvcGljc1tpXSkgKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBzdWJzY3JpcHRpb25zO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBlcmFzZSAoYXJyLCB2aWN0aW0pIHtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gYXJyLmxlbmd0aDsgaSA8IGw7IGkrKyl7XG4gICAgICAgIGlmIChhcnJbaV0gPT09IHZpY3RpbSkgYXJyLnNwbGljZShpLCAxKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICByZXR1cm4gb2JqO1xuICB9O1xuXG4gIC8vIHB1Ymxpc2hlciBpcyBhIHB1Ymxpc2hlciwgc28gbWV0YSAuLi5cbiAgcmV0dXJuIHB1Ymxpc2hlcihwdWJsaXNoZXIpO1xufSkpO1xuIl19
