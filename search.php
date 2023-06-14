<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * { box-sizing: border-box; }
    
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }
    
    .search-container {
      padding: 20px;
      text-align: center;
    }
    
    .search-container input[type=text] {
      padding: 10px;
      width: 300px;
      border: none;
      border-radius: 4px;
    }
    
    .search-container button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    
    .search-container button:hover {
      background-color: #45a049;
    }
    
    .results-container {
      margin-top: 20px;
      padding: 20px;
    }
    
    .result {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="search-container">
    <form method="POST" action="search.php">
      <input type="text" placeholder="Search by name" name="search">
      <button type="submit">Search</button>
    </form>
  </div>
  
  <div class="results-container">
    <?php
    // Check if the search form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $search = $_POST["search"];
      
      // Connect to your MySQL database
      $conn = mysqli_connect("localhost:3307", "root", "", "profile_builder");
      
      // Perform the search query for mentors
      $mentorQuery = "SELECT uname FROM mentor WHERE uname LIKE '%$search%'";
      $mentorResult = mysqli_query($conn, $mentorQuery);
      
      // Perform the search query for mentees
      $menteeQuery = "SELECT uname FROM mentee WHERE uname LIKE '%$search%'";
      $menteeResult = mysqli_query($conn, $menteeQuery);
      
      // Display the search results
      while ($mentorRow = mysqli_fetch_assoc($mentorResult)) {
        echo "<div class='result'>";
        echo "<h3>Mentor:</h3>";
        echo "<p>Name: <a href='dash_search_mentor.php?uname=" . $mentorRow["uname"] . "'>" . $mentorRow["uname"] . "</a></p>";
        echo "</div>";
      }
    
      while ($menteeRow = mysqli_fetch_assoc($menteeResult)) {
        echo "<div class='result'>";
        echo "<h3>Mentee:</h3>";
        echo "<p>Name: <a href='dash_search_mentee.php?uname=" . $menteeRow["uname"] . "'>" . $menteeRow["uname"] . "</a></p>";
        echo "</div>";
      }
      
      // Close the database connection
      mysqli_close($conn);
    }
    ?>
  </div>
</body>
</html>
