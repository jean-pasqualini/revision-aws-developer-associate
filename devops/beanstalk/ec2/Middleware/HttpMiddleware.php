<?php

namespace Darkilliant\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HttpMiddleware
{
    public function handle(Request $request): Response;
}