<h1>Homepage</h1>
<?php if (isset($_SESSION['user'])): ?>
    <p>Bienvenue <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
<?php endif; ?>
<a class="button" href="/login">Login</a>
<a class="button" href="/register">Register</a>