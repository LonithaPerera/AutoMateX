<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline — AutoMateX</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center p-8">
        <div class="text-6xl mb-4">🚗</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">You're Offline</h1>
        <p class="text-gray-500 mb-6">
            AutoMateX needs a connection for this page.<br>
            Please check your internet and try again.
        </p>
        <button onclick="window.location.reload()"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            🔄 Try Again
        </button>
    </div>
</body>
</html>