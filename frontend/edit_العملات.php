**edit_العملات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/العملات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$mod_slug = 'العملات';
$form_title = 'تعديل ' . $mod_slug;
$form_action = '../backend/العملات.php';
$form_method = 'PUT';
$form_id = 'edit-' . $mod_slug;
$form_fields = [
    'name' => [
        'label' => 'اسم العملة',
        'value' => $data['name'],
        'type' => 'text',
        'class' => 'w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600',
    ],
    'symbol' => [
        'label' => 'رمز العملة',
        'value' => $data['symbol'],
        'type' => 'text',
        'class' => 'w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600',
    ],
];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $form_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-emerald-600 {
            background-color: #0d9488;
        }
        .text-teal-500 {
            color: #009688;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-center text-gray-700"><?= $form_title ?></h1>
        <form id="<?= $form_id ?>" method="<?= $form_method ?>" action="<?= $form_action ?>">
            <?php foreach ($form_fields as $field => $props) : ?>
                <div class="mb-4">
                    <label for="<?= $field ?>" class="block text-sm font-medium text-gray-700"><?= $props['label'] ?></label>
                    <input type="<?= $props['type'] ?>" id="<?= $field ?>" name="<?= $field ?>" value="<?= $props['value'] ?>" class="<?= $props['class'] ?>">
                </div>
            <?php endforeach; ?>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#<?= $form_id ?>').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '<?= $form_action ?>',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $mod_slug ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: This code assumes that the `../backend/العملات.php` file is responsible for handling the PUT request and returning a JSON response with a `success` property indicating whether the update was successful.