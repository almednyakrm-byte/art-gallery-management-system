<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get all artists
    $stmt = $pdo->prepare('SELECT * FROM فنانين');
    $stmt->execute();
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return artists
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($artists);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['nationality']) || !isset($input['genre'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $nationality = filter_var($input['nationality'], FILTER_SANITIZE_STRING);
    $genre = filter_var($input['genre'], FILTER_SANITIZE_STRING);

    // Insert new artist
    $stmt = $pdo->prepare('INSERT INTO فنانين (name, nationality, genre) VALUES (:name, :nationality, :genre)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':nationality', $nationality);
    $stmt->bindParam(':genre', $genre);
    $stmt->execute();

    // Return artist ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['nationality']) || !isset($input['genre'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $nationality = filter_var($input['nationality'], FILTER_SANITIZE_STRING);
    $genre = filter_var($input['genre'], FILTER_SANITIZE_STRING);

    // Update artist
    $stmt = $pdo->prepare('UPDATE فنانين SET name = :name, nationality = :nationality, genre = :genre WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':nationality', $nationality);
    $stmt->bindParam(':genre', $genre);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Artist updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete artist
    $stmt = $pdo->prepare('DELETE FROM فنانين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Artist deleted successfully'));
    exit;
}

// Return error message
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;