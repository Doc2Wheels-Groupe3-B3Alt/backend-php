<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\Demande;
use App\Entities\ReclamationEntity;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ProfilDemandController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user'])) {
            return $this->redirect('/login');
        }

        $userId = $_SESSION['user']['id'];

        $demandeEntity = new Demande();
        $demandes = $demandeEntity->getDemandesByUserId($userId);

        $reclamationEntity = new ReclamationEntity();
        $reclamations = $reclamationEntity->getReclamationsByUserId($userId);

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
            return new Response(json_encode($session), 200, ['Content-Type' => 'application/json']);
        }

        return $this->render('profilDemand', get_defined_vars());
    }
}
