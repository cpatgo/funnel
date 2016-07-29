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
 * @package PostAffiliatePro
 */
class SaleFilter_Definition extends Gpf_Plugins_Definition {
	
	const NAME_MINIMUM_TOTALCOST = 'minimumTotalcost';
	const NAME_MAXIMUM_TOTALCOST = 'maximumTotalcost';
	const COMMISSION_IF_OVER_MAXIMUM = 'replaceTotalcostOverMaximum';
	const COMMISSION_IF_BELOW_MINIMUM = 'commissionIfBelowMinimum';
	const CUSTOM_COMMISSION_REMNANT = 'customCommissionRemnant';
	

    public function __construct() {
        $this->codeName =  'SaleFilter';
        $this->name = $this->_('Sale filter');

        $this->description = $this->_('Commission will be modified or not generated for sales that don\'t reach minimum total cost value or if exceeds maximum total cost. Maximum/minimum total cost is set in campaign editation');

        $this->version = '1.0.0';               
        $this->addRequirement('PapCore', '4.1.10.1');
        
        $this->addImplementation('PostAffiliate.CampaignDetailsAdditionalForm.initFields', 'SaleFilter_Main', 'initFields');
        $this->addImplementation('PostAffiliate.CampaignDetailsAdditionalForm.save', 'SaleFilter_Main', 'save');
        $this->addImplementation('PostAffiliate.CampaignDetailsAdditionalForm.load', 'SaleFilter_Main', 'load');
        $this->addImplementation('PostAffiliate.Transaction.beforeSave', 'SaleFilter_Main', 'updateCommission');
    }
}

?>
