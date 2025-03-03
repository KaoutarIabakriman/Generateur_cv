<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Log In</h2>
            <form action="../../Traitement/login_process.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Log In</button>
            </form>
            <p class="text-center mt-4">Don't have an account? <a href="signup.php" class="text-blue-500">Sign Up</a></p>
        </div>
    </div>
</body>
</html>