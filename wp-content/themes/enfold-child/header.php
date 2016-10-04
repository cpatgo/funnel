<?php
	if(isset($_SESSION['dennisn_user_name']) && isset($_SESSION['dennisn_usertoken'])):
		//Auto login user
		$pw = $_SESSION['dennisn_usertoken'];
		$decode = base64_decode($pw);
		$chunk = explode('-', $decode);
		$password = base64_decode($chunk[1]);

		$creds = array();
	    $creds['user_login'] = $_SESSION['dennisn_user_name'];
	    $creds['user_password'] = $password;
	    $creds['remember'] = true;
	    $user = wp_signon($creds, false);
	    wp_set_current_user($user->ID);
	endif;

	if ( !defined('ABSPATH') ){ die(); }
	
	global $avia_config;

	$style 				= $avia_config['box_class'];
	$responsive			= avia_get_option('responsive_active') != "disabled" ? "responsive" : "fixed_layout";
	$blank 				= isset($avia_config['template']) ? $avia_config['template'] : "";	
	$av_lightbox		= avia_get_option('lightbox_active') != "disabled" ? 'av-default-lightbox' : 'av-custom-lightbox';
	$preloader			= avia_get_option('preloader') == "preloader" ? 'av-preloader-active av-preloader-enabled' : 'av-preloader-disabled';
	$sidebar_styling 	= avia_get_option('sidebar_styling');
	$filterable_classes = avia_header_class_filter( avia_header_class_string() );

	
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo "html_{$style} ".$responsive." ".$preloader." ".$av_lightbox." ".$filterable_classes ?> ">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php
/*
 * outputs a rel=follow or nofollow tag to circumvent google duplicate content for archives
 * located in framework/php/function-set-avia-frontend.php
 */
 if (function_exists('avia_set_follow')) { echo avia_set_follow(); }

?>


<!-- mobile setting -->
<?php

if( strpos($responsive, 'responsive') !== false ) echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
?>


<!-- Scripts/CSS and wp_head hook -->
<?php
/* Always have wp_head() just before the closing </head>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to add elements to <head> such
 * as styles, scripts, and meta tags.
 */

wp_head();

?>

</head>


<body id="top" <?php body_class($style." ".$avia_config['font_stack']." ".$blank." ".$sidebar_styling); avia_markup_helper(array('context' => 'body')); ?>>
	
	<?php 	
		include_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');

		$user_class = getInstance('Class_User');

		$welcome_message = $user_class->glc_usermeta($_SESSION['dennisn_user_id'], 'welcome_message');
		if('myhub' === get_query_var('pagename') && (int)$welcome_message !== 1):
			show_welcome_message();
			$user_class->glc_update_usermeta($_SESSION['dennisn_user_id'], 'welcome_message', 1);
		endif;

		function show_welcome_message()
		{
			$page = get_page_by_path('welcome-to-glc-hub');
			$content = apply_filters('avia_builder_precompile', get_post_meta($page->ID, '_aviaLayoutBuilderCleanData', true));
			$content = apply_filters('the_content', $content);
			$content = apply_filters('avf_template_builder_content', $content);
			?>
			<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		  	<style type="text/css">
		  		.jquery-modal.blocker.current {
		  			z-index: 1000;
		  		}
		  		#welcome_message .avia-section {
		  			box-shadow: none;
    				border: none;
		  		}
		  	</style>
			<script type="text/javascript">
				$(function(){
					var the_content = '<div class="entry-content-wrapper clearfix" id="welcome_message" style="display:none;max-width: none;max-width: none;padding: 0 5%;">';
					the_content += <?php echo json_encode($content) ?>;
					the_content += '<a class="avia-button avia-size-large avia-button-right" style="color: #ffffff;background-color: #258dcd;" rel="modal:close">CLOSE</a></div>';

					$('body').append(the_content);
					
					jQuery("#welcome_message").modal();
				});
			</script><?php
		}
	?>
	<?php 
		
	if("av-preloader-active av-preloader-enabled" === $preloader)
	{
		echo avia_preload_screen(); 
	}
		
	?>

	<div id='wrap_all'>

	<?php 
	if(!$blank) //blank templates dont display header nor footer
	{ 
		 //fetch the template file that holds the main menu, located in includes/helper-menu-main.php
         get_template_part( 'includes/helper', 'main-menu' );

	} ?>
		
	<div id='main' class='all_colors' data-scroll-offset='<?php echo avia_header_setting('header_scroll_offset'); ?>'>

	<?php 
		
		if(isset($avia_config['temp_logo_container'])) echo $avia_config['temp_logo_container'];
		do_action('ava_after_main_container'); 
		
	?>
