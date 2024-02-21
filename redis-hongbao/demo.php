<?php

/**
 * 生成红包函数
 * $totalMoney 总金额
 * $num 红包数量
 * $min 红包最小值
 */
function gen($totalMoney, $num, $min='0.01'){
    // 分配结果
    $ret = [];

    // 剩余红包金额
    $remainMoney = $totalMoney;

    for ( $i = 1; $i < $num; $i++) {
        // 剩余红包数量
        $remainNum = $num-$i;

        // 当前可领取的红包的最大值
        $remainMax = bcsub($remainMoney,$remainNum*$min,2);

        $allocateMoney = bcdiv(mt_rand($min*100, $remainMax*100),100,2);
        $remainMoney = bcsub($remainMoney,$allocateMoney,2);
        $ret[$i] = array(
            'allocation' => $allocateMoney,
            'remainder' => $remainMoney
        );
    }

    // 处理最后一个
    $ret[$num] = [
        'allocation'=>$remainMoney,
        'remainder'=>0
    ];

    return $ret;
}


// ======================= demo ========================

// 红包总金额
$totalMoney = 10;

// 红包总数
$num = 10;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 库存队列
$mapStock = 'queue_stock';

// 已抢队列 uid => hongbao
$mapGrab = 'queue_grab';

// 剩余红包索引
$listIndexs = 'list_indexs';


$allocated = $redis->hlen($mapGrab);
if ($allocated == $num) {
    echo '已抢光！';
    exit;
}

$inited = $redis->hlen($mapStock);

// 如果索引尚未生成，生成红包库存
if (0 == $inited) {
    // 生成红包库存
    $stock = gen($totalMoney, $num);

    // 存储红包索引
    foreach ($stock as $index => $hongbao) {
        $redis->hset($mapStock, $index, json_encode($hongbao));
        $redis->lpush($listIndexs, $index);
    }
}

// 获取用户ID
$uid = intval($_GET['uid']);
if ($uid < 1) {
    echo '用户ID非法！';
    exit;
}

// 判断用户是否已经抢过红包
$participated = $redis->hexists($mapGrab, $uid);
if ($participated) {
    echo '不能重复参加！';
    exit;
}

// 分配红包给用户（原子操作，关键！）
$index = $redis->lpop($listIndexs);
if (intval($index) < 1) {
    echo '已抢光！';
    exit;
}

// todo 操作失败后，将红包的索引push回去

$hongbao = $redis->hget($mapStock, $index);
$redis->hset($mapGrab, $uid, json_encode($hongbao));

echo $uid . ' -> ' . json_encode($hongbao);
