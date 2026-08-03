<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <header class="bg-amber-400 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg text-gray-800">Back to Index</a>
            <span class="text-lg text-gray-800">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg text-gray-800">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl text-gray-800 mb-4">Artists Management</h1>
        <button class="bg-amber-400 hover:bg-amber-500 text-gray-800 font-bold py-2 px-4 rounded mb-4">
            <a href="create_artists.php">Add New Item</a>
        </button>
        <input type="text" id="search" placeholder="Search..." class="w-full p-2 pl-10 text-sm text-gray-800 border border-gray-400 rounded-lg focus:outline-none focus:ring-amber-400 focus:border-amber-400">
        <table id="artists-table" class="w-full text-gray-800 mt-4">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table content will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to load table content
        fetch('../backend/artists.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(artist => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${artist.id}</td>
                        <td class="px-4 py-2">${artist.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_artists.php?id=${artist.id}" class="text-amber-400 hover:text-amber-500">Edit</a>
                            <button class="text-amber-400 hover:text-amber-500" onclick="deleteArtist(${artist.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete artist using AJAX
        function deleteArtist(id) {
            fetch('../backend/artists.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            tableBody.removeChild(rows[i]);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting artist:', data.message);
                }
            })
            .catch(error => console.error('Error deleting artist:', error));
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>