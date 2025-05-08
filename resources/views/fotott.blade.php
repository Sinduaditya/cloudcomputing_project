<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Downloader - Home</title>
    <link rel="stylesheet" href="{{ asset('css/styleku.css') }}">
</head>
<body>
    <header>
        <h1>Selamat Datang di Video Downloader</h1>
        <nav>
            <a href="/index"><button>Youtube</button></a>
            <a href="/instagram"><button>Instagram</button></a>
            <a href="/tiktok"><button class="active">Tiktok</button></a>
        </nav>
    </header>
    <div class="content-type">
      <a href="/tiktok"><button>Video</button></a>
      <a href="/fotott"><button class="active">Foto</button></a>
    </div>
    <main>
        <h2>Masukkan URL video kamu</h2>
        <input type="text" id="videoUrl" placeholder="Tempel URL video di sini">
        <button>📋 Tempel</button>
        <div class="download-buttons">
            <button onclick="downloadmp3()">Download Mp3</button>
            <button onclick="downloadmp4()">Download Mp4</button>
        </div>
    </main>
    <script src="{{ asset('js/scriptku.js') }}"></script>
</body>
</html>