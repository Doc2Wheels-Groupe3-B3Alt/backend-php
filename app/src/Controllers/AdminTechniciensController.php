<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\User;

class AdminTechniciensController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminTechnicien();
    }

    private function adminTechnicien()
    {
       

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $tmp = new User();
            $tmp->deleteById($_POST['delete_id']);
        }

        $tmp = new User();
        $users = $tmp->getAdminTechnicien();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminTechniciens', get_defined_vars());
        }
    }
}
