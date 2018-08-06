<?php /** Created by ic on 02-Aug-18 at 3:48 */



$before = microtime(true);
for ($i=0 ; $i<1000000 ; $i++) {
    $a = md5('test');
}
$after = microtime(true);
echo ($after-$before) . "\n";