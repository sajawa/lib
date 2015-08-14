<?php
/**
 * Global functions.
 * @version $Id: func.php 417 2011-09-29 11:36:13Z chenbin $
 * @since 2011-08-30
 */

/**
 * Check if the URL is correct.
 * TODO: the function is not been able to check the URL's correctness , see its testcase.
 * @author  Neil<yanghl@snsplus.com>
 * @since 2011-08-30 
 * @link https://svn2.mymaji.com/svn/corelib/sources/platform/common/func.php#checkUrl
 * @param string $address <p>the URL for checking</p>
 * @return mixed <p>the URL or boolen if the $address is not a URL</p>. 
 */
function checkUrl($url) {
    $url = trim($url);
    $toplabel = "(?:[a-z]+|[a-z]+(?:[a-z\d]|-)*[a-z\d]+)";
    $domainlabel = "(?:[a-z\d]+|[a-z\d]+(?:[a-z\d]|-)*[a-z\d]+)";
    $hostname = "(?:(?:$domainlabel\.)*$toplabel)";
    $hostnumber = "(?:(?:\d{1,3}\.){3}\d{1,3})";
    $host = "(?:$hostname|$hostnumber)";
    $hostport = "(?:$host(?:[:]\d+)?)";
    $extra = "[!*'(),]";
    $safe = "[$-_.+]";
    $unreserved = "(?:[a-z]|\d|$safe|$extra)";
    $hex = "\d[a-f]"; //upercase ignored
    $escape = "%$hex$hex";
    $uchar = "(?:$unreserved|$escape)";
    $hsegment = "(?:$uchar|[;:@&=])*";
    $search = $hsegment;
    $hpath = "$hsegment(?:[/].$hsegment)*";
    //httpurl  = "http://" hostport [ "/" hpath [ "?" search ]]
    $httpurl = "~^https?:\/\/$hostport(?:\/($hpath)?(?:\?|\?($search))?)?$~i";
    if(! preg_match($httpurl, $url)){
        return false;
    }
    
    return true;
}
/**
 * Get site url
 * @author chenbin
 * @since 2011-09-19
 * @return string 
 */
function getSiteUrl() {
    if(isset($_SERVER['HTTP_HOST'])){
        $scheme = 'http';
        if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on'){
            $scheme .= 's';
        }else if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
            $scheme .= 's';
        }
        $scheme .= '://';
        
        $currentUrl = $scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parts = parse_url($currentUrl);
        
        $port = isset($parts['port']) &&
        (($scheme === 'http://' && $parts['port'] !== 80) ||
        ($scheme === 'https://' && $parts['port'] !== 443))
        ? ':' . $parts['port'] : '';
        
        return $scheme . $parts['host'] . $port;
    }else{
        return '';
    }
}
/**
 * 获取IP地址
 * @author chenbin
 * @since 2011-09-23
 * @return string
 */
function getClientIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)){
        foreach($matches[0] as $xip){
            if(! preg_match('#^(10|172\.16|192\.168)\.#', $xip)){
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}