<?php
namespace App;

use Darkilliant\Middleware\ApplicationMiddleware;
use Darkilliant\Middleware\TracingMiddleware;
use Darkilliant\Database\Mysql;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/vendor/autoload.php';

//https://patrickkerrigan.uk/blog/instrumenting-php-apps-with-aws-x-ray/

$app = new TracingMiddleware(new ApplicationMiddleware(new Mysql()));
$response = $app->handle(Request::createFromGlobals());
$response->send();