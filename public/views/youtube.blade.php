<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Downloader - Home</title>
    <link rel="stylesheet" href="{{ asset('resources/css/styleku.css') }}">
</head>
<body>
    <header>
        <h1>Selamat Datang di Video Downloader</h1>
        <nav>
            <a href="/youtube"><button class="active">Youtube</button></a>
            <a href="/instagram"><button>Instagram</button></a>
            <a href="/tiktok"><button>Tiktok</button></a>
        </nav>
    </header>
    <div class="content-type">
      <a href="/short"><button>Short</button></a>
    </div>
    <main>
        <h2>Masukkan URL video kamu</h2>
        <input type="text" id="videoUrl" placeholder="Tempel URL video di sini">
        <button>📋 Tempel</button>
        <div class="download-buttons">
            <button onclick="downloadmp4()">Download Mp4</button>
        </div>
    </main>
    <script src="{{ asset('resources/js/scriptku.js') }}"></script>
</body>
</html>