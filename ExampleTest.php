<?php
require_once __DIR__.'/vendor/autoload.php';

$x = new turkmvc\Hepsiburada\Hepsiburada(['username' => 'xxx', 'password' => 'xxx', 'merchant_id' => 'xxx']);

try{
    $x->products();
}catch (Exception $e) {
    echo $e;
}
