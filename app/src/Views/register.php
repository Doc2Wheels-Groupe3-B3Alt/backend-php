<form action="" method="POST">
    <div id="titre">
        <h1>
            Création de compte
        </h1>
        <p>
            <?php if (isset($message) && !empty($message)) {
                echo $message;
            } ?>
        </p>
    </div>

    <hr>
    <div id="username">
        Identifiant : &nbsp; <input style="width: 30%;" minlength="1" type="text" name="username" rows="1">
    </div>
    <br>
    <div id="password">
        Mot de passe : &nbsp; <input type="password" minlength="8" style="width: 30%;" name="password" rows="1">
    </div>


    <hr>
    <input type="submit" value="submit">
</form>