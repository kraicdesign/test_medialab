<?php /** Created by ic on 01-Aug-18 at 15:48 */
// Пробовал с использованием обычных массивов и получилось медленнее и на 300+Мб большее использование памяти. С SplFixedArray в данном случае получается макс. эффективно.

/**
 * @param array $words
 * @param $count
 * @return SplFixedArray
 */
function render_strings(array $words, $count)
{
    $array = new SplFixedArray($count);
    for($i = 0; $i < $count; ++$i){
        shuffle($words);
        $array[$i] = implode(' ', $words);
    }
    return $array;
}

/**
 * @param SplFixedArray $strings
 * @return array
 */
function get_uniques(SplFixedArray $strings)
{
    $toReturn = [];
    foreach($strings as $string){
        $toReturn[$string] = NULL;
    }
    return array_keys($toReturn);
}

$words = ['red', 'green', 'yellow', 'blue', 'orange'];


$t = microtime(true);
$strings = render_strings($words, 10000000);
echo "T = ".(microtime(true) - $t)."\n";

$t = microtime(true);
$uniques = get_uniques($strings);
echo "T = ".(microtime(true) - $t)."\n";
print_r($uniques);
