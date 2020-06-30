<?php
declare(strict_types=1);
$input = file_get_contents("php://input");
if (function_exists('exec')) {
    if (!is_dir('temp')) mkdir('temp',0700);
    $temp = "temp/.up_" . rand(0, 1000) . "" . time();
    file_put_contents($temp, $input);
    exec("php main.php $temp > /dev/null &");
} /*not recommended beater way is use exec function */
else {
    require_once 'main.php';
}