<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package GwtPhpFramework
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package GwtPhpFramework
 */
class RocketGate_Config extends Gpf_Plugins_Config {
    const CUSTOM_SEPARATOR = 'RocketGateCustomSeparator';

    protected function initFields() {
        $this->addTextBox($this->_("Visitor ID value separator"), self::CUSTOM_SEPARATOR, $this->_("Visitor ID value separator should be set only in case 'pm' variable is already used by other script."));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::CUSTOM_SEPARATOR, $form->getFieldValue(self::CUSTOM_SEPARATOR));
        $form->setInfoMessage($this->_('RocketGate settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::CUSTOM_SEPARATOR, Gpf_Settings::get(self::CUSTOM_SEPARATOR));
        return $form;
    }
}

?>
