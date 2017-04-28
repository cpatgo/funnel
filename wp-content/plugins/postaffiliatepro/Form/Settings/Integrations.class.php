<?php
class postaffiliatepro_Form_Settings_Integrations extends postaffiliatepro_Form_Base {
    public function __construct() {
        parent::__construct(postaffiliatepro::INTEGRATIONS_SETTINGS_PAGE_NAME, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/IntegrationsConfig.xtpl';
    }

    protected function initForm() {
        if (!postaffiliatepro_Util_ContactForm7Helper::formsExists()) {
            $this->addCheckbox(postaffiliatepro_Form_Settings_ContactForm7::CONTACT7_SIGNUP_COMMISSION_ENABLED, null, ' disabled');
            $this->addHtml('contact7-signup-note', '<tr><td colspan="2" style="padding-top:0px;padding-bottom:15px;color:#750808;">No forms exist!</td></tr>');
        } else {
            $this->addCheckbox(postaffiliatepro_Form_Settings_ContactForm7::CONTACT7_SIGNUP_COMMISSION_ENABLED);
        }
        // JotForm
        $this->addCheckbox(postaffiliatepro_Form_Settings_JotForm::JOTFORM_COMMISSION_ENABLED);
        // Marketpress
        $this->addCheckbox(postaffiliatepro_Form_Settings_Marketpress::MARKETPRESS_COMMISSION_ENABLED);
        // MemberPress
        $this->addCheckbox(postaffiliatepro_Form_Settings_MemberPress::MEMBERPRESS_COMMISSION_ENABLED);
        // s2Member
        $this->addCheckbox(postaffiliatepro_Form_Settings_S2Member::S2MEMBER_COMMISSION_ENABLED);
        // Simple Pay Pro
        $this->addCheckbox(postaffiliatepro_Form_Settings_SimplePayPro::SIMPLEPAYPRO_COMMISSION_ENABLED);
        // WishList Member
        $this->addCheckbox(postaffiliatepro_Form_Settings_WishListMember::WLM_COMMISSION_ENABLED);
        // WooComm
        $this->addCheckbox(postaffiliatepro_Form_Settings_WooComm::WOOCOMM_COMMISSION_ENABLED);

        $this->addSubmit();
    }
}