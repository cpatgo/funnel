INSERT INTO qu_pap_impressions (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, DATE_FORMAT(month, '%Y-%m-16 12:00:00') as dateinserted, raw_16 as raw, unique_16 as uniq FROM qu_pap_monthlyimpressions WHERE DAY(LAST_DAY(month)) >= 16 AND (raw_16 > 0 OR unique_16 > 0);