<?php
namespace Middleware;

class MiddlewareStack {
    private $middlewares = [];
    private static $middlewareAliases = [
        'auth' => AuthMiddleware::class,
        'admin' => AdminMiddleware::class
    ];

    public function add($middleware) {
        if (is_string($middleware) && isset(self::$middlewareAliases[$middleware])) {
            $middleware = new self::$middlewareAliases[$middleware]();
        }
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function handle($request, $next) {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            function($next, $middleware) {
                return function($request) use ($next, $middleware) {
                    return $middleware->handle($request, $next);
                };
            },
            $next
        );

        return $pipeline($request);
    }
}
