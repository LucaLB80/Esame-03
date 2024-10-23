<?php
ini_set("auto_detect_line_endings", true); // per il fine linea su MAC


require_once('utilitySQL.php');
require_once("dati_connessione.php");



try {
    // Connessione al database
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    // Query per recuperare la singola riga
    $sql = "SELECT * FROM index_home";       
    $query = $pdo->prepare($sql);
    $query->execute();

    // Recupero dei dati
    $dati = $query->fetch(PDO::FETCH_ASSOC);

    // Recupero i dati dal database utilizzando la funzione
    $datiA = getDataFromTable($pdo, "SELECT * FROM index_nav");
    $datiB = getDataFromTable($pdo, "SELECT * FROM index_button");
    $datiC = getDataFromTable($pdo, "SELECT * FROM index_button1");
    $datiD = getDataFromTable($pdo, "SELECT * FROM index_contacts");
    $datiE = getDataFromTable($pdo, "SELECT * FROM index_footer");


    // Controllo se i dati sono stati recuperati
    if ($dati && $datiA && $datiB && $datiC && $datiD && $datiE) {
   
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
    <link rel="stylesheet" href="css/style_index.min.css" type="text/css">
    
    <title><?php echo htmlspecialchars($dati['titolo']); ?></title>
</head>
<body>
<img src="./Immagini/blue_elegante.jpg" alt="BG" width="100%" class="bg">

<!-- BARRA DI NAVIGAZIONE ------------------------------------------------------------------------------------------------------------------------------------------------------------>
<header>
    <nav>
        <ul class="menu">
        <?php
                    foreach ($datiA as $link) {
                        printf('<li><a id="%s" href="%s" title="%s">%s</a></li>',$link["id_css"], $link["href"], $link["title"], $link["testo"]);
                    }
                ?>
        </ul>
    </nav>
    <?php
        foreach ($datiB as $link) {
            printf('<div class="%s"><a href="%s" title="%s">%s</a></div>', $link["class"], $link["href"], $link["title"], $link["testo"]);
        }
    ?>
</header>

<!-- HOME ------------------------------------------------------------------------------------------------------------------------------------------------------------>
<main>
    <img src="Immagini/Logo_Luca_2.webp" alt="logo" width="230" class="logo">
    <div class="presentazione">
        <h4 id="hello"><?php echo htmlspecialchars($dati['hello']); ?></h4>
        <h1 id="hello2"><?php echo htmlspecialchars($dati['hello2']); ?></h1>
    </div>
    <div class="button-1">
        <?php
            foreach ($datiC as $link) {
                printf('<div><a href="%s" title="%s">%s</a></div>', $link["href"], $link["title"], $link["testo"]);
            }
        ?>
    </div>
    <div class="box">        
        <div class="container">
            <h3><?php echo htmlspecialchars($dati['hello3']);?></h3>

            <div class="content-wrapper">
                <img src="Immagini/index.png" alt="Immagine" class="img-home1">           
                <p class="lorem1"><?php echo htmlspecialchars($dati['parag']); ?></p>
            </div>
        </div>
    </div>
</main>
<div class="box2">
    <h3>I Servizi</h3>
    <h4>Web Marketing</h4>
    <p class="lorem2"><?php echo htmlspecialchars($dati['par']); ?></p>
    <hr>
    <h4>Web Design</h4>
    <p class="lorem2"><?php echo htmlspecialchars($dati['par']); ?></p>
    <hr>
    <h4>SEO</h4>
    <p class="lorem2"><?php echo htmlspecialchars($dati['par']); ?></p>
</div>

<!-- FOOTER ------------------------------------------------------------------------------------------------------------------------------------------------------------>
<footer>
    <div class="container">
        <div class="footer-contenuto">
            <h3 id="contact">Contact</h3>
            <?php
                foreach ($datiD as $link) {
                    if ($link["href"] == 'null') {
                        printf('<p>%s</p>', $link["testo"]);
                    } else {
                        printf('<li><a href="%s" title="%s">%s</a></li>', $link["href"], $link["title"], $link["testo"]);
                    }
                }
            ?> 
        </div>
        <div class="footer-contenuto_1">
            <h3 id="list">Quick Links</h3>
            <ul class="list">      
            <?php
                    foreach ($datiE as $link) {
                        printf('<li><a href="%s" title="%s">%s</a></li>', $link["href"], $link["title"], $link["testo"]);
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

