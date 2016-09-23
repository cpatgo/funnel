<?php
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
			?>
			<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		  	<style type="text/css">
		  		.jquery-modal.blocker.current {
		  			z-index: 1000;
		  		}
		  	</style>
			<script type="text/javascript">
				$(function(){
					$('body').append('<div class="entry-content-wrapper clearfix" id="welcome_message" style="display:none;max-width: none;max-width: none;padding: 2% 5%;"><div class="flex_column av_one_fifth  flex_column_div av-zero-column-padding first  avia-builder-el-1  el_before_av_three_fifth  avia-builder-el-first  " style="border-radius:0px; "></div><div class="flex_column av_three_fifth  flex_column_div av-zero-column-padding   avia-builder-el-2  el_after_av_one_fifth  el_before_av_one_fifth  " style="border-radius:0px; "><div style="padding-bottom:10px;font-size:36px;" class="av-special-heading av-special-heading-h2  blockquote modern-quote modern-centered  avia-builder-el-3  el_before_av_hr  avia-builder-el-first   av-inherit-size"><h2 class="av-special-heading-tag" itemprop="headline">Thank you for joining GLC HUB!</h2><div class="special-heading-border"><div class="special-heading-inner-border"></div></div></div><div style=" margin-top:15px; margin-bottom:15px;" class="hr hr-custom hr-center hr-icon-yes  avia-builder-el-4  el_after_av_heading  avia-builder-el-last "><span class="hr-inner   inner-border-av-border-thin" style=" width:200px; border-color:#999999;"><span class="hr-inner-style"></span></span><span class="av-seperator-icon" style="color:#e97e00;" aria-hidden="true" data-av_icon="" data-av_iconfont="entypo-fontello"></span><span class="hr-inner   inner-border-av-border-thin" style=" width:200px; border-color:#999999;"><span class="hr-inner-style"></span></span></div></div><div class="flex_column av_one_fifth  flex_column_div av-zero-column-padding   avia-builder-el-5  el_after_av_three_fifth  el_before_av_textblock  " style="border-radius:0px; "></div><section class="av_textblock_section" itemscope="itemscope" itemtype="https://schema.org/CreativeWork"><div class="avia_textblock " itemprop="text"><p style="text-align: center;">Our mission is provide the education, tools and technology for anyone who desires to rise above the common level in business.<br>We support our Members by removing the headaches of technology and providing the training and tools which help to automate their marketing efforts.</p><p style="text-align: center;">The GLC HUB was created by a group of like-minded professional Marketers who realized that many of the marketing platforms available today left their customers seeking more of the tools which help to automate the marketing process. Created from the ground up, we placed high value on the new Members, but also wanted to provide everyone regardless of IT skill level, a User Experience that was seamless for all.</p><p style="text-align: center;">To Your Success,<br>The GLC HUB Support Team</p><p style="text-align: center;">To access our HUB training videos, knowledge base, and FAQ’s click on the "HUB HOW TO\'S" link located in the main menu or visit <a href="http://glcv2.identifz.com/hub-how-tos">http://glcv2.identifz.com/hub-how-tos</a>.</p><a class="avia-button avia-size-large avia-button-right" style="color: #ffffff;background-color: #258dcd;" rel="modal:close">CLOSE</a></div></section></div>');
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
