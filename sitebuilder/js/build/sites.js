(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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
},{"./ui.js":7}],2:[function(require,module,exports){
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
},{"../vendor/publisher":10,"./config.js":3,"./ui.js":7,"./utils.js":8}],3:[function(require,module,exports){
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
},{}],4:[function(require,module,exports){
(function () {
	"use strict";

	var bConfig = require('./config.js');
	var siteBuilder = require('./builder.js');
	var appUI = require('./ui.js').appUI;

	var publish = {
        
        buttonPublish: document.getElementById('publishPage'),
        buttonSavePendingBeforePublishing: document.getElementById('buttonSavePendingBeforePublishing'),
        publishModal: document.getElementById('publishModal'),
        buttonPublishSubmit: document.getElementById('publishSubmit'),
        publishActive: 0,
        theItem: {},
        modalSiteSettings: document.getElementById('siteSettings'),
    
        init: function() {
        
            $(this.buttonPublish).on('click', this.loadPublishModal);
            $(this.buttonSavePendingBeforePublishing).on('click', this.saveBeforePublishing);
            $(this.publishModal).on('change', 'input[type=checkbox]', this.publishCheckboxEvent);
            $(this.buttonPublishSubmit).on('click', this.publishSite);
            $(this.modalSiteSettings).on('click', '#siteSettingsBrowseFTPButton, .link', this.browseFTP);
            $(this.modalSiteSettings).on('click', '#ftpListItems .close', this.closeFtpBrowser);
            $(this.modalSiteSettings).on('click', '#siteSettingsTestFTP', this.testFTPConnection);
            
            //show the publish button
            $(this.buttonPublish).show();
            
            //listen to site settings load event
            $('body').on('siteSettingsLoad', this.showPublishSettings);
            
            //publish hash?
            if( window.location.hash === "#publish" ) {
                $(this.buttonPublish).click();
            }
            
            // header tooltips
            //if( this.buttonPublish.hasAttribute('data-toggle') && this.buttonPublish.getAttribute('data-toggle') == 'tooltip' ) {
            //   $(this.buttonPublish).tooltip('show');
            //   setTimeout(function(){$(this.buttonPublish).tooltip('hide')}, 5000);
            //}
            
        },
        
        
        /*
            loads the publish modal
        */
        loadPublishModal: function(e) {
            
            e.preventDefault();
            
            if( publish.publishActive === 0 ) {//check if we're currently publishing anything
		
                //hide alerts
                $('#publishModal .modal-alerts > *').each(function(){
                    $(this).remove();
                });
                
                $('#publishModal .modal-body > .alert-success').hide();
                
                //hide loaders
                $('#publishModal_assets .publishing').each(function(){
                    $(this).hide();
                    $(this).find('.working').show();
                    $(this).find('.done').hide();
                });
                
                //remove published class from asset checkboxes
                $('#publishModal_assets input').each(function(){
                    $(this).removeClass('published');
                });
                
                //do we have pending changes?
                if( siteBuilder.site.pendingChanges === true ) {//we've got changes, save first
                    
                    $('#publishModal #publishPendingChangesMessage').show();
                    $('#publishModal .modal-body-content').hide();
		
                } else {//all set, get on it with publishing
                    
                    //get the correct pages in the Pages section of the publish modal
                    $('#publishModal_pages tbody > *').remove();

                    $('#pages li:visible').each(function(){
                        
                        var thePage = $(this).find('a:first').text();
                        var theRow = $('<tr><td class="text-center" style="width: 30px;"><label class="checkbox no-label"><input type="checkbox" value="'+thePage+'" id="" data-type="page" name="pages[]" data-toggle="checkbox"></label></td><td>'+thePage+'<span class="publishing"><span class="working">Publishing... <img src="'+appUI.baseUrl+'images/publishLoader.gif"></span><span class="done text-primary">Published &nbsp;<span class="fui-check"></span></span></span></td></tr>');
                        
                        //checkboxify
                        theRow.find('input').radiocheck();
                        theRow.find('input').on('check uncheck toggle', function(){
                            $(this).closest('tr')[$(this).prop('checked') ? 'addClass' : 'removeClass']('selected-row');
                        });
                        
                        $('#publishModal_pages tbody').append( theRow );
                    
                    });
                    
                    $('#publishModal #publishPendingChangesMessage').hide();
                    $('#publishModal .modal-body-content').show();
                
                }
            }
            
            //enable/disable publish button
            
            var activateButton = false;
            
            $('#publishModal input[type=checkbox]').each(function(){
			
                if( $(this).prop('checked') ) {
                    activateButton = true;
                    return false;
                }
            });
            
            if( activateButton ) {
                $('#publishSubmit').removeClass('disabled');
            } else {
                $('#publishSubmit').addClass('disabled');
            }
            
            $('#publishModal').modal('show');
            
        },
        
        
        /*
            saves pending changes before publishing
        */
        saveBeforePublishing: function() {
            
            $('#publishModal #publishPendingChangesMessage').hide();
            $('#publishModal .loader').show();
            $(this).addClass('disabled');

            siteBuilder.site.prepForSave(false);
            
            var serverData = {};
            serverData.pages = siteBuilder.site.sitePagesReadyForServer;
            if( siteBuilder.site.pagesToDelete.length > 0 ) {
                serverData.toDelete = siteBuilder.site.pagesToDelete;
            }
            serverData.siteData = siteBuilder.site.data;
            
            $.ajax({
                url: appUI.siteUrl+"sites/save/1",
                type: "POST",
                dataType: "json",
                data: serverData,
            }).done(function(res){			
						
                $('#publishModal .loader').fadeOut(500, function(){
                    
                    $('#publishModal .modal-alerts').append( $(res.responseHTML) );
                    
                    //self-destruct success messages
                    setTimeout(function(){$('#publishModal .modal-alerts .alert-success').fadeOut(500, function(){$(this).remove();});}, 2500);
                    
                    //enable button
                    $('#publishModal #publishPendingChangesMessage .btn.save').removeClass('disabled');
                
                });
				
                if( res.responseCode === 1 ) {//changes were saved without issues

                    //no more pending changes
                    siteBuilder.site.setPendingChanges(false);
				
                    //get the correct pages in the Pages section of the publish modal
                    $('#publishModal_pages tbody > *').remove();

                    $('#pages li:visible').each(function(){
				
                        var thePage = $(this).find('a:first').text();
                        var theRow = $('<tr><td class="text-center" style="width: 0px;"><label class="checkbox"><input type="checkbox" value="'+thePage+'" id="" data-type="page" name="pages[]" data-toggle="checkbox"></label></td><td>'+thePage+'<span class="publishing"><span class="working">Publishing... <img src="'+appUI.baseUrl+'images/publishLoader.gif"></span><span class="done text-primary">Published &nbsp;<span class="fui-check"></span></span></span></td></tr>');
                        
                        //checkboxify
                        theRow.find('input').radiocheck();
                        theRow.find('input').on('check uncheck toggle', function(){
                            $(this).closest('tr')[$(this).prop('checked') ? 'addClass' : 'removeClass']('selected-row');
                        });
                        
                        $('#publishModal_pages tbody').append( theRow );
                    
                    });
                    
                    //show content
                    $('#publishModal .modal-body-content').fadeIn(500);
                
                }
            
            });
            
        },
        
        
        /*
            event handler for the checkboxes inside the publish modal
        */
        publishCheckboxEvent: function() {
            
            var activateButton = false;
            
            $('#publishModal input[type=checkbox]').each(function(){
                
                if( $(this).prop('checked') ) {
                    activateButton = true;
                    return false;
                }
            
            });
            
            if( activateButton ) {
                
                $('#publishSubmit').removeClass('disabled');
            
            } else {
                
                $('#publishSubmit').addClass('disabled');
            
            }
            
        },
        
        
        /*
            publishes the selected items
        */
        publishSite: function() {
            
            //track the publishing state
            publish.publishActive = 1;
            
            //disable button
            $('#publishSubmit, #publishCancel').addClass('disabled');
		
            //remove existing alerts
            $('#publishModal .modal-alerts > *').remove();
		
            //prepare stuff
            $('#publishModal form input[type="hidden"].page').remove();
            
            //loop through all pages
            $('#pageList > ul').each(function(){
                
                //export this page?
                if( $('#publishModal #publishModal_pages input:eq('+($(this).index()+1)+')').prop('checked') ) {
                    
                    //grab the skeleton markup
                    var newDocMainParent = $('iframe#skeleton').contents().find( bConfig.pageContainer );
                    
                    //empty out the skeleton
                    newDocMainParent.find('*').remove();
                    
                    //loop through page iframes and grab the body stuff
                    $(this).find('iframe').each(function(){
                        
                        var attr = $(this).attr('data-sandbox');

                        var theContents;
                        
                        if (typeof attr !== typeof undefined && attr !== false) {
                            theContents = $('#sandboxes #'+attr).contents().find( bConfig.pageContainer );
                        } else {
                            theContents = $(this).contents().find( bConfig.pageContainer );
                        }
                        
                        theContents.find('.frameCover').each(function(){
                            $(this).remove();
                        });
                        
                        //remove inline styling leftovers
                        for( var key in bConfig.editableItems ) {
                            
                            theContents.find( key ).each(function(){
                                
                                $(this).removeAttr('data-selector');
                                
                                if( $(this).attr('style') === '' ) {
                                    $(this).removeAttr('style');
                                }
                            
                            });
                        
                        }	
					
                        for (var i = 0; i < bConfig.editableContent.length; ++i) {
                            
                            $(this).contents().find( bConfig.editableContent[i] ).each(function(){
                                $(this).removeAttr('data-selector');
                            });
                        
                        }
                        
                        var toAdd = theContents.html();
                        
                        //grab scripts
                        
                        var scripts = $(this).contents().find( bConfig.pageContainer ).find('script');
                        
                        if( scripts.size() > 0 ) {
                            
                            var theIframe = document.getElementById("skeleton");
                            
                            scripts.each(function(){

                                var script;
                                
                                if( $(this).text() !== '' ) {//script tags with content
                                    
                                    script = theIframe.contentWindow.document.createElement("script");
                                    script.type = 'text/javascript';
                                    script.innerHTML = $(this).text();
                                    theIframe.contentWindow.document.getElementById( bConfig.pageContainer.substring(1) ).appendChild(script);
                                
                                } else if( $(this).attr('src') !== null ) {
                                    
                                    script = theIframe.contentWindow.document.createElement("script");
                                    script.type = 'text/javascript';
                                    script.src = $(this).attr('src');
                                    theIframe.contentWindow.document.getElementById( bConfig.pageContainer.substring(1) ).appendChild(script);
                                }
                            
                            });
                        
                        }
                        
                        newDocMainParent.append( $(toAdd) );
                    
                    });
                    
                    var newInput = $('<input type="hidden" class="page" name="xpages['+$('#pages li:eq('+($(this).index()+1)+') a:first').text()+']" value="">');
                    
                    $('#publishModal form').prepend( newInput );
                    
                    newInput.val( "<html>"+$('iframe#skeleton').contents().find('html').html()+"</html>" );
                
                }
            
            });
            
            publish.publishAsset();
            
        },
        
        publishAsset: function() {
            
            var toPublish = $('#publishModal_assets input[type=checkbox]:checked:not(.published, .toggleAll), #publishModal_pages input[type=checkbox]:checked:not(.published, .toggleAll)');
            
            if( toPublish.size() > 0 ) {
                
                publish.theItem = toPublish.first();
                
                //display the asset loader
                publish.theItem.closest('td').next().find('.publishing').fadeIn(500);

                var theData;
		
                if( publish.theItem.attr('data-type') === 'page' ) {
                    
                    theData = {siteID: $('form#publishForm input[name=siteID]').val(), item: publish.theItem.val(), pageContent: $('form#publishForm input[name="xpages['+publish.theItem.val()+']"]').val()};
                
                } else if( publish.theItem.attr('data-type') === 'asset' ) {
                    
                    theData = {siteID: $('form#publishForm input[name=siteID]').val(), item: publish.theItem.val()};
                
                }
                
                $.ajax({
                    url: $('form#publishForm').attr('action')+"/"+publish.theItem.attr('data-type'),
                    type: 'post',
                    data: theData,
                    dataType: 'json'
                }).done(function(ret){
                    
                    if( ret.responseCode === 0 ) {//fatal error, publishing will stop
                        
                        //hide indicators
                        publish.theItem.closest('td').next().find('.working').hide();
                        
                        //enable buttons
                        $('#publishSubmit, #publishCancel').removeClass('disabled');
                        $('#publishModal .modal-alerts').append( $(ret.responseHTML) );
                    
                    } else if( ret.responseCode === 1 ) {//no issues
                        
                        //show done
                        publish.theItem.closest('td').next().find('.working').hide();
                        publish.theItem.closest('td').next().find('.done').fadeIn();
                        publish.theItem.addClass('published');
                        
                        publish.publishAsset();
                    
                    }
                
                });

            } else {
                
                //publishing is done
                publish.publishActive = 0;
                
                //enable buttons
                $('#publishSubmit, #publishCancel').removeClass('disabled');
		
                //show message
                $('#publishModal .modal-body > .alert-success').fadeIn(500, function(){
                    setTimeout(function(){$('#publishModal .modal-body > .alert-success').fadeOut(500);}, 2500);
                });
            
            }
            
        },
        
        showPublishSettings: function() {
                        
            $('#siteSettingsPublishing').show();
        },
        
        
        /*
            browse the FTP connection
        */
        browseFTP: function(e) {
            
            e.preventDefault();
    		
    		//got all we need?
    		
    		if( $('#siteSettings_ftpServer').val() === '' || $('#siteSettings_ftpUser').val() === '' || $('#siteSettings_ftpPassword').val() === '' ) {
                alert('Please make sure all FTP connection details are present');
                return false;
            }
    		
            //check if this is a deeper level link
    		if( $(this).hasClass('link') ) {
    			
    			if( $(this).hasClass('back') ) {
    			
    				$('#siteSettings_ftpPath').val( $(this).attr('href') );
    			
    			} else {
    			
    				//if so, we'll change the path before connecting
    			
    				if( $('#siteSettings_ftpPath').val().substr( $('#siteSettings_ftpPath').val.length - 1 ) === '/' ) {//prepend "/"
    				
    					$('#siteSettings_ftpPath').val( $('#siteSettings_ftpPath').val()+$(this).attr('href') );
    			
    				} else {
    				
    					$('#siteSettings_ftpPath').val( $('#siteSettings_ftpPath').val()+"/"+$(this).attr('href') );
    				
    				}
    			
    			}
    			
    			
    		}
    		
    		//destroy all alerts
    		
    		$('#ftpAlerts .alert').fadeOut(500, function(){
    			$(this).remove();
    		});
    		
    		//disable button
    		$(this).addClass('disabled');
    		
    		//remove existing links
    		$('#ftpListItems > *').remove();
    		
    		//show ftp section
    		$('#ftpBrowse .loaderFtp').show();
    		$('#ftpBrowse').slideDown(500);

    		var theButton = $(this);
    		
    		$.ajax({
                url: appUI.siteUrl+"ftpconnection/connect",
    			type: 'post',
    			dataType: 'json',
    			data: $('form#siteSettingsForm').serializeArray()
    		}).done(function(ret){
    		
    			//enable button
    			theButton.removeClass('disabled');
    			
    			//hide loading
    			$('#ftpBrowse .loaderFtp').hide();
    		
    			if( ret.responseCode === 0 ) {//error
    				$('#ftpAlerts').append( $(ret.responseHTML) );
    			} else if( ret.responseCode === 1 ) {//all good
    				$('#ftpListItems').append( $(ret.responseHTML) );
    			}
    		
    		});
            
        },
        
        
        /*
            hides/closes the FTP browser
        */
        closeFtpBrowser: function(e) {
            
            e.preventDefault();
    		$(this).closest('#ftpBrowse').slideUp(500);
            
        },
        
        
         /*
            tests the FTP connection with the provided details
        */
        testFTPConnection: function() {
            
            //got all we need?
    		if( $('#siteSettings_ftpServer').val() === '' || $('#siteSettings_ftpUser').val() === '' || $('#siteSettings_ftpPassword').val() === '' ) {
                alert('Please make sure all FTP connection details are present');
                return false;
            }
    		
    		//destroy all alerts
            $('#ftpTestAlerts .alert').fadeOut(500, function(){
                $(this).remove();
            });
    		
    		//disable button
    		$(this).addClass('disabled');
    		
    		//show loading indicator
    		$(this).next().fadeIn(500);
    		
            var theButton = $(this);
    		
    		$.ajax({
                url: appUI.siteUrl+"ftpconnection/test",
    			type: 'post',
    			dataType: 'json',
    			data: $('form#siteSettingsForm').serializeArray()
    		}).done(function(ret){
    		    		
    			//enable button
    			theButton.removeClass('disabled');
                theButton.next().fadeOut(500);
    			    		
    			if( ret.responseCode === 0 ) {//error
                    $('#ftpTestAlerts').append( $(ret.responseHTML) );
                } else if( ret.responseCode === 1 ) {//all good
                    $('#ftpTestAlerts').append( $(ret.responseHTML) );
                }
    		
    		});
            
        }
        
    };
    
    publish.init();

}());
},{"./builder.js":2,"./config.js":3,"./ui.js":7}],5:[function(require,module,exports){
(function () {
	"use strict";

	var appUI = require('./ui.js').appUI;

	var sites = {
        
        wrapperSites: document.getElementById('sites'),
        selectUser: document.getElementById('userDropDown'),
        selectSort: document.getElementById('sortDropDown'),
        buttonDeleteSite: document.getElementById('deleteSiteButton'),
		buttonsDeleteSite: document.querySelectorAll('.deleteSiteButton'),
        
        init: function() {
            
            this.createThumbnails();
            
            $(this.selectUser).on('change', this.filterUser);
            $(this.selectSort).on('change', this.changeSorting);
            $(this.buttonsDeleteSite).on('click', this.deleteSite);
			$(this.buttonDeleteSite).on('click', this.deleteSite);
            
        },
        
        
        /*
            applies zoomer to create the iframe thubmnails
        */
        createThumbnails: function() {
                        
            $(this.wrapperSites).find('iframe').each(function(){
                            
                var theHeight = $(this).attr('data-height')*0.25;
                
                $(this).zoomer({
                    zoom: 0.25,
                    height: theHeight,
                    width: $(this).parent().width(),
                    message: "",
                    messageURL: appUI.siteUrl+"sites/"+$(this).attr('data-siteid')
                });
                
                $(this).closest('.site').find('.zoomer-cover > a').attr('target', '');
                    
            });
            
        },
        
        
        /*
            filters the site list by selected user
        */
        filterUser: function() {
            
            if( $(this).val() === 'All' || $(this).val() === '' ) {
                $('#sites .site').hide().fadeIn(500);
            } else {
                $('#sites .site').hide();
                $('#sites').find('[data-name="'+$(this).val()+'"]').fadeIn(500);
            }
            
        },
        
        
        /*
            chnages the sorting on the site list
        */
        changeSorting: function() {

            var sites;
            
            if( $(this).val() === 'NoOfPages' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
                    
                    var an = a.getAttribute('data-pages');
					var bn = b.getAttribute('data-pages');
				
					if(an > bn) {
						return 1;
					}
				
					if(an < bn) {
						return -1;
					}
				
					return 0;
				
				} );
			
				sites.detach().appendTo( $('#sites') );
		
			} else if( $(this).val() === 'CreationDate' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
			
					var an = a.getAttribute('data-created').replace("-", "");
					var bn = b.getAttribute('data-created').replace("-", "");
				
					if(an > bn) {
						return 1;
					}
				
					if(an < bn) {
						return -1;
					}
				
					return 0;
				
				} );
			
				sites.detach().appendTo( $('#sites') );
		
			} else if( $(this).val() === 'LastUpdate' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
			
					var an = a.getAttribute('data-update').replace("-", "");
					var bn = b.getAttribute('data-update').replace("-", "");
				
					if(an > bn) {
						return 1;
					}
				
					if(an < bn) {
						return -1;
					}
				
				return 0;
				
				} );
			
				sites.detach().appendTo( $('#sites') );
		
			}
            
        },
        
        
        /*
            deletes a site
        */
        deleteSite: function(e) {
			            
            e.preventDefault();
            
            $('#deleteSiteModal .modal-content p').show();
            
            //remove old alerts
            $('#deleteSiteModal .modal-alerts > *').remove();
            $('#deleteSiteModal .loader').hide();
		
            var toDel = $(this).closest('.site');
            var delButton = $(this);
           
            $('#deleteSiteModal button#deleteSiteButton').show();
            $('#deleteSiteModal').modal('show');
           
            $('#deleteSiteModal button#deleteSiteButton').unbind('click').click(function(){
                
                $(this).addClass('disabled');
                $('#deleteSiteModal .loader').fadeIn(500);
               
                $.ajax({
                    url: appUI.siteUrl+"sites/trash/"+delButton.attr('data-siteid'),
                    type: 'post',
                    dataType: 'json'
                }).done(function(ret){
                    
                    $('#deleteSiteModal .loader').hide();
                    $('#deleteSiteModal button#deleteSiteButton').removeClass('disabled');
                   
                    if( ret.responseCode === 0 ) {//error
                       
                        $('#deleteSiteModal .modal-content p').hide();
                        $('#deleteSiteModal .modal-alerts').append( $(ret.responseHTML) );
                   
                    } else if( ret.responseCode === 1 ) {//all good
                       
                        $('#deleteSiteModal .modal-content p').hide();
                        $('#deleteSiteModal .modal-alerts').append( $(ret.responseHTML) );
                        $('#deleteSiteModal button#deleteSiteButton').hide();
                       
                        toDel.fadeOut(800, function(){
                            $(this).remove();
                        });
                    }
               
                });	
            });
            
        }
        
    };
    
    sites.init();

}());
},{"./ui.js":7}],6:[function(require,module,exports){
(function () {
	"use strict";

	var appUI = require('./ui.js').appUI;

	var siteSettings = {
        
        //buttonSiteSettings: document.getElementById('siteSettingsButton'),
		buttonSiteSettings2: $('.siteSettingsModalButton'),
        buttonSaveSiteSettings: document.getElementById('saveSiteSettingsButton'),
    
        init: function() {
            
            //$(this.buttonSiteSettings).on('click', this.siteSettingsModal);
			this.buttonSiteSettings2.on('click', this.siteSettingsModal);
            $(this.buttonSaveSiteSettings).on('click', this.saveSiteSettings);
        
        },
    
        /*
            loads the site settings data
        */
        siteSettingsModal: function(e) {
            
            e.preventDefault();
    		
    		$('#siteSettings').modal('show');
    		
    		//destroy all alerts
    		$('#siteSettings .alert').fadeOut(500, function(){
    		
    			$(this).remove();
    		
    		});
    		
    		//set the siteID
    		$('input#siteID').val( $(this).attr('data-siteid') );
    		
    		//destroy current forms
    		$('#siteSettings .modal-body-content > *').each(function(){
    			$(this).remove();
    		});
    		
            //show loader, hide rest
    		$('#siteSettingsWrapper .loader').show();
    		$('#siteSettingsWrapper > *:not(.loader)').hide();
    		
    		//load site data using ajax
    		$.ajax({
                url: appUI.siteUrl+"sites/siteAjax/"+$(this).attr('data-siteid'),
    			type: 'post',
    			dataType: 'json'
    		}).done(function(ret){    			
    			
    			if( ret.responseCode === 0 ) {//error
    			
    				//hide loader, show error message
    				$('#siteSettings .loader').fadeOut(500, function(){
    					
    					$('#siteSettings .modal-alerts').append( $(ret.responseHTML) );
    				
    				});
    				
    				//disable submit button
    				$('#saveSiteSettingsButton').addClass('disabled');
    			
    			
    			} else if( ret.responseCode === 1 ) {//all well :)
    			
    				//hide loader, show data
    				
    				$('#siteSettings .loader').fadeOut(500, function(){
    				
    					$('#siteSettings .modal-body-content').append( $(ret.responseHTML) );
                        
                        $('body').trigger('siteSettingsLoad');
    				
    				});
    				
    				//enable submit button
    				$('#saveSiteSettingsButton').removeClass('disabled');
                        			
    			}
    		
    		});
            
        },
        
        
        /*
            saves the site settings
        */
        saveSiteSettings: function() {
            
            //destroy all alerts
    		$('#siteSettings .alert').fadeOut(500, function(){
    		
    			$(this).remove();
    		
    		});
    		
    		//disable button
    		$('#saveSiteSettingsButton').addClass('disabled');
    		
    		//hide form data
    		$('#siteSettings .modal-body-content > *').hide();
    		
    		//show loader
    		$('#siteSettings .loader').show();
    		
    		$.ajax({
                url: appUI.siteUrl+"sites/siteAjaxUpdate",
    			type: 'post',
    			dataType: 'json',
    			data: $('form#siteSettingsForm').serializeArray()
    		}).done(function(ret){
    		
    			if( ret.responseCode === 0 ) {//error
    			
    				$('#siteSettings .loader').fadeOut(500, function(){
    				
    					$('#siteSettings .modal-alerts').append( ret.responseHTML );
    					
    					//show form data
    					$('#siteSettings .modal-body-content > *').show();
    					
    					//enable button
    					$('#saveSiteSettingsButton').removeClass('disabled');
    				
    				});
    			
    			
    			} else if( ret.responseCode === 1 ) {//all is well
    			
    				$('#siteSettings .loader').fadeOut(500, function(){
    					
    					
    					//update site name in top menu
    					$('#siteTitle').text( ret.siteName );
    					
    					$('#siteSettings .modal-alerts').append( ret.responseHTML );
    					
    					//hide form data
    					$('#siteSettings .modal-body-content > *').remove();
    					$('#siteSettings .modal-body-content').append( ret.responseHTML2 );
    					
    					//enable button
    					$('#saveSiteSettingsButton').removeClass('disabled');
    					
    					//is the FTP stuff all good?
    					
    					if( ret.ftpOk === 1 ) {//yes, all good
    					
    						$('#publishPage').removeAttr('data-toggle');
    						$('#publishPage span.text-danger').hide();
    						
    						$('#publishPage').tooltip('destroy');
    					
    					} else {//nope, can't use FTP
    						
    						$('#publishPage').attr('data-toggle', 'tooltip');
    						$('#publishPage span.text-danger').show();
    						
    						$('#publishPage').tooltip('show');
    					
    					}
    					
    					
    					//update the site name in the small window
    					$('#site_'+ret.siteID+' .window .top b').text( ret.siteName );
    				
    				});
    			
    			
    			}
    		
    		});
    		            
        },
        
    
    };
    
    siteSettings.init();

}());
},{"./ui.js":7}],7:[function(require,module,exports){
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
},{}],8:[function(require,module,exports){
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
},{}],9:[function(require,module,exports){
(function () {
	"use strict";

	require('./modules/ui.js');
	require('./modules/account.js');
	require('./modules/sites.js');
	require('./modules/sitesettings.js');
	require('./modules/publishing.js');

}());
},{"./modules/account.js":1,"./modules/publishing.js":4,"./modules/sites.js":5,"./modules/sitesettings.js":6,"./modules/ui.js":7}],10:[function(require,module,exports){
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

},{}]},{},[9])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9tb2R1bGVzL2FjY291bnQuanMiLCJqcy9tb2R1bGVzL2J1aWxkZXIuanMiLCJqcy9tb2R1bGVzL2NvbmZpZy5qcyIsImpzL21vZHVsZXMvcHVibGlzaGluZy5qcyIsImpzL21vZHVsZXMvc2l0ZXMuanMiLCJqcy9tb2R1bGVzL3NpdGVzZXR0aW5ncy5qcyIsImpzL21vZHVsZXMvdWkuanMiLCJqcy9tb2R1bGVzL3V0aWxzLmpzIiwianMvc2l0ZXMuanMiLCJqcy92ZW5kb3IvcHVibGlzaGVyLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FDQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDdEpBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN2aUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3pEQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDdGpCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDM01BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN6TEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNoSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNuQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDVEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgYXBwVUkgPSByZXF1aXJlKCcuL3VpLmpzJykuYXBwVUk7XG5cblx0dmFyIGFjY291bnQgPSB7XG4gICAgICAgIFxuICAgICAgICBidXR0b25VcGRhdGVBY2NvdW50RGV0YWlsczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2FjY291bnREZXRhaWxzU3VibWl0JyksXG4gICAgICAgIGJ1dHRvblVwZGF0ZUxvZ2luRGV0YWlsczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2FjY291bnRMb2dpblN1Ym1pdCcpLFxuICAgICAgICBcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5idXR0b25VcGRhdGVBY2NvdW50RGV0YWlscykub24oJ2NsaWNrJywgdGhpcy51cGRhdGVBY2NvdW50RGV0YWlscyk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uVXBkYXRlTG9naW5EZXRhaWxzKS5vbignY2xpY2snLCB0aGlzLnVwZGF0ZUxvZ2luRGV0YWlscyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdXBkYXRlcyBhY2NvdW50IGRldGFpbHNcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlQWNjb3VudERldGFpbHM6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2FsbCBmaWVsZHMgZmlsbGVkIGluP1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2ZpcnN0bmFtZScpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2ZpcnN0bmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAwO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2ZpcnN0bmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykucmVtb3ZlQ2xhc3MoJ2hhcy1lcnJvcicpO1xuICAgICAgICAgICAgICAgIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnI2FjY291bnRfZGV0YWlscyBpbnB1dCNsYXN0bmFtZScpLnZhbCgpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9kZXRhaWxzIGlucHV0I2xhc3RuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDA7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgaW5wdXQjbGFzdG5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIH1cblx0XHRcbiAgICAgICAgICAgIGlmKCBhbGxHb29kID09PSAxICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIHRoZUJ1dHRvbiA9ICQodGhpcyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9kaXNhYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zaG93IGxvYWRlclxuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmxvYWRlcicpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIGFsZXJ0c1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmFsZXJ0cyA+IConKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJ1c2Vycy91YWNjb3VudFwiLFxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAncG9zdCcsXG4gICAgICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnanNvbicsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6ICQoJyNhY2NvdW50X2RldGFpbHMnKS5zZXJpYWxpemUoKVxuICAgICAgICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24ocmV0KXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZW5hYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICAgICB0aGVCdXR0b24ucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2hpZGUgbG9hZGVyXG4gICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2RldGFpbHMgLmxvYWRlcicpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyAuYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDEgKSB7Ly9zdWNjZXNzXG4gICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHsgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2FjY291bnRfZGV0YWlscyAuYWxlcnRzID4gKicpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbiAoKSB7ICQodGhpcykucmVtb3ZlKCk7IH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSwgMzAwMCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHVwZGF0ZXMgYWNjb3VudCBsb2dpbiBkZXRhaWxzXG4gICAgICAgICovXG4gICAgICAgIHVwZGF0ZUxvZ2luRGV0YWlsczogZnVuY3Rpb24oKSB7XG5cdFx0XHRcblx0XHRcdGNvbnNvbGUubG9nKGFwcFVJKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGFsbEdvb2QgPSAxO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCgnI2FjY291bnRfbG9naW4gaW5wdXQjZW1haWwnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gaW5wdXQjZW1haWwnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmFkZENsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI2FjY291bnRfbG9naW4gaW5wdXQjZW1haWwnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdoYXMtZXJyb3InKTtcbiAgICAgICAgICAgICAgICBhbGxHb29kID0gMTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I3Bhc3N3b3JkJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I3Bhc3N3b3JkJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5hZGRDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDA7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIGlucHV0I3Bhc3N3b3JkJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5yZW1vdmVDbGFzcygnaGFzLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgYWxsR29vZCA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCBhbGxHb29kID09PSAxICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciB0aGVCdXR0b24gPSAkKHRoaXMpO1xuXG4gICAgICAgICAgICAgICAgLy9kaXNhYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zaG93IGxvYWRlclxuICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIC5sb2FkZXInKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBhbGVydHNcbiAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAuYWxlcnRzID4gKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybDogYXBwVUkuc2l0ZVVybCtcInVzZXJzL3Vsb2dpblwiLFxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAncG9zdCcsXG4gICAgICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnanNvbicsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6ICQoJyNhY2NvdW50X2xvZ2luJykuc2VyaWFsaXplKClcbiAgICAgICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAgICAgdGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9oaWRlIGxvYWRlclxuICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAubG9hZGVyJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAkKCcjYWNjb3VudF9sb2dpbiAuYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG5cdFx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vc3VjY2Vzc1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7IFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJyNhY2NvdW50X2xvZ2luIC5hbGVydHMgPiAqJykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uICgpIHsgJCh0aGlzKS5yZW1vdmUoKTsgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9LCAzMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuICAgIFxuICAgIGFjY291bnQuaW5pdCgpO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXG4gICAgdmFyIHNpdGVCdWlsZGVyVXRpbHMgPSByZXF1aXJlKCcuL3V0aWxzLmpzJyk7XG4gICAgdmFyIGJDb25maWcgPSByZXF1aXJlKCcuL2NvbmZpZy5qcycpO1xuICAgIHZhciBhcHBVSSA9IHJlcXVpcmUoJy4vdWkuanMnKS5hcHBVSTtcbiAgICB2YXIgcHVibGlzaGVyID0gcmVxdWlyZSgnLi4vdmVuZG9yL3B1Ymxpc2hlcicpO1xuICAgIHZhciBmb3JtX2lkID0gMDtcblxuXHQgLypcbiAgICAgICAgQmFzaWMgQnVpbGRlciBVSSBpbml0aWFsaXNhdGlvblxuICAgICovXG4gICAgdmFyIGJ1aWxkZXJVSSA9IHtcbiAgICAgICAgXG4gICAgICAgIGFsbEJsb2Nrczoge30sICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vaG9sZHMgYWxsIGJsb2NrcyBsb2FkZWQgZnJvbSB0aGUgc2VydmVyXG4gICAgICAgIG1lbnVXcmFwcGVyOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbWVudScpLFxuICAgICAgICBwcmltYXJ5U2lkZU1lbnVXcmFwcGVyOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbWFpbicpLFxuICAgICAgICBidXR0b25CYWNrOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmFja0J1dHRvbicpLFxuICAgICAgICBidXR0b25CYWNrQ29uZmlybTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xlYXZlUGFnZUJ1dHRvbicpLFxuICAgICAgICBcbiAgICAgICAgYWNlRWRpdG9yczoge30sXG4gICAgICAgIGZyYW1lQ29udGVudHM6ICcnLCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9ob2xkcyBmcmFtZSBjb250ZW50c1xuICAgICAgICB0ZW1wbGF0ZUlEOiAwLCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vaG9sZHMgdGhlIHRlbXBsYXRlIElEIGZvciBhIHBhZ2UgKD8/PylcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgbW9kYWxEZWxldGVCbG9jazogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZUJsb2NrJyksXG4gICAgICAgIG1vZGFsUmVzZXRCbG9jazogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Jlc2V0QmxvY2snKSxcbiAgICAgICAgbW9kYWxEZWxldGVQYWdlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGVsZXRlUGFnZScpLFxuICAgICAgICBidXR0b25EZWxldGVQYWdlQ29uZmlybTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZVBhZ2VDb25maXJtJyksXG4gICAgICAgIFxuICAgICAgICBkcm9wZG93blBhZ2VMaW5rczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludGVybmFsTGlua3NEcm9wZG93bicpLFxuXG4gICAgICAgIHBhZ2VJblVybDogbnVsbCxcbiAgICAgICAgXG4gICAgICAgIHRlbXBGcmFtZToge30sXG5cbiAgICAgICAgY3VycmVudFJlc3BvbnNpdmVNb2RlOiB7fSxcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sb2FkIGJsb2Nrc1xuICAgICAgICAgICAgJC5nZXRKU09OKGFwcFVJLmJhc2VVcmwrJ2VsZW1lbnRzLmpzb24/dj0xMjM0NTY3OCcsIGZ1bmN0aW9uKGRhdGEpeyBidWlsZGVyVUkuYWxsQmxvY2tzID0gZGF0YTsgYnVpbGRlclVJLmltcGxlbWVudEJsb2NrcygpOyB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zaXRlYmFyIGhvdmVyIGFuaW1hdGlvbiBhY3Rpb25cbiAgICAgICAgICAgICQodGhpcy5tZW51V3JhcHBlcikub24oJ21vdXNlZW50ZXInLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcykuc3RvcCgpLmFuaW1hdGUoeydsZWZ0JzogJzBweCd9LCA1MDApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSkub24oJ21vdXNlbGVhdmUnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcykuc3RvcCgpLmFuaW1hdGUoeydsZWZ0JzogJy0xOTBweCd9LCA1MDApO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQoJyNtZW51ICNtYWluIGEnKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgJCgnLm1lbnUgLnNlY29uZCcpLnN0b3AoKS5hbmltYXRlKHtcbiAgICAgICAgICAgICAgICAgICAgd2lkdGg6IDBcbiAgICAgICAgICAgICAgICB9LCA1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICQoJyNtZW51ICNzZWNvbmQnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9wcmV2ZW50IGNsaWNrIGV2ZW50IG9uIGFuY29ycyBpbiB0aGUgYmxvY2sgc2VjdGlvbiBvZiB0aGUgc2lkZWJhclxuICAgICAgICAgICAgJCh0aGlzLnByaW1hcnlTaWRlTWVudVdyYXBwZXIpLm9uKCdjbGljaycsICdhOm5vdCguYWN0aW9uQnV0dG9ucyknLCBmdW5jdGlvbihlKXtlLnByZXZlbnREZWZhdWx0KCk7fSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5idXR0b25CYWNrKS5vbignY2xpY2snLCB0aGlzLmJhY2tCdXR0b24pO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbkJhY2tDb25maXJtKS5vbignY2xpY2snLCB0aGlzLmJhY2tCdXR0b25Db25maXJtKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9ub3RpZnkgdGhlIHVzZXIgb2YgcGVuZGluZyBjaG5hZ2VzIHdoZW4gY2xpY2tpbmcgdGhlIGJhY2sgYnV0dG9uXG4gICAgICAgICAgICAkKHdpbmRvdykuYmluZCgnYmVmb3JldW5sb2FkJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBpZiggc2l0ZS5wZW5kaW5nQ2hhbmdlcyA9PT0gdHJ1ZSApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuICdZb3VyIHNpdGUgY29udGFpbnMgY2hhbmdlZCB3aGljaCBoYXZlblxcJ3QgYmVlbiBzYXZlZCB5ZXQuIEFyZSB5b3Ugc3VyZSB5b3Ugd2FudCB0byBsZWF2ZT8nO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL1VSTCBwYXJhbWV0ZXJzXG4gICAgICAgICAgICBidWlsZGVyVUkucGFnZUluVXJsID0gc2l0ZUJ1aWxkZXJVdGlscy5nZXRQYXJhbWV0ZXJCeU5hbWUoJ3AnKTtcblxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBidWlsZHMgdGhlIGJsb2NrcyBpbnRvIHRoZSBzaXRlIGJhclxuICAgICAgICAqL1xuICAgICAgICBpbXBsZW1lbnRCbG9ja3M6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICB2YXIgbmV3SXRlbSwgbG9hZGVyRnVuY3Rpb247XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGtleSBpbiB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50cyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgbmljZUtleSA9IGtleS50b0xvd2VyQ2FzZSgpLnJlcGxhY2UoXCIgXCIsIFwiX1wiKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCc8bGk+PGEgaHJlZj1cIlwiIGlkPVwiJytuaWNlS2V5KydcIj4nK2tleSsnPC9hPjwvbGk+JykuYXBwZW5kVG8oJyNtZW51ICNtYWluIHVsI2VsZW1lbnRDYXRzJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldLmxlbmd0aDsgeCsrICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0udGh1bWJuYWlsID09PSBudWxsICkgey8vd2UnbGwgbmVlZCBhbiBpZnJhbWVcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9idWlsZCB1cyBzb21lIGlmcmFtZXMhXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHRoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0ubG9hZGVyRnVuY3Rpb24gKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxvYWRlckZ1bmN0aW9uID0gJ2RhdGEtbG9hZGVyZnVuY3Rpb249XCInK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0ubG9hZGVyRnVuY3Rpb24rJ1wiJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3SXRlbSA9ICQoJzxsaSBjbGFzcz1cImVsZW1lbnQgJytuaWNlS2V5KydcIj48aWZyYW1lIHNyYz1cIicrYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnVybCsnXCIgc2Nyb2xsaW5nPVwibm9cIiBzYW5kYm94PVwiYWxsb3ctc2FtZS1vcmlnaW5cIj48L2lmcmFtZT48L2xpPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0gPSAkKCc8bGkgY2xhc3M9XCJlbGVtZW50ICcrbmljZUtleSsnXCI+PGlmcmFtZSBzcmM9XCJhYm91dDpibGFua1wiIHNjcm9sbGluZz1cIm5vXCI+PC9pZnJhbWU+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtLmZpbmQoJ2lmcmFtZScpLnVuaXF1ZUlkKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdJdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ3NyYycsIGFwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS51cmwpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHsvL3dlJ3ZlIGdvdCBhIHRodW1ibmFpbFxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsb2FkZXJGdW5jdGlvbiA9ICdkYXRhLWxvYWRlcmZ1bmN0aW9uPVwiJyt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmxvYWRlckZ1bmN0aW9uKydcIic7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0gPSAkKCc8bGkgY2xhc3M9XCJlbGVtZW50ICcrbmljZUtleSsnXCI+PGltZyBzcmM9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS50aHVtYm5haWwrJ1wiIGRhdGEtc3JjYz1cIicrYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnVybCsnXCIgZGF0YS1oZWlnaHQ9XCInK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uaGVpZ2h0KydcIiBkYXRhLXNhbmRib3g9XCJcIiAnK2xvYWRlckZ1bmN0aW9uKyc+PC9saT4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld0l0ZW0gPSAkKCc8bGkgY2xhc3M9XCJlbGVtZW50ICcrbmljZUtleSsnXCI+PGltZyBzcmM9XCInK2FwcFVJLmJhc2VVcmwrdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS50aHVtYm5haWwrJ1wiIGRhdGEtc3JjYz1cIicrYXBwVUkuYmFzZVVybCt0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLnVybCsnXCIgZGF0YS1oZWlnaHQ9XCInK3RoaXMuYWxsQmxvY2tzLmVsZW1lbnRzW2tleV1beF0uaGVpZ2h0KydcIj48L2xpPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgbmV3SXRlbS5hcHBlbmRUbygnI21lbnUgI3NlY29uZCB1bCNlbGVtZW50cycpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vem9vbWVyIHdvcmtzXG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIHRoZUhlaWdodDtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGlmKCB0aGlzLmFsbEJsb2Nrcy5lbGVtZW50c1trZXldW3hdLmhlaWdodCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdGhlSGVpZ2h0ID0gdGhpcy5hbGxCbG9ja3MuZWxlbWVudHNba2V5XVt4XS5oZWlnaHQqMC4yNTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZUhlaWdodCA9ICdhdXRvJztcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBuZXdJdGVtLmZpbmQoJ2lmcmFtZScpLnpvb21lcih7XG4gICAgICAgICAgICAgICAgICAgICAgICB6b29tOiAwLjI1LFxuICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6IDI3MCxcbiAgICAgICAgICAgICAgICAgICAgICAgIGhlaWdodDogdGhlSGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgbWVzc2FnZTogXCJEcmFnJkRyb3AgTWUhXCJcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZHJhZ2dhYmxlc1xuICAgICAgICAgICAgYnVpbGRlclVJLm1ha2VEcmFnZ2FibGUoKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICAgICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGV2ZW50IGhhbmRsZXIgZm9yIHdoZW4gdGhlIGJhY2sgbGluayBpcyBjbGlja2VkXG4gICAgICAgICovXG4gICAgICAgIGJhY2tCdXR0b246IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggc2l0ZS5wZW5kaW5nQ2hhbmdlcyA9PT0gdHJ1ZSApIHtcbiAgICAgICAgICAgICAgICAkKCcjYmFja01vZGFsJykubW9kYWwoJ3Nob3cnKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYnV0dG9uIGZvciBjb25maXJtaW5nIGxlYXZpbmcgdGhlIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgYmFja0J1dHRvbkNvbmZpcm06IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLnBlbmRpbmdDaGFuZ2VzID0gZmFsc2U7Ly9wcmV2ZW50IHRoZSBKUyBhbGVydCBhZnRlciBjb25maXJtaW5nIHVzZXIgd2FudHMgdG8gbGVhdmVcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICAgICAgICAgIFxuICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgbWFrZXMgdGhlIGJsb2NrcyBhbmQgdGVtcGxhdGVzIGluIHRoZSBzaWRlYmFyIGRyYWdnYWJsZSBvbnRvIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgbWFrZURyYWdnYWJsZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQoJyNlbGVtZW50cyBsaSwgI3RlbXBsYXRlcyBsaScpLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICQodGhpcykuZHJhZ2dhYmxlKHtcbiAgICAgICAgICAgICAgICAgICAgaGVscGVyOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiAkKCc8ZGl2IHN0eWxlPVwiaGVpZ2h0OiAxMDBweDsgd2lkdGg6IDMwMHB4OyBiYWNrZ3JvdW5kOiAjRjlGQUZBOyBib3gtc2hhZG93OiA1cHggNXB4IDFweCByZ2JhKDAsMCwwLDAuMSk7IHRleHQtYWxpZ246IGNlbnRlcjsgbGluZS1oZWlnaHQ6IDEwMHB4OyBmb250LXNpemU6IDI4cHg7IGNvbG9yOiAjMTZBMDg1XCI+PHNwYW4gY2xhc3M9XCJmdWktbGlzdFwiPjwvc3Bhbj48L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgcmV2ZXJ0OiAnaW52YWxpZCcsXG4gICAgICAgICAgICAgICAgICAgIGFwcGVuZFRvOiAnYm9keScsXG4gICAgICAgICAgICAgICAgICAgIGNvbm5lY3RUb1NvcnRhYmxlOiAnI3BhZ2VMaXN0ID4gdWwnLFxuICAgICAgICAgICAgICAgICAgICBzdGFydDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5tb3ZlTW9kZSgnb24nKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgc3RvcDogZnVuY3Rpb24gKCkge31cbiAgICAgICAgICAgICAgICB9KTsgXG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCcjZWxlbWVudHMgbGkgYScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnVuYmluZCgnY2xpY2snKS5iaW5kKCdjbGljaycsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIEltcGxlbWVudHMgdGhlIHNpdGUgb24gdGhlIGNhbnZhcywgY2FsbGVkIGZyb20gdGhlIFNpdGUgb2JqZWN0IHdoZW4gdGhlIHNpdGVEYXRhIGhhcyBjb21wbGV0ZWQgbG9hZGluZ1xuICAgICAgICAqL1xuICAgICAgICBwb3B1bGF0ZUNhbnZhczogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgICAgIHZhciBpLFxuICAgICAgICAgICAgICAgIGNvdW50ZXIgPSAxO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xvb3AgdGhyb3VnaCB0aGUgcGFnZXNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCBpIGluIHNpdGUucGFnZXMgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIG5ld1BhZ2UgPSBuZXcgUGFnZShpLCBzaXRlLnBhZ2VzW2ldLCBjb3VudGVyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgY291bnRlcisrO1xuXG4gICAgICAgICAgICAgICAgLy9zZXQgdGhpcyBwYWdlIGFzIGFjdGl2ZT9cbiAgICAgICAgICAgICAgICBpZiggYnVpbGRlclVJLnBhZ2VJblVybCA9PT0gaSApIHtcbiAgICAgICAgICAgICAgICAgICAgbmV3UGFnZS5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hY3RpdmF0ZSB0aGUgZmlyc3QgcGFnZVxuICAgICAgICAgICAgaWYoc2l0ZS5zaXRlUGFnZXMubGVuZ3RoID4gMCAmJiBidWlsZGVyVUkucGFnZUluVXJsID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXNbMF0uc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgQ2FudmFzIGxvYWRpbmcgb24vb2ZmXG4gICAgICAgICovXG4gICAgICAgIGNhbnZhc0xvYWRpbmc6IGZ1bmN0aW9uICh2YWx1ZSkge1xuXG4gICAgICAgICAgICBpZiAoIHZhbHVlID09PSAnb24nICYmIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdmcmFtZVdyYXBwZXInKS5xdWVyeVNlbGVjdG9yQWxsKCcjY2FudmFzT3ZlcmxheScpLmxlbmd0aCA9PT0gMCApIHtcblxuICAgICAgICAgICAgICAgIHZhciBvdmVybGF5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XG5cbiAgICAgICAgICAgICAgICBvdmVybGF5LnN0eWxlLmRpc3BsYXkgPSAnZmxleCc7XG4gICAgICAgICAgICAgICAgJChvdmVybGF5KS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgb3ZlcmxheS5pZCA9ICdjYW52YXNPdmVybGF5JztcblxuICAgICAgICAgICAgICAgIG92ZXJsYXkuaW5uZXJIVE1MID0gJzxkaXYgY2xhc3M9XCJsb2FkZXJcIj48c3Bhbj57PC9zcGFuPjxzcGFuPn08L3NwYW4+PC9kaXY+JztcblxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdmcmFtZVdyYXBwZXInKS5hcHBlbmRDaGlsZChvdmVybGF5KTtcblxuICAgICAgICAgICAgICAgICQoJyNjYW52YXNPdmVybGF5JykuZmFkZUluKDUwMCk7XG5cbiAgICAgICAgICAgIH0gZWxzZSBpZiAoIHZhbHVlID09PSAnb2ZmJyAmJiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZnJhbWVXcmFwcGVyJykucXVlcnlTZWxlY3RvckFsbCgnI2NhbnZhc092ZXJsYXknKS5sZW5ndGggPT09IDEgKSB7XG5cbiAgICAgICAgICAgICAgICBzaXRlLmxvYWRlZCgpO1xuXG4gICAgICAgICAgICAgICAgJCgnI2NhbnZhc092ZXJsYXknKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgZm9ybV9pZCA9ICQoJyNmb3JtSUQnKS52YWwoKTtcbiAgICAgICAgICAgICAgICBpZihmb3JtX2lkICE9PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHNpdGUucXVpY2tfbG9hZF9mb3JtKCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuXG4gICAgLypcbiAgICAgICAgUGFnZSBjb25zdHJ1Y3RvclxuICAgICovXG4gICAgZnVuY3Rpb24gUGFnZSAocGFnZU5hbWUsIHBhZ2UsIGNvdW50ZXIpIHtcbiAgICBcbiAgICAgICAgdGhpcy5uYW1lID0gcGFnZU5hbWUgfHwgXCJcIjtcbiAgICAgICAgdGhpcy5wYWdlSUQgPSBwYWdlLnBhZ2VfaWQgfHwgMDtcbiAgICAgICAgdGhpcy5ibG9ja3MgPSBbXTtcbiAgICAgICAgdGhpcy5wYXJlbnRVTCA9IHt9OyAvL3BhcmVudCBVTCBvbiB0aGUgY2FudmFzXG4gICAgICAgIHRoaXMuc3RhdHVzID0gJyc7Ly8nJywgJ25ldycgb3IgJ2NoYW5nZWQnXG4gICAgICAgIHRoaXMuc2NyaXB0cyA9IFtdOy8vdHJhY2tzIHNjcmlwdCBVUkxzIHVzZWQgb24gdGhpcyBwYWdlXG4gICAgICAgIFxuICAgICAgICB0aGlzLnBhZ2VTZXR0aW5ncyA9IHtcbiAgICAgICAgICAgIHRpdGxlOiBwYWdlLnBhZ2VzX3RpdGxlIHx8ICcnLFxuICAgICAgICAgICAgbWV0YV9kZXNjcmlwdGlvbjogcGFnZS5tZXRhX2Rlc2NyaXB0aW9uIHx8ICcnLFxuICAgICAgICAgICAgbWV0YV9rZXl3b3JkczogcGFnZS5tZXRhX2tleXdvcmRzIHx8ICcnLFxuICAgICAgICAgICAgaGVhZGVyX2luY2x1ZGVzOiBwYWdlLmhlYWRlcl9pbmNsdWRlcyB8fCAnJyxcbiAgICAgICAgICAgIHBhZ2VfY3NzOiBwYWdlLnBhZ2VfY3NzIHx8ICcnXG4gICAgICAgIH07XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIHRoaXMucGFnZU1lbnVUZW1wbGF0ZSA9ICc8YSBocmVmPVwiXCIgY2xhc3M9XCJtZW51SXRlbUxpbmtcIj5wYWdlPC9hPjxzcGFuIGNsYXNzPVwicGFnZUJ1dHRvbnNcIj48YSBocmVmPVwiXCIgY2xhc3M9XCJmaWxlRWRpdCBmdWktbmV3XCI+PC9hPjxhIGhyZWY9XCJcIiBjbGFzcz1cImZpbGVEZWwgZnVpLWNyb3NzXCI+PGEgY2xhc3M9XCJidG4gYnRuLXhzIGJ0bi1wcmltYXJ5IGJ0bi1lbWJvc3NlZCBmaWxlU2F2ZSBmdWktY2hlY2tcIiBocmVmPVwiI1wiPjwvYT48L3NwYW4+PC9hPjwvc3Bhbj4nO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5tZW51SXRlbSA9IHt9Oy8vcmVmZXJlbmNlIHRvIHRoZSBwYWdlcyBtZW51IGl0ZW0gZm9yIHRoaXMgcGFnZSBpbnN0YW5jZVxuICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtID0ge307Ly9yZWZlcmVuY2UgdG8gdGhlIGxpbmtzIGRyb3Bkb3duIGl0ZW0gZm9yIHRoaXMgcGFnZSBpbnN0YW5jZVxuICAgICAgICBcbiAgICAgICAgdGhpcy5wYXJlbnRVTCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ1VMJyk7XG4gICAgICAgIHRoaXMucGFyZW50VUwuc2V0QXR0cmlidXRlKCdpZCcsIFwicGFnZVwiK2NvdW50ZXIpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgbWFrZXMgdGhlIGNsaWNrZWQgcGFnZSBhY3RpdmVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zZWxlY3RQYWdlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vY29uc29sZS5sb2coJ3NlbGVjdDonKTtcbiAgICAgICAgICAgIC8vY29uc29sZS5sb2codGhpcy5wYWdlU2V0dGluZ3MpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL21hcmsgdGhlIG1lbnUgaXRlbSBhcyBhY3RpdmVcbiAgICAgICAgICAgIHNpdGUuZGVBY3RpdmF0ZUFsbCgpO1xuICAgICAgICAgICAgJCh0aGlzLm1lbnVJdGVtKS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbGV0IFNpdGUga25vdyB3aGljaCBwYWdlIGlzIGN1cnJlbnRseSBhY3RpdmVcbiAgICAgICAgICAgIHNpdGUuc2V0QWN0aXZlKHRoaXMpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Rpc3BsYXkgdGhlIG5hbWUgb2YgdGhlIGFjdGl2ZSBwYWdlIG9uIHRoZSBjYW52YXNcbiAgICAgICAgICAgIHNpdGUucGFnZVRpdGxlLmlubmVySFRNTCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9sb2FkIHRoZSBwYWdlIHNldHRpbmdzIGludG8gdGhlIHBhZ2Ugc2V0dGluZ3MgbW9kYWxcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NUaXRsZS52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLnRpdGxlO1xuICAgICAgICAgICAgc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc01ldGFEZXNjcmlwdGlvbi52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLm1ldGFfZGVzY3JpcHRpb247XG4gICAgICAgICAgICBzaXRlLmlucHV0UGFnZVNldHRpbmdzTWV0YUtleXdvcmRzLnZhbHVlID0gdGhpcy5wYWdlU2V0dGluZ3MubWV0YV9rZXl3b3JkcztcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NJbmNsdWRlcy52YWx1ZSA9IHRoaXMucGFnZVNldHRpbmdzLmhlYWRlcl9pbmNsdWRlcztcbiAgICAgICAgICAgIHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NQYWdlQ3NzLnZhbHVlID0gdGhpcy5wYWdlU2V0dGluZ3MucGFnZV9jc3M7XG4gICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy90cmlnZ2VyIGN1c3RvbSBldmVudFxuICAgICAgICAgICAgJCgnYm9keScpLnRyaWdnZXIoJ2NoYW5nZVBhZ2UnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZXNldCB0aGUgaGVpZ2h0cyBmb3IgdGhlIGJsb2NrcyBvbiB0aGUgY3VycmVudCBwYWdlXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBPYmplY3Qua2V5cyh0aGlzLmJsb2Nrc1tpXS5mcmFtZURvY3VtZW50KS5sZW5ndGggPiAwICl7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzW2ldLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zaG93IHRoZSBlbXB0eSBtZXNzYWdlP1xuICAgICAgICAgICAgdGhpcy5pc0VtcHR5KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjaGFuZ2VkIHRoZSBsb2NhdGlvbi9vcmRlciBvZiBhIGJsb2NrIHdpdGhpbiBhIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zZXRQb3NpdGlvbiA9IGZ1bmN0aW9uKGZyYW1lSUQsIG5ld1Bvcykge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3dlJ2xsIG5lZWQgdGhlIGJsb2NrIG9iamVjdCBjb25uZWN0ZWQgdG8gaWZyYW1lIHdpdGggZnJhbWVJRFxuICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdGhpcy5ibG9ja3MpIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5ibG9ja3NbaV0uZnJhbWUuZ2V0QXR0cmlidXRlKCdpZCcpID09PSBmcmFtZUlEICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9jaGFuZ2UgdGhlIHBvc2l0aW9uIG9mIHRoaXMgYmxvY2sgaW4gdGhlIGJsb2NrcyBhcnJheVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrcy5zcGxpY2UobmV3UG9zLCAwLCB0aGlzLmJsb2Nrcy5zcGxpY2UoaSwgMSlbMF0pO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZWxldGUgYmxvY2sgZnJvbSBibG9ja3MgYXJyYXlcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5kZWxldGVCbG9jayA9IGZ1bmN0aW9uKGJsb2NrKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVtb3ZlIGZyb20gYmxvY2tzIGFycmF5XG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuYmxvY2tzICkge1xuICAgICAgICAgICAgICAgIGlmKCB0aGlzLmJsb2Nrc1tpXSA9PT0gYmxvY2sgKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vZm91bmQgaXQsIHJlbW92ZSBmcm9tIGJsb2NrcyBhcnJheVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrcy5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdG9nZ2xlcyBhbGwgYmxvY2sgZnJhbWVDb3ZlcnMgb24gdGhpcyBwYWdlXG4gICAgICAgICovXG4gICAgICAgIHRoaXMudG9nZ2xlRnJhbWVDb3ZlcnMgPSBmdW5jdGlvbihvbk9yT2ZmKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5ibG9ja3MgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrc1tpXS50b2dnbGVDb3Zlcihvbk9yT2ZmKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHNldHVwIGZvciBlZGl0aW5nIGEgcGFnZSBuYW1lXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZWRpdFBhZ2VOYW1lID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAhdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXQnKSApIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vaGlkZSB0aGUgbGlua1xuICAgICAgICAgICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5tZW51SXRlbUxpbmsnKS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9pbnNlcnQgdGhlIGlucHV0IGZpZWxkXG4gICAgICAgICAgICAgICAgdmFyIG5ld0lucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC50eXBlID0gJ3RleHQnO1xuICAgICAgICAgICAgICAgIG5ld0lucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsICdwYWdlJyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRoaXMubmFtZSk7XG4gICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5pbnNlcnRCZWZvcmUobmV3SW5wdXQsIHRoaXMubWVudUl0ZW0uZmlyc3RDaGlsZCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIG5ld0lucHV0LmZvY3VzKCk7XG4gICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciB0bXBTdHIgPSBuZXdJbnB1dC5nZXRBdHRyaWJ1dGUoJ3ZhbHVlJyk7XG4gICAgICAgICAgICAgICAgbmV3SW5wdXQuc2V0QXR0cmlidXRlKCd2YWx1ZScsICcnKTtcbiAgICAgICAgICAgICAgICBuZXdJbnB1dC5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgdG1wU3RyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLmNsYXNzTGlzdC5hZGQoJ2VkaXQnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgVXBkYXRlcyB0aGlzIHBhZ2UncyBuYW1lIChldmVudCBoYW5kbGVyIGZvciB0aGUgc2F2ZSBidXR0b24pXG4gICAgICAgICovXG4gICAgICAgIHRoaXMudXBkYXRlUGFnZU5hbWVFdmVudCA9IGZ1bmN0aW9uKGVsKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCB0aGlzLm1lbnVJdGVtLmNsYXNzTGlzdC5jb250YWlucygnZWRpdCcpICkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9lbCBpcyB0aGUgY2xpY2tlZCBidXR0b24sIHdlJ2xsIG5lZWQgYWNjZXNzIHRvIHRoZSBpbnB1dFxuICAgICAgICAgICAgICAgIHZhciB0aGVJbnB1dCA9IHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignaW5wdXRbbmFtZT1cInBhZ2VcIl0nKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL21ha2Ugc3VyZSB0aGUgcGFnZSdzIG5hbWUgaXMgT0tcbiAgICAgICAgICAgICAgICBpZiggc2l0ZS5jaGVja1BhZ2VOYW1lKHRoZUlucHV0LnZhbHVlKSApIHtcbiAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lID0gc2l0ZS5wcmVwUGFnZU5hbWUoIHRoZUlucHV0LnZhbHVlICk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPVwicGFnZVwiXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EubWVudUl0ZW1MaW5rJykuaW5uZXJIVE1MID0gdGhpcy5uYW1lO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EubWVudUl0ZW1MaW5rJykuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5jbGFzc0xpc3QucmVtb3ZlKCdlZGl0Jyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vdXBkYXRlIHRoZSBsaW5rcyBkcm9wZG93biBpdGVtXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0udGV4dCA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgdGhpcy5uYW1lK1wiLmh0bWxcIik7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3VwZGF0ZSB0aGUgcGFnZSBuYW1lIG9uIHRoZSBjYW52YXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5wYWdlVGl0bGUuaW5uZXJIVE1MID0gdGhpcy5uYW1lO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vY2hhbmdlZCBwYWdlIHRpdGxlLCB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGFsZXJ0KHNpdGUucGFnZU5hbWVFcnJvcik7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGRlbGV0ZXMgdGhpcyBlbnRpcmUgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmRlbGV0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2RlbGV0ZSBmcm9tIHRoZSBTaXRlXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHNpdGUuc2l0ZVBhZ2VzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBzaXRlLnNpdGVQYWdlc1tpXSA9PT0gdGhpcyApIHsvL2dvdCBhIG1hdGNoIVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldGUgZnJvbSBzaXRlLnNpdGVQYWdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnNpdGVQYWdlcy5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2RlbGV0ZSBmcm9tIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnBhcmVudFVMLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9hZGQgdG8gZGVsZXRlZCBwYWdlc1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnBhZ2VzVG9EZWxldGUucHVzaCh0aGlzLm5hbWUpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9kZWxldGUgdGhlIHBhZ2UncyBtZW51IGl0ZW1cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51SXRlbS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXQgdGhlIHBhZ2VzIGxpbmsgZHJvcGRvd24gaXRlbVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmxpbmtzRHJvcGRvd25JdGVtLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9hY3RpdmF0ZSB0aGUgZmlyc3QgcGFnZVxuICAgICAgICAgICAgICAgICAgICBzaXRlLnNpdGVQYWdlc1swXS5zZWxlY3RQYWdlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3BhZ2Ugd2FzIGRlbGV0ZWQsIHNvIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2hlY2tzIGlmIHRoZSBwYWdlIGlzIGVtcHR5LCBpZiBzbyBzaG93IHRoZSAnZW1wdHknIG1lc3NhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5pc0VtcHR5ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCB0aGlzLmJsb2Nrcy5sZW5ndGggPT09IDAgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgc2l0ZS5tZXNzYWdlU3RhcnQuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICAgICAgc2l0ZS5kaXZGcmFtZVdyYXBwZXIuY2xhc3NMaXN0LmFkZCgnZW1wdHknKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNpdGUubWVzc2FnZVN0YXJ0LnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICAgICAgc2l0ZS5kaXZGcmFtZVdyYXBwZXIuY2xhc3NMaXN0LnJlbW92ZSgnZW1wdHknKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBwcmVwcy9zdHJpcHMgdGhpcyBwYWdlIGRhdGEgZm9yIGEgcGVuZGluZyBhamF4IHJlcXVlc3RcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5wcmVwRm9yU2F2ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZSA9IHt9O1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHBhZ2UubmFtZSA9IHRoaXMubmFtZTtcbiAgICAgICAgICAgIHBhZ2UucGFnZVNldHRpbmdzID0gdGhpcy5wYWdlU2V0dGluZ3M7XG4gICAgICAgICAgICBwYWdlLnN0YXR1cyA9IHRoaXMuc3RhdHVzO1xuICAgICAgICAgICAgcGFnZS5wYWdlSUQgPSB0aGlzLnBhZ2VJRDtcbiAgICAgICAgICAgIHBhZ2UuYmxvY2tzID0gW107XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9wcm9jZXNzIHRoZSBibG9ja3NcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciB4ID0gMDsgeCA8IHRoaXMuYmxvY2tzLmxlbmd0aDsgeCsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIGJsb2NrID0ge307XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggdGhpcy5ibG9ja3NbeF0uc2FuZGJveCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgYmxvY2suZnJhbWVDb250ZW50ID0gXCI8aHRtbD5cIiskKCcjc2FuZGJveGVzICMnK3RoaXMuYmxvY2tzW3hdLnNhbmRib3gpLmNvbnRlbnRzKCkuZmluZCgnaHRtbCcpLmh0bWwoKStcIjwvaHRtbD5cIjtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2suc2FuZGJveCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLmxvYWRlckZ1bmN0aW9uID0gdGhpcy5ibG9ja3NbeF0uc2FuZGJveF9sb2FkZXI7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLmZyYW1lQ29udGVudCA9IHRoaXMuYmxvY2tzW3hdLmdldFNvdXJjZSgpO1xuICAgICAgICAgICAgICAgICAgICBibG9jay5zYW5kYm94ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIGJsb2NrLmxvYWRlckZ1bmN0aW9uID0gJyc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suZnJhbWVIZWlnaHQgPSB0aGlzLmJsb2Nrc1t4XS5mcmFtZUhlaWdodDtcbiAgICAgICAgICAgICAgICBibG9jay5vcmlnaW5hbFVybCA9IHRoaXMuYmxvY2tzW3hdLm9yaWdpbmFsVXJsO1xuICAgICAgICAgICAgICAgIGlmICggdGhpcy5ibG9ja3NbeF0uZ2xvYmFsICkgYmxvY2suZnJhbWVzX2dsb2JhbCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgcGFnZS5ibG9ja3MucHVzaChibG9jayk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgcmV0dXJuIHBhZ2U7XG4gICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZ2VuZXJhdGVzIHRoZSBmdWxsIHBhZ2UsIHVzaW5nIHNrZWxldG9uLmh0bWxcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5mdWxsUGFnZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgcGFnZSA9IHRoaXM7Ly9yZWZlcmVuY2UgdG8gc2VsZiBmb3IgbGF0ZXJcbiAgICAgICAgICAgIHBhZ2Uuc2NyaXB0cyA9IFtdOy8vbWFrZSBzdXJlIGl0J3MgZW1wdHksIHdlJ2xsIHN0b3JlIHNjcmlwdCBVUkxzIGluIHRoZXJlIGxhdGVyXG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBuZXdEb2NNYWluUGFyZW50ID0gJCgnaWZyYW1lI3NrZWxldG9uJykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbXB0eSBvdXQgdGhlIHNrZWxldG9uIGZpcnN0XG4gICAgICAgICAgICAkKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmh0bWwoJycpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBvbGQgc2NyaXB0IHRhZ3NcbiAgICAgICAgICAgICQoJ2lmcmFtZSNza2VsZXRvbicpLmNvbnRlbnRzKCkuZmluZCggJ3NjcmlwdCcgKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB2YXIgdGhlQ29udGVudHM7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGZvciggdmFyIGkgaW4gdGhpcy5ibG9ja3MgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9ncmFiIHRoZSBibG9jayBjb250ZW50XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuYmxvY2tzW2ldLnNhbmRib3ggIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cyA9ICQoJyNzYW5kYm94ZXMgIycrdGhpcy5ibG9ja3NbaV0uc2FuZGJveCkuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cyA9ICQodGhpcy5ibG9ja3NbaV0uZnJhbWVEb2N1bWVudC5ib2R5KS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgdmlkZW8gZnJhbWVDb3ZlcnNcbiAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCcuZnJhbWVDb3ZlcicpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcmVtb3ZlIHZpZGVvIGZyYW1lV3JhcHBlcnNcbiAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCcudmlkZW9XcmFwcGVyJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdmFyIGNudCA9ICQodGhpcykuY29udGVudHMoKTtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZXBsYWNlV2l0aChjbnQpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBzdHlsZSBsZWZ0b3ZlcnMgZnJvbSB0aGUgc3R5bGUgZWRpdG9yXG4gICAgICAgICAgICAgICAgZm9yKCB2YXIga2V5IGluIGJDb25maWcuZWRpdGFibGVJdGVtcyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCgga2V5ICkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUF0dHIoJ2RhdGEtc2VsZWN0b3InKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5jc3MoJ291dGxpbmUnLCAnJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcygnb3V0bGluZS1vZmZzZXQnLCAnJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmNzcygnY3Vyc29yJywgJycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggJCh0aGlzKS5hdHRyKCdzdHlsZScpID09PSAnJyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlbW92ZSBzdHlsZSBsZWZ0b3ZlcnMgZnJvbSB0aGUgY29udGVudCBlZGl0b3JcbiAgICAgICAgICAgICAgICBmb3IgKCB2YXIgeCA9IDA7IHggPCBiQ29uZmlnLmVkaXRhYmxlQ29udGVudC5sZW5ndGg7ICsreCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMuZmluZCggYkNvbmZpZy5lZGl0YWJsZUNvbnRlbnRbeF0gKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlQXR0cignZGF0YS1zZWxlY3RvcicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vYXBwZW5kIHRvIERPTSBpbiB0aGUgc2tlbGV0b25cbiAgICAgICAgICAgICAgICBuZXdEb2NNYWluUGFyZW50LmFwcGVuZCggJCh0aGVDb250ZW50cy5odG1sKCkpICk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9kbyB3ZSBuZWVkIHRvIGluamVjdCBhbnkgc2NyaXB0cz9cbiAgICAgICAgICAgICAgICB2YXIgc2NyaXB0cyA9ICQodGhpcy5ibG9ja3NbaV0uZnJhbWVEb2N1bWVudC5ib2R5KS5maW5kKCdzY3JpcHQnKTtcbiAgICAgICAgICAgICAgICB2YXIgdGhlSWZyYW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJza2VsZXRvblwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIHNjcmlwdHMuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHNjcmlwdHMuZWFjaChmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2NyaXB0O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiggJCh0aGlzKS50ZXh0KCkgIT09ICcnICkgey8vc2NyaXB0IHRhZ3Mgd2l0aCBjb250ZW50XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0ID0gdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNjcmlwdFwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQudHlwZSA9ICd0ZXh0L2phdmFzY3JpcHQnO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC5pbm5lckhUTUwgPSAkKHRoaXMpLnRleHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGVJZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHNjcmlwdCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggJCh0aGlzKS5hdHRyKCdzcmMnKSAhPT0gbnVsbCAmJiBwYWdlLnNjcmlwdHMuaW5kZXhPZigkKHRoaXMpLmF0dHIoJ3NyYycpKSA9PT0gLTEgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy91c2UgaW5kZXhPZiB0byBtYWtlIHN1cmUgZWFjaCBzY3JpcHQgb25seSBhcHBlYXJzIG9uIHRoZSBwcm9kdWNlZCBwYWdlIG9uY2VcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQgPSB0aGVJZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic2NyaXB0XCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC50eXBlID0gJ3RleHQvamF2YXNjcmlwdCc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LnNyYyA9ICQodGhpcykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChzY3JpcHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhZ2Uuc2NyaXB0cy5wdXNoKCQodGhpcykuYXR0cignc3JjJykpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIENoZWNrcyBpZiBhbGwgYmxvY2tzIG9uIHRoaXMgcGFnZSBoYXZlIGZpbmlzaGVkIGxvYWRpbmdcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5sb2FkZWQgPSBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIHZhciBpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8dGhpcy5ibG9ja3MubGVuZ3RoOyBpKysgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoICF0aGlzLmJsb2Nrc1tpXS5sb2FkZWQgKSByZXR1cm4gZmFsc2U7XG5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG5cbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2xlYXIgb3V0IHRoaXMgcGFnZVxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNsZWFyID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBibG9jayA9IHRoaXMuYmxvY2tzLnBvcCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB3aGlsZSggYmxvY2sgIT09IHVuZGVmaW5lZCApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBibG9jay5kZWxldGUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBibG9jayA9IHRoaXMuYmxvY2tzLnBvcCgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgSGVpZ2h0IGFkanVzdG1lbnQgZm9yIGFsbCBibG9ja3Mgb24gdGhlIHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5oZWlnaHRBZGp1c3RtZW50ID0gZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICBmb3IgKCB2YXIgaSA9IDA7IGkgPCB0aGlzLmJsb2Nrcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICB0aGlzLmJsb2Nrc1tpXS5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfTtcbiAgICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLy9sb29wIHRocm91Z2ggdGhlIGZyYW1lcy9ibG9ja3NcbiAgICAgICAgXG4gICAgICAgIGlmKCBwYWdlLmhhc093blByb3BlcnR5KCdibG9ja3MnKSApIHtcbiAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciB4ID0gMDsgeCA8IHBhZ2UuYmxvY2tzLmxlbmd0aDsgeCsrICkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9jcmVhdGUgbmV3IEJsb2NrXG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgbmV3QmxvY2sgPSBuZXcgQmxvY2soKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHBhZ2UuYmxvY2tzW3hdLnNyYyA9IGFwcFVJLnNpdGVVcmwrXCJzaXRlcy9nZXRmcmFtZS9cIitwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfaWQ7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9zYW5kYm94ZWQgYmxvY2s/XG4gICAgICAgICAgICAgICAgaWYoIHBhZ2UuYmxvY2tzW3hdLmZyYW1lc19zYW5kYm94ID09PSAnMScpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suc2FuZGJveCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLnNhbmRib3hfbG9hZGVyID0gcGFnZS5ibG9ja3NbeF0uZnJhbWVzX2xvYWRlcmZ1bmN0aW9uO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5mcmFtZUlEID0gcGFnZS5ibG9ja3NbeF0uZnJhbWVzX2lkO1xuICAgICAgICAgICAgICAgIGlmICggcGFnZS5ibG9ja3NbeF0uZnJhbWVzX2dsb2JhbCA9PT0gJzEnICkgbmV3QmxvY2suZ2xvYmFsID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVQYXJlbnRMSShwYWdlLmJsb2Nrc1t4XS5mcmFtZXNfaGVpZ2h0KTtcbiAgICAgICAgICAgICAgICBuZXdCbG9jay5jcmVhdGVGcmFtZShwYWdlLmJsb2Nrc1t4XSk7XG4gICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlRnJhbWVDb3ZlcigpO1xuICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmluc2VydEJsb2NrSW50b0RvbSh0aGlzLnBhcmVudFVMKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9hZGQgdGhlIGJsb2NrIHRvIHRoZSBuZXcgcGFnZVxuICAgICAgICAgICAgICAgIHRoaXMuYmxvY2tzLnB1c2gobmV3QmxvY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgICAgIC8vYWRkIHRoaXMgcGFnZSB0byB0aGUgc2l0ZSBvYmplY3RcbiAgICAgICAgc2l0ZS5zaXRlUGFnZXMucHVzaCggdGhpcyApO1xuICAgICAgICBcbiAgICAgICAgLy9wbGFudCB0aGUgbmV3IFVMIGluIHRoZSBET00gKG9uIHRoZSBjYW52YXMpXG4gICAgICAgIHNpdGUuZGl2Q2FudmFzLmFwcGVuZENoaWxkKHRoaXMucGFyZW50VUwpO1xuICAgICAgICBcbiAgICAgICAgLy9tYWtlIHRoZSBibG9ja3MvZnJhbWVzIGluIGVhY2ggcGFnZSBzb3J0YWJsZVxuICAgICAgICBcbiAgICAgICAgdmFyIHRoZVBhZ2UgPSB0aGlzO1xuICAgICAgICBcbiAgICAgICAgJCh0aGlzLnBhcmVudFVMKS5zb3J0YWJsZSh7XG4gICAgICAgICAgICByZXZlcnQ6IHRydWUsXG4gICAgICAgICAgICBwbGFjZWhvbGRlcjogXCJkcm9wLWhvdmVyXCIsXG4gICAgICAgICAgICBoYW5kbGU6ICcuZHJhZ0Jsb2NrJyxcbiAgICAgICAgICAgIGNhbmNlbDogJycsXG4gICAgICAgICAgICBzdG9wOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgc2l0ZS5tb3ZlTW9kZSgnb2ZmJyk7XG4gICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICBpZiAoICFzaXRlLmxvYWRlZCgpICkgYnVpbGRlclVJLmNhbnZhc0xvYWRpbmcoJ29uJyk7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgYmVmb3JlU3RvcDogZnVuY3Rpb24oZXZlbnQsIHVpKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3RlbXBsYXRlIG9yIHJlZ3VsYXIgYmxvY2s/XG4gICAgICAgICAgICAgICAgdmFyIGF0dHIgPSB1aS5pdGVtLmF0dHIoJ2RhdGEtZnJhbWVzJyk7XG5cbiAgICAgICAgICAgICAgICB2YXIgbmV3QmxvY2s7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgYXR0ciAhPT0gdHlwZW9mIHVuZGVmaW5lZCAmJiBhdHRyICE9PSBmYWxzZSkgey8vdGVtcGxhdGUsIGJ1aWxkIGl0XG4gICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKCcjc3RhcnQnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vY2xlYXIgb3V0IGFsbCBibG9ja3Mgb24gdGhpcyBwYWdlICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVQYWdlLmNsZWFyKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2NyZWF0ZSB0aGUgbmV3IGZyYW1lc1xuICAgICAgICAgICAgICAgICAgICB2YXIgZnJhbWVJRHMgPSB1aS5pdGVtLmF0dHIoJ2RhdGEtZnJhbWVzJykuc3BsaXQoJy0nKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGhlaWdodHMgPSB1aS5pdGVtLmF0dHIoJ2RhdGEtaGVpZ2h0cycpLnNwbGl0KCctJyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciB1cmxzID0gdWkuaXRlbS5hdHRyKCdkYXRhLW9yaWdpbmFsdXJscycpLnNwbGl0KCctJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgZm9yKCB2YXIgeCA9IDA7IHggPCBmcmFtZUlEcy5sZW5ndGg7IHgrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jayA9IG5ldyBCbG9jaygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3QmxvY2suY3JlYXRlUGFyZW50TEkoaGVpZ2h0c1t4XSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBmcmFtZURhdGEgPSB7fTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWVEYXRhLnNyYyA9IGFwcFVJLnNpdGVVcmwrJ3NpdGVzL2dldGZyYW1lLycrZnJhbWVJRHNbeF07XG4gICAgICAgICAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX29yaWdpbmFsX3VybCA9IGFwcFVJLnNpdGVVcmwrJ3NpdGVzL2dldGZyYW1lLycrZnJhbWVJRHNbeF07XG4gICAgICAgICAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX2hlaWdodCA9IGhlaWdodHNbeF07XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZUZyYW1lKCBmcmFtZURhdGEgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmNyZWF0ZUZyYW1lQ292ZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLmluc2VydEJsb2NrSW50b0RvbSh0aGVQYWdlLnBhcmVudFVMKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9hZGQgdGhlIGJsb2NrIHRvIHRoZSBuZXcgcGFnZVxuICAgICAgICAgICAgICAgICAgICAgICAgdGhlUGFnZS5ibG9ja3MucHVzaChuZXdCbG9jayk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vZHJvcHBlZCBlbGVtZW50LCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vc2V0IHRoZSB0ZW1wYXRlSURcbiAgICAgICAgICAgICAgICAgICAgYnVpbGRlclVJLnRlbXBsYXRlSUQgPSB1aS5pdGVtLmF0dHIoJ2RhdGEtcGFnZWlkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9tYWtlIHN1cmUgbm90aGluZyBnZXRzIGRyb3BwZWQgaW4gdGhlIGxzaXRcbiAgICAgICAgICAgICAgICAgICAgdWkuaXRlbS5odG1sKG51bGwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8vZGVsZXRlIGRyYWcgcGxhY2UgaG9sZGVyXG4gICAgICAgICAgICAgICAgICAgICQoJ2JvZHkgLnVpLXNvcnRhYmxlLWhlbHBlcicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2Ugey8vcmVndWxhciBibG9ja1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2FyZSB3ZSBkZWFsaW5nIHdpdGggYSBuZXcgYmxvY2sgYmVpbmcgZHJvcHBlZCBvbnRvIHRoZSBjYW52YXMsIG9yIGEgcmVvcmRlcmluZyBvZyBibG9ja3MgYWxyZWFkeSBvbiB0aGUgY2FudmFzP1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggdWkuaXRlbS5maW5kKCcuZnJhbWVDb3ZlciA+IGJ1dHRvbicpLnNpemUoKSA+IDAgKSB7Ly9yZS1vcmRlcmluZyBvZiBibG9ja3Mgb24gY2FudmFzXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9ubyBuZWVkIHRvIGNyZWF0ZSBhIG5ldyBibG9jayBvYmplY3QsIHdlIHNpbXBseSBuZWVkIHRvIG1ha2Ugc3VyZSB0aGUgcG9zaXRpb24gb2YgdGhlIGV4aXN0aW5nIGJsb2NrIGluIHRoZSBTaXRlIG9iamVjdFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9pcyBjaGFuZ2VkIHRvIHJlZmxlY3QgdGhlIG5ldyBwb3NpdGlvbiBvZiB0aGUgYmxvY2sgb24gdGggY2FudmFzXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGZyYW1lSUQgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ2lkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3UG9zID0gdWkuaXRlbS5pbmRleCgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5zZXRQb3NpdGlvbihmcmFtZUlELCBuZXdQb3MpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Ugey8vbmV3IGJsb2NrIG9uIGNhbnZhc1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL25ldyBibG9jayAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdCbG9jayA9IG5ldyBCbG9jaygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0Jsb2NrLnBsYWNlT25DYW52YXModWkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHN0YXJ0OiBmdW5jdGlvbiAoZXZlbnQsIHVpKSB7XG5cbiAgICAgICAgICAgICAgICBzaXRlLm1vdmVNb2RlKCdvbicpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggdWkuaXRlbS5maW5kKCcuZnJhbWVDb3ZlcicpLnNpemUoKSAhPT0gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgYnVpbGRlclVJLmZyYW1lQ29udGVudHMgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICkuaHRtbCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIG92ZXI6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQoJyNzdGFydCcpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIFxuICAgICAgICAvL2FkZCB0byB0aGUgcGFnZXMgbWVudVxuICAgICAgICB0aGlzLm1lbnVJdGVtID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnTEknKTtcbiAgICAgICAgdGhpcy5tZW51SXRlbS5pbm5lckhUTUwgPSB0aGlzLnBhZ2VNZW51VGVtcGxhdGU7XG4gICAgICAgIFxuICAgICAgICAkKHRoaXMubWVudUl0ZW0pLmZpbmQoJ2E6Zmlyc3QnKS50ZXh0KHBhZ2VOYW1lKS5hdHRyKCdocmVmJywgJyNwYWdlJytjb3VudGVyKTtcbiAgICAgICAgXG4gICAgICAgIHZhciB0aGVMaW5rID0gJCh0aGlzLm1lbnVJdGVtKS5maW5kKCdhOmZpcnN0JykuZ2V0KDApO1xuICAgICAgICBcbiAgICAgICAgLy9iaW5kIHNvbWUgZXZlbnRzXG4gICAgICAgIHRoaXMubWVudUl0ZW0uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgIFxuICAgICAgICB0aGlzLm1lbnVJdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EuZmlsZUVkaXQnKS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgdGhpcy5tZW51SXRlbS5xdWVyeVNlbGVjdG9yKCdhLmZpbGVTYXZlJykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgIHRoaXMubWVudUl0ZW0ucXVlcnlTZWxlY3RvcignYS5maWxlRGVsJykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgIFxuICAgICAgICAvL2FkZCB0byB0aGUgcGFnZSBsaW5rIGRyb3Bkb3duXG4gICAgICAgIHRoaXMubGlua3NEcm9wZG93bkl0ZW0gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdPUFRJT04nKTtcbiAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgcGFnZU5hbWUrXCIuaHRtbFwiKTtcbiAgICAgICAgdGhpcy5saW5rc0Ryb3Bkb3duSXRlbS50ZXh0ID0gcGFnZU5hbWU7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIGJ1aWxkZXJVSS5kcm9wZG93blBhZ2VMaW5rcy5hcHBlbmRDaGlsZCggdGhpcy5saW5rc0Ryb3Bkb3duSXRlbSApO1xuICAgICAgICBcbiAgICAgICAgc2l0ZS5wYWdlc01lbnUuYXBwZW5kQ2hpbGQodGhpcy5tZW51SXRlbSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgIH1cbiAgICBcbiAgICBQYWdlLnByb3RvdHlwZS5oYW5kbGVFdmVudCA9IGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgIHN3aXRjaCAoZXZlbnQudHlwZSkge1xuICAgICAgICAgICAgY2FzZSBcImNsaWNrXCI6IFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnZmlsZUVkaXQnKSApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5lZGl0UGFnZU5hbWUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaWxlU2F2ZScpICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLnVwZGF0ZVBhZ2VOYW1lRXZlbnQoZXZlbnQudGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbGVEZWwnKSApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHZhciB0aGVQYWdlID0gdGhpcztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVQYWdlKS5tb2RhbCgnc2hvdycpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVQYWdlKS5vZmYoJ2NsaWNrJywgJyNkZWxldGVQYWdlQ29uZmlybScpLm9uKCdjbGljaycsICcjZGVsZXRlUGFnZUNvbmZpcm0nLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdGhlUGFnZS5kZWxldGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxEZWxldGVQYWdlKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGlzLnNlbGVjdFBhZ2UoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICB9O1xuXG5cbiAgICAvKlxuICAgICAgICBCbG9jayBjb25zdHJ1Y3RvclxuICAgICovXG4gICAgZnVuY3Rpb24gQmxvY2sgKCkge1xuICAgICAgICBcbiAgICAgICAgdGhpcy5mcmFtZUlEID0gMDtcbiAgICAgICAgdGhpcy5sb2FkZWQgPSBmYWxzZTtcbiAgICAgICAgdGhpcy5zYW5kYm94ID0gZmFsc2U7XG4gICAgICAgIHRoaXMuc2FuZGJveF9sb2FkZXIgPSAnJztcbiAgICAgICAgdGhpcy5zdGF0dXMgPSAnJzsvLycnLCAnY2hhbmdlZCcgb3IgJ25ldydcbiAgICAgICAgdGhpcy5nbG9iYWwgPSBmYWxzZTtcbiAgICAgICAgdGhpcy5vcmlnaW5hbFVybCA9ICcnO1xuICAgICAgICBcbiAgICAgICAgdGhpcy5wYXJlbnRMSSA9IHt9O1xuICAgICAgICB0aGlzLmZyYW1lQ292ZXIgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZSA9IHt9O1xuICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQgPSB7fTtcbiAgICAgICAgdGhpcy5mcmFtZUhlaWdodCA9IDA7XG4gICAgICAgIFxuICAgICAgICB0aGlzLmFubm90ID0ge307XG4gICAgICAgIHRoaXMuYW5ub3RUaW1lb3V0ID0ge307XG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY3JlYXRlcyB0aGUgcGFyZW50IGNvbnRhaW5lciAoTEkpXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY3JlYXRlUGFyZW50TEkgPSBmdW5jdGlvbihoZWlnaHQpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0xJJyk7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnZWxlbWVudCcpO1xuICAgICAgICAgICAgLy90aGlzLnBhcmVudExJLnNldEF0dHJpYnV0ZSgnc3R5bGUnLCAnaGVpZ2h0OiAnK2hlaWdodCsncHgnKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGNyZWF0ZXMgdGhlIGlmcmFtZSBvbiB0aGUgY2FudmFzXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuY3JlYXRlRnJhbWUgPSBmdW5jdGlvbihmcmFtZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmZyYW1lID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnSUZSQU1FJyk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnZnJhbWVib3JkZXInLCAwKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdzY3JvbGxpbmcnLCAwKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc2V0QXR0cmlidXRlKCdzcmMnLCBmcmFtZS5zcmMpO1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtb3JpZ2luYWx1cmwnLCBmcmFtZS5mcmFtZXNfb3JpZ2luYWxfdXJsKTtcbiAgICAgICAgICAgIHRoaXMub3JpZ2luYWxVcmwgPSBmcmFtZS5mcmFtZXNfb3JpZ2luYWxfdXJsO1xuICAgICAgICAgICAgLy90aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnZGF0YS1oZWlnaHQnLCBmcmFtZS5mcmFtZXNfaGVpZ2h0KTtcbiAgICAgICAgICAgIC8vdGhpcy5mcmFtZUhlaWdodCA9IGZyYW1lLmZyYW1lc19oZWlnaHQ7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICQodGhpcy5mcmFtZSkudW5pcXVlSWQoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zYW5kYm94P1xuICAgICAgICAgICAgaWYoIHRoaXMuc2FuZGJveCAhPT0gZmFsc2UgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtbG9hZGVyZnVuY3Rpb24nLCB0aGlzLnNhbmRib3hfbG9hZGVyKTtcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lLnNldEF0dHJpYnV0ZSgnZGF0YS1zYW5kYm94JywgdGhpcy5zYW5kYm94KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3JlY3JlYXRlIHRoZSBzYW5kYm94ZWQgaWZyYW1lIGVsc2V3aGVyZVxuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94ZWRGcmFtZSA9ICQoJzxpZnJhbWUgc3JjPVwiJytmcmFtZS5zcmMrJ1wiIGlkPVwiJyt0aGlzLnNhbmRib3grJ1wiIHNhbmRib3g9XCJhbGxvdy1zYW1lLW9yaWdpblwiPjwvaWZyYW1lPicpO1xuICAgICAgICAgICAgICAgICQoJyNzYW5kYm94ZXMnKS5hcHBlbmQoIHNhbmRib3hlZEZyYW1lICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgaW5zZXJ0IHRoZSBpZnJhbWUgaW50byB0aGUgRE9NIG9uIHRoZSBjYW52YXNcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5pbnNlcnRCbG9ja0ludG9Eb20gPSBmdW5jdGlvbih0aGVVTCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmFwcGVuZENoaWxkKHRoaXMuZnJhbWUpO1xuICAgICAgICAgICAgdGhlVUwuYXBwZW5kQ2hpbGQoIHRoaXMucGFyZW50TEkgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5mcmFtZS5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgdGhpcywgZmFsc2UpO1xuXG4gICAgICAgICAgICBidWlsZGVyVUkuY2FudmFzTG9hZGluZygnb24nKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzZXRzIHRoZSBmcmFtZSBkb2N1bWVudCBmb3IgdGhlIGJsb2NrJ3MgaWZyYW1lXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuc2V0RnJhbWVEb2N1bWVudCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3NldCB0aGUgZnJhbWUgZG9jdW1lbnQgYXMgd2VsbFxuICAgICAgICAgICAgaWYoIHRoaXMuZnJhbWUuY29udGVudERvY3VtZW50ICkge1xuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudCA9IHRoaXMuZnJhbWUuY29udGVudERvY3VtZW50OyAgIFxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lRG9jdW1lbnQgPSB0aGlzLmZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vdGhpcy5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjcmVhdGVzIHRoZSBmcmFtZSBjb3ZlciBhbmQgYmxvY2sgYWN0aW9uIGJ1dHRvblxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lQ292ZXIgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9idWlsZCB0aGUgZnJhbWUgY292ZXIgYW5kIGJsb2NrIGFjdGlvbiBidXR0b25zXG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdESVYnKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5jbGFzc0xpc3QuYWRkKCdmcmFtZUNvdmVyJyk7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuY2xhc3NMaXN0LmFkZCgnZnJlc2gnKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgZGVsQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBkZWxCdXR0b24uc2V0QXR0cmlidXRlKCdjbGFzcycsICdidG4gYnRuLWludmVyc2UgYnRuLXNtIGRlbGV0ZUJsb2NrJyk7XG4gICAgICAgICAgICBkZWxCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgZGVsQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZ1aS10cmFzaFwiPjwvaT4gPHNwYW4+cmVtb3ZlPC9zcGFuPic7XG4gICAgICAgICAgICBkZWxCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHJlc2V0QnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICByZXNldEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2J0biBidG4taW52ZXJzZSBidG4tc20gcmVzZXRCbG9jaycpO1xuICAgICAgICAgICAgcmVzZXRCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgcmVzZXRCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtcmVmcmVzaFwiPjwvaT4gPHNwYW4+cmVzZXQ8L3NwYW4+JztcbiAgICAgICAgICAgIHJlc2V0QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcywgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBodG1sQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBodG1sQnV0dG9uLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnYnRuIGJ0bi1pbnZlcnNlIGJ0bi1zbSBodG1sQmxvY2snKTtcbiAgICAgICAgICAgIGh0bWxCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgaHRtbEJ1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmYSBmYS1jb2RlXCI+PC9pPiA8c3Bhbj5zb3VyY2U8L3NwYW4+JztcbiAgICAgICAgICAgIGh0bWxCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG5cbiAgICAgICAgICAgIHZhciBkcmFnQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBkcmFnQnV0dG9uLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnYnRuIGJ0bi1pbnZlcnNlIGJ0bi1zbSBkcmFnQmxvY2snKTtcbiAgICAgICAgICAgIGRyYWdCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgZHJhZ0J1dHRvbi5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmYSBmYS1hcnJvd3NcIj48L2k+IDxzcGFuPk1vdmU8L3NwYW4+JztcbiAgICAgICAgICAgIGRyYWdCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG5cbiAgICAgICAgICAgIHZhciBnbG9iYWxMYWJlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0xBQkVMJyk7XG4gICAgICAgICAgICBnbG9iYWxMYWJlbC5jbGFzc0xpc3QuYWRkKCdjaGVja2JveCcpO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuY2xhc3NMaXN0LmFkZCgncHJpbWFyeScpO1xuICAgICAgICAgICAgdmFyIGdsb2JhbENoZWNrYm94ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnSU5QVVQnKTtcbiAgICAgICAgICAgIGdsb2JhbENoZWNrYm94LnR5cGUgPSAnY2hlY2tib3gnO1xuICAgICAgICAgICAgZ2xvYmFsQ2hlY2tib3guc2V0QXR0cmlidXRlKCdkYXRhLXRvZ2dsZScsICdjaGVja2JveCcpO1xuICAgICAgICAgICAgZ2xvYmFsQ2hlY2tib3guY2hlY2tlZCA9IHRoaXMuZ2xvYmFsO1xuICAgICAgICAgICAgZ2xvYmFsTGFiZWwuYXBwZW5kQ2hpbGQoZ2xvYmFsQ2hlY2tib3gpO1xuICAgICAgICAgICAgdmFyIGdsb2JhbFRleHQgPSBkb2N1bWVudC5jcmVhdGVUZXh0Tm9kZSgnR2xvYmFsJyk7XG4gICAgICAgICAgICBnbG9iYWxMYWJlbC5hcHBlbmRDaGlsZChnbG9iYWxUZXh0KTtcblxuICAgICAgICAgICAgdmFyIHRyaWdnZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgICAgICB0cmlnZ2VyLmNsYXNzTGlzdC5hZGQoJ2Z1aS1nZWFyJyk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKGRlbEJ1dHRvbik7XG4gICAgICAgICAgICB0aGlzLmZyYW1lQ292ZXIuYXBwZW5kQ2hpbGQocmVzZXRCdXR0b24pO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKGh0bWxCdXR0b24pO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKGRyYWdCdXR0b24pO1xuICAgICAgICAgICAgdGhpcy5mcmFtZUNvdmVyLmFwcGVuZENoaWxkKGdsb2JhbExhYmVsKTtcbiAgICAgICAgICAgIHRoaXMuZnJhbWVDb3Zlci5hcHBlbmRDaGlsZCh0cmlnZ2VyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuYXBwZW5kQ2hpbGQodGhpcy5mcmFtZUNvdmVyKTtcblxuICAgICAgICAgICAgdmFyIHRoZUJsb2NrID0gdGhpcztcblxuICAgICAgICAgICAgJChnbG9iYWxDaGVja2JveCkub24oJ2NoYW5nZScsIGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgICAgICB0aGVCbG9jay50b2dnbGVHbG9iYWwoZSk7XG5cbiAgICAgICAgICAgIH0pLnJhZGlvY2hlY2soKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH07XG5cblxuICAgICAgICAvKlxuICAgICAgICAgICAgXG4gICAgICAgICovXG4gICAgICAgIHRoaXMudG9nZ2xlR2xvYmFsID0gZnVuY3Rpb24gKGUpIHtcblxuICAgICAgICAgICAgaWYgKCBlLmN1cnJlbnRUYXJnZXQuY2hlY2tlZCApIHRoaXMuZ2xvYmFsID0gdHJ1ZTtcbiAgICAgICAgICAgIGVsc2UgdGhpcy5nbG9iYWwgPSBmYWxzZTtcblxuICAgICAgICAgICAgLy93ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuXG4gICAgICAgICAgICBjb25zb2xlLmxvZyh0aGlzKTtcblxuICAgICAgICB9O1xuXG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGF1dG9tYXRpY2FsbHkgY29ycmVjdHMgdGhlIGhlaWdodCBvZiB0aGUgYmxvY2sncyBpZnJhbWUgZGVwZW5kaW5nIG9uIGl0cyBjb250ZW50XG4gICAgICAgICovXG4gICAgICAgIHRoaXMuaGVpZ2h0QWRqdXN0bWVudCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiAoIE9iamVjdC5rZXlzKHRoaXMuZnJhbWVEb2N1bWVudCkubGVuZ3RoICE9PSAwICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIGhlaWdodCxcbiAgICAgICAgICAgICAgICAgICAgYm9keUhlaWdodCA9IHRoaXMuZnJhbWVEb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCxcbiAgICAgICAgICAgICAgICAgICAgcGFnZUNvbnRhaW5lckhlaWdodCA9IHRoaXMuZnJhbWVEb2N1bWVudC5ib2R5LnF1ZXJ5U2VsZWN0b3IoIGJDb25maWcucGFnZUNvbnRhaW5lciApLm9mZnNldEhlaWdodDtcblxuICAgICAgICAgICAgICAgIGlmICggYm9keUhlaWdodCA+IHBhZ2VDb250YWluZXJIZWlnaHQgJiYgIXRoaXMuZnJhbWVEb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5jb250YWlucyggYkNvbmZpZy5ib2R5UGFkZGluZ0NsYXNzICkgKSBoZWlnaHQgPSBwYWdlQ29udGFpbmVySGVpZ2h0O1xuICAgICAgICAgICAgICAgIGVsc2UgaGVpZ2h0ID0gYm9keUhlaWdodDtcblxuICAgICAgICAgICAgICAgIHRoaXMuZnJhbWUuc3R5bGUuaGVpZ2h0ID0gaGVpZ2h0K1wicHhcIjtcbiAgICAgICAgICAgICAgICB0aGlzLnBhcmVudExJLnN0eWxlLmhlaWdodCA9IGhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICAgICAgLy90aGlzLmZyYW1lQ292ZXIuc3R5bGUuaGVpZ2h0ID0gaGVpZ2h0K1wicHhcIjtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLmZyYW1lSGVpZ2h0ID0gaGVpZ2h0O1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlcyBhIGJsb2NrXG4gICAgICAgICovXG4gICAgICAgIHRoaXMuZGVsZXRlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcmVtb3ZlIGZyb20gRE9NL2NhbnZhcyB3aXRoIGEgbmljZSBhbmltYXRpb25cbiAgICAgICAgICAgICQodGhpcy5mcmFtZS5wYXJlbnROb2RlKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdGhpcy5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLmlzRW1wdHkoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlbW92ZSBmcm9tIGJsb2NrcyBhcnJheSBpbiB0aGUgYWN0aXZlIHBhZ2VcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5kZWxldGVCbG9jayh0aGlzKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zYW5ib3hcbiAgICAgICAgICAgIGlmKCB0aGlzLnNhbmJkb3ggKSB7XG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIHRoaXMuc2FuZGJveCApLnJlbW92ZSgpOyAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2VsZW1lbnQgd2FzIGRlbGV0ZWQsIHNvIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZVxuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICByZXNldHMgYSBibG9jayB0byBpdCdzIG9yaWduYWwgc3RhdGVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5yZXNldCA9IGZ1bmN0aW9uIChmaXJlRXZlbnQpIHtcblxuICAgICAgICAgICAgaWYgKCB0eXBlb2YgZmlyZUV2ZW50ID09PSAndW5kZWZpbmVkJykgZmlyZUV2ZW50ID0gdHJ1ZTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZXNldCBmcmFtZSBieSByZWxvYWRpbmcgaXRcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuY29udGVudFdpbmRvdy5sb2NhdGlvbiA9IHRoaXMuZnJhbWUuZ2V0QXR0cmlidXRlKCdkYXRhLW9yaWdpbmFsdXJsJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vc2FuZGJveD9cbiAgICAgICAgICAgIGlmKCB0aGlzLnNhbmRib3ggKSB7XG4gICAgICAgICAgICAgICAgdmFyIHNhbmRib3hGcmFtZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMuc2FuZGJveCkuY29udGVudFdpbmRvdy5sb2NhdGlvbi5yZWxvYWQoKTsgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2VsZW1lbnQgd2FzIGRlbGV0ZWQsIHNvIHdlJ3ZlIGdvdCBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG5cbiAgICAgICAgICAgIGJ1aWxkZXJVSS5jYW52YXNMb2FkaW5nKCdvbicpO1xuXG4gICAgICAgICAgICBpZiAoIGZpcmVFdmVudCApIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgdGhpcywgJ3JlbG9hZCcpO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGxhdW5jaGVzIHRoZSBzb3VyY2UgY29kZSBlZGl0b3JcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zb3VyY2UgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9oaWRlIHRoZSBpZnJhbWVcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kaXNhYmxlIHNvcnRhYmxlIG9uIHRoZSBwYXJlbnRMSVxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnBhcmVudE5vZGUpLnNvcnRhYmxlKCdkaXNhYmxlJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnVpbHQgZWRpdG9yIGVsZW1lbnRcbiAgICAgICAgICAgIHZhciB0aGVFZGl0b3IgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdESVYnKTtcbiAgICAgICAgICAgIHRoZUVkaXRvci5jbGFzc0xpc3QuYWRkKCdhY2VFZGl0b3InKTtcbiAgICAgICAgICAgICQodGhlRWRpdG9yKS51bmlxdWVJZCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmFwcGVuZENoaWxkKHRoZUVkaXRvcik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYnVpbGQgYW5kIGFwcGVuZCBlcnJvciBkcmF3ZXJcbiAgICAgICAgICAgIHZhciBuZXdMSSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ0xJJyk7XG4gICAgICAgICAgICB2YXIgZXJyb3JEcmF3ZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdESVYnKTtcbiAgICAgICAgICAgIGVycm9yRHJhd2VyLmNsYXNzTGlzdC5hZGQoJ2Vycm9yRHJhd2VyJyk7XG4gICAgICAgICAgICBlcnJvckRyYXdlci5zZXRBdHRyaWJ1dGUoJ2lkJywgJ2Rpdl9lcnJvckRyYXdlcicpO1xuICAgICAgICAgICAgZXJyb3JEcmF3ZXIuaW5uZXJIVE1MID0gJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi14cyBidG4tZW1ib3NzZWQgYnRuLWRlZmF1bHQgYnV0dG9uX2NsZWFyRXJyb3JEcmF3ZXJcIiBpZD1cImJ1dHRvbl9jbGVhckVycm9yRHJhd2VyXCI+Q0xFQVI8L2J1dHRvbj4nO1xuICAgICAgICAgICAgbmV3TEkuYXBwZW5kQ2hpbGQoZXJyb3JEcmF3ZXIpO1xuICAgICAgICAgICAgZXJyb3JEcmF3ZXIucXVlcnlTZWxlY3RvcignYnV0dG9uJykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLnBhcmVudE5vZGUuaW5zZXJ0QmVmb3JlKG5ld0xJLCB0aGlzLnBhcmVudExJLm5leHRTaWJsaW5nKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgYWNlLmNvbmZpZy5zZXQoXCJiYXNlUGF0aFwiLCBcIi9qcy92ZW5kb3IvYWNlXCIpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgdGhlSWQgPSB0aGVFZGl0b3IuZ2V0QXR0cmlidXRlKCdpZCcpO1xuICAgICAgICAgICAgdmFyIGVkaXRvciA9IGFjZS5lZGl0KCB0aGVJZCApO1xuXG4gICAgICAgICAgICAvL2VkaXRvci5nZXRTZXNzaW9uKCkuc2V0VXNlV3JhcE1vZGUodHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwYWdlQ29udGFpbmVyID0gdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJDb25maWcucGFnZUNvbnRhaW5lciApO1xuICAgICAgICAgICAgdmFyIHRoZUhUTUwgPSBwYWdlQ29udGFpbmVyLmlubmVySFRNTDtcbiAgICAgICAgICAgIFxuXG4gICAgICAgICAgICBlZGl0b3Iuc2V0VmFsdWUoIHRoZUhUTUwgKTtcbiAgICAgICAgICAgIGVkaXRvci5zZXRUaGVtZShcImFjZS90aGVtZS90d2lsaWdodFwiKTtcbiAgICAgICAgICAgIGVkaXRvci5nZXRTZXNzaW9uKCkuc2V0TW9kZShcImFjZS9tb2RlL2h0bWxcIik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBibG9jayA9IHRoaXM7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZWRpdG9yLmdldFNlc3Npb24oKS5vbihcImNoYW5nZUFubm90YXRpb25cIiwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBibG9jay5hbm5vdCA9IGVkaXRvci5nZXRTZXNzaW9uKCkuZ2V0QW5ub3RhdGlvbnMoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBjbGVhclRpbWVvdXQoYmxvY2suYW5ub3RUaW1lb3V0KTtcblxuICAgICAgICAgICAgICAgIHZhciB0aW1lb3V0Q291bnQ7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoICQoJyNkaXZfZXJyb3JEcmF3ZXIgcCcpLnNpemUoKSA9PT0gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgdGltZW91dENvdW50ID0gYkNvbmZpZy5zb3VyY2VDb2RlRWRpdFN5bnRheERlbGF5O1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRpbWVvdXRDb3VudCA9IDEwMDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgYmxvY2suYW5ub3RUaW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIGZvciAodmFyIGtleSBpbiBibG9jay5hbm5vdCl7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGJsb2NrLmFubm90Lmhhc093blByb3BlcnR5KGtleSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKCBibG9jay5hbm5vdFtrZXldLnRleHQgIT09IFwiU3RhcnQgdGFnIHNlZW4gd2l0aG91dCBzZWVpbmcgYSBkb2N0eXBlIGZpcnN0LiBFeHBlY3RlZCBlLmcuIDwhRE9DVFlQRSBodG1sPi5cIiApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld0xpbmUgPSAkKCc8cD48L3A+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdLZXkgPSAkKCc8Yj4nK2Jsb2NrLmFubm90W2tleV0udHlwZSsnOiA8L2I+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdJbmZvID0gJCgnPHNwYW4+ICcrYmxvY2suYW5ub3Rba2V5XS50ZXh0ICsgXCJvbiBsaW5lIFwiICsgXCIgPGI+XCIgKyBibG9jay5hbm5vdFtrZXldLnJvdysnPC9iPjwvc3Bhbj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3TGluZS5hcHBlbmQoIG5ld0tleSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdMaW5lLmFwcGVuZCggbmV3SW5mbyApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2Rpdl9lcnJvckRyYXdlcicpLmFwcGVuZCggbmV3TGluZSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggJCgnI2Rpdl9lcnJvckRyYXdlcicpLmNzcygnZGlzcGxheScpID09PSAnbm9uZScgJiYgJCgnI2Rpdl9lcnJvckRyYXdlcicpLmZpbmQoJ3AnKS5zaXplKCkgPiAwICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2Rpdl9lcnJvckRyYXdlcicpLnNsaWRlRG93bigpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9LCB0aW1lb3V0Q291bnQpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9idXR0b25zXG4gICAgICAgICAgICB2YXIgY2FuY2VsQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnQlVUVE9OJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bicpO1xuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bi1kYW5nZXInKTtcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdlZGl0Q2FuY2VsQnV0dG9uJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuLXNtJyk7XG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZnVpLWNyb3NzXCI+PC9pPiA8c3Bhbj5DYW5jZWw8L3NwYW4+JztcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHNhdmVCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdCVVRUT04nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdidG4nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuLXByaW1hcnknKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uY2xhc3NMaXN0LmFkZCgnZWRpdFNhdmVCdXR0b24nKTtcbiAgICAgICAgICAgIHNhdmVCdXR0b24uY2xhc3NMaXN0LmFkZCgnYnRuLXNtJyk7XG4gICAgICAgICAgICBzYXZlQnV0dG9uLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZ1aS1jaGVja1wiPjwvaT4gPHNwYW4+U2F2ZTwvc3Bhbj4nO1xuICAgICAgICAgICAgc2F2ZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGJ1dHRvbldyYXBwZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdESVYnKTtcbiAgICAgICAgICAgIGJ1dHRvbldyYXBwZXIuY2xhc3NMaXN0LmFkZCgnZWRpdG9yQnV0dG9ucycpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBidXR0b25XcmFwcGVyLmFwcGVuZENoaWxkKCBjYW5jZWxCdXR0b24gKTtcbiAgICAgICAgICAgIGJ1dHRvbldyYXBwZXIuYXBwZW5kQ2hpbGQoIHNhdmVCdXR0b24gKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5hcHBlbmRDaGlsZCggYnV0dG9uV3JhcHBlciApO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBidWlsZGVyVUkuYWNlRWRpdG9yc1sgdGhlSWQgXSA9IGVkaXRvcjtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjYW5jZWxzIHRoZSBibG9jayBzb3VyY2UgY29kZSBlZGl0b3JcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jYW5jZWxTb3VyY2VCbG9jayA9IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAvL2VuYWJsZSBkcmFnZ2FibGUgb24gdGhlIExJXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkucGFyZW50Tm9kZSkuc29ydGFibGUoJ2VuYWJsZScpO1xuXHRcdFxuICAgICAgICAgICAgLy9kZWxldGUgdGhlIGVycm9yRHJhd2VyXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkubmV4dFNpYmxpbmcpLnJlbW92ZSgpO1xuICAgICAgICBcbiAgICAgICAgICAgIC8vZGVsZXRlIHRoZSBlZGl0b3JcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmFjZUVkaXRvcicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgJCh0aGlzLmZyYW1lKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5lZGl0b3JCdXR0b25zJykpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGRhdGVzIHRoZSBibG9ja3Mgc291cmNlIGNvZGVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zYXZlU291cmNlQmxvY2sgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9lbmFibGUgZHJhZ2dhYmxlIG9uIHRoZSBMSVxuICAgICAgICAgICAgJCh0aGlzLnBhcmVudExJLnBhcmVudE5vZGUpLnNvcnRhYmxlKCdlbmFibGUnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHRoZUlkID0gdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuYWNlRWRpdG9yJykuZ2V0QXR0cmlidXRlKCdpZCcpO1xuICAgICAgICAgICAgdmFyIHRoZUNvbnRlbnQgPSBidWlsZGVyVUkuYWNlRWRpdG9yc1t0aGVJZF0uZ2V0VmFsdWUoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kZWxldGUgdGhlIGVycm9yRHJhd2VyXG4gICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZGl2X2Vycm9yRHJhd2VyJykucGFyZW50Tm9kZS5yZW1vdmUoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kZWxldGUgdGhlIGVkaXRvclxuICAgICAgICAgICAgdGhpcy5wYXJlbnRMSS5xdWVyeVNlbGVjdG9yKCcuYWNlRWRpdG9yJykucmVtb3ZlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vdXBkYXRlIHRoZSBmcmFtZSdzIGNvbnRlbnRcbiAgICAgICAgICAgIHRoaXMuZnJhbWVEb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5pbm5lckhUTUwgPSB0aGVDb250ZW50O1xuICAgICAgICAgICAgdGhpcy5mcmFtZS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9zYW5kYm94ZWQ/XG4gICAgICAgICAgICBpZiggdGhpcy5zYW5kYm94ICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBzYW5kYm94RnJhbWUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggdGhpcy5zYW5kYm94ICk7XG4gICAgICAgICAgICAgICAgdmFyIHNhbmRib3hGcmFtZURvY3VtZW50ID0gc2FuZGJveEZyYW1lLmNvbnRlbnREb2N1bWVudCB8fCBzYW5kYm94RnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudDtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBidWlsZGVyVUkudGVtcEZyYW1lID0gc2FuZGJveEZyYW1lO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHNhbmRib3hGcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJDb25maWcucGFnZUNvbnRhaW5lciApLmlubmVySFRNTCA9IHRoZUNvbnRlbnQ7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZG8gd2UgbmVlZCB0byBleGVjdXRlIGEgbG9hZGVyIGZ1bmN0aW9uP1xuICAgICAgICAgICAgICAgIGlmKCB0aGlzLnNhbmRib3hfbG9hZGVyICE9PSAnJyApIHtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIC8qXG4gICAgICAgICAgICAgICAgICAgIHZhciBjb2RlVG9FeGVjdXRlID0gXCJzYW5kYm94RnJhbWUuY29udGVudFdpbmRvdy5cIit0aGlzLnNhbmRib3hfbG9hZGVyK1wiKClcIjtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRtcEZ1bmMgPSBuZXcgRnVuY3Rpb24oY29kZVRvRXhlY3V0ZSk7XG4gICAgICAgICAgICAgICAgICAgIHRtcEZ1bmMoKTtcbiAgICAgICAgICAgICAgICAgICAgKi9cbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmVkaXRvckJ1dHRvbnMnKSkuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2FkanVzdCBoZWlnaHQgb2YgdGhlIGZyYW1lXG4gICAgICAgICAgICB0aGlzLmhlaWdodEFkanVzdG1lbnQoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9uZXcgcGFnZSBhZGRlZCwgd2UndmUgZ290IHBlbmRpbmcgY2hhbmdlc1xuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9ibG9jayBoYXMgY2hhbmdlZFxuICAgICAgICAgICAgdGhpcy5zdGF0dXMgPSAnY2hhbmdlZCc7XG5cbiAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrQ2hhbmdlJywgdGhpcywgJ2NoYW5nZScpO1xuICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tMb2FkZWQnLCB0aGlzKTtcblxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjbGVhcnMgb3V0IHRoZSBlcnJvciBkcmF3ZXJcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5jbGVhckVycm9yRHJhd2VyID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBwcyA9IHRoaXMucGFyZW50TEkubmV4dFNpYmxpbmcucXVlcnlTZWxlY3RvckFsbCgncCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBmb3IoIHZhciBpID0gMDsgaSA8IHBzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgIHBzW2ldLnJlbW92ZSgpOyAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgfTtcbiAgICAgICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgdG9nZ2xlcyB0aGUgdmlzaWJpbGl0eSBvZiB0aGlzIGJsb2NrJ3MgZnJhbWVDb3ZlclxuICAgICAgICAqL1xuICAgICAgICB0aGlzLnRvZ2dsZUNvdmVyID0gZnVuY3Rpb24ob25Pck9mZikge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggb25Pck9mZiA9PT0gJ09uJyApIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLnBhcmVudExJLnF1ZXJ5U2VsZWN0b3IoJy5mcmFtZUNvdmVyJykuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9IGVsc2UgaWYoIG9uT3JPZmYgPT09ICdPZmYnICkge1xuICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMucGFyZW50TEkucXVlcnlTZWxlY3RvcignLmZyYW1lQ292ZXInKS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH07XG4gICAgICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHJldHVybnMgdGhlIGZ1bGwgc291cmNlIGNvZGUgb2YgdGhlIGJsb2NrJ3MgZnJhbWVcbiAgICAgICAgKi9cbiAgICAgICAgdGhpcy5nZXRTb3VyY2UgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHNvdXJjZSA9IFwiPGh0bWw+XCI7XG4gICAgICAgICAgICBzb3VyY2UgKz0gdGhpcy5mcmFtZURvY3VtZW50LmhlYWQub3V0ZXJIVE1MO1xuICAgICAgICAgICAgc291cmNlICs9IHRoaXMuZnJhbWVEb2N1bWVudC5ib2R5Lm91dGVySFRNTDtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgcmV0dXJuIHNvdXJjZTtcbiAgICAgICAgICAgIFxuICAgICAgICB9O1xuICAgICAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBwbGFjZXMgYSBkcmFnZ2VkL2Ryb3BwZWQgYmxvY2sgZnJvbSB0aGUgbGVmdCBzaWRlYmFyIG9udG8gdGhlIGNhbnZhc1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLnBsYWNlT25DYW52YXMgPSBmdW5jdGlvbih1aSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2ZyYW1lIGRhdGEsIHdlJ2xsIG5lZWQgdGhpcyBiZWZvcmUgbWVzc2luZyB3aXRoIHRoZSBpdGVtJ3MgY29udGVudCBIVE1MXG4gICAgICAgICAgICB2YXIgZnJhbWVEYXRhID0ge30sIGF0dHI7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5zaXplKCkgPiAwICkgey8vaWZyYW1lIHRodW1ibmFpbFxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuc3JjID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdzcmMnKTtcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX29yaWdpbmFsX3VybCA9IHVpLml0ZW0uZmluZCgnaWZyYW1lJykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgZnJhbWVEYXRhLmZyYW1lc19oZWlnaHQgPSB1aS5pdGVtLmhlaWdodCgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL3NhbmRib3hlZCBibG9jaz9cbiAgICAgICAgICAgICAgICBhdHRyID0gdWkuaXRlbS5maW5kKCdpZnJhbWUnKS5hdHRyKCdzYW5kYm94Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgYXR0ciAhPT0gdHlwZW9mIHVuZGVmaW5lZCAmJiBhdHRyICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNhbmRib3ggPSBzaXRlQnVpbGRlclV0aWxzLmdldFJhbmRvbUFyYml0cmFyeSgxMDAwMCwgMTAwMDAwMDAwMCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2FuZGJveF9sb2FkZXIgPSB1aS5pdGVtLmZpbmQoJ2lmcmFtZScpLmF0dHIoJ2RhdGEtbG9hZGVyZnVuY3Rpb24nKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9IGVsc2Ugey8vaW1hZ2UgdGh1bWJuYWlsXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5zcmMgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtc3JjYycpO1xuICAgICAgICAgICAgICAgIGZyYW1lRGF0YS5mcmFtZXNfb3JpZ2luYWxfdXJsID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLXNyY2MnKTtcbiAgICAgICAgICAgICAgICBmcmFtZURhdGEuZnJhbWVzX2hlaWdodCA9IHVpLml0ZW0uZmluZCgnaW1nJykuYXR0cignZGF0YS1oZWlnaHQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vc2FuZGJveGVkIGJsb2NrP1xuICAgICAgICAgICAgICAgIGF0dHIgPSB1aS5pdGVtLmZpbmQoJ2ltZycpLmF0dHIoJ2RhdGEtc2FuZGJveCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGF0dHIgIT09IHR5cGVvZiB1bmRlZmluZWQgJiYgYXR0ciAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zYW5kYm94ID0gc2l0ZUJ1aWxkZXJVdGlscy5nZXRSYW5kb21BcmJpdHJhcnkoMTAwMDAsIDEwMDAwMDAwMDApO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNhbmRib3hfbG9hZGVyID0gdWkuaXRlbS5maW5kKCdpbWcnKS5hdHRyKCdkYXRhLWxvYWRlcmZ1bmN0aW9uJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9jcmVhdGUgdGhlIG5ldyBibG9jayBvYmplY3RcbiAgICAgICAgICAgIHRoaXMuZnJhbWVJRCA9IDA7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJID0gdWkuaXRlbS5nZXQoMCk7XG4gICAgICAgICAgICB0aGlzLnBhcmVudExJLmlubmVySFRNTCA9ICcnO1xuICAgICAgICAgICAgdGhpcy5zdGF0dXMgPSAnbmV3JztcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlRnJhbWUoZnJhbWVEYXRhKTtcbiAgICAgICAgICAgIHRoaXMucGFyZW50TEkuc3R5bGUuaGVpZ2h0ID0gdGhpcy5mcmFtZUhlaWdodCtcInB4XCI7XG4gICAgICAgICAgICB0aGlzLmNyZWF0ZUZyYW1lQ292ZXIoKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHRoaXMuZnJhbWUuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIHRoaXMpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9pbnNlcnQgdGhlIGNyZWF0ZWQgaWZyYW1lXG4gICAgICAgICAgICB1aS5pdGVtLmFwcGVuZCgkKHRoaXMuZnJhbWUpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vYWRkIHRoZSBibG9jayB0byB0aGUgY3VycmVudCBwYWdlXG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UuYmxvY2tzLnNwbGljZSh1aS5pdGVtLmluZGV4KCksIDAsIHRoaXMpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9jdXN0b20gZXZlbnRcbiAgICAgICAgICAgIHVpLml0ZW0uZmluZCgnaWZyYW1lJykudHJpZ2dlcignY2FudmFzdXBkYXRlZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZHJvcHBlZCBlbGVtZW50LCBzbyB3ZSd2ZSBnb3QgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICBzaXRlLnNldFBlbmRpbmdDaGFuZ2VzKHRydWUpO1xuICAgICAgICAgICAgXG4gICAgICAgIH07XG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIGluamVjdHMgZXh0ZXJuYWwgSlMgKGRlZmluZWQgaW4gY29uZmlnLmpzKSBpbnRvIHRoZSBibG9ja1xuICAgICAgICAqL1xuICAgICAgICB0aGlzLmxvYWRKYXZhc2NyaXB0ID0gZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB2YXIgaSxcbiAgICAgICAgICAgICAgICBvbGQsXG4gICAgICAgICAgICAgICAgbmV3U2NyaXB0O1xuXG4gICAgICAgICAgICAvL3JlbW92ZSBvbGQgb25lc1xuICAgICAgICAgICAgb2xkID0gdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ3NjcmlwdC5idWlsZGVyJyk7XG5cbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgb2xkLmxlbmd0aDsgaSsrICkgb2xkW2ldLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICAvL2luamVjdFxuICAgICAgICAgICAgZm9yICggaSA9IDA7IGkgPCBiQ29uZmlnLmV4dGVybmFsSlMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgbmV3U2NyaXB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnU0NSSVBUJyk7XG4gICAgICAgICAgICAgICAgbmV3U2NyaXB0LmNsYXNzTGlzdC5hZGQoJ2J1aWxkZXInKTtcbiAgICAgICAgICAgICAgICBuZXdTY3JpcHQuc3JjID0gYkNvbmZpZy5leHRlcm5hbEpTW2ldO1xuXG4gICAgICAgICAgICAgICAgdGhpcy5mcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2JvZHknKS5hcHBlbmRDaGlsZChuZXdTY3JpcHQpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfTtcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBDaGVja3MgaWYgdGhpcyBibG9jayBoYXMgZXh0ZXJuYWwgc3R5bGVzaGVldFxuICAgICAgICAqL1xuICAgICAgICB0aGlzLmhhc0V4dGVybmFsQ1NTID0gZnVuY3Rpb24gKHNyYykge1xuXG4gICAgICAgICAgICB2YXIgZXh0ZXJuYWxDc3MsXG4gICAgICAgICAgICAgICAgeDtcblxuICAgICAgICAgICAgZXh0ZXJuYWxDc3MgPSB0aGlzLmZyYW1lRG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnbGlua1tocmVmKj1cIicgKyBzcmMgKyAnXCJdJyk7XG5cbiAgICAgICAgICAgIHJldHVybiBleHRlcm5hbENzcy5sZW5ndGggIT09IDA7XG5cbiAgICAgICAgfTtcbiAgICAgICAgXG4gICAgfVxuICAgIFxuICAgIEJsb2NrLnByb3RvdHlwZS5oYW5kbGVFdmVudCA9IGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgIHN3aXRjaCAoZXZlbnQudHlwZSkge1xuICAgICAgICAgICAgY2FzZSBcImxvYWRcIjogXG4gICAgICAgICAgICAgICAgdGhpcy5zZXRGcmFtZURvY3VtZW50KCk7XG4gICAgICAgICAgICAgICAgdGhpcy5oZWlnaHRBZGp1c3RtZW50KCk7XG4gICAgICAgICAgICAgICAgdGhpcy5sb2FkSmF2YXNjcmlwdCgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQodGhpcy5mcmFtZUNvdmVyKS5yZW1vdmVDbGFzcygnZnJlc2gnLCA1MDApO1xuXG4gICAgICAgICAgICAgICAgcHVibGlzaGVyLnB1Ymxpc2goJ29uQmxvY2tMb2FkZWQnLCB0aGlzKTtcblxuICAgICAgICAgICAgICAgIHRoaXMubG9hZGVkID0gdHJ1ZTtcblxuICAgICAgICAgICAgICAgIGJ1aWxkZXJVSS5jYW52YXNMb2FkaW5nKCdvZmYnKTtcblxuICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgY2FzZSBcImNsaWNrXCI6XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgdmFyIHRoZUJsb2NrID0gdGhpcztcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2ZpZ3VyZSBvdXQgd2hhdCB0byBkbyBuZXh0XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2RlbGV0ZUJsb2NrJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdkZWxldGVCbG9jaycpICkgey8vZGVsZXRlIHRoaXMgYmxvY2tcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlQmxvY2spLm1vZGFsKCdzaG93Jyk7ICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsRGVsZXRlQmxvY2spLm9mZignY2xpY2snLCAnI2RlbGV0ZUJsb2NrQ29uZmlybScpLm9uKCdjbGljaycsICcjZGVsZXRlQmxvY2tDb25maXJtJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoZUJsb2NrLmRlbGV0ZShldmVudCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbERlbGV0ZUJsb2NrKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdyZXNldEJsb2NrJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdyZXNldEJsb2NrJykgKSB7Ly9yZXNldCB0aGUgYmxvY2tcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoYnVpbGRlclVJLm1vZGFsUmVzZXRCbG9jaykubW9kYWwoJ3Nob3cnKTsgXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAkKGJ1aWxkZXJVSS5tb2RhbFJlc2V0QmxvY2spLm9mZignY2xpY2snLCAnI3Jlc2V0QmxvY2tDb25maXJtJykub24oJ2NsaWNrJywgJyNyZXNldEJsb2NrQ29uZmlybScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5yZXNldCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJChidWlsZGVyVUkubW9kYWxSZXNldEJsb2NrKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdodG1sQmxvY2snKSB8fCBldmVudC50YXJnZXQucGFyZW50Tm9kZS5jbGFzc0xpc3QuY29udGFpbnMoJ2h0bWxCbG9jaycpICkgey8vc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5zb3VyY2UoKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCBldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0Q2FuY2VsQnV0dG9uJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0Q2FuY2VsQnV0dG9uJykgKSB7Ly9jYW5jZWwgc291cmNlIGNvZGUgZWRpdG9yXG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVCbG9jay5jYW5jZWxTb3VyY2VCbG9jaygpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIGV2ZW50LnRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2VkaXRTYXZlQnV0dG9uJykgfHwgZXZlbnQudGFyZ2V0LnBhcmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdlZGl0U2F2ZUJ1dHRvbicpICkgey8vc2F2ZSBzb3VyY2UgY29kZVxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suc2F2ZVNvdXJjZUJsb2NrKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggZXZlbnQudGFyZ2V0LmNsYXNzTGlzdC5jb250YWlucygnYnV0dG9uX2NsZWFyRXJyb3JEcmF3ZXInKSApIHsvL2NsZWFyIGVycm9yIGRyYXdlclxuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlQmxvY2suY2xlYXJFcnJvckRyYXdlcigpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICB9O1xuXG5cbiAgICAvKlxuICAgICAgICBTaXRlIG9iamVjdCBsaXRlcmFsXG4gICAgKi9cbiAgICAvKmpzaGludCAtVzAwMyAqL1xuICAgIHZhciBzaXRlID0ge1xuICAgICAgICBcbiAgICAgICAgcGVuZGluZ0NoYW5nZXM6IGZhbHNlLCAgICAgIC8vcGVuZGluZyBjaGFuZ2VzIG9yIG5vP1xuICAgICAgICBwYWdlczoge30sICAgICAgICAgICAgICAgICAgLy9hcnJheSBjb250YWluaW5nIGFsbCBwYWdlcywgaW5jbHVkaW5nIHRoZSBjaGlsZCBmcmFtZXMsIGxvYWRlZCBmcm9tIHRoZSBzZXJ2ZXIgb24gcGFnZSBsb2FkXG4gICAgICAgIGlzX2FkbWluOiAwLCAgICAgICAgICAgICAgICAvLzAgZm9yIG5vbi1hZG1pbiwgMSBmb3IgYWRtaW5cbiAgICAgICAgZGF0YToge30sICAgICAgICAgICAgICAgICAgIC8vY29udGFpbmVyIGZvciBhamF4IGxvYWRlZCBzaXRlIGRhdGFcbiAgICAgICAgcGFnZXNUb0RlbGV0ZTogW10sICAgICAgICAgIC8vY29udGFpbnMgcGFnZXMgdG8gYmUgZGVsZXRlZFxuICAgICAgICAgICAgICAgIFxuICAgICAgICBzaXRlUGFnZXM6IFtdLCAgICAgICAgICAgICAgLy90aGlzIGlzIHRoZSBvbmx5IHZhciBjb250YWluaW5nIHRoZSByZWNlbnQgY2FudmFzIGNvbnRlbnRzXG4gICAgICAgIFxuICAgICAgICBzaXRlUGFnZXNSZWFkeUZvclNlcnZlcjoge30sICAgICAvL2NvbnRhaW5zIHRoZSBzaXRlIGRhdGEgcmVhZHkgdG8gYmUgc2VudCB0byB0aGUgc2VydmVyXG4gICAgICAgIFxuICAgICAgICBhY3RpdmVQYWdlOiB7fSwgICAgICAgICAgICAgLy9ob2xkcyBhIHJlZmVyZW5jZSB0byB0aGUgcGFnZSBjdXJyZW50bHkgb3BlbiBvbiB0aGUgY2FudmFzXG4gICAgICAgIFxuICAgICAgICBwYWdlVGl0bGU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlVGl0bGUnKSwvL2hvbGRzIHRoZSBwYWdlIHRpdGxlIG9mIHRoZSBjdXJyZW50IHBhZ2Ugb24gdGhlIGNhbnZhc1xuICAgICAgICBcbiAgICAgICAgZGl2Q2FudmFzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZUxpc3QnKSwvL0RJViBjb250YWluaW5nIGFsbCBwYWdlcyBvbiB0aGUgY2FudmFzXG4gICAgICAgIFxuICAgICAgICBwYWdlc01lbnU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlcycpLCAvL1VMIGNvbnRhaW5pbmcgdGhlIHBhZ2VzIG1lbnUgaW4gdGhlIHNpZGViYXJcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgYnV0dG9uTmV3UGFnZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2FkZFBhZ2UnKSxcbiAgICAgICAgbGlOZXdQYWdlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbmV3UGFnZUxJJyksXG4gICAgICAgIFxuICAgICAgICBpbnB1dFBhZ2VTZXR0aW5nc1RpdGxlOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZURhdGFfdGl0bGUnKSxcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NNZXRhRGVzY3JpcHRpb246IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9tZXRhRGVzY3JpcHRpb24nKSxcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NNZXRhS2V5d29yZHM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlRGF0YV9tZXRhS2V5d29yZHMnKSxcbiAgICAgICAgaW5wdXRQYWdlU2V0dGluZ3NJbmNsdWRlczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX2hlYWRlckluY2x1ZGVzJyksXG4gICAgICAgIGlucHV0UGFnZVNldHRpbmdzUGFnZUNzczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BhZ2VEYXRhX2hlYWRlckNzcycpLFxuICAgICAgICBcbiAgICAgICAgYnV0dG9uU3VibWl0UGFnZVNldHRpbmdzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGFnZVNldHRpbmdzU3VibWl0dEJ1dHRvbicpLFxuICAgICAgICBcbiAgICAgICAgbW9kYWxQYWdlU2V0dGluZ3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYWdlU2V0dGluZ3NNb2RhbCcpLFxuICAgICAgICBcbiAgICAgICAgYnV0dG9uU2F2ZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NhdmVQYWdlJyksXG4gICAgICAgIFxuICAgICAgICBtZXNzYWdlU3RhcnQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzdGFydCcpLFxuICAgICAgICBkaXZGcmFtZVdyYXBwZXI6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdmcmFtZVdyYXBwZXInKSxcbiAgICAgICAgXG4gICAgICAgIHNrZWxldG9uOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2tlbGV0b24nKSxcblx0XHRcblx0XHRhdXRvU2F2ZVRpbWVyOiB7fSxcbiAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkLmdldEpTT04oYXBwVUkuc2l0ZVVybCtcInNpdGVzL3NpdGVEYXRhXCIsIGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCBkYXRhLnNpdGUgIT09IHVuZGVmaW5lZCApIHtcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5kYXRhID0gZGF0YS5zaXRlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiggZGF0YS5wYWdlcyAhPT0gdW5kZWZpbmVkICkge1xuICAgICAgICAgICAgICAgICAgICBzaXRlLnBhZ2VzID0gZGF0YS5wYWdlcztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgc2l0ZS5pc19hZG1pbiA9IGRhdGEuaXNfYWRtaW47XG4gICAgICAgICAgICAgICAgXG5cdFx0XHRcdGlmKCAkKCcjcGFnZUxpc3QnKS5zaXplKCkgPiAwICkge1xuICAgICAgICAgICAgICAgIFx0YnVpbGRlclVJLnBvcHVsYXRlQ2FudmFzKCk7XG5cdFx0XHRcdH1cblxuICAgICAgICAgICAgICAgIGlmKCBkYXRhLnNpdGUudmlld21vZGUgKSB7XG4gICAgICAgICAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvblNldE1vZGUnLCBkYXRhLnNpdGUudmlld21vZGUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2ZpcmUgY3VzdG9tIGV2ZW50XG4gICAgICAgICAgICAgICAgJCgnYm9keScpLnRyaWdnZXIoJ3NpdGVEYXRhTG9hZGVkJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbk5ld1BhZ2UpLm9uKCdjbGljaycsIHNpdGUubmV3UGFnZSk7XG4gICAgICAgICAgICAkKHRoaXMubW9kYWxQYWdlU2V0dGluZ3MpLm9uKCdzaG93LmJzLm1vZGFsJywgc2l0ZS5sb2FkUGFnZVNldHRpbmdzKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25TdWJtaXRQYWdlU2V0dGluZ3MpLm9uKCdjbGljaycsIHNpdGUudXBkYXRlUGFnZVNldHRpbmdzKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25TYXZlKS5vbignY2xpY2snLCBmdW5jdGlvbigpe3NpdGUuc2F2ZSh0cnVlKTt9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9hdXRvIHNhdmUgdGltZSBcbiAgICAgICAgICAgIHRoaXMuYXV0b1NhdmVUaW1lciA9IHNldFRpbWVvdXQoc2l0ZS5hdXRvU2F2ZSwgYkNvbmZpZy5hdXRvU2F2ZVRpbWVvdXQpO1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIuc3Vic2NyaWJlKCdvbkJsb2NrQ2hhbmdlJywgZnVuY3Rpb24gKGJsb2NrLCB0eXBlKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoIGJsb2NrLmdsb2JhbCApIHtcblxuICAgICAgICAgICAgICAgICAgICBmb3IgKCB2YXIgaSA9IDA7IGkgPCBzaXRlLnNpdGVQYWdlcy5sZW5ndGg7IGkrKyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yICggdmFyIHkgPSAwOyB5IDwgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzLmxlbmd0aDsgeSArKyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICggc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldICE9PSBibG9jayAmJiBzaXRlLnNpdGVQYWdlc1tpXS5ibG9ja3NbeV0ub3JpZ2luYWxVcmwgPT09IGJsb2NrLm9yaWdpbmFsVXJsICYmIHNpdGUuc2l0ZVBhZ2VzW2ldLmJsb2Nrc1t5XS5nbG9iYWwgKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCB0eXBlID09PSAnY2hhbmdlJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldLmZyYW1lRG9jdW1lbnQuYm9keSA9IGJsb2NrLmZyYW1lRG9jdW1lbnQuYm9keS5jbG9uZU5vZGUodHJ1ZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHB1Ymxpc2hlci5wdWJsaXNoKCdvbkJsb2NrTG9hZGVkJywgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKCB0eXBlID09PSAncmVsb2FkJyApIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2l0ZS5zaXRlUGFnZXNbaV0uYmxvY2tzW3ldLnJlc2V0KGZhbHNlKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgYXV0b1NhdmU6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKHNpdGUucGVuZGluZ0NoYW5nZXMpIHtcbiAgICAgICAgICAgICAgICBzaXRlLnNhdmUoZmFsc2UpO1xuICAgICAgICAgICAgfVxuXHRcdFx0XG5cdFx0XHR3aW5kb3cuY2xlYXJJbnRlcnZhbCh0aGlzLmF1dG9TYXZlVGltZXIpO1xuICAgICAgICAgICAgdGhpcy5hdXRvU2F2ZVRpbWVyID0gc2V0VGltZW91dChzaXRlLmF1dG9TYXZlLCBiQ29uZmlnLmF1dG9TYXZlVGltZW91dCk7XG4gICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICAgICAgICAgIFxuICAgICAgICBzZXRQZW5kaW5nQ2hhbmdlczogZnVuY3Rpb24odmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgdGhpcy5wZW5kaW5nQ2hhbmdlcyA9IHZhbHVlO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggdmFsdWUgPT09IHRydWUgKSB7XG5cdFx0XHRcdFxuXHRcdFx0XHQvL3Jlc2V0IHRpbWVyXG5cdFx0XHRcdHdpbmRvdy5jbGVhckludGVydmFsKHRoaXMuYXV0b1NhdmVUaW1lcik7XG4gICAgICAgICAgICBcdHRoaXMuYXV0b1NhdmVUaW1lciA9IHNldFRpbWVvdXQoc2l0ZS5hdXRvU2F2ZSwgYkNvbmZpZy5hdXRvU2F2ZVRpbWVvdXQpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQoJyNzYXZlUGFnZSAuYkxhYmVsJykudGV4dChcIlNhdmUgbm93ICghKVwiKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiggc2l0ZS5hY3RpdmVQYWdlLnN0YXR1cyAhPT0gJ25ldycgKSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5zdGF0dXMgPSAnY2hhbmdlZCc7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH1cblx0XHRcdFxuICAgICAgICAgICAgfSBlbHNlIHtcblx0XG4gICAgICAgICAgICAgICAgJCgnI3NhdmVQYWdlIC5iTGFiZWwnKS50ZXh0KFwiTm90aGluZyB0byBzYXZlXCIpO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICBzaXRlLnVwZGF0ZVBhZ2VTdGF0dXMoJycpO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgc2F2ZTogZnVuY3Rpb24oc2hvd0NvbmZpcm1Nb2RhbCkge1xuXG4gICAgICAgICAgICBwdWJsaXNoZXIucHVibGlzaCgnb25CZWZvcmVTYXZlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZmlyZSBjdXN0b20gZXZlbnRcbiAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdiZWZvcmVTYXZlJyk7XG5cbiAgICAgICAgICAgIC8vZGlzYWJsZSBidXR0b25cbiAgICAgICAgICAgICQoXCJhI3NhdmVQYWdlXCIpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcbiAgICAgICAgICAgIC8vcmVtb3ZlIG9sZCBhbGVydHNcbiAgICAgICAgICAgICQoJyNlcnJvck1vZGFsIC5tb2RhbC1ib2R5ID4gKiwgI3N1Y2Nlc3NNb2RhbCAubW9kYWwtYm9keSA+IConKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH0pO1xuXHRcbiAgICAgICAgICAgIHNpdGUucHJlcEZvclNhdmUoZmFsc2UpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgc2VydmVyRGF0YSA9IHt9O1xuICAgICAgICAgICAgc2VydmVyRGF0YS5wYWdlcyA9IHRoaXMuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXI7XG4gICAgICAgICAgICBpZiggdGhpcy5wYWdlc1RvRGVsZXRlLmxlbmd0aCA+IDAgKSB7XG4gICAgICAgICAgICAgICAgc2VydmVyRGF0YS50b0RlbGV0ZSA9IHRoaXMucGFnZXNUb0RlbGV0ZTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgc2VydmVyRGF0YS5zaXRlRGF0YSA9IHRoaXMuZGF0YTtcblxuICAgICAgICAgICAgLy9zdG9yZSBjdXJyZW50IHJlc3BvbnNpdmUgbW9kZSBhcyB3ZWxsXG4gICAgICAgICAgICBzZXJ2ZXJEYXRhLnNpdGVEYXRhLnJlc3BvbnNpdmVNb2RlID0gYnVpbGRlclVJLmN1cnJlbnRSZXNwb25zaXZlTW9kZTtcblxuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJzaXRlcy9zYXZlXCIsXG4gICAgICAgICAgICAgICAgdHlwZTogXCJQT1NUXCIsXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6IFwianNvblwiLFxuICAgICAgICAgICAgICAgIGRhdGE6IHNlcnZlckRhdGEsXG4gICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJlcyl7XG5cdFxuICAgICAgICAgICAgICAgIC8vZW5hYmxlIGJ1dHRvblxuICAgICAgICAgICAgICAgICQoXCJhI3NhdmVQYWdlXCIpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcbiAgICAgICAgICAgICAgICBpZiggcmVzLnJlc3BvbnNlQ29kZSA9PT0gMCApIHtcblx0XHRcdFxuICAgICAgICAgICAgICAgICAgICBpZiggc2hvd0NvbmZpcm1Nb2RhbCApIHtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZXJyb3JNb2RhbCAubW9kYWwtYm9keScpLmFwcGVuZCggJChyZXMucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2Vycm9yTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgfVxuXHRcdFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcmVzLnJlc3BvbnNlQ29kZSA9PT0gMSApIHtcblx0XHRcbiAgICAgICAgICAgICAgICAgICAgaWYoIHNob3dDb25maXJtTW9kYWwgKSB7XG5cdFx0XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjc3VjY2Vzc01vZGFsIC5tb2RhbC1ib2R5JykuYXBwZW5kKCAkKHJlcy5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjc3VjY2Vzc01vZGFsJykubW9kYWwoJ3Nob3cnKTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIH1cblx0XHRcdFxuXHRcdFx0XG4gICAgICAgICAgICAgICAgICAgIC8vbm8gbW9yZSBwZW5kaW5nIGNoYW5nZXNcbiAgICAgICAgICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyhmYWxzZSk7XG5cdFx0XHRcblxuICAgICAgICAgICAgICAgICAgICAvL3VwZGF0ZSByZXZpc2lvbnM/XG4gICAgICAgICAgICAgICAgICAgICQoJ2JvZHknKS50cmlnZ2VyKCdjaGFuZ2VQYWdlJyk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgcHJlcHMgdGhlIHNpdGUgZGF0YSBiZWZvcmUgc2VuZGluZyBpdCB0byB0aGUgc2VydmVyXG4gICAgICAgICovXG4gICAgICAgIHByZXBGb3JTYXZlOiBmdW5jdGlvbih0ZW1wbGF0ZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLnNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyID0ge307XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCB0ZW1wbGF0ZSApIHsvL3NhdmluZyB0ZW1wbGF0ZSwgb25seSB0aGUgYWN0aXZlUGFnZSBpcyBuZWVkZWRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB0aGlzLnNpdGVQYWdlc1JlYWR5Rm9yU2VydmVyW3RoaXMuYWN0aXZlUGFnZS5uYW1lXSA9IHRoaXMuYWN0aXZlUGFnZS5wcmVwRm9yU2F2ZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHRoaXMuYWN0aXZlUGFnZS5mdWxsUGFnZSgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgfSBlbHNlIHsvL3JlZ3VsYXIgc2F2ZVxuICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9maW5kIHRoZSBwYWdlcyB3aGljaCBuZWVkIHRvIGJlIHNlbmQgdG8gdGhlIHNlcnZlclxuICAgICAgICAgICAgICAgIGZvciggdmFyIGkgPSAwOyBpIDwgdGhpcy5zaXRlUGFnZXMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggdGhpcy5zaXRlUGFnZXNbaV0uc3RhdHVzICE9PSAnJyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNSZWFkeUZvclNlcnZlclt0aGlzLnNpdGVQYWdlc1tpXS5uYW1lXSA9IHRoaXMuc2l0ZVBhZ2VzW2ldLnByZXBGb3JTYXZlKCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHNldHMgYSBwYWdlIGFzIHRoZSBhY3RpdmUgb25lXG4gICAgICAgICovXG4gICAgICAgIHNldEFjdGl2ZTogZnVuY3Rpb24ocGFnZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3JlZmVyZW5jZSB0byB0aGUgYWN0aXZlIHBhZ2VcbiAgICAgICAgICAgIHRoaXMuYWN0aXZlUGFnZSA9IHBhZ2U7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vaGlkZSBvdGhlciBwYWdlc1xuICAgICAgICAgICAgZm9yKHZhciBpIGluIHRoaXMuc2l0ZVBhZ2VzKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zaXRlUGFnZXNbaV0ucGFyZW50VUwuc3R5bGUuZGlzcGxheSA9ICdub25lJzsgICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kaXNwbGF5IGFjdGl2ZSBvbmVcbiAgICAgICAgICAgIHRoaXMuYWN0aXZlUGFnZS5wYXJlbnRVTC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBkZS1hY3RpdmUgYWxsIHBhZ2UgbWVudSBpdGVtc1xuICAgICAgICAqL1xuICAgICAgICBkZUFjdGl2YXRlQWxsOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2VzID0gdGhpcy5wYWdlc01lbnUucXVlcnlTZWxlY3RvckFsbCgnbGknKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSA9IDA7IGkgPCBwYWdlcy5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICBwYWdlc1tpXS5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBhZGRzIGEgbmV3IHBhZ2UgdG8gdGhlIHNpdGVcbiAgICAgICAgKi9cbiAgICAgICAgbmV3UGFnZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuZGVBY3RpdmF0ZUFsbCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2NyZWF0ZSB0aGUgbmV3IHBhZ2UgaW5zdGFuY2VcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIHBhZ2VEYXRhID0gW107XG4gICAgICAgICAgICB2YXIgdGVtcCA9IHtcbiAgICAgICAgICAgICAgICBwYWdlc19pZDogMFxuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIHBhZ2VEYXRhWzBdID0gdGVtcDtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIG5ld1BhZ2VOYW1lID0gJ3BhZ2UnKyhzaXRlLnNpdGVQYWdlcy5sZW5ndGgrMSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBuZXdQYWdlID0gbmV3IFBhZ2UobmV3UGFnZU5hbWUsIHBhZ2VEYXRhLCBzaXRlLnNpdGVQYWdlcy5sZW5ndGgrMSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIG5ld1BhZ2Uuc3RhdHVzID0gJ25ldyc7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIG5ld1BhZ2Uuc2VsZWN0UGFnZSgpO1xuICAgICAgICAgICAgbmV3UGFnZS5lZGl0UGFnZU5hbWUoKTtcbiAgICAgICAgXG4gICAgICAgICAgICBuZXdQYWdlLmlzRW1wdHkoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5zZXRQZW5kaW5nQ2hhbmdlcyh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBjaGVja3MgaWYgdGhlIG5hbWUgb2YgYSBwYWdlIGlzIGFsbG93ZWRcbiAgICAgICAgKi9cbiAgICAgICAgY2hlY2tQYWdlTmFtZTogZnVuY3Rpb24ocGFnZU5hbWUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9tYWtlIHN1cmUgdGhlIG5hbWUgaXMgdW5pcXVlXG4gICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMuc2l0ZVBhZ2VzICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCB0aGlzLnNpdGVQYWdlc1tpXS5uYW1lID09PSBwYWdlTmFtZSAmJiB0aGlzLmFjdGl2ZVBhZ2UgIT09IHRoaXMuc2l0ZVBhZ2VzW2ldICkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnBhZ2VOYW1lRXJyb3IgPSBcIlRoZSBwYWdlIG5hbWUgbXVzdCBiZSB1bmlxdWUuXCI7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9ICAgXG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHJlbW92ZXMgdW5hbGxvd2VkIGNoYXJhY3RlcnMgZnJvbSB0aGUgcGFnZSBuYW1lXG4gICAgICAgICovXG4gICAgICAgIHByZXBQYWdlTmFtZTogZnVuY3Rpb24ocGFnZU5hbWUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgcGFnZU5hbWUgPSBwYWdlTmFtZS5yZXBsYWNlKCcgJywgJycpO1xuICAgICAgICAgICAgcGFnZU5hbWUgPSBwYWdlTmFtZS5yZXBsYWNlKC9bPyohLnwmIzskJUBcIjw+KCkrLF0vZywgXCJcIik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBwYWdlTmFtZTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzYXZlIHBhZ2Ugc2V0dGluZ3MgZm9yIHRoZSBjdXJyZW50IHBhZ2VcbiAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlUGFnZVNldHRpbmdzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy50aXRsZSA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NUaXRsZS52YWx1ZTtcbiAgICAgICAgICAgIHNpdGUuYWN0aXZlUGFnZS5wYWdlU2V0dGluZ3MubWV0YV9kZXNjcmlwdGlvbiA9IHNpdGUuaW5wdXRQYWdlU2V0dGluZ3NNZXRhRGVzY3JpcHRpb24udmFsdWU7XG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLm1ldGFfa2V5d29yZHMgPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzTWV0YUtleXdvcmRzLnZhbHVlO1xuICAgICAgICAgICAgc2l0ZS5hY3RpdmVQYWdlLnBhZ2VTZXR0aW5ncy5oZWFkZXJfaW5jbHVkZXMgPSBzaXRlLmlucHV0UGFnZVNldHRpbmdzSW5jbHVkZXMudmFsdWU7XG4gICAgICAgICAgICBzaXRlLmFjdGl2ZVBhZ2UucGFnZVNldHRpbmdzLnBhZ2VfY3NzID0gc2l0ZS5pbnB1dFBhZ2VTZXR0aW5nc1BhZ2VDc3MudmFsdWU7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIHNpdGUuc2V0UGVuZGluZ0NoYW5nZXModHJ1ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoc2l0ZS5tb2RhbFBhZ2VTZXR0aW5ncykubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICB1cGRhdGUgcGFnZSBzdGF0dXNlc1xuICAgICAgICAqL1xuICAgICAgICB1cGRhdGVQYWdlU3RhdHVzOiBmdW5jdGlvbihzdGF0dXMpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZm9yKCB2YXIgaSBpbiB0aGlzLnNpdGVQYWdlcyApIHtcbiAgICAgICAgICAgICAgICB0aGlzLnNpdGVQYWdlc1tpXS5zdGF0dXMgPSBzdGF0dXM7ICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcblxuXG4gICAgICAgIC8qXG4gICAgICAgICAgICBDaGVja3MgYWxsIHRoZSBibG9ja3MgaW4gdGhpcyBzaXRlIGhhdmUgZmluaXNoZWQgbG9hZGluZ1xuICAgICAgICAqL1xuICAgICAgICBsb2FkZWQ6IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgdmFyIGk7XG5cbiAgICAgICAgICAgIGZvciAoIGkgPSAwOyBpIDwgdGhpcy5zaXRlUGFnZXMubGVuZ3RoOyBpKysgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoICF0aGlzLnNpdGVQYWdlc1tpXS5sb2FkZWQoKSApIHJldHVybiBmYWxzZTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcblxuICAgICAgICB9LFxuXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIE1ha2UgZXZlcnkgYmxvY2sgaGF2ZSBhbiBvdmVybGF5IGR1cmluZyBkcmFnZ2luZyB0byBwcmV2ZW50IG1vdXNlIGV2ZW50IGlzc3Vlc1xuICAgICAgICAqL1xuICAgICAgICBtb3ZlTW9kZTogZnVuY3Rpb24gKHZhbHVlKSB7XG5cbiAgICAgICAgICAgIHZhciBpO1xuXG4gICAgICAgICAgICBmb3IgKCBpID0gMDsgaSA8IHRoaXMuYWN0aXZlUGFnZS5ibG9ja3MubGVuZ3RoOyBpKysgKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAoIHZhbHVlID09PSAnb24nICkgdGhpcy5hY3RpdmVQYWdlLmJsb2Nrc1tpXS5mcmFtZUNvdmVyLmNsYXNzTGlzdC5hZGQoJ21vdmUnKTtcbiAgICAgICAgICAgICAgICBlbHNlIGlmICggdmFsdWUgPT09ICdvZmYnICkgdGhpcy5hY3RpdmVQYWdlLmJsb2Nrc1tpXS5mcmFtZUNvdmVyLmNsYXNzTGlzdC5yZW1vdmUoJ21vdmUnKTtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG5cbiAgICAgICAgLypcbiAgICAgICAgICAgIEdldCBmb3JtIGZyb20gQUVNXG4gICAgICAgICovXG4gICAgICAgIHF1aWNrX2xvYWRfZm9ybTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZigkKCcjcGFnZUxpc3QgaWZyYW1lJykuY29udGVudHMoKS5maW5kKCcjdXNlcl9mb3JtX2Rpdl9yZW1vdmUnKS5sZW5ndGgpe1xuICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuZmluZCgnI3VzZXJfZm9ybV9kaXZfcmVtb3ZlJykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmKCQoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2JykubGVuZ3RoKXtcbiAgICAgICAgICAgICAgICBqUXVlcnkuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6IFwicG9zdFwiLFxuICAgICAgICAgICAgICAgICAgICB1cmw6IFwiL3NpdGVzL2ZldGNoRm9ybVwiLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAnZm9ybUlEJzpmb3JtX2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnanNvbicsXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3M6ZnVuY3Rpb24ocmVzdWx0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihyZXN1bHQudHlwZSA9PT0gJ3N1Y2Nlc3MnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGpoZWlnaHQgPSBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmhlaWdodCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpoZWlnaHQgKz0gMTAwO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScpLmNvbnRlbnRzKCkuZmluZCgnI3VzZXJfZm9ybV9kaXYnKS5odG1sKHJlc3VsdC5odG1sKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBqUXVlcnkoJyNwYWdlTGlzdCBpZnJhbWUnKS5jb250ZW50cygpLmZpbmQoJyN1c2VyX2Zvcm1fZGl2X3JlbW92ZScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGpRdWVyeSgnI3BhZ2VMaXN0IGlmcmFtZScsIHdpbmRvdy5wYXJlbnQuZG9jdW1lbnQpLmhlaWdodChqaGVpZ2h0KydweCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGVycm9yOiBmdW5jdGlvbihlcnJvclRocm93bil7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhlcnJvclRocm93bik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIFxuICAgIH07XG5cbiAgICBidWlsZGVyVUkuaW5pdCgpOyBzaXRlLmluaXQoKTtcblxuICAgIFxuICAgIC8vKioqKiBFWFBPUlRTXG4gICAgbW9kdWxlLmV4cG9ydHMuc2l0ZSA9IHNpdGU7XG4gICAgbW9kdWxlLmV4cG9ydHMuYnVpbGRlclVJID0gYnVpbGRlclVJO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuICAgICAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5wYWdlQ29udGFpbmVyID0gXCIjcGFnZVwiO1xuXG4gICAgbW9kdWxlLmV4cG9ydHMuYm9keVBhZGRpbmdDbGFzcyA9IFwiYlBhZGRpbmdcIjtcbiAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5lZGl0YWJsZUl0ZW1zID0ge1xuICAgICAgICAnc3Bhbi5mYSc6IFsnY29sb3InLCAnZm9udC1zaXplJ10sXG4gICAgICAgICcuYmcuYmcxJzogWydiYWNrZ3JvdW5kLWNvbG9yJ10sXG4gICAgICAgICduYXYgYSc6IFsnY29sb3InLCAnZm9udC13ZWlnaHQnLCAndGV4dC10cmFuc2Zvcm0nXSxcbiAgICAgICAgJ2ltZyc6IFsnYm9yZGVyLXRvcC1sZWZ0LXJhZGl1cycsICdib3JkZXItdG9wLXJpZ2h0LXJhZGl1cycsICdib3JkZXItYm90dG9tLWxlZnQtcmFkaXVzJywgJ2JvcmRlci1ib3R0b20tcmlnaHQtcmFkaXVzJywgJ2JvcmRlci1jb2xvcicsICdib3JkZXItc3R5bGUnLCAnYm9yZGVyLXdpZHRoJ10sXG4gICAgICAgICdoci5kYXNoZWQnOiBbJ2JvcmRlci1jb2xvcicsICdib3JkZXItd2lkdGgnXSxcbiAgICAgICAgJy5kaXZpZGVyID4gc3Bhbic6IFsnY29sb3InLCAnZm9udC1zaXplJ10sXG4gICAgICAgICdoci5zaGFkb3dEb3duJzogWydtYXJnaW4tdG9wJywgJ21hcmdpbi1ib3R0b20nXSxcbiAgICAgICAgJy5mb290ZXIgYSc6IFsnY29sb3InXSxcbiAgICAgICAgJy5zb2NpYWwgYSc6IFsnY29sb3InXSxcbiAgICAgICAgJy5iZy5iZzEsIC5iZy5iZzIsIC5oZWFkZXIxMCwgLmhlYWRlcjExJzogWydiYWNrZ3JvdW5kLWltYWdlJywgJ2JhY2tncm91bmQtY29sb3InXSxcbiAgICAgICAgJy5mcmFtZUNvdmVyJzogW10sXG4gICAgICAgICcuZWRpdENvbnRlbnQnOiBbJ2NvbnRlbnQnLCAnY29sb3InLCAnZm9udC1zaXplJywgJ2JhY2tncm91bmQtY29sb3InLCAnZm9udC1mYW1pbHknXSxcbiAgICAgICAgJ2EuYnRuLCBidXR0b24uYnRuJzogWydib3JkZXItcmFkaXVzJywgJ2ZvbnQtc2l6ZScsICdiYWNrZ3JvdW5kLWNvbG9yJ10sXG4gICAgICAgICcjcHJpY2luZ190YWJsZTIgLnByaWNpbmcyIC5ib3R0b20gbGknOiBbJ2NvbnRlbnQnXVxuICAgIH07XG4gICAgXG4gICAgbW9kdWxlLmV4cG9ydHMuZWRpdGFibGVJdGVtT3B0aW9ucyA9IHtcbiAgICAgICAgJ25hdiBhIDogZm9udC13ZWlnaHQnOiBbJzQwMCcsICc3MDAnXSxcbiAgICAgICAgJ2EuYnRuIDogYm9yZGVyLXJhZGl1cyc6IFsnMHB4JywgJzRweCcsICcxMHB4J10sXG4gICAgICAgICdpbWcgOiBib3JkZXItc3R5bGUnOiBbJ25vbmUnLCAnZG90dGVkJywgJ2Rhc2hlZCcsICdzb2xpZCddLFxuICAgICAgICAnaW1nIDogYm9yZGVyLXdpZHRoJzogWycxcHgnLCAnMnB4JywgJzNweCcsICc0cHgnXSxcbiAgICAgICAgJ2gxLCBoMiwgaDMsIGg0LCBoNSwgcCA6IGZvbnQtZmFtaWx5JzogWydkZWZhdWx0JywgJ0xhdG8nLCAnSGVsdmV0aWNhJywgJ0FyaWFsJywgJ1RpbWVzIE5ldyBSb21hbiddLFxuICAgICAgICAnaDIgOiBmb250LWZhbWlseSc6IFsnZGVmYXVsdCcsICdMYXRvJywgJ0hlbHZldGljYScsICdBcmlhbCcsICdUaW1lcyBOZXcgUm9tYW4nXSxcbiAgICAgICAgJ2gzIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ10sXG4gICAgICAgICdwIDogZm9udC1mYW1pbHknOiBbJ2RlZmF1bHQnLCAnTGF0bycsICdIZWx2ZXRpY2EnLCAnQXJpYWwnLCAnVGltZXMgTmV3IFJvbWFuJ11cbiAgICB9O1xuXG4gICAgbW9kdWxlLmV4cG9ydHMucmVzcG9uc2l2ZU1vZGVzID0ge1xuICAgICAgICBkZXNrdG9wOiAnOTclJyxcbiAgICAgICAgbW9iaWxlOiAnNDgwcHgnLFxuICAgICAgICB0YWJsZXQ6ICcxMDI0cHgnXG4gICAgfTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmVkaXRhYmxlQ29udGVudCA9IFsnLmVkaXRDb250ZW50JywgJy5uYXZiYXIgYScsICdidXR0b24nLCAnYS5idG4nLCAnLmZvb3RlciBhOm5vdCguZmEpJywgJy50YWJsZVdyYXBwZXInLCAnaDEnLCAnaDInXTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmF1dG9TYXZlVGltZW91dCA9IDMwMDAwMDtcbiAgICBcbiAgICBtb2R1bGUuZXhwb3J0cy5zb3VyY2VDb2RlRWRpdFN5bnRheERlbGF5ID0gMTAwMDA7XG5cbiAgICBtb2R1bGUuZXhwb3J0cy5tZWRpdW1Dc3NVcmxzID0gW1xuICAgICAgICAnLy9jZG4uanNkZWxpdnIubmV0L21lZGl1bS1lZGl0b3IvbGF0ZXN0L2Nzcy9tZWRpdW0tZWRpdG9yLm1pbi5jc3MnLFxuICAgICAgICAnL2Nzcy9tZWRpdW0tYm9vdHN0cmFwLmNzcydcbiAgICBdO1xuICAgIG1vZHVsZS5leHBvcnRzLm1lZGl1bUJ1dHRvbnMgPSBbJ2JvbGQnLCAnaXRhbGljJywgJ3VuZGVybGluZScsICdhbmNob3InLCAnb3JkZXJlZGxpc3QnLCAndW5vcmRlcmVkbGlzdCcsICdoMScsICdoMicsICdoMycsICdoNCcsICdyZW1vdmVGb3JtYXQnXTtcblxuICAgIG1vZHVsZS5leHBvcnRzLmV4dGVybmFsSlMgPSBbXG4gICAgICAgICdqcy9idWlsZGVyX2luX2Jsb2NrLmpzJ1xuICAgIF07XG4gICAgICAgICAgICAgICAgICAgIFxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgYkNvbmZpZyA9IHJlcXVpcmUoJy4vY29uZmlnLmpzJyk7XG5cdHZhciBzaXRlQnVpbGRlciA9IHJlcXVpcmUoJy4vYnVpbGRlci5qcycpO1xuXHR2YXIgYXBwVUkgPSByZXF1aXJlKCcuL3VpLmpzJykuYXBwVUk7XG5cblx0dmFyIHB1Ymxpc2ggPSB7XG4gICAgICAgIFxuICAgICAgICBidXR0b25QdWJsaXNoOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncHVibGlzaFBhZ2UnKSxcbiAgICAgICAgYnV0dG9uU2F2ZVBlbmRpbmdCZWZvcmVQdWJsaXNoaW5nOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYnV0dG9uU2F2ZVBlbmRpbmdCZWZvcmVQdWJsaXNoaW5nJyksXG4gICAgICAgIHB1Ymxpc2hNb2RhbDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3B1Ymxpc2hNb2RhbCcpLFxuICAgICAgICBidXR0b25QdWJsaXNoU3VibWl0OiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncHVibGlzaFN1Ym1pdCcpLFxuICAgICAgICBwdWJsaXNoQWN0aXZlOiAwLFxuICAgICAgICB0aGVJdGVtOiB7fSxcbiAgICAgICAgbW9kYWxTaXRlU2V0dGluZ3M6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzaXRlU2V0dGluZ3MnKSxcbiAgICBcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblB1Ymxpc2gpLm9uKCdjbGljaycsIHRoaXMubG9hZFB1Ymxpc2hNb2RhbCk7XG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uU2F2ZVBlbmRpbmdCZWZvcmVQdWJsaXNoaW5nKS5vbignY2xpY2snLCB0aGlzLnNhdmVCZWZvcmVQdWJsaXNoaW5nKTtcbiAgICAgICAgICAgICQodGhpcy5wdWJsaXNoTW9kYWwpLm9uKCdjaGFuZ2UnLCAnaW5wdXRbdHlwZT1jaGVja2JveF0nLCB0aGlzLnB1Ymxpc2hDaGVja2JveEV2ZW50KTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25QdWJsaXNoU3VibWl0KS5vbignY2xpY2snLCB0aGlzLnB1Ymxpc2hTaXRlKTtcbiAgICAgICAgICAgICQodGhpcy5tb2RhbFNpdGVTZXR0aW5ncykub24oJ2NsaWNrJywgJyNzaXRlU2V0dGluZ3NCcm93c2VGVFBCdXR0b24sIC5saW5rJywgdGhpcy5icm93c2VGVFApO1xuICAgICAgICAgICAgJCh0aGlzLm1vZGFsU2l0ZVNldHRpbmdzKS5vbignY2xpY2snLCAnI2Z0cExpc3RJdGVtcyAuY2xvc2UnLCB0aGlzLmNsb3NlRnRwQnJvd3Nlcik7XG4gICAgICAgICAgICAkKHRoaXMubW9kYWxTaXRlU2V0dGluZ3MpLm9uKCdjbGljaycsICcjc2l0ZVNldHRpbmdzVGVzdEZUUCcsIHRoaXMudGVzdEZUUENvbm5lY3Rpb24pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3Nob3cgdGhlIHB1Ymxpc2ggYnV0dG9uXG4gICAgICAgICAgICAkKHRoaXMuYnV0dG9uUHVibGlzaCkuc2hvdygpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2xpc3RlbiB0byBzaXRlIHNldHRpbmdzIGxvYWQgZXZlbnRcbiAgICAgICAgICAgICQoJ2JvZHknKS5vbignc2l0ZVNldHRpbmdzTG9hZCcsIHRoaXMuc2hvd1B1Ymxpc2hTZXR0aW5ncyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vcHVibGlzaCBoYXNoP1xuICAgICAgICAgICAgaWYoIHdpbmRvdy5sb2NhdGlvbi5oYXNoID09PSBcIiNwdWJsaXNoXCIgKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzLmJ1dHRvblB1Ymxpc2gpLmNsaWNrKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIGhlYWRlciB0b29sdGlwc1xuICAgICAgICAgICAgLy9pZiggdGhpcy5idXR0b25QdWJsaXNoLmhhc0F0dHJpYnV0ZSgnZGF0YS10b2dnbGUnKSAmJiB0aGlzLmJ1dHRvblB1Ymxpc2guZ2V0QXR0cmlidXRlKCdkYXRhLXRvZ2dsZScpID09ICd0b29sdGlwJyApIHtcbiAgICAgICAgICAgIC8vICAgJCh0aGlzLmJ1dHRvblB1Ymxpc2gpLnRvb2x0aXAoJ3Nob3cnKTtcbiAgICAgICAgICAgIC8vICAgc2V0VGltZW91dChmdW5jdGlvbigpeyQodGhpcy5idXR0b25QdWJsaXNoKS50b29sdGlwKCdoaWRlJyl9LCA1MDAwKTtcbiAgICAgICAgICAgIC8vfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGxvYWRzIHRoZSBwdWJsaXNoIG1vZGFsXG4gICAgICAgICovXG4gICAgICAgIGxvYWRQdWJsaXNoTW9kYWw6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggcHVibGlzaC5wdWJsaXNoQWN0aXZlID09PSAwICkgey8vY2hlY2sgaWYgd2UncmUgY3VycmVudGx5IHB1Ymxpc2hpbmcgYW55dGhpbmdcblx0XHRcbiAgICAgICAgICAgICAgICAvL2hpZGUgYWxlcnRzXG4gICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCAubW9kYWwtYWxlcnRzID4gKicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIC5tb2RhbC1ib2R5ID4gLmFsZXJ0LXN1Y2Nlc3MnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9oaWRlIGxvYWRlcnNcbiAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsX2Fzc2V0cyAucHVibGlzaGluZycpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuZmluZCgnLndvcmtpbmcnKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuZmluZCgnLmRvbmUnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9yZW1vdmUgcHVibGlzaGVkIGNsYXNzIGZyb20gYXNzZXQgY2hlY2tib3hlc1xuICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWxfYXNzZXRzIGlucHV0JykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUNsYXNzKCdwdWJsaXNoZWQnKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2RvIHdlIGhhdmUgcGVuZGluZyBjaGFuZ2VzP1xuICAgICAgICAgICAgICAgIGlmKCBzaXRlQnVpbGRlci5zaXRlLnBlbmRpbmdDaGFuZ2VzID09PSB0cnVlICkgey8vd2UndmUgZ290IGNoYW5nZXMsIHNhdmUgZmlyc3RcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgI3B1Ymxpc2hQZW5kaW5nQ2hhbmdlc01lc3NhZ2UnKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgLm1vZGFsLWJvZHktY29udGVudCcpLmhpZGUoKTtcblx0XHRcbiAgICAgICAgICAgICAgICB9IGVsc2Ugey8vYWxsIHNldCwgZ2V0IG9uIGl0IHdpdGggcHVibGlzaGluZ1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9nZXQgdGhlIGNvcnJlY3QgcGFnZXMgaW4gdGhlIFBhZ2VzIHNlY3Rpb24gb2YgdGhlIHB1Ymxpc2ggbW9kYWxcbiAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbF9wYWdlcyB0Ym9keSA+IConKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjcGFnZXMgbGk6dmlzaWJsZScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHRoZVBhZ2UgPSAkKHRoaXMpLmZpbmQoJ2E6Zmlyc3QnKS50ZXh0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGhlUm93ID0gJCgnPHRyPjx0ZCBjbGFzcz1cInRleHQtY2VudGVyXCIgc3R5bGU9XCJ3aWR0aDogMzBweDtcIj48bGFiZWwgY2xhc3M9XCJjaGVja2JveCBuby1sYWJlbFwiPjxpbnB1dCB0eXBlPVwiY2hlY2tib3hcIiB2YWx1ZT1cIicrdGhlUGFnZSsnXCIgaWQ9XCJcIiBkYXRhLXR5cGU9XCJwYWdlXCIgbmFtZT1cInBhZ2VzW11cIiBkYXRhLXRvZ2dsZT1cImNoZWNrYm94XCI+PC9sYWJlbD48L3RkPjx0ZD4nK3RoZVBhZ2UrJzxzcGFuIGNsYXNzPVwicHVibGlzaGluZ1wiPjxzcGFuIGNsYXNzPVwid29ya2luZ1wiPlB1Ymxpc2hpbmcuLi4gPGltZyBzcmM9XCInK2FwcFVJLmJhc2VVcmwrJ2ltYWdlcy9wdWJsaXNoTG9hZGVyLmdpZlwiPjwvc3Bhbj48c3BhbiBjbGFzcz1cImRvbmUgdGV4dC1wcmltYXJ5XCI+UHVibGlzaGVkICZuYnNwOzxzcGFuIGNsYXNzPVwiZnVpLWNoZWNrXCI+PC9zcGFuPjwvc3Bhbj48L3NwYW4+PC90ZD48L3RyPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2NoZWNrYm94aWZ5XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVSb3cuZmluZCgnaW5wdXQnKS5yYWRpb2NoZWNrKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVSb3cuZmluZCgnaW5wdXQnKS5vbignY2hlY2sgdW5jaGVjayB0b2dnbGUnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgndHInKVskKHRoaXMpLnByb3AoJ2NoZWNrZWQnKSA/ICdhZGRDbGFzcycgOiAncmVtb3ZlQ2xhc3MnXSgnc2VsZWN0ZWQtcm93Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbF9wYWdlcyB0Ym9keScpLmFwcGVuZCggdGhlUm93ICk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgI3B1Ymxpc2hQZW5kaW5nQ2hhbmdlc01lc3NhZ2UnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgLm1vZGFsLWJvZHktY29udGVudCcpLnNob3coKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vZW5hYmxlL2Rpc2FibGUgcHVibGlzaCBidXR0b25cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgdmFyIGFjdGl2YXRlQnV0dG9uID0gZmFsc2U7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgaW5wdXRbdHlwZT1jaGVja2JveF0nKS5lYWNoKGZ1bmN0aW9uKCl7XG5cdFx0XHRcbiAgICAgICAgICAgICAgICBpZiggJCh0aGlzKS5wcm9wKCdjaGVja2VkJykgKSB7XG4gICAgICAgICAgICAgICAgICAgIGFjdGl2YXRlQnV0dG9uID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggYWN0aXZhdGVCdXR0b24gKSB7XG4gICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hTdWJtaXQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hTdWJtaXQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgc2F2ZXMgcGVuZGluZyBjaGFuZ2VzIGJlZm9yZSBwdWJsaXNoaW5nXG4gICAgICAgICovXG4gICAgICAgIHNhdmVCZWZvcmVQdWJsaXNoaW5nOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCAjcHVibGlzaFBlbmRpbmdDaGFuZ2VzTWVzc2FnZScpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgLmxvYWRlcicpLnNob3coKTtcbiAgICAgICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cbiAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUucHJlcEZvclNhdmUoZmFsc2UpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgc2VydmVyRGF0YSA9IHt9O1xuICAgICAgICAgICAgc2VydmVyRGF0YS5wYWdlcyA9IHNpdGVCdWlsZGVyLnNpdGUuc2l0ZVBhZ2VzUmVhZHlGb3JTZXJ2ZXI7XG4gICAgICAgICAgICBpZiggc2l0ZUJ1aWxkZXIuc2l0ZS5wYWdlc1RvRGVsZXRlLmxlbmd0aCA+IDAgKSB7XG4gICAgICAgICAgICAgICAgc2VydmVyRGF0YS50b0RlbGV0ZSA9IHNpdGVCdWlsZGVyLnNpdGUucGFnZXNUb0RlbGV0ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHNlcnZlckRhdGEuc2l0ZURhdGEgPSBzaXRlQnVpbGRlci5zaXRlLmRhdGE7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvc2F2ZS8xXCIsXG4gICAgICAgICAgICAgICAgdHlwZTogXCJQT1NUXCIsXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6IFwianNvblwiLFxuICAgICAgICAgICAgICAgIGRhdGE6IHNlcnZlckRhdGEsXG4gICAgICAgICAgICB9KS5kb25lKGZ1bmN0aW9uKHJlcyl7XHRcdFx0XG5cdFx0XHRcdFx0XHRcbiAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIC5sb2FkZXInKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWwgLm1vZGFsLWFsZXJ0cycpLmFwcGVuZCggJChyZXMucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9zZWxmLWRlc3RydWN0IHN1Y2Nlc3MgbWVzc2FnZXNcbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpeyQoJyNwdWJsaXNoTW9kYWwgLm1vZGFsLWFsZXJ0cyAuYWxlcnQtc3VjY2VzcycpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpeyQodGhpcykucmVtb3ZlKCk7fSk7fSwgMjUwMCk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25cbiAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCAjcHVibGlzaFBlbmRpbmdDaGFuZ2VzTWVzc2FnZSAuYnRuLnNhdmUnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9KTtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgaWYoIHJlcy5yZXNwb25zZUNvZGUgPT09IDEgKSB7Ly9jaGFuZ2VzIHdlcmUgc2F2ZWQgd2l0aG91dCBpc3N1ZXNcblxuICAgICAgICAgICAgICAgICAgICAvL25vIG1vcmUgcGVuZGluZyBjaGFuZ2VzXG4gICAgICAgICAgICAgICAgICAgIHNpdGVCdWlsZGVyLnNpdGUuc2V0UGVuZGluZ0NoYW5nZXMoZmFsc2UpO1xuXHRcdFx0XHRcbiAgICAgICAgICAgICAgICAgICAgLy9nZXQgdGhlIGNvcnJlY3QgcGFnZXMgaW4gdGhlIFBhZ2VzIHNlY3Rpb24gb2YgdGhlIHB1Ymxpc2ggbW9kYWxcbiAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbF9wYWdlcyB0Ym9keSA+IConKS5yZW1vdmUoKTtcblxuICAgICAgICAgICAgICAgICAgICAkKCcjcGFnZXMgbGk6dmlzaWJsZScpLmVhY2goZnVuY3Rpb24oKXtcblx0XHRcdFx0XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGhlUGFnZSA9ICQodGhpcykuZmluZCgnYTpmaXJzdCcpLnRleHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0aGVSb3cgPSAkKCc8dHI+PHRkIGNsYXNzPVwidGV4dC1jZW50ZXJcIiBzdHlsZT1cIndpZHRoOiAwcHg7XCI+PGxhYmVsIGNsYXNzPVwiY2hlY2tib3hcIj48aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgdmFsdWU9XCInK3RoZVBhZ2UrJ1wiIGlkPVwiXCIgZGF0YS10eXBlPVwicGFnZVwiIG5hbWU9XCJwYWdlc1tdXCIgZGF0YS10b2dnbGU9XCJjaGVja2JveFwiPjwvbGFiZWw+PC90ZD48dGQ+Jyt0aGVQYWdlKyc8c3BhbiBjbGFzcz1cInB1Ymxpc2hpbmdcIj48c3BhbiBjbGFzcz1cIndvcmtpbmdcIj5QdWJsaXNoaW5nLi4uIDxpbWcgc3JjPVwiJythcHBVSS5iYXNlVXJsKydpbWFnZXMvcHVibGlzaExvYWRlci5naWZcIj48L3NwYW4+PHNwYW4gY2xhc3M9XCJkb25lIHRleHQtcHJpbWFyeVwiPlB1Ymxpc2hlZCAmbmJzcDs8c3BhbiBjbGFzcz1cImZ1aS1jaGVja1wiPjwvc3Bhbj48L3NwYW4+PC9zcGFuPjwvdGQ+PC90cj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9jaGVja2JveGlmeVxuICAgICAgICAgICAgICAgICAgICAgICAgdGhlUm93LmZpbmQoJ2lucHV0JykucmFkaW9jaGVjaygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhlUm93LmZpbmQoJ2lucHV0Jykub24oJ2NoZWNrIHVuY2hlY2sgdG9nZ2xlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmNsb3Nlc3QoJ3RyJylbJCh0aGlzKS5wcm9wKCdjaGVja2VkJykgPyAnYWRkQ2xhc3MnIDogJ3JlbW92ZUNsYXNzJ10oJ3NlbGVjdGVkLXJvdycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoTW9kYWxfcGFnZXMgdGJvZHknKS5hcHBlbmQoIHRoZVJvdyApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAvL3Nob3cgY29udGVudFxuICAgICAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIC5tb2RhbC1ib2R5LWNvbnRlbnQnKS5mYWRlSW4oNTAwKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGV2ZW50IGhhbmRsZXIgZm9yIHRoZSBjaGVja2JveGVzIGluc2lkZSB0aGUgcHVibGlzaCBtb2RhbFxuICAgICAgICAqL1xuICAgICAgICBwdWJsaXNoQ2hlY2tib3hFdmVudDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBhY3RpdmF0ZUJ1dHRvbiA9IGZhbHNlO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIGlucHV0W3R5cGU9Y2hlY2tib3hdJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLnByb3AoJ2NoZWNrZWQnKSApIHtcbiAgICAgICAgICAgICAgICAgICAgYWN0aXZhdGVCdXR0b24gPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIGFjdGl2YXRlQnV0dG9uICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQoJyNwdWJsaXNoU3VibWl0JykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hTdWJtaXQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIHB1Ymxpc2hlcyB0aGUgc2VsZWN0ZWQgaXRlbXNcbiAgICAgICAgKi9cbiAgICAgICAgcHVibGlzaFNpdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL3RyYWNrIHRoZSBwdWJsaXNoaW5nIHN0YXRlXG4gICAgICAgICAgICBwdWJsaXNoLnB1Ymxpc2hBY3RpdmUgPSAxO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2Rpc2FibGUgYnV0dG9uXG4gICAgICAgICAgICAkKCcjcHVibGlzaFN1Ym1pdCwgI3B1Ymxpc2hDYW5jZWwnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcbiAgICAgICAgICAgIC8vcmVtb3ZlIGV4aXN0aW5nIGFsZXJ0c1xuICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCAubW9kYWwtYWxlcnRzID4gKicpLnJlbW92ZSgpO1xuXHRcdFxuICAgICAgICAgICAgLy9wcmVwYXJlIHN0dWZmXG4gICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIGZvcm0gaW5wdXRbdHlwZT1cImhpZGRlblwiXS5wYWdlJykucmVtb3ZlKCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vbG9vcCB0aHJvdWdoIGFsbCBwYWdlc1xuICAgICAgICAgICAgJCgnI3BhZ2VMaXN0ID4gdWwnKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgLy9leHBvcnQgdGhpcyBwYWdlP1xuICAgICAgICAgICAgICAgIGlmKCAkKCcjcHVibGlzaE1vZGFsICNwdWJsaXNoTW9kYWxfcGFnZXMgaW5wdXQ6ZXEoJysoJCh0aGlzKS5pbmRleCgpKzEpKycpJykucHJvcCgnY2hlY2tlZCcpICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9ncmFiIHRoZSBza2VsZXRvbiBtYXJrdXBcbiAgICAgICAgICAgICAgICAgICAgdmFyIG5ld0RvY01haW5QYXJlbnQgPSAkKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9lbXB0eSBvdXQgdGhlIHNrZWxldG9uXG4gICAgICAgICAgICAgICAgICAgIG5ld0RvY01haW5QYXJlbnQuZmluZCgnKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgLy9sb29wIHRocm91Z2ggcGFnZSBpZnJhbWVzIGFuZCBncmFiIHRoZSBib2R5IHN0dWZmXG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuZmluZCgnaWZyYW1lJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgYXR0ciA9ICQodGhpcykuYXR0cignZGF0YS1zYW5kYm94Jyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0aGVDb250ZW50cztcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhlQ29udGVudHMgPSAkKCcjc2FuZGJveGVzICMnK2F0dHIpLmNvbnRlbnRzKCkuZmluZCggYkNvbmZpZy5wYWdlQ29udGFpbmVyICk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUNvbnRlbnRzID0gJCh0aGlzKS5jb250ZW50cygpLmZpbmQoIGJDb25maWcucGFnZUNvbnRhaW5lciApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCcuZnJhbWVDb3ZlcicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vcmVtb3ZlIGlubGluZSBzdHlsaW5nIGxlZnRvdmVyc1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9yKCB2YXIga2V5IGluIGJDb25maWcuZWRpdGFibGVJdGVtcyApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGVDb250ZW50cy5maW5kKCBrZXkgKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZUF0dHIoJ2RhdGEtc2VsZWN0b3InKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKCAkKHRoaXMpLmF0dHIoJ3N0eWxlJykgPT09ICcnICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cdFxuXHRcdFx0XHRcdFxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBiQ29uZmlnLmVkaXRhYmxlQ29udGVudC5sZW5ndGg7ICsraSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLmVkaXRhYmxlQ29udGVudFtpXSApLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5yZW1vdmVBdHRyKCdkYXRhLXNlbGVjdG9yJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHRvQWRkID0gdGhlQ29udGVudHMuaHRtbCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2dyYWIgc2NyaXB0c1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2NyaXB0cyA9ICQodGhpcykuY29udGVudHMoKS5maW5kKCBiQ29uZmlnLnBhZ2VDb250YWluZXIgKS5maW5kKCdzY3JpcHQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIHNjcmlwdHMuc2l6ZSgpID4gMCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGhlSWZyYW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJza2VsZXRvblwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHRzLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2NyaXB0O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoICQodGhpcykudGV4dCgpICE9PSAnJyApIHsvL3NjcmlwdCB0YWdzIHdpdGggY29udGVudFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQgPSB0aGVJZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic2NyaXB0XCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0LnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC5pbm5lckhUTUwgPSAkKHRoaXMpLnRleHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBiQ29uZmlnLnBhZ2VDb250YWluZXIuc3Vic3RyaW5nKDEpICkuYXBwZW5kQ2hpbGQoc2NyaXB0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggJCh0aGlzKS5hdHRyKCdzcmMnKSAhPT0gbnVsbCApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NyaXB0ID0gdGhlSWZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNjcmlwdFwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjcmlwdC50eXBlID0gJ3RleHQvamF2YXNjcmlwdCc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY3JpcHQuc3JjID0gJCh0aGlzKS5hdHRyKCdzcmMnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoZUlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBiQ29uZmlnLnBhZ2VDb250YWluZXIuc3Vic3RyaW5nKDEpICkuYXBwZW5kQ2hpbGQoc2NyaXB0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld0RvY01haW5QYXJlbnQuYXBwZW5kKCAkKHRvQWRkKSApO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB2YXIgbmV3SW5wdXQgPSAkKCc8aW5wdXQgdHlwZT1cImhpZGRlblwiIGNsYXNzPVwicGFnZVwiIG5hbWU9XCJ4cGFnZXNbJyskKCcjcGFnZXMgbGk6ZXEoJysoJCh0aGlzKS5pbmRleCgpKzEpKycpIGE6Zmlyc3QnKS50ZXh0KCkrJ11cIiB2YWx1ZT1cIlwiPicpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hNb2RhbCBmb3JtJykucHJlcGVuZCggbmV3SW5wdXQgKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIG5ld0lucHV0LnZhbCggXCI8aHRtbD5cIiskKCdpZnJhbWUjc2tlbGV0b24nKS5jb250ZW50cygpLmZpbmQoJ2h0bWwnKS5odG1sKCkrXCI8L2h0bWw+XCIgKTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBwdWJsaXNoLnB1Ymxpc2hBc3NldCgpO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBwdWJsaXNoQXNzZXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB2YXIgdG9QdWJsaXNoID0gJCgnI3B1Ymxpc2hNb2RhbF9hc3NldHMgaW5wdXRbdHlwZT1jaGVja2JveF06Y2hlY2tlZDpub3QoLnB1Ymxpc2hlZCwgLnRvZ2dsZUFsbCksICNwdWJsaXNoTW9kYWxfcGFnZXMgaW5wdXRbdHlwZT1jaGVja2JveF06Y2hlY2tlZDpub3QoLnB1Ymxpc2hlZCwgLnRvZ2dsZUFsbCknKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaWYoIHRvUHVibGlzaC5zaXplKCkgPiAwICkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHB1Ymxpc2gudGhlSXRlbSA9IHRvUHVibGlzaC5maXJzdCgpO1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vZGlzcGxheSB0aGUgYXNzZXQgbG9hZGVyXG4gICAgICAgICAgICAgICAgcHVibGlzaC50aGVJdGVtLmNsb3Nlc3QoJ3RkJykubmV4dCgpLmZpbmQoJy5wdWJsaXNoaW5nJykuZmFkZUluKDUwMCk7XG5cbiAgICAgICAgICAgICAgICB2YXIgdGhlRGF0YTtcblx0XHRcbiAgICAgICAgICAgICAgICBpZiggcHVibGlzaC50aGVJdGVtLmF0dHIoJ2RhdGEtdHlwZScpID09PSAncGFnZScgKSB7XG4gICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICB0aGVEYXRhID0ge3NpdGVJRDogJCgnZm9ybSNwdWJsaXNoRm9ybSBpbnB1dFtuYW1lPXNpdGVJRF0nKS52YWwoKSwgaXRlbTogcHVibGlzaC50aGVJdGVtLnZhbCgpLCBwYWdlQ29udGVudDogJCgnZm9ybSNwdWJsaXNoRm9ybSBpbnB1dFtuYW1lPVwieHBhZ2VzWycrcHVibGlzaC50aGVJdGVtLnZhbCgpKyddXCJdJykudmFsKCl9O1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcHVibGlzaC50aGVJdGVtLmF0dHIoJ2RhdGEtdHlwZScpID09PSAnYXNzZXQnICkge1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdGhlRGF0YSA9IHtzaXRlSUQ6ICQoJ2Zvcm0jcHVibGlzaEZvcm0gaW5wdXRbbmFtZT1zaXRlSURdJykudmFsKCksIGl0ZW06IHB1Ymxpc2gudGhlSXRlbS52YWwoKX07XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybDogJCgnZm9ybSNwdWJsaXNoRm9ybScpLmF0dHIoJ2FjdGlvbicpK1wiL1wiK3B1Ymxpc2gudGhlSXRlbS5hdHRyKCdkYXRhLXR5cGUnKSxcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bvc3QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiB0aGVEYXRhLFxuICAgICAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nXG4gICAgICAgICAgICAgICAgfSkuZG9uZShmdW5jdGlvbihyZXQpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgaWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDAgKSB7Ly9mYXRhbCBlcnJvciwgcHVibGlzaGluZyB3aWxsIHN0b3BcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9oaWRlIGluZGljYXRvcnNcbiAgICAgICAgICAgICAgICAgICAgICAgIHB1Ymxpc2gudGhlSXRlbS5jbG9zZXN0KCd0ZCcpLm5leHQoKS5maW5kKCcud29ya2luZycpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgLy9lbmFibGUgYnV0dG9uc1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hTdWJtaXQsICNwdWJsaXNoQ2FuY2VsJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIC5tb2RhbC1hbGVydHMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL25vIGlzc3Vlc1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICAvL3Nob3cgZG9uZVxuICAgICAgICAgICAgICAgICAgICAgICAgcHVibGlzaC50aGVJdGVtLmNsb3Nlc3QoJ3RkJykubmV4dCgpLmZpbmQoJy53b3JraW5nJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcHVibGlzaC50aGVJdGVtLmNsb3Nlc3QoJ3RkJykubmV4dCgpLmZpbmQoJy5kb25lJykuZmFkZUluKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBwdWJsaXNoLnRoZUl0ZW0uYWRkQ2xhc3MoJ3B1Ymxpc2hlZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICAgICBwdWJsaXNoLnB1Ymxpc2hBc3NldCgpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIC8vcHVibGlzaGluZyBpcyBkb25lXG4gICAgICAgICAgICAgICAgcHVibGlzaC5wdWJsaXNoQWN0aXZlID0gMDtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvL2VuYWJsZSBidXR0b25zXG4gICAgICAgICAgICAgICAgJCgnI3B1Ymxpc2hTdWJtaXQsICNwdWJsaXNoQ2FuY2VsJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XG4gICAgICAgICAgICAgICAgLy9zaG93IG1lc3NhZ2VcbiAgICAgICAgICAgICAgICAkKCcjcHVibGlzaE1vZGFsIC5tb2RhbC1ib2R5ID4gLmFsZXJ0LXN1Y2Nlc3MnKS5mYWRlSW4oNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7JCgnI3B1Ymxpc2hNb2RhbCAubW9kYWwtYm9keSA+IC5hbGVydC1zdWNjZXNzJykuZmFkZU91dCg1MDApO30sIDI1MDApO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIHNob3dQdWJsaXNoU2V0dGluZ3M6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCcjc2l0ZVNldHRpbmdzUHVibGlzaGluZycpLnNob3coKTtcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYnJvd3NlIHRoZSBGVFAgY29ubmVjdGlvblxuICAgICAgICAqL1xuICAgICAgICBicm93c2VGVFA6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9nb3QgYWxsIHdlIG5lZWQ/XG4gICAgXHRcdFxuICAgIFx0XHRpZiggJCgnI3NpdGVTZXR0aW5nc19mdHBTZXJ2ZXInKS52YWwoKSA9PT0gJycgfHwgJCgnI3NpdGVTZXR0aW5nc19mdHBVc2VyJykudmFsKCkgPT09ICcnIHx8ICQoJyNzaXRlU2V0dGluZ3NfZnRwUGFzc3dvcmQnKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgYWxlcnQoJ1BsZWFzZSBtYWtlIHN1cmUgYWxsIEZUUCBjb25uZWN0aW9uIGRldGFpbHMgYXJlIHByZXNlbnQnKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgXHRcdFxuICAgICAgICAgICAgLy9jaGVjayBpZiB0aGlzIGlzIGEgZGVlcGVyIGxldmVsIGxpbmtcbiAgICBcdFx0aWYoICQodGhpcykuaGFzQ2xhc3MoJ2xpbmsnKSApIHtcbiAgICBcdFx0XHRcbiAgICBcdFx0XHRpZiggJCh0aGlzKS5oYXNDbGFzcygnYmFjaycpICkge1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdFx0JCgnI3NpdGVTZXR0aW5nc19mdHBQYXRoJykudmFsKCAkKHRoaXMpLmF0dHIoJ2hyZWYnKSApO1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdH0gZWxzZSB7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XHQvL2lmIHNvLCB3ZSdsbCBjaGFuZ2UgdGhlIHBhdGggYmVmb3JlIGNvbm5lY3RpbmdcbiAgICBcdFx0XHRcbiAgICBcdFx0XHRcdGlmKCAkKCcjc2l0ZVNldHRpbmdzX2Z0cFBhdGgnKS52YWwoKS5zdWJzdHIoICQoJyNzaXRlU2V0dGluZ3NfZnRwUGF0aCcpLnZhbC5sZW5ndGggLSAxICkgPT09ICcvJyApIHsvL3ByZXBlbmQgXCIvXCJcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzX2Z0cFBhdGgnKS52YWwoICQoJyNzaXRlU2V0dGluZ3NfZnRwUGF0aCcpLnZhbCgpKyQodGhpcykuYXR0cignaHJlZicpICk7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XHR9IGVsc2Uge1xuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdCQoJyNzaXRlU2V0dGluZ3NfZnRwUGF0aCcpLnZhbCggJCgnI3NpdGVTZXR0aW5nc19mdHBQYXRoJykudmFsKCkrXCIvXCIrJCh0aGlzKS5hdHRyKCdocmVmJykgKTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0fVxuICAgIFx0XHRcdFxuICAgIFx0XHRcdH1cbiAgICBcdFx0XHRcbiAgICBcdFx0XHRcbiAgICBcdFx0fVxuICAgIFx0XHRcbiAgICBcdFx0Ly9kZXN0cm95IGFsbCBhbGVydHNcbiAgICBcdFx0XG4gICAgXHRcdCQoJyNmdHBBbGVydHMgLmFsZXJ0JykuZmFkZU91dCg1MDAsIGZ1bmN0aW9uKCl7XG4gICAgXHRcdFx0JCh0aGlzKS5yZW1vdmUoKTtcbiAgICBcdFx0fSk7XG4gICAgXHRcdFxuICAgIFx0XHQvL2Rpc2FibGUgYnV0dG9uXG4gICAgXHRcdCQodGhpcykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFxuICAgIFx0XHQvL3JlbW92ZSBleGlzdGluZyBsaW5rc1xuICAgIFx0XHQkKCcjZnRwTGlzdEl0ZW1zID4gKicpLnJlbW92ZSgpO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9zaG93IGZ0cCBzZWN0aW9uXG4gICAgXHRcdCQoJyNmdHBCcm93c2UgLmxvYWRlckZ0cCcpLnNob3coKTtcbiAgICBcdFx0JCgnI2Z0cEJyb3dzZScpLnNsaWRlRG93big1MDApO1xuXG4gICAgXHRcdHZhciB0aGVCdXR0b24gPSAkKHRoaXMpO1xuICAgIFx0XHRcbiAgICBcdFx0JC5hamF4KHtcbiAgICAgICAgICAgICAgICB1cmw6IGFwcFVJLnNpdGVVcmwrXCJmdHBjb25uZWN0aW9uL2Nvbm5lY3RcIixcbiAgICBcdFx0XHR0eXBlOiAncG9zdCcsXG4gICAgXHRcdFx0ZGF0YVR5cGU6ICdqc29uJyxcbiAgICBcdFx0XHRkYXRhOiAkKCdmb3JtI3NpdGVTZXR0aW5nc0Zvcm0nKS5zZXJpYWxpemVBcnJheSgpXG4gICAgXHRcdH0pLmRvbmUoZnVuY3Rpb24ocmV0KXtcbiAgICBcdFx0XG4gICAgXHRcdFx0Ly9lbmFibGUgYnV0dG9uXG4gICAgXHRcdFx0dGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdC8vaGlkZSBsb2FkaW5nXG4gICAgXHRcdFx0JCgnI2Z0cEJyb3dzZSAubG9hZGVyRnRwJykuaGlkZSgpO1xuICAgIFx0XHRcbiAgICBcdFx0XHRpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMCApIHsvL2Vycm9yXG4gICAgXHRcdFx0XHQkKCcjZnRwQWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG4gICAgXHRcdFx0fSBlbHNlIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vYWxsIGdvb2RcbiAgICBcdFx0XHRcdCQoJyNmdHBMaXN0SXRlbXMnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcbiAgICBcdFx0XHR9XG4gICAgXHRcdFxuICAgIFx0XHR9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBoaWRlcy9jbG9zZXMgdGhlIEZUUCBicm93c2VyXG4gICAgICAgICovXG4gICAgICAgIGNsb3NlRnRwQnJvd3NlcjogZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgXHRcdCQodGhpcykuY2xvc2VzdCgnI2Z0cEJyb3dzZScpLnNsaWRlVXAoNTAwKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgICAvKlxuICAgICAgICAgICAgdGVzdHMgdGhlIEZUUCBjb25uZWN0aW9uIHdpdGggdGhlIHByb3ZpZGVkIGRldGFpbHNcbiAgICAgICAgKi9cbiAgICAgICAgdGVzdEZUUENvbm5lY3Rpb246IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvL2dvdCBhbGwgd2UgbmVlZD9cbiAgICBcdFx0aWYoICQoJyNzaXRlU2V0dGluZ3NfZnRwU2VydmVyJykudmFsKCkgPT09ICcnIHx8ICQoJyNzaXRlU2V0dGluZ3NfZnRwVXNlcicpLnZhbCgpID09PSAnJyB8fCAkKCcjc2l0ZVNldHRpbmdzX2Z0cFBhc3N3b3JkJykudmFsKCkgPT09ICcnICkge1xuICAgICAgICAgICAgICAgIGFsZXJ0KCdQbGVhc2UgbWFrZSBzdXJlIGFsbCBGVFAgY29ubmVjdGlvbiBkZXRhaWxzIGFyZSBwcmVzZW50Jyk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgIFx0XHRcbiAgICBcdFx0Ly9kZXN0cm95IGFsbCBhbGVydHNcbiAgICAgICAgICAgICQoJyNmdHBUZXN0QWxlcnRzIC5hbGVydCcpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICBcdFx0XG4gICAgXHRcdC8vZGlzYWJsZSBidXR0b25cbiAgICBcdFx0JCh0aGlzKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcbiAgICBcdFx0XG4gICAgXHRcdC8vc2hvdyBsb2FkaW5nIGluZGljYXRvclxuICAgIFx0XHQkKHRoaXMpLm5leHQoKS5mYWRlSW4oNTAwKTtcbiAgICBcdFx0XG4gICAgICAgICAgICB2YXIgdGhlQnV0dG9uID0gJCh0aGlzKTtcbiAgICBcdFx0XG4gICAgXHRcdCQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wiZnRwY29ubmVjdGlvbi90ZXN0XCIsXG4gICAgXHRcdFx0dHlwZTogJ3Bvc3QnLFxuICAgIFx0XHRcdGRhdGFUeXBlOiAnanNvbicsXG4gICAgXHRcdFx0ZGF0YTogJCgnZm9ybSNzaXRlU2V0dGluZ3NGb3JtJykuc2VyaWFsaXplQXJyYXkoKVxuICAgIFx0XHR9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgXHRcdCAgICBcdFx0XG4gICAgXHRcdFx0Ly9lbmFibGUgYnV0dG9uXG4gICAgXHRcdFx0dGhlQnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIHRoZUJ1dHRvbi5uZXh0KCkuZmFkZU91dCg1MDApO1xuICAgIFx0XHRcdCAgICBcdFx0XG4gICAgXHRcdFx0aWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDAgKSB7Ly9lcnJvclxuICAgICAgICAgICAgICAgICAgICAkKCcjZnRwVGVzdEFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL2FsbCBnb29kXG4gICAgICAgICAgICAgICAgICAgICQoJyNmdHBUZXN0QWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgfVxuICAgIFx0XHRcbiAgICBcdFx0fSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuICAgIFxuICAgIHB1Ymxpc2guaW5pdCgpO1xuXG59KCkpOyIsIihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXG5cdHZhciBhcHBVSSA9IHJlcXVpcmUoJy4vdWkuanMnKS5hcHBVSTtcblxuXHR2YXIgc2l0ZXMgPSB7XG4gICAgICAgIFxuICAgICAgICB3cmFwcGVyU2l0ZXM6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzaXRlcycpLFxuICAgICAgICBzZWxlY3RVc2VyOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlckRyb3BEb3duJyksXG4gICAgICAgIHNlbGVjdFNvcnQ6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzb3J0RHJvcERvd24nKSxcbiAgICAgICAgYnV0dG9uRGVsZXRlU2l0ZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RlbGV0ZVNpdGVCdXR0b24nKSxcblx0XHRidXR0b25zRGVsZXRlU2l0ZTogZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmRlbGV0ZVNpdGVCdXR0b24nKSxcbiAgICAgICAgXG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLmNyZWF0ZVRodW1ibmFpbHMoKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLnNlbGVjdFVzZXIpLm9uKCdjaGFuZ2UnLCB0aGlzLmZpbHRlclVzZXIpO1xuICAgICAgICAgICAgJCh0aGlzLnNlbGVjdFNvcnQpLm9uKCdjaGFuZ2UnLCB0aGlzLmNoYW5nZVNvcnRpbmcpO1xuICAgICAgICAgICAgJCh0aGlzLmJ1dHRvbnNEZWxldGVTaXRlKS5vbignY2xpY2snLCB0aGlzLmRlbGV0ZVNpdGUpO1xuXHRcdFx0JCh0aGlzLmJ1dHRvbkRlbGV0ZVNpdGUpLm9uKCdjbGljaycsIHRoaXMuZGVsZXRlU2l0ZSk7XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgYXBwbGllcyB6b29tZXIgdG8gY3JlYXRlIHRoZSBpZnJhbWUgdGh1Ym1uYWlsc1xuICAgICAgICAqL1xuICAgICAgICBjcmVhdGVUaHVtYm5haWxzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgJCh0aGlzLndyYXBwZXJTaXRlcykuZmluZCgnaWZyYW1lJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciB0aGVIZWlnaHQgPSAkKHRoaXMpLmF0dHIoJ2RhdGEtaGVpZ2h0JykqMC4yNTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnpvb21lcih7XG4gICAgICAgICAgICAgICAgICAgIHpvb206IDAuMjUsXG4gICAgICAgICAgICAgICAgICAgIGhlaWdodDogdGhlSGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogJCh0aGlzKS5wYXJlbnQoKS53aWR0aCgpLFxuICAgICAgICAgICAgICAgICAgICBtZXNzYWdlOiBcIlwiLFxuICAgICAgICAgICAgICAgICAgICBtZXNzYWdlVVJMOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvXCIrJCh0aGlzKS5hdHRyKCdkYXRhLXNpdGVpZCcpXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jbG9zZXN0KCcuc2l0ZScpLmZpbmQoJy56b29tZXItY292ZXIgPiBhJykuYXR0cigndGFyZ2V0JywgJycpO1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH0sXG4gICAgICAgIFxuICAgICAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGZpbHRlcnMgdGhlIHNpdGUgbGlzdCBieSBzZWxlY3RlZCB1c2VyXG4gICAgICAgICovXG4gICAgICAgIGZpbHRlclVzZXI6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXG4gICAgICAgICAgICBpZiggJCh0aGlzKS52YWwoKSA9PT0gJ0FsbCcgfHwgJCh0aGlzKS52YWwoKSA9PT0gJycgKSB7XG4gICAgICAgICAgICAgICAgJCgnI3NpdGVzIC5zaXRlJykuaGlkZSgpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkKCcjc2l0ZXMgLnNpdGUnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgJCgnI3NpdGVzJykuZmluZCgnW2RhdGEtbmFtZT1cIicrJCh0aGlzKS52YWwoKSsnXCJdJykuZmFkZUluKDUwMCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgY2huYWdlcyB0aGUgc29ydGluZyBvbiB0aGUgc2l0ZSBsaXN0XG4gICAgICAgICovXG4gICAgICAgIGNoYW5nZVNvcnRpbmc6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICB2YXIgc2l0ZXM7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIGlmKCAkKHRoaXMpLnZhbCgpID09PSAnTm9PZlBhZ2VzJyApIHtcblx0XHRcblx0XHRcdFx0c2l0ZXMgPSAkKCcjc2l0ZXMgLnNpdGUnKTtcblx0XHRcdFxuXHRcdFx0XHRzaXRlcy5zb3J0KCBmdW5jdGlvbihhLGIpe1xuICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgdmFyIGFuID0gYS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZXMnKTtcblx0XHRcdFx0XHR2YXIgYm4gPSBiLmdldEF0dHJpYnV0ZSgnZGF0YS1wYWdlcycpO1xuXHRcdFx0XHRcblx0XHRcdFx0XHRpZihhbiA+IGJuKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gMTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFxuXHRcdFx0XHRcdGlmKGFuIDwgYm4pIHtcblx0XHRcdFx0XHRcdHJldHVybiAtMTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFxuXHRcdFx0XHRcdHJldHVybiAwO1xuXHRcdFx0XHRcblx0XHRcdFx0fSApO1xuXHRcdFx0XG5cdFx0XHRcdHNpdGVzLmRldGFjaCgpLmFwcGVuZFRvKCAkKCcjc2l0ZXMnKSApO1xuXHRcdFxuXHRcdFx0fSBlbHNlIGlmKCAkKHRoaXMpLnZhbCgpID09PSAnQ3JlYXRpb25EYXRlJyApIHtcblx0XHRcblx0XHRcdFx0c2l0ZXMgPSAkKCcjc2l0ZXMgLnNpdGUnKTtcblx0XHRcdFxuXHRcdFx0XHRzaXRlcy5zb3J0KCBmdW5jdGlvbihhLGIpe1xuXHRcdFx0XG5cdFx0XHRcdFx0dmFyIGFuID0gYS5nZXRBdHRyaWJ1dGUoJ2RhdGEtY3JlYXRlZCcpLnJlcGxhY2UoXCItXCIsIFwiXCIpO1xuXHRcdFx0XHRcdHZhciBibiA9IGIuZ2V0QXR0cmlidXRlKCdkYXRhLWNyZWF0ZWQnKS5yZXBsYWNlKFwiLVwiLCBcIlwiKTtcblx0XHRcdFx0XG5cdFx0XHRcdFx0aWYoYW4gPiBibikge1xuXHRcdFx0XHRcdFx0cmV0dXJuIDE7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcblx0XHRcdFx0XHRpZihhbiA8IGJuKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gLTE7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcblx0XHRcdFx0XHRyZXR1cm4gMDtcblx0XHRcdFx0XG5cdFx0XHRcdH0gKTtcblx0XHRcdFxuXHRcdFx0XHRzaXRlcy5kZXRhY2goKS5hcHBlbmRUbyggJCgnI3NpdGVzJykgKTtcblx0XHRcblx0XHRcdH0gZWxzZSBpZiggJCh0aGlzKS52YWwoKSA9PT0gJ0xhc3RVcGRhdGUnICkge1xuXHRcdFxuXHRcdFx0XHRzaXRlcyA9ICQoJyNzaXRlcyAuc2l0ZScpO1xuXHRcdFx0XG5cdFx0XHRcdHNpdGVzLnNvcnQoIGZ1bmN0aW9uKGEsYil7XG5cdFx0XHRcblx0XHRcdFx0XHR2YXIgYW4gPSBhLmdldEF0dHJpYnV0ZSgnZGF0YS11cGRhdGUnKS5yZXBsYWNlKFwiLVwiLCBcIlwiKTtcblx0XHRcdFx0XHR2YXIgYm4gPSBiLmdldEF0dHJpYnV0ZSgnZGF0YS11cGRhdGUnKS5yZXBsYWNlKFwiLVwiLCBcIlwiKTtcblx0XHRcdFx0XG5cdFx0XHRcdFx0aWYoYW4gPiBibikge1xuXHRcdFx0XHRcdFx0cmV0dXJuIDE7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcblx0XHRcdFx0XHRpZihhbiA8IGJuKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gLTE7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcblx0XHRcdFx0cmV0dXJuIDA7XG5cdFx0XHRcdFxuXHRcdFx0XHR9ICk7XG5cdFx0XHRcblx0XHRcdFx0c2l0ZXMuZGV0YWNoKCkuYXBwZW5kVG8oICQoJyNzaXRlcycpICk7XG5cdFx0XG5cdFx0XHR9XG4gICAgICAgICAgICBcbiAgICAgICAgfSxcbiAgICAgICAgXG4gICAgICAgIFxuICAgICAgICAvKlxuICAgICAgICAgICAgZGVsZXRlcyBhIHNpdGVcbiAgICAgICAgKi9cbiAgICAgICAgZGVsZXRlU2l0ZTogZnVuY3Rpb24oZSkge1xuXHRcdFx0ICAgICAgICAgICAgXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoJyNkZWxldGVTaXRlTW9kYWwgLm1vZGFsLWNvbnRlbnQgcCcpLnNob3coKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9yZW1vdmUgb2xkIGFsZXJ0c1xuICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubW9kYWwtYWxlcnRzID4gKicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubG9hZGVyJykuaGlkZSgpO1xuXHRcdFxuICAgICAgICAgICAgdmFyIHRvRGVsID0gJCh0aGlzKS5jbG9zZXN0KCcuc2l0ZScpO1xuICAgICAgICAgICAgdmFyIGRlbEJ1dHRvbiA9ICQodGhpcyk7XG4gICAgICAgICAgIFxuICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCBidXR0b24jZGVsZXRlU2l0ZUJ1dHRvbicpLnNob3coKTtcbiAgICAgICAgICAgICQoJyNkZWxldGVTaXRlTW9kYWwnKS5tb2RhbCgnc2hvdycpO1xuICAgICAgICAgICBcbiAgICAgICAgICAgICQoJyNkZWxldGVTaXRlTW9kYWwgYnV0dG9uI2RlbGV0ZVNpdGVCdXR0b24nKS51bmJpbmQoJ2NsaWNrJykuY2xpY2soZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgICQoJyNkZWxldGVTaXRlTW9kYWwgLmxvYWRlcicpLmZhZGVJbig1MDApO1xuICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvdHJhc2gvXCIrZGVsQnV0dG9uLmF0dHIoJ2RhdGEtc2l0ZWlkJyksXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwb3N0JyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdqc29uJ1xuICAgICAgICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24ocmV0KXtcbiAgICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgICQoJyNkZWxldGVTaXRlTW9kYWwgLmxvYWRlcicpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCBidXR0b24jZGVsZXRlU2l0ZUJ1dHRvbicpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMCApIHsvL2Vycm9yXG4gICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubW9kYWwtY29udGVudCBwJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubW9kYWwtYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL2FsbCBnb29kXG4gICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubW9kYWwtY29udGVudCBwJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI2RlbGV0ZVNpdGVNb2RhbCAubW9kYWwtYWxlcnRzJykuYXBwZW5kKCAkKHJldC5yZXNwb25zZUhUTUwpICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZGVsZXRlU2l0ZU1vZGFsIGJ1dHRvbiNkZWxldGVTaXRlQnV0dG9uJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAgICAgICAgIHRvRGVsLmZhZGVPdXQoODAwLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgfSk7XHRcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgfTtcbiAgICBcbiAgICBzaXRlcy5pbml0KCk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0dmFyIGFwcFVJID0gcmVxdWlyZSgnLi91aS5qcycpLmFwcFVJO1xuXG5cdHZhciBzaXRlU2V0dGluZ3MgPSB7XG4gICAgICAgIFxuICAgICAgICAvL2J1dHRvblNpdGVTZXR0aW5nczogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NpdGVTZXR0aW5nc0J1dHRvbicpLFxuXHRcdGJ1dHRvblNpdGVTZXR0aW5nczI6ICQoJy5zaXRlU2V0dGluZ3NNb2RhbEJ1dHRvbicpLFxuICAgICAgICBidXR0b25TYXZlU2l0ZVNldHRpbmdzOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2F2ZVNpdGVTZXR0aW5nc0J1dHRvbicpLFxuICAgIFxuICAgICAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8kKHRoaXMuYnV0dG9uU2l0ZVNldHRpbmdzKS5vbignY2xpY2snLCB0aGlzLnNpdGVTZXR0aW5nc01vZGFsKTtcblx0XHRcdHRoaXMuYnV0dG9uU2l0ZVNldHRpbmdzMi5vbignY2xpY2snLCB0aGlzLnNpdGVTZXR0aW5nc01vZGFsKTtcbiAgICAgICAgICAgICQodGhpcy5idXR0b25TYXZlU2l0ZVNldHRpbmdzKS5vbignY2xpY2snLCB0aGlzLnNhdmVTaXRlU2V0dGluZ3MpO1xuICAgICAgICBcbiAgICAgICAgfSxcbiAgICBcbiAgICAgICAgLypcbiAgICAgICAgICAgIGxvYWRzIHRoZSBzaXRlIHNldHRpbmdzIGRhdGFcbiAgICAgICAgKi9cbiAgICAgICAgc2l0ZVNldHRpbmdzTW9kYWw6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIFx0XHRcbiAgICBcdFx0JCgnI3NpdGVTZXR0aW5ncycpLm1vZGFsKCdzaG93Jyk7XG4gICAgXHRcdFxuICAgIFx0XHQvL2Rlc3Ryb3kgYWxsIGFsZXJ0c1xuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5hbGVydCcpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgIFx0XHRcbiAgICBcdFx0XHQkKHRoaXMpLnJlbW92ZSgpO1xuICAgIFx0XHRcbiAgICBcdFx0fSk7XG4gICAgXHRcdFxuICAgIFx0XHQvL3NldCB0aGUgc2l0ZUlEXG4gICAgXHRcdCQoJ2lucHV0I3NpdGVJRCcpLnZhbCggJCh0aGlzKS5hdHRyKCdkYXRhLXNpdGVpZCcpICk7XG4gICAgXHRcdFxuICAgIFx0XHQvL2Rlc3Ryb3kgY3VycmVudCBmb3Jtc1xuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5tb2RhbC1ib2R5LWNvbnRlbnQgPiAqJykuZWFjaChmdW5jdGlvbigpe1xuICAgIFx0XHRcdCQodGhpcykucmVtb3ZlKCk7XG4gICAgXHRcdH0pO1xuICAgIFx0XHRcbiAgICAgICAgICAgIC8vc2hvdyBsb2FkZXIsIGhpZGUgcmVzdFxuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzV3JhcHBlciAubG9hZGVyJykuc2hvdygpO1xuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzV3JhcHBlciA+ICo6bm90KC5sb2FkZXIpJykuaGlkZSgpO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9sb2FkIHNpdGUgZGF0YSB1c2luZyBhamF4XG4gICAgXHRcdCQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVSS5zaXRlVXJsK1wic2l0ZXMvc2l0ZUFqYXgvXCIrJCh0aGlzKS5hdHRyKCdkYXRhLXNpdGVpZCcpLFxuICAgIFx0XHRcdHR5cGU6ICdwb3N0JyxcbiAgICBcdFx0XHRkYXRhVHlwZTogJ2pzb24nXG4gICAgXHRcdH0pLmRvbmUoZnVuY3Rpb24ocmV0KXsgICAgXHRcdFx0XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0aWYoIHJldC5yZXNwb25zZUNvZGUgPT09IDAgKSB7Ly9lcnJvclxuICAgIFx0XHRcdFxuICAgIFx0XHRcdFx0Ly9oaWRlIGxvYWRlciwgc2hvdyBlcnJvciBtZXNzYWdlXG4gICAgXHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5sb2FkZXInKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICBcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdCQoJyNzaXRlU2V0dGluZ3MgLm1vZGFsLWFsZXJ0cycpLmFwcGVuZCggJChyZXQucmVzcG9uc2VIVE1MKSApO1xuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHR9KTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0Ly9kaXNhYmxlIHN1Ym1pdCBidXR0b25cbiAgICBcdFx0XHRcdCQoJyNzYXZlU2l0ZVNldHRpbmdzQnV0dG9uJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0fSBlbHNlIGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAxICkgey8vYWxsIHdlbGwgOilcbiAgICBcdFx0XHRcbiAgICBcdFx0XHRcdC8vaGlkZSBsb2FkZXIsIHNob3cgZGF0YVxuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5sb2FkZXInKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5tb2RhbC1ib2R5LWNvbnRlbnQnKS5hcHBlbmQoICQocmV0LnJlc3BvbnNlSFRNTCkgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnYm9keScpLnRyaWdnZXIoJ3NpdGVTZXR0aW5nc0xvYWQnKTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0fSk7XG4gICAgXHRcdFx0XHRcbiAgICBcdFx0XHRcdC8vZW5hYmxlIHN1Ym1pdCBidXR0b25cbiAgICBcdFx0XHRcdCQoJyNzYXZlU2l0ZVNldHRpbmdzQnV0dG9uJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcdFx0XHRcbiAgICBcdFx0XHR9XG4gICAgXHRcdFxuICAgIFx0XHR9KTtcbiAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgXG4gICAgICAgIC8qXG4gICAgICAgICAgICBzYXZlcyB0aGUgc2l0ZSBzZXR0aW5nc1xuICAgICAgICAqL1xuICAgICAgICBzYXZlU2l0ZVNldHRpbmdzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9kZXN0cm95IGFsbCBhbGVydHNcbiAgICBcdFx0JCgnI3NpdGVTZXR0aW5ncyAuYWxlcnQnKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICBcdFx0XG4gICAgXHRcdFx0JCh0aGlzKS5yZW1vdmUoKTtcbiAgICBcdFx0XG4gICAgXHRcdH0pO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9kaXNhYmxlIGJ1dHRvblxuICAgIFx0XHQkKCcjc2F2ZVNpdGVTZXR0aW5nc0J1dHRvbicpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9oaWRlIGZvcm0gZGF0YVxuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5tb2RhbC1ib2R5LWNvbnRlbnQgPiAqJykuaGlkZSgpO1xuICAgIFx0XHRcbiAgICBcdFx0Ly9zaG93IGxvYWRlclxuICAgIFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5sb2FkZXInKS5zaG93KCk7XG4gICAgXHRcdFxuICAgIFx0XHQkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHVybDogYXBwVUkuc2l0ZVVybCtcInNpdGVzL3NpdGVBamF4VXBkYXRlXCIsXG4gICAgXHRcdFx0dHlwZTogJ3Bvc3QnLFxuICAgIFx0XHRcdGRhdGFUeXBlOiAnanNvbicsXG4gICAgXHRcdFx0ZGF0YTogJCgnZm9ybSNzaXRlU2V0dGluZ3NGb3JtJykuc2VyaWFsaXplQXJyYXkoKVxuICAgIFx0XHR9KS5kb25lKGZ1bmN0aW9uKHJldCl7XG4gICAgXHRcdFxuICAgIFx0XHRcdGlmKCByZXQucmVzcG9uc2VDb2RlID09PSAwICkgey8vZXJyb3JcbiAgICBcdFx0XHRcbiAgICBcdFx0XHRcdCQoJyNzaXRlU2V0dGluZ3MgLmxvYWRlcicpLmZhZGVPdXQoNTAwLCBmdW5jdGlvbigpe1xuICAgIFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdCQoJyNzaXRlU2V0dGluZ3MgLm1vZGFsLWFsZXJ0cycpLmFwcGVuZCggcmV0LnJlc3BvbnNlSFRNTCApO1xuICAgIFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0Ly9zaG93IGZvcm0gZGF0YVxuICAgIFx0XHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5tb2RhbC1ib2R5LWNvbnRlbnQgPiAqJykuc2hvdygpO1xuICAgIFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0Ly9lbmFibGUgYnV0dG9uXG4gICAgXHRcdFx0XHRcdCQoJyNzYXZlU2l0ZVNldHRpbmdzQnV0dG9uJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFx0XHRcbiAgICBcdFx0XHRcdH0pO1xuICAgIFx0XHRcdFxuICAgIFx0XHRcdFxuICAgIFx0XHRcdH0gZWxzZSBpZiggcmV0LnJlc3BvbnNlQ29kZSA9PT0gMSApIHsvL2FsbCBpcyB3ZWxsXG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XHQkKCcjc2l0ZVNldHRpbmdzIC5sb2FkZXInKS5mYWRlT3V0KDUwMCwgZnVuY3Rpb24oKXtcbiAgICBcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQvL3VwZGF0ZSBzaXRlIG5hbWUgaW4gdG9wIG1lbnVcbiAgICBcdFx0XHRcdFx0JCgnI3NpdGVUaXRsZScpLnRleHQoIHJldC5zaXRlTmFtZSApO1xuICAgIFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0JCgnI3NpdGVTZXR0aW5ncyAubW9kYWwtYWxlcnRzJykuYXBwZW5kKCByZXQucmVzcG9uc2VIVE1MICk7XG4gICAgXHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQvL2hpZGUgZm9ybSBkYXRhXG4gICAgXHRcdFx0XHRcdCQoJyNzaXRlU2V0dGluZ3MgLm1vZGFsLWJvZHktY29udGVudCA+IConKS5yZW1vdmUoKTtcbiAgICBcdFx0XHRcdFx0JCgnI3NpdGVTZXR0aW5ncyAubW9kYWwtYm9keS1jb250ZW50JykuYXBwZW5kKCByZXQucmVzcG9uc2VIVE1MMiApO1xuICAgIFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0Ly9lbmFibGUgYnV0dG9uXG4gICAgXHRcdFx0XHRcdCQoJyNzYXZlU2l0ZVNldHRpbmdzQnV0dG9uJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgXHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQvL2lzIHRoZSBGVFAgc3R1ZmYgYWxsIGdvb2Q/XG4gICAgXHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHRpZiggcmV0LmZ0cE9rID09PSAxICkgey8veWVzLCBhbGwgZ29vZFxuICAgIFx0XHRcdFx0XHRcbiAgICBcdFx0XHRcdFx0XHQkKCcjcHVibGlzaFBhZ2UnKS5yZW1vdmVBdHRyKCdkYXRhLXRvZ2dsZScpO1xuICAgIFx0XHRcdFx0XHRcdCQoJyNwdWJsaXNoUGFnZSBzcGFuLnRleHQtZGFuZ2VyJykuaGlkZSgpO1xuICAgIFx0XHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHRcdCQoJyNwdWJsaXNoUGFnZScpLnRvb2x0aXAoJ2Rlc3Ryb3knKTtcbiAgICBcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdH0gZWxzZSB7Ly9ub3BlLCBjYW4ndCB1c2UgRlRQXG4gICAgXHRcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdFx0JCgnI3B1Ymxpc2hQYWdlJykuYXR0cignZGF0YS10b2dnbGUnLCAndG9vbHRpcCcpO1xuICAgIFx0XHRcdFx0XHRcdCQoJyNwdWJsaXNoUGFnZSBzcGFuLnRleHQtZGFuZ2VyJykuc2hvdygpO1xuICAgIFx0XHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHRcdCQoJyNwdWJsaXNoUGFnZScpLnRvb2x0aXAoJ3Nob3cnKTtcbiAgICBcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdH1cbiAgICBcdFx0XHRcdFx0XG4gICAgXHRcdFx0XHRcdFxuICAgIFx0XHRcdFx0XHQvL3VwZGF0ZSB0aGUgc2l0ZSBuYW1lIGluIHRoZSBzbWFsbCB3aW5kb3dcbiAgICBcdFx0XHRcdFx0JCgnI3NpdGVfJytyZXQuc2l0ZUlEKycgLndpbmRvdyAudG9wIGInKS50ZXh0KCByZXQuc2l0ZU5hbWUgKTtcbiAgICBcdFx0XHRcdFxuICAgIFx0XHRcdFx0fSk7XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0XG4gICAgXHRcdFx0fVxuICAgIFx0XHRcbiAgICBcdFx0fSk7XG4gICAgXHRcdCAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICBcbiAgICB9O1xuICAgIFxuICAgIHNpdGVTZXR0aW5ncy5pbml0KCk7XG5cbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblxuLyogZ2xvYmFscyBzaXRlVXJsOmZhbHNlLCBiYXNlVXJsOmZhbHNlICovXG4gICAgXCJ1c2Ugc3RyaWN0XCI7XG4gICAgICAgIFxuICAgIHZhciBhcHBVSSA9IHtcbiAgICAgICAgXG4gICAgICAgIGZpcnN0TWVudVdpZHRoOiAxOTAsXG4gICAgICAgIHNlY29uZE1lbnVXaWR0aDogMzAwLFxuICAgICAgICBsb2FkZXJBbmltYXRpb246IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsb2FkZXInKSxcbiAgICAgICAgc2Vjb25kTWVudVRyaWdnZXJDb250YWluZXJzOiAkKCcjbWVudSAjbWFpbiAjZWxlbWVudENhdHMsICNtZW51ICNtYWluICN0ZW1wbGF0ZXNVbCcpLFxuICAgICAgICBzaXRlVXJsOiBzaXRlVXJsLFxuICAgICAgICBiYXNlVXJsOiBiYXNlVXJsLFxuICAgICAgICBcbiAgICAgICAgc2V0dXA6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIEZhZGUgdGhlIGxvYWRlciBhbmltYXRpb25cbiAgICAgICAgICAgICQoYXBwVUkubG9hZGVyQW5pbWF0aW9uKS5mYWRlT3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgJCgnI21lbnUnKS5hbmltYXRlKHsnbGVmdCc6IC1hcHBVSS5maXJzdE1lbnVXaWR0aH0sIDEwMDApO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYnNcbiAgICAgICAgICAgICQoXCIubmF2LXRhYnMgYVwiKS5vbignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnRhYihcInNob3dcIik7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgJChcInNlbGVjdC5zZWxlY3RcIikuc2VsZWN0MigpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkKCc6cmFkaW8sIDpjaGVja2JveCcpLnJhZGlvY2hlY2soKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVG9vbHRpcHNcbiAgICAgICAgICAgICQoXCJbZGF0YS10b2dnbGU9dG9vbHRpcF1cIikudG9vbHRpcChcImhpZGVcIik7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYmxlOiBUb2dnbGUgYWxsIGNoZWNrYm94ZXNcbiAgICAgICAgICAgICQoJy50YWJsZSAudG9nZ2xlLWFsbCA6Y2hlY2tib3gnKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgICAgICAgICB2YXIgY2ggPSAkdGhpcy5wcm9wKCdjaGVja2VkJyk7XG4gICAgICAgICAgICAgICAgJHRoaXMuY2xvc2VzdCgnLnRhYmxlJykuZmluZCgndGJvZHkgOmNoZWNrYm94JykucmFkaW9jaGVjayghY2ggPyAndW5jaGVjaycgOiAnY2hlY2snKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBBZGQgc3R5bGUgY2xhc3MgbmFtZSB0byBhIHRvb2x0aXBzXG4gICAgICAgICAgICAkKFwiLnRvb2x0aXBcIikuYWRkQ2xhc3MoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgaWYgKCQodGhpcykucHJldigpLmF0dHIoXCJkYXRhLXRvb2x0aXAtc3R5bGVcIikpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIFwidG9vbHRpcC1cIiArICQodGhpcykucHJldigpLmF0dHIoXCJkYXRhLXRvb2x0aXAtc3R5bGVcIik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICQoXCIuYnRuLWdyb3VwXCIpLm9uKCdjbGljaycsIFwiYVwiLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnNpYmxpbmdzKCkucmVtb3ZlQ2xhc3MoXCJhY3RpdmVcIikuZW5kKCkuYWRkQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gRm9jdXMgc3RhdGUgZm9yIGFwcGVuZC9wcmVwZW5kIGlucHV0c1xuICAgICAgICAgICAgJCgnLmlucHV0LWdyb3VwJykub24oJ2ZvY3VzJywgJy5mb3JtLWNvbnRyb2wnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5jbG9zZXN0KCcuaW5wdXQtZ3JvdXAsIC5mb3JtLWdyb3VwJykuYWRkQ2xhc3MoJ2ZvY3VzJyk7XG4gICAgICAgICAgICB9KS5vbignYmx1cicsICcuZm9ybS1jb250cm9sJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgnLmlucHV0LWdyb3VwLCAuZm9ybS1ncm91cCcpLnJlbW92ZUNsYXNzKCdmb2N1cycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFRhYmxlOiBUb2dnbGUgYWxsIGNoZWNrYm94ZXNcbiAgICAgICAgICAgICQoJy50YWJsZSAudG9nZ2xlLWFsbCcpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHZhciBjaCA9ICQodGhpcykuZmluZCgnOmNoZWNrYm94JykucHJvcCgnY2hlY2tlZCcpO1xuICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdCgnLnRhYmxlJykuZmluZCgndGJvZHkgOmNoZWNrYm94JykuY2hlY2tib3goIWNoID8gJ2NoZWNrJyA6ICd1bmNoZWNrJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy8gVGFibGU6IEFkZCBjbGFzcyByb3cgc2VsZWN0ZWRcbiAgICAgICAgICAgICQoJy50YWJsZSB0Ym9keSA6Y2hlY2tib3gnKS5vbignY2hlY2sgdW5jaGVjayB0b2dnbGUnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIHZhciAkdGhpcyA9ICQodGhpcylcbiAgICAgICAgICAgICAgICAsIGNoZWNrID0gJHRoaXMucHJvcCgnY2hlY2tlZCcpXG4gICAgICAgICAgICAgICAgLCB0b2dnbGUgPSBlLnR5cGUgPT09ICd0b2dnbGUnXG4gICAgICAgICAgICAgICAgLCBjaGVja2JveGVzID0gJCgnLnRhYmxlIHRib2R5IDpjaGVja2JveCcpXG4gICAgICAgICAgICAgICAgLCBjaGVja0FsbCA9IGNoZWNrYm94ZXMubGVuZ3RoID09PSBjaGVja2JveGVzLmZpbHRlcignOmNoZWNrZWQnKS5sZW5ndGg7XG5cbiAgICAgICAgICAgICAgICAkdGhpcy5jbG9zZXN0KCd0cicpW2NoZWNrID8gJ2FkZENsYXNzJyA6ICdyZW1vdmVDbGFzcyddKCdzZWxlY3RlZC1yb3cnKTtcbiAgICAgICAgICAgICAgICBpZiAodG9nZ2xlKSAkdGhpcy5jbG9zZXN0KCcudGFibGUnKS5maW5kKCcudG9nZ2xlLWFsbCA6Y2hlY2tib3gnKS5jaGVja2JveChjaGVja0FsbCA/ICdjaGVjaycgOiAndW5jaGVjaycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIC8vIFN3aXRjaFxuICAgICAgICAgICAgJChcIltkYXRhLXRvZ2dsZT0nc3dpdGNoJ11cIikud3JhcCgnPGRpdiBjbGFzcz1cInN3aXRjaFwiIC8+JykucGFyZW50KCkuYm9vdHN0cmFwU3dpdGNoKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgIGFwcFVJLnNlY29uZE1lbnVUcmlnZ2VyQ29udGFpbmVycy5vbignY2xpY2snLCAnYTpub3QoLmJ0biknLCBhcHBVSS5zZWNvbmRNZW51QW5pbWF0aW9uKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICB9LFxuICAgICAgICBcbiAgICAgICAgc2Vjb25kTWVudUFuaW1hdGlvbjogZnVuY3Rpb24oKXtcbiAgICAgICAgXG4gICAgICAgICAgICAkKCcjbWVudSAjbWFpbiBhJykucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgJCh0aGlzKS5hZGRDbGFzcygnYWN0aXZlJyk7XG5cdFxuICAgICAgICAgICAgLy9zaG93IG9ubHkgdGhlIHJpZ2h0IGVsZW1lbnRzXG4gICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kIHVsIGxpJykuaGlkZSgpO1xuICAgICAgICAgICAgJCgnI21lbnUgI3NlY29uZCB1bCBsaS4nKyQodGhpcykuYXR0cignaWQnKSkuc2hvdygpO1xuXG4gICAgICAgICAgICBpZiggJCh0aGlzKS5hdHRyKCdpZCcpID09PSAnYWxsJyApIHtcbiAgICAgICAgICAgICAgICAkKCcjbWVudSAjc2Vjb25kIHVsI2VsZW1lbnRzIGxpJykuc2hvdygpO1x0XHRcbiAgICAgICAgICAgIH1cblx0XG4gICAgICAgICAgICAkKCcubWVudSAuc2Vjb25kJykuY3NzKCdkaXNwbGF5JywgJ2Jsb2NrJykuc3RvcCgpLmFuaW1hdGUoe1xuICAgICAgICAgICAgICAgIHdpZHRoOiBhcHBVSS5zZWNvbmRNZW51V2lkdGhcbiAgICAgICAgICAgIH0sIDUwMCk7XHRcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9O1xuICAgIFxuICAgIC8vaW5pdGlhdGUgdGhlIFVJXG4gICAgYXBwVUkuc2V0dXAoKTtcblxuXG4gICAgLy8qKioqIEVYUE9SVFNcbiAgICBtb2R1bGUuZXhwb3J0cy5hcHBVSSA9IGFwcFVJO1xuICAgIFxufSgpKTsiLCIoZnVuY3Rpb24gKCkge1xuICAgIFwidXNlIHN0cmljdFwiO1xuICAgIFxuICAgIGV4cG9ydHMuZ2V0UmFuZG9tQXJiaXRyYXJ5ID0gZnVuY3Rpb24obWluLCBtYXgpIHtcbiAgICAgICAgcmV0dXJuIE1hdGguZmxvb3IoTWF0aC5yYW5kb20oKSAqIChtYXggLSBtaW4pICsgbWluKTtcbiAgICB9O1xuXG4gICAgZXhwb3J0cy5nZXRQYXJhbWV0ZXJCeU5hbWUgPSBmdW5jdGlvbiAobmFtZSwgdXJsKSB7XG5cbiAgICAgICAgaWYgKCF1cmwpIHVybCA9IHdpbmRvdy5sb2NhdGlvbi5ocmVmO1xuICAgICAgICBuYW1lID0gbmFtZS5yZXBsYWNlKC9bXFxbXFxdXS9nLCBcIlxcXFwkJlwiKTtcbiAgICAgICAgdmFyIHJlZ2V4ID0gbmV3IFJlZ0V4cChcIls/Jl1cIiArIG5hbWUgKyBcIig9KFteJiNdKil8JnwjfCQpXCIpLFxuICAgICAgICAgICAgcmVzdWx0cyA9IHJlZ2V4LmV4ZWModXJsKTtcbiAgICAgICAgaWYgKCFyZXN1bHRzKSByZXR1cm4gbnVsbDtcbiAgICAgICAgaWYgKCFyZXN1bHRzWzJdKSByZXR1cm4gJyc7XG4gICAgICAgIHJldHVybiBkZWNvZGVVUklDb21wb25lbnQocmVzdWx0c1syXS5yZXBsYWNlKC9cXCsvZywgXCIgXCIpKTtcbiAgICAgICAgXG4gICAgfTtcbiAgICBcbn0oKSk7IiwiKGZ1bmN0aW9uICgpIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cblx0cmVxdWlyZSgnLi9tb2R1bGVzL3VpLmpzJyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9hY2NvdW50LmpzJyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9zaXRlcy5qcycpO1xuXHRyZXF1aXJlKCcuL21vZHVsZXMvc2l0ZXNldHRpbmdzLmpzJyk7XG5cdHJlcXVpcmUoJy4vbW9kdWxlcy9wdWJsaXNoaW5nLmpzJyk7XG5cbn0oKSk7IiwiLyohXG4gKiBwdWJsaXNoZXIuanMgLSAoYykgUnlhbiBGbG9yZW5jZSAyMDExXG4gKiBnaXRodWIuY29tL3JwZmxvcmVuY2UvcHVibGlzaGVyLmpzXG4gKiBNSVQgTGljZW5zZVxuKi9cblxuLy8gVU1EIEJvaWxlcnBsYXRlIFxcby8gJiYgRDpcbihmdW5jdGlvbiAocm9vdCwgZmFjdG9yeSkge1xuICBpZiAodHlwZW9mIGV4cG9ydHMgPT09ICdvYmplY3QnKSB7XG4gICAgbW9kdWxlLmV4cG9ydHMgPSBmYWN0b3J5KCk7IC8vIG5vZGVcbiAgfSBlbHNlIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICBkZWZpbmUoZmFjdG9yeSk7IC8vIGFtZFxuICB9IGVsc2Uge1xuICAgIC8vIHdpbmRvdyB3aXRoIG5vQ29uZmxpY3RcbiAgICB2YXIgX3B1Ymxpc2hlciA9IHJvb3QucHVibGlzaGVyO1xuICAgIHZhciBwdWJsaXNoZXIgPSByb290LnB1Ymxpc2hlciA9IGZhY3RvcnkoKTtcbiAgICByb290LnB1Ymxpc2hlci5ub0NvbmZsaWN0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgcm9vdC5wdWJsaXNoZXIgPSBfcHVibGlzaGVyO1xuICAgICAgcmV0dXJuIHB1Ymxpc2hlcjtcbiAgICB9XG4gIH1cbn0odGhpcywgZnVuY3Rpb24gKCkge1xuXG4gIHZhciBwdWJsaXNoZXIgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgdmFyIHRvcGljcyA9IHt9O1xuICAgIG9iaiA9IG9iaiB8fCB7fTtcblxuICAgIG9iai5wdWJsaXNoID0gZnVuY3Rpb24gKHRvcGljLyosIG1lc3NhZ2VzLi4uKi8pIHtcbiAgICAgIGlmICghdG9waWNzW3RvcGljXSkgcmV0dXJuIG9iajtcbiAgICAgIHZhciBtZXNzYWdlcyA9IFtdLnNsaWNlLmNhbGwoYXJndW1lbnRzLCAxKTtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gdG9waWNzW3RvcGljXS5sZW5ndGg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgdG9waWNzW3RvcGljXVtpXS5oYW5kbGVyLmFwcGx5KHRvcGljc1t0b3BpY11baV0uY29udGV4dCwgbWVzc2FnZXMpO1xuICAgICAgfVxuICAgICAgcmV0dXJuIG9iajtcbiAgICB9O1xuXG4gICAgb2JqLnN1YnNjcmliZSA9IGZ1bmN0aW9uICh0b3BpY09yU3Vic2NyaWJlciwgaGFuZGxlck9yVG9waWNzKSB7XG4gICAgICB2YXIgZmlyc3RUeXBlID0gdHlwZW9mIHRvcGljT3JTdWJzY3JpYmVyO1xuXG4gICAgICBpZiAoZmlyc3RUeXBlID09PSAnc3RyaW5nJykge1xuICAgICAgICByZXR1cm4gc3Vic2NyaWJlLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgICB9XG5cbiAgICAgIGlmIChmaXJzdFR5cGUgPT09ICdvYmplY3QnICYmICFoYW5kbGVyT3JUb3BpY3MpIHtcbiAgICAgICAgcmV0dXJuIHN1YnNjcmliZU11bHRpcGxlLmFwcGx5KG51bGwsIGFyZ3VtZW50cyk7XG4gICAgICB9XG5cbiAgICAgIGlmICh0eXBlb2YgaGFuZGxlck9yVG9waWNzID09PSAnc3RyaW5nJykge1xuICAgICAgICByZXR1cm4gaGl0Y2guYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuIGhpdGNoTXVsdGlwbGUuYXBwbHkobnVsbCwgYXJndW1lbnRzKTtcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gc3Vic2NyaWJlICh0b3BpYywgaGFuZGxlciwgY29udGV4dCkge1xuICAgICAgdmFyIHJlZmVyZW5jZSA9IHsgaGFuZGxlcjogaGFuZGxlciwgY29udGV4dDogY29udGV4dCB8fCBvYmogfTtcbiAgICAgIHRvcGljID0gdG9waWNzW3RvcGljXSB8fCAodG9waWNzW3RvcGljXSA9IFtdKTtcbiAgICAgIHRvcGljLnB1c2gocmVmZXJlbmNlKTtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIGF0dGFjaDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgIHRvcGljLnB1c2gocmVmZXJlbmNlKTtcbiAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfSxcbiAgICAgICAgZGV0YWNoOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgZXJhc2UodG9waWMsIHJlZmVyZW5jZSk7XG4gICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH1cbiAgICAgIH07XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIHN1YnNjcmliZU11bHRpcGxlIChwYWlycykge1xuICAgICAgdmFyIHN1YnNjcmlwdGlvbnMgPSB7fTtcbiAgICAgIGZvciAodmFyIHRvcGljIGluIHBhaXJzKSB7XG4gICAgICAgIGlmICghcGFpcnMuaGFzT3duUHJvcGVydHkodG9waWMpKSBjb250aW51ZTtcbiAgICAgICAgc3Vic2NyaXB0aW9uc1t0b3BpY10gPSBzdWJzY3JpYmUodG9waWMsIHBhaXJzW3RvcGljXSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gc3Vic2NyaXB0aW9ucztcbiAgICB9O1xuXG4gICAgZnVuY3Rpb24gaGl0Y2ggKHN1YnNjcmliZXIsIHRvcGljKSB7XG4gICAgICByZXR1cm4gc3Vic2NyaWJlKHRvcGljLCBzdWJzY3JpYmVyW3RvcGljXSwgc3Vic2NyaWJlcik7XG4gICAgfTtcblxuICAgIGZ1bmN0aW9uIGhpdGNoTXVsdGlwbGUgKHN1YnNjcmliZXIsIHRvcGljcykge1xuICAgICAgdmFyIHN1YnNjcmlwdGlvbnMgPSBbXTtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gdG9waWNzLmxlbmd0aDsgaSA8IGw7IGkrKykge1xuICAgICAgICBzdWJzY3JpcHRpb25zLnB1c2goIGhpdGNoKHN1YnNjcmliZXIsIHRvcGljc1tpXSkgKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBzdWJzY3JpcHRpb25zO1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBlcmFzZSAoYXJyLCB2aWN0aW0pIHtcbiAgICAgIGZvciAodmFyIGkgPSAwLCBsID0gYXJyLmxlbmd0aDsgaSA8IGw7IGkrKyl7XG4gICAgICAgIGlmIChhcnJbaV0gPT09IHZpY3RpbSkgYXJyLnNwbGljZShpLCAxKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICByZXR1cm4gb2JqO1xuICB9O1xuXG4gIC8vIHB1Ymxpc2hlciBpcyBhIHB1Ymxpc2hlciwgc28gbWV0YSAuLi5cbiAgcmV0dXJuIHB1Ymxpc2hlcihwdWJsaXNoZXIpO1xufSkpO1xuIl19
