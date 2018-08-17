<?php
    $ws = new swoole_websocket_server('0.0.0.1',9505);
    // $ws = new swoole_websocket_server('192.168.200.80',9505);


	$ws->on('open', function ($ws, $request) {  
    	var_dump('websocket连接成功--> $request->fd\n');
    	   
        $nowFd = $request->fd;   
        $userName = $request->get['name'];
        $userId = $userName.$nowFd;
        
    	$res = [
    		'is_god' => true,
    		'name' => $userName,
    		'msg' => "Hi,$userName,欢迎使用suvii简易聊天工具"
    	];
    	$ws->push($nowFd, json_encode($res,256));

        foreach ($ws->connections as $fd) {
            if($fd != $nowFd){
                $tip = [
                    'is_god' => true,
                    'name' => $userName,
                    'msg' => $userName." 上线！"
                ];
                $ws->push($fd, json_encode($tip,256));
            }
        }

	});

	$ws->on('message', function ($ws, $frame) {
    	echo "Message: ".$frame->data.'\n';
    	var_dump('frame'.json_encode($frame,256));

    	$curFd = $frame->fd;
    	$data = $frame->data;
    	$data = json_decode(trim($data),true);


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
        // foreach ($ws->connections as $con) {
        //     if($con != $fd){
        //         $tip = [
        //             'is_god' => true,
        //             'name' => $userName,
        //             'msg' => $userName." 下线！"
        //         ];
        //         $ws->push($con, json_encode($tip,256));
        //     }
        // }
    	echo "客户端--".$fd.": 关闭了.\n";
	});

	$ws->start(); 

?>
