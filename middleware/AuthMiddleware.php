<?php
namespace Middleware;

class AuthMiddleware extends Middleware {
    public function handle($request, $next) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header("Location: /login");
            exit;
        }
        return $next($request);
    }
}
