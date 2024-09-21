<?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Collect form data and sanitize inputs
    $firstName = isset($_POST['firstName']) ? htmlspecialchars(trim($_POST['firstName'])) : null;
    $lastName = isset($_POST['lastName']) ? htmlspecialchars(trim($_POST['lastName'])) : null;
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : null;
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null;
    $number = isset($_POST['number']) ? htmlspecialchars(trim($_POST['number'])) : null;

    // Basic validation: Check if all fields are filled
    if (empty($firstName) || empty($lastName) || empty($gender) || empty($email) || empty($password) || empty($number)) {
        die("All fields are required. Please fill in all fields.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'test','3307');

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into the registration table
    $stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");

    // Check if the statement was prepared correctly
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters ("ssssss" means six strings)
    $stmt->bind_param("ssssss", $firstName, $lastName, $gender, $email, $hashed_password, $number);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error in registration: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
?>




