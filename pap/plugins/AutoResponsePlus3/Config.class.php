<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class AutoResponsePlus3_Config extends Gpf_Plugins_Config {

    const ARP_URL = 'AutoResponsePlus3FormURL';
    const ARP_FORM_ID = 'AutoResponsePlus3AutoresponderID';

    protected function initFields() {
        $this->addTextBox($this->_('AutoResponsePlus form URL'), self::ARP_URL, $this->_('AutoResponse Plus form processing script (arp3-formcapture.pl) e.g. http://www.yoursite.com/cgi-bin/arp3/arp3-formcapture.pl'));
        $this->addTextBox($this->_('Autoresponder ID'), self::ARP_FORM_ID, $this->_('An ID of the autoresponder you want your affiliates to be added to. You can find the ID in the list of Autoresponders in your admin panel, in the number sign (#) column.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::ARP_URL, $form->getFieldValue(self::ARP_URL));
        Gpf_Settings::set(self::ARP_FORM_ID, $form->getFieldValue(self::ARP_FORM_ID));
        $form->setInfoMessage($this->_('AutoResponsePlus3 plugin configuration saved.'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::ARP_URL, Gpf_Settings::get(self::ARP_URL));
        $form->addField(self::ARP_FORM_ID, Gpf_Settings::get(self::ARP_FORM_ID));
        return $form;
    }
}

?>
