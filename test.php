<?php

function add($a, $b) {
    $result = $a + $b;   //  breakpoint 
    return $result;
}

function multiply($x, $y) {
    $result = $x * $y;   //  breakpoint 
    return $result;
}

$num1 = 10;
$num2 = 5;

$sum = add($num1, $num2);
$product = multiply($num1, $num2);

echo "Sum: " . $sum . PHP_EOL;
echo "Product: " . $product . PHP_EOL;