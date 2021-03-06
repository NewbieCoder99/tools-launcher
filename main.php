<?php
error_reporting(0);
global $argv;
$server_host = "";
require_once('src/Engine.php');

echo "\n";
section('','',$server_host);
echo "\n";
echo "- Select and typing code : ";
$section = trim(fgets(fopen("php://stdin","r")));

if($section == "") {
	echo "\033[34m- Enter the code first.\n";
} else {
	echo "- Validating ...\n";
	if(section("validate",$section,$server_host) == true) {
		echo "- Validate is successfull.\n";
		echo "- API TOKEN : ";
		$api_token = trim(fgets(fopen("php://stdin","r")));
		if($api_token == "") {
			echo "\033[34m- Enter the api token.\n";
		} else {
			echo "- Path file wordlist : ";
			$wl = trim(fgets(fopen("php://stdin","r")));
			if($wl == "") {
				echo "- Enter the file wordlists path first.\n";
			} else {
				echo "- Email delimiter with password used : ";
				$delim = trim(fgets(fopen("php://stdin","r")));
				if(trim($delim) == "") {
					echo "- Enter the delimiter first.\n";
				} else {
					$agent = php_uname();
					echo created_by(strtoupper($section));
					if(find_str($agent,"Windows") == 1) {
						echo _main_windows($wl,$section,$delim,$server_host,$api_token);
					} else if(find_str($agent,"Linux") == 1) {
						echo _main_linux($wl,$section,$delim,$server_host,$api_token);
					} else {
						echo "- Your Operating System Not Supported \n";
					}
				}
			}
		}
	} else {
		echo "- Account checker list code :\n";
		echo section('check','',$server_host);
	}
}