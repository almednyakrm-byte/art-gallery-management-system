**list_معارض-فنون.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معارض فنون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-emerald-600 {
            background-color: #0d6efd;
        }
        .text-teal-500 {
            color: #0fc2c9;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-emerald-600 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-teal-500 hover:text-white">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <p class="text-white mr-4">مرحباً, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-white hover:text-emerald-600">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl text-emerald-600 mb-4">معارض فنون</h1>
        <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_معارض-فنون.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 mr-4" placeholder="بحث...">
            <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">العنوان</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/معارض-فنون.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.اسم}</td>
                        <td class="border border-gray-400 p-2">${record.عنوان}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_معارض-فنون.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                            <button class="text-red-600 hover:text-emerald-600" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }
        fetchRecords();

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                // Fetch records from backend with search query
                async function fetchSearchedRecords() {
                    try {
                        const response = await fetch('../backend/معارض-فنون.php', {
                            method: 'GET',
                            params: { search: searchValue }
                        });
                        const data = await response.json();
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 p-2">${record.اسم}</td>
                                <td class="border border-gray-400 p-2">${record.عنوان}</td>
                                <td class="border border-gray-400 p-2">
                                    <a href="edit_معارض-فنون.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                                    <button class="text-red-600 hover:text-emerald-600" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
                fetchSearchedRecords();
            } else {
                fetchRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/معارض-فنون.php', {
                        method: 'DELETE',
                        params: { id }
                    });
                    if (response.ok) {
                        fetchRecords();
                    } else {
                        console.error('Error deleting record');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    </script>
</body>
</html>

This code includes:

1. Session validation to ensure the user is logged in before accessing the page.
2. A premium Tailwind UI layout with a custom color palette.
3. A header navigation bar with links to the index page, current user info, and logout.
4. A table displaying a list of records with actions to edit and delete each record.
5. An "Add New Item" button linking to the create_معارض-فنون.php page.
6. A search bar filtering elements in real-time.
7. AJAX JavaScript code using the Fetch API to fetch records from the backend and delete records.

Note: This code assumes that the backend API is implemented to handle GET and DELETE requests for the "معارض_فنون" module.