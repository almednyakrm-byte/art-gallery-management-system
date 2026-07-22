<?php
// edit_فنانين.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_فنانين.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فنان</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">تعديل فنان</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم الفنان</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="bio" class="block text-sm font-medium mb-2">السيرة الذاتية</label>
                <textarea id="bio" name="bio" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 rounded-lg hover:bg-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/فنانين.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('bio').value = data.bio;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/فنانين.php`, {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_فنانين.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>