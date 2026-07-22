<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!$input) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Define table name
$table_name = 'معارض';

// Define columns
$columns = array('id', 'name', 'description');

// Define validation rules
$validation_rules = array(
    'name' => array('required' => true, 'max_length' => 255),
    'description' => array('max_length' => 255)
);

// Validate input data
foreach ($validation_rules as $column => $rules) {
    if (isset($input[$column])) {
        if (isset($rules['required']) && empty($input[$column])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $column));
            exit;
        }
        if (isset($rules['max_length']) && strlen($input[$column]) > $rules['max_length']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Field ' . $column . ' exceeds maximum length'));
            exit;
        }
    }
}

// Sanitize input data
$sanitized_input = array();
foreach ($columns as $column) {
    if (isset($input[$column])) {
        $sanitized_input[$column] = PDO::quote($input[$column]);
    }
}

// Handle CRUD operations
if (isset($input['action'])) {
    switch ($input['action']) {
        case 'get':
            // Get all records
            $stmt = $pdo->prepare("SELECT * FROM $table_name");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($records);
            break;
        case 'create':
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO $table_name (name, description) VALUES (:name, :description)");
            $stmt->execute($sanitized_input);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record created successfully'));
            break;
        case 'update':
            // Update existing record
            $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, description = :description WHERE id = :id");
            $stmt->execute($sanitized_input);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record updated successfully'));
            break;
        case 'delete':
            // Delete existing record
            $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
            $stmt->execute(array('id' => $input['id']));
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record deleted successfully'));
            break;
        default:
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid action'));
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Missing action parameter'));
}