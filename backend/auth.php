<?php

// Start the session to store user data
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check if the user is trying to register
    if ($action == 'register') {
        // Check if the required fields are filled
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if the username and email are valid
            if (preg_match('/^[a-zA-Z0-9_]+$/', $username) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare the SQL query to insert the user into the database
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                // Execute the query
                if ($stmt->execute()) {
                    // If the user is registered successfully, return a JSON response
                    $response = array('status' => 'registered', 'message' => 'User registered successfully');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    // If there's an error, return a JSON response with the error message
                    $response = array('status' => 'error', 'message' => 'Error registering user');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
            } else {
                // If the username or email is invalid, return a JSON response with the error message
                $response = array('status' => 'error', 'message' => 'Invalid username or email');
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            // If the required fields are not filled, return a JSON response with the error message
            $response = array('status' => 'error', 'message' => 'Please fill in all fields');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Check if the user is trying to login
    elseif ($action == 'login') {
        // Check if the required fields are filled
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare the SQL query to select the user from the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows == 1) {
                // Get the user's data
                $user_data = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user_data['password'])) {
                    // If the password is correct, log the user in and return a JSON response
                    $_SESSION['user_id'] = $user_data['id'];
                    $_SESSION['username'] = $user_data['username'];
                    $response = array('status' => 'logged_in', 'user_id' => $user_data['id'], 'username' => $user_data['username']);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    // If the password is incorrect, return a JSON response with the error message
                    $response = array('status' => 'error', 'message' => 'Incorrect password');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
            } else {
                // If the user does not exist, return a JSON response with the error message
                $response = array('status' => 'error', 'message' => 'User not found');
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            // If the required fields are not filled, return a JSON response with the error message
            $response = array('status' => 'error', 'message' => 'Please fill in all fields');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Check if the user is trying to logout
    elseif ($action == 'logout') {
        // Destroy the session to log the user out
        session_destroy();
        $response = array('status' => 'logged_out');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

// If the user is not trying to register, login, or logout, return a JSON response with the error message
$response = array('status' => 'error', 'message' => 'Invalid action');
header('Content-Type: application/json');
echo json_encode($response);


This code handles user registration, login, logout, and checks the current session user status. It uses prepared statements to prevent SQL injection and hashes passwords using `password_hash()` for secure password storage. It also checks input fields securely using regular expressions and returns JSON responses for AJAX calls.