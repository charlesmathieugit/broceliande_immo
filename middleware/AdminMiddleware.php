<?php
namespace Middleware;

class AdminMiddleware extends Middleware {
    public function handle($request, $next) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Accès non autorisé.";
            header("Location: /");
            exit;
        }
        return $next($request);
    }
}
