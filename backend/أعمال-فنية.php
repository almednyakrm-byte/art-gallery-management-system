<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($userRole == 'admin') {
        // Get all records
        $stmt = $pdo->prepare('SELECT * FROM أعمال_فنية');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } else {
        // Get user's records
        $stmt = $pdo->prepare('SELECT * FROM أعمال_فنية WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $userID);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['description']) || !isset($inputData['image'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $title = trim($inputData['title']);
    $description = trim($inputData['description']);
    $image = trim($inputData['image']);

    // Insert record
    $stmt = $pdo->prepare('INSERT INTO أعمال_فنية (title, description, image, user_id) VALUES (:title, :description, :image, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':user_id', $userID);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record created successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description']) || !isset($inputData['image'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = (int) $inputData['id'];
    $title = trim($inputData['title']);
    $description = trim($inputData['description']);
    $image = trim($inputData['image']);

    // Update record
    $stmt = $pdo->prepare('UPDATE أعمال_فنية SET title = :title, description = :description, image = :image WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = (int) $inputData['id'];

    // Delete record
    $stmt = $pdo->prepare('DELETE FROM أعمال_فنية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
}