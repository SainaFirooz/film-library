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
  
  <h2>Add a movie to your library:</h2>
  <form action="add_movie.php" method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="director">Director:</label>
    <input type="text" id="director" name="director" required><br>

    <label for="year">Year:</label>
    <input type="number" id="year" name="year" required><br>

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
    <input type="submit" value="Add Film">
</form>

<footer class="footer">
    <a href="https://www.flaticon.com/free-icons/cinema-icons" title="cinema icons">Cinema icons created by Freepik - Flaticon</a>
  </footer>
</body>
</html>