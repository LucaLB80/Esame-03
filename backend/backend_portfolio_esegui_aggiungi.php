<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    // Controllo se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inizializzo un array per raccogliere gli errori
    $errors = [];

    // Recupero i dati
    $id = UT::richiestaHTTP("id");
    $url = UT::richiestaHTTP("url");
    $title = UT::richiestaHTTP("title");
    $file_path = UT::richiestaHTTP("file_path");
    $ext = UT::richiestaHTTP("ext");
    $alt = UT::richiestaHTTP("alt");
    $width = UT::richiestaHTTP("width");
    $class1 = UT::richiestaHTTP("class1");
    $titolo = UT::richiestaHTTP("titolo");
    $class = UT::richiestaHTTP("class");
    $nome = UT::richiestaHTTP("nome");
    $par = UT::richiestaHTTP("par");

    //VALIDO I DATI

    // Id
    if (empty($id) || !UT::controllaRangeStringa($id, 1, 3)) {
        $errors[] = "Il campo 'Id' è obbligatorio e deve essere tra 2 e 3 caratteri.";
    }

    // Url
    if (empty($url) || !UT::controllaRangeStringa($url, 2, 50)) {
        $errors[] = "Il campo 'Url' è obbligatorio e deve essere tra 2 e 50 caratteri.";
    }

    // Title
    if (empty($title) || !UT::controllaRangeStringa($title, 2, 25)) {
        $errors[] = "Il campo 'Title' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }

    // File_path
    if (!empty($file_path) && !UT::controllaRangeStringa($file_path, 2, 40)) {
        $errors[] = "Il campo 'File Path' è obbligatoriodeve essere tra 2 e 40 caratteri.";
    }

    // Ext
    if (!empty($ext) && !UT::controllaRangeStringa($ext, 2, 5)) {
        $errors[] = "Il campo 'Ext' è obbligatorio deve essere tra 2 e 5 caratteri.";
    }

    // Alt
    if (empty($alt) || !UT::controllaRangeStringa($alt, 1, 6)) {
        $errors[] = "Il campo 'Alt' è obbligatorio e deve essere tra 1 e 6 caratteri.";
    }

    // Width
    if (empty($width) || !UT::controllaRangeStringa($width, 1, 10)) {
        $errors[] = "Il campo 'Width' è obbligatorio e deve essere tra 1 e 10 caratteri.";
    }
    // Class1
    if (empty($class1) || !UT::controllaRangeStringa($class1, 2, 25)) {
        $errors[] = "Il campo 'Class1' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }
    // Titolo
    if (empty($titolo) || !UT::controllaRangeStringa($titolo, 2, 25)) {
        $errors[] = "Il campo 'Titolo' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }
    // Class
    if (empty($class) || !UT::controllaRangeStringa($class, 2, 25)) {
        $errors[] = "Il campo 'Class' è obbligatorio e deve essere tra 2 e 25 caratteri.";
    }
    // Nome
    if (empty($nome) || !UT::controllaRangeStringa($nome, 2, 25)) {
        $errors[] = "Il campo 'Nome' è obbligatorio e deve essere tra 2 e 25 caratteri.";
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


    // Preparazione della query SQL per l'inserimento
    $sql = "INSERT INTO portfolio (id, `url`, title, file_path, ext, alt, width, class1, titolo, class, nome, par) 
            VALUES (:id, :url, :title, :file_path, :ext, :alt, :width, :class1, :titolo, :class, :nome, :par)";
    $query = $pdo->prepare($sql);

    // Binding dei parametri
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->bindParam(':url', $url, PDO::PARAM_STR);
    $query->bindParam(':title', $title, PDO::PARAM_STR);
    $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);
    $query->bindParam(':ext', $ext, PDO::PARAM_STR);
    $query->bindParam(':alt', $alt, PDO::PARAM_STR);
    $query->bindParam(':width', $width, PDO::PARAM_STR);
    $query->bindParam(':class1', $class1, PDO::PARAM_STR);
    $query->bindParam(':titolo', $titolo, PDO::PARAM_STR);
    $query->bindParam(':class', $class, PDO::PARAM_STR);
    $query->bindParam(':nome', $nome, PDO::PARAM_STR);
    $query->bindParam(':par', $par, PDO::PARAM_STR);

    $result = $query->execute();
    if ($result) {
        echo '<a id="return_back" href="backend_portfolio.php">Torna a Portfolio</a>';
        echo "<br><strong>Dati inseriti correttamente</strong><br>";
    } else {
        echo '<a id="return_back" href="backend_portfolio.php">Torna a Portfolio</a>';
        echo "<br><strong>Dati non inseriti </strong><br>";
    }
 }
}
#<------------------UPLOAD IMMAGINE----------------------------------------------------------------------------------------------------------------->#
   
    $uploadDir = __DIR__ . '/../Immagini';

    $arrFile = UT::riordinaArrayFILES($_FILES);   

    $prefix = UT::correggiNome($id);
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
            }

            #return_back:hover {
                color: #0056b3;
            }
            .errore {
                color: red;
            }
</style>