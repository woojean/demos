<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 第3个参数为true，代表队列的durable为true
$channel->queue_declare('task_queue', FALSE, TRUE, FALSE, FALSE);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// 定义任务处理函数
$callback = function ($msg){
    echo " [x] Received ", $msg->body, "\n";  // 总之，消息体的形式肯定是一个字符串（byte string）
    sleep(substr_count($msg->body, '.'));  // 模拟任务执行时间
    echo " [x] Done", "\n";

    // message acknowledgment
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// prefetch_count = 1,This tells RabbitMQ not to give more than one message to a worker at a time.
// in other words, don't dispatch a new message to a worker until it has processed and acknowledged the previous one. Instead, it will dispatch it to the next worker that is not still busy.
$channel->basic_qos(null,1,null);
$channel->basic_consume('task_queue', '', FALSE, FALSE, FALSE, FALSE, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();





