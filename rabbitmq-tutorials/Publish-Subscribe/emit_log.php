<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 定义一个fanout类型的exchange
$channel->exchange_declare('logs','fanout',false,FALSE,FALSE);

// 拼装消息
$data = implode(' ', array_slice($argv, 1));
if (empty($data)) $data = "info: Hello World!";
$msg = new AMQPMessage($data);

// 将消息发布到exchange
$channel->basic_publish($msg,'logs');

echo " [x] Sent ", $data, "\n";
$channel->close();
$connection->close();



