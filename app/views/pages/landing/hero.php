<!-- Hero Section with Discord & GitHub Theme -->
<div class="relative bg-gray-900 overflow-hidden">
  <!-- Animated Background Gradient -->
  <div class="absolute inset-0 bg-gradient-to-br from-[#7775D6]/10 via-gray-900 to-[#E935C1]/10 animate-gradient"></div>
  
  <!-- Radial Gradient Overlay -->
  <div class="absolute inset-0">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#7775D6]/20 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#E935C1]/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 1s;"></div>
  </div>

  <div class="relative mx-auto max-w-12xl py-1 sm:px-6 sm:py-8 lg:px-8">
    <div class="relative isolate overflow-hidden bg-gray-800/40 backdrop-blur-xl px-6 pt-16 ring-1 ring-white/10 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0 transition-all duration-300 hover:ring-[#7775D6]/40">
      <svg viewBox="0 0 1024 1024" aria-hidden="true" class="absolute top-1/2 left-1/2 -z-10 size-256 -translate-y-1/2 mask-[radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2 lg:translate-y-0 opacity-60">
        <circle r="512" cx="512" cy="512" fill="url(#discord-gradient)" fill-opacity="0.8" />
        <defs>
          <radialGradient id="discord-gradient">
            <stop stop-color="#7775D6" />
            <stop offset="1" stop-color="#E935C1" />
          </radialGradient>
        </defs>
      </svg>
      <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left animate-fade-in">
        <h2 class="text-4xl font-bold tracking-tight text-balance text-white sm:text-5xl leading-tight hover:text-transparent hover:bg-clip-text hover:bg-gradient-to-r hover:from-[#7775D6] hover:to-[#E935C1] transition-all duration-300">
          Tingkatkan pengalaman belajar Anda bersama Stuarz.
        </h2>
        <p class="mt-6 text-lg/8 text-pretty text-gray-300 animate-fade-in" style="animation-delay: 0.1s;">
          Stuarz memudahkan siswa untuk mendapatkan ilmu, mengirim tugas, dan absensi. Guru dan sekolah juga dapat bergabung untuk mendukung proses pendidikan yang lebih baik.
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-4 lg:justify-start animate-fade-in" style="animation-delay: 0.2s;">
          <a href="index.php?page=login" class="group relative rounded-lg bg-gradient-to-r from-[#7775D6] to-[#E935C1] px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-[#7775D6]/30 hover:shadow-2xl hover:shadow-[#7775D6]/50 transform hover:scale-105 transition-all duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#7775D6]">
            <span class="relative z-10">Mulai Sekarang</span>
            <div class="absolute inset-0 rounded-lg bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </a>
          <a href="#" class="group text-sm/6 font-semibold text-gray-300 hover:text-white transition-all duration-200 flex items-center gap-2">
            <span class="story-link">Pelajari lebih lanjut</span>
            <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform duration-200">→</span>
          </a>
        </div>
      </div>
      <div class="relative mt-16 h-80 lg:mt-8 animate-scale-in" style="animation-delay: 0.3s;">
        <img width="1824" height="1080" src="<?= htmlspecialchars(($prefix ?? '') . 'assets/apps.png', ENT_QUOTES, 'UTF-8') ?>" alt="Tampilan aplikasi Stuarz" class="absolute top-0 left-0 w-228 max-w-none rounded-xl bg-gray-800/50 ring-1 ring-[#7775D6]/40 shadow-2xl shadow-[#7775D6]/20 hover:ring-[#7775D6]/60 hover:shadow-[#7775D6]/40 transition-all duration-300 transform hover:scale-105" />
      </div>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gradient-to-r from-transparent via-[#7775D6]/50 to-transparent"></div>

<!-- Features Section -->
<div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-20">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#7775D6] rounded-full blur-[150px]"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-[#E935C1] rounded-full blur-[150px]"></div>
  </div>
  
  <div class="relative mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
    <div class="text-center animate-fade-in">
      <h2 class="inline-block text-sm font-semibold tracking-wide text-[#7775D6] uppercase px-4 py-2 bg-[#7775D6]/10 rounded-full ring-1 ring-[#7775D6]/20">Belajar Lebih Mudah</h2>
      <p class="mx-auto mt-6 max-w-2xl text-center text-4xl font-bold tracking-tight text-balance text-white sm:text-5xl animate-fade-in" style="animation-delay: 0.1s;">
        Semua yang Anda butuhkan untuk pendidikan digital
      </p>
    </div>
    <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
      <!-- Feature 1 -->
      <div class="relative lg:row-span-2 group animate-fade-in" style="animation-delay: 0.2s;">
        <div class="absolute inset-px rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 lg:rounded-l-4xl"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)] transform transition-all duration-300 group-hover:scale-[1.02]">
          <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
            <p class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center flex items-center gap-2 max-lg:justify-center">
              <span class="inline-block w-2 h-2 bg-gradient-to-r from-[#7775D6] to-[#E935C1] rounded-full animate-pulse"></span>
              Akses Mobile
            </p>
            <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">
              Stuarz dapat diakses dari perangkat apapun, memudahkan siswa dan guru belajar dan mengajar di mana saja.
            </p>
          </div>
          <div class="@container relative min-h-120 w-full grow max-lg:mx-auto max-lg:max-w-sm">
            <div class="absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-800 bg-gray-950 outline outline-[#7775D6]/30 shadow-xl shadow-[#7775D6]/10 group-hover:outline-[#7775D6]/50 transition-all duration-300">
              <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/mobile-apps.png', ENT_QUOTES, 'UTF-8') ?>" alt="" class="size-full object-cover object-top" />
            </div>
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-white/10 lg:rounded-l-4xl group-hover:outline-[#7775D6]/40 transition-all duration-300"></div>
      </div>
      <!-- Feature 2 -->
      <div class="relative max-lg:row-start-1 group animate-fade-in" style="animation-delay: 0.3s;">
        <div class="absolute inset-px rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 max-lg:rounded-t-4xl"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)] transform transition-all duration-300 group-hover:scale-[1.02]">
          <div class="px-8 pt-8 sm:px-10 sm:pt-10">
            <p class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center flex items-center gap-2 max-lg:justify-center">
              <span class="inline-block w-2 h-2 bg-gradient-to-r from-[#7775D6] to-[#E935C1] rounded-full animate-pulse"></span>
              Kinerja Cepat
            </p>
            <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">
              Proses belajar, pengiriman tugas, dan absensi berlangsung cepat dan efisien.
            </p>
          </div>
          <div class="flex flex-1 items-center justify-center px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
            <img src="https://tailwindcss.com/plus-assets/img/component-images/dark-bento-03-performance.png" alt="" class="w-full max-lg:max-w-xs transform transition-transform duration-300 group-hover:scale-110" />
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-white/10 max-lg:rounded-t-4xl group-hover:outline-[#7775D6]/40 transition-all duration-300"></div>
      </div>
      <!-- Feature 3 -->
      <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2 group animate-fade-in" style="animation-delay: 0.4s;">
        <div class="absolute inset-px rounded-lg bg-gradient-to-br from-gray-800 to-gray-900"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] transform transition-all duration-300 group-hover:scale-[1.02]">
          <div class="px-8 pt-8 sm:px-10 sm:pt-10">
            <p class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center flex items-center gap-2 max-lg:justify-center">
              <span class="inline-block w-2 h-2 bg-gradient-to-r from-[#7775D6] to-[#E935C1] rounded-full animate-pulse"></span>
              Keamanan Data
            </p>
            <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">
              Data siswa, guru, dan sekolah terlindungi dengan sistem keamanan Stuarz.
            </p>
          </div>
          <div class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2">
            <img src="https://tailwindcss.com/plus-assets/img/component-images/dark-bento-03-security.png" alt="" class="h-[min(152px,40cqw)] object-cover transform transition-transform duration-300 group-hover:scale-110" />
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-white/10 group-hover:outline-[#7775D6]/40 transition-all duration-300"></div>
      </div>
      <!-- Feature 4 -->
      <div class="relative lg:row-span-2 group animate-fade-in" style="animation-delay: 0.5s;">
        <div class="absolute inset-px rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 max-lg:rounded-b-4xl lg:rounded-r-4xl"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)] transform transition-all duration-300 group-hover:scale-[1.02]">
          <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
            <p class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center flex items-center gap-2 max-lg:justify-center">
              <span class="inline-block w-2 h-2 bg-gradient-to-r from-[#7775D6] to-[#E935C1] rounded-full animate-pulse"></span>
              API Terintegrasi
            </p>
            <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">
              Stuarz menyediakan API untuk integrasi dengan sistem sekolah dan aplikasi lain.
            </p>
          </div>
          <div class="relative min-h-120 w-full grow">
            <div class="absolute top-10 right-0 bottom-0 left-10 overflow-hidden rounded-tl-xl bg-gray-950/90 outline outline-[#7775D6]/30 shadow-xl shadow-[#7775D6]/10 group-hover:outline-[#7775D6]/50 transition-all duration-300">
              <div class="flex bg-gray-950 outline outline-[#7775D6]/20">
                <div class="-mb-px flex text-sm/6 font-medium text-gray-400">
                  <div class="border-r border-b border-r-[#7775D6]/30 border-b-[#7775D6]/40 bg-gradient-to-r from-[#7775D6]/20 to-[#E935C1]/10 px-4 py-2 text-white">StuarzAPI.php</div>
                  <div class="border-r border-gray-800 px-4 py-2 hover:bg-gray-800/50 transition-colors cursor-pointer">Absensi.php</div>
                </div>
              </div>
                <div class="px-6 pt-6 pb-14 bg-gray-950 rounded-b-xl">
                <pre class="overflow-x-auto text-xs leading-relaxed" style="background:#0a0a0a;color:#d4d4d4;padding:1em;border-radius:0.5em;">
        <span style="color:#E935C1;">&lt;?php</span>
        <span style="color:#7775D6;">$absensi</span> = [
          [
          <span style="color:#b5bac1;">'id'</span> =&gt; <span style="color:#b5cea8;">1</span>,
          <span style="color:#b5bac1;">'nama'</span> =&gt; <span style="color:#b5cea8;">'Andi Wijaya'</span>,
          <span style="color:#b5bac1;">'tanggal'</span> =&gt; <span style="color:#b5cea8;">'2024-06-01'</span>,
          <span style="color:#b5bac1;">'status'</span> =&gt; <span style="color:#b5cea8;">'Hadir'</span>
          ],
          [
          <span style="color:#b5bac1;">'id'</span> =&gt; <span style="color:#b5cea8;">2</span>,
          <span style="color:#b5bac1;">'nama'</span> =&gt; <span style="color:#b5cea8;">'Siti Rahma'</span>,
          <span style="color:#b5bac1;">'tanggal'</span> =&gt; <span style="color:#b5cea8;">'2024-06-01'</span>,
          <span style="color:#b5bac1;">'status'</span> =&gt; <span style="color:#b5cea8;">'Izin'</span>
          ]
        ];
        <span style="color:#E935C1;">echo</span> <span style="color:#d7ba7d;">json_encode</span>([
          <span style="color:#b5bac1;">'success'</span> =&gt; <span style="color:#7775D6;">true</span>,
          <span style="color:#b5bac1;">'data'</span> =&gt; <span style="color:#7775D6;">$absensi</span>
        ]);
        <span style="color:#E935C1;">?&gt;</span>
                </pre>
                </div>
            </div>
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-white/10 max-lg:rounded-b-4xl lg:rounded-r-4xl group-hover:outline-[#7775D6]/40 transition-all duration-300"></div>
      </div>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gradient-to-r from-transparent via-[#E935C1]/50 to-transparent"></div>

<!-- Deploy Section -->
<div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-[#E935C1] rounded-full blur-[120px] animate-pulse"></div>
  </div>
  
  <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:text-center animate-fade-in">
      <h2 class="inline-block text-sm font-semibold tracking-wide text-[#7775D6] uppercase px-4 py-2 bg-[#7775D6]/10 rounded-full ring-1 ring-[#7775D6]/20">Implementasi Mudah</h2>
      <p class="mt-6 text-4xl font-bold tracking-tight text-pretty text-white sm:text-5xl lg:text-balance animate-fade-in" style="animation-delay: 0.1s;">
        Semua fitur untuk mendukung pendidikan digital
      </p>
      <p class="mt-6 text-lg/8 text-gray-400 animate-fade-in" style="animation-delay: 0.2s;">
        Stuarz membantu siswa, guru, dan sekolah dalam proses belajar, pengiriman tugas, dan absensi secara digital dengan mudah dan aman.
      </p>
    </div>
    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
      <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
        <!-- Deploy Feature 1 -->
        <div class="relative pl-16 group animate-fade-in" style="animation-delay: 0.3s;">
          <dt class="text-base/7 font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#7775D6] to-[#E935C1] shadow-lg shadow-[#7775D6]/30 transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-[#7775D6]/50">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-white">
                <path d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Kirim Tugas Mudah
          </dt>
          <dd class="mt-2 text-base/7 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Siswa dapat mengirim tugas langsung melalui aplikasi, guru dapat memeriksa dan memberi nilai secara digital.
          </dd>
        </div>
        <!-- Deploy Feature 2 -->
        <div class="relative pl-16 group animate-fade-in" style="animation-delay: 0.4s;">
          <dt class="text-base/7 font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#7775D6] to-[#E935C1] shadow-lg shadow-[#7775D6]/30 transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-[#7775D6]/50">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-white">
                <path d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Sertifikat Digital
          </dt>
          <dd class="mt-2 text-base/7 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Sekolah dapat memberikan sertifikat digital kepada siswa yang telah menyelesaikan tugas dan absensi dengan baik.
          </dd>
        </div>
        <!-- Deploy Feature 3 -->
        <div class="relative pl-16 group animate-fade-in" style="animation-delay: 0.5s;">
          <dt class="text-base/7 font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#7775D6] to-[#E935C1] shadow-lg shadow-[#7775D6]/30 transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-[#7775D6]/50">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-white">
                <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Absensi Otomatis
          </dt>
          <dd class="mt-2 text-base/7 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Siswa dan guru dapat melakukan absensi secara otomatis dan terintegrasi dengan sistem sekolah.
          </dd>
        </div>
        <!-- Deploy Feature 4 -->
        <div class="relative pl-16 group animate-fade-in" style="animation-delay: 0.6s;">
          <dt class="text-base/7 font-semibold text-white">
            <div class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#7775D6] to-[#E935C1] shadow-lg shadow-[#7775D6]/30 transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-[#7775D6]/50">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-white">
                <path d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            Keamanan Terjamin
          </dt>
          <dd class="mt-2 text-base/7 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Data pendidikan Anda aman bersama Stuarz, didukung sistem keamanan modern.
          </dd>
        </div>
      </dl>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gradient-to-r from-transparent via-[#7775D6]/50 to-transparent"></div>

<!-- Work With Us Section -->
<div class="relative isolate overflow-hidden bg-gray-900 py-24 sm:py-32">
  <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-y=.8&w=2830&h=1500&q=80&blend=111827&sat=-100&exp=15&blend-mode=multiply" alt="" class="absolute inset-0 -z-10 size-full object-cover object-right md:object-center opacity-40" />
  <div aria-hidden="true" class="hidden sm:absolute sm:-top-10 sm:right-1/2 sm:-z-10 sm:mr-10 sm:block sm:transform-gpu sm:blur-3xl">
    <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="aspect-1097/845 w-274.25 bg-gradient-to-tr from-[#E935C1] to-[#7775D6] opacity-40 animate-pulse"></div>
  </div>
  <div aria-hidden="true" class="absolute -top-52 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:-top-112 sm:ml-16 sm:translate-x-0">
    <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="aspect-1097/845 w-274.25 bg-gradient-to-tr from-[#E935C1] to-[#7775D6] opacity-40 animate-pulse" style="animation-delay: 1s;"></div>
  </div>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:mx-0 animate-fade-in">
      <h2 class="text-5xl font-semibold tracking-tight text-white sm:text-7xl bg-clip-text bg-gradient-to-r from-white to-gray-400">Bergabung bersama Stuarz</h2>
      <p class="mt-8 text-lg font-medium text-pretty text-gray-300 sm:text-xl/8 animate-fade-in" style="animation-delay: 0.1s;">
        Stuarz mengajak siswa, guru, dan sekolah untuk berkolaborasi dalam dunia pendidikan digital. Bersama kami, proses belajar menjadi lebih mudah dan menyenangkan.
      </p>
    </div>
    <div class="mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none">
      <div class="grid grid-cols-1 gap-x-8 gap-y-6 text-base/7 font-semibold text-white sm:grid-cols-2 md:flex lg:gap-x-10 animate-fade-in" style="animation-delay: 0.2s;">
        <a href="#" class="group flex items-center gap-2 hover:text-[#7775D6] transition-all duration-200">
          <span>Peran terbuka</span>
          <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
        </a>
        <a href="#" class="group flex items-center gap-2 hover:text-[#E935C1] transition-all duration-200">
          <span>Program magang</span>
          <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
        </a>
        <a href="#" class="group flex items-center gap-2 hover:text-[#7775D6] transition-all duration-200">
          <span>Nilai-nilai kami</span>
          <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
        </a>
        <a href="#" class="group flex items-center gap-2 hover:text-[#E935C1] transition-all duration-200">
          <span>Tim Stuarz</span>
          <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
        </a>
      </div>
      <dl class="mt-16 grid grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 lg:grid-cols-4 animate-fade-in" style="animation-delay: 0.3s;">
        <div class="flex flex-col-reverse gap-1 p-6 rounded-2xl bg-gray-800/40 backdrop-blur-sm ring-1 ring-white/10 hover:ring-[#7775D6]/40 transition-all duration-300 transform hover:scale-105">
          <dt class="text-base/7 text-gray-400">Sekolah bergabung</dt>
          <dd class="text-4xl font-semibold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">12</dd>
        </div>
        <div class="flex flex-col-reverse gap-1 p-6 rounded-2xl bg-gray-800/40 backdrop-blur-sm ring-1 ring-white/10 hover:ring-[#E935C1]/40 transition-all duration-300 transform hover:scale-105">
          <dt class="text-base/7 text-gray-400">Guru aktif</dt>
          <dd class="text-4xl font-semibold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">300+</dd>
        </div>
        <div class="flex flex-col-reverse gap-1 p-6 rounded-2xl bg-gray-800/40 backdrop-blur-sm ring-1 ring-white/10 hover:ring-[#7775D6]/40 transition-all duration-300 transform hover:scale-105">
          <dt class="text-base/7 text-gray-400">Jam belajar per minggu</dt>
          <dd class="text-4xl font-semibold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">40</dd>
        </div>
        <div class="flex flex-col-reverse gap-1 p-6 rounded-2xl bg-gray-800/40 backdrop-blur-sm ring-1 ring-white/10 hover:ring-[#E935C1]/40 transition-all duration-300 transform hover:scale-105">
          <dt class="text-base/7 text-gray-400">Access for study</dt>
          <dd class="text-4xl font-semibold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">Unlimited</dd>
        </div>
      </dl>
    </div>
  </div>
</div>

<!-- Divider -->
<div class="h-px bg-gradient-to-r from-transparent via-[#E935C1]/50 to-transparent"></div>

<!-- Blog Section -->
<div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-10">
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#7775D6] rounded-full blur-[140px] animate-pulse"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#E935C1] rounded-full blur-[140px] animate-pulse" style="animation-delay: 1.5s;"></div>
  </div>
  
  <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:mx-0 animate-fade-in">
      <h2 class="text-4xl font-semibold tracking-tight text-pretty text-white sm:text-5xl">Dari Blog Stuarz</h2>
      <p class="mt-2 text-lg/8 text-gray-400">Dapatkan tips dan inspirasi seputar pendidikan digital bersama Stuarz.</p>
    </div>
    <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-800 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
      <!-- Blog Post 1 -->
      <article class="flex max-w-xl flex-col items-start justify-between group animate-fade-in p-6 rounded-2xl bg-gray-800/20 ring-1 ring-white/10 hover:ring-[#7775D6]/40 hover:bg-gray-800/40 transition-all duration-300 transform hover:scale-105" style="animation-delay: 0.1s;">
        <div class="flex items-center gap-x-4 text-xs">
          <time datetime="2020-03-16" class="text-gray-400">Mar 16, 2020</time>
          <a href="#" class="relative z-10 rounded-full bg-gradient-to-r from-[#7775D6]/20 to-[#E935C1]/20 px-3 py-1.5 font-medium text-gray-300 hover:from-[#7775D6]/30 hover:to-[#E935C1]/30 ring-1 ring-[#7775D6]/30 transition-all duration-200">Pendidikan</a>
        </div>
        <div class="relative grow">
          <h3 class="mt-3 text-lg/6 font-semibold text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-[#7775D6] group-hover:to-[#E935C1] transition-all duration-300">
            <a href="#">
              <span class="absolute inset-0"></span>
              Maksimalkan pembelajaran digital dengan Stuarz
            </a>
          </h3>
          <p class="mt-5 line-clamp-3 text-sm/6 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Temukan cara mudah belajar, mengirim tugas, dan absensi online. Stuarz hadir untuk mendukung pendidikan modern di sekolah Anda.
          </p>
        </div>
        <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
          <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full bg-gray-800 ring-2 ring-[#7775D6]/40 group-hover:ring-[#7775D6]/60 transition-all duration-300" />
          <div class="text-sm/6">
            <p class="font-semibold text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                Michael Foster
              </a>
            </p>
            <p class="text-gray-400">Guru Matematika</p>
          </div>
        </div>
      </article>
      <!-- Blog Post 2 -->
      <article class="flex max-w-xl flex-col items-start justify-between group animate-fade-in p-6 rounded-2xl bg-gray-800/20 ring-1 ring-white/10 hover:ring-[#E935C1]/40 hover:bg-gray-800/40 transition-all duration-300 transform hover:scale-105" style="animation-delay: 0.2s;">
        <div class="flex items-center gap-x-4 text-xs">
          <time datetime="2020-03-10" class="text-gray-400">Mar 10, 2020</time>
          <a href="#" class="relative z-10 rounded-full bg-gradient-to-r from-[#E935C1]/20 to-[#7775D6]/20 px-3 py-1.5 font-medium text-gray-300 hover:from-[#E935C1]/30 hover:to-[#7775D6]/30 ring-1 ring-[#E935C1]/30 transition-all duration-200">Teknologi</a>
        </div>
        <div class="relative grow">
          <h3 class="mt-3 text-lg/6 font-semibold text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-[#E935C1] group-hover:to-[#7775D6] transition-all duration-300">
            <a href="#">
              <span class="absolute inset-0"></span>
              Cara mudah absensi online di sekolah
            </a>
          </h3>
          <p class="mt-5 line-clamp-3 text-sm/6 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Dengan Stuarz, absensi siswa dan guru menjadi lebih praktis dan terintegrasi dengan sistem sekolah.
          </p>
        </div>
        <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
          <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full bg-gray-800 ring-2 ring-[#E935C1]/40 group-hover:ring-[#E935C1]/60 transition-all duration-300" />
          <div class="text-sm/6">
            <p class="font-semibold text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                Lindsay Walton
              </a>
            </p>
            <p class="text-gray-400">Guru Bahasa Inggris</p>
          </div>
        </div>
      </article>
      <!-- Blog Post 3 -->
      <article class="flex max-w-xl flex-col items-start justify-between group animate-fade-in p-6 rounded-2xl bg-gray-800/20 ring-1 ring-white/10 hover:ring-[#7775D6]/40 hover:bg-gray-800/40 transition-all duration-300 transform hover:scale-105" style="animation-delay: 0.3s;">
        <div class="flex items-center gap-x-4 text-xs">
          <time datetime="2020-02-12" class="text-gray-400">Feb 12, 2020</time>
          <a href="#" class="relative z-10 rounded-full bg-gradient-to-r from-[#7775D6]/20 to-[#E935C1]/20 px-3 py-1.5 font-medium text-gray-300 hover:from-[#7775D6]/30 hover:to-[#E935C1]/30 ring-1 ring-[#7775D6]/30 transition-all duration-200">Sekolah</a>
        </div>
        <div class="relative grow">
          <h3 class="mt-3 text-lg/6 font-semibold text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-[#7775D6] group-hover:to-[#E935C1] transition-all duration-300">
            <a href="#">
              <span class="absolute inset-0"></span>
              Kolaborasi guru dan siswa di Stuarz
            </a>
          </h3>
          <p class="mt-5 line-clamp-3 text-sm/6 text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
            Stuarz memudahkan komunikasi antara guru dan siswa, sehingga proses belajar menjadi lebih interaktif dan efektif.
          </p>
        </div>
        <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
          <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full bg-gray-800 ring-2 ring-[#7775D6]/40 group-hover:ring-[#7775D6]/60 transition-all duration-300" />
          <div class="text-sm/6">
            <p class="font-semibold text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                Tom Cook
              </a>
            </p>
            <p class="text-gray-400">Kepala Sekolah</p>
          </div>
        </div>
      </article>
    </div>
  </div>
</div>