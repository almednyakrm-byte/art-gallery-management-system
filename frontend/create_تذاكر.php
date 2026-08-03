**create_تذاكر.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

// Include header and navigation
require_once '../includes/header.php';
require_once '../includes/navigation.php';
?>

<!-- Create تذاكر form -->
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create تذاكر</h2>
    <form id="create-tazker-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter title">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
            <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
            <input type="time" id="time" name="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" id="location" name="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter location">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create تذاكر</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-tazker-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/تذاكر.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = '../list_تذاكر.php';
                    } else {
                        alert('Error creating تذاكر');
                    }
                }
            });
        });
    });
</script>

Note: This code assumes that you have jQuery and Tailwind CSS installed in your project. Also, make sure to replace `../backend/تذاكر.php` with the actual URL of your backend script that handles the form submission.