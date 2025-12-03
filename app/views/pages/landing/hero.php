<!-- Hero Section with Discord & GitHub Theme -->
<div class="relative bg-gray-900 overflow-hidden min-h-screen flex items-center">
  <div>
    <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/default-banner.png', ENT_QUOTES, 'UTF-8') ?>" alt="Background Hero" class="absolute inset-0 w-full h-full object-cover opacity-20 pointer-events-none select-none" />
  </div>
  <div class="relative w-full px-6 py-24 sm:py-32 lg:px-8 lg:py-5">
    <div class="w-full text-center">
      <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 bg-gray-800 border border-gray-700 rounded-full">
        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
        <span class="text-gray-300 text-sm font-medium">50K+ Active Users</span>
      </div>
      <h1 class="text-5xl font-bold tracking-tight text-white sm:text-6xl">
        Tingkatkan pengalaman belajar Anda bersama Stuarz
      </h1>
      <p class="mt-6 text-lg leading-8 text-gray-400">
        Stuarz memudahkan siswa untuk mendapatkan ilmu, mengirim tugas, dan absensi. Guru dan sekolah juga dapat bergabung untuk mendukung proses pendidikan yang lebih baik.
      </p>
      <div class="mt-10 flex items-center justify-center gap-x-6">
        <a href="index.php?page=login" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:scale-[1.02]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          Mulai Sekarang
        </a>
        <a href="index.php?page=docs" class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-700 rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
          Pelajari lebih lanjut
        </a>
      </div>
    </div>
    </div>
</div>

    <div class="h-px bg-gray-700"></div>
    
<div class="bg-gray-900 min-h-screen">
  <div class="relative isolate overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 -z-10">
      <div class="absolute inset-0 bg-gradient-to-b from-[#5865F2]/5 via-transparent to-transparent"></div>
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(88, 101, 242, 0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="w-full px-6 py-16 sm:py-24 lg:px-8">
      <!-- Hero Section -->
      <div class="text-center mb-16">
        <div class="mb-6">
          <span class="inline-block px-4 py-2 bg-[#5865F2]/10 text-[#5865F2] border border-[#5865F2]/20 rounded-full text-sm font-semibold uppercase tracking-wider">
            Stuarz Platform
          </span>
        </div>
        <h1 class="text-5xl sm:text-6xl font-bold text-gray-100 mb-6 leading-tight">
          Alur kerja yang lebih mudah<br/>dan terintegrasi
        </h1>
        <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
          Stuarz dirancang untuk mempermudah manajemen pembelajaran, kolaborasi, dan pengelolaan proyek. Semua fitur yang kamu butuhkan tersedia dalam satu tempat.
        </p>
      </div>

      <!-- App Screenshot -->
      <div class="w-full mb-24">
        <div class="relative group">
          <div class="absolute -inset-4 bg-gradient-to-r from-[#5865F2]/20 to-[#4752C4]/20 rounded-2xl blur-2xl group-hover:blur-3xl transition-all duration-500 opacity-50"></div>
          <div class="relative overflow-hidden rounded-xl border border-gray-700 bg-[#1f2937] shadow-2xl">
            <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES, 'UTF-8') ?>" 
                 alt="Stuarz App Screenshot" 
                 class="w-full h-auto transition-transform duration-700 group-hover:scale-[1.02]" />
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-24">
        <div class="bg-[#1f2937] border border-gray-700 rounded-xl p-8 hover:border-gray-600 transition-colors">
          <h2 class="text-3xl font-bold text-gray-100 mb-6">Kenapa Stuarz?</h2>
          <p class="text-gray-400 leading-relaxed mb-8">
            Dengan Stuarz, aktivitas belajar, penyimpanan dokumen, hingga monitoring progress dapat dilakukan dengan cepat dan efisien. Dibuat untuk mendukung gaya kerja modern dan kebutuhan pendidikan maupun organisasi.
          </p>

          <div class="space-y-6">
            <div class="flex gap-4">
              <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20">
                <svg class="w-6 h-6 text-[#5865F2]" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5.5 17a4.5 4.5 0 0 1-1.44-8.765 4.5 4.5 0 0 1 8.302-3.046 3.5 3.5 0 0 1 4.504 4.272A4 4 0 0 1 15 17H5.5Zm3.75-2.75a.75.75 0 0 0 1.5 0V9.66l1.95 2.1a.75.75 0 1 0 1.1-1.02l-3.25-3.5a.75.75 0 0 0-1.1 0l-3.25 3.5a.75.75 0 1 0 1.1 1.02l1.95-2.1v4.59Z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-2">Mudah digunakan</h3>
                <p class="text-sm text-gray-400">Antarmuka yang sederhana, sehingga pengguna baru pun bisa langsung beradaptasi.</p>
              </div>
            </div>

            <div class="flex gap-4">
              <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-2">Keamanan terjamin</h3>
                <p class="text-sm text-gray-400">Data tersimpan aman dengan sistem enkripsi dan backup reguler.</p>
              </div>
            </div>

            <div class="flex gap-4">
              <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4.632 3.533A2 2 0 0 1 6.577 2h6.846a2 2 0 0 1 1.945 1.533l1.976 8.234A3.489 3.489 0 0 0 16 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234Z" />
                  <path d="M4 13a2 2 0 1 0 0 4h12a2 2 0 1 0 0-4H4Zm11.24 2a.75.75 0 0 1 .75-.75H16a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75h-.01a.75.75 0 0 1-.75-.75V15Zm-2.25-.75a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75h-.01Z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-2">Kolaborasi lebih baik</h3>
                <p class="text-sm text-gray-400">Saling terhubung dengan tim, siswa, atau rekan kerja tanpa hambatan.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-8">
          <div class="bg-[#1f2937] border border-gray-700 rounded-xl p-8 hover:border-gray-600 transition-colors">
            <h2 class="text-3xl font-bold text-gray-100 mb-4">Belajar & bekerja tanpa ribet</h2>
            <p class="text-gray-400 leading-relaxed mb-6">
              Dengan Stuarz, tidak perlu server rumit atau setup berlebihan. Cukup login, atur, dan mulai bekerja. Semua fitur sudah siap mendukung kebutuhanmu setiap hari.
            </p>
            <p class="text-gray-400 leading-relaxed">
              Stuarz bukan hanya alat, tapi solusi lengkap untuk mendukung perkembangan belajar, produktivitas tim, dan pengelolaan proyek.
            </p>
          </div>

            <div class="bg-[#1f2937] border border-gray-700 rounded-xl p-8 hover:border-gray-600 transition-colors">
            <h2 class="text-3xl font-bold text-gray-100 mb-4">Efisiensi kerja tanpa batas</h2>
            <p class="text-gray-400 leading-relaxed mb-6">
              Dengan Stuarz, semua proses kerja menjadi lebih cepat dan efisien. Fitur otomatisasi dan integrasi yang canggih membantu mengurangi waktu yang dihabiskan untuk tugas-tugas rutin.
            </p>
            <p class="text-gray-400 leading-relaxed">
              Ini merupakan langkah besar menuju produktivitas yang lebih baik.
            </p>
          </div>
      
        </div>
      </div>

    <!-- Hero Illustration -->
     
<div class="bg-gray-900 py-24">
  <div class="text-center w-full px-6">
    <h2 class="text-base font-semibold leading-7 text-indigo-600">Dashboard</h2>
    <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
      Overview
    </p>
  </div>

  <div class="mt-16 w-full overflow-hidden relative">

    <!-- Container yang bisa pause -->
    <div id="slider-container" class="overflow-hidden">

      <!-- Track bergerak -->
      <div id="slider-track" class="flex gap-8 animate-slide">

        <!-- SET 1 -->
        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">
    <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">

        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">

        <!-- SET 2 (CLONE untuk infinite loop) -->
        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">

        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">

        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES) ?>"
             class="w-[420px] h-[280px] object-cover rounded-2xl border border-gray-700 shadow-lg">

      </div>
    </div>

  </div>
</div>

<style>
@keyframes slide {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.animate-slide {
  animation: slide 22s linear infinite;
}

/* Ketika pause ditambahkan dari JS */
.paused {
  animation-play-state: paused !important;
}
</style>

<script>
const sliderTrack = document.getElementById("slider-track");
const sliderContainer = document.getElementById("slider-container");

// Pause ketika hover
sliderContainer.addEventListener("mouseenter", () => {
  sliderTrack.classList.add("paused");
});

// Resume ketika kursor keluar
sliderContainer.addEventListener("mouseleave", () => {
  sliderTrack.classList.remove("paused");
});
</script>




 
<!-- Divider -->
<div class="h-px bg-gray-700"></div>

<!-- Features Section -->
<div class="relative bg-gray-900 py-24 sm:py-32">
  <div class="w-full px-6 lg:px-8">
    <div class="text-center">
      <h2 class="text-base font-semibold leading-7 text-indigo-600">Features</h2>
      <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
        Semua yang Anda butuhkan untuk pendidikan digital
      </p>
    </div>
    <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Feature 1 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-indigo-600/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Akses Mobile</h3>
        <p class="text-gray-400">Stuarz dapat diakses dari perangkat apapun, memudahkan siswa dan guru belajar dan mengajar di mana saja.</p>
      </div>
      
      <!-- Feature 2 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Kinerja Cepat</h3>
        <p class="text-gray-400">Proses belajar, pengiriman tugas, dan absensi berlangsung cepat dan efisien.</p>
      </div>
      
      <!-- Feature 3 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-yellow-500/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Keamanan Data</h3>
        <p class="text-gray-400">Data siswa, guru, dan sekolah terlindungi dengan sistem keamanan Stuarz.</p>
      </div>
      
      <!-- Feature 4 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-purple-500/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">API Terintegrasi</h3>
        <p class="text-gray-400">Stuarz menyediakan API untuk integrasi dengan sistem sekolah dan aplikasi lain.</p>
      </div>
      
      <!-- Feature 5 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-red-500/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Sertifikat Digital</h3>
        <p class="text-gray-400">Sekolah dapat memberikan sertifikat digital kepada siswa yang telah menyelesaikan tugas.</p>
      </div>
      
      <!-- Feature 6 -->
      <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
        <div class="w-14 h-14 bg-indigo-600/20 rounded-xl flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Absensi Otomatis</h3>
        <p class="text-gray-400">Siswa dan guru dapat melakukan absensi secara otomatis dan terintegrasi dengan sistem sekolah.</p>
      </div>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gray-700"></div>

<!-- How It Works Section -->
<div class="relative bg-gray-900 py-24 sm:py-32">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:text-center">
      <h2 class="text-base font-semibold leading-7 text-indigo-600">How It Works</h2>
      <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
        Benefit untuk siswa, guru, dan sekolah
      </p>
      <p class="mt-6 text-lg leading-8 text-gray-400">
        Stuarz membantu siswa, guru, dan sekolah dalam proses belajar, pengiriman tugas, dan absensi secara digital dengan mudah dan aman.
      </p>
    </div>
    <div class="w-full mt-16 sm:mt-20 lg:mt-24">
      <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
        <!-- Step 1 -->
        <div class="relative pl-16 group">
          <dt class="text-base font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-600 shadow-lg">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" class="size-6 text-white">
                <path d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Kirim Tugas Mudah
          </dt>
          <dd class="mt-2 text-base text-gray-400">
            Siswa dapat mengirim tugas langsung melalui aplikasi, guru dapat memeriksa dan memberi nilai secara digital.
          </dd>
        </div>
        <!-- Step 2 -->
        <div class="relative pl-16 group">
          <dt class="text-base font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-600 shadow-lg">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" class="size-6 text-white">
                <path d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Sertifikat Digital
          </dt>
          <dd class="mt-2 text-base text-gray-400">
            Sekolah dapat memberikan sertifikat digital kepada siswa yang telah menyelesaikan tugas dan absensi dengan baik.
          </dd>
        </div>
        <!-- Step 3 -->
        <div class="relative pl-16 group">
          <dt class="text-base font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-600 shadow-lg">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" class="size-6 text-white">
                <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Absensi Otomatis
          </dt>
          <dd class="mt-2 text-base text-gray-400">
            Siswa dan guru dapat melakukan absensi secara otomatis dan terintegrasi dengan sistem sekolah.
          </dd>
        </div>
        <!-- Step 4 -->
        <div class="relative pl-16 group">
          <dt class="text-base font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-600 shadow-lg">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" class="size-6 text-white">
                <path d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Keamanan Terjamin
          </dt>
          <dd class="mt-2 text-base text-gray-400">
            Data pendidikan Anda aman bersama Stuarz, didukung sistem keamanan modern.
          </dd>
        </div>
      </dl>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gray-700"></div>

<!-- Stats Section -->
<div class="relative bg-gray-800 py-24 sm:py-32 overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0">
    <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/default-banner.png', ENT_QUOTES, 'UTF-8') ?>" alt="" class="h-full w-full object-cover opacity-20" />
  </div>
  <!-- Overlay untuk readability -->
  <div class="absolute inset-0 bg-gray-900/60"></div>
  
  <!-- Content -->
  <div class="relative w-full px-6 lg:px-8">
    <div class="w-full lg:mx-0">
      <h2 class="text-5xl font-semibold tracking-tight text-white sm:text-6xl">Bergabung bersama Stuarz</h2>
      <p class="mt-6 text-lg leading-8 text-gray-300">
        Stuarz mengajak siswa, guru, dan sekolah untuk berkolaborasi dalam dunia pendidikan digital. Bersama kami, proses belajar menjadi lebih mudah dan menyenangkan.
      </p>
    </div>
    <dl class="w-full mt-16 grid grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 lg:grid-cols-4">
      <div class="flex flex-col-reverse gap-2 p-6 bg-gray-900 border border-gray-700 rounded-2xl backdrop-blur-sm">
        <dt class="text-base text-gray-400">Sekolah bergabung</dt>
        <dd class="text-4xl font-bold tracking-tight text-white">12</dd>
      </div>
      <div class="flex flex-col-reverse gap-2 p-6 bg-gray-900 border border-gray-700 rounded-2xl backdrop-blur-sm">
        <dt class="text-base text-gray-400">Guru aktif</dt>
        <dd class="text-4xl font-bold tracking-tight text-white">300+</dd>
      </div>
      <div class="flex flex-col-reverse gap-2 p-6 bg-gray-900 border border-gray-700 rounded-2xl backdrop-blur-sm">
        <dt class="text-base text-gray-400">Jam belajar per minggu</dt>
        <dd class="text-4xl font-bold tracking-tight text-white">40</dd>
      </div>
      <div class="flex flex-col-reverse gap-2 p-6 bg-gray-900 border border-gray-700 rounded-2xl backdrop-blur-sm">
        <dt class="text-base text-gray-400">Access for study</dt>
        <dd class="text-4xl font-bold tracking-tight text-white">∞</dd>
      </div>
    </dl>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gray-700"></div>

<!-- CTA Section -->
<div class="relative bg-gray-900 py-24 sm:py-32">
  <div class="w-full px-6 lg:px-8">
    <div class="w-full text-center">
      <h2 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Siap untuk memulai?</h2>
      <p class="mt-6 text-lg leading-8 text-gray-400">
        Bergabunglah dengan ribuan siswa dan guru yang telah memilih Stuarz untuk pendidikan digital yang lebih baik.
      </p>
      <div class="mt-10 flex items-center justify-center gap-x-6">
        <a href="index.php?page=register" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:scale-[1.02]">
          Daftar Sekarang
        </a>
        <a href="index.php?page=login" class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-700 rounded-lg font-medium transition-all duration-200">
          Masuk
        </a>
      </div>
    </div>
  </div>
</div>