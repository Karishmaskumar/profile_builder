<!-- recommend.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Recommendations</title>
    <style>
        .mentee-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .mentee-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 300px;
            height: 300px;
            background-color: #B9D9EB;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-top: 80px;
            color: #fff;
            text-align: center;
            color: black;
        }

        .mentee-box:hover {
            background-color: white;
            cursor: pointer;
        }

        .mentee-icon {
            width: 120px;
            height: 120px;
            background: url('user.png') no-repeat center center;
            background-size: cover;
            margin-bottom: 10px;
        }

        .mentee-name {
            font-size: 24px;
        }

        .request-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .accepted-button {
            background-color: #ffc107;
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
        include 'mentor_header.html';

        // Retrieve uname from session
        if (isset($_SESSION["uname"])) {
            $mentorUname = $_SESSION["uname"];
        } 

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["mentee_uname"])) {
                $menteeUname = $_POST["mentee_uname"];

                // Update the status of the request to "accepted" in the requests table
                $updateQuery = "UPDATE requests SET status = 'accepted' WHERE mentor_uname = '$mentorUname' AND mentee_uname = '$menteeUname'";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "Request accepted successfully.";
                } else {
                    echo "Error updating request status: " . $conn->error;
                }
            }
        }

        // Query to find matching mentees and their usernames
        $sql = "SELECT E.uname, R.status
                FROM Mentee E
                INNER JOIN Mentor M ON M.areas_of_interest = E.areas_of_interest
                LEFT JOIN requests R ON R.mentee_uname = E.uname AND R.mentor_uname = '$mentorUname'
                WHERE M.uname = '$mentorUname'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2 style='color: white; text-align: center; margin-top:30px;'>RECOMMENDED MENTEES</h2>";

            echo "<div class='mentee-container'>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='mentee-box' onclick='location.href=\"dash_rec_mentee.php?uname=" . $row["uname"] . "\"'>
                    <span class='mentee-icon'></span>
                    </br> </br><span class='mentee-name'>" . $row["uname"] . "</span>";

                if ($row["status"] === "accepted") {
                    echo "<button class='request-button accepted-button'>Accepted</button>";
                } elseif ($row["status"] === "pending") {
                    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                            <input type='hidden' name='mentee_uname' value='" . $row["uname"] . "'>
                            <button class='request-button' type='submit'>Request Pending</button>
                        </form>";
                }

                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "No recommended mentees found.";
        }

        // Close the database connection
        $conn->close();
    ?>
</body>
</html>