<?php
session_start();
// Distrugge tutte le sessioni
session_unset();
session_destroy();

// Reindirizza alla pagina di login
header('Location: backend_login_utenti.php');
exit();
?>
