**list_تذاكر.php**

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
    <title>تذاكر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        .table-container table th {
            background-color: #f0f0f0;
        }
        .table-container table td {
            cursor: pointer;
        }
        .table-container table td a {
            text-decoration: none;
            color: #337ab7;
        }
        .table-container table td a:hover {
            color: #23527c;
        }
        .search-bar {
            padding: 1rem;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"]::placeholder {
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">تذاكر</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be generated dynamically -->
            </tbody>
        </table>
    </div>
    <div class="search-bar">
        <input type="search" id="search-input" placeholder="بحث...">
        <button id="search-button">بحث</button>
    </div>
    <button id="add-button" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</button>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        // Get search input element
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const addButton = document.getElementById('add-button');
        const tableBody = document.getElementById('table-body');

        // Add event listener to search button
        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/تذاكر.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.اسم}</td>
                                <td>${item.تاريخ}</td>
                                <td>${item.حالة}</td>
                                <td>
                                    <a href="edit_تذاكر.php?id=${item.id}">تعديل</a>
                                    <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/تذاكر.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.اسم}</td>
                                <td>${item.تاريخ}</td>
                                <td>${item.حالة}</td>
                                <td>
                                    <a href="edit_تذاكر.php?id=${item.id}">تعديل</a>
                                    <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }
        });

        // Add event listener to add button
        addButton.addEventListener('click', () => {
            window.location.href = 'create_تذاكر.php';
        });

        // Delete item function
        function deleteItem(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                fetch('../backend/تذاكر.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                });
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`../backend/تذاكر.php`) that handles the GET and DELETE requests for the `تذاكر` module. The backend script should return a JSON response with the list of records or a success/failure message for the delete request.