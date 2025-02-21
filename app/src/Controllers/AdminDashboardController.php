<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminDashboardController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->dashboard();
    }

    private function dashboard()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        $db = (new ConnectDatabase())->execute();

        $periode = $_GET['periode'] ?? '30';
        $date_debut = date('Y-m-d', strtotime("-$periode days"));

        $stats_globales = $this->getStatsGlobales($db, $date_debut);

        $stats_techniciens = $this->getStatsTechniciens($db, $date_debut);

        $evolution_demandes = $this->getEvolutionDemandes($db);

        $top_services = $this->getTopServices($db, $date_debut);

        return $this->render('adminDashboard', get_defined_vars());
    }

    private function getStatsGlobales($db, $date_debut)
    {
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total_demandes,
                COUNT(CASE WHEN date_fin < CURRENT_TIMESTAMP THEN 1 END) as demandes_terminees
            FROM Demandes
            WHERE date_debut >= :date_debut
        ");
        $stmt->execute([':date_debut' => $date_debut]);
        $stats = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT user_id) as nombre_clients
            FROM Demandes
            WHERE date_debut >= :date_debut
        ");
        $stmt->execute([':date_debut' => $date_debut]);
        $clients = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stats['nombre_clients'] = $clients['nombre_clients'];

        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT intervenant_id) as nombre_techniciens
            FROM Demandes
            WHERE date_debut >= :date_debut
            AND intervenant_id IS NOT NULL
        ");
        $stmt->execute([':date_debut' => $date_debut]);
        $techniciens = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stats['nombre_techniciens'] = $techniciens['nombre_techniciens'];

        return $stats;
    }

    private function getStatsTechniciens($db, $date_debut)
    {
        $stmt = $db->prepare("
            SELECT 
                u.id,
                u.nom,
                u.prenom,
                COUNT(d.id) as nombre_demandes,
                COUNT(CASE WHEN d.date_fin < CURRENT_TIMESTAMP THEN 1 END) as demandes_terminees,
                s.nom as service_plus_frequent,
                COUNT(DISTINCT d.user_id) as nombre_clients_uniques
            FROM Utilisateurs u
            LEFT JOIN Intervenants i ON u.intervenant_id = i.id
            LEFT JOIN Demandes d ON d.intervenant_id = i.id AND d.date_debut >= :date_debut
            LEFT JOIN Services s ON d.services_id = s.id
            WHERE u.role = 'technicien'
            GROUP BY u.id, u.nom, u.prenom, s.nom
            ORDER BY nombre_demandes DESC
        ");
        $stmt->execute([':date_debut' => $date_debut]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getEvolutionDemandes($db)
    {
        $stmt = $db->query("
            SELECT 
                TO_CHAR(date_trunc('month', date_debut), 'YYYY-MM') as mois,
                COUNT(*) as nombre_demandes
            FROM Demandes
            WHERE date_debut >= date_trunc('month', CURRENT_DATE - interval '12 months')
            GROUP BY date_trunc('month', date_debut)
            ORDER BY mois ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getTopServices($db, $date_debut)
    {
        $stmt = $db->prepare("
            SELECT 
                s.nom as service,
                COUNT(*) as nombre,
                COUNT(DISTINCT d.user_id) as clients_uniques
            FROM Demandes d
            JOIN Services s ON d.services_id = s.id
            WHERE d.date_debut >= :date_debut
            GROUP BY s.nom
            ORDER BY nombre DESC
            LIMIT 5
        ");
        $stmt->execute([':date_debut' => $date_debut]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
