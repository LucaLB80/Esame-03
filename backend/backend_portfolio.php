<?php
// Includo i dati di connessione
require_once("../dati_connessione.php");


try {
    $pdo = new PDO("mysql:host=" . INDIRIZZO . ";dbname=" . DB, UTENTE, PASSWORD);

    
    $sql = "SELECT id, titolo, nome FROM portfolio";       
    $query = $pdo->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
        while ($righe = $query->fetch(PDO::FETCH_ASSOC)) {
            $tmp = array(
                "id" => $righe["id"],
                "titolo" => $righe["titolo"],
                "nome" => $righe["nome"]

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
            <title>Portfolio</title>
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
                }
                h1::first-letter {
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
                caption {
                    caption-side: top;
                    font-size: 1.5em;
                    margin: 40px;
                    margin-bottom: 100px;
                    font-weight: bold;
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

                <h1>Portfolio</h1>                  
                              
                <table style="width: 80%;">

                    <caption>I Miei Lavori</caption>

                    <thead>
                        <th>Id</th>
                        <th>Titolo</th>
                        <th>Nome</th>                       
                        <th>Link Modifica</th>
                        <th>Link Elimina</th>
                        <th>Link Visualizza</th>                        
                    </thead>

                    <tbody>                                                                           
                   
                        <?php                       
                            foreach ($dati as $link ) {                                        
                                printf('<tr><td> %s </td>                                                                                                                                                     
                                            <td> %s </td>
                                            <td> %s </td> 
                                            <td style="width: 80px;"><a href="backend_portfolio_modifica.php?id=%s">Modifica</a>
                                            </td><td style="width: 80px;"><a href="backend_portfolio_elimina.php?id=%s">Elimina</a>
                                            </td><td style="width: 80px;"><a href="backend_portfolio_visualizza.php?id=%s">Visualizza</a>
                                            </td>
                                       </tr>' ,$link["id"],
                                               $link["titolo"],
                                               $link["nome"],
                                               $link["id"],
                                               $link["id"],
                                               $link["id"]);
                            }                         
                        ?>     
                    </tbody>
                </table>    
                <form action="backend_portfolio_aggiungi.php">
                        <input type="submit" value="Aggiungi Utente" />
                </form>
        </body>
        </html>




        
    
       
    