<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>403 - Akses Ditolak</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-primary">403</h1>
        <p class="mt-2 text-muted">{{ $exception->getMessage() ?: 'Akses ditolak.' }}</p>
        <a href="/" class="btn-primary mt-6 inline-flex">Kembali ke Beranda</a>
    </div>
</body>
</html>
