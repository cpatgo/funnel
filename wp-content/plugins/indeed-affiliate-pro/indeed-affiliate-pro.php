<?php
/*
Plugin Name: Indeed Ultimate Affiliate Pro
Plugin URI: http://www.wpindeed.com/
Description: The most complete and easy to use Affiliate system Plugin that provides you a complete solution for your affiliates.
Version: 1.9.1
Author: indeed
Author URI: http://www.wpindeed.com
*/
class UAP_Main{
	private static $instance = FALSE;
	
	public function __construct(){}
	
	public static function run(){
		/*
		 * @param none
		 * @return none
		 */
		if (self::$instance==TRUE){
			return;
		}		
		self::$instance = TRUE;
		/// PATHS
		if (!defined('UAP_PATH')){
			define('UAP_PATH', plugin_dir_path(__FILE__));
		}
		if (!defined('UAP_URL')){
			define('UAP_URL', plugin_dir_url(__FILE__));
		}
		if (!defined('UAP_PROTOCOL')){
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
				define('UAP_PROTOCOL', 'https://');
			} else {
				define('UAP_PROTOCOL', 'http://');
			}
		}
		
		if (!defined('UAP_PLUGIN_VER')){			
			define('UAP_PLUGIN_VER', 1.8);//used for updates
		}
		
		/// LANGUAGES
		add_action('init', array('UAP_Main', 'uap_load_language'));
		add_filter('send_password_change_email', array('UAP_Main', 'uap_update_passowrd_filter'), 99, 2);
		
		require_once UAP_PATH . 'utilities.php';
		require_once UAP_PATH . 'classes/Uap_Db.class.php';
		global $indeed_db;
		$indeed_db = new Uap_Db();	
		
		define('UAP_LICENSE_SET', $indeed_db->envato_check_license() );	
		
		require_once UAP_PATH . 'classes/Uap_Ajax.class.php';
		$uap_ajax = new Uap_Ajax();
		
		if (is_admin() && !defined('DOING_AJAX')){
			/// ADMIN
			require_once UAP_PATH . 'admin/Uap_Main_Admin.class.php';
			$uap_main_object = new Uap_Main_Admin();
		} else {
			/// PUBLIC
			require_once UAP_PATH . 'public/Uap_Main_Public.class.php';
			$uap_main_object = new Uap_Main_Public();
		}
		
		/// CRON
		require_once UAP_PATH . 'classes/Uap_Cron_Jobs.class.php';
		$uap_cron_object = new Uap_Cron_Jobs();		
		
	}
	
	public static function uap_load_language(){
		/*
		 * @param none
		 * @return none
		 */
		load_plugin_textdomain( 'uap', false, dirname(plugin_basename(__FILE__)) . '/languages/' );
	}
	
	public static function uap_update_passowrd_filter($return, $user_data){
		/*
		 * @param return - boolean, $user_data - array
		 * @return boolean
		 */
		if (isset($user_data['ID']) && $return){
			$sent_mail = uap_send_user_notifications($user_data['ID'], 'change_password');
			if ($sent_mail){
				return FALSE;
			}
		}
		return $return;				
	}

}

UAP_Main::run();


