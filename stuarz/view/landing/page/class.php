<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-3xl mx-auto">
        <div class="bg-gray-800/80 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
            <div class="p-6 border-b border-gray-700 flex items-center justify-between">
                <h1 class="text-xl font-bold">Kelas</h1>
                <span class="text-sm text-gray-400">Pilih menu kelas</span>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="index.php?page=tugas" class="group rounded-xl border border-gray-700 bg-gray-800/60 hover:bg-gray-700/50 transition-colors p-5 flex flex-col items-start gap-3">
                    <span class="material-symbols-outlined text-blue-400 group-hover:text-blue-300">assignment</span>
                    <div>
                        <div class="font-medium">Tugas</div>
                        <div class="text-xs text-gray-400">Lihat dan kelola tugas</div>
                    </div>
                </a>
                <a href="index.php?page=absensi" class="group rounded-xl border border-gray-700 bg-gray-800/60 hover:bg-gray-700/50 transition-colors p-5 flex flex-col items-start gap-3">
                    <span class="material-symbols-outlined text-green-400 group-hover:text-green-300">event_available</span>
                    <div>
                        <div class="font-medium">Absensi</div>
                        <div class="text-xs text-gray-400">Catat kehadiran</div>
                    </div>
                </a>
                <a href="index.php?page=jadwal" class="group rounded-xl border border-gray-700 bg-gray-800/60 hover:bg-gray-700/50 transition-colors p-5 flex flex-col items-start gap-3">
                    <span class="material-symbols-outlined text-indigo-400 group-hover:text-indigo-300">calendar_month</span>
                    <div>
                        <div class="font-medium">Jadwal</div>
                        <div class="text-xs text-gray-400">Agenda kegiatan</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
