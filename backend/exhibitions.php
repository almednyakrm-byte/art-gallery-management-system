<?php
// Import database connection file
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'You are not logged in.']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle different HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Validate and sanitize input parameters
        $exhibitionId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // Check if the exhibition ID is provided
        if ($exhibitionId) {
            // SQL query to retrieve a single exhibition
            $stmt = $pdo->prepare('SELECT * FROM exhibitions WHERE id = :id');
            $stmt->bindParam(':id', $exhibitionId);
            $stmt->execute();
            $exhibition = $stmt->fetch();

            // Check if the exhibition exists
            if ($exhibition) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($exhibition);
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Exhibition not found.']);
            }
        } else {
            // SQL query to retrieve all exhibitions
            $stmt = $pdo->prepare('SELECT * FROM exhibitions');
            $stmt->execute();
            $exhibitions = $stmt->fetchAll();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($exhibitions);
        }
        break;

    case 'POST':
        // Check if the user is an admin
        if ($userRole !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Only admins can create exhibitions.']);
            exit;
        }

        // Get input data
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
        $startDate = filter_var($input['start_date'], FILTER_SANITIZE_STRING);
        $endDate = filter_var($input['end_date'], FILTER_SANITIZE_STRING);

        // Check if all required fields are provided
        if (!$name || !$description || !$startDate || !$endDate) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'All fields are required.']);
            exit;
        }

        // SQL query to insert a new exhibition
        $stmt = $pdo->prepare('INSERT INTO exhibitions (name, description, start_date, end_date) VALUES (:name, :description, :start_date, :end_date)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Exhibition created successfully.']);
        break;

    case 'PUT':
        // Check if the user is an admin
        if ($userRole !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Only admins can update exhibitions.']);
            exit;
        }

        // Get input data
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize input data
        $exhibitionId = filter_var($input['id'], FILTER_VALIDATE_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
        $startDate = filter_var($input['start_date'], FILTER_SANITIZE_STRING);
        $endDate = filter_var($input['end_date'], FILTER_SANITIZE_STRING);

        // Check if all required fields are provided
        if (!$exhibitionId || !$name || !$description || !$startDate || !$endDate) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'All fields are required.']);
            exit;
        }

        // SQL query to update an exhibition
        $stmt = $pdo->prepare('UPDATE exhibitions SET name = :name, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :id');
        $stmt->bindParam(':id', $exhibitionId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Exhibition updated successfully.']);
        break;

    case 'DELETE':
        // Check if the user is an admin
        if ($userRole !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Only admins can delete exhibitions.']);
            exit;
        }

        // Get input data
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize input data
        $exhibitionId = filter_var($input['id'], FILTER_VALIDATE_INT);

        // Check if the exhibition ID is provided
        if (!$exhibitionId) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Exhibition ID is required.']);
            exit;
        }

        // SQL query to delete an exhibition
        $stmt = $pdo->prepare('DELETE FROM exhibitions WHERE id = :id');
        $stmt->bindParam(':id', $exhibitionId);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Exhibition deleted successfully.']);
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Method not allowed.']);
        break;
}