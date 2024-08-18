<?php
// I included the database connection and necessary functions
require '../includes/db.php';
require '../includes/functions.php';

// I checked if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // I trimmed and sanitized the username to prevent XSS attacks
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');

    // I trimmed and sanitized the email to prevent XSS attacks and ensure clean input
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');

    // I retrieved the password directly (will be hashed later)
    $password = $_POST['password'];

    // I validated the email format using PHP's built-in filter_var function
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format."; // I provided feedback if the email format is incorrect
    } elseif (!strpos(substr($email, strpos($email, '@')), '.')) {
        echo "Email must contain a period (.) in the domain part."; // I ensured the email domain contains a period
    } else {
        // I extracted the domain part of the email
        $domain = substr(strrchr($email, "@"), 1);

        // I checked if the domain is valid and converted it to ASCII if it's in UTF-8
        if (filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $email = idn_to_ascii($email); // I converted the email domain to ASCII to handle international domains
        } else {
            echo "Invalid domain in email."; // I provided feedback if the domain is invalid
            exit(); // I stopped the script if the domain is invalid
        }

        // I validated the password strength (minimum length, includes uppercase, lowercase, number, special character)
        if (
            strlen($password) < 8 ||
            !preg_match("/[A-Z]/", $password) ||
            !preg_match("/[a-z]/", $password) ||
            !preg_match("/[0-9]/", $password) ||
            !preg_match("/[\W]/", $password)
        ) {
            echo "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character."; // I enforced strong password rules
            exit(); // I stopped the script if the password doesn't meet the criteria
        }

        // I called the register_user function to save the user in the database
        if (register_user($username, $email, $password)) {
            echo "Registration successful."; // I confirmed the registration was successful
        } else {
            echo "Registration failed."; // I provided feedback if the registration failed
        }
    }
}

// I defined the register_user function to handle user registration securely
function register_user($username, $email, $password)
{
    global $conn; // I used the global database connection variable

    // I checked if the email already exists in the database to prevent duplicate registrations
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // I securely bound the email parameter to prevent SQL injection
    $stmt->execute(); // I executed the query
    $stmt->store_result(); // I stored the result to check if the email exists

    // I checked if any rows were returned, meaning the email is already registered
    if ($stmt->num_rows > 0) {
        echo "Email already registered."; // I informed the user that the email is already in use
        return false; // I returned false to indicate the registration failed
    }

    $stmt->close(); // I closed the statement after checking the email

    // I hashed the password using BCRYPT for secure storage
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // I prepared the SQL statement to insert the new user's data securely
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password); // I securely bound the parameters

    // I executed the query and checked if it was successful
    if ($stmt->execute()) {
        return true; // I returned true to indicate the registration was successful
    } else {
        return false; // I returned false to indicate the registration failed
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Added some water.css styles to the webpage -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <!-- Added some random favicon -->
    <link rel="icon" type="image/png" href="https://www.paypalobjects.com/webstatic/icon/pp32.png">
</head>

<body>
    <!-- I created the registration form -->
    <form action="register.php" method="post">
        <!-- I included a CSRF token for security against Cross-Site Request Forgery -->
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <label for="username">Username:</label> <!-- I labeled the username input field -->
        <input type="text" id="username" name="username" required pattern="^[a-zA-Z0-9_]{5,20}$" title="Username should be 5-20 characters long, and can include letters, numbers, and underscores only."> <!-- I set a pattern for valid usernames -->

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>
</body>

</html>