<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class CreateDemandController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/homepage');
        } else {
            return $this->createDemand($request);
        }
    }

    private function createDemand(Request $request)
    {
        $db = (new ConnectDatabase())->execute();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
        }

        return $this->render('createDemand', get_defined_vars());
    }
}
