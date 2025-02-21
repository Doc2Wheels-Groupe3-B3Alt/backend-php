<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ProfilDemandController extends AbstractController
{
    public function process(Request $request): Response
    {


        $this->startSessionIfNeeded();
        $db = (new ConnectDatabase())->execute();

        if (!isset($_SESSION['user'])) {
            return $this->redirect('/login');
        }

        $stmt = $db->prepare("
            SELECT d.*, 
                m.marque, m.modele, m.type, 
                l.adresse AS localisation_adresse, l.ville AS localisation_ville, l.codePostal AS localisation_code_postal,
                s.nom AS service_nom, s.description AS service_description
            FROM Demandes d
            LEFT JOIN Modeles m ON d.modele_id = m.id
            LEFT JOIN Localisations l ON d.localisation_id = l.id
            LEFT JOIN Services s ON d.services_id = s.id
            WHERE d.user_id = :id
        ");
        $stmt->execute([':id' => $_SESSION['user']['id']]);

        $demandes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT demande_id
            FROM Reclamations
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $reclamations = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        Stripe::setApiKey($_ENV['STRIPE_API_KEY']);

        if ($request->getMethod() === 'POST') {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Service Doc2Wheels'],
                        'unit_amount' => 17000, // 170.00€
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'https://votresite.com/success',
                'cancel_url' => 'https://votresite.com/cancel',
            ]);
            return new Response(json_encode($session), 200, ['Content-Type: application/json']);
        }

        return $this->render('profilDemand', get_defined_vars());
    }
}
