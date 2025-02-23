<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterController extends AbstractController
{
    public function process(Request $request): Response
    {
        session_start();

        if ($this->isLoggedIn()) {
            return $this->redirect('/homepage');
        }

        return $this->register();
    }

    private function sendVerificationEmail(string $email, string $token): void
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.privateemail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption TLS
            $mail->Port = 587;

            // Configuration email
            $mail->setFrom($_ENV['MAIL_USERNAME'], 'DOC 2 WHEELS');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // Contenu du mail
            $verificationLink = "http://localhost:8081/verify?token=$token";

            $mail->Subject = 'Activez votre compte';
            $mail->Body = "
                <h1>Merci pour votre inscription !</h1>
                <p>Cliquez sur le lien pour activer votre compte :</p>
                <a href='$verificationLink' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                    Activer mon compte
                </a>
                <p style='color: #666; margin-top: 20px;'>
                    Ce lien expirera dans 24 heures.<br>
                    Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.
                </p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur envoi mail : " . $mail->ErrorInfo);
            // Garder cette ligne en commentaire pour le débogage :
            throw new Exception("Erreur envoi mail : {$mail->ErrorInfo}");
        }
    }

    private function register()
    {
        $db = (new ConnectDatabase())->execute();
        function RemoveSpecialChar($str)
        {
            $res = preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
            return $res;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['username'])) {
                $message = "Veuillez remplir tous les champs";
                $messageColor = "c-red";
            }
            $username = RemoveSpecialChar($_POST['username']);
            $nom = RemoveSpecialChar($_POST['nom']);
            $prenom = RemoveSpecialChar($_POST['prenom']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $email = ($_POST['email']);

            $testUniqueUsername = $db->query("SELECT * FROM Utilisateurs WHERE username='$username'");
            $resultUsername = $testUniqueUsername->rowCount();

            $testUniqueEmail = $db->query("SELECT * FROM Utilisateurs WHERE email='$email'");
            $resultEmail = $testUniqueEmail->rowCount();

            $stmt = $db->prepare("INSERT INTO Utilisateurs (username, password, email, nom, prenom) VALUES (:username, :password, :email, :nom, :prenom)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);

            if (strlen($_POST['password']) >= 8) {
                $checkPasswordNmbChar = "check-password-true";
            } else {
                $checkPasswordNmbChar = "check-password-false";
            }

            if (!$resultUsername == 0) {
                $message = "Cet identifiant est déjà utilisé";
                $messageColor = "c-red";
            } elseif (!$resultEmail == 0) {
                $message = "Cet email est déjà utilisé";
                $messageColor = "c-red";
            } elseif (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
                $message = "Veuillez remplir tous les champs";
                $messageColor = "c-red";
            } elseif (strlen($_POST['password']) < 8) {
                $message = "Le mot de passe doit comporter au moins 8 caractères";
                $messageColor = "c-red";
            } else {
                // Génération du token
                $verificationToken = bin2hex(random_bytes(32));
                $verificationExpires = date('Y-m-d H:i:s', strtotime('+24 hours'));

                // Modification de la requête SQL
                $stmt = $db->prepare("
                    INSERT INTO Utilisateurs 
                    (username, password, email, nom, prenom, verification_token, verification_expires, is_verified) 
                    VALUES (:username, :password, :email, :nom, :prenom, :token, :expires, FALSE)
                ");

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':token', $verificationToken);
                $stmt->bindParam(':expires', $verificationExpires);

                if ($stmt->execute()) {
                    // SUPPRIMER la création de session ici
                    $this->sendVerificationEmail($email, $verificationToken);
                    $message = "Inscription réussie ! Vérifiez vos emails pour activer votre compte";
                    $messageColor = "c-green";
                }
            }
        }
        return $this->render('register', get_defined_vars());
    }
}
