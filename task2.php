<?php /** Created by ic on 02-Aug-18 at 12:11 */

$pdo = new PDO('mysql:host=localhost;dbname=test_medialab;charset=utf8', 'medialab', '1111');
$pdo->exec('SET NAMES UTF8');

function initStrings(PDO $pdo, $strings)
{
    $sqlParams = $sqlVals = [];
    foreach((array)$strings as $string){
        array_push($sqlParams, md5($string), $string);
        $sqlVals[] = '(?,?)';
    }
    if($sqlParams){
        $sql = 'INSERT INTO `strings` (`hash`, `string`)
          VALUES ' . implode(',', $sqlVals) . '
          ON DUPLICATE KEY UPDATE
          `hash`=`hash`';
        $que = $pdo->prepare($sql);
        $que->execute($sqlParams);
    }
}

function stringExists(PDO $pdo, $string)
{
    $que = $pdo->prepare('SELECT * FROM `strings` WHERE `hash` = ?');
    $que->execute([md5($string)]);
    return (bool)$que->rowCount();
}

// Test fill example:
//$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 .,';
//$strings = [];
//for($i = 0; $i < 10000000; ++$i){
//    $length = rand(2000, 2000000);
//    $max = strlen($pool) - 1;
//    $string = '';
//    for($n = 0; $n < $length; ++$n) {
//        $string .= $pool[mt_rand(0, $max)];
//    }
//    $strings[] = $string;
//}
//initStrings($pdo, $strings);
