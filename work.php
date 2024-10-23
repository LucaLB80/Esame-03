<?php
ini_set("auto_detect_line_endings", true); // per il fine linea su MAC

require_once('utilitySQL.php');
require_once('utility.php');
require_once("dati_connessione.php");

use MieClassi\utility as UT;


$selezionato = UT::richiestaHTTP("idClente");
// $selezionato = $_GET["idClente"]; invece di usare la funzione in utility potevo usare semplicemente $_GET

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    
    $sql = "SELECT * FROM portfolio";       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        while ($righe = $query->fetch(PDO::FETCH_ASSOC)) {
            $tmp = array(
                "id" => $righe["id"],
                "file_path" => $righe["file_path"],
                "ext" => $righe["ext"],
                "alt" => $righe["alt"],
                "width" => $righe["width"],
                "class1" => $righe["class1"],
                "titolo" => $righe["titolo"],
                "class" => $righe["class"],
                "par" => $righe["par"]
            );
            $dati[] = $tmp;                    
        }
    }

        // Recupero i dati dal database utilizzando la funzione
        $datiA = getDataFromTable($pdo, "SELECT * FROM work_nav");
        $datiB = getDataFromTable($pdo, "SELECT * FROM work_button");
        $datiC = getDataFromTable($pdo, "SELECT * FROM work_contacts");
        $datiD = getDataFromTable($pdo, "SELECT * FROM work_footer");
    
    
        // Controllo se i dati sono stati recuperati
        if ($dati && $datiA && $datiB && $datiC && $datiD) {
        
        } else {
            echo "Errore: alcuni dati non sono stati recuperati.";
        }

} catch (PDOException $e) {
    echo "<br><br>Errore PDO: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" href="Favicon/favicon_16x16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="Favicon/favicon_32x32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="Favicon/favicon_48x48.png" type="image/png" sizes="48x48">
    <link rel="icon" href="Favicon/favicon_64x64.png" type="image/png" sizes="64x64">
    <link rel="icon" href="Favicon/favicon_128x128.png" type="image/png" sizes="128x128">
    <link rel="icon" href="Favicon/favicon_192x192.png" type="image/png" sizes="192x192">
    <link rel="icon" href="Favicon/favicon_256x256.png" type="image/png" sizes="256x256">
    <link rel="icon" href="Favicon/favicon_512x512.png" type="image/png" sizes="512x512">
    <!-- CSS -->    
    <link rel="stylesheet" href="./css/style_work.min.css" type="text/css">
    
    <title>Work in Progress</title>
</head>
<body>

    <img src="./Immagini/blue_elegante.jpg" alt="BG" width="100%" class="bg">

<!--BARRA DI NAVIGAZIONE------------------------------------------------------------------------------------------------------------------------------------------------------------>

    <header>
        <nav>
            <ul class="menu">
               <?php
                    foreach ($datiA as $link) {
                        printf('<li><a id="%s" href="%s" title="%s" >%s</a></li>' ,$link["id_css"], $link["href"], $link["title"], $link["testo"]);
                    }
                ?>
            </ul>
        </nav>     
        <?php
            foreach ($datiB as $link) {
                        printf('<div class="%s"><a  href="%s" title="%s" >%s</a></div>' ,$link["class"], $link["href"], $link["title"], $link["testo"]);
                    }
        ?>
    </header>

<!--HOME---------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

        <main>  
        <img src="Immagini/Logo_Luca_2.webp" alt="logo" width="230" class="logo">
        <?php
            // Funzione per disegnare gli elementi in HTML
            function disegna($link) {
                echo '<div class="container"><h1 ' . htmlspecialchars($link["id"]) . '">' . htmlspecialchars($link["titolo"]) . '</h1>';
                echo '<div class="content-wrapper"><img ' . htmlspecialchars($link["id"]) . '" src="./Immagini/' . htmlspecialchars($link["file_path"]). '.' .htmlspecialchars($link["ext"]) . '" width="' . htmlspecialchars($link["width"]) . '" class="' . htmlspecialchars($link["class1"]) . '" alt="' . htmlspecialchars($link["alt"]) . '">';
                echo '<p ' . htmlspecialchars($link["id"]) . '" class="' . htmlspecialchars($link["class"]) . '">' . htmlspecialchars($link["par"]) . '</p></div></div>';
            }

            // Iterazione sull'array $dati e disegno l'elemento corrispondente
            if (isset($dati)) {
                foreach ($dati as $link) {
                    if ($link["id"] == $selezionato) {
                        disegna($link);
                        break;
                    }
                }
            }
        ?>          
       </main>

<!--FOOTER--------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
        
        <footer>
            <div class="container">
                <div class="footer-contenuto">
                <h3 id="contact">Contact</h3>
                    <?php
                        foreach ($datiC as $link) {
                            if($link["href"] == 'null') {
                                    printf('<p>%s</p>' ,$link["testo"]);
                                } else {
                                    printf('<li><a href="%s" title="%s" >%s</a></li>' ,$link["href"],$link["title"],$link["testo"]);
                                }
                        }
                    ?> 
                </div>
                <div class="footer-contenuto_1">
                    <h3 id="list">Quick Links</h3>
                    <ul class="list">
                    <?php
                        foreach ($datiD as $link) {
                            printf('<li><a href="%s" title="%s" >%s</a></li>' ,$link["href"], $link["title"], $link["testo"]);
                        }
                    ?>   
                    </ul>
                </div>
                <div class="footer-contenuto_2">
                    <img src="Immagini/Logo_Luca_2.webp" alt="logo" width="180" class="logo2">
                </div>
                <a class="privacy_policy" href="privacy_policy.html"><p>Privacy Policy</p></a>
            </div>
            <div class="bottom-bar">
                <p>&copy; 2024. All rights reserved</p>
            </div>
       </footer>
    </body>
    </html>
