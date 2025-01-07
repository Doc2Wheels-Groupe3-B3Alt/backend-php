<form action="/login" method="POST" class="page-connexion p-8 bg-grey">
    <div id="titre">
        <h1>
            Connexion
        </h1>
    </div>
    <p class="h-7 <?php echo $messageColor ?>">
        <?php if (isset($message) && !empty($message)) {
            echo $message;
        } ?>
    </p>
    <div class="connexion-bloc">
        <input type="text" class="input" minlength="1" name="username" placeholder="Username or Email" />
        <input type="password" class="input" placeholder="Password" minlength="8" name="password" />
        <input type="submit" value="Confirmer" class="button bg-light-grey c-black ml-8 mr-8">
    </div>
</form>