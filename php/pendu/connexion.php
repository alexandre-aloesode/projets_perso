<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="formulaires.css" rel = "stylesheet">
    <title>Page de connexion</title>
</head>
<body>

    <?php 
        include 'header.php';
        include 'connec.php';
    ?>

    <main>
        <form method="post" class="formulaire">
        <?php 
            if($check !== 2) {
            echo '
            <h2>Remplis les champs suivants pour te connecter</h2>';
            }
        ?>

        <h3>
            <?php
                if(isset($_POST['connexion'])){
                    echo $message;
                }
            ?>
        </h3>

        <?php     
            if(isset($_POST['connexion']) && $check == 2) {
                echo '
                <h2>Bonjour et bienvenue ' . $_POST['pseudo'] . ' !</h2>';
            } 
            
            else {
                echo '
                
                <label for="pseudo" class="form_label">Pseudo :</label><br>
                <input type="text" name="pseudo" class="form_input">
                <br>
                <label for="password" class="form_label">Mot de passe :</label><br>
                <input type="password" name="mdp" class="form_input">
                <br>
                <button type="submit" name="connexion" 
                class="form_button">Connexion</button>
                ' ;
            }
        ?>
        </form>
        </main>
</body>
</html>