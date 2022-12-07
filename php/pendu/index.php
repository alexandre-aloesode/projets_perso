<?php
    include 'connec.php';
    
    include 'connecSQL.php';

    $request_max= "SELECT login, parties FROM `Users` ORDER BY `Users`.`parties` DESC";
    $biggest_player = $mysqli->query($request_max);
    $show_biggest_player = $biggest_player->fetch_all();

    $request_min= "SELECT login, parties FROM `Users` ORDER BY `Users`.`parties` ASC";
    $lowest_player = $mysqli->query($request_min);
    $show_lowest_player = $lowest_player->fetch_all();

    
    $request_mvp = "SELECT login, victoires, parties, ROUND(victoires * 100.0 / parties, 1) AS Percent FROM Users";
    $mvp = $mysqli->query($request_mvp);
    $stats = $mvp->fetch_all();
    //Je n'arrivais pas à inclure dans ma requête de ne sélectionner que le meilleur %age. Je crée donc ci-dessous 2 
    //variables nulles qui viennent grâce à ma boucle for récupérer d'abord le %age le plus élevé, ensuite le nom du 
    //joueur associé à ce %age.
    $res = 0;
    $player = '';
    for($x = 0; isset($stats[$x][3]); $x++){
        if($stats[$x][3] > $res){
            $res = $stats[$x][3];
            $player = $stats[$x][0];
        }       
    }


    $request_total_parties = "SELECT SUM(parties) FROM Users";
    $total_parties = $mysqli->query($request_total_parties);
    $show_total_parties = $total_parties->fetch_all();

    $request_total_victoires = "SELECT SUM(victoires) FROM Users";
    $total_victoires = $mysqli->query($request_total_victoires);
    $show_total_victoires = $total_victoires->fetch_all();

    $request_avg_len = "SELECT AVG(longueur) FROM Mots";
    $avg_len = $mysqli->query($request_avg_len);
    $show_avg_len = $avg_len->fetch_all();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="header.css" rel="stylesheet">
    <link href="footer.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Page d'accueil</title>
</head>

<body>

    <?php include 'header.php' ?>

    <main>

    <div id="panneau_accueil">

        <img id="accueil_img" 
        src="panneau_western.png">

        <h2>Welcome to <br> Hanging Town</h2>

    </div>

    <div id="smartphone">

        <h2>Bienvenue !</h2>

        <a href ="connexion.php">
            <button>Connexion</button>
        </a>

        <a href ="inscription.php">
            <button>Inscription</button>
        </a>

        <a href ="pendu.php">
            <button>Jouer</button>
        </a>

    </div>

    <!-- <div id="best_players">

        <img id="best_players_img1" src="https://i.pinimg.com/originals/1a/21/f0/1a21f05ba6e3e7ae528ccfa538fb7cef.png">
        <img id="best_players_img1" src="https://i.pinimg.com/originals/1a/21/f0/1a21f05ba6e3e7ae528ccfa538fb7cef.png">

    </div> -->

    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>