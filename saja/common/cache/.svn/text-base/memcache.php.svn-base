<?php
/**
* Memcache cache access class
* @category platform
* @package  common
* @author   Hoffmann
* @version  $Id$
* @link
* @since    2011-09-05
* @abstract
*/
class cacheMemcache extends cacheAbstract{

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 11211;
    const DEFAULT_PERSISTENT = true;
    const DEFAULT_WEIGHT = 1;
    const DEFAULT_TIMEOUT = 1;
    const DEFAULT_RETRY_INTERVAL = 15;
    const DEFAULT_STATUS = true;
    const DEFAULT_FAILURE_CALLBACK = null;

    /**
    * default server
    *
    * @var array default server configuration
    */

    protected $_defualt_servers =
    	array(
    			'servers' =>
	    			array(
	    				//server 1
	    				array(
    						'host' => self::DEFAULT_HOST,
    						'port' => self::DEFAULT_PORT,
    						'persistent' => self::DEFAULT_PERSISTENT,
    						'weight' => self::DEFAULT_WEIGHT,
    						'timeout' => self::DEFAULT_TIMEOUT,
    						'retry_interval' => self::DEFAULT_RETRY_INTERVAL,
    						'status' => self::DEFAULT_STATUS,
    						'failure_callback' => self::DEFAULT_FAILURE_CALLBACK,
    					),
    ),
    );

    /**
     * Memcache object
     *
     * @var mixed memcache object
     */
    protected $_memcache = null;

    /**
    * constructor
	* Configuration Parameters :
    * $config['host'] => (string) : the name of the memcached server.
    * 								Default : '127.0.0.1'.
    * $config['port'] => (int) : 	the port of the memcached server.
    * 								Default: 11211.
    * $config['persistent'] => (bool) : use or not persistent connections to this memcached server.
    * 								Default: true.
    * $config['weight'] => (int) : 	number of buckets to create for this server which in turn control its.
    *     							probability of it being selected. The probability is relative to the total
    * 								weight of all servers.
    * 								Default: 1.
    * $config['timeout'] => (int) : value in seconds which will be used for connecting to the daemon. Think twice
    * 								before changing the default value of 1 second - you can lose all the
    * 								advantages of caching if your connection is too slow.
    * 								Default : 1
    * $config['retry_interval'] => (int) : 	controls how often a failed server will be retried, the default value
    * 								is 15 seconds. Setting this parameter to -1 disables automatic retry.
    * 								Default: 15
    * $config['status'] => (bool) : 			controls if the server should be flagged as online.
    * 								Default: true
    * $config['failure_callback'] => (callback) : Allows the user to specify a callback function to run upon
    * 								encountering an error. The callback is run before failover
    * 								is attempted. The function takes two parameters, the hostname
    * 								and port of the failed server.
    * 								Default: null
	*
	* @access	public
	* @param	array $config configuration parameters
	* @since	2011-09-05
	*/
    public function __construct($config){

    	if(! extension_loaded('memcache')){
    		log::errorLog('memcache module does not exist');
    		die();
    	}

		if(empty($config)){
			$this->_config=$_defualt_servers;
		} else {

			//assume single server now.
			$this->_config['servers'][0]=$config;
		}

		//initalize memcache object
		$this->_memcache = new Memcache();

		log::returnLog(true);

    }

    /**
    * 連接 Memcache server
    * @access	public
    * @return	boolean
    * @since	2011-09-05
    */
    public function connect() {

    	$rv=true;
    	foreach($this->_config['servers'] as $server){

    		if(! array_key_exists('host', $server)){
    			$server['host'] = self::DEFAULT_HOST;
    		}
    		if(! array_key_exists('port', $server)){
    			$server['port'] = self::DEFAULT_PORT;
    		}
    		if(! array_key_exists('persistent', $server)){
    			$server['persistent'] = self::DEFAULT_PERSISTENT;
    		}
    		if(! array_key_exists('weight', $server)){
    			$server['weight'] = self::DEFAULT_WEIGHT;
    		}
    		if(! array_key_exists('timeout', $server)){
    			$server['timeout'] = self::DEFAULT_TIMEOUT;
    		}
    		if(! array_key_exists('retry_interval', $server)){
    			$server['retry_interval'] = self::DEFAULT_RETRY_INTERVAL;
    		}
    		if(! array_key_exists('status', $server)){
    			$server['status'] = self::DEFAULT_STATUS;
    		}
    		if(! array_key_exists('failure_callback', $server)){
    			$server['failure_callback'] = self::DEFAULT_FAILURE_CALLBACK;
    		}

    		$_rv=$this->_memcache->addServer($server['host'], $server['port'], $server['persistent'], $server['weight'], $server['timeout'], $server['retry_interval'], $server['status'], $server['failure_callback']);
    		$rv=$rv&&$_rv;
    	}

		if($rv) {
    		log::accessLog('memcache connect() successful');
    		log::returnLog(true);
    		return true;
		} else {
			log::errorLog('memcache connect() failed');
			return false;
		}

    }

    /**
    * 關閉 memcache server 連結
    * @access
    * @return
    * @since	2011-09-05
    * @note 據 PHP document , close() 不會關閉 persistent connection
    */
    public function close() {
    	$rv=$this->_memcache->close();

    	if($rv) {
    		log::accessLog('memcache close() successful');
   			log::returnLog(true);
    		return true;
    	} else {
    		log::errorLog('memcache close() failed');
    		return false;
    	}
    }

    /**
    * 寫入 cache data
    * @access	public
    * @return	boolean
    * @since	2011-09-05
    */
    public function write($key,$data,$expire=self::DEFAULT_EXPIRATION_DURATION) {

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

        if(!@$this->_memcache->set($key, $data,0,$expire)) {
			log::errorLog('memcacheLayer write() failed');
			return false ;
        }

		log::accessLog('memcacheLayer write() successful');
		log::returnLog(true);

		return true ;
	}

	/**
	* 取回 cache 資料
	* @access	public
	* @return	string
	* @since	2011-09-05
	*/
	public function read($key){

		if($key == ''){
			log::errorLog('$key is empty');
			return false ;
		}

        if(!$str = $this->_memcache->get($key)) {
			//log::errorLog('memcacheLayer read() failed');
			return false ;
        }

		log::accessLog('memcacheLayer read() successful') ;
		log::returnLog($str);

		return $str ;

	}

	/**
	* 刪除 cache 資料
	* @access	public
	* @return	boolean
	* @since	2011-09-05
	*/
	public function delete($key){

	    if($key == ''){
	        log::errorLog('$key is empty');
	        return false ;
	    }

	    if(!$this->_memcache->delete($key,0)) {
	        log::errorLog('memcacheLayer delete() failed');
	        return false ;
	    }

	    log::accessLog('memcacheLayer delete() successful') ;
	    log::returnLog(true);
	    return true ;

	}

}
