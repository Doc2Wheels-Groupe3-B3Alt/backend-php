<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class BeTechnicienController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->handleRequest($request);
    }

    private function handleRequest(Request $request): Response
    {
        $db = (new ConnectDatabase())->execute();

        if ($_SESSION['user']['role'] === 'technicien') {
            return $this->redirect('/profil');
        }

        $horaires = $db->query("SELECT * FROM HorairesTravail")->fetchAll();
        $adresse = $db->query("SELECT * FROM Adresses WHERE id = " . ($_SESSION['user']['adresse_id'] ?? 0))->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db->beginTransaction();

                $stmt = $db->prepare("
                    INSERT INTO Adresses (adresse, ville, codePostal, complement)
                    VALUES (:adresse, :ville, :codePostal, :complement)
                ");
                $stmt->execute([
                    ':adresse' => htmlspecialchars($_POST['adresse']),
                    ':ville' => htmlspecialchars($_POST['ville']),
                    ':codePostal' => htmlspecialchars($_POST['codePostal']),
                    ':complement' => htmlspecialchars($_POST['complement'] ?? '')
                ]);
                $adresseId = $db->lastInsertId();

                // Création du profil intervenant
                $stmt = $db->prepare("
                    INSERT INTO Intervenants (horaire_id, notes)
                    VALUES (:horaire_id, :notes)
                ");
                $stmt->execute([
                    ':horaire_id' => $_POST['horaire_id'],
                    ':notes' => htmlspecialchars($_POST['notes'] ?? '')
                ]);
                $intervenantId = $db->lastInsertId();

                // Mise à jour de l'utilisateur
                $stmt = $db->prepare("
                    UPDATE Utilisateurs SET
                        role = 'technicien',
                        adresse_id = :adresse_id,
                        intervenant_id = :intervenant_id
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':adresse_id' => $adresseId,
                    ':intervenant_id' => $intervenantId,
                    ':id' => $_SESSION['user']['id']
                ]);

                $db->commit();

                // Mettre à jour la session
                $_SESSION['user']['role'] = 'technicien';
                $_SESSION['user']['adresse_id'] = $adresseId;
                $_SESSION['user']['intervenant_id'] = $intervenantId;
            } catch (\Exception $e) {
                $db->rollBack();
                error_log("Erreur lors de la création du technicien : " . $e->getMessage());
            }

            return $this->redirect('/profil');
        }

        return $this->render('beTechnicien', get_defined_vars());
    }
}
