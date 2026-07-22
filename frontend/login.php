<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-emerald-600 to-teal-500 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="glassmorphic-card w-96 p-8 bg-white/20 backdrop-blur-md rounded-md shadow-lg">
            <h2 class="text-2xl font-bold text-emerald-600 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Login</button>
                <p class="text-gray-700 text-sm mt-2">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-700">Register</a></p>
            </form>
        </div>
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
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });

        document.getElementById('username').addEventListener('input', () => {
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                document.getElementById('username-error').classList.remove('hidden');
                document.getElementById('username-error').textContent = 'Username must be at least 3 characters long';
            } else {
                document.getElementById('username-error').classList.add('hidden');
            }
        });

        document.getElementById('password').addEventListener('input', () => {
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                document.getElementById('password-error').classList.remove('hidden');
                document.getElementById('password-error').textContent = 'Password must be at least 8 characters long';
            } else {
                document.getElementById('password-error').classList.add('hidden');
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It also includes a form for username and password input, along with validation rules using standard HTML input pattern validators. The form is submitted using AJAX with the Fetch API, and the response or error is handled dynamically using JavaScript. The code also includes a direct link to the register page.