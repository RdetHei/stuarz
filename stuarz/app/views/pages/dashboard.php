<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Sekolah - Educational Management System</title>
  <meta name="description" content="Modern educational dashboard with Discord and GitHub inspired design">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'discord-blurple': '#5865F2',
            'github-blue': '#58A6FF',
            'dark-bg': '#111827',
            'dark-card': '#1f2937',
            'dark-card-hover': '#374151',
            'dark-border': '#374151',
            'dark-text': '#f9fafb',
            'dark-muted': '#9ca3af',
          }
        }
      }
    }
  </script>
  
  <style>
    body {
      background-color: #111827;
      color: #f9fafb;
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Header -->
  <header class="border-b border-dark-border bg-dark-card/50 backdrop-blur-sm sticky top-0 z-10">
    <div class="container mx-auto px-6 py-4">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-discord-blurple to-github-blue bg-clip-text text-transparent">
        Dashboard Overview
      </h1>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-8 space-y-8">
    
    <!-- Stats Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
      
      <!-- Total Siswa -->
      <div class="bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-dark-muted mb-2">Total Siswa</p>
            <p class="text-3xl font-bold">120</p>
          </div>
          <svg class="h-12 w-12 text-discord-blurple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
      </div>

      <!-- Total Guru -->
      <div class="bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-dark-muted mb-2">Total Guru</p>
            <p class="text-3xl font-bold">15</p>
          </div>
          <svg class="h-12 w-12 text-github-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
          </svg>
        </div>
      </div>

      <!-- Tugas Selesai -->
      <div class="bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-dark-muted mb-2">Tugas Selesai</p>
            <p class="text-3xl font-bold">320</p>
          </div>
          <svg class="h-12 w-12 text-discord-blurple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>

      <!-- Absensi Hari Ini -->
      <div class="bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-dark-muted mb-2">Absensi Hari Ini</p>
            <p class="text-3xl font-bold">98%</p>
          </div>
          <svg class="h-12 w-12 text-github-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>

    </div>

    <!-- Chart & Announcements -->
    <div class="grid gap-6 lg:grid-cols-3">
      
      <!-- Chart Section -->
      <div class="lg:col-span-2 bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center gap-2 mb-6">
          <svg class="h-5 w-5 text-discord-blurple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
          <h2 class="text-xl font-bold">Statistik Absensi Mingguan</h2>
        </div>
        
        <!-- Simple Bar Chart -->
        <div class="space-y-4">
          <!-- Senin -->
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-dark-muted">Senin</span>
              <span class="font-semibold">95%</span>
            </div>
            <div class="h-3 bg-dark-card-hover rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-discord-blurple to-github-blue rounded-full" style="width: 95%"></div>
            </div>
          </div>

          <!-- Selasa -->
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-dark-muted">Selasa</span>
              <span class="font-semibold">97%</span>
            </div>
            <div class="h-3 bg-dark-card-hover rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-discord-blurple to-github-blue rounded-full" style="width: 97%"></div>
            </div>
          </div>

          <!-- Rabu -->
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-dark-muted">Rabu</span>
              <span class="font-semibold">92%</span>
            </div>
            <div class="h-3 bg-dark-card-hover rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-discord-blurple to-github-blue rounded-full" style="width: 92%"></div>
            </div>
          </div>

          <!-- Kamis -->
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-dark-muted">Kamis</span>
              <span class="font-semibold">94%</span>
            </div>
            <div class="h-3 bg-dark-card-hover rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-discord-blurple to-github-blue rounded-full" style="width: 94%"></div>
            </div>
          </div>

          <!-- Jumat -->
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-dark-muted">Jumat</span>
              <span class="font-semibold">98%</span>
            </div>
            <div class="h-3 bg-dark-card-hover rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-discord-blurple to-github-blue rounded-full" style="width: 98%"></div>
            </div>
          </div>
        </div>

        <!-- Average -->
        <div class="mt-6 pt-6 border-t border-dark-border">
          <div class="flex items-center justify-between">
            <span class="text-dark-muted">Rata-rata Absensi</span>
            <span class="text-2xl font-bold text-discord-blurple">95.2%</span>
          </div>
        </div>
      </div>

      <!-- Announcements -->
      <div class="bg-dark-card border border-dark-border p-6 rounded-xl">
        <div class="flex items-center gap-2 mb-6">
          <svg class="h-5 w-5 text-github-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <h2 class="text-xl font-bold">Pengumuman Terbaru</h2>
        </div>
        
        <ul class="space-y-3">
          <li class="p-4 bg-dark-card-hover rounded-lg">
            <p class="text-sm">📝 Ujian Matematika minggu depan</p>
          </li>
          <li class="p-4 bg-dark-card-hover rounded-lg">
            <p class="text-sm">🎉 Libur Nasional tanggal 20</p>
          </li>
          <li class="p-4 bg-dark-card-hover rounded-lg">
            <p class="text-sm">📚 Pengumpulan tugas PPKN hari Jumat</p>
          </li>
        </ul>

        <button class="mt-6 w-full py-3 bg-discord-blurple text-white font-semibold rounded-lg">
          Lihat Semua
        </button>
      </div>

    </div>

    <!-- Quick Actions -->
    <div class="grid gap-6 md:grid-cols-3">
      
      <div class="bg-gradient-to-br from-discord-blurple/10 to-discord-blurple/5 border border-discord-blurple/20 p-6 rounded-xl">
        <h3 class="text-lg font-bold mb-2">Tambah Siswa Baru</h3>
        <p class="text-sm text-dark-muted">Daftarkan siswa baru ke sistem</p>
      </div>
      
      <div class="bg-gradient-to-br from-github-blue/10 to-github-blue/5 border border-github-blue/20 p-6 rounded-xl">
        <h3 class="text-lg font-bold mb-2">Input Nilai</h3>
        <p class="text-sm text-dark-muted">Masukkan nilai siswa</p>
      </div>
      
      <div class="bg-gradient-to-br from-discord-blurple/10 to-github-blue/5 border border-dark-border p-6 rounded-xl">
        <h3 class="text-lg font-bold mb-2">Laporan Bulanan</h3>
        <p class="text-sm text-dark-muted">Unduh laporan lengkap</p>
      </div>

    </div>

  </main>

</body>
</html>
