<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AdminController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->admin();
    }

    private function admin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('admin');
        }
    }
}
