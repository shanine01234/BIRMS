<?php
session_start();

// Retrieve any errors or success messages from the session
$errors = $_SESSION['signup_errors'] ?? [];
$registration_success = $_SESSION['registration_success'] ?? '';

// Clear session messages
unset($_SESSION['signup_errors'], $_SESSION['registration_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Create an Account</h2>
        
        <?php if (!empty($registration_success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($registration_success); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors['registration'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($errors['registration']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="create_account.php" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo htmlspecialchars($name); ?>"
                    required 
                    class="w-full px-3 py-2 border <?php echo isset($errors['name']) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your full name"
                >
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['name']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                <input 
                    type="tel" 
                    id="contact" 
                    name="contact" 
                    value="<?php echo htmlspecialchars($contact); ?>"
                    required 
                    class="w-full px-3 py-2 border <?php echo isset($errors['contact']) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter 10-digit mobile number"
                >
                <?php if (isset($errors['contact'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['contact']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($email); ?>"
                    required 
                    class="w-full px-3 py-2 border <?php echo isset($errors['email']) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="you@example.com"
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['email']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full px-3 py-2 border <?php echo isset($errors['password']) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Create a strong password"
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['password']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required 
                    class="w-full px-3 py-2 border <?php echo isset($errors['confirm_password']) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Repeat your password"
                >
                <?php if (isset($errors['confirm_password'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['confirm_password']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the 
                        <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>
                    </label>
                </div>
                <?php if (isset($errors['terms'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['terms']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300"
                >
                    Sign Up
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="login.php" class="text-blue-500 hover:underline">Login</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>