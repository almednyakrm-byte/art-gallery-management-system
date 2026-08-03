<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define database table name
$table_name = 'معارض_فنون';

// Define validation rules
$validation_rules = [
    'id' => 'integer',
    'name' => 'string',
    'description' => 'string',
];

// Validate input data
foreach ($validation_rules as $field => $rule) {
    if (isset($input[$field])) {
        switch ($rule) {
            case 'integer':
                if (!is_numeric($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid ' . $field]);
                    exit;
                }
                break;
            case 'string':
                if (!is_string($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid ' . $field]);
                    exit;
                }
                break;
        }
    }
}

// Handle HTTP requests
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all records
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO $table_name (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Record created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create record']);
    }
} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update existing record
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID']);
        exit;
    }
    $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':id', $input['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Record updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update record']);
    }
} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete existing record
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID']);
        exit;
    }
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Record deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete record']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}