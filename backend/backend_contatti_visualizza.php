<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    // Aggiorna lo stato a visualizzato
    $sql = "UPDATE contatti_form SET visualizzato = 1 WHERE id=" . (int)$idSel;
    $query = $pdo->prepare($sql);
    $query->execute();
    
    $sql = "SELECT * FROM contatti_form WHERE id=" . (int)$idSel;       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        $riga = $query->fetch(PDO::FETCH_ASSOC);
            

       
    }


?>     
   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <style>
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            background-color: #e9ecef;
            color: #333;
            margin: 0;
            padding: 4%;
        }
        #return_back {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 1.3em;
                
        }

        #return_back:hover {
            color: #0056b3;
        }


        h1 {
            font-size: 4em;
            text-align: center;
        }

        h1::first-letter {

            color: #007bff;
            font-size: 1.2em;

        }
        h1 span.highlight {
            color: #007bff;
            font-size: 1.2em;
        }


        p {
            text-align: center;
            font-size: 1.2em;
            max-width: 600px;
            margin: 10px auto;
            line-height: 1.8;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }



        img {
            display: block;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            
        }


    </style>
</head>
<body>
<a id="return_back" href="backend_contatti.php">Torna a Contatti</a>

<h1>Visualizza <span class="highlight">C</span>ontatti<span class="highlight"> U</span>tente</h1>

<?php
echo UT::visualizzaContatti($riga);
?>

</html>

<?php

} catch (PDOException $e) {
    echo "<br><br>Errore PDO: " . $e->getMessage();
    die();
}

?>