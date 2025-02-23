<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Services;

class AdminServiceEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->editService($request);
    }

    public function editService(Request $request): Response
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        
        $service = null;

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $tmp = new Services();
            $service = $tmp->getServiceById($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);

            if ($id) {
                $tmp = new Services();
                $service = $tmp->updateService($id, $nom, $description);
            } else {
                $tmp = new Services();
                $service = $tmp->insertService($id, $nom, $description);
            }

        
            return $this->redirect('/admin/services');
        }

        return $this->render('adminServiceEdit', get_defined_vars());
    }
}
