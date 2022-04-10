<?php
namespace App;

use Darkilliant\Http\Controller\HomepageController;
use Darkilliant\Http\Controller\PhpinfoController;
use Darkilliant\Http\Controller\SchemaController;
use Darkilliant\Http\Middleware\RoutingMiddleware;
use Darkilliant\Http\Middleware\TracingMiddleware;
use Darkilliant\Database\Mysql;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/vendor/autoload.php';

//https://patrickkerrigan.uk/blog/instrumenting-php-apps-with-aws-x-ray/

$app = new TracingMiddleware(new RoutingMiddleware(
    [
        "/" => new HomepageController(new Mysql()),
        "/info" => new PhpinfoController(),
        "/schema" => new SchemaController(new Mysql()),
    ]
));
$response = $app->handle(Request::createFromGlobals());
$response->send();