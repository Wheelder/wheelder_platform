<?php
$path=$_SERVER['DOCUMENT_ROOT'];
require $path.'/pool/libs/composer/vendor/autoload.php';


use React\Http\Browser;
use Psr\Http\Message\ResponseInterface;



$client = new Browser();

$client->get('http://google.com/')->then(function (ResponseInterface $response) {
    var_dump($response->getHeaders(), (string)$response->getBody());
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});