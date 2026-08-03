<?php
// edit_أعمال-فنية.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_أعمال-فنية.php');
    exit;
}

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل أعمال فنية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-900 text-indigo-500 rounded">
        <h2 class="text-2xl font-bold mb-4">تعديل أعمال فنية</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-indigo-500 bg-slate-800 rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 text-indigo-500 bg-slate-800 rounded"></textarea>
            </div>
            <button type="submit" class="w-full p-2 bg-indigo-500 text-slate-900 rounded">تعديل</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/أعمال-فنية.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/أعمال-فنية.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    title: formData.get('title'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_أعمال-فنية.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>