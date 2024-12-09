<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Include Tailwind CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[name="code[]"]');
            inputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    if (Array.from(inputs).every(input => input.value.length === 1)) {
                        submitForm();
                    }
                });
            });

            function submitForm() {
                const code = Array.from(inputs).map(input => input.value).join('');
                fetch('verify_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ code: code })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                });
            }
        });
    </script>
</head>
<body class="bg-gradient-to-r from-green-200 to-blue-200 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Email Verification</h2>
        <form id="verificationForm" class="space-y-6">
            <label for="code" class="block text-gray-700 text-center">Enter your 5-digit verification code:</label>
            <div class="flex space-x-2 justify-center">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <input type="text" name="code[]" maxlength="1" size="1" required class="w-12 h-12 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
        </form>
    </div>
</body>
</html>