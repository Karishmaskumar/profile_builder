<?php
// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'profile_builder');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}
session_start();
include 'mentee_header.php';
// Retrieve uname from session
if (isset($_SESSION["uname"])) {
  $uname = $_SESSION["uname"];
} else {
  // Redirect to signup page if uname is not set
  header("Location: signup_mentee.php");
  exit;
}

// Retrieve courses from user_courses table for the specific mentee
$sql_courses = "SELECT user_courses.course_id, course_names.course_name 
                FROM user_courses 
                INNER JOIN course_names ON user_courses.course_id = course_names.id
                WHERE user_courses.uname = '$uname'";
$result_courses = mysqli_query($conn, $sql_courses);

// Handle form submission
if (isset($_POST['course_id']) && isset($_POST['status'])) {
  // Sanitize user input
  $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  // Update status of the selected course
  $sql = "UPDATE user_courses
          SET status = '$status'
          WHERE uname = '$uname'
          AND course_id = '$course_id'";
  mysqli_query($conn, $sql);

  // Redirect to the same page to prevent form resubmission on page refresh
  header('Location: update_status.php');
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Course Status</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* CSS styles */
  </style>
</head>
<body>
  <h1>Update Course Status</h1>
  <form method="POST" action="update_status.php">
    <label for="course_id">Select Course:</label>
    <select id="course_id" name="course_id">
      <?php
      // Loop through courses and create dropdown options
      while ($row = mysqli_fetch_assoc($result_courses)) {
        echo '<option value="' . $row['course_id'] . '">' . $row['course_name'] . '</option>';
      }
      ?>
    </select><br><br>
    <label for="status">New Status:</label>
    <select id="status" name="status">
      <option value="on_set">On Set</option>
      <option value="on_progress">On Progress</option>
      <option value="completed">Completed</option>
    </select><br><br>
    <input type="submit" value="Update Status">
  </form>
</body>
</html>