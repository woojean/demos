<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$queueName = 'queue_hello';
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Note that we declare the queue here, as well. Because we might start the consumer before the publisher, we want to make sure the queue exists before we try to consume messages from it.
$channel->queue_declare($queueName, FALSE, FALSE, FALSE, FALSE);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// define a PHP callable that will receive the messages sent by the server
$callback = function($msg){
    echo " [x] Received ", $msg->body, "\n";
};
$channel->basic_consume($queueName,'',false,true,false,false,$callback);

// keep it running to listen for messages and print them out .
while(count($channel->callbacks)){
    $channel->wait();
}



