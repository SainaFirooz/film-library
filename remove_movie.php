<?php
// anslutning till databasen
require_once 'connection.php';

// kontrollerar om id finns för att kunna radera filmens information
if (isset($_GET['id'])) {
    try {
        //  tar bort filmen från databasen, använder prepared statements och bindParam
        $stmt = $db->prepare('DELETE FROM movies WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();

        // Omdirigera till film biblioteket efter radering
        header('Location: library.php?success=Movie removed successfully');
        exit;
    } catch (PDOException $e) { // hanterar databasfel
        header('Location: library.php?error=Database error: ' . $e->getMessage());
        exit;
    }
} else {
    // om inget ID finns omdirigerar tillbaka till biblioteket
    header('Location: library.php');
    exit;
}
?>
