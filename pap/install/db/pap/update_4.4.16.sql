DELETE va FROM qu_pap_visitoraffiliates va LEFT JOIN qu_pap_users u ON va.userid = u.userid WHERE u.userid IS NULL;