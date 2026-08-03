**list_العملات.php**

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
    <title>العملات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4ade80; /* emerald-600 */
            color: #fff;
            padding: 10px;
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
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 30%;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .search-bar button {
            background-color: #4ade80; /* emerald-600 */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3e8e7e;
        }
        .actions {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>العملات</h2>
            <a href="index.php">الرئيسية</a>
            <span class="text-right">مرحباً, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العملة</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/العملات.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['العملة'] . '</td>';
                    echo '<td>' . $record['الوصف'] . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="edit_العملات.php?id=' . $record['id'] . '">تعديل</a>';
                    echo '<button onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <button onclick="addNewRecord()">إضافة جديد</button>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/العملات.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record['العملة']}</td>
                            <td>${record['الوصف']}</td>
                            <td class="actions">
                                <a href="edit_العملات.php?id=${record['id']}">تعديل</a>
                                <button onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/العملات.php', {
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
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        function addNewRecord() {
            window.location.href = 'create_العملات.php';
        }
    </script>
</body>
</html>

**backend/العملات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'العملة' => 'USD', 'الوصف' => 'الدولار الأمريكي');
$records[] = array('id' => 2, 'العملة' => 'EUR', 'الوصف' => 'اليرة الأوروبية');
$records[] = array('id' => 3, 'العملة' => 'GBP', 'الوصف' => 'الجنيه الإسترليني');

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['العملة'], $search) !== false || strpos($record['الوصف'], $search) !== false;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);

Note: This code assumes that you have a database setup to store the records, and that you have a `create_العملات.php` and `edit_العملات.php` pages to handle adding and editing records, respectively.