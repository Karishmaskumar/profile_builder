<?php
    // Connect to the database
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "profile_builder";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    session_start();

    // Retrieve uname from session
    if (isset($_SESSION["uname"])) {
        $menteeUname = $_SESSION["uname"];
    } 

    // Retrieve mentor username from the form submission
    if (isset($_POST["mentor_uname"])) {
        $mentorUname = $_POST["mentor_uname"];

        // Insert the request into the requests table
        $sql = "INSERT INTO requests (mentee_uname, mentor_uname) VALUES ('$menteeUname', '$mentorUname')";
        if ($conn->query($sql) === TRUE) {
            $message = "Request sent successfully.";
        } else {
            $message = "Error sending request: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Status</title>
</head>
<body>
    <h2>Request Status</h2>
    <p><?php echo $message; ?></p>
    <a href="recommend.php">Go Back</a>
</body>
</html>