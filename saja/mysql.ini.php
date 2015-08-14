<?php

class mysql {

        public $_con ;

        protected $_config ;

        public function __construct($config){
				if(!isset($config) && !is_array($config)){
					return false ;
				}
				$this->_config = $config ;
        }



	public function connect(){
                error_log("[mysql.ini] connect()...");
                $this->_con= new mysqli($this->_config['host'], $this->_config['username'], $this->_config['password'], $this->_config['dbname']);

                if ($this->_con->connect_error) {
                        echo 'mysql connect error:' . $this->_con->connect_error ; exit ;
                }

		if(!$this->_con->set_charset($this->_config['charset'])){
			echo 'mysql connect error:' . $this->_con->connect_error; exit ; 
		}

	}

	/**
	* Query
	* @access public
	* @link self()
	* @return boolean
	* @example $this->query();
	*/
	public function query($query){

		if(!$query){
			echo '$query is empty!' ; exit ;
		}

		if(!$this->_result = $this->_con->query($query)){
			echo '$this->_con->query fail! : '.$this->_con->error.": $query" ; exit ;
		}

                $this->queryAssign('query',$query);
		return true  ;

	}



        function getQueryRecord($query){
                $arr = "";
                $this->queryAssign('getQueryRecord',$query);

		if($this->query($query)===false){
			return false ;
		}


		$arr=array();
		while($raw = $this->_result->fetch_assoc()){
			$arr[] = $raw ;
		}
                $result["table"]["record"] =  $arr ;
                return  $result ;
        }


	function queryAssign($type , $query){
		$this->queryArr[$type][] = $query ; 
	}


    function recordPage($rows,$obj){
        $max_page = $obj->config->max_page;		//20
        $max_range = $obj->config->max_range;	//10
        
        if(!isset($obj->io->input["get"]["p"])) { $obj->io->input["get"]["p"] = ""; }
        if(trim($obj->io->input["get"]["p"]) == "" or $obj->io->input["get"]["p"] < 1) { $obj->io->input["get"]["p"] = 1; }
        
        $lastpage = ceil($rows / $max_page);
        
        if($obj->io->input["get"]["p"] > $lastpage &&  $obj->io->input["get"]["p"] > 1) { $obj->io->input["get"]["p"] = $lastpage; }
        
        $rec_start = ($obj->io->input["get"]["p"] - 1) * $max_page + 1;
        $rec_end   = $rec_start + $max_page - 1;
        $ploops    = floor(($obj->io->input["get"]["p"] - 1) / $max_range) * $max_range + 1;
        $ploope    = $ploops + $max_range - 1;
        
        if($ploope >= $lastpage) { $ploope = $lastpage; }
        
        $ppg       = $obj->io->input["get"]["p"] - 1;
        $npg       = $obj->io->input["get"]["p"] + 1;
        //if($ppg <= 0) $ppg = $lastpage;
        if($ppg <= 0) $ppg = 1;
        //if($npg > $lastpage) $npg=1;
        if($npg > $lastpage) $npg  = $lastpage;
        if($rec_end > $rows) $rec_end = $rows;

        $page["rec_start"]       = $rec_start;
        $page["rec_end"]         = $rec_end;
        $page["firstpage"]       = 1;
        $page["lastpage"]        = $lastpage;
        $page["previousrange"]   = $ploops - $max_range;
        $page["nextrange"]       = $ploops + $max_range;
        $page["previouspage"]    = $ppg;
        $page["nextpage"]        = $npg;
        $page["thispage"]        = $obj->io->input["get"]["p"] ;
        $page["total"]           = $rows ;
        $page["loop"]            = $lastpage+1 ;
        $page["max_page"]         = $max_page ;
        $page["max_range"]        = $max_range ;
        for($i=$ploops;$i <= $ploope;$i++){
            $page["item"][]["p"] = $i ;
        }
        if(!empty($obj->io->input["get"]["p"])){
            if (empty($obj->status["path"]["page"])) {
                $obj->status["path"]["page"] = '&p='.$page["thispage"] ;
            } else {
                $obj->status["path"]["page"] .= ('&'.'p='.$page["thispage"]) ;
            }
        }
        return $page;
    }



}

?>
