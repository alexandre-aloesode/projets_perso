<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="pendu.css" rel="stylesheet">
    <title>Pendu PHP</title>
</head>
<body>

<header>
    <p> Ma version du Pendu </p>
    <p>Alexandre ALOESODE</p>
</header>
<section id ='Pendu'>
    <?php

        session_start();
        if(isset($_SESSION['chosen_word'])){
            if(isset($_GET['reset']) && $_GET['reset'] == 'reset'){
                session_destroy();
                header('Location: index.php');
            }
        }

        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
	    $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        
        $dico = file('dico_france.txt');
        $dico_sans_espaces= array_map('trim', $dico);

        $wCount = count($dico_sans_espaces);

    
        if(!isset($_SESSION['chosen_word'])){
            $_SESSION['turn'] = 0;
            $_SESSION['chosen_word'] = $dico_sans_espaces[rand(0, $wCount -1)];
            $_SESSION['chosen_word'] = (utf8_decode($_SESSION['chosen_word']));
            var_dump($_SESSION['chosen_word']);
            $_SESSION['chosen_word'] = str_replace($search, $replace, $_SESSION['chosen_word']);
            $_SESSION['chosen_word'] = strtoupper($_SESSION['chosen_word']);
            $_SESSION['hidden_word'] = '';
            for($x = 0; $x < strlen($_SESSION['chosen_word']); $x++){
                $_SESSION['hidden_word'] = $_SESSION['hidden_word'] . '-';
            }
            $_SESSION['lifes'] = 7;
            $_SESSION['history'] = '';
        }

        
        if(isset($_GET['letter'])){

            if(str_contains($_SESSION['chosen_word'], $_GET['letter'])){
                for($i = 0; $i < strlen($_SESSION['chosen_word']); $i++){
                    if($_SESSION['chosen_word'][$i] == $_GET['letter']){
                        $_SESSION['hidden_word'][$i] = $_GET['letter'];
                    }
                }
            } else {
                $_SESSION['lifes'] --;
                $_SESSION['history'] = $_SESSION['history'] . $_GET['letter'] . '-';
            }
        }
    ?>
    <section id='consignes'>
        <h2>
        <?php
            if($_SESSION['turn'] == 0){
                echo 'Bonjour ! Tu as un mot de ' . strlen($_SESSION['chosen_word']). ' lettres à trouver';
            };
            if($_SESSION['lifes'] == 0 && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']){
                echo 'Perdu ! Le mot à trouver était ...' . $_SESSION['chosen_word'];
            }
            if(isset($_GET['letter']) && $_SESSION['lifes'] > 0){
                if($_SESSION['hidden_word'] == $_SESSION['chosen_word']){
                echo 'Tu as trouvé le mot, Bravo !';
                } elseif(str_contains($_SESSION['chosen_word'], $_GET['letter'])
                && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']){
                    for($i = 0; $i < strlen($_SESSION['chosen_word']); $i++){
                        if($_SESSION['chosen_word'][$i] == $_GET['letter']){
                            echo 'Bien vu !';
                            break;
                        }
                    }
                } else {
                    echo 'Raté ! Essaie encore';
                }
            }
        ?>
        </h2>
    </section>

    <section id='aide'>

        <section id="hidden_word">
            <h4>Mot à trouver :</h4>
            <p>
                <?php
                for($i = 0; isset($_SESSION['hidden_word'][$i]); $i++){
                    echo $_SESSION['hidden_word'][$i] . ' ';
                }
                ?>
            </p>
        </section>

        <section id='dessin'>
     
        </section>

        <section id="history">
            <h4>Lettres déjà proposées : </h4>
            <p>
                <?php echo $_SESSION['history']?>
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

    <section id='lifes'>
        <h3>Vies restantes : 
        <?php echo $_SESSION['lifes']; ?>
        </h3>
        <h3>
         <?php 
         echo $_SESSION['chosen_word'];
         $_SESSION['turn'] ++;
         ?>
         </h3>
    </section>


<section id='replay'>
    <form method ='get' id='reset'>
        <button type='submit' name='reset' id='reset_button' value='reset'>Rejouer</button>
    </form>

</section>
</section>
</body>
</html>