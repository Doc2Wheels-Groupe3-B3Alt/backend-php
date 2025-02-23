<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Demande;

class AdminRepairController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $tmp = new Demande();
            $tmp->deleteById($_POST['delete_id']);
        }

        $tmp = new Demande();
        $demandes = $tmp->getDemandeWaiting();
        

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepair', get_defined_vars());
        }
    }
}
