<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'أعمال-فنية';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-slate-900">Create <?php echo $mod_slug; ?></h3>
                <p class="mt-1 text-sm text-slate-600">Create a new <?php echo $mod_slug; ?> record.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="title" id="title" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Title">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="artist" class="block text-sm font-medium text-slate-700">Artist</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="artist" id="artist" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Artist">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="year" class="block text-sm font-medium text-slate-700">Year</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="year" id="year" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Year">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="medium" class="block text-sm font-medium text-slate-700">Medium</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="medium" id="medium" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Medium">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/أعمال-فنية.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>