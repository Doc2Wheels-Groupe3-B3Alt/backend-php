<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Demande; 
use App\Entities\User; 

class AdminRepairHistoryViewController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        

       

        $tmp = new Demande();
        $demande = $tmp->getHistoryDemandeById($_GET['id']);
        
       
        $tmp = new User();
        $intervenant = $tmp->getIntervenant($demande['intervenant_id']);


        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairHistoryView', get_defined_vars());
        }
    }
}
