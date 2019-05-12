<?php
$query_string = $_POST['query_string'];
parse_str($query_string, $data);

$url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=".$data['access_token'];
echo send_curl_request($url);

function send_curl_request($url) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = (string)curl_exec($ch);
	curl_close($ch);
	return $result;
}

?>