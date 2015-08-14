<?php
/**
* File-based cache access class
* @category platform
* @package  common
* @author   Hoffmann
* @version  $Id$
* @link
* @since    2011-09-05
* @abstract
*/
class cacheFile extends cacheAbstract{

    private $_cacheFileTopDirectory = '/tmp/cachefileCache/';


	/**
	* constructor.
	* Configuration parameters :
	* $config['cacheDirectory'] => (string) : 	Cache directory to hold cache files.
	* 											Default : '/tmp/cachefileCache/'
	* @access	public
	* @param	array configuration parameter
	* @since	2011-09-05
	*/
	public function __construct($config){

		if(isset($config['cacheDirectory']) && !empty($config['cacheDirectory'])) {
			//TODO: sanity-check directory here
			$this->_cacheFileTopDirectory=dirname($config['cacheDirectory']).'/';

		}

	}

	/**
	* 連接虛擬 fileCache server
	* @access	public
	* @return	boolean
	* @since	2011-09-05
	*/
	public function connect() {
	    //pseudo connection
		log::accessLog('fileCache connect() successful');
		log::returnLog(true);
		return true;
	}

	/**
	* 關閉虛擬 fileCache server
	* @access
	* @return
	* @since	2011-09-05
	*/
	public function close() {
		//dummy function
		log::accessLog('fileCache close() success');
		log::returnLog(true);
		return true;
	}

	/**
	* 寫入 cache 資料
	* @access	public
	* @return	boolean
	* @since	2011-09-05
	*/
	public function write($key,$data,$expire=self::DEFAULT_EXPIRATION_DURATION){
		if(!$key){
 			log::errorLog('$key is empty');
			return false ;
		}

		if(!$data){
			log::errorLog('$data is empty');
			return false ;
		}

		if(empty($expire))
		    $expire=self::DEFAULT_EXPIRATION_DURATION;

		$fileSeed=sha1($key);

		$dir1=substr($fileSeed,0,2);
		$dir2=substr($fileSeed,2,2);

		$cacheFileDirectory=$this->_cacheFileTopDirectory."$dir1/$dir2";
		$cacheFile="$cacheFileDirectory/$fileSeed";
		$cacheExpirationFile="{$cacheFile}_expirationTime";

		//is directory exists?
		if(!file_exists($cacheFileDirectory)) {
		     if(!@mkdir($cacheFileDirectory,0700,true)) {
		         log::errorLog('cacheFile write error: directory creation error');
		         return false ;
		     }
		}
		//write cache file
		$fp=fopen($cacheFile,'w');

		if(($rv=fwrite($fp,$data))===false) {
			log::errorLog('fileCache write() failed');
			return false ;
		}
		fclose($fp);

		//write expiration time file
		$fp=fopen($cacheExpirationFile,'w');

		if(fwrite($fp,intval($expire)+intval(time()))===false) {
			log::errorLog('fileCache write() cache failed');
			return false ;
		}
		fclose($fp);

        chmod($cacheFile,0700);
        chmod($cacheExpirationFile,0700);

		log::accessLog('fileCache write() success');
		log::returnLog(true);

		return true ;

	}

	/**
	* 讀入 cache 資料
	* @access	public
	* @return	boolean
	* @since	2011-09-05
	*/
	public function read($key){
		if(!$key){
			log::errorLog('$key is empty');
			return false ;
		}

		//convert key to file. sha1 has 1/2^51 collision possibility.
		$fileSeed=sha1($key);

		$dir1=substr($fileSeed,0,2);
		$dir2=substr($fileSeed,2,2);

		$cacheFile=$this->_cacheFileTopDirectory."$dir1/$dir2/$fileSeed";
		$cacheExpirationFile="{$cacheFile}_expirationTime";

		if(!file_exists($cacheFile))
			return false;

		$expirationTime=intval(file_get_contents($cacheExpirationFile));

		if($expirationTime != 0 && time()>=$expirationTime) {
			@unlink($cacheFile);
			@unlink($cacheExpirationFile);
			return false;
		}

		if(!$str = file_get_contents($cacheFile)){
			log::errorLog('fileCache read() failed');
			return false ;
		}

		log::accessLog('fileCache read() success');
		log::returnLog($str);

		return $str ;
	}

	/**
	* 刪除 cache 資料
	* @access	public
	* @return	string
	* @since	2011-09-08
	*/
	public function delete($key){
	    if(!$key){
	        log::errorLog('$key is empty');
	        return false ;
	    }

	    //convert key to file. sha1 has 1/2^51 collision possibility.
	    $fileSeed=sha1($key);

	    $dir1=substr($fileSeed,0,2);
	    $dir2=substr($fileSeed,2,2);

	    $cacheFile=$this->_cacheFileTopDirectory."$dir1/$dir2/$fileSeed";
	    $cacheExpirationFile="{$cacheFile}_expirationTime";

	    if(!file_exists($cacheFile))
    	    return false;

	    if(unlink($cacheFile) && unlink($cacheExpirationFile)) {
	        log::accessLog('fileCache delete() success');
	        log::returnLog($str);
	        return true;
	    } else {
	        log::errorLog('fileCache delete() failed');
	        return false ;
	    }
	}

}
