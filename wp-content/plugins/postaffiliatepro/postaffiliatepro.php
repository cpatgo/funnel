<?php
/*
 Plugin Name: Post Affiliate Pro
 Plugin URI: http://www.postaffiliatepro.com/
 Description: Easily integrate your WordPress site with your Post Affiliate Pro
 Author: QualityUnit
 Version: 1.9.2
 Author URI: http://www.qualityunit.com/
 License: GPL2
 */
if (!defined('PAP_PLUGIN_VERSION')) {
	define('PAP_PLUGIN_VERSION', '1.9.2');
}
if (!defined('PAP_PLUGIN_NAME')) {
	define('PAP_PLUGIN_NAME', 'postaffiliatepro');
}
include WP_PLUGIN_DIR . '/' . PAP_PLUGIN_NAME . '/Base.class.php';

if (!class_exists('postaffiliatepro')) {
    class postaffiliatepro extends postaffiliatepro_Base {
        const API_FILE = '/postaffiliatepro/PapApi.class.php';

        //configuration pages and settings
        //general page
        const GENERAL_SETTINGS_PAGE_NAME = 'pap_config_general_page';

        const PAP_URL_SETTING_NAME = 'pap-url';
        const PAP_MERCHANT_NAME_SETTING_NAME = 'pap-merchant-name';
        const PAP_MERCHANT_PASSWORD_SETTING_NAME = 'pap-merchant-password';

        //signup options
        const SIGNUP_SETTINGS_PAGE_NAME = 'pap_config_signup_page';

        const SIGNUP_INTEGRATION_ENABLED_SETTING_NAME = 'pap-sugnup-integration-enabled';
        const SIGNUP_DEFAULT_PARENT_SETTING_NAME = 'pap-sugnup-default-parent';
        const SIGNUP_DEFAULT_STATUS_SETTING_NAME = 'pap-sugnup-default-status';
        const SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME = 'pap-sugnup-sendconfiramtionemail';
        const SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME = 'pap-sugnup-campaigns-settings';
        const SIGNUP_INTEGRATION_USE_PHOTO = 'pap-sugnup-use-photo';
        const SIGNUP_INTEGRATION_SAVE_LEVEL = 'pap-sugnup-save-level';

        //click tracking integration page
        const CLICK_TRACKING_SETTINGS_PAGE_NAME = 'pap_config_click_tracking_page';

        const CLICK_TRACKING_ENABLED_SETTING_NAME = 'pap-click-tracking-enabled';
        const CLICK_TRACKING_ACCOUNT_SETTING_NAME = 'pap-click-tracking-account';
        const CLICK_TRACKING_CAMPAIGN = 'pap-click-tracking-capaign';

        const DEFAULT_ACCOUNT_NAME = 'default1';

        //specail integrations page
        const INTEGRATIONS_SETTINGS_PAGE_NAME = 'pap-integrations-config-page';

        public function __construct() {
            if (!$this->apiFileExists()) {
                $this->_log(__('Error during loading PAP API file: ' . WP_PLUGIN_DIR . self::API_FILE));
                return;
            }
            $this->includePapApiFile();
            $this->initUtils();
            $this->initForms();
            $this->initWidgets();
            $this->initPlugin();
            $this->initShortcodes();
        }

        private function initUtils() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/CampaignHelper.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/TopAffiliatesHelper.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/ContactForm7Helper.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/JotFormHelper.class.php';
        }

        private function initForms() {
            $path = WP_PLUGIN_DIR . '/postaffiliatepro/Form/';
            require_once $path.'Base.class.php';
            require_once $path.'Settings/General.class.php';
            require_once $path.'Settings/Signup.class.php';
            require_once $path.'Settings/AdditionalOptions.class.php';
            require_once $path.'Settings/Campaigns.class.php';
            require_once $path.'Settings/CampaignInfo.class.php';
            require_once $path.'Settings/ClickTracking.class.php';
            require_once $path.'Settings/Integrations.class.php';
            require_once $path.'Settings/ContactForm7.class.php';
            require_once $path.'Settings/JotForm.class.php';
            require_once $path.'Settings/Marketpress.class.php';
            require_once $path.'Settings/MemberPress.class.php';
            require_once $path.'Settings/S2Member.class.php';
            require_once $path.'Settings/SimplePayPro.class.php';
            require_once $path.'Settings/WishListMember.class.php';
            require_once $path.'Settings/WooComm.class.php';
        }

        private function initWidgets() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Widget/TopAffiliates.class.php';
        }

        private function initShortcodes() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Shortcode/Cache.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Shortcode/Affiliate.class.php';
        }

        private function initPlugin() {
            add_action('admin_init', array($this, 'initSettings'));
            add_filter('admin_head', array($this, 'initAdminHeader'), 99);
            add_action('admin_menu', array($this, 'addPrimaryConfigMenu'));
            add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'addSettingsLinkIntoPlugin'));
            add_action('user_register', array($this, 'onNewUserRegistration'), 99);
            add_action('register_form', array($this, 'addHiddenFieldToRegistrationForm'));
            add_action('mgm_user_register', array($this, 'onNewUserRegistration'), 99); //fix to work with magic members
            add_action('profile_update', array($this, 'onUpdateExistingUser'));
            add_filter('wp_footer', array($this, 'insertIntegrationCodeToFooter'), 99);
        }

        private function includePapApiFile() {
            require_once WP_PLUGIN_DIR . self::API_FILE;
        }

        private function apiFileExists() {
            return @file_exists(WP_PLUGIN_DIR . self::API_FILE);
        }

        private function getPapIconURL() {
            return $this->getImgUrl() . '/menu-icon.png';
        }

        public function initSettings() {
        	register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_URL_SETTING_NAME);
        	register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_MERCHANT_NAME_SETTING_NAME);
        	register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_MERCHANT_PASSWORD_SETTING_NAME);
        	register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::CLICK_TRACKING_ACCOUNT_SETTING_NAME);

        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_DEFAULT_PARENT_SETTING_NAME);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_DEFAULT_STATUS_SETTING_NAME);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_INTEGRATION_USE_PHOTO);
        	register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_INTEGRATION_SAVE_LEVEL);

        	register_setting(self::CLICK_TRACKING_SETTINGS_PAGE_NAME, self::CLICK_TRACKING_ENABLED_SETTING_NAME);
        	register_setting(self::CLICK_TRACKING_SETTINGS_PAGE_NAME, self::CLICK_TRACKING_CAMPAIGN);
        }

        public static function getPAPTrackJSDynamicCode() {
          return '<script type="text/javascript">
document.write(decodeURI("%3Cscript id=\'pap_x2s6df8d\' src=\'" + (("https:" == document.location.protocol) ? "https://" : "http://") +
"'.self::parseServerPathForClickTrackingCode().'scripts/trackjs.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>';
        }

        public static function parseServerPathForClickTrackingCode() {
            $url = str_replace ('https://', '', get_option(self::PAP_URL_SETTING_NAME));
            $url = str_replace ('http://', '', $url);
            if (substr($url,-1) != '/') {
                $url .= '/';
            }
            return $url;
        }

        public static function parseSaleScriptPath() {
        	$url = str_replace('https://', 'http://', get_option(self::PAP_URL_SETTING_NAME));
        	if (substr($url,-1) != '/') {
        		$url .= '/';
        	}
        	return $url.'scripts/sale.php';
        }

        public function insertIntegrationCodeToFooter($content) {
        	// exit if feed
        	if (is_feed()) {
        		return false;
        	}

        	// JotForm
        	if (get_option(postaffiliatepro_Form_Settings_JotForm::JOTFORM_COMMISSION_ENABLED) === 'true') {
        		$jotform = new postaffiliatepro_Util_JotFormHelper();
        		$jotform->trackSubmission();
        	}

            if (get_option(self::CLICK_TRACKING_ENABLED_SETTING_NAME) != 'true') {
                return $content;
            }

            $result = self::getPAPTrackJSDynamicCode().'<script type="text/javascript">
PostAffTracker.setAccountId(\''.self::getAccountName().'\');
try {';
            if (get_option(self::CLICK_TRACKING_CAMPAIGN) != '' && get_option(self::CLICK_TRACKING_CAMPAIGN) != '0' &&
            		get_option(self::CLICK_TRACKING_CAMPAIGN) != null & get_option(self::CLICK_TRACKING_CAMPAIGN) != 0) {
            	$result .= "var CampaignID = '".get_option(self::CLICK_TRACKING_CAMPAIGN)."';\n";
            }
  			$result .= 'PostAffTracker.track();
} catch (err) { }
</script>';
			echo $result.$content;
        }

        private function resolveParentAffiliateFromCookie(Gpf_Api_Session $session, Pap_Api_Affiliate $affiliate) {
        	if (!empty($_REQUEST['pap_parent'])) {
        		$affiliate->setParentUserId($_REQUEST['pap_parent']);
        		$this->_log(__('Parent affiliate resolved from cookies: '.$_REQUEST['pap_parent']));
        		return true;
        	}

            $clickTracker = new Pap_Api_ClickTracker($session);
            try {
                $clickTracker->track();
            } catch (Exception $e) {
                $this->_log(__('Error running track:' . $e->getMessage()));
            }
            if ($clickTracker->getAffiliate() != null) {
                $affiliate->setParentUserId($clickTracker->getAffiliate()->getValue('userid'));
            } else {
                $this->_log(__('Parent affiliate not found from cookie'));
            }
        }

        private function resolveFirstAndLastName(WP_User $user, Pap_Api_Affiliate $affiliate) {
            if ($user->first_name=='' && $user->last_name=='') {
                $affiliate->setFirstname($user->nickname);
                $affiliate->setLastname(' ');
            } else {
                $affiliate->setFirstname(($user->first_name=='')?' ':$user->first_name);
                $affiliate->setLastname(($user->last_name=='')?' ':$user->last_name);
            }
        }

        /**
         * @return Pap_Api_Affiliate
         */
        private function initAffiliate(WP_User $user, Gpf_Api_Session $session) {
            $affiliate = new Pap_Api_Affiliate($session);
            $affiliate->setUsername($user->user_email);
            $affiliate->setRefid($user->user_nicename);
            $this->resolveFirstAndLastName($user, $affiliate);
            $affiliate->setNotificationEmail($user->user_email);
            if (get_option(self::SIGNUP_INTEGRATION_SAVE_LEVEL) != '') {
                $affiliate->setData(get_option(self::SIGNUP_INTEGRATION_SAVE_LEVEL), $user->user_level);
            }
            if (get_option(self::SIGNUP_INTEGRATION_USE_PHOTO) == 'true') {
                $affiliate->setPhoto(site_url('/avatar/user-'.$user->ID.'-96.png'));
                if (is_multisite()) {
                    $affiliate->setPhoto(network_site_url('/avatar/user-'.$user->ID.'-96.png'));
                }
            }
            return $affiliate;
        }

        private function setParentToAffiliate(Pap_Api_Affiliate $affiliate, Gpf_Api_Session $session) {
        	$parentSignup = get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME);
            if (!empty($parentSignup) && $parentSignup != 'from_cookie') {
                $affiliate->setParentUserId($parentSignup);
            }
            if ($parentSignup == 'from_cookie') {
                $this->resolveParentAffiliateFromCookie($session, $affiliate);
            }
        }

        private function setStatusToAffiliate(Pap_Api_Affiliate $affiliate) {
        	$status = get_option(self::SIGNUP_DEFAULT_STATUS_SETTING_NAME);
            if (!empty($status)) {
                $affiliate->setStatus($status);
            }
        }

        private function signupIntegrationEnabled() {
            return get_option(self::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME) == 'true';
        }

        public function onNewUserRegistration($user_id) {
            if (!$this->signupIntegrationEnabled()) {
                $this->_log(__("Signup integration disabled - skipping new affiliate creation"));
                return;
            }
            $session = $this->getApiSession();
            if ($session===null) {
                $this->_log(__("We have no session to PAP installation! Registration of PAP user cancelled."));
                return;
            }
            $affiliate = $this->initAffiliate(new WP_User($user_id), $session);

            $this->setParentToAffiliate($affiliate, $session);

            $this->setStatusToAffiliate($affiliate);

            try {
                $affiliate->add();
            } catch (Exception $e) {
                $this->_log(__("Error adding affiliate" . $e->getMessage()));
                return;
            }

            if (get_option(self::SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME) == 'true') {
                if (get_option(self::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME) == 'false' || get_option('aff_notification_signup_approved_declined') == 'N') {
                    try {
                        $affiliate->sendConfirmationEmail();
                    } catch (Exception $e) {
                        $this->_log(__("Error on sending confirmation email"));
                        return;
                    }
                }
            }
            $this->processCampaigns($affiliate);
        }

        public function addHiddenFieldToRegistrationForm() {
        	if (get_option(self::PAP_URL_SETTING_NAME) == '') {
        		return false;
        	}
        	echo '<input type="hidden" name="pap_parent" value="" id="pap_xa77cb50a">'.
        		self::getPAPTrackJSDynamicCode().'
<script type="text/javascript">
	PostAffTracker.writeAffiliateToCustomField(\'pap_xa77cb50a\');
</script>';
        }

        public static function addHiddenFieldToPaymentForm($return = false) {
            $result = '<!-- Post Affiliate Pro integration snippet -->';
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $result .= '<input type="hidden" name="pap_IP" value="'.$_SERVER['REMOTE_ADDR'].'" />';
            }
            $result .= '<input type="hidden" name="pap_custom" value="" id="pap_dx8vc2s5" />'.
                    self::getPAPTrackJSDynamicCode().'
      			<script type="text/javascript">
	      		  PostAffTracker.setAccountId(\''.self::getAccountName().'\');
	              PostAffTracker.notifySale();
      			</script>
      			<!-- /Post Affiliate Pro integration snippet -->';
            if ($return) {
                return $result;
            } else {
                echo $result;
                return;
            }
        }

        private function getCampaignOption($campaignId, $name) {
            $value = get_option(self::SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME);
            if (!is_array($value)) {
                return '';
            }
            if (!array_key_exists($name . '-' . $campaignId, $value)) {
                return '';
            }
            return $value[$name . '-' . $campaignId];
        }

        private function assignToCampaign(Pap_Api_Affiliate $affiliate, $campaignId, $sendNotification) {
            try {
                $affiliate->assignToPrivateCampaign($campaignId, ($sendNotification=='true')?true:false);
            } catch (Exception $e) {
                $this->_log('Unable to assign user to private camapign ' . $campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID) . ', problem: ' . $e->getMessage());
            }
        }

        private function processCampaigns(Pap_Api_Affiliate $affiliate) {
            $campaigns = $this->getCampaignHelper()->getCampaignsList();
            if ($campaigns === null) {
                return;
            }
            foreach ($campaigns as $campaign) {
                if ($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE) != postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE_PUBLIC) {
                    if ($this->getCampaignOption($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID), postaffiliatepro_Form_Settings_CampaignInfo::ADD_TO_CAMPAIGN) == 'true') {
                        $this->assignToCampaign($affiliate, $campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID),
                        $this->getCampaignOption($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID), postaffiliatepro_Form_Settings_CampaignInfo::SEND_NOTIFICATION_EMAIL));
                    }
                }
            }
        }

        public function onUpdateExistingUser($user_id) {
            if (!$this->signupIntegrationEnabled()) {
                $this->_log(__("Signup integratoin disabled - skipping upating existing affiliate"));
                return;
            }
            $session = $this->getApiSession();
            if ($session === null) {
                $this->_log(__("We have no session to PAP installation! Updating of PAP user cancelled."));
                return;
            }
            $user = new WP_User($user_id);
            $affiliate = new Pap_Api_Affiliate($session);
            $affiliate->setRefid($user->user_nicename, Pap_Api_Affiliate::OPERATOR_EQUALS);
            try {
                $affiliate->load();
            } catch (Exception $e) {
                // try it with notification email as well
                $this->_log(__('Unable to load affiliate').' '.__('by referral ID'));
                try {
                    $affiliate->setRefid('');
                    $affiliate->setNotificationEmail($user->user_email);
                    $affiliate->load();
                } catch (Exception $e) {
                    // last try - username
                    $this->_log(__('Unable to load affiliate').' '.__('by notification email'));
                    try {
                        $affiliate->setNotificationEmail('');
                        $affiliate->setUsername($user->user_email, Pap_Api_Affiliate::OPERATOR_EQUALS);
                        $affiliate->load();
                    } catch (Exception $e) {
                        $this->_log(__('Unable to load affiliate').' '.__('by username'));
                        $this->_log(__('Update of user %s cancelled', $user->nickname));
                        return;
                    }
                }
            }
            $this->resolveFirstAndLastName($user, $affiliate);
            $affiliate->setNotificationEmail($user->user_email);
            $affiliate->setData(get_option(self::SIGNUP_INTEGRATION_SAVE_LEVEL), $user->user_level);
            $affiliate->save();
        }

        public function initAdminHeader($content) {
        	if (!is_feed()) {
        		echo $this->getStylesheetHeaderLink('style.css');
        	}
        	echo $content;
        }

        public function addSettingsLinkIntoPlugin($links) {
            return array_merge($links, array('<a href="'.admin_url('admin.php?page=pap-top-level-options-handle').'">Settings</a>'));
        }

        public function addPrimaryConfigMenu() {
            $handle = 'pap-top-level-options-handle';
            add_menu_page(__('Post Affiliate Pro','pap-menu'), __('Post Affiliate Pro','pap-menu'), 'manage_options', $handle, array($this, 'printGeneralConfigPage'), $this->getPapIconURL());
            add_submenu_page($handle, __('General','pap-menu'), __('General','pap-menu'), 'manage_options', $handle, array($this, 'printGeneralConfigPage'));

            if (!$this->isPluginSet()) {
                return;
            }

            add_submenu_page($handle, __('Click tracking','pap-menu'), __('Click tracking','pap-menu'), 'manage_options', 'click-tracking-config-page', array($this, 'printClickTrackingConfigPage'));
            add_submenu_page($handle, __('Signup','pap-menu'), __('Signup options','pap-menu'), 'manage_options', 'signup-config-page', array($this, 'printSignupConfigPage'));
            add_submenu_page($handle, __('Additional options','pap-menu'), __('Additional options','pap-menu'), 'manage_options', 'additional-options-page', array($this, 'printAdditionalOptionsPage'));

            add_menu_page(__('Integrations','pap-integrations'), __('Integrations','pap-integrations'), 'manage_options', 'integrations-config-page-handle', array($this, 'printIntegrationsConfigPage'), $this->getPapIconURL());
            add_submenu_page('integrations-config-page-handle', __('General', 'pap-integrations'), __('General', 'pap-integrations'), 'manage_options', 'integrations-config-page-handle', array($this, 'printIntegrationsConfigPage'));
        }
        public function printGeneralConfigPage() {
            $form = new postaffiliatepro_Form_Settings_General();
            $form->render();
        }
        public function printSignupConfigPage() {
            $form = new postaffiliatepro_Form_Settings_Signup();
            $form->render();
            return;
        }
        public function printClickTrackingConfigPage() {
            $form = new postaffiliatepro_Form_Settings_ClickTracking();
            $form->render();
            return;
        }
        public function printAdditionalOptionsPage() {
            $form = new postaffiliatepro_Form_Settings_AdditionalOptions();
            $form->render();
            return;
        }
        public function printIntegrationsConfigPage() {
            $form = new postaffiliatepro_Form_Settings_Integrations();
            $form->render();
            return;
        }
    }
}

$postaffiliatepro = new postaffiliatepro();