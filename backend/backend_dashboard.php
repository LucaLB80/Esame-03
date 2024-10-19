<?php
session_start();
require_once("../dati_connessione.php");

// Verifica se l'utente è loggato
if (!isset($_SESSION['username'])) {
    // Se non è loggato, reindirizza alla pagina di login
    header('Location: backend_login_utenti.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="" type="text/css">
    <style>
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            background-color: #e9ecef;
            color: #333;
            margin: 0;
            padding: 4%;
        }
        h1 {
            font-size: 4em; 
            text-align: center; 
        }
        h1::first-letter {
            color: #007bff; 
            font-size: 1.2em; 
        }

        h1 span.highlight {
            color: #007bff;
            font-size: 1.2em;
        }
        p {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 100px;
        }
        
        a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 1.2em;
        }
        a:hover {
            color: #0056b3;
        }
        .dashboard {
            text-align: center;
            margin-bottom: 40px;
        }
        .logout-button {
            display: block;
            margin: 0px auto;
            padding: 10px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
            width: fit-content;
        }
        .logout-button:hover {
            background-color: #0056b3;
            color: white;
        }
        .wrapper {
            width: 80%;
            margin: 200px auto;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        .contenuto {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }
        .contenuto div {
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
    </style>
</head>
<body>
        <div class="dashboard">
                <h1>Benvenuto,<span class="highlight"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</h1>
                <p>Questa è la tua dashboard personale.</p>        
                
        </div>
        <div class="wrapper">
            <div class="contenuto">
                <div>
                    <a href="backend_portfolio.php">Portfolio</a>
                </div>
                <div>
                     <a href="backend_utenti.php">Utenti</a>
                </div>
                <div>
                     <a href="backend_contatti.php">Contatti</a>
                </div>
            </div>

        </div>
        <!-- Pulsante di logout -->
        <a href="backend_logout.php" class="logout-button">Logout</a>
</body>
</html>
