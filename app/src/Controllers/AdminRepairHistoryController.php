<?php

namespace App\Controllers;

use App\Entities\Demande;
use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminRepairHistoryController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
       

        

        $tmp = new Demande();
        $demandes = $tmp->getHistoriesDemande();
        

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairHistory', get_defined_vars());
        }
    }
}
