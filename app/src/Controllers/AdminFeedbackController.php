<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Entities\ReclamationEntity;

class AdminFeedbackController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        if ($request->getMethod() === 'POST') {
            return $this->handlePostRequest($request);
        }

        return $this->displayReclamations($request);
    }

    private function displayReclamations(Request $request)
    {
        $type = $request->getQueryParam('type', 'all');
        $statut = $request->getQueryParam('statut', 'all');

        $reclamationsEntity = new ReclamationEntity();
        $reclamations = $reclamationsEntity->getReclamations($type, $statut);
        $stats = $reclamationsEntity->getStats();

        $filters = [
            'type' => $type,
            'statut' => $statut
        ];

        return $this->render('adminFeedback', get_defined_vars());
    }

    private function handlePostRequest(Request $request)
    {
        $action = $_POST['action'] ?? null;
        $reclamationId = $_POST['reclamation_id'] ?? null;

        if (!$action || !$reclamationId) {
            return $this->redirect('/admin/feedback');
        }

        $reponse = $_POST['reponse'] ?? '';

        $reclamationsEntity = new ReclamationEntity();
        $reclamationsEntity->updateReclamation($action, $reclamationId, $reponse);

        return $this->redirect('/admin/feedback');
    }
}
