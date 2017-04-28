<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package WpPostAffiliateProPlugin
 *   @since version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_WooComm extends postaffiliatepro_Form_Base {
    const WOOCOMM_COMMISSION_ENABLED = 'woocomm-commission-enabled';
    const WOOCOMM_CONFIG_PAGE = 'woocomm-config-page';
    const WOOCOMM_PERPRODUCT = 'woocomm-per-product';
    const WOOCOMM_PRODUCT_ID = 'woocomm-product-id';
    const WOOCOMM_DATA1 = 'woocomm-data1';
    const WOOCOMM_CAMPAIGN = 'woocomm-campaign';
    const WOOCOMM_STATUS_UPDATE = 'woocomm-status-update';

    public function __construct() {
        parent::__construct(self::WOOCOMM_CONFIG_PAGE, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/WooCommConfig.xtpl';
    }

    protected function initForm() {
        $this->addCheckbox(self::WOOCOMM_PERPRODUCT);
        $this->addSelect(self::WOOCOMM_PRODUCT_ID, array('0' => ' ', 'id' => 'product ID', 'sku' => 'SKU', 'categ' => 'product category', 'role' => 'user role'));
        $this->addCheckbox(self::WOOCOMM_STATUS_UPDATE);
        $this->addSelect(self::WOOCOMM_DATA1, array('0' => ' ', 'id' => 'customer ID', 'email' => 'customer email'));

        $campaignHelper = new postaffiliatepro_Util_CampaignHelper();
        $campaignList = $campaignHelper->getCampaignsList();

        $campaigns = array('0' => ' ');
        foreach ($campaignList as $row) {
        	$campaigns[$row->get('campaignid')] = $row->get('name');
        }
        $this->addSelect(self::WOOCOMM_CAMPAIGN, $campaigns);

        $this->addSubmit();
    }

    public function initSettings() {
        register_setting(postaffiliatepro::INTEGRATIONS_SETTINGS_PAGE_NAME, self::WOOCOMM_COMMISSION_ENABLED);
        register_setting(self::WOOCOMM_CONFIG_PAGE, self::WOOCOMM_PERPRODUCT);
        register_setting(self::WOOCOMM_CONFIG_PAGE, self::WOOCOMM_PRODUCT_ID);
        register_setting(self::WOOCOMM_CONFIG_PAGE, self::WOOCOMM_STATUS_UPDATE);
        register_setting(self::WOOCOMM_CONFIG_PAGE, self::WOOCOMM_DATA1);
        register_setting(self::WOOCOMM_CONFIG_PAGE, self::WOOCOMM_CAMPAIGN);
    }

    public function addPrimaryConfigMenu() {
        if (get_option(self::WOOCOMM_COMMISSION_ENABLED) == 'true') {
            add_submenu_page(
                    'integrations-config-page-handle',
                    __('WooCommerce','pap-integrations'),
                    __('WooCommerce','pap-integrations'),
                    'manage_options',
                    'woocommintegration-settings-page',
                    array($this, 'printConfigPage')
                    );
        }
    }

    public function printConfigPage() {
      	$this->render();
      	return;
    }

    public function wooAddThankYouPageTrackSale($order_id) {
      	$order = wc_get_order($order_id);
      	if (get_option(self::WOOCOMM_COMMISSION_ENABLED) != 'true') {
      		echo "<!-- Post Affiliate Pro sale tracking error - tracking not enabled -->\n";
      		return $order_id;
      	}
      	if (empty($order)) {
      		echo '<!-- Post Affiliate Pro sale tracking error - no order loaded for order ID '.$order_id." -->\n";
      		return $order_id;
      	}
      	if (isset($_GET['customGateway'])) {
      		echo "<!-- Post Affiliate Pro sale tracking - no sale tracker needed -->\n";
      		return $order_id;
      	}
      	$this->trackWooOrder($order);

      	return $order_id;
    }

    private function trackWooOrder($order) {
		$orderId = $order->id;
		echo "<!-- Post Affiliate Pro sale tracking -->\n";
		echo postaffiliatepro::getPAPTrackJSDynamicCode();
		echo '<script type="text/javascript">';
		echo 'PostAffTracker.setAccountId(\''.postaffiliatepro::getAccountName().'\');';

		if (function_exists('wcs_get_subscriptions_for_order')) {
			$subscriptions = wcs_get_subscriptions_for_order($orderId);
			if (!empty($subscriptions)) {
				foreach($subscriptions as $key => $value) { // take the first and leave
					$orderId = $key;
					break;
				}
			}
		}

        if (get_option(self::WOOCOMM_PERPRODUCT) === 'true') {
            $i = 1;
      		foreach ($order->get_items() as $item) {
      			$itemprice = $item['line_total'];
      			$couponCode = '';

      			try { //if coupon has been used, set the last one in the setCoupon() parameter
      				$coupon = $order->get_used_coupons();
      				$couponToBeUsed = (count($coupon)>1 ? count($coupon)-1 : 0);

      				if (isset($coupon[$couponToBeUsed])) {
      					$itemcount = $order->get_item_count($type = '');
      					$orderdiscount = $order->get_order_discount();

      					if ($itemcount > 0) {
      						$discountperitem = $orderdiscount / $itemcount;
      						$itemprice = $item['line_total'] - $discountperitem;
      					}
      					$couponCode = $coupon[$couponToBeUsed];
      				}
      			}
      			catch (Exception $e) {
      				//echo "<!--Error: ".$e->getMessage()."-->";
      			}

      			echo "var sale$i = PostAffTracker.createSale();\n";
      			echo "sale$i.setTotalCost('".$itemprice."');\n";
      			echo "sale$i.setOrderID('$orderId($i)');\n";
      			echo "sale$i.setProductID('".$this->getTrackingProductID($order, $item)."');\n";
      			echo "sale$i.setCurrency('".$order->get_order_currency()."');\n";
      			echo "sale$i.setCoupon('".$couponCode."');\n";
      			echo "sale$i.setData1('".$this->getTrackingData1($order)."');\n";
      			if (get_option(self::WOOCOMM_CAMPAIGN) !== '' &&
      					get_option(self::WOOCOMM_CAMPAIGN) !== null &&
      					get_option(self::WOOCOMM_CAMPAIGN) !== 0 &&
      					get_option(self::WOOCOMM_CAMPAIGN) !== '0') {
      				echo "sale$i.setCampaignID('".get_option(self::WOOCOMM_CAMPAIGN)."');\n";
      			}
      			echo "PostAffTracker.register();\n";
      			$i++;
      		}
      	}
      	else {
      		echo "var sale = PostAffTracker.createSale();\n";
      		echo "sale.setTotalCost('".($order->order_total - $order->order_shipping)."');\n";
      		echo "sale.setOrderID('$orderId(1)');\n";
      		echo "sale.setCurrency('".$order->get_order_currency()."');\n";
      		echo "sale.setProductID('".$this->getTrackingProductIDsLine($order)."');\n";
      		echo "sale.setData1('".$this->getTrackingData1($order)."');\n";

      		if (get_option(self::WOOCOMM_CAMPAIGN) !== '' &&
      				get_option(self::WOOCOMM_CAMPAIGN) !== null &&
      				get_option(self::WOOCOMM_CAMPAIGN) !== 0 &&
      				get_option(self::WOOCOMM_CAMPAIGN) !== '0') {
      			echo "sale.setCampaignID('".get_option(self::WOOCOMM_CAMPAIGN)."');\n";
      		}
      		try {
      			$coupon = $order->get_used_coupons();
      			echo "sale.setCoupon('".$coupon[0]."');\n";
      		} catch (Exception $e) {
      			//
      		}
      		echo 'PostAffTracker.register();';
      	}
      	echo '</script>';
      	return true;
      }

    private function getTrackingProductID($order, $item) {
        $product = $order->get_product_from_item($item);

        switch (get_option(self::WOOCOMM_PRODUCT_ID)) {
            case 'id':
                return $product->id;
            case 'sku':
                if (!empty($product->sku)) {
                    return $product->sku;
                } else {
                    return $product->id;
                }
            case 'categ':
                $categories = explode(',',$product->get_categories(','));
                return $categories[0];
            case 'role':
                try {
                    $users = new WP_User($order->user_id);
                    if (isset($user->roles[0])) {
                        return $user->roles[0];
                    } else {
                        break;
                    }
                } catch (Exception $e){
                    break;
                }
        }
    	return '';
    }

    private function getTrackingProductIDsLine($order) {
    	$productSelection = get_option(self::WOOCOMM_PRODUCT_ID);
    	if (empty($productSelection)) {
    		return '';
    	}

    	$line = '';
    	foreach ($order->get_items() as $item) {
            $line .= $this->getTrackingProductID($order, $item).', ';
    	}
    	if (!empty($line)) {
        	$line = substr($line,0,-2);
    	}
    	return $line;
    }

    private function getTrackingData1($order) {
    	if (get_option(self::WOOCOMM_DATA1) === 'id') {
    		return $order->user_id;
    	}
    	if (get_option(self::WOOCOMM_DATA1) === 'email') {
    		return $order->billing_email;
    	}
    	return '';
    }

    public function wooOrderStatusChanged($orderId, $old_status, $new_status) {
    	if (get_option(self::WOOCOMM_STATUS_UPDATE) !== 'true') {
    		return false;
    	}

    	$this->_log(__('Received status %s', $new_status));

        switch ($new_status) {
            case 'completed':
                $status = 'A';
                break;
            case 'processing':
            case 'on-hold':
                $status = 'P';
                break;
            case 'cancelled':
            case 'failed':
                $status = 'D';
                break;
            case 'refunded':
                return $this->refundTransaction($orderId);
                break;
            default:
            	$status = '';
        }

        if ($status == '') {
        	$this->_log('Unsupported status '.$new_status);
        	return false;
        }

    	return $this->changeOrderStatus($orderId, $status);
    }

    private function refundTransaction($orderId) {
        $limit = 100;
        if (function_exists('wcs_get_subscriptions_for_order')) { // we will have to refund one of the recurring commissions
            $subscriptions = wcs_get_subscriptions_for_order($orderId);
            if (!empty($subscriptions)) {
                foreach($subscriptions as $key => $value) { // take the first and leave
                    $orderId = $key;
                    $limit = 1;
                    break;
                }
            }
        }

        $session = $this->getApiSession();
        if ($session === null) {
            $this->_log(__('We have no session to PAP installation! Transaction status change failed.'));
            return;
        }
        $ids = $this->getTransactionIDsByOrderID($orderId, $session, $limit);
        if (empty($ids)) {
            $this->_log(__('Nothing to change, the commission does not exist in PAP'));
            return true;
        }

        $request = new Gpf_Rpc_FormRequest('Pap_Merchants_Transaction_TransactionsForm', 'makeRefundChargeback', $session);
        $request->addParam('ids',new Gpf_Rpc_Array($ids));
        $request->addParam('status','R');
        $request->addParam('merchant_note','Refunded automatically from WooCommerce');
        try {
            $request->sendNow();
        } catch (Exception $e) {
            $this->_log(__('A problem occurred while transaction status change with API: ').$e->getMessage());
            return false;
        }

        return true;
    }

    public function wooSubscriptionStatusChanged($orderId, $old_status, $new_status) {
        if ($new_status != 'cancelled') {
            return false;
        }
        $session = $this->getApiSession();
        if ($session === null) {
            $this->_log(__('We have no session to PAP installation! Transaction status change failed.'));
            return;
        }
        // load recurring order ID
        $request = new Gpf_Rpc_GridRequest('Pap_Features_RecurringCommissions_RecurringCommissionsGrid', 'getRows', $session);
        $request->addFilter('orderid', 'L', $orderId.'(%');
        $recurringId = array();
        try {
            $request->sendNow();
            $grid = $request->getGrid();
            $recordset = $grid->getRecordset();
            foreach($recordset as $rec) {
                $recurringId[] = $rec->get('orderid');
            }
        } catch (Exception $e) {
            $this->_log(__('A problem occurred while transaction status change with API: ').$e->getMessage());
            return false;
        }

        if ($recurringId == '') {
            $this->_log(__('Nothing to change, the commission does not exist in PAP'));
            return false;
        }

        $request = new Gpf_Rpc_FormRequest('Pap_Features_RecurringCommissions_RecurringCommissionsForm', 'changeStatus', $session);
        $request->addParam('ids', new Gpf_Rpc_Array($ids));
        $request->addParam('status', 'D');
        try {
            $request->sendNow();
        } catch (Exception $e) {
            $this->_log(__('A problem occurred while transaction status change with API: ').$e->getMessage());
            return false;
        }

        return true;
    }

    public function wooRecurringCommission($renewal_order, $subscription) {
        if (!is_object($subscription)) {
  			$subscription = wcs_get_subscription($subscription);
  		}

  		if (!is_object($renewal_order)) {
  			$renewal_order = wc_get_order($renewal_order);
  		}

    	// try to recurr a commission with order ID $subscription->id
    	$session = $this->getApiSession();
    	if ($session === null) {
    		$this->_log(__('We have no session to PAP installation! Recurring commission failed.'));
    		return $renewal_order;
    	}

    	if (!$this->fireRecurringCommissions($subscription->id.'(1)',$session)) {
    	    // creating recurring commissions failed, create a new commission instead
    	    $this->_log(__('Creating new commissions with order ID ').$renewal_order->id.'(1)');
    	    $this->trackWooOrder($renewal_order);
    	}

    	return $renewal_order;
    }

    public function wooModifyPaypalArgs($array) {
        if (strpos($array['notify_url'], '?')) {
        	$array['notify_url'] .= '&';
        } else {
        	$array['notify_url'] .= '?';
        }
        $array['notify_url'] .= 'pap_custom='.$_REQUEST['pap_custom'];
        if (isset($_REQUEST['pap_IP'])) {
              $array['notify_url'] .= '&pap_IP='.$_REQUEST['pap_IP'];
        }
        if (strpos($array['return'], '?')) {
        	$array['return'] .= '&';
        } else {
        	$array['return'] .= '?';
        }
        $array['return'] .= 'customGateway=paypal';
        return $array;
    }

    public function wooProcessPaypalIPN($post_data) {
    	$post_data['payment_status'] = strtolower($post_data['payment_status']);
    	if (empty($post_data['custom'])) {
    		return false;
    	}
    	if (!$order = $this->get_paypal_order($post_data['custom'])) {
    		return false;
    	}

    	if ($post_data['payment_status'] == 'completed') {
			if (get_option(self::WOOCOMM_PERPRODUCT) === 'true') {
				foreach ($order->get_items() as $item) {
					$itemprice = $item['line_total'];
					$couponCode = '';

					try { //if coupon has been used, set the last one in the setCoupon() parameter
						$coupon = $order->get_used_coupons();
						$couponToBeUsed = (count($coupon)>1 ? count($coupon)-1 : 0);

						if (isset($coupon[$couponToBeUsed])) {
							$itemcount = $order->get_item_count($type = '');
							$orderdiscount = $order->get_order_discount();

							if ($itemcount > 0) {
								$discountperitem = $orderdiscount / $itemcount;
								$itemprice = $item['line_total'] - $discountperitem;
							}
							$couponCode = $coupon[$couponToBeUsed];
						}
					}
					catch (Exception $e) {
						//echo "<!--Error: ".$e->getMessage()."-->";
					}

					$query = 'AccountId='.substr($_GET['pap_custom'],0,8). '&visitorId='.substr($_GET['pap_custom'],-32);
					if (isset($_GET['pap_IP'])) {
    					$query .= '&ip='.$_GET['pap_IP'];
					}
					$query .= "&TotalCost=$itemprice&OrderID=".$order->id."($i)";
					$query .= '&ProductID='.urlencode($this->getTrackingProductID($order, $item));
					$query .= '&Currency='.$order->get_order_currency()."&Coupon=$couponCode";
					$query .= '&Data1='.urlencode($this->getTrackingData1($order));

					if (get_option(self::WOOCOMM_CAMPAIGN) !== '' &&
							get_option(self::WOOCOMM_CAMPAIGN) !== null &&
							get_option(self::WOOCOMM_CAMPAIGN) !== 0 &&
							get_option(self::WOOCOMM_CAMPAIGN) !== '0') {
						$query .= '&CampaignID='.get_option(self::WOOCOMM_CAMPAIGN);
					}

					self::sendRequest(postaffiliatepro::parseSaleScriptPath(), $query);
					$i++;
				}
			}
			else {
				$coupon = $order->get_used_coupons();
				$query = 'AccountId='.substr($_GET['pap_custom'],0,8). '&visitorId='.substr($_GET['pap_custom'],-32);
				if (isset($_GET['pap_IP'])) {
    				$query .= '&ip='.$_GET['pap_IP'];
				}
				$query .= '&TotalCost='.($order->order_total - $order->order_shipping).'&OrderID='.$order->id.'(1)';
				$query .= '&ProductID='.urlencode($this->getTrackingProductIDsLine($order));
				$query .= '&Currency='.$order->get_order_currency().'&Coupon='.$coupon[0];
				$query .= '&Data1='.urlencode($this->getTrackingData1($order));

				if (get_option(self::WOOCOMM_CAMPAIGN) !== '' &&
						get_option(self::WOOCOMM_CAMPAIGN) !== null &&
						get_option(self::WOOCOMM_CAMPAIGN) !== 0 &&
						get_option(self::WOOCOMM_CAMPAIGN) !== '0') {
					$query .= '&CampaignID='.get_option(self::WOOCOMM_CAMPAIGN);
				}

				self::sendRequest(postaffiliatepro::parseSaleScriptPath(), $query);
			}

			return true;
		}
    	return false;
    }

    private function get_paypal_order($raw_custom) {
    	if (($custom = json_decode($raw_custom)) && is_object($custom)) {
    		$order_id  = $custom->order_id;
    		$order_key = $custom->order_key;
    	}
    	elseif (preg_match('/^a:2:{/', $raw_custom) && !preg_match('/[CO]:\+?[0-9]+:"/', $raw_custom) &&
    			($custom = maybe_unserialize($raw_custom))) {
    		$order_id = $custom[0];
    		$order_key = $custom[1];
    	}
    	else {
    		$this->_log('PayPal IPN handling: Order ID and key were not found in "custom".');
    		return false;
    	}

    	if (!$order = wc_get_order($order_id)) {
    		// We have an invalid $order_id, probably because invoice_prefix has changed.
    		$order_id = wc_get_order_id_by_order_key($order_key);
    		$order = wc_get_order($order_id);
    	}

    	if (!$order || $order->order_key !== $order_key) {
    		$this->_log('PayPal IPN handling: Order keys do not match.');
    		return false;
    	}

    	return $order;
    }

    public function addHiddenFieldToPaymentForm($return = false) {
        return postaffiliatepro::addHiddenFieldToPaymentForm($return);
    }
}

$submenuPriority = 95;
$integration = new postaffiliatepro_Form_Settings_WooComm();
add_action('admin_init', array($integration, 'initSettings'), 99);
add_action('admin_menu', array($integration, 'addPrimaryConfigMenu'), $submenuPriority);

add_action('woocommerce_thankyou', array($integration, 'wooAddThankYouPageTrackSale'));
add_action('woocommerce_checkout_after_order_review', array($integration, 'addHiddenFieldToPaymentForm'));
add_action('woocommerce_order_status_changed', array($integration, 'wooOrderStatusChanged'), 99, 3);
add_action('woocommerce_subscription_status_changed', array($integration, 'wooSubscriptionStatusChanged'), 99, 3);
add_filter('wcs_renewal_order_created', array($integration, 'wooRecurringCommission'), 99, 2);
// WooCommerce PayPal
add_filter('woocommerce_paypal_args', array($integration, 'wooModifyPaypalArgs'), 99);
add_action('valid-paypal-standard-ipn-request', array($integration, 'wooProcessPaypalIPN'));