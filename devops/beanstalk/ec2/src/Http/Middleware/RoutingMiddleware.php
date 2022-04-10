<?php

namespace Darkilliant\Http\Middleware;

use Darkilliant\Http\Middleware\HttpMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoutingMiddleware implements HttpMiddleware
{
    /**
     * @param array $routes
     */
    public function __construct(private array $routes = []) {}

    public function handle(Request $request): Response
    {
        $route = $this->routes[$request->getPathInfo()] ?? null;
        if (!$route) {
            return new Response("Route not found", Response::HTTP_NOT_FOUND);
        }
        if (!$route instanceof HttpMiddleware) {
            return new Response("Route is not an middleware implementation.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $route->handle($request);
    }
}