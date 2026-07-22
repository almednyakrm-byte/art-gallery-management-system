**edit_معارض.php**

<?php
// Session validation
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/معارض.php?id=' . $id), true);

// Set page title and mod slug
$pageTitle = 'تعديل معارض';
$modSlug = 'معارض';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">الوصف</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $existingRecord['description'] ?></textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">الصورة</label>
            <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/معارض.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Form submission
    document.getElementById('edit-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(document.getElementById('edit-form'));
        fetch('../backend/معارض.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + '<?= $modSlug ?>' + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/معارض.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = get_record($id);

// Return JSON response
echo json_encode($existingRecord);

// Function to get record details
function get_record($id) {
    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "SELECT * FROM المعارض WHERE id = '$id'";

    // Execute query
    $result = $conn->query($sql);

    // Fetch record details
    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
        return $record;
    } else {
        return array();
    }

    // Close connection
    $conn->close();
}
?>


**backend/edit.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if form data is set
if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_FILES['image'])) {
    http_response_code(400);
    exit;
}

// Get form data
$name = $_POST['name'];
$description = $_POST['description'];
$image = $_FILES['image'];

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "UPDATE المعارض SET name = '$name', description = '$description' WHERE id = '$id'";

// Execute query
if ($conn->query($sql) === TRUE) {
    // Upload image
    if (!empty($image['name'])) {
        $imagePath = 'path/to/image/' . $image['name'];
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

    // Return JSON response
    echo json_encode(array('success' => true));
} else {
    // Return JSON response
    echo json_encode(array('success' => false, 'error' => $conn->error));
}

// Close connection
$conn->close();
?>