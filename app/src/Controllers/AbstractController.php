<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

abstract class AbstractController
{
    abstract public function process(Request $request): Response;

    public function checkAuth()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        return true;
    }

    protected function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400, // 1 jour
                'read_and_close'  => false
            ]);
        }
    }

    protected function redirect(string $url): Response
    {
        header('Location: ' . $url);
        exit();
        return new Response('', 302, ['Location' => $url]);
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function render(string $template, array $data = []): Response
    {
        $response = new Response();
        extract($data);
        ob_start();
        require_once __DIR__ . "/../Views/layouts/start.php";
        require_once __DIR__ . "/../Views/$template.php";
        require_once __DIR__ . "/../Views/layouts/end.php";
        $response->setContent(ob_get_clean());
        $response->addHeader('Content-Type', 'text/html');

        return $response;
    }
}
