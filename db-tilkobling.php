<?php
/* Tilkobling til MySQL-databasen */

error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

if (!$host || !$username || !$database) {
    die("FEIL: Databaseinnstillingene mangler.");
}

try {
    $db = mysqli_connect(
        $host,
        $username,
        $password,
        $database
    );

    mysqli_set_charset($db, "utf8mb4");

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die("FEIL: Kunne ikke koble til databasen. Sjekk serverloggen.");
}
?>