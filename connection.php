<?php
// ansluter till databasen

$host = 'localhost';
$user = 'root';
$password = 'mysql';
$databas = 'movie_library';

try {
    $db = new PDO("mysql:host=$host;dbname=$databas", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage();
    exit;
}
?>

