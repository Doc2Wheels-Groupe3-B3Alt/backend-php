<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class ProfilEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->updateUser($request);
    }

    private function updateUser(Request $request): Response
    {
        $db = (new ConnectDatabase())->execute();

        // Récupération des données utilisateur avec jointure
        $user = $db->query(
            "
            SELECT u.*, i.horaire_id , a.*
            FROM Utilisateurs u
            LEFT JOIN Adresses a ON u.adresse_id = a.id
            LEFT JOIN Intervenants i ON u.intervenant_id = i.id
            WHERE u.id = " . $_SESSION['user']['id']
        )->fetch();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Mise à jour des informations de base
                $stmt = $db->prepare("
                    UPDATE Utilisateurs SET
                        nom = :nom,
                        prenom = :prenom,
                        email = :email
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':nom' => htmlspecialchars($_POST['nom']),
                    ':prenom' => htmlspecialchars($_POST['prenom']),
                    ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    ':id' => $_SESSION['user']['id']
                ]);

                // Mise à jour de l'adresse
                if (isset($_POST['adresse'], $_POST['ville'], $_POST['codepostal'])) {
                    if ($user['adresse_id']) {
                        // Mise à jour de l'adresse existante
                        $stmt = $db->prepare("
                            UPDATE Adresses SET
                                adresse = :adresse,
                                ville = :ville,
                                codePostal = :codepostal
                            WHERE id = :id
                        ");
                        $stmt->execute([
                            ':adresse' => htmlspecialchars($_POST['adresse']),
                            ':ville' => htmlspecialchars($_POST['ville']),
                            ':codepostal' => htmlspecialchars($_POST['codepostal']),
                            ':id' => $user['adresse_id']
                        ]);
                    } else {
                        // Création d'une nouvelle adresse
                        $stmt = $db->prepare("
                            INSERT INTO Adresses (adresse, ville, codePostal)
                            VALUES (:adresse, :ville, :codepostal)
                        ");
                        $stmt->execute([
                            ':adresse' => htmlspecialchars($_POST['adresse']),
                            ':ville' => htmlspecialchars($_POST['ville']),
                            ':codepostal' => htmlspecialchars($_POST['codepostal'])
                        ]);
                        $adresseId = $db->lastInsertId();

                        // Mise à jour de l'utilisateur avec la nouvelle adresse
                        $stmt = $db->prepare("
                            UPDATE Utilisateurs SET
                                adresse_id = :adresse_id
                            WHERE id = :id
                        ");
                        $stmt->execute([
                            ':adresse_id' => $adresseId,
                            ':id' => $_SESSION['user']['id']
                        ]);
                    }
                }

                // Gestion spécifique aux techniciens
                if ($_SESSION['user']['role'] === 'technicien') {
                    // Mise à jour des horaires
                    if (isset($_POST['horaire_id'])) {
                        $stmt = $db->prepare("
                            UPDATE Intervenants 
                            SET horaire_id = :horaire_id 
                            WHERE id = :intervenant_id
                        ");
                        $stmt->execute([
                            ':horaire_id' => $_POST['horaire_id'],
                            ':intervenant_id' => $_SESSION['user']['intervenant_id']
                        ]);
                    }

                    // Revenir à utilisateur standard
                    if (isset($_POST['revert_to_user'])) {
                        $db->beginTransaction();

                        // Suppression des liens
                        $db->exec(
                            "
                            UPDATE Utilisateurs 
                            SET role = 'user', 
                                intervenant_id = NULL, 
                                adresse_id = NULL 
                            WHERE id = " . $_SESSION['user']['id']
                        );

                        // Nettoyage des données associées
                        $db->exec("DELETE FROM Intervenants WHERE id = " . $_SESSION['user']['intervenant_id']);
                        $db->exec("DELETE FROM Adresses WHERE id = " . $_SESSION['user']['adresse_id']);

                        $db->commit();

                        // Mise à jour session
                        $_SESSION['user']['role'] = 'user';
                        unset($_SESSION['user']['intervenant_id']);
                        unset($_SESSION['user']['adresse_id']);
                    }
                }
            } catch (\Exception $e) {
                if ($db->inTransaction()) $db->rollBack();
                error_log("Erreur mise à jour : " . $e->getMessage());
            }

            $this->redirect('/profil');
        }

        // Récupération des horaires pour les techniciens
        if ($_SESSION['user']['role'] === 'technicien') {
            $horaires = $db->query("SELECT * FROM HorairesTravail")->fetchAll();
        }

        return $this->render('profilEdit', get_defined_vars());
    }
}
