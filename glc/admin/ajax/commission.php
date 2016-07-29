<?php
// This file will perform ajax requests for Merchant
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$action = (isset($_POST['action'])) ? $_POST['action'] : '';
$income_class = getInstance('Class_Income');

if($action === 'get_commission'):
	$commission_id = $_POST['commission_id'];
	$commission = $income_class->get_commission($commission_id);
	if(empty($commission)) die(json_encode(array('type' => 'error', 'message' => 'Commission not found.')));

	$commission = $commission[0];
	ob_start();
	?>		
		<div class="commission_fields">
			<div class="input-group">
				<br>
				<label class="control-label">Member Commission</label>
	        	<input type="text" placeholder="Commission ID" id="member_commission" name="member_commission" class="form-control" value="<?php echo number_format($commission['amount'], 2) ?>">
        	</div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Re Enter</label>
	        	<input type="text" placeholder="Commission ID" id="re_enter_commission" name="re_enter_commission" class="form-control" value="<?php echo number_format($commission['reenter'], 2) ?>">
	        </div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Advanced Commission</label>
	        	<input type="text" placeholder="Commission ID" id="advanced_commission" name="advanced_commission" class="form-control" value="<?php echo ($commission['other_type'] === 'advanced comm') ? number_format($commission['other'], 2) : '0.00' ?>">
        	</div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Forfeited Commission</label>
	        	<input type="text" placeholder="Commission ID" id="forfeited_commission" name="forfeited_commission" class="form-control" value="<?php echo ($commission['other_type'] === 'less than 2 qp') ? number_format($commission['other'], 2) : '0.00' ?>">
        	</div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Blocked Commission</label>
	        	<input type="text" placeholder="Commission ID" id="blocked_commission" name="blocked_commission" class="form-control" value="<?php echo ($commission['other_type'] === 'blocked member') ? number_format($commission['other'], 2) : '0.00' ?>">
        	</div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Company Commission</label>
	        	<input type="text" placeholder="Commission ID" id="company_commission" name="company_commission" class="form-control" value="<?php echo number_format($commission['co_comm'], 2) ?>">
        	</div>
        	<div class="input-group">
        		<br>
	        	<label class="control-label">Rolling Reserve</label>
	        	<input type="text" placeholder="Commission ID" id="rolling_reserve" name="rolling_reserve" class="form-control" value="<?php echo number_format($commission['reserve'], 2) ?>">
	        	<input type="hidden" name="commission_id" value="<?php echo $commission['income_id'] ?>">
        	</div>
        </div>
	<?php
	$form = ob_get_clean();
	echo json_encode(array('type' => 'success', 'message' => $commission, 'form' => $form));
    die();
endif;

if($action === 'update_commission'):
	parse_str($_POST['fields'], $fields);
	
	$data = array(
		'income_id' => $fields['commission_id'],
		'amount' 	=> $fields['member_commission'],
		'reenter' 	=> $fields['re_enter_commission'],
		'co_comm' 	=> $fields['company_commission'],
		'other' 	=> 0,
		'other_type' => '',
		'rolling_reserve' => $fields['rolling_reserve'],
	);

	if($fields['member_commission'] > 0):
		$data['other_type'] = '';
	elseif($fields['advanced_commission'] > 0):
		$data['other_type'] = 'advanced comm';
		$data['other'] = $fields['advanced_commission'];
	elseif($fields['forfeited_commission'] > 0):
		$data['other_type'] = 'less than 2 qp';
		$data['other'] = $fields['forfeited_commission'];
	elseif($fields['blocked_commission'] > 0):
		$data['other_type'] = 'blocked member';
		$data['other'] = $fields['blocked_commission'];
	endif;

	$update_income = $income_class->update_income($data);
	if($fields['rolling_reserve'] > 0 && $fields['member_commission'] > 0) $update_rolling_reserve = $income_class->update_rolling_reserve($data);

	echo json_encode(array('type' => 'success'));
	die();
endif;