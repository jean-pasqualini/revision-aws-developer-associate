<?php

namespace Darkilliant\Middleware;

use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter;
use Pkerrigan\Xray\Trace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TracingMiddleware implements HttpMiddleware {

    public function __construct(private HttpMiddleware $next) {}

    public function handle(Request $request): Response {
        Trace::getInstance()
            ->setName("beanapp")
            ->setUrl($request->getUri())
            ->setMethod($request->getMethod())
            ->begin(100);
        $response = $this->next->handle($request);
        Trace::getInstance()
            ->end()
            ->setResponseCode(200)
            ->setError(false)
            ->setFault(false)
            ->submit(new DaemonSegmentSubmitter());
        return $response;
    }
}