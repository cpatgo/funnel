{include file="adesk_strings.js"}


var campaign_status_strings = [
	'{"Draft"|alang|js}',
	'{"Scheduled"|alang|js}',
	'{"Sending"|alang|js}',
	'{"Paused"|alang|js}',
	'{"Stopped"|alang|js}',
	'{"Completed"|alang|js}',
	'{"Disabled"|alang|js}',
	'{"Pending Approval"|alang|js}'
];

{literal}
var campaign_split_offsets = {
{/literal}
	hour: '{"hour(s)"|alang|js}',
	day: '{"days(s)"|alang|js}',
	week: '{"week(s)"|alang|js}',
	month: '{"months(s)"|alang|js}'
{literal}
};

var campaign_split_types = {
{/literal}
	click: '{"CLICK"|alang|js}',
	read: '{"READ"|alang|js}'
{literal}
};

var campaign_type_strings = {
{/literal}
	single: '{"One-Time Mailing"|alang|js}',
	recurring: '{"Recurring Mailing"|alang|js}',
	responder: '{"AutoResponder"|alang|js}',
	reminder: '{"AutoReminder"|alang|js}',
	split: '{"Split Test"|alang|js}',
	deskrss: '{"RSS Mailing"|alang|js}',
	text: '{"Text-Only Mailing"|alang|js}'
{literal}
};

var campaign_types_strings = {
{/literal}
	single: '{"One-Time Mailings"|alang|js}',
	recurring: '{"Recurring Mailings"|alang|js}',
	responder: '{"AutoResponders"|alang|js}',
	reminder: '{"AutoReminders"|alang|js}',
	split: '{"Split Tests"|alang|js}',
	deskrss: '{"RSS Mailings"|alang|js}'
{literal}
};
{/literal}


var jsOptionResendUse = '{"Resend"|alang|js}';

var jsAddAndSend                            = '{"Add & Send Campaign"|alang|js}';
var jsUpdateAndSend                         = '{"Update & Send Campaign"|alang|js}';


var strEmailNotEmail = '{"Email Address is not valid."|alang|js}';
var strURLNotURL = '{"URL is not valid."|alang|js}';

// add admin side strings here
var strListNameEmpty = '{"List Name can not be empty."|alang|js}';
var strListNotEmail = '{"List Email Address has to contain an email address."|alang|js}';
var strListAnalyticsUAEmpty = '{"Please enter Google Analytics UA Number in the following format:\n\nUA-xxxxxxx-y"|alang|js}';

var strListTwitterUserEmpty = '{"Please enter your Twitter Username to use Twitter notifications."|alang|js}';
var strListTwitterPassEmpty = '{"Please enter your Twitter Password to use Twitter notifications."|alang|js}';

var strHeaderTitleEmpty = '{"Please name this Email Header."|alang|js}';
var strHeaderNameEmpty = '{"Email Header Name can not be left blank."|alang|js}';
var strHeaderNameInvalid = '{"You can not use a restricted Email Header Name."|alang|js}';
var strHeaderValueEmpty = '{"Email Header Value can not be left blank."|alang|js}';
//var strListEmpty = '{""|alang|js}';
//var strListEmpty = '{""|alang|js}';

var strSubscriberNotEmail = '{"Subscriber Email Address is not valid."|alang|js}';


var strSubscriberRuleLong = '{"Automatically %s list '%s' when subscriber %s list '%s'"|alang|js}';
var strSubscriberRuleSourceSub = '{"subscribes to"|alang|js}';
var strSubscriberRuleSourceUnsub = '{"unsubscribes from"|alang|js}';
var strSubscriberRuleTargetSub = '{"subscribe to"|alang|js}';
var strSubscriberRuleTargetUnsub = '{"unsubscribe from"|alang|js}';
var strSubscriberRuleSub = '{"subscribe"|alang|js}';
var strSubscriberRuleUnsub = '{"unsubscribe"|alang|js}';
var strSubscriberRuleWrong = '{"You cannot select the same list as both source and destination"|alang|js}';


var strOptNameEmpty = '{"Email Confirmation Set can not be left empty. Please name this set."|alang|js}';

var strOptInNeeded = '{"Email Confirmation Set needs to have Opt-In set up."|alang|js}';
var strOptInEmailNotEmail = '{"Opt-in Email Address is not valid."|alang|js}';
var strOptInSubjectEmpty = '{"Opt-in Email Subject can not be left empty."|alang|js}';
var strOptInTextConfirmMissing = '{"Opt-in Text version does not contain a confirmation link."|alang|js}';
var strOptInHTMLConfirmMissing = '{"Opt-in HTML version does not contain a confirmation link."|alang|js}';
var strOptOutEmailNotEmail = '{"Opt-out Email Address is not valid."|alang|js}';
var strOptOutSubjectEmpty = '{"Opt-out Email Subject can not be left empty."|alang|js}';
var strOptOutTextConfirmMissing = '{"Opt-out Text version does not contain a confirmation link."|alang|js}';
var strOptOutHTMLConfirmMissing = '{"Opt-out HTML version does not contain a confirmation link."|alang|js}';
var strOptInSubjectDefault = '{"Please confirm your subscription"|alang|js}';
var strOptOutSubjectDefault = '{"Please confirm your unsubscription"|alang|js}';

var strMessageEmailNotEmail = '{"From Email Address is not valid."|alang|js}';
var strMessageSubjectEmpty = '{"Email Subject can not be left empty."|alang|js}';
var strMessageTextConfirmMissing = '{"Text version does not contain an unsubscription link."|alang|js}';
var strMessageHTMLConfirmMissing = '{"HTML version does not contain an unsubscription link."|alang|js}';

var strMessageReadsTimes = '{"Read/Opened"|alang|js}';
var strMessageReadsTitle = '{"Read(s)"|alang|js}';
var strMessageClicksTimes = '{"Link(s)"|alang|js}';
var strMessageClicksTitle = '{"Click(s)"|alang|js}';
var strMessageForwardsDate = '{"Date"|alang|js}';
var strMessageForwardsTo = '{"Forward(s)"|alang|js}';


var strMailingFullStats = '{"Full report for this mailing"|alang|js}';
var strResponderFullStats = '{"Full report for this auto-responder"|alang|js}';



var strTrackUpdateSubscriberName = '{"Update Subscriber Link"|alang|js}';
var strTrackWebCopyName = '{"Web Copy Link"|alang|js}';
var strTrackForward2FriendName = '{"Forward to a Friend Link"|alang|js}';



var strPersGlobalTemplates         = '{"System Templates"|alang|js}';
var strPersListTemplates           = '{"List-Related Templates"|alang|js}';
var strPersSubscriberTags          = '{"Subscriber Personalization Tags"|alang|js}';
var strPersMessageTags             = '{"Message Option and Link Tags"|alang|js}';
var strPersSocialTags              = '{"Social Media Tags"|alang|js}';
var strPersOtherTags               = '{"Other Tags"|alang|js}';
var strPersSubscriberTags          = '{"Subscriber Personalization Tags"|alang|js}';
var strPersSubscriberTags          = '{"Subscriber Personalization Tags"|alang|js}';
var strPersSenderTags              = '{"Sender Personalization Tags"|alang|js}';

var strPersListFields = '{"List-Related Fields"|alang|js}';
var strPersConfirmLink = '{"Confirmation Link"|alang|js}';
var strPersUnsubLink = '{"Unsubscribe Link"|alang|js}';
var strPersUnsubLink_DESC = '{"Unsubscribes the subscriber from the list used to send the email."|alang|js}';
var strPersUnsubLinkAll = '{"Unsubscribe Link (All Lists)"|alang|js}';
var strPersUnsubLinkAll_DESC = '{"Unsubscribe from all lists. Even lists not included in the email sent."|alang|js}';
var strPersUpdateLink = '{"Update Subscription Account Link"|alang|js}';
var strPersUpdateLink_DESC = '{"Allow subscribers to update their subscription details."|alang|js}';
var strPersWCopyLink = '{"Web Version Link"|alang|js}';
var strPersWCopyLink_DESC = '{"Allow subscribers to view the email in their browser."|alang|js}';
var strPersFriendLink = '{"Send to Friend Link"|alang|js}';
var strPersFriendLink_DESC = '{"Your subscribers can forward the email to multiple people."|alang|js}';
var strPersSocialLink = '{"Social Submit Links"|alang|js}';
var strPersSocialFacebookLikeLink = '{"Facebook Like Button"|alang|js}';
var strPersEmailLink = '{"Email Address"|alang|js}';//"
var strPersEmailLinkDesc = '{"The subscribers email address."|alang|js}';//"
var strPersFNameLink = '{"First Name"|alang|js}';//"
var strPersLNameLink = '{"Last Name"|alang|js}';//"
var strPersNameLink = '{"Full Name"|alang|js}';//"
var strPersListnameLink = '{"Subscriber's List"|alang|js}';//"
var strPersListnameLink_DESC = '{"Show the name of the list that this email was sent to."|alang|js}';//"

var strPersIPLink = '{"Subscriber's IP Address"|alang|js}';//"
var strPersIPLink_DESC = '{"The IP Address of the subscriber when they subscribed to this list."|alang|js}';
var strPersSDateLink = '{"Date Subscribed"|alang|js}';
var strPersSDateLink_DESC = '{"The date that this subscriber subscribed."|alang|js}';

var strPersSDateTimeLink = '{"Date and Time Subscribed"|alang|js}';
var strPersSDateTimeLink_DESC = '{"The date and time the subscriber subscribed."|alang|js}';
var strPersSTimeLink = '{"Time Subscribed"|alang|js}';
var strPersSTimeLink_DESC = '{"The time of the day the subscriber subscribed."|alang|js}';
var strPersTodayLink = '{"Today's Date"|alang|js}';//"
var strPersTodayRangeLink = '{"Today's Date +/- X day(s)"|alang|js}';//"
var strPersSenderInfoLink = '{"List Sender Info"|alang|js}';//"
var strPersSIDLink = '{"Subscriber ID"|alang|js}';
var strPersSIDLink_DESC = '{"Display the ID # of the subscriber."|alang|js}';
var strPersMissing = '{"Please select a Personalization Tag"|alang|js}';

var strConfirmLinkText = '{"Click here to confirm your subscription"|alang|js}';
var strUnsubscribeText = '{"Click here to unsubscribe"|alang|js}';
var strSubscriberUpdateText = '{"Click here to update your info"|alang|js}';
var strWebCopyText = '{"Click here to see a web copy of this email"|alang|js}';
var strForward2FriendText = '{"Click here to forward this email to a friend"|alang|js}';
var strEnterText = '{"Enter the text for this link:"|alang|js}';
var strEnterRange = '{"Enter the date range compared to sending date."|alang|js}' +
	'\n\n' + '{"Sample values: -10, -1, +3, 6 (plus is assumed)..."|alang|js}';
var strEnterRangeInvalid = '{"Invalid range."|alang|js}' +
	'\n\n' + '{"Sample values: -10, -1, +3, 6 (plus is assumed)..."|alang|js}';

var strPersPersonalize = '{"Insert Personalization Tag"|alang|js}';

var strDefaultTO = '{"Subscriber"|alang|js}';


var strDefaultOptInText  = '{"Thank you for subscribing. Click here to confirm your subscription:"|alang|js}';
var strDefaultOptOutText = '{"Please click this link to confirm your unsubscription:"|alang|js}';

var strImportTypeXML  = '{"XML (Includes Dependent Files)"|alang|js}';
var strImportTypeHTML = '{"HTML Only"|alang|js}';

var strRssNew = '{"Emails will only be sent if new RSS items are found"|alang|js}';

{literal}
var personalization_social_networks = {
{/literal}
	facebook: '{"Share on Facebook Link"|alang|js}',
	twitter: '{"Share on Twitter Link"|alang|js}',
	digg: '{"Share on Digg Link"|alang|js}',
	reddit: '{"Share on Reddit Link"|alang|js}',
	delicious: '{"Share on del.icio.us Link"|alang|js}',
	greader: '{"Share on GoogleReader Link"|alang|js}',
	stumbleupon: '{"Share on StumbleUpon Link"|alang|js}'
{literal}
};
{/literal}
