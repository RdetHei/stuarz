<div class="bg-gray-900 min-h-screen">
  <div class="relative isolate overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 -z-10">
      <div class="absolute inset-0 bg-gradient-to-b from-[#5865F2]/5 via-transparent to-transparent"></div>
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(88, 101, 242, 0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
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
      <div class="max-w-5xl mx-auto mb-24">
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
            <h2 class="text-3xl font-bold text-gray-100 mb-4">Belajar & bekerja tanpa ribet</h2>
            <p class="text-gray-400 leading-relaxed mb-6">
              Dengan Stuarz, tidak perlu server rumit atau setup berlebihan. Cukup login, atur, dan mulai bekerja. Semua fitur sudah siap mendukung kebutuhanmu setiap hari.
            </p>
            <p class="text-gray-400 leading-relaxed">
              Stuarz bukan hanya alat, tapi solusi lengkap untuk mendukung perkembangan belajar, produktivitas tim, dan pengelolaan proyek.
            </p>
          </div>
      
        </div>
      </div>

      <!-- Owner Card Section -->
      <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-100 mb-2">Meet the Creator</h2>
          <p class="text-gray-400">The mind behind Stuarz Platform</p>
        </div>

        <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors">
          <div class="relative h-32 bg-gradient-to-r from-[#5865F2] to-[#4752C4]">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAgTSAwIDIwIEwgNDAgMjAgTSAyMCAwIEwgMjAgNDAgTSAwIDMwIEwgNDAgMzAgTSAzMCAwIEwgMzAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-30"></div>
          </div>

          <div class="relative px-6 pb-8">
            <!-- Avatar -->
            <div class="flex justify-center -mt-16 mb-4">
              <div class="relative">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] p-1">
                  <div class="w-full h-full rounded-full bg-[#1f2937] flex items-center justify-center">
                    <span class="text-4xl font-bold text-white">R</span>
                  </div>
                </div>
                <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-500 rounded-full border-4 border-[#1f2937]"></div>
              </div>
            </div>

            <!-- Name & Role -->
            <div class="text-center mb-6">
              <h3 class="text-2xl font-bold text-gray-100 mb-1">Rdet.hei</h3>
              <p class="text-sm text-gray-400 mb-3">Creator & Developer of Stuarz</p>
              <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#5865F2]/10 text-[#5865F2] border border-[#5865F2]/20 rounded-full text-xs font-medium">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                Full Stack Developer
              </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
              <p class="text-sm text-gray-400 text-center leading-relaxed max-w-xl mx-auto">
                Passionate developer focused on creating intuitive and powerful educational platforms. 
                Building Stuarz to revolutionize the way students and educators collaborate and manage their work.
              </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-700">
              <div class="text-center">
                <div class="text-2xl font-bold text-gray-100">5+</div>
                <div class="text-xs text-gray-500">Years Experience</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-gray-100">20+</div>
                <div class="text-xs text-gray-500">Projects</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-gray-100">10k+</div>
                <div class="text-xs text-gray-500">Lines of Code</div>
              </div>
            </div>

            <!-- Social Links -->
            <div>
              <h4 class="text-sm font-semibold text-gray-300 mb-4 text-center">Connect with me</h4>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- GitHub -->
                <a href="https://github.com/rdet" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">GitHub</div>
                    <div class="text-sm font-medium text-gray-200 truncate">@rdet</div>
                  </div>
                </a>

                <!-- LinkedIn -->
                <a href="https://linkedin.com/in/rdet" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-[#0077B5] transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">LinkedIn</div>
                    <div class="text-sm font-medium text-gray-200 truncate">Rdet Hei</div>
                  </div>
                </a>

                <!-- Twitter/X -->
                <a href="https://twitter.com/rdet" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-black transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">Twitter</div>
                    <div class="text-sm font-medium text-gray-200 truncate">@rdet</div>
                  </div>
                </a>

                <!-- Facebook -->
                <a href="https://facebook.com/rdet" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-[#1877F2] transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">Facebook</div>
                    <div class="text-sm font-medium text-gray-200 truncate">Rdet Hei</div>
                  </div>
                </a>

                <!-- YouTube -->
                <a href="https://youtube.com/@rdet" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-[#FF0000] transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">YouTube</div>
                    <div class="text-sm font-medium text-gray-200 truncate">@rdet</div>
                  </div>
                </a>

                <!-- Email -->
                <a href="mailto:rdet@example.com"
                   class="flex items-center gap-3 p-3 bg-[#111827] hover:bg-gray-700 border border-gray-700 rounded-lg transition-colors group">
                  <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center group-hover:bg-[#5865F2] transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500">Email</div>
                    <div class="text-sm font-medium text-gray-200 truncate">Contact</div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>