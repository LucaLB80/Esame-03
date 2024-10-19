<?php 

namespace MieClassi;

/**
 * Questa classe contiene tutti i metodi utili
 * @author Luca Basta
 * @copyright 2024
 * @license LGPL
 * @version 1.0.0
 */

 class utility
 {
    //------------------------------------------------------------
    /**
     * Funzione per controllare se una stringa sta all'interno di un range di lunghezza
     * 
     * @param string $stringa Stringa da controllare
     * @param integer $min Lunghezza minima
     * @param integer $max Lunghezza massima
     * @return boolean
     * 
     */
    public static function controllaRangeStringa($stringa, $min = null, $max = null)
    {
        if ($stringa === null || $stringa === '') {
            return false;
        }
        $n = strlen($stringa);
        if ($min !== null && $n < $min) {
            return false;
        }
        if ($max !== null && $n > $max) {
            return false;
        }
        return true;
    }
    
   //------------------------------------------------------------
   /**
    * Funzione per leggere del testo in un file
    *
    * @param string $file Nome del file
    * @return boolean|string
    *
    */
    public static function leggiTesto($file)
    {
        $rit = false;
        if (!$fp = fopen($file, 'r')) {
            echo "Non posso aprire il file $file<br>";
        } else {
            if (is_readable($file) === false) {
                echo "il file $file non è leggibile<br>";
            } else {
                $rit = fread($fp, filesize($file));               
            }
        }
        fclose($fp);
        return $rit;
    } 
    //------------------------------------------------------------
    /**
     * Funzione per leggere del testo di un file CSV
     *
     * @param string $file Nome del file
     * @return boolean|array
     *
     */
    public static function leggiTestoCSV($file)
    {
        $rit = false;
        $riga = 0;
        if (!$fp = fopen($file, 'r')) {
            echo "Non posso aprire il file $file<br>";
        } else {
            if (is_readable($file) === false) {
                echo "il file $file non è leggibile<br>";
            } else {
                while (($data = fgetcsv($fp, null, ";")) !== false)  {
                    $rit[$riga] = $data;
                    $riga++;
                }             
            }
        }
        fclose($fp);
        return $rit;
    }
   //------------------------------------------------------------
    /**
     * 
     * Funzione per estrarre dal $_POST o dal $_GET la propietà richiesta
     * 
     * @param string Propietà da ricercare
     * @return string|null
     * 
     */
    public static function richiestaHTTP($str)
    {
        $rit = null;
        if ($str !== null) {
            if (isset($_POST[$str])) {
                $rit = $_POST[$str];
            } elseif (isset($_GET[$str])) {
                $rit = $_GET[$str];
        }
    }  
        return $rit;
    }


    public static function creaUtentiModifica($dati,$idSel) { 
                                       
                    $form = '<form action="backend_utenti_esegui_modifica.php?id=' . $idSel . '" method="POST" >' .
                            '<fieldset><legend>Modifica</legend>' .
                                
                            '<label for="username" > Username </label>' .
                            '<input type="text" value="' . $dati["username"] . '"  name="username" placeholder="Username" required pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri, senza spazi o caratteri speciali" />' .

                            '<label for="email" > Email </label>' .
                            '<input type="text" value="' . $dati["email"] . '"  name="email" placeholder="Email" required maxlength="25" />' .
                            
                            '<label for="password_corrente">Password corrente:</label>' .
                            '<input type="password" name="password_corrente" id="password_corrente" >' .
                    
                            
                            '<label for="nuova_password">Nuova password:</label>' .
                            '<input type="password" name="nuova_password" id="nuova_password" >' .

                            '<button type="submit" id="conferma">Conferma</button>' .
                            
                            '</fieldset>' .
                            
                    
                            '</form>';
                        
                            return $form;
                        }


    public static function creaFormModifica($dati, $idSel) {

                $form = '<form action="backend_portfolio_esegui_modifica.php?id=' . $idSel . '" method="POST" enctype="multipart/form-data">' .
                        '<fieldset><legend>Modifica</legend>' .

                        '<label for="nome">Nome</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["nome"]) . '" id="nome" name="nome" placeholder="Nome" required maxlength="25" />' .

                        '<label for="titolo">Titolo</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["titolo"]) . '" id="titolo" name="titolo" placeholder="Titolo" required maxlength="25" />' .

                        '<label for="title">Title</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["title"]) . '" id="title" name="title" placeholder="Title" required maxlength="25" />' .

                        '<label for="file_path">File Path</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["file_path"]) . '" id="file_path" name="file_path" placeholder="File Path" required maxlength="40" />' .

                        '<label for="ext">Ext</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["ext"]) . '" id="ext" name="ext" placeholder="Ext" required maxlength="5" />' .

                        '<label for="alt">Alt</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["alt"]) . '" id="alt" name="alt" placeholder="Alt" required maxlength="6" />' .

                        '<label for="width">Width</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["width"]) . '" id="width" name="width" placeholder="Width" required maxlength="10" />' .

                        '<label for="par">Par</label>' .
                        '<input type="text" value="' . htmlspecialchars($dati["par"]) . '" id="par" name="par" placeholder="Par" required maxlength="1000" />' .

                        '<label id="scegli_file" for="file-01s">Scegli file:</label>' .
                        '<input type="file" id="file-01s" name="file[]" accept="image/*" multiple><br>' .

                        '<button type="submit" id="conferma">Conferma</button>' .

                        '</fieldset>' .
                        '</form>';

    return $form;
    }
                                

 
    public static function creaFormAggiungi($dati) {
                $id = $dati['id'] ?? '';
                $url = $dati['url'] ?? '';
                $title = $dati['title'] ?? '';
                $file_path = $dati['file_path'] ?? '';
                $ext = $dati['ext'] ?? '';
                $alt = $dati['alt'] ?? '';
                $width = $dati['width'] ?? '';
                $class1 = $dati['class1'] ?? '';
                $titolo = $dati['titolo'] ?? '';
                $class = $dati['class'] ?? '';
                $nome = $dati['nome'] ?? '';
                $par = $dati['par'] ?? '';

                $form = '<form action="backend_portfolio_esegui_aggiungi.php" method="POST" enctype="multipart/form-data">'.
                        '<fieldset><legend>Aggiungi Utente</legend>' .

                        '<label for="id" > ID </label>' .
                        '<input type="text" name="id" value="'. $id . '" placeholder="ID" required maxlength="3" />' .

                        '<label for="url" > Url </label>' .
                        '<input type="text" name="url" value="' . $url . '" placeholder="Url" required maxlength="50" />' . 

                        '<label for="title" > title </label>' .
                        '<input type= "text" name= "title" value="' . $title . '" placeholder="title" required maxlength="25" />'.

                        '<label for="file_path" > File Path </label>' .
                        '<input type="text" name="file_path" value="' . $file_path . '" placeholder="File Path" required maxlength="40" />' . 

                        '<label for="ext" > Ext </label>' .
                        '<input type="text" name="ext" value="' . $ext . '" placeholder="Ext" required maxlength="55" />' .

                        '<label for="alt" > Alt </label>' .
                        '<input type="text" name="alt" value="' . $alt . '" placeholder="Alt"  required maxlength="25" />' .

                        '<label for="width" > Width </label>' .
                        '<input type="text" name="width" value="' . $width . '" placeholder="Width"  required maxlength="25" />' . 

                        '<label for="class1" > Class1 </label>' .
                        '<input type="text" name="class1" value="' . $class1 . '" placeholder="Class1" required maxlength="25" />' .

                        '<label for="titolo" > Titolo </label>' .
                        '<input type="text" name="titolo" value="' . $titolo . '" placeholder="Titolo" required maxlength="25" />' .

                        '<label for="class" > class </label>' .
                        '<input type="text" name="class" value="' . $class . '" placeholder="class" required maxlength="25" />' .

                        '<label for="nome" > nome </label>' .
                        '<input type="text" name="nome" value="' . $nome . '" placeholder="nome" required maxlength="25" />' .

                        '<label for="par" > Par </label>' .
                        '<input type="text" name="par" value="' . $par . '" placeholder="Par" required maxlength="1000" />' .

                        '<label for="file-01s">Scegli file:</label>' .
                        '<input type="file" id="file-01s" name="file[]" accept="jpg, jpeg, png, gif, bmp, webp" multiple><br>' .
                    
                        '<button type="submit" id="conferma">Aggiungi</button>' . 

                        '</form>';
    
                return $form;

    }
                    
    public static function visualizzaContatti($dati) {
        
    
        // Crea il contenuto per la visualizzazione dei dati
        $visualizza = '<p> Id: ' . htmlspecialchars($dati["id"]) . '</p><br>' .
                      '<p> Nome: ' . htmlspecialchars($dati["nome"]) . '</p><br>' .
                      '<p> Cognome: ' . htmlspecialchars($dati["cognome"]) . '</p><br>' .
                      '<p> Email: ' . htmlspecialchars($dati["email"]) . '</p><br>' .
                      '<p> Telefono: ' . htmlspecialchars($dati["telefono"]) . '</p><br>' .
                      '<p> Testo: ' . htmlspecialchars($dati["testo"]) . '</p><br>';
    
        return $visualizza;
    }           


    public static function creaLavoro($dati) {
        // Crea l'HTML per l'immagine
        $immagine = '<img src="../Immagini/' . htmlspecialchars($dati["file_path"]). '.' .htmlspecialchars($dati["ext"])  . '" width="' . htmlspecialchars($dati["width"]) . '" class="' . htmlspecialchars($dati["class1"]) . '" alt="' . htmlspecialchars($dati["alt"]) . '">';
    
        // Crea il contenuto per la visualizzazione dei dati
        $visualizza = '<p> Id: ' . htmlspecialchars($dati["id"]) . '</p><br>' .
                      '<p> Url: ' . htmlspecialchars($dati["url"]) . '</p><br>' .
                      '<p> Title: ' . htmlspecialchars($dati["title"]) . '</p><br>' .
                      '<p> File Path: ' . htmlspecialchars($dati["file_path"]) . '</p><br>' .
                      '<p> Ext: ' . htmlspecialchars($dati["ext"]) . '</p><br>' .
                      '<p> Alt: ' . htmlspecialchars($dati["alt"]) . '</p><br>' .
                      '<p> Width: ' . htmlspecialchars($dati["width"]) . '</p><br>' .
                      '<p> Class1: ' . htmlspecialchars($dati["class1"]) . '</p><br>' .
                      '<p> Titolo: ' . htmlspecialchars($dati["titolo"]) . '</p><br>' .
                      '<p> Class: ' . htmlspecialchars($dati["class"]) . '</p><br>' .
                      '<p> Nome: ' . htmlspecialchars($dati["nome"]) . '</p><br>' .
                      '<p> Par: ' . htmlspecialchars($dati["par"]) . '</p><br>' .
                      '<div>' . $immagine . '</div>';
    
        return $visualizza;
    }


    public static function correggiNome($b, $l=5,$o=8,$p=3) {

        // Aggiunge zeri davanti per ottenere una lunghezza totale di N caratteri                              
            $codice = str_pad($b, $l,'0', STR_PAD_LEFT);    

        return $codice;
    }


    public static function estensioneFile($fileName) {

            // Recupero l'estensione del file
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);  
            // Rendi l'estensione minuscola
            $fileExt = strtolower($fileExt); 
            return $fileExt;
    }


    public static function riordinaArrayFILES($arr)  {

            $multiple = [];
            $normale = [];

            // ciclo gli elementi input
            foreach ($arr as $chiave1 => $valore1) {
                if (is_array($valore1["name"])) {
                    // input multiple

                    // ciclo le propietà degli elementi
                    foreach($valore1 as $chiave2 => $valore2) {
                        //negli input multiple per ogni propietà ci sono i valori degli n elementi
                        // for ($i = 0 , $count = count($valore2); $i < $count; $i++) --altro metodo--
                        for ($i = 0 ; $i <count($valore2); $i++) {
                            // per ogni propietà in base al contatore popolo il nuovo array
                            $multiple[$i][$chiave2] = $valore2[$i];
                        }
                    }
                } else {
                    // input normale
                    $normale[] = $valore1;
                }
            }
            $ordinato = array_merge($multiple, $normale);
            return $ordinato;
    }
}
?>

