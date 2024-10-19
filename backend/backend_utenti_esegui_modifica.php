<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

// Recupera l'ID dell'utente
$idSel = (isset($_GET["id"]) && $_GET["id"] != null && $_GET["id"] != "") ? $_GET["id"] : null;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    // Verifica se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Inizializzo un array per raccogliere gli errori
        $errors = [];

        // Recupero i dati
        $username = UT::richiestaHTTP("username");
        $email = UT::richiestaHTTP("email");


        //VALIDO I DATI

        // Username
        if (empty($username) || !UT::controllaRangeStringa($username, 1, 25)) {
            $errors[] = "Il campo 'Username' è obbligatorio e deve essere tra 1 e 25 caratteri.";
        }

        // Email
        if (empty($email) || !UT::controllaRangeStringa($email, 10, 100) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Il campo 'Email' è obbligatorio e deve essere tra 10 e 100 caratteri.";
        }

        // Inizializza la variabile per l'aggiornamento della password
        $aggiornaPassword = false;

        // Verifica se i campi per la password sono stati compilati
        if (isset($_POST['password_corrente'], $_POST['nuova_password']) && !empty($_POST['password_corrente']) && !empty($_POST['nuova_password'])) {
            $password_corrente = $_POST['password_corrente'];
            $nuova_password = $_POST['nuova_password'];
            // Validazione della nuova password
            if (strlen($nuova_password) < 5 || strlen($nuova_password) > 50) {
                $errors[] = "La nuova password deve essere tra 5 e 50 caratteri.";
            }
            if (!preg_match('/[A-Z]/', $nuova_password)) {
                $errors[] = "La nuova password deve contenere almeno una lettera maiuscola.";
            }
            if (!preg_match('/[a-z]/', $nuova_password)) {
                $errors[] = "La nuova password deve contenere almeno una lettera minuscola.";
            }
            if (!preg_match('/[0-9]/', $nuova_password)) {
                $errors[] = "La nuova password deve contenere almeno un numero.";
            }
            if (!preg_match('/[\W]/', $nuova_password)) {
                $errors[] = "La nuova password deve contenere almeno un carattere speciale.";
            }

            // Se non ci sono errori nelle password, procedi con la verifica della password corrente
            if (empty($errors)) {

            // Recupera l'hash della password corrente dal database
            $sql = "SELECT password_hash FROM utenti WHERE id = :id_utente";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_utente', $idSel, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hash_corrente = $row['password_hash'];

                // Verifica la password corrente
                if (password_verify($password_corrente, $hash_corrente)) {
                    // Hash della nuova password
                    $nuovo_hash_password = password_hash($nuova_password, PASSWORD_DEFAULT);
                    $aggiornaPassword = true;
                } else {
                    echo "<p>La password corrente non è corretta.</p>";
                    exit;
                }
            } else {
                echo "<p>Utente non trovato.</p>";
                
            }
        }
    
            // Se ci sono errori, visualizzali e interrompi l'esecuzione
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='errore'>" . htmlspecialchars($error) . "</p>";
                }
                echo '<a id="return_back" href="backend_utenti.php">Torna a Utenti</a>';
                
            }

            // Prepara la query di aggiornamento
            if ($aggiornaPassword) {
                $sql = "UPDATE utenti SET username=:username, email=:email, password_hash=:password_hash WHERE id=:id";
                $query = $pdo->prepare($sql);
                $query->bindParam(":password_hash", $nuovo_hash_password, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE utenti SET username=:username, email=:email WHERE id=:id";
                $query = $pdo->prepare($sql);
            }

            $query->bindParam(":username", $username, PDO::PARAM_STR);
            $query->bindParam(":email", $email, PDO::PARAM_STR);
            $query->bindParam(":id", $idSel, PDO::PARAM_INT);

            $result = $query->execute();

            if ($result) {
                echo '<a id="return_back" href="backend_utenti.php">Torna a Utenti</a>';
                echo "<br><strong>Dati modificati correttamente</strong><br>";
            } else {
                echo '<a id="return_back" href="backend_utenti.php">Torna a Utenti</a>';
                echo "<br><strong>Dati non modificati </strong><br>";
                exit;
            }
     }
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
    margin-top: 40px; /* Sposta più in basso */
    }
    #return_back:hover {
        color: #0056b3;
    }
    .errore {
                color: red;
            }
</style>
