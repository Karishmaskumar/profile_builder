<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            align: center;
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
            padding-top: 500px;
            padding-bottom: 500px;
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
include 'mentor_header.html';

// Retrieve uname from the URL parameter
if (isset($_GET["uname"])) {
    $uname = $_GET["uname"];
} else {
    // Redirect to signup page if uname is not set
    header("Location: signup_mentee.php");
    exit;
}

?>
<br>
<h1 style="text-align: center;color:white">To-Do List for <?php echo $uname; ?></h1><br>

<?php
// Select courses from the "course_names" table that have the same areas_of_interest as the mentee
$sql = "SELECT CN.id, CN.course_name
        FROM course_names CN
        INNER JOIN mentee M ON M.areas_of_interest = CN.areas_of_interest
        WHERE M.uname = '$uname'";
$result = mysqli_query($db, $sql);

// Check if there are any matching courses
if (mysqli_num_rows($result) > 0) {
    // Display the to-do list
    echo '<table>';
    echo '<tr><th>Course</th><th>Status</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        // Get the status of the course for the current user
        $sql2 = "SELECT status FROM user_courses WHERE uname = '$uname' AND course_id = " . $row['id'];
        $result2 = mysqli_query($db, $sql2);
        if (mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $status = $row2['status'];
        } else {
            $status = "Not Started";
        }
        // Display the course and its status
        echo '<tr><td>' . $row['course_name'] . '</td><td>' . $status . '</td></tr>';
    }
    echo '</table>';
} else {
    echo '<p>No matching courses found.</p>';
}

$db->close();
?>
</body>
</html>