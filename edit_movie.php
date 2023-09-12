<?php

require_once 'connection.php'; // anslutning till databasen

function sanitize($input) // funktion för att sanera användarinput
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function validString($str) // validera om strängen innehåller ogiltiga tecken
{
    // bokstäver (A-Z, a-z), siffror (0-9) och mellanslag
    return preg_match('/^[A-Za-z0-9\s]+$/', $str);
}

// kontrollerar om id finns för att kunna redigera filmens information
if (isset($_GET['id'])) {
    try {
        // hämtar film detaljer från databasen baserat på det angivna id
        $statement = $db->prepare('SELECT * FROM movies WHERE id = :id');
        $statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT); // Parameter bound securely
        $statement->execute();
        $movie = $statement->fetch(PDO::FETCH_ASSOC);


        if ($statement->rowCount() === 0) {  // kontrollera om filmen finns i databasen
            header('Location: library.php?error=Movie not found'); // Om filmen inte finns, omdirigera tillbaka till biblioteket
            exit;
        }
    } catch (PDOException $e) {   // hanterar databasfel
        error_log('Database error: ' . $e->getMessage());
        header('Location: library.php?error=An error occurred');
        exit;
    }
} else {
     // om inget ID finns omdirigerar tillbaka till biblioteket
    header('Location: library.php');
    exit;
}

// kontrollerar om formuläret har skickats in för att uppdatera filmens detaljer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanera och validera datan
    $title = sanitize($_POST['title']);
    $director = sanitize($_POST['director']);
    $year = (int) $_POST['year'];
    $category_id = (int) $_POST['category_id'];
    // kontrollerar att titel, regissör, år och category är ifyllda i formulären
    if (empty($title) || empty($director) || empty($year) || empty($category_id)) {
        header('Location: edit_movie.php?id=' . $_GET['id'] . '&error=Please fill in all the fields');
        exit;
    }
    // validerar året som numeriskt och inom en giltig range (1900) - dagens datum
    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
        header('Location: edit_movie.php?id=' . $_GET['id'] . '&error=Please enter a valid year');
        exit;
    }
    // validerar titel och regissör för ogiltiga tecken
    if (!validString($title) || !validString($director)) {
        header('Location: index.php?error=Invalid characters in title or director');
        exit;
    }
    // kontrollerar om category_id finns i tabellen 'categories'
    try {
        $statementCategory = $db->prepare('SELECT COUNT(*) FROM categories WHERE id = :category_id');
        $statementCategory->bindParam(':category_id', $category_id, PDO::PARAM_INT); 
        $statementCategory->execute();
        $categorySelected = $statementCategory->fetchColumn();
 
        if ($categorySelected === 0) {  // Om inga rader hittas i tabellen 'categories' som matchar vår category_id
            header('Location: edit_movie.php?id=' . $_GET['id'] . '&error=Invalid category selected'); 
            exit; 
        }
    } catch (PDOException $e) { // hanterar databasfel
        error_log('Database error: ' . $e->getMessage()); 
        header('Location: edit_movie.php?id=' . $_GET['id'] . '&error=An error occurred');
        exit;
    }
    // uppdaterar film detaljerna i databasen med prepared statements och bindParam
    try {
       $statementUpdate = $db->prepare('UPDATE movies SET title = :title, director = :director, year = :year, category_id = :category_id WHERE id = :id');
       $statementUpdate->bindParam(':title', $title, PDO::PARAM_STR);  // binder parametern :title till variabeln $title
       $statementUpdate->bindParam(':director', $director, PDO::PARAM_STR); // binder parametern :director till variabeln $director
       $statementUpdate->bindParam(':year', $year, PDO::PARAM_INT); // binder parametern :year till variabeln $year
       $statementUpdate->bindParam(':category_id', $category_id, PDO::PARAM_INT); // binder parametern :category_id till variabeln $category_id
       $statementUpdate->bindParam(':id', $_GET['id'], PDO::PARAM_INT);  // binder parametern :id till uppdaterade filmens id
       $statementUpdate->execute();

        //  Omdirigera till film biblioteket efter uppdatering 
        header('Location: library.php?success=Movie updated successfully');
        exit;
    } catch (PDOException $e) { // hanterar databasfel
        error_log('Database error: ' . $e->getMessage());
        header('Location: edit_movie.php?id=' . $_GET['id'] . '&error=An error occurred');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,
    initial-scale=1.0">
    <title>Movies</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
    <a href="index.php" class="logo">Movie library</a>
      
      <nav class="navbar">
        <a href="index.php">Add movies</a>
        <a href="library.php">Your Library</a>
      </nav>
  </header>

    <main class="content">
    
        <form action="edit_movie.php?id=<?php echo $_GET['id']; ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $movie['title']; ?>" required><br>

            <label for="director">Director:</label>
            <input type="text" id="director" name="director" value="<?php echo $movie['director']; ?>" required><br>

            <label for="year">Year of Production:</label>
            <input type="number" id="year" name="year" value="<?php echo $movie['year']; ?>" required><br>

            <label>Category:</label>
            
            <div class="category-row">
              <input type="radio" id="thriller" name="category_id" value="12">
              <label for="thriller">Thriller</label>

              <input type="radio" id="romantic" name="category_id" value="13">
              <label for="romantic">Romantic</label>
            </div>

            <div class="category-row">
             <input type="radio" id="swedish" name="category_id" value="18">
             <label for="swedish">Swedish</label>

             <input type="radio" id="animated" name="category_id" value="15">
             <label for="animated">Animated</label>
            </div>

            <div class="category-row">
             <input type="radio" id="comedy" name="category_id" value="16">
             <label for="comedy">Comedy</label>
             <input type="radio" id="drama" name="category_id" value="17">
             <label for="comedy">Drama</label>
             </div>
            <br>
            <input type="submit" value="Save Changes">
        </form>
    </main>
  <footer class="footer">
    <a href="https://www.flaticon.com/free-icons/cinema-icons" title="cinema icons icons">Cinema icons icons created by Freepik - Flaticon</a>
  </footer>
</body>
</html>
