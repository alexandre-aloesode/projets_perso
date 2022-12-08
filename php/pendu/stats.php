<?php

include 'connecSQL.php';

// Stats pour les parties

// Stats pour les parties jouées, non finies, gagnées, perdues:
if(isset($_SESSION['chosen_word']) && $_SESSION['turn'] == 1) {
    $request= "UPDATE Parties SET jouees = jouees+1";
    $query = $mysqli->query($request);
}

if(isset($_SESSION['chosen_word']) && $_SESSION['lifes'] == 0 && $_SESSION['hidden_word'] !== $_SESSION['chosen_word']) {  
    $request= "UPDATE Parties SET defaites = defaites+1";
    $query = $mysqli->query($request);
    }

if(isset($_SESSION['chosen_word']) && $_SESSION['lifes'] > 0 && $_SESSION['hidden_word'] == $_SESSION['chosen_word']) {  
    $request= "UPDATE Parties SET victoires = victoires+1";
    $query = $mysqli->query($request);
    }

if(isset($_SESSION['chosen_word'])){
    if(isset($_GET['reset']) && $_GET['reset'] == 'reset' && $_SESSION['turn'] > 1 ) {
        $request= "UPDATE Parties SET nonfinies = nonfinies+1";
        $query = $mysqli->query($request);
    }
}

//Stats pour les lettres:
if(isset($_GET['letter']) && isset($_SESSION['lifes']) && $_SESSION['lifes'] > 0){
    if(!str_contains($_SESSION['hidden_word'], $_GET['letter']) && !str_contains($_SESSION['history'], $_GET['letter'])) {
        $request= "UPDATE Lettres SET $_GET[letter] = $_GET[letter]+1 ";
        $query = $mysqli->query($request);
    }
}

?>