<?php
require_once 'xmlseclibs/src/XMLSecEnc.php';
require_once 'xmlseclibs/src/XMLSecurityDSig.php';
require_once 'xmlseclibs/src/XMLSecurityKey.php';

use RobRichards\XMLSecLibs\XMLSecEnc;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

// -------------- 准备数据 -------------------------
$privateKey = '/Users/wujian/projects/demo/test/app_private.pem';
$publicKey = '/Users/wujian/projects/demo/test/app_public.pem';
$dumpPath = '/Users/wujian/projects/demo/dump3.xml';

$xmlStr = '<?xml version="1.0" encoding="UTF-8"?>
<RootInfo>
  <NS:Item type="3" xmlns:NS="http://www.woojean.com/">
    <NS:id>021</NS:id>
    <name>woojean</name>
  </NS:Item>
</RootInfo>';

// -------------- 加密 -------------------------
$doc = new DOMDocument();
$doc->loadXML($xmlStr);

$objDSig = new XMLSecurityDSig();
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);  // C14N规范
$objDSig->addReference(
    $doc,
    XMLSecurityDSig::SHA256,
    ['http://www.w3.org/2000/09/xmldsig#enveloped-signature']  // 封内加签
);

$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
$objKey->loadKey($privateKey, TRUE);
// $objKey->passphrase = '<passphrase>';  // 密码

$objDSig->sign($objKey);
// KeyName节点
$keyNameNode = $objDSig->sigNode->ownerDocument->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'ds:KeyName','my_public_key');
$objDSig->appendToKeyInfo($keyNameNode);
$objDSig->add509Cert(file_get_contents($publicKey));
$objDSig->appendSignature($doc->documentElement);

$doc->save($dumpPath);















