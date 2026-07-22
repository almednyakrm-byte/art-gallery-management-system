<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-emerald-600 flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-1/2">
        <h2 class="text-3xl text-teal-500 mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="password-error"></div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
        <div class="text-green-500 text-xs" id="success-message"></div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validation
            let isValid = true;
            if (username.length < 3) {
                document.getElementById('username-error').innerText = 'Username must be at least 3 characters long';
                isValid = false;
            } else {
                document.getElementById('username-error').innerText = '';
            }

            if (!email.includes('@')) {
                document.getElementById('email-error').innerText = 'Invalid email address';
                isValid = false;
            } else {
                document.getElementById('email-error').innerText = '';
            }

            if (password.length < 8) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters long';
                isValid = false;
            } else {
                document.getElementById('password-error').innerText = '';
            }

            if (!isValid) return;

            // AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../backend/auth.php?action=register', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('success-message').innerText = 'Registration successful!';
                    } else {
                        document.getElementById('success-message').innerText = 'Registration failed: ' + response.message;
                    }
                } else {
                    document.getElementById('success-message').innerText = 'Error: ' + xhr.statusText;
                }
            };
            xhr.send('username=' + username + '&email=' + email + '&password=' + password);
        });
    </script>
</body>
</html>