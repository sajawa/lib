#!/usr/bin/php
<?php

	$path = "/usr/local/php/lib/ecstart/";
	exec('ls  /usr/local/php/lib/ecstart/',$res);

	$cmd = "rm -irvf ".$path."encode/*";
	system($cmd);
	foreach($res as $rv){
		if(ereg('ini.php',$rv)){
			$cmd = "php -q ".$path."bencoder.php -o ".$path."encode/".$rv." ".$path.$rv." -t -bz2";
			system($cmd);
			
		}
	}


?>
