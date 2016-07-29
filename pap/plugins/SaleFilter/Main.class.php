<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Milos Jancovic
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliate
 */
class SaleFilter_Main extends Gpf_Plugins_Handler {

    /**
     * @return SaleFilter_Main
     */
    public static function getHandlerInstance() {
        return new SaleFilter_Main();
    }

    public function initFields(Pap_Merchants_Campaign_CampaignDetailsAdditionalForm $additionalDetails) {
        $additionalDetails->addTextBoxWithDefault($this->_('Minimum total cost'), SaleFilter_Definition::NAME_MINIMUM_TOTALCOST,
        0, $this->_('Undefined'), $this->_("Commission will be value from setting 'Commission if below minimum' for sales that don't reach minimum total cost value"));

        $additionalDetails->addTextBoxWithDefault($this->_('Commission if below minimum'), SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM,
                0, $this->_('Undefined'), $this->_("Minimum commission value if total cost is below minimum, use '%' for percentage commission from totalcost e.g. 5%"));

        $additionalDetails->addTextBoxWithDefault($this->_('Maximum total cost'), SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST,
        -1, $this->_('Undefined'), $this->_("Commission will be value from setting 'Commission if over maximum' for sales that exceed maximum total cost value"));

        $additionalDetails->addTextBoxWithDefault($this->_('Commission if over maximum'), SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM,
                0, $this->_('Undefined'), $this->_("Maximum commission value if total cost is over maximum, use '%' for percentage commission from totalcost e.g. 5%"));

        $additionalDetails->addCheckBox($this->_('Custom commission based on remnant'), SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT,
                $this->_("If this option is enabled, the 'custom commission' will be calculated based on residual value of tracked Total Cost less the Maximum Total Cost<br>example:<br>'campaign commission' is 30%<br>'Maximum total cost' is 100<br>'Commission if over maximum' is 10%<br>when new sale is created with totalcost $150, commission is computed 30% from $100 + 10% from $50 = $35 (without this checkbox commission is 10% from 150)"));
    }

    public function save(Gpf_Rpc_Form $form) {
        $attribute = $this->createCampaignAttribute();
        $attribute->setName(SaleFilter_Definition::NAME_MINIMUM_TOTALCOST);
        $attribute->setCampaignId($form->getFieldValue('Id'));
        $attribute->setValue($form->getFieldValue(SaleFilter_Definition::NAME_MINIMUM_TOTALCOST));
        $attribute->save();
        
        $attribute = $this->createCampaignAttribute();
        $attribute->setName(SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST);
        $attribute->setCampaignId($form->getFieldValue('Id'));
        $attribute->setValue($form->getFieldValue(SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST));
        $attribute->save();
        
        $attribute = $this->createCampaignAttribute();
        $attribute->setName(SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM);
        $attribute->setCampaignId($form->getFieldValue('Id'));
        $attribute->setValue($form->getFieldValue(SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM));
        $attribute->save();
        
        $attribute = $this->createCampaignAttribute();
        $attribute->setName(SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM);
        $attribute->setCampaignId($form->getFieldValue('Id'));
        $attribute->setValue($form->getFieldValue(SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM));
        $attribute->save();
        
        $attribute = $this->createCampaignAttribute();
        $attribute->setName(SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT);
        $attribute->setCampaignId($form->getFieldValue('Id'));
        $attribute->setValue($form->getFieldValue(SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT));
        $attribute->save();
    }

    public function load(Gpf_Rpc_Form $form) {
        try {
            $form->setField(SaleFilter_Definition::NAME_MINIMUM_TOTALCOST,
            $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::NAME_MINIMUM_TOTALCOST, $form->getFieldValue('Id')));
            $form->setField(SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST,
            $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST, $form->getFieldValue('Id')));
            $form->setField(SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM,
                    $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM, $form->getFieldValue('Id')));
            $form->setField(SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM,
                    $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM, $form->getFieldValue('Id')));
            $form->setField(SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT,
                    $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT, $form->getFieldValue('Id')));
        } catch (Gpf_DbEngine_NoRowException $e) {
        }
    }

    public function updateCommission(Pap_Common_Transaction $transaction) {
    	if ($transaction->getType() != Pap_Db_Transaction::TYPE_SALE) {
    	    return;
    	}
        try {
            $minTotalCost = $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::NAME_MINIMUM_TOTALCOST, $transaction->getCampaignId());
            if ($transaction->getTotalCost() < $minTotalCost) {
                if ($transaction->getTier() > 1) {
                    $transaction->setCommission(0);
                    return;
                }
                try {
                    $newCommissionValue = $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::COMMISSION_IF_BELOW_MINIMUM, $transaction->getCampaignId());
                } catch (Gpf_DbEngine_NoRowException $e) {
                    $transaction->setCommission(0);
                    return;
                }
                $type = $this->getParameterType($newCommissionValue);
                $newCommissionValue = $this->makeCorrections($newCommissionValue);
                $commissionNew = new Pap_Tracking_Common_Commission($transaction->getTier(), $type, $newCommissionValue);
                
                $transaction->setCommission($commissionNew->getCommission($transaction->getTotalCost()-$transaction->getFixedCost()));
                return;
            }
        } catch (Gpf_DbEngine_NoRowException $e) {
        }
        try {
            $maxTotalCost = $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::NAME_MAXIMUM_TOTALCOST, $transaction->getCampaignId());
            if ($maxTotalCost > 0 && $transaction->getTotalCost() > $maxTotalCost) {
                if ($transaction->getTier() > 1) {
                    $transaction->setCommission(0);
                    return;
                }
                try {
                    $remnantSetting = $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::CUSTOM_COMMISSION_REMNANT, $transaction->getCampaignId());
                    $isCustomCommissionFromRemnant = $remnantSetting == Gpf::YES;
                } catch (Gpf_DbEngine_NoRowException $e) {
                    $isCustomCommissionFromRemnant = false;
                }
                try {
                    $newCommissionValue = $this->createCampaignAttribute()->getSetting(SaleFilter_Definition::COMMISSION_IF_OVER_MAXIMUM, $transaction->getCampaignId());
                } catch (Gpf_DbEngine_NoRowException $e) {
                    $transaction->setCommission(0);
                    return;
                }
                $type = $this->getParameterType($newCommissionValue);
                $newCommissionValue = $this->makeCorrections($newCommissionValue);
                $commissionRemnant = new Pap_Tracking_Common_Commission($transaction->getTier(), $type, $newCommissionValue);
                
                if ($isCustomCommissionFromRemnant) {
                    $oldCommission = $this->getCommissionForTransaction($transaction);
                    $commission = new Pap_Tracking_Common_Commission($transaction->getTier(), $oldCommission->get('commissiontype'), $oldCommission->get('commissionvalue'));
                    if ($transaction->getTotalCost()-$transaction->getFixedCost() <= $maxTotalCost) {
                        $transaction->setCommission($commission->getCommission($transaction->getTotalCost()-$transaction->getFixedCost()));
                    } else {
                        $transaction->setCommission($commission->getCommission($maxTotalCost)+$commissionRemnant->getCommission($transaction->getTotalCost()-$transaction->getFixedCost()-$maxTotalCost));
                    }
                } else {
                    $transaction->setCommission($commissionRemnant->getCommission($transaction->getTotalCost()-$transaction->getFixedCost()));
                }

            }
        } catch (Gpf_DbEngine_NoRowException $e) {
        }
    }

    /**
     * @return Pap_Db_CampaignAttribute
     */
    private function createCampaignAttribute() {
        return new Pap_Db_CampaignAttribute();
    }
    
    private function getParameterType($value) {
        $type = '$';
        if(strpos($value, '%') !== false) {
            $type = '%';
        }
        return $type;
    }
    
    private function makeCorrections($value) {
        $value = str_replace('%', '', $value);
        $value = str_replace('$', '', $value);
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);
        return $value;
    }
    
    /**
     * @param Pap_Db_Transaction $transaction
     * @return Pap_Db_Commission
     */
    private function getCommissionForTransaction(Pap_Db_Transaction $transaction) {
        $commission = new Pap_Db_Commission();
        $commission->setCommissionTypeId($transaction->getCommissionTypeId());
        $commission->setGroupId($transaction->getCommissionGroupId());
        $commission->setTier($transaction->getTier());
        $commission->setSubtype(Pap_Db_Table_Commissions::SUBTYPE_NORMAL);
        try {
            $commission->loadFromData(array(Pap_Db_Table_Commissions::TYPE_ID, Pap_Db_Table_Commissions::GROUP_ID, Pap_Db_Table_Commissions::TIER, Pap_Db_Table_Commissions::SUBTYPE));
        } catch (Gpf_Exception $e) {
            $userInGroup = Pap_Db_Table_UserInCommissionGroup::getInstance()->getUserCommissionGroup($transaction->getUserId(), $transaction->getCampaignId());
            $commission->setGroupId($userInGroup->getCommissionGroupId());
            try {
                $commission->loadFromData(array(Pap_Db_Table_Commissions::TYPE_ID, Pap_Db_Table_Commissions::GROUP_ID, Pap_Db_Table_Commissions::SUBTYPE, Pap_Db_Table_Commissions::TIER));
            } catch (Gpf_Exception $e) {
                throw new Gpf_Exception($this->_('Unable to find commision for transaction id=' . $transaction->getId()));
            }
        }
    
        return $commission;
    }
}
?>
