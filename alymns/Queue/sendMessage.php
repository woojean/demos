<?php

// 引用MNS SDK
$root = dirname(dirname(__FILE__));
require_once($root . '/php_sdk/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\SendMessageRequest;


// 新建队列操作客户端
$endPoint = 'http://1460488513831433.mns.cn-shanghai.aliyuncs.com/';
$accessId = 'LTAI8dvzUOuRxbb2';
$accessKey = '9GSwPeJ52SWO6VcwoimV0bczzUogRr';
$queueName = 'woojean-queue';
$client = new Client($endPoint, $accessId, $accessKey);

// 获取队列的引用
$queue = $client->getQueueRef($queueName);

// 创建待发送的消息
$messageBody = "Hello World!";

// 发送消息
$request = new SendMessageRequest($messageBody);
try {
    $res = $queue->sendMessage($request);
    echo "MessageSent! \n";
} catch (MnsException $e) {
    echo "SendMessage Failed: " . $e;
    return;
}

















