<?php
// echo date('Y-m-d H:i:s',strtotime("+1 month"));

// echo date('Y-m-01 00:00:00',strtotime($t));

 function foo($time){
	$endMonth = date('Y-m-01 00:00:00',strtotime($time));
        for($i = 1; $i<=6;$i++){
            $beginTime = date('Y-m-d H:i:s',strtotime("$endMonth -1 month"));
            echo $beginTime;
            echo $endMonth;
            echo '</br>';
            $endMonth = $beginTime;
        }
}


$t = '2017-09-10 12:25:46';
foo($t);
