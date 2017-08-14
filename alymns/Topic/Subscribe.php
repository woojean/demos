<?php

// 引用MNS SDK
$root = dirname(dirname(__FILE__));
require_once($root . '/php_sdk/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Model\SubscriptionAttributes;

// 新建队列操作客户端
$endPoint = 'http://1460488513831433.mns.cn-shanghai.aliyuncs.com/';
$accessId = 'LTAI8dvzUOuRxbb2';
$accessKey = '9GSwPeJ52SWO6VcwoimV0bczzUogRr';
$topicName = 'woojean-topic';
$client = new Client($endPoint, $accessId, $accessKey);

try
{
    $subscriptionName = "woojean-subscription";
    $endPoint = 'http://sms.dongshier.com/test/topic';
    $attributes = new SubscriptionAttributes($subscriptionName, $endPoint,'BACKOFF_RETRY','SIMPLIFIED');
    $topic = $client->getTopicRef($topicName);
    $topic->subscribe($attributes);
    echo "Subscribed! \n";
}
catch (MnsException $e)
{
    echo "SubscribeFailed: " . $e;
    return;
}
















