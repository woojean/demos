<?php
require_once('/Users/wujian/woojean/demos/mail/php-smtp-master/src/email.php');

use Snipworks\SMTP\Email;

$mail = new Email('smtp.partner.outlook.cn', 587);
$mail->setProtocol(Email::TLS);
$mail->setLogin('ops@wemeshops.com', 'hello%%123');
$mail->addTo('winstonwu@wemeshops.com', 'Winston Wu');
$mail->setFrom('ops@wemeshops.com', 'ops');
$mail->setSubject('Example subject');
$mail->setMessage('<b>Example message</b>...', true);

if($mail->send()){
    echo 'Succes!';
} else {
    echo 'An error occurred.';
}


