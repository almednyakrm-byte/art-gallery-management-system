**create_المبيعات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded shadow-md p-4">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مبيعات جديدة</h2>
        <form id="create-sale-form">
            <div class="mb-4">
                <label for="sale_date" class="block text-gray-700 text-sm font-bold mb-2">تاريخ المبيعات</label>
                <input type="date" id="sale_date" name="sale_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="sale_amount" class="block text-gray-700 text-sm font-bold mb-2">مبلغ المبيعات</label>
                <input type="number" id="sale_amount" name="sale_amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">اسم العميل</label>
                <input type="text" id="customer_name" name="customer_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">اسم المنتج</label>
                <input type="text" id="product_name" name="product_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-sale-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المبيعات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_المبيعات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/المبيعات.php**

<?php
// Database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['sale_date']) && isset($_POST['sale_amount']) && isset($_POST['customer_name']) && isset($_POST['product_name'])) {
    // Prepare SQL query
    $sale_date = $_POST['sale_date'];
    $sale_amount = $_POST['sale_amount'];
    $customer_name = $_POST['customer_name'];
    $product_name = $_POST['product_name'];

    $query = "INSERT INTO المبيعات (sale_date, sale_amount, customer_name, product_name) VALUES ('$sale_date', '$sale_amount', '$customer_name', '$product_name')";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>