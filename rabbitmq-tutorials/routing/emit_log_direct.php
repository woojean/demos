<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 定义一个direct类型的exchange
$channel->exchange_declare('direct_logs', 'direct', FALSE, FALSE, FALSE);

// 获取用户输入
$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';
$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = "Hello World!";
}
$msg = new AMQPMessage($data);

// 发出信息，其中$severity为route key
$channel->basic_publish($msg, 'direct_logs', $severity);

echo " [x] Sent ", $severity, ':', $data, " \n";

$channel->close();
$connection->close();