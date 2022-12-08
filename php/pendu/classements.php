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
    <link href="header.css" rel="stylesheet">
    <link href="footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
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

    <?php include 'footer.php' ?>

</body>
</html>