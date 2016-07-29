INSERT INTO qu_pap_impressions (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, CONCAT(DATE(day), ' 20:00:00') as dateinserted, raw_20 as raw, unique_20 as uniq FROM qu_pap_dailyimpressions WHERE raw_20 > 0 OR unique_20 > 0;