<?php
session_start();

ini_set("auto_detect_line_endings", true); // per il fine linea su MAC

require_once('utility.php');
require_once('utilitySQL.php');
require_once("dati_connessione.php");
use MieClassi\utility as UT;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // Controllo se il form è stato inviato
    $inviato = UT::richiestaHTTP("inviato");
    $inviato = ($inviato == null || $inviato != 1) ? false : true;

    if ($inviato) {
        $valido = 0;
        // Recupero i dati
        $nome = UT::richiestaHTTP("nome");
        $cognome = UT::richiestaHTTP("cognome");
        $email = UT::richiestaHTTP("email");
        $telefono = UT::richiestaHTTP("telefono");
        $testo = UT::richiestaHTTP("testo");

        $clsErrore = ' class="errore" ';

        //VALIDO I DATI

        // Nome
        if (($nome != "") && UT::controllaRangeStringa($nome, 2, 25)) {
            $clsErroreNome = "";
        } else {
            $valido++;
            $clsErroreNome = $clsErrore;
            $nome = "";
        }

        // Cognome
        if (($cognome != "") && UT::controllaRangeStringa($cognome, 2, 25)) {
            $clsErroreCognome = "";
        } else {
            $valido++;
            $clsErroreCognome = $clsErrore;
            $cognome = "";
        }

        // Email
        if (($email != "") && UT::controllaRangeStringa($email, 10, 100) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clsErroreEmail = "";
        } else {
            $valido++;
            $clsErroreEmail = $clsErrore;
            $email = "";
        }

        // Telefono
        if (($telefono != "") && UT::controllaRangeStringa($telefono, 5, 20) && preg_match('/^\+?[0-9]+$/', $telefono)) {
            $clsErroreTelefono = "";
        } else {
            $valido++;
            $clsErroreTelefono = $clsErrore;
            $telefono = "";
        }

        // Testo
        if (($testo != "") && UT::controllaRangeStringa($testo, 1, 500)) {
            $clsErroreTesto = "";
        } else {
            $valido++;
            $clsErroreTesto = $clsErrore;
            $testo = "";
        }

        $inviato = ($valido == 0) ? true : false;

        // Se i dati sono validi, procedo con l'inserimento nel database
        if ($inviato) {
            // Preparazione della query SQL
            $sql = "INSERT INTO contatti_form (nome, cognome, email, telefono, testo) VALUES (:nome, :cognome, :email, :telefono, :testo)";
            $query = $pdo->prepare($sql);

            // Binding dei parametri
            $query->bindParam(':nome', $nome, PDO::PARAM_STR);
            $query->bindParam(':cognome', $cognome, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $query->bindParam(':testo', $testo, PDO::PARAM_STR);

            // Esecuzione della query e gestione del risultato
            if ($query->execute()) {
                // Dopo l'inserimento con successo salvo il messaggio nella sessione
                $_SESSION['successo']  = '<span class="success-message"><i class="fas fa-check-circle"></i> Dati inseriti correttamente. Grazie per averci contattato!</span>';
                header("Location: contatti.php");
               exit;
               
            } else {
                $messaggioErrore = "Errore durante l'inserimento dei dati. Per favore riprova.";
            }
        } else {
            $messaggioErrore = '<span class="error-message"><i class="fas fa-exclamation-circle"></i> Si è verificato un errore. Per favore riprova.</span>';
        }
    } else {
        // Inizializzo le variabili di input e di errore
        $nome = "";
        $cognome = "";
        $email = "";
        $telefono = "";
        $testo = "";

        $clsErroreNome = "";
        $clsErroreCognome = "";
        $clsErroreEmail = "";
        $clsErroreTelefono = "";
        $clsErroreTesto = "";
    }

    // Controllo se c'è un messaggio di successo nella sessione
    if (isset($_SESSION['successo'])) {
        $messaggioSuccesso = $_SESSION['successo'];
        // Rimuovo il messaggio di successo dalla sessione dopo averlo mostrato
        unset($_SESSION['successo']);
        // Reset dei campi del form
        $nome = $cognome = $email = $telefono = $testo = "";
    }

    // Recupero i dati dal database utilizzando la funzione
    $dati = getDataFromTable($pdo, "SELECT * FROM contatti_nav");
    $datiB = getDataFromTable($pdo, "SELECT * FROM contatti_button");
    $datiC = getDataFromTable($pdo, "SELECT * FROM contatti_contacts");
    $datiD = getDataFromTable($pdo, "SELECT * FROM contatti_footer");

    // Controllo se i dati sono stati recuperati
    if ($dati && $datiB && $datiC && $datiD) {
        
    } else {
        echo "Errore: alcuni dati non sono stati recuperati.";
    }
    


} catch (PDOException $e) {
    echo "<br><br>Errore PDO: " . $e->getMessage();
    echo "<br><br>Si è verificato un errore durante l'elaborazione della richiesta. Riprova più tardi.";
    die();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Immagini/favicon.ico" type="image/x-icon">    
    <link rel="stylesheet" href="./css/style_contatti.min.css" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Contatti</title>
    
</head>
<body>

    <img src="./Immagini/blue_elegante.jpg" alt="BG" width="100%" class="bg">

    <!-- BARRA DI NAVIGAZIONE ------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <header>
        
        <nav>
            <ul class="menu">
                <?php
                    foreach ($dati as $link) {
                        printf('<li><a id="%s" href="%s" title="%s">%s</a></li>',$link["id_css"], $link["href"], $link["title"], $link["testo"]);
                    }
                ?>
            </ul>
        </nav>
    </header>

    <!-- HOME ------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <img src="Immagini/Logo_Luca_2.webp" alt="logo" width="230" class="logo">
    <form action="contatti.php?inviato=1" method="POST" >
   
        <fieldset>
            <div class="testo">
                <h2>Compila il form per richiedere informazioni</h2>
                <h4>Inserisci i tuoi contatti</h4>
            </div>

            <?php
            if (isset($messaggioErrore)) {
                echo '<p>' . $messaggioErrore . '</p>';
            }
            if (isset($messaggioSuccesso)) {
                echo '<p>' . $messaggioSuccesso . '</p>';
            }
            ?>

        <div class="form-group">
            <label for="nome" <?php echo $clsErroreNome; ?>>Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Nome" required maxlength="25" value="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
            <label for="cognome" <?php echo $clsErroreCognome; ?>>Cognome</label>
            <input type="text" id="cognome" name="cognome" placeholder="Cognome" required maxlength="25" value="<?php echo htmlspecialchars($cognome, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>


        <div class="form-group">
            <label for="email" <?php echo $clsErroreEmail; ?>>E-mail</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required minlength="5" maxlength="100"  value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
                <label for="telefono" <?php echo $clsErroreTelefono; ?>>Telefono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Telefono" required  pattern="\+?[0-9\s\-\(\)]+" title="Inserisci un numero di telefono valido" maxlength="25" minlength="5"  value="<?php echo htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
                <label for="text-area" <?php echo $clsErroreTesto; ?>>Testo</label>
                <textarea id="text-area" name="testo" rows="4" cols="50" placeholder="Scrivi un messaggio"  maxlength="500"><?php echo htmlspecialchars($testo, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="a-button">
                <?php
                    foreach ($datiB as $link) {
                        printf('<button  class="%s" type="%s" title="%s"><p id=buttoncont>%s</p></button>', $link["class"], $link["type"],$link["title"], $link["testo"]);
                    }
                ?>
        </div>
        </fieldset>
    </form>

    <!-- FOOTER ------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <footer>
        <div class="container">
            <div class="footer-contenuto">
                <h3 id="contact">Contact</h3>
                <?php
                    foreach ($datiC as $link) {
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
                        foreach ($datiD as $link) {
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
