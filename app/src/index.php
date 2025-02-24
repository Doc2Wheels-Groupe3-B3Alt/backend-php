<?php

use App\Http\Request;
use App\Http\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = new Request();

$router = new Router();
$response = $router->route($request);

http_response_code($response->getStatus());

echo $response->getContent();
