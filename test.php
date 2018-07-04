<?php

require __DIR__ . '/vendor/autoload.php';

use EastWood\Http\HttpRequest;

$request = new HttpRequest();
$request->setUrl('http://localhost/index.php');
$request->setQueryData(['id' => 1, 'name' => 2]);
echo $request->send()->getHeader();
echo $request->getHeader();



