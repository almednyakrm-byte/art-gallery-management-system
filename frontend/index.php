<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة معارض الفن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 h-screen">
    <header class="bg-amber-400 text-gray-200 p-4 flex justify-between">
        <h1 class="text-2xl font-bold">نظام إدارة معارض الفن</h1>
        <button class="bg-gray-200 text-amber-400 py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="glassmorphism-card bg-white rounded-2xl p-4">
                <h2 class="text-2xl font-bold mb-2">مرحباً!</h2>
                <p class="text-lg">نظام إدارة معارض الفن هو نظام لإدارة الأعمال الفنية.</p>
            </div>
            <div class="glassmorphism-card bg-white rounded-2xl p-4">
                <h2 class="text-2xl font-bold mb-2">إحصائيات عامة</h2>
                <div id="stats" class="text-lg"></div>
            </div>
            <div class="glassmorphism-card bg-white rounded-2xl p-4">
                <h2 class="text-2xl font-bold mb-2">روابط سريعة</h2>
                <ul class="text-lg">
                    <li class="mb-2"><a href="artists.php" class="text-amber-400 hover:text-gray-200">إدارة الفنانين</a></li>
                    <li class="mb-2"><a href="artworks.php" class="text-amber-400 hover:text-gray-200">إدارة الأعمال الفنية</a></li>
                    <li class="mb-2"><a href="exhibitions.php" class="text-amber-400 hover:text-gray-200">إدارة المعارض</a></li>
                </ul>
            </div>
        </section>
    </main>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                const statsHtml = `
                    <p>عدد الفنانين: ${data.artistsCount}</p>
                    <p>عدد الأعمال الفنية: ${data.artworksCount}</p>
                    <p>عدد المعارض: ${data.exhibitionsCount}</p>
                `;
                document.getElementById('stats').innerHTML = statsHtml;
            })
            .catch(error => console.error('Error fetching stats:', error));

        // Logout function
        function logout() {
            fetch('api/logout.php')
                .then(() => window.location.href = 'login.php')
                .catch(error => console.error('Error logging out:', error));
        }
    </script>

    <style>
        .glassmorphism-card {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
    </style>
</body>
</html>