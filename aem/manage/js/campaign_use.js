//var campaign_use_listfilter = {jsvar var=$listfilter};
//var campaign_id = {jsvar var=$campaignid};

var campaign_obj = {jsvar var=$campaign};

var campaign_sendat_str = '{"at"|alang|js}';
var campaign_sendspecial_str = '{"This campaign will be sent to every subscriber individually based on their subscription date/time."|alang|js}';
var campaign_rightnow_str = '{"Immediately"|alang|js}';
var campaign_noname_str = '{"Please select a name for this campaign before you continue"|alang|js}';


{literal}

function campaignname() {
	if ( $('summary_campaign_label_box').style.display == 'none' ) {
		var val = adesk_str_trim($('summary_campaign_input').value);
		if ( val == '' ) {
			$('summary_campaign_label').innerHTML = '<em>' + jsNone + '</em>';
		} else {
			$('summary_campaign_label').innerHTML = val;
		}

	}
	$('summary_campaign_label_box').toggle();
	$('summary_campaign_input_box').toggle();
}

function form_check() {
	var val = adesk_str_trim($('summary_campaign_input').value);
	if ( val == '' ) {
		// open editor
		if ( $('summary_campaign_input_box').style.display == 'none' ) campaignname();
		alert(campaign_noname_str);
		$('summary_campaign_input').focus();
		return false;
	}
	return true;
}


function campaign_use_onload() {
	pageLoaded = true;
}

adesk_dom_onload_hook(campaign_use_onload);

{/literal}
