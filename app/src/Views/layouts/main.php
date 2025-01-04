<?php

use App\CSS\Styles;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        <?php
        $styles = new Styles;
        $css = $styles->getStyles();
        echo $css;
        ?>
    </style>
</head>

<body>
<nav class="menu" id="nav">
	<span class="nav-item active">
		<span class="icon">
			<i data-feather="home"></i>
		</span>
		<a href="/homepage">Home</a>
	</span>
	<span class="nav-item">
		<span class="icon">
			<i data-feather="search"></i>
		</span>
		<a href="/login">Login</a>
	</span>
	<span class="nav-item">
		<span class="icon">
			<i data-feather="search"></i>
		</span>
		<a href="/register">Register</a>
	</span>
</nav>

{{content}}

</body>

</html>