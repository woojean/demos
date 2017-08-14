<?php

// 引用MNS SDK
$root = dirname(dirname(__FILE__));
require_once($root . '/php_sdk/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

// 新建队列操作客户端
$endPoint = 'http://1460488513831433.mns.cn-shanghai.aliyuncs.com/';
$accessId = 'LTAI8dvzUOuRxbb2';
$accessKey = '9GSwPeJ52SWO6VcwoimV0bczzUogRr';
$topicName = 'woojean-topic';
$client = new Client($endPoint, $accessId, $accessKey);

try
{
    $messageBody = "Hello World!";
    $request = new PublishMessageRequest($messageBody);
    $topic = $client->getTopicRef($topicName);
    $res = $topic->publishMessage($request);
    echo "MessagePublished! \n";

    /*
    $topic->unsubscribe($subscriptionName);
    echo "Unsubscribe Succeed! \n";
    */
}
catch (MnsException $e)
{
    echo "PublishMessage Failed: " . $e;
    return;
}

/*
NotifyContentFormat
https://help.aliyun.com/document_detail/27482.html?spm=5176.doc27496.6.712.6Pyve8
*/


/*
info | 13130 | 1497335254.557 | 2017:06:13 06:27:34 | /test/topic
info | 13130 | 1497335254.557 | 2017:06:13 06:27:34 | {"_url":"\/test\/topic"}
info | 13130 | 1497335254.557 | 2017:06:13 06:27:34 | <?xml version="1.0" encoding="UTF-8"?>
<Notification xmlns="http://mns.aliyuncs.com/doc/v1/">
  <TopicOwner>1460488513831433</TopicOwner>
  <TopicName>woojean-topic</TopicName>
  <Subscriber>1460488513831433</Subscriber>
  <SubscriptionName>woojean-subscription</SubscriptionName>
  <MessageId>CB1A8E7EFFA19158-1-15CA022CE05-200000008</MessageId>
  <MessageMD5>ED076287532E86365E841E92BFC50D8C</MessageMD5>
  <Message>Hello World!</Message>
  <PublishTime>1497335254533</PublishTime>
  <SigningCertURL>https://mnstest.oss-cn-hangzhou.aliyuncs.com/x509_public_certificate.pem</SigningCertURL>
</Notification>
*/













