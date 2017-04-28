<?php
/**
 * Plugin installation
 * @since   1.0.0
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class LLMS_Install {

	public static $background_updater;

	/**
	 * Array of databse updates versions
	 * and an array of callback functions for the update
	 * @var  array
	 */
	private static $db_updates = array(
		'3.0.0' => array(
			'llms_update_300_create_access_plans',
			'llms_update_300_del_deprecated_options',
			'llms_update_300_migrate_account_field_options',
			'llms_update_300_migrate_coupon_data',
			'llms_update_300_migrate_course_postmeta',
			'llms_update_300_migrate_lesson_postmeta',
			'llms_update_300_migrate_order_data',
			'llms_update_300_migrate_email_postmeta',
			'llms_update_300_update_orders',
			'llms_update_300_update_db_version',
		),
		'3.0.3' => array(
			'llms_update_303_update_students_role',
			'llms_update_303_update_db_version',
		),
		'3.4.3' => array(
			'llms_update_343_update_relationships',
			'llms_update_343_update_db_version',
		),
		'3.6.0' => array(
			'llms_update_360_set_product_visibility',
			'llms_update_360_update_db_version',
		),
	);

	/**
	 * Initialize the install class
	 * Hooks all actions
	 * @return   void
	 * @since    3.0.0
	 * @version  3.4.3
	 */
	public static function init() {

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once 'admin/llms.functions.admin.php';

		add_action( 'init', array( __CLASS__, 'init_background_updater' ), 4 );
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'update_actions' ) );
		add_action( 'admin_init', array( __CLASS__, 'wizard_redirect' ) );

	}

	/**
	 * Checks the current LLMS version and runs installer if required
	 * @return   void
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public static function check_version() {

		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'lifterlms_current_version' ) !== LLMS()->version ) {
			self::install();
			do_action( 'lifterlms_updated' );
		}

	}

	/**
	 * Create LifterLMS cron jobs
	 * @return  void
	 * @since   1.0.0
	 * @version 3.0.0
	 */
	public static function create_cron_jobs() {

		if ( ! wp_next_scheduled( 'lifterlms_cleanup_sessions' )) {
			wp_schedule_event( time(), 'twicedaily', 'lifterlms_cleanup_sessions' );
		}

		if ( ! wp_next_scheduled( 'llms_send_tracking_data' )) {
			wp_schedule_event( time(), apply_filters( 'llms_tracker_schedule_interval', 'daily' ), 'llms_send_tracking_data' );
		}

	}

	/**
	 * Create basic course difficulties on installation
	 * @return   void
	 * @since    3.0.4
	 * @version  3.0.4
	 */
	public static function create_difficulties() {

		foreach ( self::get_difficulties() as $name ) {

			// only create if it doesn't already exist
			if ( ! get_term_by( 'name', $name, 'course_difficulty' ) ) {

				wp_insert_term( $name, 'course_difficulty' );

			}

		}

	}

	/**
	 * Create files needed by LifterLMS
	 * @return   void
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public static function create_files() {

		$upload_dir      = wp_upload_dir();
		$files = array(
			array(
				'base' 		=> LLMS_LOG_DIR,
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all',
			),
			array(
				'base' 		=> LLMS_LOG_DIR,
				'file' 		=> 'index.html',
				'content' 	=> '',
			),
		);
		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}

	}

	/**
	 * Store all default options in the DB
	 * @return  void
	 * @since   1.0.0
	 * @version 3.5.1
	 */
	public static function create_options() {

		include_once( 'admin/class.llms.admin.settings.php' );

		$settings = LLMS_Admin_Settings::get_settings_tabs();

		foreach ( $settings as $section ) {
			// skip general settings since this screen doesn't actually have any settings on it
			if ( 'general' === $section->id ) {
				continue;
			}
			foreach ( $section->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}

	}

	/**
	 * Create essential starter pages
	 * @return   boolean    false on error, true on success
	 * @since    1.0.0
	 * @version  3.0.0
	 */
	public static function create_pages() {
		$pages = apply_filters( 'llms_install_create_pages', array(
			array(
				'content' => '',
				'option' => 'lifterlms_shop_page_id',
				'slug' => 'courses',
				'title' => __( 'Course Catalog', 'lifterlms' ),
			),
			array(
				'content' => '',
				'option' => 'lifterlms_memberships_page_id',
				'slug' => 'memberships',
				'title' => __( 'Membership Catalog', 'lifterlms' ),
			),
			array(
				'content' => '[lifterlms_checkout]',
				'option' => 'lifterlms_checkout_page_id',
				'slug' => 'purchase',
				'title' => __( 'Purchase', 'lifterlms' ),
			),
			array(
				'content' => '[lifterlms_my_account]',
				'option' => 'lifterlms_myaccount_page_id',
				'slug' => 'my-courses',
				'title' => __( 'My Courses', 'lifterlms' ),
			),
		) );
		foreach ( $pages as $page ) {
			if ( ! llms_create_page( $page['slug'], $page['title'], $page['content'], $page['option'] ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Create LifterLMS user roles
	 * @return void
	 * @since  1.0.0
	 * @version  3.0.0
	 */
	public static function create_roles() {

		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		add_role( 'student', __( 'Student', 'lifterlms' ),
			array(
				'read' => true,
			)
		);

	}

	/**
	 * Create LifterLMS DB tables
	 * @return  void
	 * @since   1.0.0
	 * @version 3.3.1
	 */
	public static function create_tables() {

		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );

	}

	/**
	 * Create default LifterLMS Product Visibility Options
	 * @return   void
	 * @since    3.6.0
	 * @version  3.6.0
	 */
	public static function create_visibilities() {
		foreach ( array_keys( llms_get_product_visibility_options() ) as $term ) {
			if ( ! get_term_by( 'name', $term, 'llms_product_visibility' ) ) {
				wp_insert_term( $term, 'llms_product_visibility' );
			}
		}
	}

	/**
	 * Queue all required db updates into the bg update queue
	 * @return   void
	 * @since    3.0.0
	 * @version  3.4.3
	 */
	public static function db_updates() {

		$current_db_version = get_option( 'lifterlms_db_version' );
		$queued = false;

		foreach ( self::$db_updates as $version => $callbacks ) {

			if ( version_compare( $current_db_version, $version, '<' ) ) {

				foreach ( $callbacks as $callback ) {

					self::$background_updater->log( sprintf( 'Queuing %s - %s', $version, $callback ) );
					self::$background_updater->push_to_queue( $callback );
					$queued = true;

				}

			}

		}

		if ( $queued ) {
			add_action( 'shutdown', array( __CLASS__, 'dispatch_db_updates' ) );
		}

	}

	/**
	 * Dispatches the bg updater
	 * Prevents small database updates from displaying the "updating" admin notice
	 * instead of the "completed" notice
	 * These small updates would finish on a second thread faster than the main
	 * thread and the wrong notice would be displayed
	 * @return   void
	 * @since    3.4.3
	 * @version  3.4.3
	 */
	public static function dispatch_db_updates() {
		self::$background_updater->save()->dispatch();
	}

	/**
	 * Retrieve the default difficulty terms that should be created on a fresh install
	 * @return   array
	 * @since    3.3.1
	 * @version  3.3.1
	 */
	public static function get_difficulties() {
		return apply_filters( 'llms_install_create_difficulties', array(
			_x( 'Beginner', 'course difficulty name', 'lifterlms' ),
			_x( 'Intermediate', 'course difficulty name', 'lifterlms' ),
			_x( 'Advanced', 'course difficulty name', 'lifterlms' ),
		) );
	}

	/**
	 * Get a string of table data that can be passed to dbDelta() to install LLMS tables
	 * @return   string
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	private static function get_schema() {

		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {

			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}

		}

		$tables = "
CREATE TABLE {$wpdb->prefix}lifterlms_user_postmeta (
  meta_id bigint(20) NOT NULL auto_increment,
  user_id bigint(20) NOT NULL,
  post_id bigint(20) NOT NULL,
  meta_key varchar(255) NULL,
  meta_value longtext NULL,
  updated_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`meta_id`),
  KEY user_id (`user_id`),
  KEY post_id (`post_id`)
) $collate;
CREATE TABLE `{$wpdb->prefix}lifterlms_product_to_voucher` (
  `product_id` bigint(20) NOT NULL,
  `voucher_id` bigint(20) NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `voucher_id` (`voucher_id`)
) $collate;
CREATE TABLE `{$wpdb->prefix}lifterlms_voucher_code_redemptions` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `code_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `redemption_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code_id` (`code_id`),
  KEY `user_id` (`user_id`)
) $collate;
CREATE TABLE `{$wpdb->prefix}lifterlms_vouchers_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint(20) NOT NULL,
  `code` varchar(20) NOT NULL DEFAULT '',
  `redemption_count` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `voucher_id` (`voucher_id`)
) $collate;
";

		return $tables;

	}

	/**
	 * Initializes the bg updater class
	 * @return   void
	 * @since    3.4.3
	 * @version  3.6.0
	 */
	public static function init_background_updater() {

		include_once dirname( __FILE__ ) . '/class.llms.background.updater.php';
		self::$background_updater = new LLMS_Background_Updater;

	}

	/**
	 * Core install function
	 * @return  void
	 * @since   1.0.0
	 * @version 3.4.7
	 */
	public static function install() {

		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'lifterlms_before_install' );

		LLMS_Site::set_lock_url();
		self::create_tables();
		self::create_options();
		self::create_roles();

		LLMS_Post_Types::register_post_types();
		LLMS_Post_Types::register_taxonomies();

		LLMS()->query->init_query_vars();
		LLMS()->query->add_endpoints();

		self::create_cron_jobs();
		self::create_files();
		self::create_difficulties();
		self::create_visibilities();

		$version = get_option( 'lifterlms_current_version', null );
		$db_version = get_option( 'lifterlms_db_version', $version );

		// trigger first time run redirect
		if ( ( is_null( $version ) || is_null( $db_version ) ) || 'no' === get_option( 'lifterlms_first_time_setup', 'no' ) ) {

			set_transient( '_llms_first_time_setup_redirect', 'yes', 30 );

		}

		// show the update notice since theres db updates to run
		if ( ! is_null( $db_version ) && version_compare( $db_version, max( array_keys( self::$db_updates ) ), '<' ) ) {

			self::update_notice();

		} else {

			self::update_db_version();

		}

		self::update_llms_version();

		flush_rewrite_rules();

		do_action( 'lifterlms_after_install' );

	}

	/**
	 * Remove the difficulties created by the create_difficulties() function
	 * Used during uninstall when "remove_all_data" is set
	 * @return   void
	 * @since    3.3.1
	 * @version  3.3.1
	 */
	public static function remove_difficulties() {

		foreach ( self::get_difficulties() as $name ) {

			if ( $term = get_term_by( 'name', $name, 'course_difficulty' ) ) {

				wp_delete_term( $term->term_id, 'course_difficulty' );

			}

		}

	}

	/**
	 * Handle form submission of update related actions
	 * @return   void
	 * @since    3.4.3
	 * @version  3.4.3
	 */
	public static function update_actions() {

		// start the updater if the run button was clicked
		if ( ! empty( $_GET['llms-db-update'] ) ) {

			if ( ! wp_verify_nonce( $_GET['llms-db-update'], 'do_db_updates' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'lifterlms' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'lifterlms' ) );
			}

			// prevent page refreshes from trigerring a second queue / batch
			if ( ! self::$background_updater->is_updating() ) {
				self::db_updates();
			}

			self::update_notice();

		}

		// force update triggered
		if ( ! empty( $_GET['llms-force-db-update'] ) ) {

			if ( ! wp_verify_nonce( $_GET['llms-force-db-update'], 'force_db_updates' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'lifterlms' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'lifterlms' ) );
			}

			do_action( 'wp_llms_bg_updater_cron' );

			wp_redirect( admin_url( 'admin.php?page=llms-settings' ) );

			exit;

		}

	}

	/**
	 * Stores an admin notice for the current state of the background updater
	 * @return   void
	 * @since    3.4.3
	 * @version  3.4.3
	 */
	public static function update_notice() {

		include_once 'admin/class.llms.admin.notices.php';

		if ( LLMS_Admin_Notices::has_notice( 'bg-db-update' ) ) {
			LLMS_Admin_Notices::delete_notice( 'bg-db-update' );
		}

		if ( version_compare( get_option( 'lifterlms_db_version' ), LLMS()->version, '<' ) ) {

			if ( ! self::$background_updater ) {
				self::init_background_updater();
			}

			// update is running or button was just pressed
			if ( self::$background_updater->is_updating() || ! empty( $_GET['llms-db-update'] ) ) {

				LLMS_Admin_Notices::add_notice( 'bg-db-update', array(
					'dismissible' => false,
					'template' => 'admin/notices/db-updating.php',
				) );

			} // update needs to be run
			else {

				LLMS_Admin_Notices::add_notice( 'bg-db-update', array(
					'dismissible' => false,
					'template' => 'admin/notices/db-update.php',
				) );

			}

		} else {

			LLMS_Admin_Notices::add_notice( 'bg-db-update', __( 'The LifterLMS database update is complete.', 'lifterlms' ), array(
				'dismissible' => true,
				'dismiss_for_days' => 0,
			) );

		}

	}

	/**
	 * Update the LifterLMS DB record to the latest version
	 * @param  string $version version number
	 * @return void
	 * @since    3.0.0
	 * @version  3.4.3
	 */
	public static function update_db_version( $version = null ) {
		delete_option( 'lifterlms_db_version' );
		add_option( 'lifterlms_db_version', is_null( $version ) ? LLMS()->version : $version );
	}

	/**
	 * Update the LifterLMS version record to the latest version
	 * @param  string $version version number
	 * @return void
	 * @since    3.0.0
	 * @version  3.4.3
	 */
	public static function update_llms_version( $version = null ) {
		delete_option( 'lifterlms_current_version' );
		add_option( 'lifterlms_current_version', is_null( $version ) ? LLMS()->version : $version );
	}

	/**
	 * Redirects users to the setup wizard
	 * @return   void
	 * @since    1.0.0
	 * @version  3.0.0
	 */
	public static function wizard_redirect() {

		if ( get_transient( '_llms_first_time_setup_redirect' ) ) {

			delete_transient( '_llms_first_time_setup_redirect' );

			if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'llms-setup' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || apply_filters( 'llms_prevent_automatic_wizard_redirect', false ) ) {
				return;
			}

			if ( current_user_can( 'install_plugins' ) ) {

				wp_redirect( admin_url() . '?page=llms-setup' );
				exit;

			}

		}

	}

}

LLMS_Install::init();
