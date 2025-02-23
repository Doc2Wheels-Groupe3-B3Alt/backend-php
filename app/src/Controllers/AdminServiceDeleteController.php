<?php

namespace App\Controllers;

use App\Entities\Services;
use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminServiceDeleteController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->deleteService();
    }

    public function deleteService(): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int) $_POST['id'];

            $tmp = new Services();
            $service = $tmp->getServiceById($id);

            if ($service) {
                $tmp->deleteService($id);
            }
        }

        return $this->redirect('/admin/services');
    }
}
