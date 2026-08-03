<?php
// create_artworks.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
$mod_slug = 'artworks';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Artwork</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-amber-400 mb-4">Create Artwork</h2>
        <form id="create-artwork-form">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:ring-amber-400 focus:border-amber-400"></textarea>
            </div>
            <div class="mb-4">
                <label for="artist" class="block text-gray-700 font-bold mb-2">Artist</label>
                <input type="text" id="artist" name="artist" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div class="mb-4">
                <label for="year" class="block text-gray-700 font-bold mb-2">Year</label>
                <input type="number" id="year" name="year" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div class="mb-4">
                <label for="medium" class="block text-gray-700 font-bold mb-2">Medium</label>
                <input type="text" id="medium" name="medium" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:ring-amber-400 focus:border-amber-400">
            </div>
            <button type="submit" class="w-full p-2 bg-amber-400 text-gray-200 font-bold rounded-lg hover:bg-amber-500">Create Artwork</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-artwork-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/artworks.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>