<?php

require_once 'xmlseclibs/src/XMLSecEnc.php';
require_once 'xmlseclibs/src/XMLSecurityDSig.php';
require_once 'xmlseclibs/src/XMLSecurityKey.php';

use RobRichards\XMLSecLibs\XMLSecEnc;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

// -------------- 准备数据 -------------------------
$dumpPath = '/Users/wujian/projects/demo/dump3.xml';

// -------------- 解密比对 -------------------------
$doc = new DOMDocument();
$doc->load($dumpPath);

$objXMLSecDSig = new XMLSecurityDSig();
$objDSig = $objXMLSecDSig->locateSignature($doc);
$objXMLSecDSig->canonicalizeSignedInfo();
$objKey = $objXMLSecDSig->locateKey();

XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);
$publicKey = $objKey->getX509Certificate();

$keyAlgorithm = $objKey->getAlgorith();

// Check signature
$ret = $objXMLSecDSig->verify($objKey);
if (1 !== $ret) {
    var_dump('wrong!');
    return FALSE;
}
else{
    var_dump('ok!');
}

// Check references (data)
try {
    $objXMLSecDSig->validateReference();
} catch (\Exception $e) {
    return FALSE;
}




