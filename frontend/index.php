<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة معرض فني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">نظام إدارة معرض فني</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً <?php echo $_SESSION['username']; ?></h2>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">العملات</h3>
                    <p id="currencies-count" class="text-lg text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">المشتريين</h3>
                    <p id="buyers-count" class="text-lg text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">المبيعات</h3>
                    <p id="sales-count" class="text-lg text-gray-600"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">روابط سريعة</h2>
            <ul class="list-none mb-0">
                <li class="mb-2">
                    <a href="currencies.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">العملات</a>
                </li>
                <li class="mb-2">
                    <a href="buyers.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">المشتريين</a>
                </li>
                <li class="mb-2">
                    <a href="sales.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">المبيعات</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('currencies-count').textContent = data.currenciesCount;
                document.getElementById('buyers-count').textContent = data.buyersCount;
                document.getElementById('sales-count').textContent = data.salesCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes that you have a PHP file named `api.php` that handles API requests and returns JSON data. The `api.php` file should be in the same directory as this file. The `api.php` file should contain the following code:


<?php
header('Content-Type: application/json');

// Replace the following data with your actual database data
$currenciesCount = 10;
$buyersCount = 20;
$salesCount = 30;

echo json_encode(['currenciesCount' => $currenciesCount, 'buyersCount' => $buyersCount, 'salesCount' => $salesCount]);
?>


This code will fetch the stats from the `api.php` file and display them on the dashboard.