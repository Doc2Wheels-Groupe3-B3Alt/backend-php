<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AdminUtilisateursController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->adminUser();
    }

    private function adminUser()
    {
        return $this->render('adminUtilisateurs');
    }
}
