<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:10
         compiled from campaign_new_result.js */ ?>
<?php echo '

function campaign_hosted_checkapproval() {
	if ( !$("approvalqueue_waiting") ) return;
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_hostedstatus", adesk_ajax_cb(campaign_hosted_checkapproval_cb), campaign_obj.id);
}

function campaign_hosted_checkapproval_cb(ary) {
	if (ary.waiting) {
		window.setTimeout(\'campaign_hosted_checkapproval()\', 3000);
		return;
	}

	if (ary.approved) {
		$("approvalqueue_waiting").hide();
		$("approvalqueue_sending").show();
	} else {
		$("approvalqueue_waiting").hide();
		switch (ary.message) {
			case "approved":
				$("approvalqueue_sending").show();
				break;

			case "declined":
				$("approvalqueue_declined").show();
				break;

			case "moreinfo":
				$("approvalqueue_moreinfo").show();
				break;

			case "pending":
				$("approvalqueue_pending").show();
				break;

			default:		// Really shouldn\'t get here.
				$("approvalqueue_waiting").show();
				break;
		}
	}
}

'; ?>
