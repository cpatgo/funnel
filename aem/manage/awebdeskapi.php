<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

/*
	WHITELIST
*/
$allowed = array(
	'database.database_repair' => '',
	'database.database_optimize' => '',
	// settings
	'settings.settings_select_row' => '',
	'settings.settings_update_post' => '',
	'settings.settings_help_videos' => '',
	'settings.settings_gettingstarted_hide' => '',
	'settings.settings_sendlog_switch' => '',
	// branding
	'branding.branding_select_row' => '',
	'branding.branding_update_post' => '',
	'design.design_select_row' => '',
	'design.design_select_array' => '',
	'design.design_select_array_paginator' => '',
	'design.design_filter_post' => '',
     'design.design_insert_post' => '',
	 'design.design_update_post' => '',
	 'design.design_delete' => '',
	 'design.design_delete_multi' => '',
	// startup
	'startup.startup_recent_subscribers' => '',
	'startup.startup_recent_campaigns' => '',
	'startup.startup_viable' => '',
	'startup.startup_rewrite' => '',
	// list
	'list.list_delete' => '',
	'list.list_copy' => '',
	'list.list_delete_multi' => '',
	'list.list_filter_post' => '',
	'list.list_get_fields' => '',
	'list.list_insert_post' => '',
	'list.list_select_array' => '',
	'list.list_select_array_paginator' => '',
	'list.list_select_row' => '',
	'list.list_select_list' => '',
	'list.list_update_post' => '',
	'list.list_field_order' => '',
	'list.list_field_update' => '',
	'list.list_twitter_oauth_getaccesstoken' => '',
	'list.list_twitter_oauth_verifycredentials' => '',
	'list.list_twitter_token_mirror' => '',
	'list.list_twitter_oauth_init2' => '',
	'list.list_facebook_oauth_logout' => '',
	'list_field.list_field_insert_post' => '',
	'list_field.list_field_update_post' => '',
	'list_field.list_field_delete' => '',
	'list_field.list_field_select_nodata_rel' => '',
	// filter
	'filter.filter_listfilters' => '',
	'filter.filter_hide' => '',
	'filter.filter_hide_multi' => '',
	'filter.filter_delete' => '',
	'filter.filter_delete_multi' => '',
	'filter.filter_filter_post' => '',
	'filter.filter_insert_post' => '',
	'filter.filter_select_array' => '',
	'filter.filter_select_array_paginator' => '',
	'filter.filter_select_row' => '',
	'filter.filter_update_post' => '',
	'filter.filter_field_order' => '',
	'filter.filter_field_update' => '',
	'filter.filter_links' => '',
	'filter.filter_lists' => '',
	'filter.filter_analyze' => '',
	// header
	'header.header_delete' => '',
	'header.header_delete_multi' => '',
	'header.header_filter_post' => '',
	'header.header_insert_post' => '',
	'header.header_select_array' => '',
	'header.header_select_array_paginator' => '',
	'header.header_select_row' => '',
	'header.header_update_post' => '',
	// emailaccount
	'emailaccount.emailaccount_delete' => '',
	'emailaccount.emailaccount_delete_multi' => '',
	'emailaccount.emailaccount_filter_post' => '',
	'emailaccount.emailaccount_insert_post' => '',
	'emailaccount.emailaccount_select_array' => '',
	'emailaccount.emailaccount_select_array_paginator' => '',
	'emailaccount.emailaccount_select_row' => '',
	'emailaccount.emailaccount_update_post' => '',
	'emailaccount.emailaccount_run' => '',
	'emailaccount.emailaccount_log' => '',
	'emailaccount.emailaccount_log_select_row' => '',
	// subscriber
	'subscriber.subscriber_delete' => '',
	'subscriber.subscriber_select_fields' => '',
	'subscriber.subscriber_update_fields' => '',
	'subscriber.subscriber_delete_post' => '',
	'subscriber.subscriber_delete_multi' => '',
	'subscriber.subscriber_delete_multi_post' => '',
	'subscriber.subscriber_filter_post' => '',
	'subscriber.subscriber_insert_post' => '',
	'subscriber.subscriber_insert_post_web' => '',
	'subscriber.subscriber_select_array' => '',
	'subscriber.subscriber_select_array_alt' => '',
	'subscriber.subscriber_select_array_paginator' => '',
	'subscriber.subscriber_stats_array_paginator' => '',
	'subscriber.subscriber_select_row' => '',
	'subscriber.subscriber_update_post' => '',
	'subscriber.subscriber_bounce_reset' => '',
	'subscriber.subscriber_optin' => '',
	'subscriber.subscriber_optin_post' => '',
	'subscriber.subscriber_optin_multi' => '',
	'subscriber.subscriber_optin_multi_post' => '',
	'subscriber.subscriber_update_email' => '',
	'subscriber_autocomplete' => '',
	// subscriber: remote api stuff
	'subscriber.subscriber_view' => '',
	'subscriber.subscriber_view_hash' => '',
	'subscriber.subscriber_list' => '',
	//'subscriber.subscriber_select_row' => '',
	// subscriber_action
	'subscriber_action.subscriber_action_deleteparts' => '',
	'subscriber_action.subscriber_action_delete' => '',
	'subscriber_action.subscriber_action_delete_multi' => '',
	'subscriber_action.subscriber_action_filter_post' => '',
	'subscriber_action.subscriber_action_insert_post' => '',
	'subscriber_action.subscriber_action_select_array' => '',
	'subscriber_action.subscriber_action_select_array_paginator' => '',
	'subscriber_action.subscriber_action_select_row' => '',
	'subscriber_action.subscriber_action_update_post' => '',
	'subscriber.subscriber_view_unsubscribe' => '',
	'subscriber.subscriber_view_lists' => '',
	'subscriber.subscriber_view_unlists' => '',
	'subscriber.subscriber_view_subscribe' => '',
	// open
	'open.open_select_array_paginator' => '',
	'open.open_filter_post' => '',
	'open.open_select_totals' => '',
	'open.open_select_list' => '',
	// unopen
	'unopen.unopen_select_array_paginator' => '',
	'unopen.unopen_filter_post' => '',
	// unsubscriber
	'unsubscriber.unsubscriber_select_array_paginator' => '',
	'unsubscriber.unsubscriber_filter_post' => '',
	'unsubscriber.unsubscriber_select_totals' => '',
	'unsubscriber.unsubscriber_select_list' => '',
	// link
	'link.link_select_array_paginator' => '',
	'link.link_filter_post' => '',
	'link.link_select_totals' => '',
	'link.link_select_list' => '',
	// linkinfo
	'linkinfo.linkinfo_select_array_paginator' => '',
	'linkinfo.linkinfo_filter_post' => '',
	'linkinfo.linkinfo_select_totals' => '',
	'linkinfo.linkinfo_select_list' => '',
	// forward
	'forward.forward_select_array_paginator' => '',
	'forward.forward_filter_post' => '',
	'forward.forward_select_totals' => '',
	'forward.forward_select_list' => '',
	// update
	'update.update_select_array_paginator' => '',
	'update.update_filter_post' => '',
	'update.update_select_totals' => '',
	// socialsharing
	'socialsharing.socialsharing_data_fetch_read' => '',
	'socialsharing.socialsharing_data_cache_read' => '',
	'socialsharing.socialsharing_data_cache_write' => '',
	'socialsharing.socialsharing_select_totals' => '',
	'socialsharing.socialsharing_select_array_paginator' => '',
	'socialsharing.socialsharing_filter_post' => '',
	'socialsharing.socialsharing_select_list' => '',
	// bounce_data
	'bounce_data.bounce_data_select_array_paginator' => '',
	'bounce_data.bounce_data_filter_post' => '',
	'bounce_data.bounce_data_select_totals' => '',
	'bounce_data.bounce_data_select_list' => '',
	// exclusion
	'exclusion.exclusion_delete' => '',
	'exclusion.exclusion_delete_multi' => '',
	'exclusion.exclusion_filter_post' => '',
	'exclusion.exclusion_insert_post' => '',
	'exclusion.exclusion_select_array' => '',
	'exclusion.exclusion_select_array_paginator' => '',
	'exclusion.exclusion_select_row' => '',
	'exclusion.exclusion_update_post' => '',
	// bounce_management
	'bounce_management.bounce_management_delete' => '',
	'bounce_management.bounce_management_delete_multi' => '',
	'bounce_management.bounce_management_filter_post' => '',
	'bounce_management.bounce_management_insert_post' => '',
	'bounce_management.bounce_management_select_row' => '',
	'bounce_management.bounce_management_select_array' => '',
	'bounce_management.bounce_management_select_array_paginator' => '',
	'bounce_management.bounce_management_select_row_ajax' => '',
	'bounce_management.bounce_management_update_post' => '',
	'bounce_management.bounce_management_run' => '',
	'bounce_management.bounce_management_log' => '',
	'bounce_management.bounce_log_select_row' => '',
	// bounce_code
	'bounce_code.bounce_code_delete' => '',
	'bounce_code.bounce_code_delete_multi' => '',
	'bounce_code.bounce_code_filter_post' => '',
	'bounce_code.bounce_code_insert_post' => '',
	'bounce_code.bounce_code_select_array' => '',
	'bounce_code.bounce_code_select_array_paginator' => '',
	'bounce_code.bounce_code_select_row' => '',
	'bounce_code.bounce_code_update_post' => '',
	// optinoptout
	'optinoptout.optinoptout_delete' => '',
	'optinoptout.optinoptout_delete_multi' => '',
	'optinoptout.optinoptout_filter_post' => '',
	'optinoptout.optinoptout_insert_post' => '',
	'optinoptout.optinoptout_select_array' => '',
	'optinoptout.optinoptout_select_array_paginator' => '',
	'optinoptout.optinoptout_select_row_ajax' => '',
	'optinoptout.optinoptout_update_post' => '',
	// template
	'template.template_selector_tdisplay' => '',
	'template.template_selector_cdisplay' => '',
	'template.template_delete' => '',
	'template.template_delete_multi' => '',
	'template.template_filter_post' => '',
	'template.template_insert_post' => '',
	'template.template_select_array' => '',
	'template.template_select_array_paginator' => '',
	'template.template_select_row' => '',
	'template.template_update_post' => '',
	'template.template_import_post' => '',
	'template.template_select_list' => '',
	'template.template_export_list' => '',
	'template.template_import_list' => '',
	// personalization
	'personalization.personalization_delete' => '',
	'personalization.personalization_delete_multi' => '',
	'personalization.personalization_filter_post' => '',
	'personalization.personalization_insert_post' => '',
	'personalization.personalization_select_array' => '',
	'personalization.personalization_select_array_paginator' => '',
	'personalization.personalization_select_row' => '',
	'personalization.personalization_update_post' => '',
	// form
	'form.form_update_charset' => '',
	'form.form_delete' => '',
	'form.form_delete_multi' => '',
	'form.form_filter_post' => '',
	'form.form_insert_post' => '',
	'form.form_select_array' => '',
	'form.form_select_array_paginator' => '',
	'form.form_select_row' => '',
	'form.form_select_list' => '',
	'form.form_update_post' => '',
	'form.form_list_change' => '',
	'form.form_list_other_api_load' => '',
	// message
	'message.message_preview' => '',
	'message.message_delete' => '',
	'message.message_delete_multi' => '',
	'message.message_filter_post' => '',
	'message.message_insert_post' => '',
	'message.message_select_array' => '',
	'message.message_select_list' => '',
	'message.message_select_array_paginator' => '',
	'message.message_select_array_available' => '',
	'message.message_select_row' => '',
	'message.message_update_post' => '',
	'message.message_fetch_url' => '',
	'message.message_fetch_upload' => '',
	//'message.message_striptags' => '',
	'message.message_spam_emailcheck' => '',
	'message.message_send_emailtest' => '',
	// filter
	'filter.filter_delete' => '',
	'filter.filter_delete_multi' => '',
	'filter.filter_filter_post' => '',
	'filter.filter_insert_post' => '',
	'filter.filter_select_array' => '',
	'filter.filter_select_array_paginator' => '',
	'filter.filter_select_row' => '',
	'filter.filter_update_post' => '',
	// external services
	'service.service_select_array_paginator' => '',
	'service.service_select_row' => '',
	'service.service_get' => '',
	'service.service_update_post' => '',
	// campaign
	'campaign.campaign_save' => '',
	'campaign.campaign_save_action' => '',
	'campaign.campaign_load_action' => '',
	'campaign.campaign_delete_action' => '',
	'campaign.campaign_reminder_compile_post' => '',
	'campaign.campaign_delete' => '',
	'campaign.campaign_delete_multi' => '',
	'campaign.campaign_filter_post' => '',
	'campaign.campaign_insert_post' => '',
	'campaign.campaign_select_array' => '',
	'campaign.campaign_select_array_paginator' => '',
	'campaign.campaign_select_row' => '',
	'campaign.campaign_select_list' => '',
	'campaign.campaign_update_post' => '',
	'campaign.campaign_list_change' => '',
	'campaign.campaign_list_messages' => '',
	'campaign.campaign_spam_emailcheck' => '',
	'campaign.campaign_spamcheck' => '',
	'campaign.campaign_send_emailtest' => '',
	'campaign.campaign_inboxpreview' => '',
	'campaign.campaign_preview' => '',
	'campaign.campaign_subscribers' => '',
	'campaign.campaign_status' => '',
	'campaign.campaign_selectdropdown_bylist' => '',
	'campaign.campaign_select_totals' => '',
	'campaign.campaign_share' => '',
	'campaign.campaign_create' => '',
	'campaign.campaign_share_get' => '',
	'campaign.campaign_messages' => '',
	'campaign.campaign_quick_send' => '',
	'campaign.campaign_ajax_send' => '',
	'campaign.campaign_now' => '',
	'deskrss.deskrss_checkfeed' => '',
	// links
	'link.link_selectdropdown_bycampaign' => '',
	// reports - trend - read
	'report_trend_read.report_trend_read_filter_post' => '',
	'report_trend_read.report_trend_read_insert_post' => '',
	'report_trend_read.report_trend_read_select_array' => '',
	'report_trend_read.report_trend_read_select_array_paginator' => '',
	'report_trend_read.report_trend_read_select_row' => '',
	// reports - trend - email clients
	'report_trend_client.report_trend_client_filter_post' => '',
	'report_trend_client.report_trend_client_insert_post' => '',
	'report_trend_client.report_trend_client_select_array' => '',
	'report_trend_client.report_trend_client_select_array_paginator' => '',
	'report_trend_client.report_trend_client_select_row' => '',
	// reports - trend - email clients
	'report_trend_client_list.report_trend_client_list_filter_post' => '',
	'report_trend_client_list.report_trend_client_list_insert_post' => '',
	'report_trend_client_list.report_trend_client_list_select_array' => '',
	'report_trend_client_list.report_trend_client_list_select_array_paginator' => '',
	'report_trend_client_list.report_trend_client_list_select_row' => '',
	// reports - list
	'report_list.report_list_filter_post' => '',
	'report_list.report_list_insert_post' => '',
	'report_list.report_list_select_array' => '',
	'report_list.report_list_select_array_paginator' => '',
	'report_list.report_list_select_row' => '',
	// reports - list subscription
	'report_list_subscription.report_list_subscription_filter_post' => '',
	'report_list_subscription.report_list_subscription_insert_post' => '',
	'report_list_subscription.report_list_subscription_select_array' => '',
	'report_list_subscription.report_list_subscription_select_array_paginator' => '',
	'report_list_subscription.report_list_subscription_select_row' => '',
	// reports - user
	'report_user.report_user_filter_post' => '',
	'report_user.report_user_insert_post' => '',
	'report_user.report_user_select_array' => '',
	'report_user.report_user_select_array_paginator' => '',
	'report_user.report_user_select_row' => '',
	// reports - group
	'report_group.report_group_filter_post' => '',
	'report_group.report_group_insert_post' => '',
	'report_group.report_group_select_array' => '',
	'report_group.report_group_select_array_paginator' => '',
	'report_group.report_group_select_row' => '',
	// reports - campaign
	'report_campaign.report_campaign_spamcheck' => '',
	'report_campaign.report_campaign_share' => '',
	// approval
	'approval.approval_delete' => '',
	'approval.approval_delete_multi' => '',
	'approval.approval_filter_post' => '',
	'approval.approval_insert_post' => '',
	'approval.approval_select_array' => '',
	'approval.approval_select_array_paginator' => '',
	'approval.approval_select_row' => '',
	'approval.approval_update_post' => '',
	'approval.approval_hostedstatus' => '',
	// abuse
	'abuse.abuse_report' => '',
	'abuse.abuse_delete' => '',
	'abuse.abuse_delete_multi' => '',
	'abuse.abuse_filter_post' => '',
	'abuse.abuse_insert_post' => '',
	'abuse.abuse_select_array' => '',
	'abuse.abuse_select_array_paginator' => '',
	'abuse.abuse_select_row' => '',
	'abuse.abuse_update_post' => '',
	'abuse.abuse_list' => '',
	'abuse.abuse_reset' => '',
	'abuse.abuse_notify' => '',
	'abuse.abuse_update' => '',
	'feedbackloop.feedbackloop_select_array_paginator' => '',
	// group
	'group!adesk_group_delete' => '',
	'group!adesk_group_delete_multi' => '',
	'group!adesk_group_filter_post' => '',
	'group!adesk_group_insert_post' => '',
	'group!adesk_group_select_array' => '',
	'group!adesk_group_select_array_userpage' => '',
	'group!adesk_group_select_array_paginator' => '',
	'group!adesk_group_select_row' => '',
	'group!adesk_group_update_post' => '',
	'loginsource!adesk_loginsource_select_array_paginator' => '',
	'loginsource!adesk_loginsource_select_row' => '',
	'loginsource!adesk_loginsource_update_post' => '',
	'loginsource!adesk_loginsource_recognize' => '',
	'loginsource!adesk_loginsource_reorder' => '',
	// users
	"user!adesk_user_delete" => "",
	"user!adesk_user_delete_multi" => "",
	"user!adesk_user_filter_post" => "",
	"user!adesk_user_global_delete" => "",
	"user!adesk_user_global_import" => "",
	"user!adesk_user_insert_post" => "",
	"user!adesk_user_select_array" => "",
	"user!adesk_user_select_one" => "",
	"user!adesk_user_select_row" => "",
	"user!adesk_user_select_row_email" => "",
	"user!adesk_user_select_row_username" => "",
	"user!adesk_user_select_list" => "",
	"user!adesk_user_select_paginator" => "",
	"user!adesk_user_select_paginator_global" => "",
	"user!adesk_user_update_post" => "",
	"user.user_update_value" => "",
	'user_autocomplete' => '',
	 /* Disabled for security reasons
	"singlesignon!adesk_sso_token_generate" => "",
	"singlesignon!adesk_sso_sameserver" => "", */
	// cron
	'cron!adesk_cron_delete' => '',
	'cron!adesk_cron_delete_multi' => '',
	'cron!adesk_cron_filter_post' => '',
	'cron!adesk_cron_insert_post' => '',
	'cron!adesk_cron_select_array' => '',
	'cron!adesk_cron_select_array_paginator' => '',
	'cron!adesk_cron_select_row' => '',
	'cron!adesk_cron_update_post' => '',
	'cron!adesk_cron_status' => '',
	'cron!adesk_cron_run' => '',
	'cron!adesk_cron_log' => '',
	// emailcss (CSS check page)
	'emailpreview!adesk_emailpreview_share_email' => '',
	'emailpreview!adesk_emailpreview_sendfeedback' => '',
	// processes
	'processes!adesk_processes_delete' => '',
	'processes!adesk_processes_delete_multi' => '',
	'processes!adesk_processes_filter_post' => '',
	'processes!adesk_processes_insert_post' => '',
	'processes!adesk_processes_select_array' => '',
	'processes!adesk_processes_select_array_paginator' => '',
	'processes!adesk_processes_select_row' => '',
	'processes!adesk_processes_update_post' => '',
	'processes!adesk_processes_trigger' => '',
	// progress bars
	'process!adesk_progressbar_update' => '',
	// sync
	'sync!adesk_sync_list' => '',
	'sync!adesk_sync_select' => '',
	'sync!adesk_sync_db' => '',
	'sync!adesk_sync_table' => '',
	'sync!adesk_sync_field' => '',
	'sync!adesk_sync_save' => '',
	'sync!adesk_sync_delete' => '',
	'sync!adesk_sync_run_api' => '',
	'sync!adesk_sync_report' => '',
	// widget
	"widget!widget_install" => "",
	"widget!widget_uninstall" => "",
	"widget!widget_uninstallall" => "",
	"widget!widget_sort" => "",
	"widget!widget_save" => "",
	"widget!widget_options" => "",
	// importers
	'subscriber_import.subscriber_import_cfield_add' => '',
	'subscriber_import.subscriber_import_relids' => '',
	'subscriber_import.import_relid_change' => '',
	'subscriber_import.adesk_import_src' => '',
	'subscriber_import.adesk_import_report' => '',
	'subscriber_import_remove' => 'adesk_import_file_remove',
	'subscriber.subscriber_exportlist_export' => '',
	'template_import_remove' => 'template_import_file_remove',
	'message_fetch_remove' => '',
	'message_attach_remove' => '',
	'optinoptout_attach_remove' => '',
	"site_updateacu" => "site_updateacu",
	"calendar!calendar_display_month" => "",
	"calendar!calendar_display_day" => "",
	"em.awebdesk_blog_posts" => "",
	"em.import_files" => "",
	"mailer.mailer_insert" => "",
	"mailer.mailer_update" => "",
	"mailer.mailer_delete" => "",
	"mailer.mailer_test" => "",
	"mailer.mailer_sort" => "",
	// recipient
	'recipient.recipient_filter_post' => '',
	'recipient.recipient_select_array' => '',
	'recipient.recipient_select_array_paginator' => '',
	'recipient.recipient_select_row' => '',
);

$shareallowed = array(
	"bounce_data.bounce_data_select_totals" => "",
	"bounce_data.bounce_data_select_array_paginator" => "",
	"campaign.campaign_select_totals" => "",
	"forward.forward_select_totals" => "",
	"forward.forward_select_array_paginator" => "",
	"link.link_select_totals" => "",
	"link.link_select_array_paginator" => "",
	"linkinfo.linkinfo_select_totals" => "",
	"linkinfo.linkinfo_select_array_paginator" => "",
	"open.open_select_totals" => "",
	"open.open_select_array_paginator" => "",
	"subscriber.subscriber_select_array_paginator" => "",
	"unopen.unopen_select_array_paginator" => "",
	"unsubscriber.unsubscriber_select_totals" => "",
	"unsubscriber.unsubscriber_select_array_paginator" => "",
	"update.update_select_array_paginator" => "",
	"update.update_select_totals" => "",
	"socialsharing.socialsharing_select_array_paginator" => "",
	"socialsharing.socialsharing_select_totals" => "",
);

/*
	REMOTE WHITELIST
*/
$whitelist = array(
	// singlesignon
 /* Disabled for security reasons	'singlesignon' => array(
		'action' => 'singlesignon!adesk_sso_token_generate',
		'params' => array('sso_addr', 'sso_user', 'sso_pass', 'sso_duration'),
	),
	*/
	'singlesignon_sameserver' => array(
		'action' => 'singlesignon!adesk_sso_sameserver',
		'params' => array(),
	),
	// subscriber
	'subscriber_view' => array(
		'action' => 'subscriber.subscriber_view',
		'params' => array('id'),
	),
	'subscriber_view_email' => array(
		'action' => 'subscriber.subscriber_view',
		'params' => array('email'),
	),
	'subscriber_view_hash' => array(
		'action' => 'subscriber.subscriber_view_hash',
		'params' => array('hash'),
	),
	'subscriber_list' => array(
		'action' => 'subscriber.subscriber_list',
		'params' => array('ids', 'filters'),
	),
	'subscriber_paginator' => array(
		'action' => 'subscriber.subscriber_select_array_paginator',
		'params' => array('somethingthatwillneverbeused', 'sort', 'offset', 'limit', 'filter'),
	),
	'subscriber_add' => array(
		'action' => 'subscriber.subscriber_insert_post',
		'params' => array('listid'),
		'post'   => 1,
	),
	'subscriber_edit' => array(
		'action' => 'subscriber.subscriber_update_post',
		'params' => array(),
		'post'   => 1,
	),
/*	'subscriber_delete' => array(
		'action' => 'subscriber.subscriber_delete',
		'params' => array('id', 'listids'),
	),
	'subscriber_delete_list' => array(
		'action' => 'subscriber.subscriber_delete_multi',
		'params' => array('ids', 'listids'),
	), */
	// user
	'user_view' => array(
		'action' => 'user!adesk_user_select_row',
		'params' => array('id'),
	),
	'user_view_email' => array(
		'action' => 'user!adesk_user_select_row_email',
		'params' => array('email'),
	),
	'user_view_username' => array(
		'action' => 'user!adesk_user_select_row_username',
		'params' => array('username'),
	),
	'user_list' => array(
		'action' => 'user!adesk_user_select_list',
		'params' => array('ids'),
	),
	'user_add' => array(
		'action' => 'user!adesk_user_insert_post',
		'params' => array(),
		'post'   => 1,
	),
	/*'user_edit' => array(
		'action' => 'user!adesk_user_update_post',
		'params' => array(),
		'post'   => 1,
	),
	'user_delete' => array(
		'action' => 'user!adesk_user_delete',
		'params' => array('id'),
	),
	'user_delete_list' => array(
		'action' => 'user!adesk_user_delete_multi',
		'params' => array('ids'),
	), */
	// group
	'group_view' => array(
		'action' => 'group!adesk_group_select_row',
		'params' => array('id'),
	),
	'group_list' => array(
		'action' => 'group!adesk_group_select_array',
		'params' => array(null, 'ids'),
	),
/*	'group_add' => array(
		'action' => 'group!adesk_group_insert_post',
		'params' => array(),
		'post'   => 1,
	),
	'group_edit' => array(
		'action' => 'group!adesk_group_update_post',
		'params' => array(),
		'post'   => 1,
	),
	'group_delete' => array(
		'action' => 'group!adesk_group_delete',
		'params' => array('id', 'alt'),
	),
	'group_delete_list' => array(
		'action' => 'group!adesk_group_delete_multi',
		'params' => array('ids', 'alt'),
	), */
	// branding
/*	'branding_view' => array(
		'action' => 'branding.branding_select_row',
		'params' => array('id'),
	),
	'branding_edit' => array(
		'action' => 'design.design_update_post',
		'params' => array(),
		'post'   => 1,
	), */
	// list
	'list_view' => array(
		'action' => 'list.list_select_row',
		'params' => array('id'),
	),
	'list_list' => array(
		'action' => 'list.list_select_list',
		'params' => array('ids', 'filters'),
	),
	'list_paginator' => array(
		'action' => 'list.list_select_array_paginator',
		'params' => array('somethingthatwillneverbeused', 'sort', 'offset', 'limit', 'filter'),
	),
	'list_add' => array(
		'action' => 'list.list_insert_post',
		'params' => array(),
		'post'   => 1,
	),
	'list_edit' => array(
		'action' => 'list.list_update_post',
		'params' => array(),
		'post'   => 1,
	),
	/*'list_delete' => array(
		'action' => 'list.list_delete',
		'params' => array('id'),
	),
	'list_delete_list' => array(
		'action' => 'list.list_delete_multi',
		'params' => array('ids'),
	), */
	'list_field_add' => array(
		'action' => 'list_field.list_field_insert_post',
		'params' => array(),
	),
	'list_field_edit' => array(
		'action' => 'list_field.list_field_update_post',
		'params' => array(),
	),
	'list_field_delete' => array(
		'action' => 'list_field.list_field_delete',
		'params' => array('id'),
	),
	'list_field_view' => array(
		'action' => 'list_field.list_field_select_nodata_rel',
		'params' => array('ids'),
	),
	// campaign
	'campaign_view' => array(
		'action' => 'campaign.campaign_select_row',
		'params' => array('id'),
	),
	'campaign_list' => array(
		'action' => 'campaign.campaign_select_list',
		'params' => array('ids', 'filters'),
	),
	'campaign_paginator' => array(
		'action' => 'campaign.campaign_select_array_paginator',
		'params' => array('somethingthatwillneverbeused', 'sort', 'offset', 'limit', 'filter', 'public'),
	),
	'campaign_create' => array(
		'action' => 'campaign.campaign_create',
		'params' => array(),
		'post'   => 1,
	),
	'campaign_send' => array(
		'action' => 'campaign.campaign_ajax_send',
		'params' => array('email', 'campaignid', 'messageid', 'type', 'action'),
	),
	/**/'campaign_preview' => array(
		'action' => 'campaign.campaign_preview',
		'params' => array(),
		'post'   => 1,
	),
	/**/'campaign_sendtest' => array(
		'action' => 'campaign.campaign_send_emailtest',
		'params' => array(),
		'post'   => 1,
	),
	/**/'campaign_spamcheck' => array(
		'action' => 'campaign.campaign_spam_emailcheck',
		'params' => array(),
		'post'   => 1,
	),
	'campaign_status' => array(
		'action' => 'campaign.campaign_status',
		'params' => array('id', 'status'),
	),
/*	'campaign_delete' => array(
		'action' => 'campaign.campaign_delete',
		'params' => array('id'),
	),
	'campaign_delete_list' => array(
		'action' => 'campaign.campaign_delete_multi',
		'params' => array('ids'),
	),*/
	'campaign_report_totals' => array(
		'action' => 'campaign.campaign_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_open_totals' => array(
		'action' => 'open.open_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_open_list' => array(
		'action' => 'open.open_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_link_totals' => array(
		'action' => 'link.link_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_link_list' => array(
		'action' => 'link.link_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_forward_totals' => array(
		'action' => 'forward.forward_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_forward_list' => array(
		'action' => 'forward.forward_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_bounce_totals' => array(
		'action' => 'bounce_data.bounce_data_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_bounce_list' => array(
		'action' => 'bounce_data.bounce_data_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_unsubscription_totals' => array(
		'action' => 'unsubscriber.unsubscriber_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_unsubscription_list' => array(
		'action' => 'unsubscriber.unsubscriber_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_socialsharing_totals' => array(
		'action' => 'socialsharing.socialsharing_select_totals',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_socialsharing_list' => array(
		'action' => 'socialsharing.socialsharing_select_list',
		'params' => array('campaignid', 'messageid'),
	),
	'campaign_report_share' => array(
		'action' => 'report_campaign.report_campaign_share',
		'params' => array('campaignid', 'email'),
	),
	// message
	'message_view' => array(
		'action' => 'message.message_select_row',
		'params' => array('id'),
	),
	'message_list' => array(
		'action' => 'message.message_select_list',
		'params' => array(9999999, 'ids'),
	),
	'message_add' => array(
		'action' => 'message.message_insert_post',
		'params' => array(),
		'post'   => 1,
	),
	'message_edit' => array(
		'action' => 'message.message_update_post',
		'params' => array(),
		'post'   => 1,
	),
/*	'message_delete' => array(
		'action' => 'message.message_delete',
		'params' => array('id'),
	),
	'message_delete_list' => array(
		'action' => 'message.message_delete_multi',
		'params' => array('ids'),
	), */
	// message templates
	'message_template_view' => array(
		'action' => 'template.template_select_row',
		'params' => array('id'),
	),
	'message_template_list' => array(
		'action' => 'template.template_select_list',
		'params' => array(9999999, 'ids'),
	),
	'message_template_add' => array(
		'action' => 'template.template_insert_post',
		'params' => array(),
		'post'   => 1,
	),
	'message_template_edit' => array(
		'action' => 'template.template_update_post',
		'params' => array(),
		'post'   => 1,
	),
/*	'message_template_delete' => array(
		'action' => 'template.template_delete',
		'params' => array('id'),
	),
	'message_template_delete_list' => array(
		'action' => 'template.template_delete_multi',
		'params' => array('ids'),
	), */
	'message_template_export' => array(
		'action' => 'template.template_export_list',
		'params' => array('ids', 'type'),
	),
	'message_template_import' => array(
		'action' => 'template.template_import_list',
		'params' => array(),
	),
	'form_list' => array(
		'action' => 'form.form_select_list',
		'params' => array('ids'),
	),
	'form_view' => array(
		'action' => 'form.form_select_row',
		'params' => array('id', 'generate'),
	),
	'form_edit' => array(
		'action' => 'form.form_update_post',
		'params' => array(),
		'post'   => 1,
	),
	'form_add' => array(
		'action' => 'form.form_insert_post',
		'params' => array(),
		'post'   => 1,
	),
/*	'form_delete' => array(
		'action' => 'form.form_delete',
		'params' => array('id'),
	),
	'form_delete_list' => array(
		'action' => 'form.form_delete_multi',
		'params' => array('ids'),
	), */
);

// require ajax functions
require_once(awebdesk_functions('ajax.php'));

 
	// check if it's remote api
	$api_user   = (string)adesk_http_param('api_user');
	$api_pass   = (string)adesk_http_param('api_pass');
	$api_pass_md5 = (string)adesk_http_param("api_pass_h");
	$api_action = (string)adesk_http_param('api_action');
	$api_output = (string)adesk_http_param('api_output');
	// if it is a remote api
	if ( /*$api_user and*/ $api_action ) {
		// adjust defaults
		
		//Disabled for security reasons to enable comment below line ie put // in front of die("Sorry Remote API Disabled.");
		
	 
		//die("Sorry Remote API Disabled.");
		
	 	if ( !$api_output ) $api_output = 'xml';

		define('adesk_API_REMOTE', 1);
		define('adesk_API_REMOTE_OUTPUT', $api_output);

		if ( isset($whitelist[$api_action]) ) {
			// convert input into ajax-style
			$_GET = adesk_api_input($whitelist[$api_action]);

			/*
				perform login
			*/
			if ( $api_user ) {
				
					//Disabled for security reasons to enable comment below line ie put // in front of die("Sorry Remote API Disabled.");
		
		
		
		
		//die("Sorry Remote API Disabled.");
		
		
		
		
		
		
		
		
				
				require_once awebdesk_functions("tz.php");
				require_once awebdesk_functions("loginsource.php");
				require_once awebdesk_classes("loginsource.php");
				// do login source stuff first (setup)
				adesk_loginsource_sync();
				$source = adesk_loginsource_determine($api_user, $api_pass, 1);

				if ($source !== false) {
					$GLOBALS["loginsource"] = new $source["_classname"]($source);
				} else {
					die("This should never happen.");
				}

				// try to authenticate
				$authenticated = adesk_auth_login_plain($api_user, $api_pass);
				if ( !$authenticated and strlen($api_pass_md5) == 32 and $api_pass_md5==md5($api_pass)) {
					$authenticated = adesk_auth_login_md5($api_user, $api_pass_md5);
				}
				if ( $authenticated ) {
					// authenticated, refetch admin user
					adesk_session_drop_cache();
					unset($admin);
					$admin = adesk_admin_get();
					tz_checkdst();
				}
			}
		}  
	}

 

// set constants
if ( !defined('adesk_API_REMOTE') ) define('adesk_API_REMOTE', 0);
if ( !defined('adesk_API_REMOTE_OUTPUT') ) define('adesk_API_REMOTE_OUTPUT', 'xml');

// Preload the language file
adesk_lang_get('admin');

// check for basic admin privileges
if ( !adesk_admin_isadmin() ) {
	adesk_api_error(_a("You are not authorized to access this file"));
	exit;
}

 
// require ajax include
require_once awebdesk_includes("awebdeskapi.php");


?>