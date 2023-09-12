<?php
// anslutning till databasen
require_once 'connection.php';
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
        <a href="library.php">Your movies</a>
      </nav>

  </header>
  <main class="content">
        <?php
        try {
          /*skapar en PDO query för att hämta film informationen från databasen 
            Jag hämtar information från movies tabellen och med JOIN anslutar
            vi movies tabellen till categories tabellen */
          $statement = $db->query('SELECT movies.id, movies.title, movies.director, movies.year, categories.name AS category
                              FROM movies
                              JOIN categories ON movies.category_id = categories.id
                              ORDER BY movies.id DESC'); // gör att den senaste filmen man sparar visas först i biblioteket
            
            if ($statement->rowCount() > 0) {  // Kontrollera om det finns några filmer i biblioteket
                echo '<ul>'; // Om det finns filmer i biblioteket skrevs det ut som en lista 
                while ($id = $statement->fetch(PDO::FETCH_ASSOC)) { // Iterera genom varje rad
                    echo '<li class="movie-item">'; 
                    echo '<strong>Title:</strong> <span>' . $id['title'] . '</span><br>'; // skriver ut titeln 
                    echo '<strong>Director:</strong> <span>' . $id['director'] . '</span><br>'; // skriver ut regissör
                    echo '<strong>Year:</strong> <span>' . $id['year'] . '</span><br>'; // skriver ut år
                    echo '<strong>Category:</strong> <span>' . $id['category'] . '</span>'; // skriver ut category
                    echo '<br>';
                    // Skapar en länk för att redigera filmens information
                    echo '<a href="edit_movie.php?id=' . $id['id'] . '">Edit</a>';
                    echo ' | ';
                    // Skapar en länk för att ta bort filmen 
                    echo '<a href="remove_movie.php?id=' . $id['id'] . '">Delete</a>';
                    
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No movies in your library yet.</p>';
            }
        } catch (PDOException $e) { // hanterar databasfel
            echo 'Database Error: ' . $e->getMessage();
        }
        ?>
    </main>

<footer class="footer">
    <a href="https://www.flaticon.com/free-icons/cinema" title="cinema icons">Cinema icons created by Freepik - Flaticon</a>
  </footer>

</body>
</html>

