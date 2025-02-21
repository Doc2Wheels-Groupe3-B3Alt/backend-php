<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->handlePayment($request);
    }

    private function handlePayment(Request $request): Response
    {
        Stripe::setApiKey('sk_test_51QukgX4GYCGhgx29nYRo3NELcMY5HwpKtTcK1zUNeHDnblFpvxQhQoNA8LVHzpNOFuj9IEx94Gb6XSr8Laq0Uj2z00WDXF51Tx');

        if ($request->getMethod() === 'POST') {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Service Doc2Wheels'],
                        'unit_amount' => 18000, // 180.00€
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'https://votresite.com/success',
                'cancel_url' => 'https://votresite.com/cancel',
            ]);
            return new Response(json_encode($session), 200, ['Content-Type: application/json']);
        } else {
            return $this->render('checkout', get_defined_vars());
        }
    }
}
