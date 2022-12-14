<?php
    include 'connec.php';

// ci_dessous une requête php my admin qui me permet de récupérer les infos du profil pour les 
// utiliser dans la page profil.php pour afficher les infos du profil du user connecté
    include 'connecSQL.php';
    $request_fetch_user_info= "SELECT * FROM `Users` where id = '$_SESSION[userID]'";
    $query_fetch_user_info = $mysqli->query($request_fetch_user_info);
    $result_fetch_user_info = $query_fetch_user_info->fetch_all();

    $message_profil;
    $user = 1;

    if(isset($_POST['profil_change'])){  

        if(empty($_POST['profil_pseudo']) || empty($_POST['profil_new_mdp'])){
            $message_profil = 'Certains champs sont vides';
        }

        elseif($_POST['profil_new_mdp'] !== $_POST['new_mdp_confirm']){
            $message_profil = 'Les nouveaux mots de passe ne correspondent pas';
        }

        elseif(!password_verify($_POST['mdp'], $result_fetch_user_info[0][2])){
            $message_profil = 'Ancien mot de passe incorrect.';
        }
// Après mon isset qui vérifie si l'utilisateur a cliqué sur le bouton, je commence par vérifier avec le 1er if si tous les champs sont 
// remplis. Le 2eme if vérifie si les 2 nouveaux mdp concordent, le 3eme si l'ancien mot de passe est bon. Si ces 3 conditions sont remplies 
// je passe à la suite ci-dessous, qui se lance si l'ancien mdp est juste.

        elseif(password_verify($_POST['mdp'], $result_fetch_user_info[0][2])){

            if(!empty($_POST['profil_pseudo']) && $_POST['profil_pseudo'] !==  $result_fetch_user_info[0][1]){         
                $request_user= "SELECT `login` FROM `Users`";
                $query_user = $mysqli->query($request_user);
                $result_user = $query_user->fetch_all();
                for($x = 0; isset($result_user[$x]); $x++ ){
                    for($i = 0; isset($result_user[$x][$i]); $i++)
                        if($result_user[$x][$i] == $_POST['profil_pseudo']){
                            $user = 0;
                        }
                }
// Ci-dessus la même requête et la même boucle for que dans inscription qui me permettent de vérifier si le nouveau login existe déjà, 
// et me renvoient $user=0 si c'est le cas.

                if($user == 0){
                    $message_profil = "Ce nom d'utilisateur existe déjà";
                }

                else{
                $update_user_profil = "UPDATE Users 
                SET login = '$_POST[profil_pseudo]' 
                WHERE id= '$_SESSION[userID]'";
                $query_update_user_profil = $mysqli->query($update_user_profil);
                $message_profil = "informations modifiées.";
                }
            }

// J'ai séparé les modifs du pseudo et du mdp, donc pour éviter que la modif du mdp se lance même si le nouveau pseudo choisi existe 
// déjà, je rajoute également la condition $user=1 en dessous.

            if(!empty($_POST['profil_new_mdp']) && $_POST['profil_new_mdp'] == $_POST['new_mdp_confirm'] && $user == 1){
                $modified_mdp_hashed = password_hash($_POST['profil_new_mdp'], PASSWORD_DEFAULT);
                $update_user_profil = "UPDATE Users 
                SET password = '$modified_mdp_hashed' 
                WHERE id= '$_SESSION[userID]'";
                $query_update_user_profil = $mysqli->query($update_user_profil);
                $message_profil = "informations modifiées.";
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
    <link href="formulaires.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
    <link href="footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Profil</title>
</head>
<body>

    <?php include 'header.php'?>

    <main>
        
        <form method="post" class ="formulaire">

        <h2>Edition profil</h2>

        <h3>
           <?php 
                if(isset($_POST['profil_change'])) { 
                        echo $message_profil;
                }
            ?>
        </h3>

            <label for="pseudo" class="form_label">Pseudo : </label>
            <br>
            <input type="text" name="profil_pseudo" value="<?php echo $result_fetch_user_info[0][1]?>" class="form_input" >
            <br>       
<!-- infos des values récupérées grâce à ma requête sql du haut de la page -->

            <label for="new_mdp" class="form_label">Nouveau MDP :</label>
            <br>
            <input type="password" name="profil_new_mdp" class="form_input">
            <br>

            <label for="new_mdp_confirm" class="form_label">Confirme ton nouveau MDP </label>
            <br>
            <input type="password" name="new_mdp_confirm" class="form_input">
            <br>

            <label for="mdp" class="form_label">Ancien MDP :</label>
            <br>
            <input type="password" name="mdp" class="form_input">
            <br>

            <button type="submit" name="profil_change" class="form_button">Modifier</button>

        </form>

    </div>
    </main>
    
    <?php include 'footer.php' ?>

</body>
</html>