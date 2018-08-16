<?php
	$ws = new swoole_websocket_server('0.0.0.0',9502);

	// $redis = new Redis();
 // 	$red = $redis->connect('127.0.0.1',6379);
	// if($red){
 // 		echo 'redis服务器 连接成功! ----';
 // 	}else{
 // 		echo 'redis服务器 连接失败！----n';
 // 	}


	$ws->on('open', function ($ws, $request) {  
        echo '--------------------***************-';
    	var_dump('websocket连接成功--> $request->fd\n');
    	   
        $userName = $request->get['name'];
        $userId = $userName.$request->fd;
        
    	$res = [
    		'is_god' => true,
    		'name' => $userName,
    		'msg' => "Hi,$userName,欢迎使用suvii简易聊天工具"
    	];
    	$ws->push($request->fd, json_encode($res,256));
	});

	$ws->on('message', function ($ws, $frame) {
    	echo "Message: ".$frame->data.'\n';
    	var_dump('frame'.json_encode($frame,256));
    	echo '--------************------------';

    	$curFd = $frame->fd;
    	$data = $frame->data;
    	$data = json_decode(trim($data),true);

   //  	$name = $redis->hMget('user',['name','lover','age']);
 		// var_dump('redis缓存：：：：'.json_encode($name,256));

		$return = [
    		'name' => $data['name'],
    		'msg'  => $data['msg'],
    	];

		$users = $ws->getClientList();
    	var_dump('当前连接的用户ID有：'.json_encode($users,256).'\n');

    	if($users){
    		foreach ($users as $fd) {
    			if($curFd != $fd){
    				$ws->push($fd,json_encode($return,256));
    			}
    		}
    	}
    });

	$ws->on('close', function ($ws, $fd) {
        echo '--------------------***************-';
    	echo "客户端--".$fd.": 关闭了.\n";
	});

	$ws->start(); 

?>
