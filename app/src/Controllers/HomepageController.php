<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class HomepageController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->homepage();
    }

    private function homepage()
    {
        ob_start();
        include __DIR__ . '/../Views/homepage.php';
        $content = ob_get_clean();

        return new Response($content, 200);
    }
}
