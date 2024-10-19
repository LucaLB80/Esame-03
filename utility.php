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
//------------------------------------------------------------------
/**
 * Funzione che riempie una stringa con un'altra stringa portando 
 * la prima ad una lunghezza pre-determinata
 * 
 * Spiegazione:
 * str_pad($b, 5, '0', STR_PAD_LEFT):
 * $b: il numero che vuoi correggere (in questo caso, 56).
 * $l oppure $o oppure $p: il numero totale di caratteri che desideri nella stringa risultante.
 * '0': il carattere da usare per riempire (in questo caso, lo zero).
 * STR_PAD_LEFT: specifica che gli zeri devono essere aggiunti a sinistra.
 * 
 */

    public static function correggiNome($b,$l=5,$o=8,$p=3) {
    // Aggiunge zeri davanti per ottenere una lunghezza totale di N caratteri
    $codice = str_pad($b, $l, '0', STR_PAD_LEFT);
    return $codice;
}

/** 
 * $a = 56;
 * echo 'Il numero' . '<br>'. $a . '<br>' . 'diventa numero:' . '<br>' . correggiNome($a);
 */ 




#<----------------FUNZIONE RIORDINA ARRAY FILES------------------------------------------------------------------------------------------------------------------->#
       /*
        * Riordina l'array FILES dell'input multiple per essere usato come
        * nella versione di sopra in modo da avere tutti i dati per il singolo
        * file assieme e poter riutilizzare il ciclo for di prima che volendo
        * potrebbe essere inserito in una funzione
        * 
        * @param array gli passo $_FILES[multiplo]
        * @return array ritorna array ordinato
        */
    public static function riordinaArrayFILES($arr)
       {
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


#<----------------FUNZIONE CHE PRENDE L'ESTENSIONE DI UN FILE------------------------------------------------------------------------------------------------------------------->#
    public static function estensioneFile($fileName) {
        // Recupero l'estensione del file
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);  
        // Rendi l'estensione minuscola
        $fileExt = strtolower($fileExt); 
        return $fileExt;
}
#<----------------FUNZIONE DI PROVA CHE RINOMINARE UN FILE------------------------------------------------------------------------------------------------------------------->#

    public static function rinominaFile($fileVecchio,$fileNuovo) {
    // Verifica se il file esiste
    if (file_exists($fileVecchio)) {
      // Prova a rinominare il file
      if ($fileNuovo >= 100) {                        
          rename($fileVecchio, '00' . $fileNuovo . '.' .'jpg');

      } elseif ($fileNuovo >= 10) {
          rename($fileVecchio, '000' . $fileNuovo . '.' .'jpg');

      } else {
          rename($fileVecchio, '0000' . $fileNuovo . '.' .'jpg');
      }
  
      echo "<br><strong>Il file è stato rinominato con successo.</strong><br>";
  } else {
      echo "<br><strong>Il file $fileVecchio non esiste.</strong><br>";
  }

  }

 //------------------------------------------------------------------
    /**
     * Funzione per scrivere del testo in un file
     * 
     * @param string $file Nome del file
     * @param string $stringa Testo da inserire
     * @param boolean $commenta Scrive a video se l'operazione e'andata a buon fine
     * @return boolean
     * 
     */
    public static function scriviTesto($file, $stringa, $commenta = false)
    {
        $rit = false;
        if (!$fp = fopen($file, 'a')) {
            echo "Non posso aprire il file $file<br>";
        } else {
            if (is_writable($file) === false) {
                echo "il file $file non è scrivibile<br>";
            } else {
                if (!fwrite($fp, $stringa)) {
                    echo "<br>Non posso scrivere il file $file<br>";
                } else {
                    if ($commenta) echo "Operazione completata!<br> Ho scritto:<br>" .  str_repeat ("-",20) . "<br>" , $stringa , "<br>".  str_repeat ("-",20) . "<br>"  , "Nel file " ,  $file  , "<br>"  ;
                    $rit = true;
                }
            }
        }
        fclose($fp);
        return $rit;
    }   
}

define("Commenta", true);


?>