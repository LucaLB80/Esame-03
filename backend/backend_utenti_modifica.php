<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    
    $sql = "SELECT * FROM utenti WHERE id=" . (int)$idSel;       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        $riga = $query->fetch(PDO::FETCH_ASSOC);
                
    }


?>     

   

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Utenti Modifica</title>
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


            form {
                width: 60%;
                margin: 0 auto;
                padding: 40px;
                background-color: #fff;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
                border-radius: 12px;
                transition: box-shadow 0.3s ease;
            }

            form:hover {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }

            fieldset {
                border: none;
                padding: 0;
            }

            legend {
                font-size: 1.5em;
                color: #007bff;
                font-weight: bold;
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-top: 20px;
                font-weight: bold;
            }


            input[type="text"], input[type="file"], input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 1em;
            }

            input[type="file"] {
                    padding: 5px;
                    width: 60%;
                    background-color: #f8f9fa;                    
                    border-radius: 4px;
                    transition: border-color 0.4s ease;
                }

            input[type="file"]:hover {
                border-color: #0056b3;
                border: 1px solid #007bff;
            }

            button[type="submit"] {
                display: block;
                width: 20%;
                padding: 15px;
                margin-top: 20px;
                background-color: #007bff;
                color: white;
                border: none;
                cursor: pointer;
                font-size: 1.2em;
                border-radius: 8px;
                transition: background-color 0.3s;
            }

            button[type="submit"]:hover {
                background-color: #0056b3;
            }
            .preview-container {
            position: absolute;
            display: inline-block;
            margin-top: -8%;
            margin-left: 63%;
            
            }
            .preview-container img {    
                        
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }


            .remove-btn {
                position: absolute;
                top: -10px;
                right: -10px;
                background-color: red;
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                width: 24px;
                height: 24px;
                text-align: center;
            }


    </style>
</head>
<body>
    <a id="return_back" href="backend_utenti.php">Torna a Utenti</a>

    <h1>Modifica</h1>

<?php
echo UT::creaUtentiModifica($riga,$idSel);
?>

</html>

<?php

} catch (PDOException $e) {
    echo "<br><br>Errore PDO: " . $e->getMessage();
    die();
}

?>