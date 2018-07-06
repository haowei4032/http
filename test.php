<?php

require __DIR__ . '/vendor/autoload.php';

use EastWood\Http\HttpRequest;

$request = new HttpRequest();
$request->setMethod(HttpRequest::METHOD_POST);
$request->setUrl('http://localhost/index.php');
$request->setCookies([
    'id' => 1,
    'name' => 'haowei',
    'email' => 'boss@haowei.me'
]);
$request->setQueryData(['id' => 1, 'name' => 2]);
$request->setBody('xxxxxx');
echo $request->send();

var_dump($request);




