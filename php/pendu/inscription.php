<?php

// variable user suivie de la requète pour vérifier que le nom de login n'existe pas déjà. 
// Si le nom existe déjà la requète renvoie la valeur 0 à $user
    $user = 1;
    $message;
    if(isset($_POST['inscription'])){

//Si certains champs sont vides on renvoit un message d'erreur.
        if(empty($_POST['pseudo']) || empty($_POST['mdp'])){
            $message = 'Certains champs sont vides';
        }

        else{
            
            include 'connecSQL.php';
            
            $request_user= "SELECT `login` FROM `Users`";
            $query_user = $mysqli->query($request_user);
            $result_user = $query_user->fetch_all();

    // Je commence par vérifier si le nom d'utilisateur existe déjà.
            for($x = 0; isset($result_user[$x]); $x++ ){
                for($i = 0; isset($result_user[$x][$i]); $i++)
                    if($result_user[$x][$i] == $_POST['pseudo']){
                        $user = 0;
                    }
            } 
            
    //création du compte si le user n'existe pas déjà et redirection vers page de connexion comme demandé dans l'énoncé.
            if($_POST['mdp'] == $_POST['mdp_confirm'] && $user == 1){
                $login = $_POST['pseudo'];
                $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
                $request_create = "INSERT INTO `Users`(`login`, `password`, parties, victoires, defaites) 
                VALUES ('$login','$mdp_hash', 0, 0, 0)";
                $query_create = $mysqli->query($request_create);

// Les lignes ci_dessous me permettent de connecter automatiquement l'utilisateur après avoir créé son compte. Ensuite dans connec.php je vais récupérer son ID.      
                if(session_id() == ''){
                    session_start();
                }
                $_SESSION['user'] = $_POST['pseudo'];
                // header('Location: connexion.php');
                }

    // conditions pour vérifier si le mdp est bien tapé, si le pseudo existe déjà, ou si le compte 
    // a bien été crée et afficher un message en foncton.
            if($user == 1 && $_POST ['mdp'] == $_POST['mdp_confirm']){
                $message =  'Compte créé avec succès';
            }elseif($_POST['mdp'] !== $_POST['mdp_confirm']){
                $message =  'Les mots de passe ne correspondent pas';
            }elseif($user == 0){
                $message = 'Ce pseudo existe déjà';
            }
        }
    }  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="header.css" rel="stylesheet">
    <link href="footer.css" rel="stylesheet">
    <link href="formulaires.css" rel = "stylesheet">
    <title>Inscription</title>
</head>
<body>

    <?php include 'header.php' ?>

    <main> 

        <form method="post" class ="formulaire">

            <h2>
                <?php
                    if(!empty($_POST['pseudo']) && isset($_POST['inscription']) && $user == 1 && $_POST ['mdp'] == $_POST['mdp_confirm']){
                        echo 'Félicitations!';
                    }
                    else{
                        echo 'Inscription';
                    }
                ?>   
            </h2>

            <h3>
                <?php 
                    if(isset($_POST['inscription'])) { 
                        echo $message;
                    }
                ?>
            </h3>

            <?php
                if(!isset($_POST['inscription']) || $_POST['mdp'] !== $_POST['mdp_confirm'] || $user == 0 || empty($_POST['pseudo'])):
            ?>
                <label for="pseudo" class="form_label">Pseudo :</label>
                <br>
                <input type="text" name="pseudo" class="form_input">
                <br>

                <label for="mdp" class="form_label">MDP :</label>
                <br>
                <input type="password" name="mdp" class="form_input">
                <br>

                <label for="mdp_confirm" class="form_label">Confirme ton MDP </label>
                <br>
                <input type="password" name="mdp_confirm" class="form_input">
                <br>

                <button type="submit" name="inscription" class="form_button">Confirmer</button>
                </form>
            
            <?php endif; ?>
    </main> 

    <?php include 'footer.php' ?>
    
</body>
</html>