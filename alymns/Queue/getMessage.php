<?php

// 引用MNS SDK
$root = dirname(dirname(__FILE__));
require_once($root . '/php_sdk/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;


// 新建队列操作客户端
$endPoint = 'http://1460488513831433.mns.cn-shanghai.aliyuncs.com/';
$accessId = 'LTAI8dvzUOuRxbb2';
$accessKey = '9GSwPeJ52SWO6VcwoimV0bczzUogRr';
$queueName = 'woojean-queue';
$client = new Client($endPoint, $accessId, $accessKey);

// 获取队列的引用
$queue = $client->getQueueRef($queueName);

// 接收消息
$receiptHandle = NULL;
while (true) {
    try {
        // when receiving messages, it's always a good practice to set the waitSeconds to be 30.
        // it means to send one http-long-polling request which lasts 30 seconds at most.
        $res = $queue->receiveMessage(30);

        echo "ReceiveMessage Succeed! \n";
        $messageBody = $res->getMessageBody();
        var_dump($messageBody);

        // 删除消息
        $receiptHandle = $res->getReceiptHandle();
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    } catch (MnsException $e) {
        /*
         * ReceiveMessage Failed:
         * Code: 404 Message: Message not exist.
         * MnsErrorCode: MessageNotExist
         * RequestId: 593F70CF19E20A932F4CC096
         * HostId: http://1460488513831433.mns.cn-shanghai.aliyuncs.com
         * */
        echo "ReceiveMessage Failed: " . $e;
        return;
    }
}

















