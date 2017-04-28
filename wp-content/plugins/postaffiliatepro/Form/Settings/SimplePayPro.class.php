<?php
/**
 *   @copyright Copyright (c) 2017 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package WpPostAffiliateProPlugin
 *   @since version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_SimplePayPro extends postaffiliatepro_Form_Base {
    const SIMPLEPAYPRO_COMMISSION_ENABLED = 'simplepaypro-commission-enabled';
    const SIMPLEPAYPRO_CONFIG_PAGE = 'simplepaypro-config-page';
    const SIMPLEPAYPRO_CAMPAIGN = 'simplepaypro-campaign';

    public function __construct() {
        parent::__construct(self::SIMPLEPAYPRO_CONFIG_PAGE, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/SimplePayProConfig.xtpl';
    }

    protected function initForm() {
        $campaignHelper = new postaffiliatepro_Util_CampaignHelper();
        $campaignList = $campaignHelper->getCampaignsList();

        $campaigns = array('0' => ' ');
        foreach ($campaignList as $row) {
        	$campaigns[$row->get('campaignid')] = $row->get('name');
        }
        $this->addSelect(self::SIMPLEPAYPRO_CAMPAIGN, $campaigns);

        $this->addSubmit();
    }

    public function initSettings() {
        register_setting(postaffiliatepro::INTEGRATIONS_SETTINGS_PAGE_NAME, self::SIMPLEPAYPRO_COMMISSION_ENABLED);
       	register_setting(self::SIMPLEPAYPRO_CONFIG_PAGE, self::SIMPLEPAYPRO_CAMPAIGN);
    }

    public function addPrimaryConfigMenu() {
        if (get_option(self::SIMPLEPAYPRO_COMMISSION_ENABLED) == 'true') {
            add_submenu_page(
                'integrations-config-page-handle',
                __('Simple Pay Pro','pap-integrations'),
                __('Simple Pay Pro','pap-integrations'),
                'manage_options',
                'simplepayprointegration-settings-page',
                array($this, 'printConfigPage')
                );
        }
    }

    public function printConfigPage() {
        $this->render();
        return;
    }

    public function SimplePayProAddCodeToPaymentButton($string = '') {
        $formCode = postaffiliatepro::addHiddenFieldToPaymentForm(true);
        return $string.$formCode;
    }

    public function SimplePayProHandleCharge($charge) {
        $query = 'AccountId='.substr($_POST['pap_custom'],0,8). '&visitorId='.substr($_POST['pap_custom'],-32);
        if (isset($_POST['pap_IP'])) {
            $query .= '&ip='.$_POST['pap_IP'];
        }
        $query .= '&TotalCost='.($_POST['sc-amount']/100).'&OrderID='.$charge->id;
        $query .= '&ProductID='.urlencode($_POST['sc-description']).'&Currency='.$_POST['sc-currency'];
        $query .= '&Data1='.urlencode($_POST['stripeEmail']);

        if (get_option(self::SIMPLEPAYPRO_CAMPAIGN) !== '' &&
            get_option(self::SIMPLEPAYPRO_CAMPAIGN) !== null &&
            get_option(self::SIMPLEPAYPRO_CAMPAIGN) !== 0 &&
            get_option(self::SIMPLEPAYPRO_CAMPAIGN) !== '0') {
            $query .= '&CampaignID='.get_option(self::SIMPLEPAYPRO_CAMPAIGN);
        }

        self::sendRequest(self::parseSaleScriptPath(), $query);
    }
}

$submenuPriority = 85;
$integration = new postaffiliatepro_Form_Settings_SimplePayPro();
add_action('admin_init', array($integration, 'initSettings'), 99);
add_action('admin_menu', array($integration, 'addPrimaryConfigMenu'), $submenuPriority);

add_filter('sc_before_payment_button', array($integration, 'SimplePayProAddCodeToPaymentButton'), 99);
add_action('simpay_charge_created', array($integration, 'SimplePayProHandleCharge'));