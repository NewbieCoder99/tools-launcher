<?php
error_reporting(0);
global $argv;
$server_host = "http://service.fajarpunya.com/c/";
require_once('src/Engine.php');

echo "\n";
section('','',$server_host);
echo "\n";
echo "- Pilih kode : ";
$section = trim(fgets(fopen("php://stdin","r")));

if($section == "")
{
	echo "\033[34m- Masukan kode terlebih dahulu.\n";
} else
{
	echo "- Sedang memvalidasi ...\n";
	if(section("validate",$section,$server_host) == true)
	{
		echo "- Validasi berhasil.\n";
		echo "- Username : ";
		$username = trim(fgets(fopen("php://stdin","r")));
		if($username == "")
		{
			echo "\033[34m- Masukan username terlebih dahulu.\n";
		} else
		{
			echo "- Path file wordlist : ";
			$wl = trim(fgets(fopen("php://stdin","r")));
			if($wl == "")
			{
				echo "- Masukan path file wordlists terlebih dahulu.\n";
			} else
			{
				echo "- Delimiter email dengan password yang digunakan : ";
				$delim = trim(fgets(fopen("php://stdin","r")));
				if(trim($delim) == "")
				{
					echo "- Masukan delimiter terlebih dahulu.\n";
				} else
				{
					$agent = php_uname();
					echo created_by(strtoupper($section));
					if(find_str($agent,"Windows") == 1)
					{
						echo _main_windos($wl,$section,$delim,$server_host,$username);
					} else if(find_str($agent,"Linux") == 1)
					{
						echo _main_linux($wl,$section,$delim,$server_host,$username);
					} else
					{
						echo "- Your Operating System Not Supported \n";
					}
				}
			}
		}
	} else
	{
		echo "- Account checker list code :\n";
		echo section('check','',$server_host);
	}
}