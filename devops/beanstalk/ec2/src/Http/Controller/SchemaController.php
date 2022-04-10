<?php

namespace Darkilliant\Http\Controller;

use Darkilliant\Database\Mysql;
use Darkilliant\Http\Middleware\HttpMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchemaController implements HttpMiddleware
{
    public function __construct(private Mysql $mysql) {}

    public function handle(Request $request): Response
    {
        $this->mysql->createSchema();
        return new Response("schema created");
    }
}