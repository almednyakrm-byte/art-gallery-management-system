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

// Define routes
$routes = array(
    'GET' => array(
        '/all' => 'get_all',
        '/:id' => 'get_by_id'
    ),
    'POST' => array(
        '/create' => 'create'
    ),
    'PUT' => array(
        '/:id/update' => 'update'
    ),
    'DELETE' => array(
        '/:id/delete' => 'delete'
    )
);

// Get route and method
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Check if route is valid
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Get function name
$func_name = $routes[$method][$route];

// Check if user has permission
if (in_array($func_name, array('update', 'delete')) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Call function
$func = array($func_name);
$func();

// Helper functions
function get_all() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM المشتريين');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function get_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM المشتريين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function create() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO المشتريين (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

function update($id) {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    // Update data
    $stmt = $pdo->prepare('UPDATE المشتريين SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete($id) {
    global $pdo;
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM المشتريين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}