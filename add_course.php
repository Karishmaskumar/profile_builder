<?php
// Connect to the database
$db = mysqli_connect("localhost:3307", "root", "", "profile_builder");
session_start();
include 'mentee_header.php';

// Retrieve uname from session
if (isset($_SESSION["uname"])) {
  $uname = $_SESSION["uname"];
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
  // Get the selected course ID
  $course_id = mysqli_real_escape_string($db, $_POST['course_id']);

  // Insert the course into the user_courses table with the "on set" status
  $sql = "INSERT INTO user_courses (uname, course_id, status) SELECT '$uname', id, 'on set' FROM course_names WHERE id = '$course_id'";
  mysqli_query($db, $sql);

  // Set a flag to indicate that the course was added successfully
  $course_added = true;
}

// Retrieve the areas_of_interest based on uname
$areas_of_interest_query = "SELECT areas_of_interest FROM mentee WHERE uname = '$uname'";
$areas_of_interest_result = mysqli_query($db, $areas_of_interest_query);

// Array to store the suggested courses
$suggested_courses = [];

if (mysqli_num_rows($areas_of_interest_result) > 0) {
  $areas_of_interest_row = mysqli_fetch_assoc($areas_of_interest_result);
  $user_areas_of_interest = $areas_of_interest_row['areas_of_interest'];

  // Retrieve the matching courses based on areas_of_interest
  $course_query = "SELECT id, course_name FROM course_names WHERE areas_of_interest = '$user_areas_of_interest'";
  $course_result = mysqli_query($db, $course_query);

  if (mysqli_num_rows($course_result) > 0) {
    while ($course_row = mysqli_fetch_assoc($course_result)) {
      $suggested_courses[$course_row['id']] = $course_row['course_name'];
    }
  }
}

// Close the database connection
mysqli_close($db);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Course to To-Do List</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    body {
      padding-top: 500px;
      padding-bottom: 500px;
      background-image: url("https://c4.wallpaperflare.com/wallpaper/83/667/620/blue-computer-backgrounds-wallpaper-preview.jpg");
      background-position: center;
      background-size: 100% 100%;
      position: relative;
      font-family: sans-serif;
    }

    select {
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #f2f2f2;
      font-size: 16px;
      cursor: pointer;
    }

    input[type="submit"] {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background-color: #0066b2;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    #goal {
      width: 100%;
      height: 20px;
      margin: 10px 0;
      -webkit-appearance: none;
      background-color: #ddd;
      outline: none;
    }

    #goal::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 20px;
      height: 20px;
      background-color: #007bff;
      cursor: pointer;
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    #goal-value {
      display: inline-block;
      margin-left: 10px;
      font-weight: bold;
      font-size: 16px;
    }

    #goal-value::after {
      content: " ";
    }

    #goal-value::before {
      content: attr(data-content);
      font-weight: normal;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <h1>ADD COURSE TO DO LIST</h1>
  <?php if (isset($course_added)) { ?>
    <p><u>Course added to your to-do list</u></p>
  <?php } ?>
  <form method="POST" action="">
    <h2 for="course_id"><b>Course:</b></h2>
    <select id="course_id" name="course_id">
      <?php foreach ($suggested_courses as $id => $course_name) { ?>
        <option value="<?php echo $id; ?>"><?php echo $course_name; ?></option>
      <?php } ?>
    </select><br><br>
    <h2 for="goal"><b>Adjust your Goal to complete the course:</b></h2>
    <input type="range" id="goal" name="goal" min="14" max="180" value="14" step="1">
    <p>Selected value: <span id="goal-value"></span></p>
    <input type="submit" name="submit" value="Add to My To-Do">
  </form><br/><br/>

  <form style="display: inline" action="update_status.php" method="get">
    <input type="submit" name="submit" value="Update status of courses">
  </form><br/><br/>

  <form style="display: inline" action="index.php" method="get">
    <input type="submit" name="submit" value="Display courses">
  </form>
  <script>
    const goal = document.getElementById("goal");
    const goalValue = document.getElementById("goal-value");

    goal.addEventListener("input", function() {
      goalValue.textContent = goal.value;
    });

    goal.addEventListener("input", function() {
      const weeks = Math.round(goal.value / 7);
      const months = Math.round(goal.value / 30);
      const displayValue = weeks < 4 ? weeks + " weeks" : months + " months";
      goalValue.textContent = displayValue;
    });
  </script>
  <br/>
</body>
</html>