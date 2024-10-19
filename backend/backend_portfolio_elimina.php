<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");


$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;



try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

 // Ottengo il percorso dell'immagine dal database
    $sql = "SELECT file_path, ext FROM portfolio WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id', $idSel, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['file_path'])) {
        
        $fileName = $result['file_path'] . '.' . $result['ext']; // $fileName è impostato come il valore del percorso del file
        $directory = __DIR__ . '/../Immagini'; //$directory è impostata come la directory in cui cercare il file nel server
        $deleted = false; // variabile booleana per tenere traccia se il file è stato eliminato.
        
            $filePath = $directory . '/' . $fileName ;
            if (file_exists($filePath)) {
                // Prova a eliminare l'immagine dal server
                if (unlink($filePath)) {
                    $deleted = true;  /**
                                       * se $deleted è true, viene visualizzato un messaggio di conferma che l'immagine
                                       * è stata eliminata. Altrimenti, viene visualizzato un messaggio che indica che l'immagine non esiste.
                                       */
                } else {
                    echo "<strong>Errore durante l'eliminazione dell'immagine dal server.</strong><br>";
                }
                
            }
        
        


        if ($deleted) {
            echo "<strong>Immagine cancellata correttamente dal server.</strong><br>";
        } else {
            echo "<strong>L'immagine non esiste sul server.</strong><br>";
        }
    }


    // Elimina dati dal database

    $sql = "DELETE FROM portfolio WHERE id=:id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id', $idSel, PDO::PARAM_INT);
    $query->execute();

    echo "<strong>Dati cancellati correttamente</strong>";
    echo '<a id="return_back" href="backend_portfolio.php">Torna a Portfolio</a>';
        
         
    } catch (PDOException $e) {
        echo "<br><br>Errore PDO: " . $e->getMessage();
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
            margin-top: 300px; /* Sposta più in basso */
            margin-left: -198px;
            }

            #return_back:hover {
                color: #0056b3;
            }
</style>