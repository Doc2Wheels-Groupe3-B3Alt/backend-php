<form action="" method="POST" class="page-connexion p-8 bg-grey">
    <div id="titre">
        <h1>
            Inscription
        </h1>
    </div>
    <p class="h-7 <?php echo $messageColor ?>">
        <?php if (isset($message) && !empty($message)) {
            echo $message;
        } ?>
    </p>
    <div class="connexion-bloc">
        <input type="text" class="input" minlength="1" name="username" placeholder="Pseudonyme" />
        <input type="email" class="input" minlength="1" name="email" placeholder="Email" />
        <input type="text" class="input" minlength="1" name="nom" placeholder="Nom" />
        <input type="text" class="input" minlength="1" name="prenom" placeholder="Prénom" />
        <input type="password" class="input" placeholder="Mot de passe" minlength="8" name="password" />
        <ul>
            <li class="<?php echo $checkPasswordNmbChar ?>">8 caractères minimum</li>
        </ul>
        <input type="submit" value="Confirmer" class="button bg-light-grey c-black ml-8 mr-8">
        <p>Déjà inscrit ? <a href="/register">Connectez-vous</a></p>
    </div>
</form>