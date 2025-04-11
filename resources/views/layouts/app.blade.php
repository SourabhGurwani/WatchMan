<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchMan - Luxury Watches</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between">
            <a href="/" class="text-2xl font-bold text-indigo-600">WatchMan</a>
            <div class="flex space-x-4">
                <a href="{{ route('products.index') }}" class="hover:text-indigo-600">Shop</a>
                <a href="#" class="hover:text-indigo-600">Cart (0)</a>
            </div>
        </div>
    </nav>

   
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            © 2025 WatchMan. All rights reserved.
        </div>
    </footer>
</body>
</html>