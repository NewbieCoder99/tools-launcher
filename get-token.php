<?php
error_reporting(0);
global $argv;
$server_host = "";
require_once('src/Engine.php');

echo created_by("GET TOKEN");
echo "\n";

echo "- Type this selection (add/edit) : ";
$selection = trim(fgets(fopen("php://stdin","r")));

if(empty($selection)) {
echo "\033[31m- Selection is required.\n";
return;
}

echo "- Name : ";
$name = trim(fgets(fopen("php://stdin","r")));

if(empty($name)) {
echo "\033[31m- Name is required.\n";
return;
}

echo "- Email : ";
$email = trim(fgets(fopen("php://stdin","r")));

if(empty($email)) {
echo "\033[31m- Email is required.\n";
return;
}

echo "- Password : ";
$password = trim(fgets(fopen("php://stdin","r")));

if(empty($password)) {
echo "\033[31m- Password is required.\n";
return;
}

$ch = curl_init();
$data = "email=".$email."&password=".$password.'&name='.$name.'&selection='.$selection;
curl_setopt_array($ch, array(
	CURLOPT_URL => $server_host.'/get-token',
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_FOLLOWLOCATION => 1,
	CURLOPT_VERBOSE => 0,
	CURLOPT_HEADER => 0, #0 ? 1 : 0,
	CURLOPT_NOBODY => 0, #0 ? 1 : 0,
	CURLOPT_TIMEOUT => 20,
	CURLOPT_CONNECTTIMEOUT => 30,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => $data,
	CURLOPT_HTTPHEADER => [
		'X-Requested-With: XMLHttpRequest'
	]
));

$response = curl_exec($ch);
curl_close($ch);
write_logs('token.txt', $response,'');
echo "\n";
echo "[+] ".$response;
echo "\n";
echo "\n";