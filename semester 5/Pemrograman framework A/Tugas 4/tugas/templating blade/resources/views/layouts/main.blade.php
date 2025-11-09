<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Tugas Laravel</title>
    <style>
        body { font-family: sans-serif; }
        .container { padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { color: white; padding: 4px 8px; text-align: center; border-radius: 5px; }
        .badge-hijau { background-color: #28a745; }
        .badge-abu { background-color: #6c757d; }
    </style>
</head>
<body>

    <div class="container">
        @include('layouts.header')

        <hr>

        <main>
            @yield('content')
        </main>

        <hr>
        
        @include('layouts.footer')
    </div>

</body>
</html>