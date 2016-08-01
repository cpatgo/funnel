<?php 
global $avia_config;

$responsive		= avia_get_option('responsive_active') != "disabled" ? "responsive" : "fixed_layout";
$headerS 		= avia_header_setting();
$social_args 	= array('outside'=>'ul', 'inside'=>'li', 'append' => '');
$icons 			= !empty($headerS['header_social']) ? avia_social_media_icons($social_args, false) : "";

if(isset($headerS['disabled'])) return;

?>

<header id='header' class='all_colors header_color <?php avia_is_dark_bg('header_color'); echo " ".$headerS['header_class']; ?>' <?php avia_markup_helper(array('context' => 'header','post_type'=>'forum'));?>>

<?php

if($responsive)
{
	echo '<a id="advanced_menu_toggle" href="#" '.av_icon_string('mobile_menu').'></a>';
	echo '<a id="advanced_menu_hide" href="#" 	'.av_icon_string('close').'></a>';
}


//subheader, only display when the user chooses a social header
if($headerS['header_topbar'] == true)
{
?>
		<div id='header_meta' class='container_wrap container_wrap_meta <?php echo avia_header_class_string(array('header_social', 'header_secondary_menu', 'header_phone_active'), 'av_'); ?>'>
		
			      <div class='container'>
				      <div class="logo_container">
				      	<div class="logo"><a href="/"><img src="/wp-content/uploads/2016/08/glc-logo-empowering.png" alt="Global Learning Center"><span class="subtext"><img src="/wp-content/uploads/2016/08/glc-logo-empowering.png" class="alternate" alt="" title=""></span></a></div>
				      </div>
			      <?php
			            /*
			            *	display the themes social media icons, defined in the wordpress backend
			            *   the avia_social_media_icons function is located in includes/helper-social-media-php
			            */
						$nav = "";
						
						//display icons
			            if(strpos( $headerS['header_social'], 'extra_header_active') !== false) echo $icons;
					
						//display navigation
						if(strpos( $headerS['header_secondary_menu'], 'extra_header_active') !== false )
						{
			            	//display the small submenu
			                $avia_theme_location = 'avia2';
			                $avia_menu_class = $avia_theme_location . '-menu';


			                $args = array(
			                    'theme_location'=>$avia_theme_location,
			                    'menu_id' =>$avia_menu_class,
			                    'container_class' =>$avia_menu_class,
			                    'fallback_cb' => '',
			                    'container'=>'',
			                    'echo' =>false,
								// 'items_wrap' => '<ul class="%2$s">%3$s</ul>'
			                );
			                if( isset($_COOKIE['referral']) ) {
								$args['items_wrap'] = '<ul><li id="item-id" class="btn-glc-referral menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Referred By: Temporary <span>'.$_COOKIE['referral'].'</span></a></li>%3$s</ul>';
							}
							
							
			                
			                
			                // var_dump($args);
			                $nav = wp_nav_menu($args);
						}
			            
						if(!empty($nav) || apply_filters('avf_execute_avia_meta_header', false))
						{
							echo "<nav class='sub_menu' ".avia_markup_helper(array('context' => 'nav', 'echo' => false)).">";
						
						
			    // Display name and membership
                            if(is_user_logged_in()):
                                $current_user = wp_get_current_user();
                                $role = $wpdb->prefix . 'capabilities';
                                $current_user->role = array_keys($current_user->$role);
                                $role = $current_user->role[0];
                                
                                include_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
				$user_class = getInstance('Class_User');
				$glc_user = $user_class->get_by_username($current_user->user_login);

                                $r = (!empty(get_user_meta(get_current_user_id(), 'membership', true))) ? get_user_meta(get_current_user_id(), 'membership', true) : ucfirst($role);
                                ?>
				<ul id="avia2-menu" class="menu">
				   					
                                    <li class="btn-glc-welcome menu-item menu-item-type-post_type menu-item-object-page">
                                        <span class="user_header">
                                            <span class="darker">Welcome:</span> <?php echo esc_html( $current_user->user_login ) ?>
                                        </span>
                                    </li>
                                    <li class="btn-glc-memberships menu-item menu-item-type-post_type menu-item-object-page">
                                        <span class="user_header">
                                            <span class="darker">Membership:</span> <?php echo $r ?>
                                        </span>
                                    </li>
                                    <li></li>
                                    <?php if($glc_user[0]['initial'] < 4): ?>
                                    <li class="btn btn-glc-upgrade menu-item menu-item-type-post_type menu-item-object-page">
                                        <a href='/glc/upgrade.php'>UPGRADE</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                                <?php
                            endif;

						
							echo $nav;
		                    do_action('avia_meta_header'); // Hook that can be used for plugins and theme extensions (currently: the wpml language selector)
							echo '</nav>';
							
							// CUSTOM CODE
							
							// END CUSTOM CODE
		                    
						}
						
						
						//phone/info text	
						$phone			= $headerS['header_phone_active'] != "" ? $headerS['phone'] : "";
						$phone_class 	= !empty($nav) ? "with_nav" : "";
						if($phone) 		{ echo "<div class='phone-info {$phone_class}'><span>".do_shortcode($phone)."</span></div>"; }
							
							
			        ?>
			        <?php
							
								// echo '<span class="header_referral btn btn-tertiary">Referred by: <strong>Jason'.$_COOKIE['referral'].'</strong></span>';
							
						?>



			      </div>
		</div>

<?php } 
	
	
	
	$output 	 = "";
	$temp_output = "";
	$icon_beside = "";
	
	if($headerS['header_social'] == 'icon_active_main' && empty($headerS['bottom_menu']))
	{
		$icon_beside = " av_menu_icon_beside"; 
	}
	
	
	
	
	
	
?>
		<div  id='header_main' class='container_wrap container_wrap_logo'>
	
        <?php
        /*
        * Hook that can be used for plugins and theme extensions (currently:  the woocommerce shopping cart)
        */
        do_action('ava_main_header');
        
        if($headerS['header_position'] != "header_top") do_action('ava_main_header_sidebar');
		
	
				 $output .= "<div class='container av-logo-container'>";
				 
					$output .= "<div class='inner-container'>";
						
						/*
						*	display the theme logo by checking if the default logo was overwritten in the backend.
						*   the function is located at framework/php/function-set-avia-frontend-functions.php in case you need to edit the output
						*/
						$addition = false;
						if( !empty($headerS['header_transparency']) && !empty($headerS['header_replacement_logo']) )
						{
							#$addition = "<img src='".$headerS['header_replacement_logo']."' class='alternate' alt='' title='' />";
						}
						
						$output .= avia_logo(AVIA_BASE_URL.'images/layout/logo.png', $addition, 'strong', true);
						
							if(!empty($headerS['bottom_menu']))
							{
								ob_start();
								do_action('ava_before_bottom_main_menu'); // todo: replace action with filter, might break user customizations
								$output .= ob_get_clean();
							}
							
						    if($headerS['header_social'] == 'icon_active_main' && !empty($headerS['bottom_menu']))
						    {
							    $output .= $icons;
						    }
						    
						
						/*
						*	display the main navigation menu
						*   modify the output in your wordpress admin backend at appearance->menus
						*/
						    
						    if($headerS['bottom_menu'])
						    { 
							    $output .= "</div>";  
								$output .= "</div>";
								
								if( !empty( $headerS['header_menu_above'] ))
								{
									$avia_config['temp_logo_container'] = "<div class='av-section-bottom-logo header_color'>".$output."</div>";
									$output = "";
								}
								
								$output .= "<div id='header_main_alternate' class='container_wrap'>";
								$output .= "<div class='container'>";
							}
						
						
						    $output .= "<nav class='main_menu' data-selectname='".__('Select a page','avia_framework')."' ".avia_markup_helper(array('context' => 'nav', 'echo' => false)).">";
						        $avia_theme_location = 'avia';
						        $avia_menu_class = $avia_theme_location . '-menu';
						        $args = array(
						            'theme_location'	=> $avia_theme_location,
						            'menu_id' 			=> $avia_menu_class,
						            'menu_class'		=> 'menu av-main-nav',
						            'container_class'	=> $avia_menu_class.' av-main-nav-wrap'.$icon_beside,
						            'fallback_cb' 		=> 'avia_fallback_menu',
						            'echo' 				=>	false, 
						            'walker' 			=> new avia_responsive_mega_menu()
						        );
						
						        $main_nav = wp_nav_menu($args);
						        $output .= $main_nav;
						        
						      
						    /*
						    * Hook that can be used for plugins and theme extensions
						    */
						    ob_start();
						    do_action('ava_inside_main_menu'); // todo: replace action with filter, might break user customizations
						    $output .= ob_get_clean();
						    
						    if($icon_beside)
						    {
							    $output .= $icons; 
						    }
						        
						    $output .= '</nav>';
						
						    /*
						    * Hook that can be used for plugins and theme extensions
						    */
						    ob_start();
						    do_action('ava_after_main_menu'); // todo: replace action with filter, might break user customizations
							$output .= ob_get_clean();
				
					 /* inner-container */
			        $output .= "</div>";
						
		        /* end container */
		        $output .= " </div> ";
		   		
		   		
		   		//output the whole menu     
		        echo $output; 
		        
		        
		   ?>

		<!-- end container_wrap-->
		</div>
		
		<div class='header_bg'></div>

<!-- end header -->
</header>
