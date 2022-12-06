<?php 

include 'header.php';

echo '<br>';

include 'connecSQL.php';

$request_admin = $mysqli->query('select * from Users');
$result_admin = $request_admin->fetch_array(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="pendu.css" rel="stylesheet">
    <title>Page Admin</title>
</head>
<body>


<html>
<table border="3">
        <thead>
            <?php
                foreach($result_admin = $request_admin->fetch_array(MYSQLI_ASSOC) as $key => $value){
                    echo '<th>' . $key . '</th>';
                }
            ?>
        </thead>
        <tbody>
            <?php
            while($result_admin !=null){
                echo '<tr>';
                foreach($result_admin as $value){
                    echo '<td>' . $value . '</td>';
                }
                $result_admin = $request_admin->fetch_array(MYSQLI_ASSOC);
                echo '</tr>';
            }
            ?>
        </tbody>
</body>
</html>