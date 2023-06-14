<!DOCTYPE html>
<html>
<head>
  <title>My To-Do List</title>
  <style>
    table {
      border-collapse: collapse;
      width: 70%;
      margin-left: auto;
      margin-right: auto;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: black;
      color: white;
    }

    tr {
      background-color: white;
      border: 1px solid black;
    }

    body {
      padding: 50px;
      background-image: url("https://c4.wallpaperflare.com/wallpaper/83/667/620/blue-computer-backgrounds-wallpaper-preview.jpg");
      background-position: center;
      background-size: 100% 100%;
      position: relative;
      font-family: sans-serif;
    }
  </style>
</head>
<body>

<?php
// Connect to the database
$db = mysqli_connect("localhost:3307", "root", "", "profile_builder");
session_start();
include 'mentee_header.php';
?>
<h1 style="text-align: center; color: white;">My To-Do List</h1>
<?php
// Retrieve uname from session
if (isset($_SESSION["uname"])) {
  $uname = $_SESSION["uname"];
} else {
  // Redirect to signup page if uname is not set
  header("Location: signup_mentee.php");
  exit;
}

// Select areas of interest for the current user
$sql_interests = "SELECT areas_of_interest FROM mentee WHERE uname = '$uname'";
$result_interests = mysqli_query($db, $sql_interests);
$row_interests = mysqli_fetch_assoc($result_interests);
$areas_of_interest = $row_interests['areas_of_interest'];

// Retrieve the courses that match the mentee's areas_of_interest from the course_names table
$sql_courses = "SELECT id, course_name FROM course_names WHERE areas_of_interest = '$areas_of_interest'";
$result_courses = mysqli_query($db, $sql_courses);

// Check if there are any matching courses
if (mysqli_num_rows($result_courses) > 0) {
  // Display the to-do list
  echo '<table>';
  echo '<tr><th>Course</th><th>Status</th></tr>';
  while ($row_courses = mysqli_fetch_assoc($result_courses)) {
    $id = $row_courses['id'];
    
    // Get the status of the course for the current user
    $sql_status = "SELECT status FROM user_courses WHERE uname = '$uname' AND course_id = $id";
    $result_status = mysqli_query($db, $sql_status);
    if (mysqli_num_rows($result_status) > 0) {
      $row_status = mysqli_fetch_assoc($result_status);
      $status = $row_status['status'];
    } else {
      $status = "Not Started";
    }
    
    // Display the course and its status
    echo '<tr><td>' . $row_courses['course_name'] . '</td><td>' . $status . '</td></tr>';
  }
  
  echo '</table>';
} else {
  echo '<p>No matching courses found.</p>';
}

$db->close();
?>

</body>
</html>