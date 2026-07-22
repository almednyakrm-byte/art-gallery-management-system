<?php

require_once 'db.php';

// Get user role and authentication status
if (!isset($_SESSION['role']) || !isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role for admin-only access
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query to retrieve all sales data
    $stmt = $pdo->prepare('SELECT * FROM المبيعات');
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return sales data in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($sales);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role for admin-only access
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['product_id']) || !isset($input['quantity']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $product_id = filter_var($input['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Prepare SQL query to insert new sale
    $stmt = $pdo->prepare('INSERT INTO المبيعات (product_id, quantity, price) VALUES (:product_id, :quantity, :price)');
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    echo json_encode(array('message' => 'Sale created successfully'));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role for admin-only access
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['product_id']) || !isset($input['quantity']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $product_id = filter_var($input['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Prepare SQL query to update sale
    $stmt = $pdo->prepare('UPDATE المبيعات SET product_id = :product_id, quantity = :quantity, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    echo json_encode(array('message' => 'Sale updated successfully'));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role for admin-only access
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
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete sale
    $stmt = $pdo->prepare('DELETE FROM المبيعات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    echo json_encode(array('message' => 'Sale deleted successfully'));
}

// Return error message for unsupported request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}