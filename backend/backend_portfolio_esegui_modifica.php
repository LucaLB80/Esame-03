<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;



try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

// Controllo se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inizializzo un array per raccogliere gli errori
    $errors = [];

    // Recupero i dati
    $nome = UT::richiestaHTTP("nome");
    $titolo = UT::richiestaHTTP("titolo");
    $title = UT::richiestaHTTP("title");
    $file_path = UT::richiestaHTTP("file_path");
    $ext = UT::richiestaHTTP("ext");
    $alt = UT::richiestaHTTP("alt");
    $width = UT::richiestaHTTP("width");
    $par = UT::richiestaHTTP("par");

    //VALIDO I DATI

    // Nome
    if (empty($nome) || !UT::controllaRangeStringa($nome, 2, 25)) {
        $errors[] = "Il campo 'Nome' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }

    // Titolo
    if (empty($titolo) || !UT::controllaRangeStringa($titolo, 2, 25)) {
        $errors[] = "Il campo 'Titolo' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }

    // Title
    if (empty($title) || !UT::controllaRangeStringa($title, 2, 25)) {
        $errors[] = "Il campo 'Title' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }

    // File_path
    if (!empty($file_path) && !UT::controllaRangeStringa($file_path, 2, 40)) {
        $errors[] = "Il campo 'File Path' deve essere tra 2 e 40 caratteri.";
    }

    // Ext
    if (!empty($ext) && !UT::controllaRangeStringa($ext, 2, 5)) {
        $errors[] = "Il campo 'Ext' deve essere tra 2 e 5 caratteri.";
    }

    // Alt
    if (empty($alt) || !UT::controllaRangeStringa($alt, 1, 6)) {
        $errors[] = "Il campo 'Alt' è obbligatorio e deve essere tra 1 e 6 caratteri.";
    }

    // Width
    if (empty($width) || !UT::controllaRangeStringa($width <= 0)) {
        $errors[] = "Il campo 'Width' è obbligatorio e deve essere un numero positivo.";
    }

    // Par
    if (empty($par) || !UT::controllaRangeStringa($par, 2, 1000)) {
        $errors[] = "Il campo 'Par' è obbligatorio e deve essere tra 2 e 1000 caratteri.";
    }

    // Errori
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='errore'>" . htmlspecialchars($error) . "</p>";
        }
       
    } else {
        // Preparazione della query SQL
        $sql = "UPDATE portfolio SET title = :title, file_path = :file_path, ext = :ext, alt = :alt, width = :width, titolo = :titolo, nome = :nome, par = :par WHERE id = :id";
        $query = $pdo->prepare($sql);

        // Binding dei parametri
        $query->bindParam(":title", $title, PDO::PARAM_STR);
        $query->bindParam(":file_path", $file_path, PDO::PARAM_STR);
        $query->bindParam(":ext", $ext, PDO::PARAM_STR);
        $query->bindParam(":alt", $alt, PDO::PARAM_STR);
        $query->bindParam(":width", $width, PDO::PARAM_STR);
        $query->bindParam(":titolo", $titolo, PDO::PARAM_STR);
        $query->bindParam(":nome", $nome, PDO::PARAM_STR);
        $query->bindParam(":par", $par, PDO::PARAM_STR);
        $query->bindParam(":id", $idSel, PDO::PARAM_INT);

        $result = $query->execute();
        if ($result) {
            echo '<a id="return_back" href="backend_portfolio.php">Torna a Portfolio</a>';
            echo "<br><strong>Dati modificati correttamente</strong><br>";
        } else {
            echo '<a id="return_back" href="backend_portfolio.php">Torna a Portfolio</a>';
            echo "<br><strong>Errore nell'aggiornamento dei dati</strong><br>";
        }
    }
}

#<------------------UPLOAD IMMAGINE----------------------------------------------------------------------------------------------------------------->#


        if (isset($_FILES) && count($_FILES) > 0 && isset($_GET["id"]) && $_GET["id"] === $idSel) {
            

            $uploadDir = __DIR__ . '/../Immagini';

            $arrFile = UT::riordinaArrayFILES($_FILES);   

            $prefix = UT::correggiNome($idSel);
            // print_r($_FILES);

            // Conta i file esistenti con lo stesso prefisso
            $filesInDir = glob($uploadDir . DIRECTORY_SEPARATOR . $prefix . '_*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            $contaFile = count($filesInDir) + 1; // Inizia il contatore dopo l'ultimo file esistente

            foreach ($arrFile as $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $fileName = basename($file['name']); 
                    $newFileName =  $prefix . '_' . $contaFile . '.' . UT::estensioneFile($fileName);
                    $contaFile++; // Incremento il contatore
                    echo "<br><strong>Salvo il file $fileName in $uploadDir</strong><br>";
                    move_uploaded_file($file['tmp_name'], $uploadDir . DIRECTORY_SEPARATOR . $newFileName);
                }
            }
            // Svuota l'array $_FILES
            unset($_FILES);
        }
              
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
            margin-top: 300px; 
            }

            #return_back:hover {
                color: #0056b3;
            }
            .errore {
                color: red;
            }
</style>