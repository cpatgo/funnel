<!-- email_settings_spf -->
<div class="SpfSettingsInfo">
    <div class="SpfSettingsDescription">##Setup SPF record in your domain settings. You need it to be sure, that your replies won't be recognized as spam. Please add one of these two lines to TXT record of your domain. We recommend you to use:##</div>
    <div class="SpfSettingsCode">v=spf1 redirect=_spf.postaffiliatepro.com</div>
    <div class="SpfSettingsDescription">##If you need multiple SPF mechanisms, you can also add include mechanism to your existing record.##</div>
    <div class="SpfSettingsCode">v=spf1 ... your records ... include:_spf.postaffiliatepro.com -all</div>
    <div class="SpfSettingsDescription">##Note: Be sure you have only one DNS record. For more information check our knowledge base:## <a href="https://support.qualityunit.com/250549-Mail-account" target="_blank">support.qualityunit.com/250549-Mail-account</a></div>
    {widget id="warningMessage" class="SpfSettingsWarning"}
</div>
