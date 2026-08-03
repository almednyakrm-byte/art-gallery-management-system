<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all tickets
    $stmt = $pdo->prepare('SELECT * FROM tickets');
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return tickets
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($tickets);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Insert new ticket
    $stmt = $pdo->prepare('INSERT INTO tickets (title, description, user_id) VALUES (:title, :description, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    // Return new ticket
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Ticket created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Update ticket
    $stmt = $pdo->prepare('UPDATE tickets SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated ticket
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Ticket updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);

    // Delete ticket
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted ticket
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Ticket deleted successfully'));
    exit;
}

// Return error for unsupported HTTP method
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;