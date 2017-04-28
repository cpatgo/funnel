<?php
if (!class_exists('postaffiliatepro_Base')) {
    class postaffiliatepro_Base {
        const IMG_PATH = 'resources/img/';
        const URL_SEPARATOR = '/';
        const CSS_PATH = 'resources/css/';

        private static $session = null;
        private static $campaignHelper = null;
        private $error = '';

        public static function _log($message) {
            if( WP_DEBUG === true ){
                if( is_array( $message ) || is_object( $message ) ){
                    $message = print_r( $message, true );
                }
                $message = 'PostAffiliatPro Wordpress plugin log: ' . $message;
                error_log($message);
                echo $message;
            }
        }

        /**
         * @return postaffiliatepro_Util_CampaignHelper
         */
        protected function getCampaignHelper() {
            if (self::$campaignHelper) {
                return self::$campaignHelper;
            }
            self::$campaignHelper = new postaffiliatepro_Util_CampaignHelper();
            return self::$campaignHelper;
        }

        public static function getAccountName() {
            if (get_option(postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME) == '') {
                return postaffiliatepro::DEFAULT_ACCOUNT_NAME;
            }
            return get_option(postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME);
        }

        public function getError() {
            return $this->error;
        }

        protected function getPapVersion () {
            $url = get_option(postaffiliatepro::PAP_URL_SETTING_NAME);
            if (substr($url, -1) != '/') {
                $url .= '/';
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . 'api/version.php');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($result);
            if (!$xml) {
                $msg = '';
                foreach(libxml_get_errors() as $error) {
                    $msg .= $error->message;
                }
                self::_log('Unable to parse application version number: ' . $msg);
                return _('unknown (possible less than 4.5.48.1)').$msg;
            }
            return (string) $xml->applications->pap->versionNumber;
        }

        protected function getApiSessionUrl() {
            $url = get_option(postaffiliatepro::PAP_URL_SETTING_NAME);
            if (substr($url, -1) != '/') {
                $url .= '/';
            }
            return $url . 'scripts/server.php';
        }

        /**
         * @return Gpf_Api_Session
         */
        protected function getApiSession() {
            if (self::$session !== null) {
                return self::$session;
            }
            $session = new Gpf_Api_Session($this->getApiSessionUrl());
            try {
                $login = $session->login(get_option(postaffiliatepro::PAP_MERCHANT_NAME_SETTING_NAME), get_option(postaffiliatepro::PAP_MERCHANT_PASSWORD_SETTING_NAME));
            } catch (Gpf_Api_IncompatibleVersionException $e) {
                $this->error = 'Unable to login into PAP installation because of icompatible versions (probably your API file here in WP installation is older than your PAP installation)';
                self::_log(__($this->error));
                return null;
            }
            if($login == false) {
                $this->error = $session->getMessage();
                self::_log(__('Unable to login into PAP installation with given credentails: ' . $session->getMessage()));
                return null;
            }
            self::$session = $session;
            return $session;
        }

        public function changeOrderStatus($orderId, $status) {
            $session = $this->getApiSession();
            if ($session === null) {
                self::_log(__('We have no session to PAP installation! Transaction status change failed.'));
                return;
            }
            $ids = $this->getTransactionIDsByOrderID($orderId, $session);
            if (empty($ids)) {
                // try unprocessed transactions here
                $transactions = new Pap_Api_TransactionsGrid($session);
                if (!method_exists($transactions,'approveByOrderId')) {
                    self::_log(__('Your API version is old (you can update it), we cannot change status of unprocessed transactions with it. Ending'));
                    return false;
                }

                if ($status == 'A') { // for approval
                    self::_log('Order '.$orderId.' will be approved.');
                    for ($i = 0; $i < 30; $i++) {
                        $transaction->setOrderId("$orderId($i)");
                        $transaction->approveByOrderId();
                    }
                }
                elseif ($status == 'D') { // for declining
                    self::_log('Order '.$orderId.' will be declined.');
                    for ($i = 0; $i < 30; $i++) {
                        $transaction->setOrderId("$orderId($i)");
                        $transaction->declineByOrderId();
                    }
                }
                self::_log(__('Unprocessed transactions have been changed'));
                return true;
            }

            $request = new Gpf_Rpc_FormRequest('Pap_Merchants_Transaction_TransactionsForm', 'changeStatus', $session);
            $request->addParam('ids',new Gpf_Rpc_Array($ids));
            $request->addParam('status',$status);
            try {
                $request->sendNow();
            } catch (Exception $e) {
                self::_log(__('A problem occurred while transaction status change with API: '.$e->getMessage()));
                return false;
            }

            return true;
        }

        public function getTransactionIDsByOrderID($orderId, $session, $limit = 100) {
            $ids = array();
            if (($orderId == '') || $orderId == null) {
                return $ids;
            }
            $request = new Pap_Api_TransactionsGrid($session);
            $request->addFilter('orderid', Gpf_Data_Filter::LIKE, $orderId.'(%');
            $request->setLimit(0, $limit);
            if ($limit == 1) {
                $request->addParam('sort_col', 'dateinserted');
                $request->addParam('sort_asc', 'false');
                $request->addFilter('rstatus','IN','A,P');
            }

            try {
                $request->sendNow();
                $grid = $request->getGrid();
                $recordset = $grid->getRecordset();
                foreach($recordset as $rec) {
                    $ids[] = $rec->get('id');
                }
            } catch (Exception $e) {
                self::_log(__('A problem occurred while loading transactions with API: ').$e->getMessage());
            }
            return $ids;
        }

        public function fireRecurringCommissions($orderId, $session) {
            $recurringCommission = new Pap_Api_RecurringCommission($session);
            $recurringCommission->setOrderId($orderId);
            try {
                $recurringCommission->createCommissions();
            } catch (Exception $e) {
                self::_log(__('Can not process recurring commission: ').$e->getMessage());
                return false;
            }
            return true;
        }

        public function isPluginSet() {
            return (get_option(postaffiliatepro::PAP_MERCHANT_NAME_SETTING_NAME) != '' && get_option(postaffiliatepro::PAP_MERCHANT_PASSWORD_SETTING_NAME) != '');
        }

        protected function getImgUrl() {
            return WP_PLUGIN_URL . self::URL_SEPARATOR . PAP_PLUGIN_NAME . self::URL_SEPARATOR . self::IMG_PATH;
        }

        protected function getCssUrl() {
            return WP_PLUGIN_URL . self::URL_SEPARATOR . PAP_PLUGIN_NAME . self::URL_SEPARATOR . self::CSS_PATH;
        }

        protected function getStylesheetHeaderLink($filename) {
            return '<link type="text/css" rel="stylesheet" href="' . $this->getCssUrl() . $filename . '?ver=' . PAP_PLUGIN_VERSION . '" />' . "\n";
        }

        public static function sendRequest($url, $query = null, $method = 'GET') {
        	$curl = curl_init();
        	if ($method == 'POST') {
        		curl_setopt($curl, CURLOPT_POST, 1);
        		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        		curl_setopt($curl, CURLOPT_URL, str_replace('https://','http://',$url));
        	}
        	else {
        		if (is_array($query)) {
        			$query = http_build_query($query);
        		}
        		curl_setopt($curl, CURLOPT_URL, $url.((strpos($url, '?') === false)?'?':'&').$query);
        	}
        	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        	$response = curl_exec($curl);
        	$error = curl_error($curl);
        	curl_close($curl);
        	if (!$response) {
        		self::_log($error);
        		return false;
        	} else {
        		return true;
        	}
        }
    }
}