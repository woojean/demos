<?php
define('DIR_BACKEND', dirname(__DIR__) . '/Client');
var_dump(DIR_BACKEND);

// 用于自动寻找并加载Thrift库中的类
spl_autoload_register(function ($clientClass) {
    try {
        $class = str_replace('\\', '/', $clientClass);
        $filePath = DIR_BACKEND.'/Library/' . $class . '.php';
        require_once $filePath;
    } catch (\Exception $e) {
        echo $e->getMessage();
        var_dump($clientClass);
    }
});

// 引用Thrift生成的文件
require_once DIR_BACKEND .'/Woojean/Rpc/Demo/DemoService.php';
require_once DIR_BACKEND .'/Woojean/Rpc/Demo/Types.php';


// Demo
use \Thrift\Transport\TSocket;
use \Thrift\Transport\TBufferedTransport;
use \Thrift\Protocol\TBinaryProtocol;
use \Thrift\Protocol\TMultiplexedProtocol;
use \Thrift\Exception\TException;

use \Woojean\Rpc\Demo\DemoServiceClient;
use \Woojean\Rpc\Demo\Param;
use \Woojean\Rpc\Demo\RequestException;

try {
    // 注意端口号与服务端一致
    $socket = new TSocket('0.0.0.0', '9524', TRUE);  

    // 注意传输协议与服务端一致
    $transport = new TBufferedTransport($socket, 1024, 1024);
    $protocol = new TBinaryProtocol($transport);
    $protocol = new TMultiplexedProtocol($protocol, "DemoService");  // 注意服务名与服务端注册的一致
    
    // 构造参数
    $params = new \Woojean\Rpc\Demo\Param();
    $params->s1 = 'Hello';
    $params->s2 = 'World!';
    $sep = '+';

    // 构造客户端
    $client = new DemoServiceClient($protocol);
    $transport->open();

    // 调用Rpc方法
    $ret = $client->joinString($params, $sep);

    // 打印调用结果
    var_dump($ret);  // Hello+World!
    $transport->close();

} catch (RequestException $ex) {
    print 'RequestException: ' . $ex->getMessage() . "\n";
}

