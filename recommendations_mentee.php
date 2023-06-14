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
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            background-color: #0066b2;
            color: white;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .requested {
            background-color: #808080;
            cursor: default;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission using AJAX
            $('form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);

                // Disable the request button to prevent multiple submissions
                form.find('button[type="submit"]').prop('disabled', true);

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        // Display the success message on the same page
                        form.find('button[type="submit"]').replaceWith("<button class='request-button requested' disabled>Requested</button>");
                    },
                    error: function(xhr, status, error) {
                        // Handle the error if the request fails
                        console.log(error);
                    }
                });
            });
        });
    </script>
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
        include 'mentee_header.php';

        // Retrieve uname from session
        if (isset($_SESSION["uname"])) {
            $uname = $_SESSION["uname"];
        } 
        
        // Query to find matching mentors and their usernames
        $sql = "SELECT M.uname
                FROM Mentor M
                INNER JOIN Mentee E ON E.areas_of_interest = M.areas_of_interest
                WHERE E.uname = '$uname'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            echo "<h2 style='color: white; text-align: center; margin-top:30px;'>RECOMMENDED MENTORS</h2>";

            echo "<div class='mentee-container'>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='mentee-box'>
                        <span class='mentee-icon'></span>
                        </br></br><span class='mentee-name'>" . $row["uname"] . "</span>";

                // Check if a request has already been sent to this mentor
                $mentorUname = $row["uname"];
                $requestSent = false;

                $requestQuery = "SELECT * FROM requests WHERE mentee_uname = '$uname' AND mentor_uname = '$mentorUname' AND status = 'pending'";
                $requestResult = $conn->query($requestQuery);

                if ($requestResult->num_rows > 0) {
                    $requestSent = true;
                }

                if ($requestSent) {
                    echo "<button class='request-button requested' disabled>Request Pending</button>";
                } else {
                    echo "<form method='POST' action='send_request.php'>
                            <input type='hidden' name='mentor_uname' value='" . $row["uname"] . "'>
                            <button class='request-button' type='submit'>Request</button>
                        </form>";
                }

                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "No recommended mentors found.";
        }

        // Close the database connection
        $conn->close();
    ?>
</body>
</html>