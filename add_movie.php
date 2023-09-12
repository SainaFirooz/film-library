<?php
require_once 'connection.php'; // anslutning till databasen

// funktion för att sanera användarinput
function sanitize($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
// funktion för att validera om strängen innehåller ogiltiga tecken
function validString($str)
{
    // bokstäver (A-Z, a-z), siffror (0-9) och mellanslag 
    return preg_match('/^[A-Za-z0-9\s]+$/', $str);
}

// kontrollera om formuläret har skickats in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanera datan som skickats in för title, director, year och category_id
    $title = sanitize($_POST['title']);
    $director = sanitize($_POST['director']);
    $year = (int) $_POST['year'];
    $category_id = (int) $_POST['category_id'];

    // kontrollerar att titel, regissör, år och category är ifyllda i formulären
    if (empty($title) || empty($director) || empty($year) || empty($category_id)) {
        header('Location: index.php?error=Please fill in all the fields');
        exit;
    }
    // validerar titel och regissör för ogiltiga tecken
    if (!validString($title) || !validString($director)) {
        header('Location: index.php?error=Invalid characters in title or director');
        exit;
    }
    // validerar året som numeriskt och inom en giltig range (1900) - dagens datum
    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
        header('Location: index.php?error=Please enter a valid year');
        exit;
    }
    try {
        // med prepared statements och placeholders infogas filmen i tabellen 'movies'
        $statement = $db->prepare('INSERT INTO movies (title, director, year, category_id) VALUES (:title, :director, :year, :category_id)');
        $statement->bindParam(':title', $title, PDO::PARAM_STR); // binder parametern :title till variabeln $title | PDO::PARAM_STR = värdet är string
        $statement->bindParam(':director', $director, PDO::PARAM_STR); // binder parametern :director till variabeln $director
        $statement->bindParam(':year', $year, PDO::PARAM_INT);  // binder parametern :year till variabeln $year | PDO::PARAM_INT = värdet är heltal
        $statement->bindParam(':category_id', $category_id, PDO::PARAM_INT); // binder parametern :category_id till variabeln $category_id 
        $statement->execute();
        header('Location: library.php?success=Film added successfully'); // Omdirigera till  film biblioteket
        exit;
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage()); // databas error skickar omdirigerar till formulär sidan 
        header('Location: index.php?error=An error occurred');
        exit;
    }
} else {
    // om formulär inte har skickats in till databasen omdirigerar till formulär sidan
    header('Location: index.php');
    exit;
}
?>