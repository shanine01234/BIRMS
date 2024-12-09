<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Include Tailwind CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-200 to-blue-200 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Email Verification</h2>
        <form action="verify_gmail.php" method="POST" class="space-y-6">
            <label for="code" class="block text-gray-700 text-center">Enter your 5-digit verification code:</label>
            <div class="flex space-x-2 justify-center">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div class="text-center">
                <input type="submit" value="Verify" class="mt-4 bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
        </form>
    </div>
</body>
</html>