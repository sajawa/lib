<?php
//記憶體
class mc{
	function connectionMemcache( $ip = '', $port = '') {
		$this->memcache = new Memcache;
		if ($ip !='' || $port != '') {
			$this->memcache->connect($ip, $port) ; 
			return true ; 
		}             
		return false ;
	}

	function getKey($category,$fun,$act,$custom = '') {
		return md5($category.$fun.$act.$custom);
	}

	function writeMemCache($key,$data,$expire) {
        if ($this->memcache->set($key, $data,0,$expire)) {
			return true ; 
        }
        
		return false ; 
	}

	function getMemKeyList($prefix,$custon="") {
		return false ; 
	}

	function readMemcache($key){
		if ($data = $this->memcache->get($key)) {
			return $data ; 
		}
		
		return false ; 
	}

	function deleteMemcache($key){
		if($data = $this->memcache->delete($key, 0)){
			return $data ; 
		}
		return false ; 
	}
	
	//清空
	function flush(){
		$this->memcache->flush(); 
	}
}



?>
