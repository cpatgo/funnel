UPDATE qu_g_settings SET accountid = NULL WHERE 
name = 'proxyServer' OR
name = 'proxyPort' OR
name = 'proxyUser' OR
name = 'proxyPassword' OR
name = 'quickLaunchSetting' OR
name = 'log_level' OR
name = 'notForceEmailUsernames' OR
name = 'deleteeventdays' OR
name = 'defaultCountry' OR
name = 'base_link' OR
name = 'favicon' OR
name = 'qualityunit_addons_link' OR
name = 'qualityunit_company_link' OR
name = 'qualityunit_privacy_policy_link' OR
name = 'qualityunit_contact_us_link' OR
name = 'quality_unit' OR
name = 'MAP_API_KEY';