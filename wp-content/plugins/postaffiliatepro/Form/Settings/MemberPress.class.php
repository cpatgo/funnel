<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package WpPostAffiliateProPlugin
 *   @since version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_MemberPress extends postaffiliatepro_Form_Base {
    const MEMBERPRESS_COMMISSION_ENABLED = 'memberpress-commission-enabled';
    const MEMBERPRESS_CONFIG_PAGE = 'memberpress-config-page';
    const MEMBERPRESS_ENABLE_LIFETIME = 'memberpress-enable-lifetime';
    const MEMBERPRESS_TRACK_RECURRING = 'memberpress-track-refurring';

    public function __construct() {
        parent::__construct(self::MEMBERPRESS_CONFIG_PAGE, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/MemberPressConfig.xtpl';
    }

    protected function initForm() {
        $this->addCheckbox(self::MEMBERPRESS_ENABLE_LIFETIME);
        $this->addCheckbox(self::MEMBERPRESS_TRACK_RECURRING);

        $this->addSubmit();
    }

    public function initSettings() {
        register_setting(postaffiliatepro::INTEGRATIONS_SETTINGS_PAGE_NAME, self::MEMBERPRESS_COMMISSION_ENABLED);
        register_setting(self::MEMBERPRESS_CONFIG_PAGE, self::MEMBERPRESS_ENABLE_LIFETIME);
        register_setting(self::MEMBERPRESS_CONFIG_PAGE, self::MEMBERPRESS_TRACK_RECURRING);
    }

    public function addPrimaryConfigMenu() {
        if (get_option(self::MEMBERPRESS_COMMISSION_ENABLED) == 'true') {
            add_submenu_page(
                'integrations-config-page-handle',
                __('MemberPress','pap-integrations'),
                __('MemberPress','pap-integrations'),
                'manage_options',
                'memberpressintegration-settings-page',
                array($this, 'printConfigPage')
                );
        }
    }

    public function printConfigPage() {
        $this->render();
        return;
    }

    public function MemberPressTrackSale($txn) {
        if (get_option(self::MEMBERPRESS_COMMISSION_ENABLED) !== 'true') {
            return false;
        }

        $accountID = '';
        $visitorID = '';
        if (isset($_REQUEST['pap_custom']) && ($_REQUEST['pap_custom'] != '')) {
            $visitorID = substr($_REQUEST['pap_custom'],-32);
        }
        if (isset($_REQUEST['pap_custom']) && ($_REQUEST['pap_custom'] != '')) {
            $accountID = substr($_REQUEST['pap_custom'],0,8);
        }

        $query = 'AccountId='.$accountID. '&visitorId='.$visitorID.
            '&TotalCost='.$txn->amount.'&ProductID='.$txn->product_id.'&OrderID=';

        if (isset($txn->subscription_id)) {
            $query .= $txn->subscription_id;
        } else {
            $query .= $txn->id;
        }
        if (get_option(self::MEMBERPRESS_ENABLE_LIFETIME) === 'true') {
            $query .= '&Data1='.$txn->user_id;
    	}
    	if (isset($_REQUEST['pap_IP'])) {
    	    $query .= '&ip='.$_REQUEST['pap_IP'];
    	}

        self::sendRequest(postaffiliatepro::parseSaleScriptPath(), $query);
        return $txn;
    }

    public function addHiddenFieldToPaymentForm($return = false) {
        $result = '<!-- Post Affiliate Pro integration snippet -->';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $result .= '<input type="hidden" name="pap_IP" value="'.$_SERVER['REMOTE_ADDR'].'" />';
        }
        $result .= '<input type="hidden" name="pap_custom" value="" id="pap_dx8vc2s5" />'.
            postaffiliatepro::getPAPTrackJSDynamicCode().'
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

    public function MemberPressRecurringSale(MeprEvent $event) {
        $txn = new MeprTransaction($event->evt_id);
        if (get_option(self::MEMBERPRESS_TRACK_RECURRING) !== 'true') {
            $this->_log(__('Recurring commissions are not enabled, ending'));
            return false;
        }

        // try to recurr a commission with order ID $txn->subscription_id
        $session = $this->getApiSession();
        if ($session === null) {
            $this->_log(__('We have no session to PAP installation! Recurring commission failed.'));
            return $renewal_order;
        }

        if (!$this->fireRecurringCommissions($txn->subscription_id, $session)) {
            // creating recurring commissions failed, create a new commission instead
            $this->_log(__('Creating a new commission with order ID %s',$txn->subscription_id));
            $this->MemberPressTrackSale($txn);
        }
    }
}

$submenuPriority = 75;
$integration = new postaffiliatepro_Form_Settings_MemberPress();
add_action('admin_init', array($integration, 'initSettings'), 99);
add_action('admin_menu', array($integration, 'addPrimaryConfigMenu'), $submenuPriority);

add_action('mepr-signup', array($integration, 'MemberPressTrackSale'));
add_action('mepr-checkout-before-submit', array($integration, 'addHiddenFieldToPaymentForm'));
add_action('mepr-event-recurring-transaction-completed', array($integration, 'MemberPressRecurringSale'), 99, 1);