<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Select all rows from 'عروض' table
    $stmt = $pdo->prepare('SELECT * FROM عروض');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return all rows as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

// Handle POST request
elseif ($method === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
    
    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Insert new row into 'عروض' table
    $stmt = $pdo->prepare('INSERT INTO عروض (title, description, price) VALUES (:title, :description, :price)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();
    
    // Return new row as JSON
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Offer created successfully']);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $data = json_decode(file_get_contents('php://input'), true);
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
    
    // Check if user is logged in and has admin role
    if (!$userID || $userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Update existing row in 'عروض' table
    $stmt = $pdo->prepare('UPDATE عروض SET title = :title, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return updated row as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Offer updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    // Check if user is logged in and has admin role
    if (!$userID || $userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Delete row from 'عروض' table
    $stmt = $pdo->prepare('DELETE FROM عروض WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return success message as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Offer deleted successfully']);
}

// Return error message if request method is not supported
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}