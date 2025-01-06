<?php

use App\CSS\Styles;

?>

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
			<a href="#">Demandes</a>
			<a href="#">Contact</a>
		</div>
		<img class="navbar-profil" src="/src/Images/Avatar.png" alt="Avatar" />
	</nav>

	{{content}}

</body>

</html>