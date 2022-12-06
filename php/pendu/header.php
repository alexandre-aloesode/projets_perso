<?php
    include 'connec.php'
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="header.css" rel="stylesheet">
    <title>Pendu Header</title>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="pendu.php">Jouer</a></li>
            <li><a href="classements.php">Classements</a></li>
        </ul>
    </nav>
    <nav>
        <ul>
            <?php

                if(isset($_SESSION['user']) && $_SESSION['user'] == 'admin'){
                    echo 
                    '<li><a href="admin.php">Admin</a></li>
                    <li>
                    <form method="get" id="deco_form">
                    <button type="submit" name="deco" value="deco">Déconnexion</button>
                    </form>
                    <li>';
                } elseif(isset($_SESSION['user'])){
                    echo 
                    '<li><a href="profil.php">Profil</a></li>
                    <li>
                    <form method="get" id="deco_form">
                    <button type="submit" name="deco" value="deco">Déconnexion</button>
                    </form>
                    </li>';
                        
                } else{
                    echo 
                    '<li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>

</body>
</html>