<?php
class curl{

    public $getUrlCount = 0;
    public $curlRetry = 5;
    public $curlTimeOut = 3;
	public $sslVerifypeer = 0;
        
    function getCurl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curlTimeOut); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curlTimeOut); 
        //curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerifypeer); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); 
        $content = curl_exec($ch); 
        $httpCode = curl_getinfo($ch); 
        if(!$httpCode){
            if($this->getUrlCount < $this->curlRetry){
                //echo $this->getUrlCount ; 
                $this->getUrlCount++ ; 
                $this->getCurl($url);
            }
            else{
                return false ; break ;
            }
        }
        curl_close ($ch);
        
        //因為 curl 會爬成字串型態, 為避免誤判, 將字串 'true'/'false' 轉為 Boolean true/false 2012-04-25
        if ('true' == $content) $content = true;
        elseif ('false' == $content) $content = false;
        
        return $content;
    }

    function postCurl($url,$params=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curlTimeOut); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curlTimeOut); 
        //curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerifypeer);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); 
        $content = curl_exec($ch); 
        $httpCode = curl_getinfo($ch); 
        if(!$httpCode){
            if($this->getUrlCount < $this->curlRetry){
                //echo $this->getUrlCount ; 
                $this->getUrlCount++ ; 
                $this->getCurl($url);
            }
            else{
                return false ; break ;
            }
        }
        curl_close ($ch);
        
        //因為 curl 會爬成字串型態, 為避免誤判, 將字串 'true'/'false' 轉為 Boolean true/false 2012-04-25
        if ('true' == $content) $content = true;
        elseif ('false' == $content) $content = false;
        
        return $content;
    }
}
?>