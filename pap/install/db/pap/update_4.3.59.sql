INSERT INTO qu_pap_impressions (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, DATE_FORMAT(month, '%Y-%m-2 12:00:00') as dateinserted, raw_2 as raw, unique_2 as uniq FROM qu_pap_monthlyimpressions WHERE DAY(LAST_DAY(month)) >= 2 AND (raw_2 > 0 OR unique_2 > 0);