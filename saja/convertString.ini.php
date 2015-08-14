<?php

class convertString{

	function ipDecode($TargetValue,$sort = '')
	{
                $TargetValue = "0000000000000000000000000000000000000000" . base_convert($TargetValue,10,2) ;
                $TargetValue=substr($TargetValue,-32);
 
                $IP1 = substr($TargetValue,0,8);
                $IP2 = substr($TargetValue,8,8);
                $IP3 = substr($TargetValue,16,8);
                $IP4 = substr($TargetValue,24,8);
		if($sort == 'x86'){
	                $IP = bindec($IP1) . "." . bindec($IP2) . "." . bindec($IP3) . "." . bindec($IP4)       ;
		}
		else{
	                $IP = bindec($IP4) . "." . bindec($IP3) . "." . bindec($IP2) . "." . bindec($IP1)       ;
		}
 
                return $IP;
	}

	function ipEncode($IPaddr,$sort='')
	{
                if ($IPaddr == "") {
                    return 0;
                } else {
		    if($sort == 'x86'){
                    	$ips = split ("\.", "$IPaddr");
                    	return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
		    }
		    else{
                    	$ips = split ("\.", "$IPaddr");
                    	return ($ips[0]  + $ips[1] * 256  + $ips[2] * 256 * 256 + $ips[3] * 256 * 256 * 256 );
		    }
                }
	}

	function ipMask($ip,$mask){
                $arrip = explode('.',$ip);
                $arrmask = explode('.',$mask);
                $maskip = bindec(decbin($arrip[0]) & decbin($arrmask[0])).'.'.bindec(decbin($arrip[1]) & decbin($arrmask[1])).'.'.bindec(decbin($arrip[2]) & decbin($arrmask[2])).'.'.bindec(decbin($arrip[3]) & decbin($arrmask[3]));
                return $maskip ;
	}



	function macDecode($mac){
                $a = 0 ;
                for($i=0;$i<strlen($mac)-1;$i++){
                        $a = ($i+1)/2 ;
                        if($mac[$i] != ""){
                                $mac_arr[$a] = $mac[$i] ;
                                $mac_arr[$a] .= $mac[$i+1] ;
                        }
                }
                if(is_array($mac_arr)){
                        $mac_result = implode(':',$mac_arr);
                }
                return $mac_result ;
	}



	function csvConvertArr($filename)
	{
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		$list =  explode("\n",$contents);
		for($i=0;$i<count($list);$i++){
			if(trim($list[$i])){
				$sub_list_arr = explode(",",$list[$i]);
				$list_arr[] = $sub_list_arr ;
			}
		}
		return($list_arr);
		unset($list_arr);

	}
   
	/* md5 string */
	function md5Hmac($data, $key = ""){
	        $key = str_pad(strlen($key) <= 64 ? $key : pack('H*', md5($key)), 64, chr(0x00));
	        return md5(($key ^ str_repeat(chr(0x5c), 64)) . pack('H*', md5(($key ^ str_repeat(chr(0x36), 64)). $data)));
	}

	function strEncode($data,$key,$encode_type = "crypt"){
	        if($encode_type == "crypt"){
	                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	                return urlencode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$key,$data,MCRYPT_MODE_ECB,$iv));
	        }
                elseif($encode_type == "md5"){
                        return urlencode($data);
                }
                else{
                        echo "Please write \$encode_type type to config.ini.php!!"; exit ;
                }
	}

	function strDecode($data,$key,$encode_type = 'crypt'){
                if($encode_type == "crypt"){
                        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256 , MCRYPT_MODE_ECB);
                        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,urldecode($data),MCRYPT_MODE_ECB,$iv));
                }
                elseif($encode_type == "md5"){
                        return urldecode($data);
                }
                else{
                        echo "Please write \$encode_type type to config.ini.php file!!"; exit ;
                }

	}

	function arrayEncode($arr_data = ""){
	        $serialize_data = "";
	        if(is_array($arr_data)){
	                $serialize_data = base64_encode(serialize($arr_data));
	        }
	        return $serialize_data ;
	}

	function arrayDecode($serialize_data = ""){
		$arr_data = array();
	        if(isset($serialize_data)){
	                $arr_data = unserialize(base64_decode($serialize_data));
	        }
	        return $arr_data ;
	}




	function setCookies($cookie_field="",$cookie_value ="",$login_time = ""){
                global $cookie_domain , $cookie_path ;
                if($cookie_domain != "" || $cookie_domain != ""){
                        if($login_time != ""){
                                setcookie ($cookie_field,$cookie_value,time() + $login_time,$cookie_path,$cookie_domain);
                        }
                        else{
                                setcookie ($cookie_field,$cookie_value,0, $cookie_path ,$cookie_domain);
                        }
                }
                else{
                        if($login_time != ""){
                                setcookie ($cookie_field,$cookie_value,time() + $login_time);
                        }
                        else{
                                setcookie ($cookie_field,$cookie_value);
                        }
                }
	}

	function convertToTimestamp($time_string) {
		return strtotime($time_string);
	}

	function encryptAES128($key, $data) {
	  if(16 !== strlen($key)) $key = hash('MD5', $key, true);
	  $padding = 16 - (strlen($data) % 16);
	  $data .= str_repeat(chr($padding), $padding);
	  return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
	}

	function encryptAES256($key, $data) {
	  if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
	  $padding = 16 - (strlen($data) % 16);
	  $data .= str_repeat(chr($padding), $padding);
	  return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
	}

	function decryptAES128($key, $data) {
	  if(16 !== strlen($key)) $key = hash('MD5', $key, true);
	  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
	  $padding = ord($data[strlen($data) - 1]); 
	  return substr($data, 0, -$padding); 
	}

	function decryptAES256($key, $data) {
	  if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
	  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
	  $padding = ord($data[strlen($data) - 1]); 
	  return substr($data, 0, -$padding); 
	}

}


?>
