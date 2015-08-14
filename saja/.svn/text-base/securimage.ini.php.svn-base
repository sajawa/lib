<?php
include_once "securimage/securimage.php";

class walletSecurimage extends Securimage {

	public $Secureimage ;

	function __construct()
	{
		$include_path_str = ini_get('include_path');

		$include_path_arr = explode(':',str_replace('.:','',$include_path_str));
	
		if(is_array($include_path_arr)){
			foreach($include_path_arr as $ik => $iv){
				if(is_dir($iv."/securimage")){
					$this->lib_path = $iv."/securimage";
				}
			}
		}
			
	}

	function initSecurimage($width = '200', $height = '40')
	{
		if (!$this->Secureimage)
		{
			$this->Secureimage = new Securimage;
			$this->Secureimage->image_type = SI_IMAGE_JPEG;
			if ($width) {
				$this->Secureimage->image_width = $width;
			}
			if ($height) {
				$this->Secureimage->image_height = $height;
			}
			$this->Secureimage->code_length		= 4;
			$this->Secureimage->charset		= '1234567890';
			$this->Secureimage->wordlist_file	= $this->lib_path."/words/words.txt";
			$this->Secureimage->gd_font_file	= $this->lib_path."/gdfonts/automatic.gdf";
			$this->Secureimage->ttf_file		= $this->lib_path."/AHGBold.ttf";
			$this->Secureimage->signature_font	= $this->lib_path."/AHGBold.ttf";
			$this->Secureimage->audio_path		= $this->lib_path."/audio/";
			$this->Secureimage->sqlite_database	= $this->lib_path."/database/securimage.sqlite";
		}
	}
	
	function check($code)
	{
	  	$this->initSecurimage();
		return $this->Secureimage->check($code);
	}
	
	function show($width, $height)
	{
	  	$this->initSecurimage($width, $height);
		$this->Secureimage->show();
	}
}
