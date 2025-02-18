<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class VerifyController extends AbstractController
{
    public function process(Request $request): Response
    {
        $token = $request->getQueryParams('token');

        if (empty($token)) {
            return $this->render('verification-error');
        }

        $db = (new ConnectDatabase())->execute();

        try {
            $stmt = $db->prepare("
                UPDATE Utilisateurs 
                SET is_verified = TRUE, 
                    verification_token = NULL,
                    verification_expires = NULL 
                WHERE verification_token = :token
                AND verification_expires > NOW()
            ");

            $stmt->execute([':token' => $token]);

            if ($stmt->rowCount() === 0) {
                throw new \Exception("Token invalide ou expiré");
            }

            return $this->render('verification-success');
        } catch (\Exception $e) {
            error_log("Échec vérification : " . $e->getMessage());
            return $this->render('verification-error');
        }
    }
}
