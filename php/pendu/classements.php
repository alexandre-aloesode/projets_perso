<?php 

    include 'stats.php';

    $request_classement = "SELECT login, parties, victoires, ROUND(victoires * 100.0 / parties, 1) AS Percent FROM Users ORDER BY parties DESC ";
    $query_classement = $mysqli->query($request_classement);
    $classement = $query_classement->fetch_array(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="classements.css" rel="stylesheet">
    <title>Classements Pendu</title>
</head>
<body>

    <?php include 'header.php' ?>

    <main>
        
        <section id="classement">

            <h2>Classement Général</h2>

            <table>

                <thead>
                    <th>Joueur</th>
                    <th>Nombre de parties</th>
                    <th>Nombre de victoires</th>
                    <th>Pourcentage de victoires</th>
                </thead>

                <tbody>
                    <?php
                        while($classement !=null){
                            echo '<tr>';
                            foreach($classement as $value){
                                echo '<td>' . $value . '</td>';
                            }
                            $classement = $query_classement->fetch_array(MYSQLI_ASSOC);
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>