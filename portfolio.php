<?php
ini_set("auto_detect_line_endings", true); // per il fine linea su MAC

require_once('utilitySQL.php');
require_once("dati_connessione.php");





try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    
    $sql = "SELECT * FROM portfolio";       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        while ($righe = $query->fetch(PDO::FETCH_ASSOC)) {
            $tmp = array(
                "id" => $righe["id"],
                "url" => $righe["url"],
                "title" => $righe["title"],
                "file_path" => $righe["file_path"],
                "alt" => $righe["alt"],
                "width" => $righe["width"],
                "class1" => $righe["class1"],
                "titolo" => $righe["titolo"],
                "class" => $righe["class"],
                "nome" => $righe["nome"],
                "par" => $righe["par"]
            );
            $dati[] = $tmp;                    
        }
    }

        // Recupero i dati dal database utilizzando la funzione
        $datiA = getDataFromTable($pdo, "SELECT * FROM portfolio_nav");
        $datiB = getDataFromTable($pdo, "SELECT * FROM portfolio_button");
        $datiC = getDataFromTable($pdo, "SELECT * FROM portfolio_contacts");
        $datiD = getDataFromTable($pdo, "SELECT * FROM portfolio_footer");
    
    
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
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/style_portfolio.min.css" type="text/css">
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

        <h1>I Progetti più recenti</h1>       
        <main>     
        <img src="Immagini/Logo_Luca_2.webp" alt="logo" width="230" class="logo">        
            <div class="container-home"> 
                <?php
                        foreach ($dati as $link) {                    
                        printf('<div "%u"><a href="%s" title="%s" class="%s" >%s</a></div>' ,$link["id"], $link["url"],$link["title"],$link["class"],$link["nome"]);
                    }
                ?>
            </div>
       
        </main>

<!--FOOTER--------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
        
        <footer>
            <div class="container">
                <div class="footer-contenuto">
                    <h3 id="contact">Contact</h3>
                        <!-- <ul class="contact"> -->
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
