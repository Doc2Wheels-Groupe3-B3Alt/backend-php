<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Services;

class AdminServicesController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminServices();
    }

    private function adminServices()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $tmp = new Services();
            $tmp->deleteService($_POST['delete_id']);
        }

        $tmp = new Services();
        $services = $tmp->getServices();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminServices', get_defined_vars());
        }
    }
}
