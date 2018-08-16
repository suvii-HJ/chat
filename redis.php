<?php
 	$redis = new Redis();
 	$redis->connect('127.0.0.1',6379);
 	if($redis){
 		echo 'redis服务器 连接成功！<br>';
 	}else{
 		echo 'redis服务器 连接失败！<br>';
 	}

 	$aa = [
 		'name'=>'suvii',
 		'age'=>16,
 		'lover'=>'dhl'
 	];
 	$bb = [
 		'name'=> '海领',
 		'age' => '24',
 		'lover' => '旭伟'
 	]
 	$redis->hMset('suvii',$aa);
 	$redis->hMset('dhl',$bb);


 	// $all = $redis->hGetAll('suvii');
 	
?>
