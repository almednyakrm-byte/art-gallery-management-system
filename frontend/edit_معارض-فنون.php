**edit_معارض-فنون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/معارض-فنون.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل معرض فنون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 mt-12">
        <h1 class="text-3xl font-bold text-emerald-600">تعديل معرض فنون</h1>
        <form id="edit-form" class="bg-white p-4 mt-4 rounded-lg shadow-md">
            <div class="grid grid-cols-1 gap-4">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                    <input type="text" id="title" name="title" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['title'] ?>">
                </div>
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                    <textarea id="description" name="description" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600"><?= $record['description'] ?></textarea>
                </div>
                <div class="col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700">الصورة</label>
                    <input type="file" id="image" name="image" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600">
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/معارض-فنون.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/معارض-فنون.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$record = get_record($id);

// Output JSON
echo json_encode($record);

function get_record($id) {
    // Your database query to fetch the record
    // For example:
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('SELECT * FROM معارض_فنون WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $record = $stmt->fetch();
    $db = null;
    return $record;
}
?>