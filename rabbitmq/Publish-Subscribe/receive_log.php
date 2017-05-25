<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 定义exchange
$channel->exchange_declare('logs','fanout', FALSE, FALSE, FALSE);

// 生成一个临时queue，并保存queue的名字
list($queue_name,,) = $channel->queue_declare('', FALSE, FALSE,TRUE, FALSE);

// 将queue绑定到exchange
$channel->queue_bind($queue_name,'logs');  // queue -> exchange
echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function ($msg) {
    echo ' [x] ', $msg->body, "\n";
};

$channel->basic_consume($queue_name,'',FALSE,true,false,false,$callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();


