<?php
/**
 *   @copyright Copyright (c) 2015 Quality Unit s.r.o.
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
class OptimizeMember_Config extends Gpf_Plugins_Config {
    const SECRET_WORD = 'OptimizeMemberSecretWord';
    const REGISTER_AFFILIATE = 'OptimizeMemberRegAffiliate';
    const ONLY_MATCHED_RECURRENCE = 'OptimizeMemberOnlyMatchedRecurrence';

    protected function initFields() {
        $this->addTextBox($this->_('Secret word'), self::SECRET_WORD, $this->_('Enter your sercret word here, see integration steps for OptimizeMember.'));
        $this->addCheckBox($this->_('Register new affiliate'), self::REGISTER_AFFILIATE, $this->_('When this is checked, the plugin will try to create an affiliate account based on information sent from OptimizeMember. Requires further setting up, see the integration method.'));
        $this->addCheckBox($this->_('Only matched recurrence'), self::ONLY_MATCHED_RECURRENCE, $this->_('If an initial payment was not tracked, a recurring commission will not be created as well. By disabling this option, you will be able to track such a recurring commission if it was referred.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::SECRET_WORD, $form->getFieldValue(self::SECRET_WORD));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::ONLY_MATCHED_RECURRENCE, $form->getFieldValue(self::ONLY_MATCHED_RECURRENCE));

        if (Gpf_Settings::get(self::SECRET_WORD) == '') {
            $form->setErrorMessage($this->_('You have to enter a secret word!'));
        }
        $form->setInfoMessage($this->_('OptimizeMember settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::SECRET_WORD, Gpf_Settings::get(self::SECRET_WORD));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::ONLY_MATCHED_RECURRENCE, Gpf_Settings::get(self::ONLY_MATCHED_RECURRENCE));

        return $form;
    }
}
