<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 第3个参数为true，代表队列的durable为true，此处要与worker中的定义一致
$channel->queue_declare('task_queue',false,true,false,false);

// 根据用户输入拼装消息参数
$data = implode(' ',array_slice($argv,1));
if(empty($data)){
    $data = "Hello World!";
}
$msg = new AMQPMessage($data,[
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT  // 代表持久化消息
]);

//  发布消息
$channel->basic_publish($msg,'','task_queue');
echo " [x] Sent ", $data, "\n";

$channel->close();
$connection->close();



