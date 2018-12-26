<?php

//echo time()."<br/>";
//echo "value of ".date('H')." ".date('i');exit;

//phpinfo();
//exit;
//http://egcrm.cloudapp.net/test/push.php?type=load
// API access key from Google API's Console

define( 'API_ACCESS_KEY', 'AIzaSyCa8wMMIngMDg6npiBAatluPenOzajm9OI' ); //truck app
//define( 'API_ACCESS_KEY', 'AIzaSyAHqZz4r7QM3tRRxvgULC9w0NwSrcGiyqU' ); //DCapp app

//$registrationIds=array($_REQUEST['mobID']);
$registrationIds=array('APA91bHv2A2NqTDwx-wEIwEQlwBRB3SU-iKV9NTY8RLEkwdUpzolkqDVt4pQSvZ_7XQOn4db__Z5vE6wIXElsFZznQIVM5Y_V6wEAcguc1SIN8rJlgkTPEbBwPiKpfq334X9PxDiuLOH');
//tarun=APA91bHT6g-s6hgSeNHF27zsybGmB8vWdhD-Rjs8qb4YvV7swyPt2Y-FvlP8m1Ck_nVp9_nNVK-ndp3Qj0h0v2JyypWL6ac0Nutd7exfHHRRQOObCyRqPZVPlP8gZ8kA06az5SatX0zd
//$registrationIds=array('APA91bH9LGlycahkyyFsqWOWhdGzBqeO8pRvV2sb_YMsQxLwtxJ3ntUogGCFf4r1nB9gJGlen3WBdbdF9Iyj2jpT5rmWs3Wci5iQYjvKcNpDtRVn5h6ropCTebjrtieT-jen_j5BgaI4','APA91bG5nicF4LP-dmLegud-N2TD0bVbxD325LBrbVijU9eUrID_YtJwdgC-gVol7q_G2_tyPPwiAoS7GO03UTlVjI6aietOmJllZy43iZEA-pNb3Ln9TVbStSWr0TX9W-3H4RniLEwL','APA91bGnl8t97ktyreg9CzRjeehl3I59QbFEFFSk0vatXVYZ5emgku8GW6xRzoJFtmFR5Ca-ckuBfSHM7PgMkjV6KlxyYCpN2x1YZnCHbLNHJrESnjVWl8PuZO-9-esIGhv0l1xoGwqJ','APA91bGsYbjYgOYCCIyCWHl1cHiJEmY7zUbLyACYc14lHGeX24OP2an2Yxo0o4NUHIXEHoImFCCmF0u7-drox08FTJpWNZERvgT5i7eDndMhlriSxTVyG98R9b7U526TjYrTiISE_EGM','cbGt6uRju6g:APA91bEOOM4FpGSofWfmSrGyzwxu1CY-g0JEERS6H9wnziWVr0nNjfg0yi4ICZSg-yRhdufKtzVs1T2KJR-2usjOUjfac-qkWTksQqHufY63hFZNenL6ebSw-tQFHO1nY9SJrMNQEPg6','APA91bGsYbjYgOYCCIyCWHl1cHiJEmY7zUbLyACYc14lHGeX24OP2an2Yxo0o4NUHIXEHoImFCCmF0u7-drox08FTJpWNZERvgT5i7eDndMhlriSxTVyG98R9b7U526TjYrTiISE_EGM'); //array('APA91bGsYbjYgOYCCIyCWHl1cHiJEmY7zUbLyACYc14lHGeX24OP2an2Yxo0o4NUHIXEHoImFCCmF0u7-drox08FTJpWNZERvgT5i7eDndMhlriSxTVyG98R9b7U526TjYrTiISE_EGM');
//'cbGt6uRju6g:APA91bEOOM4FpGSofWfmSrGyzwxu1CY-g0JEERS6H9wnziWVr0nNjfg0yi4ICZSg-yRhdufKtzVs1T2KJR-2usjOUjfac-qkWTksQqHufY63hFZNenL6ebSw-tQFHO1nY9SJrMNQEPg6',
//$registrationIds=array('APA91bG9RQAOg4gPlzsGhTlIY85loO7Oz5OeQIm-z1M0YF1cbAhs5wbzUlS3qlmflEL1EtSlf5u5X6oOexJb8bLMdvrcn3NiUNCpHC8wX4r_RE7R1zHbWs_gn9G_URcQ3epFTpr3BU-F');//array('APA91bEQQKuZ3YErx1nZZjG75RXiLYLyTIaNInXt50nvW_MfycV6cYw9MuyOXCQRp44EyCtVsbklvDVWG-3Ubuf-E3ladXBYcaCR5Dv7zmBjXeaLC00DM0PZ5oAzlrp6FUhgedD8lo8w','APA91bE36ynNM-vzTRftC26JLKMF12gSrN8yO-9cBccYUaz9FDWJxyR6I0w_zCpHYyMhMHphUpww11r-lrKylxCtIXR7UQBBM8-3xCT37CHVIFquW3TVGfgBWYCo_qAHzeFHOMD7gGLj','APA91bER4-sIcSmLsc5XXW7rulox3t_jkRhC1HH0PCOU5E10_8RlLMrFpusn9exAkXZeTu8zO-euFp8pPr23g5KNuBpbxibUTMgvxyLRhlnQu5MRVqUroW_1633Prg96yW2uY4dnb4zW','APA91bH1zpZgxCM2YYvipnWK39KIxwNO3w-fvrqSWPw4ZYaDqqYq1gWzxz6cLfRQhqLjjtU1uX4Z-olYcapMEaYvv97DLecN8wZmWYVLjXX4WKkKcl0UDyLAdsdDLNpuKwpbRq5OW39r','APA91bHiCJSIZZn9Tki7m4PAXIPZfPbenpABz3YzVfOpoTqTr4KXAye4iWXUeodr2fglIZpq82NaEldu3zqMCfRrSYj6ABSC2hBsVOWQ6Zg8WD7gtkogYbwM7_EGNB0WSJUKtV7V0DVR','APA91bFAJ1OmcMRVL2UzsX737dpMCyWzQjFF6psnKcOkMUuji6G_zZneNMDwjCMss9Nr1u6MRVnuAmnJWdIUIoa2I7S58whc6QO-e5wlohDfEClwAlgT_DjSRC4oB0HjGxiTzTnXmAlK','APA91bER4-sIcSmLsc5XXW7rulox3t_jkRhC1HH0PCOU5E10_8RlLMrFpusn9exAkXZeTu8zO-euFp8pPr23g5KNuBpbxibUTMgvxyLRhlnQu5MRVqUroW_1633Prg96yW2uY4dnb4zW','APA91bEPKnTYW4EehtDiRtvsV1fJgE08C5ZdPP7I7o-zmQ6TOQ4pJCXUO_4CBbMoxPLCzyRmSY3hS_a-b4ab0QNWn81SMk0RT6iTsw3PMzIEsqpkoALRXTb-yoDviK1y9FEs052FSqwt');//array('APA91bEQQKuZ3YErx1nZZjG75RXiLYLyTIaNInXt50nvW_MfycV6cYw9MuyOXCQRp44EyCtVsbklvDVWG-3Ubuf-E3ladXBYcaCR5Dv7zmBjXeaLC00DM0PZ5oAzlrp6FUhgedD8lo8w');//array('APA91bGRYJ8YXvEZEXYfqa7e4XAqFBe8BFg0h2kseaUlpZEBFm2W5a_48Jdo6t_7hl6bDM0ahXWlE6ZXdWu1CxKpHuudF_61ihQ0UlQfJTtEDl-t6UqSDSDYo7a4dTS7XW6Mj1vroDYp'); 

// prep the bundle
$msg = array
(
	'message' 	=> json_encode(array('message'=>$_REQUEST["msg"],"type"=>$_REQUEST['type'])),
	//'message'=>'Hello Trial Message',
	'title'		=> 'This is a title. title',
	'subtitle'	=> 'This is a subtitle. subtitle',
	'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> 'http://egcrm.cloudapp.net/operations/img/logo.jpg',
	'smallIcon'	=> 'http://egcrm.cloudapp.net/operations/img/logo.jpg'
);

//echo '<pre>';print_r($msg);exit;
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;