<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $artist_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all artists or a specific artist by ID
    $sql = 'SELECT * FROM artists';
    $params = [];
    if ($artist_id) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $artist_id;
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($artists);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $bio = filter_var($input['bio'] ?? null, FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Insert a new artist
    $sql = 'INSERT INTO artists (name, bio) VALUES (:name, :bio)';
    $params = [
        ':name' => $name,
        ':bio' => $bio,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artist created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to create artist']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $bio = filter_var($input['bio'] ?? null, FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Update an existing artist
    $sql = 'UPDATE artists SET name = :name, bio = :bio WHERE id = :id';
    $params = [
        ':id' => $id,
        ':name' => $name,
        ':bio' => $bio,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artist updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to update artist']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Delete an existing artist
    $sql = 'DELETE FROM artists WHERE id = :id';
    $params = [
        ':id' => $id,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artist deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to delete artist']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Method not allowed']);
}