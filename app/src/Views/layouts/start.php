<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/src/CSS/styles.css">
</head>

<body>
    <nav class="navbar">
        <img class="navbar-logo" src='/src/Images/Placeholder.png' alt="Logo" />
        <div class="navbar-title">DOC 2 WHEELS</div>
        <div class="navbar-menu">
            <a href="/homepage">Accueil</a>
            <a href="#">Présentation</a>
            <a href="/createDemand">Demandes</a>
            <a href="#">Contact</a>
        </div>
        <div class="navbar-profile">
            <img class="navbar-profil" src="/src/Images/Avatar.png" alt="Avatar" />
            <div class="profile-dropdown">
                <?php
                $this->startSessionIfNeeded();
                if (isset($_SESSION['user'])) : ?>
                    <?php if ($_SESSION['user']['role'] === 'admin') : ?>
                        <a href="/admin">Admin</a>
                    <?php endif; ?>
                    <a href="/profil">Profil</a>
                    <a href="/profil/demandes">Mes demandes</a>
                    <form action="/logout" method="POST">
                        <button type="submit">Déconnexion</button>
                    </form>
                <?php else : ?>
                    <a href="/login">Connexion</a>
                    <a href="/register">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>