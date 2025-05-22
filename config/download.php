<?php
// config/download.php
// return [
//     'ytdlp_path' => 'C:\Users\DELL\AppData\Local\Microsoft\WindowsApps\python.exe -m yt_dlp',
//     'temp_path' => storage_path('app/downloads/temp'),
//     'output_format' => '%(title)s-%(id)s.%(ext)s',
// ];
return [
    'ytdlp_path' => storage_path('app/bin/yt-dlp.exe'), // Gunakan yt-dlp.exe langsung
    'temp_path' => storage_path('app/downloads/temp'),
    'output_format' => '%(title)s-%(id)s.%(ext)s',
];


