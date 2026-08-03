<?php
// Start the session
session_start();

// Validate session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require_once '../backend/db.php';

// Define the module slug
$mod_slug = 'exhibitions';

// Define the page title
$page_title = 'Create Exhibition';

// Include the header file
require_once 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Create Exhibition</h3>
                <p class="mt-1 text-sm text-gray-600">Create a new exhibition record.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-exhibition-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="title" id="title" class="focus:ring-amber-400 focus:border-amber-400 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-200" placeholder="Exhibition title">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-amber-400 focus:border-amber-400 mt-1 block w-full sm:text-sm border border-gray-200 rounded-md" placeholder="Exhibition description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="date" name="start_date" id="start_date" class="focus:ring-amber-400 focus:border-amber-400 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-200">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="date" name="end_date" id="end_date" class="focus:ring-amber-400 focus:border-amber-400 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-200">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="location" id="location" class="focus:ring-amber-400 focus:border-amber-400 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-200" placeholder="Exhibition location">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-200 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-400 hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400">Create Exhibition</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-exhibition-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/exhibitions.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include the footer file
require_once 'footer.php';
?>