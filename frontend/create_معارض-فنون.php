**create_معارض-فنون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);

    if (!empty($name) && !empty($description) && !empty($location) && !empty($start_date) && !empty($end_date)) {
        // Insert data into database
        $query = "INSERT INTO معارض_فنون (name, description, location, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssss", $name, $description, $location, $start_date, $end_date);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_معارض-فنون.php');
        exit;
    } else {
        $error = 'Please fill in all fields.';
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">Create New معارض_فنون</h1>

    <?php if (isset($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Location</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="location" type="text" name="location" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" type="date" name="start_date" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">End Date</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_date" type="date" name="end_date" required>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="submit">Create</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('create-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/معارض-فنون.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_معارض-فنون.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    });
</script>

<?php
// Include footer
require_once '../includes/footer.php';
?>

This code creates a premium Tailwind UI form with all necessary fields for the `معارض_فنون` module. It uses AJAX to POST the form data to the backend PHP file `../backend/معارض-فنون.php`. On success, it redirects back to the list page `list_معارض-فنون.php`.