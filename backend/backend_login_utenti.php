<?php
// Dati di connessione
require_once("../dati_connessione.php");

session_start();

try {
    $pdo =  new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se il form di login è stato inviato
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Preparazione della query SQL per selezionare l'utente
        $sql = "SELECT * FROM utenti WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Verifica se l'utente esiste
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($utente && password_verify($password, $utente['password_hash'])) {
            // Login avvenuto con successo
            $_SESSION['username'] = $utente['username'];
            header("Location: backend_dashboard.php"); // Reindirizza alla dashboard
            exit();
        } else {
            // Login fallito
            echo "Username o password errati. Per favore riprova.";
        }
    }
} catch (PDOException $e) {
    echo "Errore: " . $e->getMessage();
}
?>

<!-- Form di login -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Utenti</title>
    <style>
        body{
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }
            /* Definizione delle variabili CSS */
            :root {
                --clr-correct: #28a745; /* verde */
                --clr-error: #dc3545;   /* rosso */
            }

            /* Stile per il form di login */
            form {
                width: 300px;
                margin: 100px auto;
                padding: 20px;
                background-color: #f7f7f7;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            div {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            label {
                font-weight: bold;
                font-size: 14px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 10px;
                font-size: 16px;
                border: 2px solid #ccc;
                border-radius: 5px;
                transition: border-color 0.3s;
            }

            input[type="text"]:focus,
            input[type="password"]:focus {
                border-color: #007BFF;
                outline: none;
            }

            input[type="text"][required]:focus:valid,
            input[type="password"][required]:focus:valid,
            input[type="text"][required]:valid,
            input[type="password"][required]:valid {
                border-color: var(--clr-correct);
            }

            input[type="text"][required]:focus:invalid,
            input[type="password"][required]:focus:invalid,
            input[type="text"][required]:invalid:not(:empty),
            input[type="password"][required]:invalid:not(:empty) {
                border-color: var(--clr-error);
            }


            button[type="submit"] {
                padding: 10px;
                font-size: 16px;
                width: 40%;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.2s;
                margin: 20px auto;
                display: block;
            }

            button[type="submit"]:hover {
                background-color: #0056b3;
            }

            button[type="submit"]:active {
                transform: scale(0.98);
            }
            h1 {
            font-size: 3em; 
            text-align: center; 
            margin-bottom: 25%;
            }
            h1::first-letter {
                color: #007bff; 
                font-size: 1.2em; 
            }

            h1 span.highlight {
                color: #007bff;
                font-size: 1.2em;
            }
    </style>
</head>
<body>
    <form action="backend_login_utenti.php" method="post">
        <h1>Login <span class="highlight">U</span>tenti</h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri, senza spazi o caratteri speciali">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required  title="Inserisci lettere, numeri e caratteri speciali">

        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
