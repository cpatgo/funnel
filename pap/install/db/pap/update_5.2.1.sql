UPDATE    qu_pap_transactions AS t
       INNER JOIN
          (  SELECT *
               FROM qu_pap_transactions
              WHERE rtype != 'U'
           GROUP BY campaignid) t2
       ON t.campaignid = t2.campaignid AND t.rtype = 'U'
   SET t.accountid = t2.accountid;