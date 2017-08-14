<?php

$serv = new swoole_server("127.0.0.1", 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$serv->set([
	'reactor_num' => 2, //reactor thread num
    'worker_num' => 1,  // 
    'daemonize' => false,  // 使错误日志可以打印出来
    'backlog' => 128,      // 最多同时有多少个待accept的连接
    'log_file' => 'swoole.log',
    'max_request' => 50,
    'dispatch_mode' => 1, // 1 平均分配，2 按FD取摸固定分配，3 抢占式分配，默认为取模(dispatch=2)
    'heartbeat_check_interval' => 10, //每隔多少秒检测一次，单位秒，Swoole会轮询所有TCP连接，将超过心跳时间的连接关闭掉
    'open_eof_check' => true, // 打开buffer，buffer主要是用于检测数据是否完整
    'open_tcp_nodelay' => 1 , // 启用tcp_nodelay
]);


// 设置事件处理函数
//$serv->on('Connect', 'my_onConnect');
$serv->on('connect', function ($serv, $fd){
    echo "Client:Connect.\n";
});

$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	echo "Client:Receive $from_id.\n";
    $serv->send($fd, 'Swoole: '.$data);
    $serv->close($fd);
});

$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});
// 启动Server
$serv->start();



/*
lsof -i tcp:9501
kill -9 $(`lsof -i tcp:9501|grep -v PID|awk '{print $2}'`)
*/


























