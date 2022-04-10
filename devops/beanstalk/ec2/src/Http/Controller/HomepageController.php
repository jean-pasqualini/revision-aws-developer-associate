<?php

namespace Darkilliant\Http\Controller;

use Darkilliant\Database\Mysql;
use Darkilliant\Http\Middleware\HttpMiddleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomepageController implements HttpMiddleware {

    public function __construct(private Mysql $mysql) {}

    public function handle(Request $request): Response {
        return new JsonResponse($this->mysql->showTables(), intval($request->query->get('status', 200)));
    }
}
