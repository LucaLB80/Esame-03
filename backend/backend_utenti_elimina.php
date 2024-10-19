<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");


$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    // Elimina dati dal database

    $sql = "DELETE FROM utenti WHERE id=:id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id', $idSel, PDO::PARAM_INT);

    if ($query->execute()) {
        echo '<a id="return_back" href="backend_utenti.php">Torna a Utenti</a>';
        echo ("<strong>Dati cancellati correttamente</strong>");
    } else {
        echo '<a id="return_back" href="backend_utenti.php">Torna a Utenti</a>';
        echo ("<strong>Errore durante la cancellazione dei dati</strong>");
    }
        
         
    } catch (PDOException $e) {
        echo "<br><br>Errore PDO: " . ($e->getMessage());
        die();
    }
        
?>
<style>
            body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

            }
            #return_back {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 1.3em;
            position: absolute;
            margin-top: 40px; /* Sposta più in basso */
            }

            #return_back:hover {
                color: #0056b3;
            }
</style>