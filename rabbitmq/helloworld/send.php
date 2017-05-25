<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$queueName = 'queue_hello';
$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');

// AMQP 0-9-1 connections are multiplexed with channels that can be thought of as “lightweight connections that share a single TCP connection”.
$channel = $connection->channel();

// declare a queue for us to send to
$channel->queue_declare($queueName, FALSE, FALSE, FALSE, FALSE);

// publish a message to the queue
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg,'', $queueName);

echo " [x] Sent 'Hello World!'\n";

// close the channel and the connection
$channel->close();
$connection->close();
