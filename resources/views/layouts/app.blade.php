
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAGGS Tracking Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4">
            <h1 class="text-3xl font-bold text-gray-900">TAGGS Tracking Test</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="flex gap-6">
            <div class="flex-1">
                @yield('content')
            </div>
            <div class="w-1/3">
                @yield('sidebar')
            </div>
        </div>
    </main>
</div>
</body>
</html>
