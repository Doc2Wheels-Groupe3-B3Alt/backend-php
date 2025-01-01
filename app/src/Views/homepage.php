<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- le fichier style.css ne veut pas marcher :,( -->
    <link rel="stylesheet" href="style.css">
    <style>
        .bouton {
            text-decoration: none;
            padding: 12.5px 30px;
            border: 0;
            border-radius: 100px;
            background-color: #2ba8fb;
            color: #ffffff;
            font-weight: Bold;
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }

        .bouton:hover {
            background-color: #6fc5ff;
            box-shadow: 0 0 20px #6fc5ff50;
            transform: scale(1.1);
        }

        .bouton:active {
            background-color: #3d94cf;
            -webkit-transition: all 0.25s;
            transition: all 0.25s;
            box-shadow: none;
            transform: scale(0.98);
        }
    </style>
</head>

<body>
    <h1>Homepage</h1>
    <a class="bouton" href="/login">Login</a>
    <a class="bouton" href="/register">Register</a>
</body>

</html>