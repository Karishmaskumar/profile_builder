<?php
// Establish a database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "profile_builder";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


/*
// Check if the user is logged in and get their uname
session_start();
if (isset($_SESSION["uname"])) {
    $uname = $_SESSION["uname"];
} else {
    // Redirect to signup page if uname is not set
    header("Location: signup_mentee.php");
    exit;
}

*/

$uname = 'anu';

// Retrieve the areas_of_interest based on uname
$areas_of_interest_query = "SELECT areas_of_interest FROM mentee WHERE uname = '$uname'";
$areas_of_interest_result = $conn->query($areas_of_interest_query);

if ($areas_of_interest_result->num_rows > 0) {
    $areas_of_interest_row = $areas_of_interest_result->fetch_assoc();
    $user_areas_of_interest = $areas_of_interest_row['areas_of_interest'];

    // Execute the Python script and capture the output
    $command = 'python3 course_recommendation.py ' . escapeshellarg($user_areas_of_interest);
    $output = shell_exec($command);

    // Process the output
    $recommended_courses = explode("\n", $output);
    $recommended_courses = array_filter($recommended_courses);

    // Display the recommended courses
    echo "<h3>Recommended courses:</h3>";
    echo "<ul>";
    foreach ($recommended_courses as $course) {
        echo "<li>" . $course . "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>