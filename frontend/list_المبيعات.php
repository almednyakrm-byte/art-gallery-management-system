**list_المبيعات.php**

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
    <title>المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #1a1d23;
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
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="header">
        <a href="index.php" class="text-lg font-bold">الرئيسية</a>
        <span class="mx-4 text-lg">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-lg font-bold">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المبيعات</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_المبيعات.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المبيعات</th>
                    <th>تاريخ المبيعات</th>
                    <th>إجمالي المبيعات</th>
                    <th>حالة المبيعات</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['total']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_المبيعات.php?id=<?php echo $record['id']; ?>" class="text-emerald-600 hover:text-emerald-700">تعديل</a>
                            <button class="text-red-600 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchValue = searchInput.value.trim();
            if (searchValue !== '') {
                fetch('../backend/المبيعات.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.date}</td>
                                <td>${record.total}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_المبيعات.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-700">تعديل</a>
                                    <button class="text-red-600 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetchRecords();
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/المبيعات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/المبيعات.php')
                .then(response => response.json())
                .then(data => data.records);
        }

        searchRecords();
    </script>
</body>
</html>


**backend/المبيعات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $query = "SELECT * FROM المبيعات WHERE CONCAT(id, date, total, status) LIKE '%$searchValue%'";
} else {
    $query = "SELECT * FROM المبيعات";
}

// Fetch records
$result = $conn->query($query);
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
echo json_encode(array('records' => $records));

// Close connection
$conn->close();
?>


Note: This code assumes you have a database table named `المبيعات` with columns `id`, `date`, `total`, and `status`. You should replace the database credentials and table name with your actual values. Additionally, this code uses a simple search query that searches for the search value in all columns. You may want to improve the search query to search only in specific columns or use a more advanced search algorithm.