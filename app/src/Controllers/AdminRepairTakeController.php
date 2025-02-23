<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Demande;
use App\Entities\User;

class AdminRepairTakeController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['take_id'])) {
            $tmp = new Demande();
            $tmp->updateDemande($_POST['take_id'], $_POST['technicien_id']);    
            return $this->redirect('/admin/repair/take?id=' . $_POST['take_id']);
        }

        $tmp = new Demande();
        $demande = $tmp->getDemandeWaitingById($_GET['id']);
        
        $tmp = new User();
        $techniciens = $tmp->getTechniciens($demande['localisation_ville']);


        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairTake', get_defined_vars());
        }
    }
}
