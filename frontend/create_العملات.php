<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Module slug
$mod_slug = 'العملات';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
require_once 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-emerald-600"><?= $page_title ?></h1>
    <form id="create-form" class="mt-6">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                    Currency Name
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="currency_name" type="text" placeholder="Currency Name">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    Currency Code
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="currency_code" type="text" placeholder="Currency Code">
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                    Exchange Rate
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="exchange_rate" type="number" step="any" placeholder="Exchange Rate">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    Status
                </label>
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Create
        </button>
    </form>
</div>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = {
                'currency_name': $('#currency_name').val(),
                'currency_code': $('#currency_code').val(),
                'exchange_rate': $('#exchange_rate').val(),
                'status': $('#status').val()
            };
            $.ajax({
                type: 'POST',
                url: '../backend/العملات.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_العملات.php';
                }
            });
        });
    });
</script>