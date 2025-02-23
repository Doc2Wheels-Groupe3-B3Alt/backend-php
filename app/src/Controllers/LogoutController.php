<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class LogoutController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        session_unset();
        session_destroy();

        return $this->redirect('/homepage');
    }
}
