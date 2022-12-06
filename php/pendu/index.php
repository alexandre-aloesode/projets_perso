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
        }
        $player = $stats[$x][0];
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
    <link href="index.css" rel="stylesheet">
    <title>Page d'accueil</title>
</head>
<body>
    <div class="background">
        <?php
            include 'header.php';
        ?>
    <main>
        <section id="accueil">

            <div id="accueil_texte">
                <h2>Bienvenue à toi aventurier !</h2>
                <p>Connecte toi pour pousser fort ... 
                    <a href="connexion.php">c'est par ici !</a>
                </p>
                <p>Si tu n'as pas encore de compte, il n'est jamais trop tard pour pousser fort ...
                    <a href="inscription.php">c'est par ici !</a>
                </p>
                <p>Sinon lance-toi direct dans l'aventure en mode random ...
                    <a href="pendu.php">c'est par ici !</a>
                </p>
            </div>

            <div id="accueil_stats">
                <h3>Jusqu'ici ... </h3>
                <br>
                <h4>Total de parties jouées :</h4>
                <p> <?php
                       echo $show_total_parties[0][0];
                ?></p>
                <h4>Total de victoires :</h4>
                <p> <?php
                        echo $show_total_victoires[0][0];
                ?></p>
                <h4>Longueur moyenne des mots :</h4>
                <p> <?php
                        echo $show_avg_len[0][0];                
                ?></p>
            </div>
        </section>

        <section id="stats">

            <div class="stat">
                <h3>Celui qui pousse très fort</h3>
                <p>Pseudo :
                    <?php
                        echo $show_biggest_player[0][0];
                    ?>
                </p>
                <p>Nombre de parties :
                    <?php
                        echo $show_biggest_player[0][1];
                    ?>
                </p>
            </div>

            <div class="stat">
                <h3>Celui qui pousse efficacement</h3>
                <p>Pseudo :
                    <?php
                        echo $player;
                    ?>
                </p>
                <p>Pourcentage de victoires :
                    <?php
                    echo $res . '%';
                    ?>
                </p>
            </div>

            <div class="stat">
                <h3>Celui qui pousse pas assez fort</h3>
                <p>Pseudo :
                    <?php
                        echo $show_lowest_player[0][0];
                    ?>
                </p>
                <p>Nombre de parties :
                    <?php
                        echo $show_lowest_player[0][1];
                    ?>
                </p>
            </div>
        </section>
    </main>
    </div>
</body>
</html>