<?php

namespace Darkilliant\Http\Controller;

use Darkilliant\Http\Middleware\HttpMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhpinfoController implements HttpMiddleware
{
    public function handle(Request $request): Response
    {
        ob_start();
        phpinfo();
        return new Response(ob_get_clean());
    }
}