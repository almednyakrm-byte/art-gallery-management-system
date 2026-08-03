<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// HTML content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <header class="bg-amber-400 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg text-gray-800 hover:text-gray-600">Back to Index</a>
            <span class="text-lg text-gray-800">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg text-gray-800 hover:text-gray-600">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl text-gray-800 mb-4">Artworks List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-amber-400 hover:bg-amber-500 text-gray-800 font-bold py-2 px-4 rounded">
                <a href="create_artworks.php">Add New Item</a>
            </button>
            <input type="text" id="search" placeholder="Search..." class="py-2 pl-10 text-sm text-gray-700">
        </div>
        <table id="artworks-table" class="w-full table-auto border border-gray-400">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="artworks-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get artworks list
        fetch('../backend/artworks.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('artworks-tbody');
                data.forEach(artwork => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${artwork.id}</td>
                        <td class="px-4 py-2">${artwork.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_artworks.php?id=${artwork.id}" class="text-gray-800 hover:text-gray-600">Edit</a>
                            <button class="text-gray-800 hover:text-gray-600" onclick="deleteArtwork(${artwork.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete artwork via AJAX
        function deleteArtwork(id) {
            fetch('../backend/artworks.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`#artworks-tbody tr:nth-child(${id})`);
                    row.remove();
                } else {
                    console.error('Error deleting artwork:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#artworks-tbody tr');
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>