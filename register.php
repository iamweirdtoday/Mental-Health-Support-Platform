<?php
// Create connection
$con = mysqli_connect("localhost", "root", "", "mhsp");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$regName = $regEmail = $regPassword = $confirmPassword = "";

$regEmailErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    
    $regName = test_input($_POST["regName"]);
    
    $regEmail = test_input($_POST["regEmail"]);
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$regEmail'";
    $checkEmailResult = mysqli_query($con, $checkEmailQuery);
    $rowEmail = mysqli_fetch_assoc($checkEmailResult);
    if ($rowEmail) {
        $regEmailErr = "Email is already taken";
    }

    mysqli_free_result($checkEmailResult);
    
    $regPassword = test_input($_POST["regPassword"]);

    if (empty($regEmailErr)) {
        $password=$regPassword;
        $sqlInsert = "INSERT INTO users (name, email, password) VALUES ('$regName', '$regEmail', '$password')";
        mysqli_query($con, $sqlInsert);
        header("Location: login.php");
        exit();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/form.css">
    <script>
        function validateForm() {
            var regName = document.getElementById("regName").value;
            var regEmail = document.getElementById("regEmail").value;
            var regPassword = document.getElementById("regPassword").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            // Reset previous error messages
            document.getElementById("regNameErr").innerText = "";
            document.getElementById("regEmailErr").innerText = "";
            document.getElementById("regPasswordErr").innerText = "";
            document.getElementById("confirmPasswordErr").innerText = "";

            // Validate Name
            if (regName === "") {
                document.getElementById("regNameErr").innerText = "Name is required";
                return false;
            }

            // Validate Email
            if (regEmail === "") {
                document.getElementById("regEmailErr").innerText = "Email is required";
                return false;
            }

            // Display server-side email error if it exists
            var alreadyExist = "<?php echo $regEmailErr; ?>";
            if (alreadyExist !== "") {
                document.getElementById("regEmailErr").innerText = alreadyExist;
                return false;
            }

            // Validate Password
            if (regPassword === "") {
                document.getElementById("regPasswordErr").innerText = "Password is required";
                return false;
            }

            // Validate Confirm Password
            if (confirmPassword === "") {
                document.getElementById("confirmPasswordErr").innerText = "Please confirm the password";
                return false;
            }

            // Check if Passwords match
            if (regPassword !== confirmPassword) {
                document.getElementById("confirmPasswordErr").innerText = "Passwords do not match";
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
<div id="registration-form" class="container">
    <h2>User Registration</h2>

    <form method="post" action="register.php" onsubmit="return validateForm()">
        <!-- Registration form fields go here -->
        <label for="regName">Name:</label>
        <input type="text" name="regName" id="regName" value="">
        <span class="error" id="regNameErr"></span>
        <br><br>

        <label for="regEmail">Email:</label>
        <input type="text" name="regEmail" id="regEmail" value="">
        <span class="error" id="regEmailErr"></span>
        <br><br>


        <label for="regPassword">Password:</label>
        <input type="password" name="regPassword" id="regPassword">
        <span class="error" id="regPasswordErr"></span>
        <br><br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" name="confirmPassword" id="confirmPassword">
        <span class="error" id="confirmPasswordErr"></span>
        <br><br>

        <input class="submit-button" type="submit" name="register" value="Register">
        <br>

        <p>Already have an account? <a class="toggle-button" href="login.php">Click here</a> to log in.</p>

    </form>
</div>
</body>
</html>