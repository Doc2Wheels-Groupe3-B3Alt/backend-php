<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminTechniciensController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminTechnicien();
    }

    private function adminTechnicien()
    {
        $db = (new ConnectDatabase())->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $stmt = $db->prepare("DELETE FROM Utilisateurs WHERE id = :id");
            $stmt->execute([':id' => $_POST['delete_id']]);
        }

        $users = $db->query("
            SELECT 
                u.id,
                u.username,
                u.email,
                u.nom,
                u.prenom,
                u.role,
                TO_CHAR(u.date_creation, 'DD/MM/YYYY HH24:MI') as date_creation,
                ht.heure_debut,
                ht.heure_fin
            FROM Utilisateurs u
            LEFT JOIN Intervenants i ON u.intervenant_id = i.id
            LEFT JOIN HorairesTravail ht ON i.horaire_id = ht.id
            WHERE u.role = 'technicien'
            ORDER BY u.date_creation DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminTechniciens', get_defined_vars());
        }
    }
}
