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

// Check if the user is logged in and get their uname
session_start();
if (isset($_SESSION["uname"])) {
    $uname = $_SESSION["uname"];
} else {
    // Redirect to signup page if uname is not set
    header("Location: signup_mentee.php");
    exit;
}

// Retrieve the areas_of_interest based on uname
$areas_of_interest_query = "SELECT areas_of_interest FROM mentee WHERE uname = '$uname'";
$areas_of_interest_result = $conn->query($areas_of_interest_query);

if ($areas_of_interest_result->num_rows > 0) {
    $areas_of_interest_row = $areas_of_interest_result->fetch_assoc();
    $user_areas_of_interest = $areas_of_interest_row['areas_of_interest'];

    if (isset($_POST['suggestions'])) {
        // Retrieve the matching courses based on areas_of_interest
        $course_query = "SELECT course_name FROM course_names WHERE areas_of_interest = '$user_areas_of_interest'";
        $course_result = $conn->query($course_query);

        // Display the suggested courses
        echo "<h3>Recommended courses:</h3>";
        echo "<ul>";
        while ($course_row = $course_result->fetch_assoc()) {
            echo "<li>" . $course_row['course_name'] . "</li>";
        }
        echo "</ul>";
    } else {
        // Display the button to trigger course recommendations
        echo "<form method='post'>";
        echo "<input type='submit' name='suggestions' value='Get Course Suggestions'>";
        echo "</form>";
    }
}

$conn->close();
?>