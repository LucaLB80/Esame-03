<?php
 // Funzione per recuperare i dati da una tabella
 function getDataFromTable($pdo, $sql, $params = []) {
    try {
        $query = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $query->bindValue($key, $value);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore nella query: " . $e->getMessage());
        return [];
    }
}
?>