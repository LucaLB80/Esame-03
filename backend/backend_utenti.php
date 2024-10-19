<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");


try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    
    $sql = "SELECT id, username, email FROM utenti";       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        while ($righe = $query->fetch(PDO::FETCH_ASSOC)) {
            $tmp = array(
                "id" => $righe["id"],
                "username" => $righe["username"],
                "email" => $righe["email"]
                
            );
            $dati[] = $tmp;                    
        }
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
            <link rel="stylesheet" href="">
            <title>Utenti</title>
            <style>

                body {
                    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                    background-color: #e9ecef;
                    color: #333;
                    margin: 0;
                    padding: 4%;
                }
                #return_back {
                    color: #007bff;
                    text-decoration: none;
                    transition: color 0.3s;
                    font-size: 1.3em;
                        
                }

                #return_back:hover {
                    color: #0056b3;
                }
                h1 {
                    font-size: 4em; 
                    text-align: center; 
                    margin-bottom: 200px;
                }
                h1::first-letter {
                    color: #007bff; 
                    font-size: 1.2em; 
                }
                h1 span.highlight {
                    color: #007bff;
                    font-size: 1.2em;
                }
                table {
                    width: 80%;        
                    margin: 0px auto;
                    border-collapse: collapse;
                    background-color: #fff;
                    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
                    
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: center;
                }
                th {
                    background-color: #007bff;
                    color: white;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                tr:hover {
                    background-color: #ddd;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }

                input[type="submit"] {
                    display: block;
                    margin: 80px auto;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                    border-radius: 8px;
                    transition: background-color 0.3s;
                }
                input[type="submit"]:hover {
                    background-color: #0056b3;
                }
                a {
                    color: #007bff;
                    text-decoration: none;
                    transition: color 0.3s;
                }
                a:hover {
                    color: #0056b3;
                }

            </style>
        </head>
        <body>
                <a id="return_back" href="backend_dashboard.php">Torna alla Dashboard</a>

                <h1>Utenti<span class="highlight"> R</span>egistrati</h1>                  
                              
                <table style="width: 80%;">


                    <thead>
                        <th>Id</th>
                        <th>Username</th> 
                        <th>Email</th>                      
                        <th>Link Modifica</th>
                        <th>Link Elimina</th>                       
                    </thead>

                    <tbody>                                                                           
                   
                        <?php                       
                            foreach ($dati as $link ) {                                        
                                printf('<tr><td> %s </td>                                                                                                                                                     
                                            <td> %s </td>
                                            <td> %s </td> 
                                            <td style="width: 80px;"><a href="backend_utenti_modifica.php?id=%s">Modifica</a>
                                            </td><td style="width: 80px;"><a href="backend_utenti_elimina.php?id=%s">Elimina</a>
                                            </td>
                                       </tr>' ,$link["id"],
                                               $link["username"],
                                               $link["email"],
                                               $link["id"],
                                               $link["id"]);
                            }                         
                        ?>     
                    </tbody>
                </table>    
                <form action="backend_utenti_registrazione.php">
                        <input type="submit" value="Aggiungi Utente" />
                </form>
        </body>
        </html>




        
    
       
    