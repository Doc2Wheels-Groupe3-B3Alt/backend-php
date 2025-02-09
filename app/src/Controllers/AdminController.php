<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AdminController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->admin();
    }

    private function admin()
    {
        return $this->render('admin');
    }
}
