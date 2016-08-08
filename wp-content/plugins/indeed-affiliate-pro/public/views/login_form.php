<?php 

function uap_print_form_login($meta_arr){
	/*
	 * @param array
	 * @return string
	 */
	$str = '';
	if($meta_arr['uap_login_custom_css']){
		$str .= '<style>'.$meta_arr['uap_login_custom_css'].'</style>';
	}
	
	$sm_string = '';
	
	$str .= '<div class="uap-login-form-wrap '.$meta_arr['uap_login_template'].'">'
			.'<form action="" method="post" id="uap_login_form">'
			. '<input type="hidden" name="uapaction" value="login" />';
	
	switch ($meta_arr['uap_login_template']){
	
	case 'uap-login-template-2':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'uap').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'uap').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;			
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-form-line-fr impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
	break;
		
	case 'uap-login-template-3':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">'
				. '<input type="text" value="" name="log" placeholder="'.__('Username', 'uap').'"/>'
				. '</div>'
				. '<div class="impu-form-line-fr">'
				. '<input type="password" value="" name="pwd" placeholder="'.__('Password', 'uap').'"/>'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		
		$str .=    '<div class="impu-temp3-bottom">';		 
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if ($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if ($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>	
		$str .= '<div class="uap-clear"></div>';
		$str .= '</div>';
		
		break;
		
	case 'uap-login-template-4':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">'
				. '<i class="fa-uap fa-username-uap"></i><input type="text" value="" name="log" placeholder="'.__('Username', 'uap').'"/>'
				. '</div>'
				. '<div class="impu-form-line-fr">'
				. '<i class="fa-uap fa-pass-uap"></i><input type="password" value="" name="pwd" placeholder="'.__('Password', 'uap').'"/>'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.' />'
				 . '</div>';
				 
		
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		break;
	case 'uap-login-template-5':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'uap').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'uap').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		$str .=    '<div class="impu-temp5-row">';	
		$str .=    '<div class="impu-temp5-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="uap-clear"></div>';
		$str .= '</div>';	
		
		break;
		case 'uap-login-template-6':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'uap').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'uap').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .=    '<div class="impu-temp6-row">';	
		$str .=    '<div class="impu-temp6-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="uap-clear"></div>';
		$str .= '</div>';	
		
		break;	
		
		case 'uap-login-template-7':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'uap').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'uap').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		$str .=    '<div class="impu-temp5-row">';	
		$str .=    '<div class="impu-temp5-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'uap').'</span> </div>';
		}
		//>>>>
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="uap-clear"></div>';
		$str .= '</div>';	
		
		break;
			
	default:			
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'uap').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'uap').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;	
		//<<<< REMEMBER ME			
		if($meta_arr['uap_login_remember_me']){
			$str .= '<div class="impu-form-line-fr impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['uap_login_register'] || $meta_arr['uap_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['uap_login_register']){
				$pag_id = get_option('uap_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'uap').'</a></div>';
				}
			}
			if($meta_arr['uap_login_pass_lost']){
				$pag_id = get_option('uap_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'uap').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'uap').'" name="Submit" '.$disabled.' class="button button-primary button-large"/>'
				 . '</div>';
		//>>>>
		break;
	}
	
	$str .=   '</form>'
			.'</div>';
			
	return $str;
}

