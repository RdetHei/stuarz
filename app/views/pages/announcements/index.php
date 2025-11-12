<div class="bg-gray-900 min-h-screen">
  <div class="max-w-5xl mx-auto px-6 py-8">

    <!-- Flash Message -->
    <?php if (!empty($_SESSION['flash'])): ?>
      <?php 
      $flashLevel = $_SESSION['flash_level'] ?? 'info';
      $bgClass = 'bg-[#5865F2]/10';
      $borderClass = 'border-[#5865F2]/30';
      $textClass = 'text-[#5865F2]';
      $iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
      
      if ($flashLevel === 'success') {
        $bgClass = 'bg-emerald-500/10';
        $borderClass = 'border-emerald-500/30';
        $textClass = 'text-emerald-400';
        $iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
      } elseif ($flashLevel === 'danger') {
        $bgClass = 'bg-red-500/10';
        $borderClass = 'border-red-500/30';
        $textClass = 'text-red-400';
        $iconPath = 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
      } elseif ($flashLevel === 'warning') {
        $bgClass = 'bg-amber-500/10';
        $borderClass = 'border-amber-500/30';
        $textClass = 'text-amber-400';
        $iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
      }
      ?>
      <div class="mb-6 <?= $bgClass ?> border <?= $borderClass ?> rounded-lg p-4">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 <?= $textClass ?> flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $iconPath ?>"/>
          </svg>
          <p class="text-sm <?= $textClass ?> flex-1"><?= htmlspecialchars($_SESSION['flash']) ?></p>
        </div>
      </div>
      <?php unset($_SESSION['flash'], $_SESSION['flash_level']); ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-100 flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20">
            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
          </div>
          Pengumuman
        </h1>
        <p class="text-sm text-gray-400 mt-2">Informasi dan berita terkini</p>
      </div>
      <a href="index.php?page=announcement_create" 
         class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Pengumuman
      </a>
    </div>

    <!-- List -->
    <?php if (empty($announcements)): ?>
      <!-- Empty State -->
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
          <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Pengumuman</h3>
        <p class="text-gray-400 mb-6">Belum ada pengumuman yang tersedia saat ini</p>
        <a href="index.php?page=announcement_create" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Buat Pengumuman Pertama
        </a>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach ($announcements as $a): ?>
          <article class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-700 bg-[#111827]">
              <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                  <h3 class="text-lg font-semibold text-gray-100 mb-2">
                    <?= htmlspecialchars($a['title']) ?>
                  </h3>
                  <div class="flex items-center gap-3 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] flex items-center justify-center">
                        <span class="text-xs font-semibold text-white">
                          <?= strtoupper(substr($a['username'] ?? 'A', 0, 1)) ?>
                        </span>
                      </div>
                      <span class="font-medium text-gray-300"><?= htmlspecialchars($a['username'] ?? 'Anonim') ?></span>
                    </div>
                    <span>•</span>
                    <div class="flex items-center gap-1.5">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <time><?= date('d M Y H:i', strtotime($a['created_at'])) ?></time>
                    </div>
                  </div>
                </div>

                <!-- Priority Badge (Optional) -->
                <?php if (isset($a['priority']) && $a['priority'] === 'high'): ?>
                <span class="flex-shrink-0 px-2.5 py-1 bg-red-500/10 text-red-400 border border-red-500/20 rounded text-xs font-semibold uppercase tracking-wider">
                  Penting
                </span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-5">
              <div class="prose prose-invert prose-sm max-w-none">
                <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">
                  <?= nl2br(htmlspecialchars($a['content'])) ?>
                </p>
              </div>

              <!-- Photo -->
              <?php if (!empty($a['photo'])): ?>
                <div class="mt-4">
                  <a href="<?= htmlspecialchars($a['photo']) ?>" target="_blank" class="block">
                    <img src="<?= htmlspecialchars($a['photo']) ?>" 
                         alt="Foto Pengumuman" 
                         class="max-w-md rounded-lg border border-gray-700 hover:border-gray-600 transition-colors cursor-pointer"
                         loading="lazy">
                  </a>
                </div>
              <?php endif; ?>
            </div>

            <!-- Footer / Actions -->
            <div class="px-6 py-3 bg-[#111827] border-t border-gray-700 flex items-center justify-between">
              <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  ID: <?= htmlspecialchars($a['id']) ?>
                </span>
              </div>

              <div class="flex items-center gap-2">
                <a href="index.php?page=announcement_edit&id=<?= $a['id'] ?>" 
                   class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors"
                   title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="POST" action="index.php?page=announcement_delete" 
                      class="inline" 
                      onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" 
                          class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-md transition-colors"
                          title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>