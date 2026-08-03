<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-gray-200 flex justify-center items-center">
    <div class="glassmorphic-card bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-3xl shadow-2xl p-10 w-80">
        <h2 class="text-3xl text-amber-400 font-bold mb-4">Login</h2>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
        </form>
        <p class="text-gray-700 text-sm mt-4">Don't have an account? <a href="register.php" class="text-amber-400 hover:text-amber-500">Register</a></p>
        <div id="error-message" class="text-red-500 text-sm mt-4 hidden"></div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('error-message').innerHTML = data.message;
                    document.getElementById('error-message').classList.remove('hidden');
                }
            } catch (error) {
                document.getElementById('error-message').innerHTML = 'An error occurred: ' + error.message;
                document.getElementById('error-message').classList.remove('hidden');
            }
        });
    </script>
</body>
</html>