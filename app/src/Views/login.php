<form action="/login" method="POST">
    <div id="titre">
        <h1>
            Connexion à votre compte
        </h1>
    </div>
    <hr>
    <div id="message">
        <?php
        echo $message;
        ?>
    </div>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>