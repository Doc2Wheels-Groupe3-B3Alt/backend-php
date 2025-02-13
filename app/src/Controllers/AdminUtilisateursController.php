<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AdminUtilisateursController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminUser();
    }

    private function adminUser()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminUtilisateurs');
        }
    }
}
