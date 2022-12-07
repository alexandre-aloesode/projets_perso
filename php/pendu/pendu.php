<?php
    
    include 'connec.php';
    include 'stats.php';   
    
    if(isset($_SESSION['chosen_word'])){
        if(isset($_GET['reset']) && $_GET['reset'] == 'reset') {
            $_SESSION['chosen_word'] = '';
        }
    }
// Cette condition est pour le bouton rejouer, qui génère une nouvelle partie.

    $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
	$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

    $dico = file('dico_france.txt');
    $dico_sans_espaces= array_map('trim', $dico);
    $wCount = count($dico_sans_espaces);
// Je sélectionne le fichier dans la 1ère ligne, je retire les espaces dans la 2ème, et je compte 
// le nombre de mots dans la 3ème. Le compte me permet de générer un mot au hasard plus bas.

    
    if(!isset($_SESSION['chosen_word']) || $_SESSION['chosen_word'] == '') {
        
        $_SESSION['chosen_word'] = $dico_sans_espaces[rand(0, $wCount -1)];
        $_SESSION['chosen_word'] = str_replace($search, $replace, $_SESSION['chosen_word']);
        $_SESSION['chosen_word'] = strtoupper($_SESSION['chosen_word']);
// Ces 3 lignes me permettent de générer un mot au hasard que je récupère dans un fichier texte. 
// Je remplace les accents et je finis par mettre toutes les lettres en majuscules. Si le chosen_word =='' ça veut dire que 
// le joueur a cliqué sur rejouer, et donc le code regénère un nouveau mot.

        $_SESSION['turn'] = 0;
// J'ai créé cette variable de session que j'incrémente de 1 à chaque tour pour pouvoir faire ma stat sur le nombre de parties lancées.

        $_SESSION['hidden_word'] = '';
        for($x = 0; $x < strlen($_SESSION['chosen_word']); $x++) {
            $_SESSION['hidden_word'] = $_SESSION['hidden_word'] . '-';
        }
// hidden_word me génère autant de tirets qu'il y a de lettres dans le mot choisi. chaque fois 
// qu'une lettre correspondante est trouvée, le ou les tirets sont remplacés par la lettre.

        $_SESSION['lifes'] = 7;

        $_SESSION['history'] = '';
    }
// lifes, permet d'afficher le nombre de vies restantes du joueur et la fin du jeu.
// history va venir stocker les lettres proposées par le joueur qui ne sont pas dans le mot choisi.

        
    if(isset($_GET['letter'])) {
        $repeat = 0;
        if(str_contains($_SESSION['hidden_word'], $_GET['letter']) || str_contains($_SESSION['history'], $_GET['letter'])) {
            $message = 'Tu as déjà proposé cette lettre.';
            $repeat = 1;
        }

        else {
            if(str_contains($_SESSION['chosen_word'], $_GET['letter'])) {
                for($i = 0; $i < strlen($_SESSION['chosen_word']); $i++) {
                    if($_SESSION['chosen_word'][$i] == $_GET['letter']) {
                        $_SESSION['hidden_word'][$i] = $_GET['letter'];
                    }
                }
// Les boutons du clavier grâce auxquels le use joue ont une value nommée letter. les lignes ci-dessus vérifient si la lettre 
// proposée par le joueur est comprise dans le mot. J'utilise une boucle au cas où une même lettre revient plusieurs fois dans le mot.
// Mon premier if vérifie si une lettre a été cliquée. Je commence a vérifier si la lettre a été proposée avec mon compteur $repeat, 
// et si ce n'est pas le cas on passe à la suite.
                    
            } 
            
            else {
            $_SESSION['lifes'] --;
            $_SESSION['history'] = $_SESSION['history'] . $_GET['letter'] . '-';
// Si le joueur propose une mauvaise lettre, il perd une vie et la lettre est rajoutée dans l'historique.
            }
        }
    }


// ci-dessous les conditions qui vont adapter ma variable $message, qui est en quelque sorte 
// les consignes du jeu.

    $message;

    if($_SESSION['lifes'] == 7) {
        $message = 'Tu as un mot de ' . strlen($_SESSION['chosen_word']). ' lettres à trouver';
    }

    if($_SESSION['lifes'] == 0 && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']) {
        $message = 'Perdu ! Le mot à trouver était ... ' . $_SESSION['chosen_word'];
    }
        
    if(isset($_GET['letter']) && $_SESSION['lifes'] > 0 && $repeat == 0) {
        if($_SESSION['hidden_word'] == $_SESSION['chosen_word']){
            $message = 'Tu as trouvé le mot, Bravo !';
        }            
        elseif(str_contains($_SESSION['chosen_word'], $_GET['letter'])
        && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']){
            for($i = 0; $i < strlen($_SESSION['chosen_word']); $i++) {
                if($_SESSION['chosen_word'][$i] == $_GET['letter']) {
                    $message = 'Bien vu !';
                    break;
                }
            }
        } 
// Cette boucle permet d'afficher 'bien vu' si une bonne lettre est trouvée. Si la lettre est présente 
// plusieurs fois le message apparait à plusieurs reprises. J'inclus donc un break pour que le message 
// n'apparaisse qu'une fois.          
        else {
            $message = 'Raté ! Essaie encore';
        }
    }


    if(isset($_GET['letter']) && $_SESSION['lifes'] < 0) {
        $message = 'La partie est finie ! Clique plutôt sur rejouer ;)';
// Je n'avais pas de solution pour stopper la partie si le joueur n'avait plus de vies et continuait 
// à cliquer sur des lettres. Je contourne le problème en affichant ce message tant que les vies 
// sont < 0, et en n'affichant mes variables history et hidden_word que si les vies sont >=0.
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="pendu.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
    <link href="footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Pendu PHP</title>
</head>

<body>
    
        <?php
            include 'header.php';
        ?>
        <main>  
            
            <section id='replay'>
                <form method ='get' id='reset'>
                    <button type='submit' name='reset' id='reset_button' value='reset'><b>Rejouer</b></button>
                </form>
            </section>

            <section id ='Pendu'>
            
                    <section id='consignes'>
                        <h2>
                            <?php echo $message ?>
                        </h2>
                    </section>

                    <section id='lifes'>
                        <h3>Vies restantes : 
                            <?php 
                                if($_SESSION['lifes'] >= 0) {
                                    echo $_SESSION['lifes'];
                                } 
                            ?>
                        </h3>
                </section>

                    <section id='aide'>

                        <section id="hidden_word">
                            <h4>Mot à trouver :</h4>
                            <br>
                            <p>
                                <?php
                                    if($_SESSION['lifes'] >= 0){
                                        for($i = 0; isset($_SESSION['hidden_word'][$i]); $i++) {
                                            echo $_SESSION['hidden_word'][$i] . ' ';
                                        }
                                    }
        // Je génère déjà le hidden word plus haut, ainsi que le remplacement des tirets par la lettre trouvée. 
        // Ici, cette boucle me permet juste de rajouter un espace entre chaque tiret pour davantage de visibilité.
                                ?>
                            </p>
                        </section>

                        <section id='dessin'>
                            <!-- <?php
                            if(isset($_SESSION['chosen_word']) && $_SESSION['lifes'] >= 0) {
                                echo '<img src="pendu' . $_SESSION['lifes'] . '.png">';
                            } elseif(isset($_SESSION['chosen_word']) && $_SESSION['lifes'] < 0) {
                                echo '<img src="pendu0.png">';
                            }
                            ?> -->
        <!-- J'ai 7 photos du pendu, une pour chaque vie, générée par cette boucle. Le elseif me permet de 
        maintenir la dernière image si le joueur continue de cliquer après avoir perdu. -->
                        </section>

                        <section id="history">
                            <h4>Lettres déjà proposées : </h4>
                            <br>
                            <p>
                                <?php 
                                    if($_SESSION['lifes'] >= 0) {
                                        echo $_SESSION['history'];
                                    }
                                ?>
                            </p>
                        </section>
                    </section>
                <form method='get' id='keyboard'>
                    <button type='submit' class='lettre' name='letter' value='A'>A</button>
                    <button type='submit' class='lettre' name='letter' value='B'>B</button>
                    <button type='submit' class='lettre' name='letter' value='C'>C</button>
                    <button type='submit' class='lettre' name='letter' value='D'>D</button>
                    <button type='submit' class='lettre' name='letter' value='E'>E</button>
                    <button type='submit' class='lettre' name='letter' value='F'>F</button>
                    <button type='submit' class='lettre' name='letter' value='G'>G</button>
                    <button type='submit' class='lettre' name='letter' value='H'>H</button>
                    <button type='submit' class='lettre' name='letter' value='I'>I</button>
                    <button type='submit' class='lettre' name='letter' value='J'>J</button>
                    <button type='submit' class='lettre' name='letter' value='K'>K</button>
                    <button type='submit' class='lettre' name='letter' value='L'>L</button>
                    <button type='submit' class='lettre' name='letter' value='M'>M</button>
                    <button type='submit' class='lettre' name='letter' value='N'>N</button>
                    <button type='submit' class='lettre' name='letter' value='O'>O</button>
                    <button type='submit' class='lettre' name='letter' value='P'>P</button>
                    <button type='submit' class='lettre' name='letter' value='Q'>Q</button>
                    <button type='submit' class='lettre' name='letter' value='R'>R</button>
                    <button type='submit' class='lettre' name='letter' value='S'>S</button>
                    <button type='submit' class='lettre' name='letter' value='T'>T</button>
                    <button type='submit' class='lettre' name='letter' value='U'>U</button>
                    <button type='submit' class='lettre' name='letter' value='V'>V</button>
                    <button type='submit' class='lettre' name='letter' value='W'>W</button>
                    <button type='submit' class='lettre' name='letter' value='X'>X</button>
                    <button type='submit' class='lettre' name='letter' value='Y'>Y</button>
                    <button type='submit' class='lettre' name='letter' value='Z'>Z</button>
                </form>
            </section>
        </main>

    <?php include 'footer.php' ?>

</body>
</html>

<?php

// Je voulais regrouper les requetes sur le fichier stats.php mais la requête pour rajouter les stats du user ne 
// s'activait qu'au prochain rafraichissement de page. Je préfère donc la mettre ici pour ne pas prendre de risque.
// Pareil pour les 2 dernières sur les mots, ça me les rajoutait une fois à la fin de la partie, puis une 2eme au rafraichissement


include 'connecSQL.php';
// Stats pour les users:
if(isset($_SESSION['user']) && $_SESSION['lifes'] == 0 && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']) {  
    $request= "UPDATE Users SET parties = parties+1, defaites = defaites+1 WHERE id = '$_SESSION[userID]'";
    $query = $mysqli->query($request);
    }

if(isset($_SESSION['user']) && $_SESSION['lifes'] > 0 && $_SESSION['hidden_word'] == $_SESSION['chosen_word']) {  
    $request= "UPDATE Users SET parties = parties+1, victoires = victoires+1 WHERE id = '$_SESSION[userID]'";
    $query = $mysqli->query($request);
    }

if(isset($_SESSION['chosen_word']) && $_SESSION['hidden_word'] == $_SESSION['chosen_word'] && $_SESSION['lifes'] > 0) {
    $len_word = strlen($_SESSION['chosen_word']);
    if(isset($_SESSION['user'])){
        $request_word = "INSERT INTO Mots(Mot, victoires, defaites, joueur, longueur) 
        VALUES ('$_SESSION[chosen_word]',1,0,'$_SESSION[user]','$len_word')";
        $query_word = $mysqli->query($request_word);
    } else{
        $request_word = "INSERT INTO Mots(Mot, victoires, defaites, joueur, longueur) 
        VALUES ('$_SESSION[chosen_word]',1,0,'Inconnu','$len_word')";
        $query_word = $mysqli->query($request_word);
    }
}
    
if(isset($_SESSION['chosen_word']) && $_SESSION['lifes'] == 0 && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']) {
    $len_word = strlen($_SESSION['chosen_word']);
    if(isset($_SESSION['user'])){
        $request_word  = "INSERT INTO Mots(Mot, victoires, defaites, joueur, longueur) 
        VALUES ('$_SESSION[chosen_word]',0,1,'$_SESSION[user]','$len_word')";
        $query_word = $mysqli->query($request_word);
    } else{
        $request_word  = "INSERT INTO Mots(Mot, victoires, defaites, joueur, longueur) 
        VALUES ('$_SESSION[chosen_word]',0,1,'Inconnu','$len_word')";
        $query_word = $mysqli->query($request_word);
    }
}

    $_SESSION['turn']++;
?>