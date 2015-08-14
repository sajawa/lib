<?php
class Model
{
	public $db_type ;
	public $path_class ;
	public $var_chk = "N";
	public $query_arr = array() ;



	function query($query,$obj){
                global $mdb ;
		//$this->model->query_arr["query"][] = $query ; 
                //$db_res = MetabaseQuery($mdb,$query_chartset);
                $db_res = MetabaseQuery($mdb,$query);

                if(!$db_res){ 
                        if($obj->config->debug_mod == "Y"){
                                $this->sqlError($mdb,$query);
                        }
                        else{
                                $obj->message->alertErr("system_err",$obj);
                        }
		}
		return $db_res ; 
 	}

	function devMode($obj,$query){
                if(isset($obj->configure["dev"])){
			if($obj->configure["dev"] == 'Y'){
				//print_r($error);
				echo "<pre>";
				echo "This is Development model .\n";
				echo " \nQuery => \n ";
				echo "\n";
				print_r($query);
				echo "\n";
				echo " \nObject => \n  ";
				echo "\n";
				print_r($obj);
				exit ;
			}
		}
	}


	function sqlError($mdb,$query){
		global $metabase_databases ; 
		$message = "This is Development model .\n";
		$message .= "Error Message : ".MetabaseError($mdb) . "\n" ;
		$message .= "Sql Error (".mysql_errno($metabase_databases[1]->connection).")  : ".mysql_error($metabase_databases[1]->connection) . "\n" ;
		$message .= "SQL QUERY : ".$query. "\n" ;
		
		//print_r($error);
		echo "<pre>";
		echo $message;
		echo " \n GET =>";
		print_r($_GET);
		echo " \n POST =>";
		print_r($_POST);
		echo " \n SERVER =>";
		print_r($_SERVER);
		exit ;
	}



	function isTable($obj,$table_name,$database_name){
                global $mdb  ;	
		$query = "SHOW TABLES FROM `".$database_name."` like '".$table_name."'";
		$db_res = @$this->query($query,$obj) ;
                $rows= @MetabaseNumberOfRows($mdb,$db_res);
		if($rows == 0){
			return false ;
		}
		else{
			return true ; 
		}
	}



	function getQueryRecord($query,$obj){
                global $mdb  ;	
		$arr = "";
		$this->queryAssign('getQueryRecord',$query);
                //$db_res = MetabaseQuery($mdb,$query_chartset);
                //$db_res = MetabaseQuery($mdb,$query);
		$db_res = $this->query($query,$obj) ;
                $rows= @MetabaseNumberOfRows($mdb,$db_res);
                @MetabaseGetColumnNames($mdb,$db_res,$db_field_name) ;
                if($rows)
                {
                        $sno     = 0 ;
			$gno = array() ;
			$sgno = 0 ;
                        for($row=0;$row<$rows;$row++)
                        {
                                if(is_array($db_field_name)){
					$sno++ ;
                                        foreach($db_field_name as $key => $value){
						
                                                $arr["record"][$row]["no"]  = $row ;
                                                $arr["record"][$row]["sno"] = $sno ;
                                                $arr["record"][$row][$key] = @MetabaseFetchResult($mdb,$db_res,$row,$key) ;
                                        }
                                }
                        }
                }
		$result["table"] =  $arr ; 
                return  $result ;
	}



	function insertRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["insert"]["database_name"])){
                	if($obj->configure["insert"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["insert"]["database_name"]."`.";

                	}
		}
		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		$field_prefix = "`prefixid`,";
		$prefix_value = "'".$obj->config->default_prefix_id."',";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$field_prefix = "";
				$prefix = "";
				$prefix_value = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["insert"]["table_name"])){
			$insert_field = "";
			$insert_value = "";
			$query = "";
			if(isset($obj->configure["insert"]["field"])){
				if(is_array($obj->configure["insert"]["field"])){
					$query = "INSERT INTO ".$database_name."`".$prefix.$obj->configure["insert"]["table_name"]."`(".$field_prefix ;
					$no = 0 ; 
					foreach($obj->configure["insert"]["field"] as $fk => $fv){
						if($no == 0){
							$insert_field .= "`".$fv["id"]."`";
							if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
								$insert_value .= 'now()';
							}
							else{
								$insert_value .= "'".$obj->io->input["post"][$fv["id"]]."'";
							}
						}						
						else{
							$insert_field .= ",`".$fv["id"]."`";
							if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
								$insert_value .= ',now()';
							}
							else{
								$insert_value .= ",'".$obj->io->input["post"][$fv["id"]]."'";
							}

						}
						$no++;	
					}
					$query .= $insert_field.")" ; 
					$query .= " VALUES(".$prefix_value;
					$query .= $insert_value.")" ; 
					/*
					$pky_field = "" ;
					if(isset($obj->io->input["get"]["id"])){
						if($obj->io->input["get"]["id"] != ""){
							//($obj->configure["pky"]) ; 
							$arr = explode('/',$obj->io->input["get"]["id"]);
							foreach($arr as $ak => $av){
								if(is_array($obj->configure["pky"])){
									$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
								}
								
							}
							
						}
                        
					}
					$query .= $pky_field ; 
					*/
				}
			}
		}
		

		//echo $query ; exit ;
		$this->devMode($obj,$query) ;
		$this->query($query,$obj);
		return mysql_insert_id() ; 



	}




	function insertLangRecord($pkyid,$obj){
                global $mdb  ;	
		if(isset($obj->configure["insert_lang"]["database_name"])){
                	if($obj->configure["insert_lang"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["insert_lang"]["database_name"]."`.";

                	}
		}
		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		$field_prefix = "`prefixid`,";
		$prefix_value = "'".$obj->config->default_prefix_id."',";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$field_prefix = "";
				$prefix = "";
				$prefix_value = "";
				$prefix_where = " 1=1 ";
			}
		}
		if(isset($obj->configure["insert_lang"]["table_name"])){
			$insert_field = "";
			$insert_value = "";
			$query = "";
			if(isset($obj->configure["insert_lang"]["field"])){
				$obj->lang->getLanguage($obj);
				//echo "<pre>";print_r($obj->configure["insert"]["lan_field"]); exit ;
				//echo "<pre>";print_r($obj->lang->language); exit ;
				$lan_field = $obj->configure["insert_lang"]["field"] ; 
				if(is_array($obj->lang->language["table"]["record"])){
					foreach($obj->lang->language["table"]["record"] as $lk => $lv){
						if(is_array($lan_field)){
							foreach($lan_field as $lfk => $lfv){
								//echo $obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]] ; 
								if(isset($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]])){
									if($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]] != ""){
										$query = "
										INSERT INTO 
										".$database_name."`".$prefix.$obj->configure["insert_lang"]["table_name"]."`
										(
										".$field_prefix."
										`tableid`,
										`pkyid`, 
										`lanid`, 
										`pid`, 
										`vid`, 
										`value`, 
										`insertt`
										)
										VALUES(
										'".$obj->config->default_prefix_id."',
										'".$obj->configure["insert_lang"]["tableid"]."',
										'".$pkyid."',
										'".$lv["lanid"]."',
										'".$obj->config->project_id."',
										'".$lfv["id"]."',
										'".$obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]]."',
										now()
										)
										";
										$this->devMode($obj,$query) ;
										$this->query($query,$obj);
										//echo "<pre>";	print_r($query); 

									}
								}
							}
						}
					}
				}


			}
			//exit ;
			return true ; 
		}
		return false ; 
	}



	function getUpdateMutiSelectRecord($obj){
                if(isset($obj->io->input["post"]["selectlist"])){
                        $select_arr = explode(',',$obj->io->input["post"]["selectlist"]);
                        if(is_array($select_arr)){
                                foreach($select_arr as $sak => $sav){
                                        for($i=0;$i<=$obj->io->input["post"]["list"];$i++){
                                                if(isset($obj->io->input["post"]["id".$i])){
                                                        if($obj->io->input["post"]["id".$i] == $sav){
                                                                //$select_arr[$i] = $sav ;
                                                                $is_select_arr[$i] = $sav ;
                                                        }
                                                }
                                        }
                                }
                        }
                }
		return $is_select_arr ; 
	}



	function updateMutiRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["update"]["database_name"])){
                	if($obj->configure["update"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["update"]["database_name"]."`.";

                	}
		}
		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}
		if(isset($obj->configure["update"]["table_name"])){
			if(isset($obj->configure["update"]["field"])){
				if(is_array($obj->configure["update"]["field"])){
					$is_select_arr = $this->getUpdateMutiSelectRecord($obj);
					//print_r($is_select_arr); exit ;
					if(is_array($is_select_arr)){
						foreach($is_select_arr as $sak => $sav){
							$no= 0 ; 
							$update_field= "" ; 
							$query = "UPDATE ".$database_name."`".$prefix.$obj->configure["update"]["table_name"]."` SET " ;
							foreach($obj->configure["update"]["field"] as $fk => $fv){	
								//echo $fv["id"].$sak . "\n" ; 
								if(
									isset($obj->io->input["post"][$fv["id"].$sak]) ||
									$fv["id"] == 'insertt' || 
									$fv["id"] == 'modifyt'
								){
									if($no == 0){
										if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
											$update_field .= "`".$fv["id"]."` = now()";
										}
										else{
											$update_field .= "`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"].$sak]."'";
										}
									}						
									else{
										if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
											$update_field .= ",`".$fv["id"]."` = now()";
										}
										else{
											$update_field .= ",`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"].$sak]."'";
										}
									}
									$no++;	
								}
							}
							$query .= $update_field ; 
							$query .= " WHERE ".$prefix_where;
							$pky_field = "" ;
							$arr = explode('/',$sav);
							foreach($arr as $ak => $av){
								if(is_array($obj->configure["pky"])){
									$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
								}
								
							}
									
							$query .= $pky_field ; 

							$this->devMode($obj,$query) ;
							$this->query($query,$obj);
						}
					}
				}
			}
		}
		return true ; 

	}



	function updateMutiLangRecord($obj){
                global $mdb  ;	

		if(isset($obj->configure["update_lang"]["database_name"])){
                	if($obj->configure["update_lang"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["update_lang"]["database_name"]."`.";

                	}
		}


		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}


		if(isset($obj->configure["update_lang"]["table_name"])){
			if(isset($obj->configure["update_lang"]["field"])){
			    if(is_array($obj->configure["update_lang"]["field"])){
				$obj->lang->getLanguage($obj);
				$lan_field = $obj->configure["insert_lang"]["field"] ; 
				if(is_array($obj->lang->language["table"]["record"])){
					foreach($obj->lang->language["table"]["record"] as $lk => $lv){
						if(is_array($lan_field)){
							foreach($lan_field as $lfk => $lfv){
								if(isset($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]])){
									if($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]] != ""){
										$query = "
										UPDATE 
										".$database_name."`".$prefix.$obj->configure["insert_lang"]["table_name"]."`
										SET 
										`value` = '".$obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]]."', 
										`modifyt` = now()
										";
										$query .= " WHERE ".$prefix_where;
										$query .= "
											AND `tableid` = '".$obj->configure["insert_lang"]["tableid"]."'
											AND `pkyid` = '".$obj->io->input["get"]["id"]."'
											AND `lanid` = '".$lv["lanid"]."'
											AND `pid` = '".$obj->config->project_id."'
											AND `vid` = '".$lfv["id"]."'
										";
										//echo "<pre>";echo $query ; exit ;
										$this->devMode($obj,$query) ;
										$this->query($query,$obj);

									}
								}
							
							}
						}
					}
				}
			     }
				/*
					if(isset($obj->io->input["post"]["selectlist"])){
						$select_arr = explode(',',$obj->io->input["post"]["selectlist"]);
						if(is_array($select_arr)){
							foreach($select_arr as $sak => $sav){
								for($i=0;$i<=$obj->io->input["post"]["list"];$i++){
									if(isset($obj->io->input["post"]["id".$i])){
										if($obj->io->input["post"]["id".$i] == $sav){
											//$select_arr[$i] = $sav ; 
											$is_select_arr[$i] = $sav ; 
										}
									}
								}
							}
						}
					}
					//print_r($is_select_arr); exit ;
					if(is_array($is_select_arr)){
						foreach($is_select_arr as $sak => $sav){
							$no= 0 ; 
							$update_field= "" ; 
							$query = "UPDATE ".$database_name."`".$prefix.$obj->configure["update_lang"]["table_name"]."` SET " ;
							foreach($obj->configure["update_lang"]["field"] as $fk => $fv){	
								//echo $fv["id"].$sak . "\n" ; 
								if(
									isset($obj->io->input["post"][$fv["id"].$sak]) ||
									$fv["id"] == 'insertt' || 
									$fv["id"] == 'modifyt'
								){
									if($no == 0){
										if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
											$update_field .= "`".$fv["id"]."` = now()";
										}
										else{
											$update_field .= "`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"].$sak]."'";
										}
									}						
									else{
										if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
											$update_field .= ",`".$fv["id"]."` = now()";
										}
										else{
											$update_field .= ",`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"].$sak]."'";
										}
									}
									$no++;	
								}
							}
							$query .= $update_field ; 
							$query .= " WHERE ".$prefix_where;
							$pky_field = "" ;
							$arr = explode('/',$sav);
							foreach($arr as $ak => $av){
								if(is_array($obj->configure["pky"])){
									$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
								}
								
							}
									
							$query .= $pky_field ; 

							//echo $query."<br>" ; 
							$this->devMode($obj,$query) ;
							$this->query($query,$obj);
						}
					}

				*/
			}
		}
		return true ; 

	}





	function updateRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["update"]["database_name"])){
                	if($obj->configure["update"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["update"]["database_name"]."`.";

                	}
		}


		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["update"]["table_name"])){
			$update_field = "";
			$query = "";
			if(isset($obj->configure["update"]["field"])){
				if(is_array($obj->configure["update"]["field"])){
					$query = "UPDATE ".$database_name."`".$prefix.$obj->configure["update"]["table_name"]."` SET " ;
					$no = 0 ; 
					foreach($obj->configure["update"]["field"] as $fk => $fv){
						if(
							isset($obj->io->input["post"][$fv["id"]]) ||
							$fv["id"] == 'insertt' ||
							 $fv["id"] == 'modifyt'
						){
							if($no == 0){
								if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
									$update_field .= "`".$fv["id"]."` = now()";
								}
								else{
									$update_field .= "`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"]]."'";
								}
								
							}						
							else{
								if($fv["id"] == 'insertt' || $fv["id"] == 'modifyt'){
									$update_field .= ",`".$fv["id"]."` = now()";
								}
								else{
									$update_field .= ",`".$fv["id"]."` = '".$obj->io->input["post"][$fv["id"]]."'";
								}
							}
							$no++;	
						}
					}
					$query .= $update_field ; 
					$query .= " WHERE ".$prefix_where;
					$pky_field = "" ;
					if(isset($obj->io->input["get"]["id"])){
						if($obj->io->input["get"]["id"] != ""){
							//($obj->configure["pky"]) ; 
							$arr = explode('/',$obj->io->input["get"]["id"]);
							foreach($arr as $ak => $av){
								if(is_array($obj->configure["pky"])){
									$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
								}
								
							}
							
						}
                        
					}
					$query .= $pky_field ; 
				}
			}
		}

		$this->devMode($obj,$query) ;
		$this->query($query,$obj);
		return true ; 



	}


	function updateLangRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["update_lang"]["database_name"])){
                	if($obj->configure["update_lang"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["update_lang"]["database_name"]."`.";

                	}
		}


		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["update_lang"]["table_name"])){
			$update_field = "";
			$query = "";
			if(isset($obj->configure["update_lang"]["field"])){
			    if(is_array($obj->configure["update_lang"]["field"])){
				$obj->lang->getLanguage($obj);
				$lan_field = $obj->configure["insert_lang"]["field"] ; 
				if(is_array($obj->lang->language["table"]["record"])){
					foreach($obj->lang->language["table"]["record"] as $lk => $lv){
						if(is_array($lan_field)){
							foreach($lan_field as $lfk => $lfv){
								//echo $obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]] ; 
								if(isset($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]])){
									if($obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]] != ""){
										$query = "
										UPDATE 
										".$database_name."`".$prefix.$obj->configure["insert_lang"]["table_name"]."`
										SET 
										`value` = '".$obj->io->input["post"][$lfv["id"]."_".$lv["lanid"]]."', 
										`modifyt` = now()
										";
										$query .= " WHERE ".$prefix_where;
										$query .= "
											AND `tableid` = '".$obj->configure["insert_lang"]["tableid"]."'
											AND `pkyid` = '".$obj->io->input["get"]["id"]."'
											AND `lanid` = '".$lv["lanid"]."'
											AND `pid` = '".$obj->config->project_id."'
											AND `vid` = '".$lfv["id"]."'
										";
										//echo "<pre>";echo $query ; exit ;
										$this->devMode($obj,$query) ;
										$this->query($query,$obj);

									}
								}
							}
						}
					}
				}
			    }
			}
		}

		$this->devMode($obj,$query) ;
		$this->query($query,$obj);
		return true ; 



	}






	function deleteRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["delete"]["database_name"])){
                	if($obj->configure["delete"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["delete"]["database_name"]."`.";

                	}
		}


		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["delete"]["table_name"])){
			$update_field = "";
			$query = "";
			$query = "DELETE FROM ".$database_name."`".$prefix.$obj->configure["delete"]["table_name"]."` " ;
			$query .= " WHERE ".$prefix_where;
			$pky_field = "" ;
			if(isset($obj->io->input["get"]["id"])){
				if($obj->io->input["get"]["id"] != ""){
					//($obj->configure["pky"]) ; 
					$arr = explode('/',$obj->io->input["get"]["id"]);
					foreach($arr as $ak => $av){
						if(is_array($obj->configure["pky"])){
							$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
						}
						
					}
					
				}
                
			}
			$query .= $pky_field ; 
		}
		if($pky_field == ''){
			echo "Id is not null!"; exit ;
		}
		$this->devMode($obj,$query) ;
		$this->query($query,$obj);
		return true ; 



	}



	function deleteLangRecord($obj){
                global $mdb  ;	
		if(isset($obj->configure["delete_lang"]["database_name"])){
                	if($obj->configure["delete_lang"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["delete_lang"]["database_name"]."`.";

                	}
		}

		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}
		if(isset($obj->configure["delete_lang"]["table_name"])){
			$query = "";
			$query = "DELETE FROM ".$database_name."`".$prefix.$obj->configure["delete_lang"]["table_name"]."` " ;
			$query .= " WHERE ".$prefix_where;
			$query .= " AND 
				    `tableid` = '".$obj->configure["delete_lang"]["tableid"]."' AND 
				    `pkyid` = '".$obj->io->input["get"]["id"]."' AND 
				    `pid` = '".$obj->config->project_id."' 

			";
			$this->devMode($obj,$query) ;
			$this->query($query,$obj);
			return true ; 
		}
		return false ;


	}




	function deleteMutiRecord($obj){
                global $mdb  ;	

		if(isset($obj->configure["delete"]["database_name"])){
                	if($obj->configure["delete"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["delete"]["database_name"]."`.";

                	}
		}

		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["delete"]["table_name"])){
			if(isset($obj->io->input["post"]["selectlist"])){
				$select_arr = explode(',',$obj->io->input["post"]["selectlist"]);
				if(is_array($select_arr)){
					foreach($select_arr as $sak => $sav){
						for($i=0;$i<=$obj->io->input["post"]["list"];$i++){
							if(isset($obj->io->input["post"]["id".$i])){
								if($obj->io->input["post"]["id".$i] == $sav){
									//$select_arr[$i] = $sav ; 
									$is_select_arr[$i] = $sav ; 
								}
							}
						}
					}
				}
			}
			//print_r($is_select_arr); exit ;
			if(is_array($is_select_arr)){
				foreach($is_select_arr as $sak => $sav){
					$query = "DELETE FROM ".$database_name."`".$prefix.$obj->configure["delete"]["table_name"]."` " ;
					$query .= " WHERE ".$prefix_where;
					$pky_field = "" ;
					$arr = explode('/',$sav);
					foreach($arr as $ak => $av){
						if(is_array($obj->configure["pky"])){
							$pky_field .= " and `".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
						}
						
					}
					$query .= $pky_field ; 
					//echo $query."<br>" ; 
					if($pky_field == ''){
						echo "Id is not null!"; exit ;
					}
					$this->devMode($obj,$query) ;
					$this->query($query,$obj);
				}
			}
		}
		return true ; 

	}



	function deleteMutiLangRecord($obj){
                global $mdb  ;	

		if(isset($obj->configure["delete_lang"]["database_name"])){
                	if($obj->configure["delete_lang"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["delete_lang"]["database_name"]."`.";

                	}
		}

		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if(isset($obj->configure["delete_lang"]["table_name"])){
			if(isset($obj->io->input["post"]["selectlist"])){
				$select_arr = explode(',',$obj->io->input["post"]["selectlist"]);
				if(is_array($select_arr)){
					foreach($select_arr as $sak => $sav){
						for($i=0;$i<=$obj->io->input["post"]["list"];$i++){
							if(isset($obj->io->input["post"]["id".$i])){
								if($obj->io->input["post"]["id".$i] == $sav){
									//$select_arr[$i] = $sav ; 
									$is_select_arr[$i] = $sav ; 
								}
							}
						}
					}
				}
			}
			//print_r($is_select_arr); exit ;
			if(is_array($is_select_arr)){
				foreach($is_select_arr as $sak => $sav){
					$query = "";
					$query = "DELETE FROM ".$database_name."`".$prefix.$obj->configure["delete_lang"]["table_name"]."` " ;
					$query .= " WHERE ".$prefix_where;
					$query .= " AND 
						    `tableid` = '".$obj->configure["delete_lang"]["tableid"]."' AND 
						    `pkyid` = '".$sav."' AND 
						    `pid` = '".$obj->config->project_id."' 

					";
					//echo $query."<br>" ; 
					if($sav == ''){
						echo "Id is not null!"; exit ;
					}
					$this->devMode($obj,$query) ;
					$this->query($query,$obj);
				}
			}
		}
		return true ; 

	}







	function getOneRecord($obj){
                global $mdb ;
		$join_field = "";
		$search_field = "";
		$pky_field = "";
		$field =  $this -> getSelectPageField($obj);
		$join_field = $this->getJoinField($obj);
		$search_field = $this->getSearchField($obj);
		if(isset($obj->configure["select"]["database_name"])){
                	if($obj->configure["select"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["select"]["database_name"]."`.";

                	}
		}

		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`".$obj->configure["select"]["table_as_name"]."`.`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}
		$query = "SELECT ".$field." FROM ".$database_name."`".$prefix.$obj->configure["select"]["table_name"]."` AS `".$obj->configure["select"]["table_as_name"]."`" ;
		if(is_array($join_field)){
			foreach($join_field as $jfk => $jfv){
				$query .= "\n".$jfv	;
			}
		}
		if(isset($obj->io->input["get"]["id"])){
			if($obj->io->input["get"]["id"] != ""){
				//($obj->configure["pky"]) ; 
				$arr = explode('/',$obj->io->input["get"]["id"]);
				foreach($arr as $ak => $av){
					if(is_array($obj->configure["pky"])){
						$pky_field .= " and `".$obj->configure["select"]["table_as_name"]."`.`".$obj->configure["pky"][$ak]."` = '".$av."'" ; 
					}
					
				}
				
			}

		}
		$query .= "\nWHERE ".$prefix_where." ".$pky_field.$search_field;
                if(isset($obj->configure["dev"])){
			if($obj->configure["dev"] == 'Y'){
				echo "<pre>";		
				if(isset($query_num)){
					echo $query_num ; 				
				}
				echo "<br>";		
				echo "<br>";		
				echo $query ; 		
				exit ;
			}
		}
		$query_limit = " limit 1";
		$query .= $query_limit ;
		return $this->getQueryRecord($query,$obj) ;
	}

	function getRelation($obj){
		if(isset($obj->configure["relation"])){
			$arr = array();
			$sort_field = "";
			if(is_array($obj->configure["relation"])){
				foreach($obj->configure["relation"] as $rk => $rv){
					$prefixid = $obj->config->default_prefix ; 
					$prefixid_where = "prefixid = '".$obj->config->default_prefix_id."'";
					if(isset($rv["prefix"])){
						if($rv["prefix"] == "N"){
							$prefixid = "";
							$prefixid_where = "1=1";
						}
					}
					$sub_query = "";
					if(isset($rv["where"])){
						$sub_query = $rv["where"];

					}
					if(isset($rv["sort"])){
						if(is_array($rv["sort"])){
							foreach($rv as $sk => $sv){
								if($sk == 'sort'){ // get default sort query 
								  	$sort_field = " order by ";
									if(is_array($sv)){
										$no = 0 ; 
										foreach($sv as $ssk => $ssv){
											if($no == 0){
												$sort_field .= '`'.$ssk.'`'." ".$ssv ;
											}
											else{
												$sort_field .= ',`'.$ssk.'`'." ".$ssv ;
											}
											$no++ ; 
										}
									}
								}
							}
						}
						else{
							if(isset($rv["sort"])){
								$sort_field = " ".$rv["sort"] ; 
							}
						}
                                	}


					//$arr[$rv["table_name"]] = $rv["table_name"]  ; 
					$query ="
                                        SELECT  
					*
                                        FROM `".$rv["database_name"]."`.`".$prefixid.$rv["table_name"]."`
					where 
					".$prefixid_where."
					".$sub_query."
					".$sort_field."
					";
					//echo $query ; exit ;
					$arr["relation"][$rv["table_name"]] = $obj->getQueryRecord($query) ; 

				}
			}
			return $arr ;
		}
	}

	function getPageRecord($obj){
                global $mdb ;
		$join_field = "";
		$search_field = "";
		$group_by_field = "";
		$sort_field = "";
		$field =  $this -> getSelectPageField($obj);
		$group_by_field = $this->getGroupByField($obj);
		$sort_field = $this->getSortField($obj);
		$search_field = $this->getSearchField($obj);
		$join_field = $this->getJoinField($obj);


		if(isset($obj->configure["select"]["database_name"])){
                	if($obj->configure["select"]["database_name"] != ""){
                        	$database_name = "`".$obj->configure["select"]["database_name"]."`.";

                	}
		}

		$prefix = $obj->config->default_prefix ; 
		$prefix_where = "`".$obj->configure["select"]["table_as_name"]."`.`prefixid` = '".$obj->config->default_prefix_id."'";
		if(isset($obj->configure["prefix"])){
			if($obj->configure["prefix"] == 'N'){
				$prefix = "";
				$prefix_where = " 1=1 ";
			}
		}

		if($group_by_field != ""){
			$row_field = " count( DISTINCT ".$group_by_field.") as num" ;
		}
		else{
			$row_field = " count(*) as num" ;
		}

		$query = "SELECT ".$field." FROM ".$database_name."`".$prefix.$obj->configure["select"]["table_name"]."` AS `".$obj->configure["select"]["table_as_name"]."`" ;
		$query_num = "SELECT ".$row_field." FROM ".$database_name."`".$prefix.$obj->configure["select"]["table_name"]."` AS `".$obj->configure["select"]["table_as_name"]."`" ;
		
		if(!isset($obj->configure["select"]["max_page"])){
			$obj->configure["select"]["max_page"] = $obj->config->max_page ;
		}

		if(!isset($obj->configure["select"]["max_range"])){
			$obj->configure["select"]["max_range"] = $obj->config->max_range ;
		}


		if(is_array($join_field)){
			foreach($join_field as $jfk => $jfv){
				$query .= "\n".$jfv	;
				$query_num .= "\n".$jfv	;
			}
			
		}

		$query .= "\nWHERE ".$prefix_where;
		$query_num .= "\nWHERE ".$prefix_where;
		if($search_field != ''){
			$query .= " ".$search_field;
			$query_num .= " ".$search_field;
		}

		if($group_by_field != ''){
			$query .= "\nGROUP BY ".$group_by_field;
		}
		if($sort_field != ''){
			$query .= "\nORDER BY ".$sort_field;
		}


                if(isset($obj->configure["dev"])){
			if($obj->configure["dev"] == 'Y'){
				echo "<pre>";		
				echo $query_num ; 				
				echo "<br>";		
				echo "<br>";		
				echo $query ; 		
				exit ;
			}
		}

		$rows = $this->getPageRows($query_num,$obj) ;
                $page = $this->recordPage($rows,$obj);
		//echo "<pre>"; print_r($page); exit ;
		$query_limit = " limit ".($page["rec_start"]-1).",".($obj->configure["select"]["max_page"]);
		
		$query .= $query_limit ;
		//echo "<pre>";print_r($this->getQueryRecord($query,$obj)); exit ;

		$arr = "";
		$this->queryAssign('getPageRecord',$query);
		$db_res = $this->query($query,$obj) ;
                $rows= @MetabaseNumberOfRows($mdb,$db_res);
                @MetabaseGetColumnNames($mdb,$db_res,$db_field_name) ;
                if($rows)
                {
                        $sno     = $page["rec_start"]-1 ;
			$gno = array() ;
			$sgno = 0 ;
                        for($row=0;$row<$rows;$row++)
                        {
                                if(is_array($db_field_name)){
					$sno++ ;
                                        foreach($db_field_name as $key => $value){
						
                                                $arr["record"][$row]["no"]  = $row ;
                                                $arr["record"][$row]["sno"] = $sno ;
                                                $arr["record"][$row][$key] = @MetabaseFetchResult($mdb,$db_res,$row,$key) ;
                                        }
                                }
                        }
                }


		$result["table"] = $arr ; 
		$result["page"] = $page ; 

		return $result ; 
		

	}




	function getGroupByField($obj){
	
		$group_by = "";
		if(is_array($obj->configure["select"])){
			foreach($obj->configure["select"] as $sk => $sv){
				
				if($sk == 'group_by'){
					if(is_array($sv)){
						$no = 0 ; 
						foreach($sv as $ssv){
							if($no == 0){
								$group_by .= '`'.$obj->configure["select"]["table_as_name"].'`.`'.$ssv.'`' ;
							}
							else{
								$group_by .= ",".'`'.$obj->configure["select"]["table_as_name"].'`.`'.$ssv.'`' ;
							}
							$no++ ; 
						}
					}
				}
				elseif($sk == 'join'){
					if(is_array($sv)){
						foreach($sv as $jk => $jv){
							if(isset($jv["group_by"])){
								if(is_array($jv["group_by"])){
									foreach($jv["group_by"] as $jjv){
										$group_by .= ",".'`'.$jv["table_as_name"].'`.`'.$jjv.'`' ;
									}
								}
							}
						}
					
					}
				}
				
			}	
		}
		return $group_by ;

	
	}


	function getSelectPageField($obj){
		$field = "";
		if(is_array($obj->configure["select"])){
			foreach($obj->configure["select"] as $sk => $sv){
				if($sk == 'field'){
					if(is_array($sv)){
						$no = 0 ; 
						foreach($sv as $ssv){
							$select = "";
							$as = "";
							if(isset($ssv["select"])){
								if($ssv["select"] != ""){
									$select= $ssv["select"] ;
								}
							}
							if(isset($ssv["as"])){
								if($ssv["as"] != ""){
									$as= " as ".$ssv["as"] ;
								}
							}
							$field_id = '`'.$obj->configure["select"]["table_as_name"].'`.'."`".$ssv["id"]."`" ; 

							$DISTINCT = "";
							if(isset($ssv["DISTINCT"])){
								if($ssv["DISTINCT"] == 'Y'){
								  	$DISTINCT = "DISTINCT";
								}
							}
	
							if(isset($ssv["fun"])){
								if($ssv["fun"] != ''){
									if(isset($DISTINCT)){
								  		$field_id = $ssv["fun"]."(".$DISTINCT." ".$field_id.")" ; 
									}
									else{
								  		$field_id = $ssv["fun"]."(".$field_id.")" ; 
									}
								}
							}
							


							if($select != 'N'){
								if($no == 0){
									$field .= $field_id.$as ;
								}
								else{
									$field .= ",".$field_id.$as ;
								}
								$no++ ; 
							}
						}
					}
				}
				elseif($sk == 'join'){
					if(is_array($sv)){
						foreach($sv as $jk => $jv){
							if(is_array($jv)){
								foreach($jv["field"] as $jjv){
									$as = "";
									if(isset($jjv["as"])){
										if($jjv["as"] != ""){
											$as= " as ".$jjv["as"] ;
										}
									}


									if(isset($jjv["DISTINCT"])){
										if($jjv["DISTINCT"] == 'Y'){
										  	$DISTINCT = "DISTINCT";
										}
									}
	
									$field_id = '`'.$jv["table_as_name"].'`.'."`".$jjv["id"]."`" ; 
									if(isset($jjv["fun"])){
										if($jjv["fun"] != ""){
										  	$field_id = $jjv["fun"]."(".$DISTINCT." ".$field_id.")" ; 
										}
									}


									$field .= ",".$field_id.$as ;
								}
							}
						}
					
					}
				}
				
			}	
		}
		if($field != ''){
			return $field ; 
		}
		else{
			return "*";
		} 

	
	}




	function getSearchFieldParser($sv,$obj,$table_as_name= ''){
		$field = "";
		if(is_array($sv)){
			$no = 0 ; 
			foreach($sv as $ssk => $ssv){
				if(is_array($ssv)){ // if ssv is array 
				    	$no = 0 ; 
					$num = 0 ;
					foreach($ssv as $sssv){
						if(!preg_match('/IS/',strtoupper($sssv['comparison']))){
							if(isset($obj->io->input['get'][$sssv['id']])){
							   if($obj->io->input['get'][$sssv['id']] != ''){
								$num++;
							   }
							}
						}
					}
					foreach($ssv as $sssv){
						   if(preg_match('/IS/',strtoupper($sssv['comparison']))){
							$field .= ' '.$sssv['logic'].' `'.$table_as_name.'`.`'.$sssv['id'].'` '.$sssv['comparison']." " ;
						   }

						if(isset($obj->io->input['get'][$sssv['id']])){
							if(!preg_match('/IS/',strtoupper($sssv['comparison']))){
							   if($obj->io->input['get'][$sssv['id']] != ''){

								$obj->status["path"]["search"] .= "&".$sssv["id"]."=".$obj->io->input['get'][$sssv['id']] ;

								$field_str = ' `'.$table_as_name.'`.`'.$sssv['id'].'` '.$sssv['comparison']." " ; 
								if($no == 0){
									$field	.= " ".$ssk." (" ; 
									
									$field .=  $field_str."'".$sssv['start'].$obj->io->input['get'][$sssv['id']].$sssv['end']."' ";
									if($num == '1'){
										$field .= ")" ;
									}
								}
								else{
									$field .= $sssv['logic'].$field_str."'".$sssv['start'].$obj->io->input['get'][$sssv['id']].$sssv['end']."' " ;
									if(($num-1) == $no){
										$field .= ")" ;
									}
								}
								$no++;
							  }
							}
						}
						
					}
				}
				else{
					$field .= " ".$ssv ; 
				}
			}
		}

		return $field ; 

	}

	function getSearchField($obj){
		$field = '';
		if(is_array($obj->configure["select"])){
			foreach($obj->configure["select"] as $sk => $sv){
				if($sk == 'search'){
					$field .= $this->getSearchFieldParser($sv,$obj,$obj->configure["select"]["table_as_name"]);
				}
				elseif($sk == 'join'){
					foreach($sv as $ssv){
						if(isset($ssv["search"])){
							$field .= $this->getSearchFieldParser($ssv["search"],$obj,$ssv["table_as_name"]);
						}
					}
				}
			}
		}
		return $field ; 

	}



	function getSortField($obj){
	
		$default_sort_field = ""; // default sort 
		$sort_field = ""; // user sort 

		if(is_array($obj->configure["select"])){
			foreach($obj->configure["select"] as $sk => $sv){
				if($sk == 'field'){ // get field array 
					if(is_array($sv)){
						foreach($sv as $fieldk => $fieldv){
							$field_arr[$obj->configure["select"]["table_as_name"]][] = $fieldv ; 
						}
					}
				}
				if($sk == 'sort'){ // get default sort query 
					if(is_array($sv)){
						$no = 0 ; 
						foreach($sv as $ssk => $ssv){
							if($no == 0){
								$default_sort_field .= '`'.$obj->configure["select"]["table_as_name"].'`.`'.$ssk.'`'." ".$ssv ;
							}
							else{
								$default_sort_field .= ",".'`'.$obj->configure["select"]["table_as_name"].'`.`'.$ssk.'`'." ".$ssv ;
							}
							$no++ ; 
						}
					}
				}
				elseif($sk == 'join'){
					if(is_array($sv)){
						foreach($sv as $jk => $jv){ 
						    if(isset($jv["field"])){ // get field array	
							if(is_array($jv["field"])){
								foreach($jv["field"] as $jjk => $jjv){
									$field_arr[$jv["table_as_name"]][] = $jjv  ;
								}
							}
						    }
						    if(isset($jv["sort"])){ // get default sort query
							if(is_array($jv["sort"])){
							  	
								foreach($jv["sort"] as $jjk => $jjv){
								  	if($default_sort_field != ''){
										$default_sort_field .= ",".'`'.$jv["table_as_name"].'`.`'.$jjk.'`'." ".$jjv ;
									}
									else{
										$default_sort_field .= '`'.$jv["table_as_name"].'`.`'.$jjk.'`'." ".$jjv ;
									}
								}
							}
						    }
						}
					
					}
				}
			}

		}
		
		//echo "<pre>";print_r($field_arr); exit ;

		if(is_array($field_arr)){
			$no = 0 ; 
			foreach($field_arr as $fk => $fv){
				if(is_array($fv)){
					foreach($fv as $ffk => $ffv){
						if(isset($obj->io->input["get"]["sort_".$ffv["id"]])){
							//echo $obj->io->input["get"]["sort_".$ffv["id"]] ; 
							if($obj->io->input["get"]["sort_".$ffv["id"]] != ''){
								if($no == 0){
									$sort_field .= "`".$fk."`.`".$ffv["id"]."` ".$obj->io->input["get"]["sort_".$ffv["id"]];
								}
								else{
									$sort_field .= ",`".$fk."`.`".$ffv["id"]."` ".$obj->io->input["get"]["sort_".$ffv["id"]];
								}
								$no++ ; 
								$obj->status["path"]["sort"] .= "&sort_".$ffv["id"]."=".$obj->io->input["get"]["sort_".$ffv["id"]];
								$obj->status["sort"][$ffv["id"]] = $obj->io->input["get"]["sort_".$ffv["id"]];
							}
							
						}
					}
				}
			}
		}

		if($sort_field != ''){
			return $sort_field;
		}
		else{
			return $default_sort_field;
		}

	
	}




	function getJoinField($obj){
		$field_arr = array();
		if(is_array($obj->configure["select"])){
			$prefix = $obj->config->default_prefix ; 
			if(isset($obj->configure["prefix"])){
				if($obj->configure["prefix"] == 'N'){
					$prefix = "";
				}
			}
			foreach($obj->configure["select"] as $sk => $sv){
				if($sk == 'join'){
					foreach($sv as $ssk => $ssv){
					    if(isset($ssv["database_name"])){
			                	if($ssv["database_name"] != ""){
			                        	$database_name = "`".$ssv["database_name"]."`.";

		        	        	}
					    }
					    $field_arr[$ssk] = "";
					    if(isset($ssv["on"])){
						if(is_array($ssv["on"])){
							$field_arr[$ssk] = "LEFT OUTER JOIN ".$database_name.'`'.$prefix.$ssv["table_name"].'` AS '.$ssv["table_as_name"].' ON ';
							foreach($ssv["on"] as $sssk => $sssv){
							  	if(is_array($sssv)){
									//$field_str1 = "`".$sssv["table1"]."`"."."."`prefixid` = `".$ssv["table_as_name"]."`"."."."`prefixid` and ";
									$field_str2 = "";
									if(isset($obj->configure["prefix"])){
										if($obj->configure["prefix"] == 'N'){
											$field_str1 = "";
										}
									}
									if(isset($sssv["field1"])){
										$field_str1 .= "`".$sssv["table1"]."`"."."."`".$sssv["field1"]."` = ";
									}
									if(isset($sssv["field2"])){
										if($sssv["field2"] != ''){
											$field_str2 .= "`".$ssv["table_as_name"]."`"."."."`".$sssv["field2"]."`";
										}
									}
									if(isset($sssv["field"])){
										$field_value = "";
										if(isset($obj->io->input['get'][$sssv['field']])){
										  	if($sssv['match_get'] == 'Y'){
												$field_value = $obj->io->input['get'][$sssv['field']] ; 
											}
										}
										if($sssv["field"] != ''){
											$field_str1 = 
													"`".$ssv["table_as_name"]."`".
													"."."`".$sssv["field"]."`"." ".
													$sssv["comparison"] ."'".
													$sssv["start"].
													$field_value.$sssv["end"]."'"
													;
										}
									}
									if($sssk == 0){
										$field_arr[$ssk] .= $field_str1.$field_str2 ;
									}
									else{
										$field_arr[$ssk] .= " ".$sssv["logic"]." ".$field_str1.$field_str2 ;
									}
								}
								else{ // is string 
									$field_arr[$ssk] .= $sssv ;
								}
								unset($field_str1);
								unset($field_str2);
								
							}
						}
					    }
					}
				}
			}
		}
		return $field_arr ; 


		
	}


	function recordPage($rows,$obj){

		//echo "<pre>";		print_r($obj);		exit;
		$max_page = $obj->configure["select"]["max_page"] ; 
		$max_range = $obj->configure["select"]["max_range"] ; 
                if(!isset($obj->io->input["get"]["p"])){$obj->io->input["get"]["p"] = "" ;}
                if(trim($obj->io->input["get"]["p"])=="" or $obj->io->input["get"]["p"] < 1){$obj->io->input["get"]["p"] = 1  ; }

                $lastpage = ceil($rows/$max_page);
                if($obj->io->input["get"]["p"] > $lastpage){ $obj->io->input["get"]["p"]=1 ;}
                $rec_start= ($obj->io->input["get"]["p"]-1)*$max_page +1;
                $rec_end  = $rec_start + $max_page -1;
                $ploops   = floor(($obj->io->input["get"]["p"]-1)/$max_range)*$max_range + 1 ;
                $ploope   = $ploops + $max_range -1;
                if($ploope >= $lastpage){ $ploope=$lastpage;}
                $ppg      = $obj->io->input["get"]["p"] - 1 ;
                $npg      = $obj->io->input["get"]["p"] + 1 ;
                if($ppg<= 0) $ppg=$lastpage;
                if($npg > $lastpage) $npg=1;
                if($rec_end > $rows) $rec_end=$rows;
 
                $page["rec_start"]       = $rec_start ;
                $page["rec_end"]         = $rec_end ;
                $page["firstpage"]       = 1 ;
                $page["lastpage"]        = $lastpage ;
                $page["previousrange"]   = $ploops - $max_range ;
                $page["nextrange"]       = $ploops + $max_range ;
                $page["previouspage"]    = $ppg ;
                $page["nextpage"]        = $npg ;
                $page["thispage"]        = $obj->io->input["get"]["p"] ;
                $page["total"]           = $rows ;
                $page["loop"]            = $lastpage+1 ;
                $page["max_page"]         = $max_page ;
                $page["max_range"]        = $max_range ;
                for($i=$ploops;$i <= $ploope;$i++){
                        $page["item"][]["p"] = $i ;
                }
		if($obj->io->input["get"]["p"] != ''){
	                $obj->status["path"]["page"] .= '&'.'p='.$page["thispage"] ;
		}
                return $page;
	}


        function getPageRows($query,$obj){
                global $mdb  ;
		$this->queryAssign('getPageRows',$query);
                $db_res = $this->query($query,$obj);
                $rows = @MetabaseFetchResult($mdb,$db_res,0,"num") ;

                return  $rows ;
        }

	function queryAssign($type , $query){
		$this->query_arr[$type][] = $query ; 
	}


}
?>
