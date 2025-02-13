<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class HomepageController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->homepage();
    }

    private function homepage()
    {
        return $this->render('homepage');
    }
}
