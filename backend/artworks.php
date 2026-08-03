<?php
// Import database connection file
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You must be logged in to access this resource']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize the input
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    // Prepare the SQL query
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM artworks WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM artworks');
    }

    // Execute the query
    $stmt->execute();

    // Process the output
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($artworks);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to create new artworks']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $image_url = filter_var($data['image_url'], FILTER_SANITIZE_URL);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO artworks (title, description, image_url) VALUES (:title, :description, :image_url)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_url', $image_url);

    // Execute the query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artwork created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create artwork']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to update artworks']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $image_url = filter_var($data['image_url'], FILTER_SANITIZE_URL);

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE artworks SET title = :title, description = :description, image_url = :image_url WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_url', $image_url);

    // Execute the query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artwork updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update artwork']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to delete artworks']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM artworks WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute the query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Artwork deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete artwork']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}