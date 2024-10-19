<?php
// Dati di connessione
require_once("../dati_connessione.php");
require_once("backend_include.php");

use MieClassi\utility as UT;

try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inizializzo un array per raccogliere gli errori
    $errors = [];

    // Recupero i dati
    $username = UT::richiestaHTTP("username");
    $password = UT::richiestaHTTP("password");
    $email = UT::richiestaHTTP("email");


    //VALIDO I DATI

    // Username
    if (empty($username) || !UT::controllaRangeStringa($username, 1, 25)) {
        $errors[] = "Il campo 'Username' è obbligatorio e deve essere tra 1 e 25 caratteri.";
    }

    // Password
    if (empty($password) || !UT::controllaRangeStringa($password, 5, 50)) {
        $errors[] = "Il campo 'Password' è obbligatorio e deve essere tra 5 e 50 caratteri.";
    }
    
    if (!empty($password) && UT::controllaRangeStringa($password, 5, 50)) {
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "La password deve contenere almeno una lettera maiuscola.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "La password deve contenere almeno una lettera minuscola.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "La password deve contenere almeno un numero.";
        }
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "La password deve contenere almeno un carattere speciale.";
        }
    }
    

    // Email
    if (empty($email) || !UT::controllaRangeStringa($email, 10, 100) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Il campo 'Email' è obbligatorio e deve essere tra 10 e 100 caratteri.";
    }


    // Errori
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='errore'>" . htmlspecialchars($error) . "</p>";
        }
       
    } else {
        // Hash della password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Preparazione della query SQL per l'inserimento
        $sql = "INSERT INTO utenti (username, password_hash, email) VALUES (:username, :password_hash, :email)";
        $stmt = $pdo->prepare($sql);

        // Binding dei parametri
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':email', $email);

        // Esecuzione della query
        if ($stmt->execute()) {
            echo "Registrazione avvenuta con successo!";
        } else {
            echo "Errore durante la registrazione. Per favore riprova.";
        }
    }
}
} catch (PDOException $e) {
    echo "Errore: " . $e->getMessage();
}
?>

<!-- Form di registrazione -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Utente</title>
    <style>
            body {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

                }
            /* Definizione delle variabili CSS */
            :root {
                --clr-correct: #28a745; /* verde */
                --clr-error: #dc3545;   /* rosso */
            }

            #return_back {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 1.3em;
            position: absolute;
            margin-top: 50px; /* Sposta più in basso */
            margin-left: 50px; /* Sposta più a destra */
            }

            #return_back:hover {
                color: #0056b3;
            }

            h1 {
                    font-size: 4em; 
                    text-align: center; 
                }
            h1::first-letter {
                color: #007bff; 
                font-size: 1.2em; 
            }
            /* Stile per il form di login */
            form {
                width: 300px;
                margin: 100px auto;
                padding: 20px;
                background-color: #f7f7f7;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
            }

            div {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            label {
                font-weight: bold;
                font-size: 1.2em;
                margin: 10px 0px 0px 0px;
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                
            }

            input[type="text"],
            input[type="password"],
            input[type="email"] {
                padding: 10px;
                font-size: 16px;
                border: 2px solid #ccc;
                border-radius: 5px;
                transition: border-color 0.3s;
            }

            input[type="text"]:focus,
            input[type="password"]:focus,
            input[type="email"]:focus {
                border-color: #007BFF;
                outline: none;
            }

            input[type="text"][required]:focus:valid,
            input[type="password"][required]:focus:valid,
            input[type="email"]:focus:valid,
            input[type="text"][required]:valid,
            input[type="password"][required]:valid,
            input[type="email"][required]:valid  {
                border-color: var(--clr-correct);
            }

            input[type="text"][required]:focus:invalid,
            input[type="password"][required]:focus:invalid,
            input[type="email"][required]:focus:invalid,
            input[type="text"][required]:invalid:not(:empty),
            input[type="password"][required]:invalid:not(:empty),
            input[type="email"][required]:invalid:not(:empty) {
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
            .errore {
                color: red;
            }
    </style>
</head>
<body>
    <a id="return_back" href="backend_utenti.php">Torna a Utenti</a>
    <h1>Registrazione Utenti</h1>
    <form action="backend_utenti_registrazione.php" method="post">
        
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri, senza spazi o caratteri speciali">
        </div>
        <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required minlength="4" title="Inserisci solo lettere e numeri, senza spazi o caratteri speciali">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit">Registrati</button>
    </form>
</body>
</html>
