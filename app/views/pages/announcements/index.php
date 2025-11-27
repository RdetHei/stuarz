<div class="min-h-screen bg-gray-900">
  <div class="max-w-6xl mx-auto px-6 py-8">

    <!-- Flash Message -->
    <?php if (!empty($_SESSION['flash'])): ?>
      <?php 
      $flashLevel = $_SESSION['flash_level'] ?? 'info';
      $bgClass = 'bg-blue-500/10';
      $borderClass = 'border-blue-500/30';
      $textClass = 'text-blue-400';
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
      <div class="mb-6 <?= $bgClass ?> border <?= $borderClass ?> rounded-xl p-4 shadow-lg">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 <?= $textClass ?> flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $iconPath ?>"/>
          </svg>
          <p class="text-sm <?= $textClass ?> flex-1 font-medium"><?= htmlspecialchars($_SESSION['flash']) ?></p>
        </div>
      </div>
      <?php unset($_SESSION['flash'], $_SESSION['flash_level']); ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="mb-8 bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 shadow-xl">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
          <h1 class="text-3xl font-bold text-white flex items-center gap-3 mb-2">
            <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20">
              <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
              </svg>
            </div>
            Pengumuman
          </h1>
          <p class="text-sm text-gray-400">Informasi dan berita terkini untuk semua</p>
        </div>
        <a href="index.php?page=announcement_create" 
           class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2 shadow-lg shadow-blue-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Buat Pengumuman
        </a>
      </div>
    </div>

    <!-- List -->
    <?php if (empty($announcements)): ?>
      <!-- Empty State -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-16 text-center shadow-lg">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gray-700/50 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Belum Ada Pengumuman</h3>
          <p class="text-gray-400 mb-8">Belum ada pengumuman yang tersedia saat ini</p>
          <a href="index.php?page=announcement_create" 
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengumuman Pertama
          </a>
        </div>
      </div>
    <?php else: ?>
      <!-- Table View -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-750 border-b border-gray-700">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengumuman</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Konten</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Waktu</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <?php foreach ($announcements as $a): ?>
                <tr class="hover:bg-gray-750/50 transition-colors group">
                  
                  <!-- Title Column -->
                  <td class="px-6 py-5">
                    <div class="flex items-start gap-4">
                      <!-- Thumbnail -->
                      <?php if (!empty($a['photo'])): ?>
                        <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-700 border border-gray-600">
                          <img src="<?= htmlspecialchars($a['photo']) ?>" 
                               alt="Thumbnail" 
                               class="w-full h-full object-cover"
                               loading="lazy">
                        </div>
                      <?php else: ?>
                        <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                          <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                          </svg>
                        </div>
                      <?php endif; ?>
                      
                      <!-- Title & Author -->
                      <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-2 mb-2">
                          <h3 class="text-base font-semibold text-white line-clamp-2 flex-1">
                            <?= htmlspecialchars($a['title']) ?>
                          </h3>
                          <?php if (isset($a['priority']) && $a['priority'] === 'high'): ?>
                            <span class="flex-shrink-0 px-2 py-0.5 bg-red-500/10 text-red-400 border border-red-500/20 rounded text-xs font-bold">
                              PENTING
                            </span>
                          <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <?php if (!empty($a['avatar'])): ?>
                            <img src="<?= htmlspecialchars($a['avatar'], ENT_QUOTES) ?>" alt="Avatar" class="w-6 h-6 rounded-full object-cover border border-gray-600">
                            <?php else: ?>
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                              <span class="text-xs font-bold text-white">
                                <?= strtoupper(substr($a['username'] ?? 'A', 0, 1)) ?>
                              </span>
                            </div>
                            <?php endif; ?>
                            <span class="font-medium text-gray-300"><?= htmlspecialchars($a['username'] ?? 'Anonim') ?></span>
                          
                          <!-- Mobile: Show time here -->
                          <span class="md:hidden text-gray-600">•</span>
                          <span class="md:hidden text-xs text-gray-500"><?= date('d/m/Y', strtotime($a['created_at'])) ?></span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Content Column (Hidden on mobile) -->
                  <td class="px-6 py-5 hidden lg:table-cell">
                    <p class="text-sm text-gray-400 line-clamp-2 leading-relaxed">
                      <?= htmlspecialchars(substr($a['content'], 0, 120)) ?><?= strlen($a['content']) > 120 ? '...' : '' ?>
                    </p>
                  </td>

                  <!-- Time Column (Hidden on mobile) -->
                  <td class="px-6 py-5 hidden md:table-cell">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <div>
                        <div class="font-medium text-gray-300"><?= date('d M Y', strtotime($a['created_at'])) ?></div>
                        <div class="text-xs text-gray-500"><?= date('H:i', strtotime($a['created_at'])) ?></div>
                      </div>
                    </div>
                  </td>

                  <!-- Actions Column -->
                  <td class="px-6 py-5">
                    <div class="flex items-center justify-end gap-2">
                      <!-- View Button -->
                      <button onclick="showAnnouncementModal(<?= htmlspecialchars(json_encode($a), ENT_QUOTES) ?>)"
                              class="p-2 text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 border border-transparent hover:border-blue-500/20 rounded-lg transition-all"
                              title="Lihat Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                      
                      <!-- Edit Button -->
                      <a href="index.php?page=announcement_edit&id=<?= $a['id'] ?>" 
                         class="p-2 text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 border border-transparent hover:border-emerald-500/20 rounded-lg transition-all"
                         title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </a>
                      
                      <!-- Delete Button -->
                      <form method="POST" action="index.php?page=announcement_delete" 
                            class="inline" 
                            onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <button type="submit" 
                                class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 rounded-lg transition-all"
                                title="Hapus">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal for viewing announcement details -->
<div id="announcementModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-gray-800 border border-gray-700 rounded-xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
    <!-- Modal Header -->
    <div class="px-6 py-5 bg-gray-750 border-b border-gray-700 flex items-center justify-between">
      <h3 id="modalTitle" class="text-xl font-bold text-white">Detail Pengumuman</h3>
      <button onclick="closeAnnouncementModal()" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    
    <!-- Modal Content -->
    <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
      <!-- Author Info -->
      <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-700">
        <div id="modalAvatar" class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg"></div>
        <div>
          <div id="modalAuthor" class="text-base font-semibold text-white"></div>
          <div id="modalDate" class="text-sm text-gray-400"></div>
        </div>
      </div>
      
      <!-- Content -->
      <div id="modalContent" class="prose prose-invert prose-sm max-w-none mb-6">
        <p class="text-gray-300 leading-relaxed whitespace-pre-wrap"></p>
      </div>
      
      <!-- Photo -->
      <div id="modalPhotoContainer" class="hidden">
        <img id="modalPhoto" src="" alt="Foto Pengumuman" class="w-full rounded-lg border border-gray-700">
      </div>
    </div>
  </div>
</div>

<script>
function showAnnouncementModal(announcement) {
  const modal = document.getElementById('announcementModal');
  const title = document.getElementById('modalTitle');
  const avatar = document.getElementById('modalAvatar');
  const author = document.getElementById('modalAuthor');
  const date = document.getElementById('modalDate');
  const content = document.getElementById('modalContent');
  const photoContainer = document.getElementById('modalPhotoContainer');
  const photo = document.getElementById('modalPhoto');
  
  title.textContent = announcement.title;
  // Show avatar image if available, otherwise show initial
  if (announcement.avatar) {
    avatar.innerHTML = '<img src="' + announcement.avatar + '" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-gray-700">';
  } else {
    avatar.textContent = announcement.username ? announcement.username.charAt(0).toUpperCase() : 'A';
  }
  author.textContent = announcement.username || 'Anonim';
  
  const createdDate = new Date(announcement.created_at);
  date.textContent = createdDate.toLocaleDateString('id-ID', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
  
  content.querySelector('p').textContent = announcement.content;
  
  if (announcement.photo) {
    photo.src = announcement.photo;
    photoContainer.classList.remove('hidden');
  } else {
    photoContainer.classList.add('hidden');
  }
  
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeAnnouncementModal() {
  const modal = document.getElementById('announcementModal');
  modal.classList.add('hidden');
  document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('announcementModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    closeAnnouncementModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeAnnouncementModal();
  }
});
</script>