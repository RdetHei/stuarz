<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$me = $_SESSION['user'] ?? null;
$canAdmin = isset($me['level']) && $me['level'] === 'admin';
$currentScope = $_GET['scope'] ?? ($canAdmin ? 'all' : 'my');
$isAllScope = $canAdmin && $currentScope === 'all';

// Handle success/error messages
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Base URL
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>

<div class="p-6 space-y-6 bg-gray-900 min-h-screen text-gray-100">

  <!-- Header -->
  <div class="border border-gray-700 bg-gray-800 p-6 rounded-lg shadow-xl">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold flex items-center gap-3 text-white">
          <span class="material-symbols-outlined text-blue-400 text-4xl">workspace_premium</span>
          Sertifikat
        </h1>
        <p class="text-gray-400 mt-2">Manage and view your certificates</p>
      </div>
      <div class="flex items-center gap-3">
        <?php if ($canAdmin): ?>
          <?php
          $qs = $_GET;
          $qs['scope'] = (($_GET['scope'] ?? 'my') === 'all') ? 'my' : 'all';
          $toggleUrl = 'index.php?' . http_build_query(array_merge(['page' => 'certificates'], $qs));
          $isAll = (($_GET['scope'] ?? 'my') === 'all');
          ?>
          <a href="<?= htmlspecialchars($toggleUrl) ?>" 
             class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 transition-colors border border-gray-600 font-medium">
            <?= $isAll ? 'Punya Saya' : 'Semua' ?>
          </a>
        <?php endif; ?>
        <button onclick="openUploadModal()"
          class="px-4 py-2 bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] transition-all duration-200 flex items-center gap-2 shadow-lg font-medium">
          <span class="material-symbols-outlined text-xl">upload</span>
          Upload Sertifikat
        </button>
      </div>
    </div>
  </div>

  <!-- Alert -->
  <?php if ($successMsg): ?>
    <div class="bg-gray-800 border border-green-500 text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span><?= htmlspecialchars($successMsg) ?></span>
    </div>
  <?php endif; ?>

  <?php if ($errorMsg): ?>
    <div class="bg-gray-800 border border-red-500 text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
      </svg>
      <span><?= htmlspecialchars($errorMsg) ?></span>
    </div>
  <?php endif; ?>

  <!-- Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-blue-400 text-sm font-medium"><?= $isAllScope ? 'Total Sertifikat (Semua)' : 'Total Sertifikat Saya' ?></p>
          <p class="text-3xl font-bold text-white mt-2"><?= count($certificates) ?></p>
        </div>
        <div class="p-3 bg-blue-500/20 rounded-lg border border-blue-500/40">
          <span class="material-symbols-outlined text-blue-400 text-2xl">workspace_premium</span>
        </div>
      </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-green-400 text-sm font-medium">Sertifikat Saya</p>
          <?php $myCount = array_sum(array_map(fn($c) => (int)$c['user_id'] === (int)$me['id'] ? 1 : 0, $certificates)); ?>
          <p class="text-3xl font-bold text-white mt-2"><?= $isAllScope ? $myCount : count($certificates) ?></p>
        </div>
        <div class="p-3 bg-green-500/20 rounded-lg border border-green-500/40">
          <span class="material-symbols-outlined text-green-400 text-2xl">person</span>
        </div>
      </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-purple-400 text-sm font-medium">Bulan Ini</p>
          <p class="text-3xl font-bold text-white mt-2"><?= array_sum(array_map(fn($c) => date('Y-m', strtotime($c['created_at'])) === date('Y-m') ? 1 : 0, $certificates)) ?></p>
        </div>
        <div class="p-3 bg-purple-500/20 rounded-lg border border-purple-500/40">
          <span class="material-symbols-outlined text-purple-400 text-2xl">calendar_month</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Grid Sertifikat -->
  <?php if (count($certificates) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($certificates as $cert): ?>
        <div class="bg-gray-800 rounded-lg border border-gray-700 hover:border-blue-500 transition-all duration-300 group shadow-lg overflow-hidden" 
             data-cert-id="<?= (int)$cert['id'] ?>" 
             data-cert-title="<?= htmlspecialchars($cert['title'], ENT_QUOTES, 'UTF-8') ?>" 
             data-cert-by="<?= htmlspecialchars($cert['issued_by'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
             data-cert-at="<?= $cert['issued_at'] ? date('d M Y', strtotime($cert['issued_at'])) : '' ?>">
          <!-- Preview -->
          <div class="relative h-52 bg-gray-900 flex items-center justify-center overflow-hidden cursor-pointer" 
               onclick="viewCertificate(<?= $cert['id'] ?>)" 
               aria-label="Lihat sertifikat">
            <?php
            // Debug: tampilkan informasi path
            error_log("Certificate Debug - file_path from DB: " . $cert['file_path']);
            
            // Normalize path untuk sistem file Windows
            // gunakan dirname(..., 4) agar naik sampai project root (sebelum folder "app")
            $publicPath = str_replace('/', DIRECTORY_SEPARATOR, dirname(__DIR__, 4) . '/public');
            $webSrc = ($baseUrl ? $baseUrl . '/' : '') . ltrim((string)$cert['file_path'], '/');
            $fsPath = $publicPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim((string)$cert['file_path'], '/'));
            
            error_log("Certificate Debug - publicPath: " . $publicPath);
            error_log("Certificate Debug - webSrc: " . $webSrc);
            error_log("Certificate Debug - fsPath: " . $fsPath);
            ?>
            <?php if (!empty($cert['file_path'])): ?>
              <?php
              $debugInfo = "";
              if (!is_file($fsPath)) {
                  $debugInfo = "File tidak ditemukan di: " . $fsPath;
              }
              if (is_file($fsPath)): ?>
                <img src="<?= htmlspecialchars($webSrc) ?>"
                  alt="<?= htmlspecialchars($cert['title']) ?>"
                  class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
              <?php else: ?>
                <div class="text-center">
                  <span class="material-symbols-outlined text-6xl text-gray-600">workspace_premium</span>
                  <p class="text-gray-500 mt-2 text-sm">Preview tidak tersedia</p>
                  <?php if ($debugInfo): ?>
                    <p class="text-xs text-red-400 mt-1"><?= htmlspecialchars($debugInfo) ?></p>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php else: ?>
              <div class="text-center">
                <span class="material-symbols-outlined text-6xl text-gray-600">workspace_premium</span>
                <p class="text-gray-500 mt-2 text-sm">File path kosong</p>
              </div>
            <?php endif; ?>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
              <div class="absolute right-3 bottom-3">
                <button onclick="viewCertificate(<?= $cert['id'] ?>)" 
                        class="pointer-events-auto w-10 h-10 rounded-md bg-[#5865f2] text-white flex items-center justify-center hover:bg-[#4752c4] transition-colors shadow-lg" 
                        title="Lihat">
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Info -->
          <div class="p-5">
            <div class="flex items-start justify-between mb-3">
              <h3 class="text-base font-semibold text-gray-100 line-clamp-2 group-hover:text-blue-400 transition-colors flex-1">
                <?= htmlspecialchars($cert['title']) ?>
              </h3>
              <?php if ($cert['user_id'] == $me['id'] || $canAdmin): ?>
                <button onclick="deleteCertificate(event, <?= $cert['id'] ?>)"
                  type="button"
                  class="p-1.5 text-[#f85149] hover:text-white hover:bg-[#da3633] rounded transition-colors ml-2"
                  title="Hapus">
                  <span class="material-symbols-outlined text-lg">delete</span>
                </button>
              <?php endif; ?>
            </div>

            <?php if ($cert['description']): ?>
              <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($cert['description']) ?></p>
            <?php endif; ?>

            <div class="space-y-2.5 text-sm text-gray-400">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400 text-base">business</span>
                <span class="text-gray-300"><?= htmlspecialchars($cert['issued_by'] ?: 'Tidak diketahui') ?></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-400 text-base">calendar_today</span>
                <span class="text-gray-300"><?= $cert['issued_at'] ? date('d M Y', strtotime($cert['issued_at'])) : 'Tidak diketahui' ?></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-400 text-base">person</span>
                <span class="text-gray-300"><?= htmlspecialchars($cert['username'] ?: 'User') ?></span>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-700 flex items-center justify-between text-xs">
              <span class="text-gray-500"><?= date('d M Y', strtotime($cert['created_at'])) ?></span>
              <a href="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($cert['file_path'], '/')); ?>" 
                 target="_blank"
                 class="px-3 py-1.5 bg-[#238636] text-white rounded-md hover:bg-[#2ea043] transition-colors font-medium">
                Download
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <!-- Empty -->
    <div class="text-center py-20 bg-gray-800 rounded-lg border border-gray-700">
      <div class="w-32 h-32 mx-auto mb-6 bg-gray-900 rounded-full flex items-center justify-center border border-gray-700">
        <span class="material-symbols-outlined text-6xl text-gray-600">workspace_premium</span>
      </div>
      <h3 class="text-xl font-semibold text-gray-200 mb-2">Belum ada sertifikat</h3>
      <p class="text-gray-400 mb-6">Upload sertifikat pertama Anda untuk memulai</p>
      <button onclick="openUploadModal()"
        class="px-6 py-3 bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] transition-all duration-200 font-medium shadow-lg">
        Upload Sertifikat Pertama
      </button>
    </div>
  <?php endif; ?>
</div>

<!-- Modal Upload -->
<div id="uploadModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
  <div class="bg-[#161b22] border border-[#30363d] rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl">
    <div class="p-6 border-b border-[#30363d] flex items-center justify-between">
      <h2 class="text-xl font-semibold text-white">Upload Sertifikat</h2>
      <button onclick="closeUploadModal()" class="text-[#8b949e] hover:text-white transition-colors">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <form action="index.php?page=upload_certificate" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
      <input type="text" name="title" required placeholder="Judul Sertifikat"
        class="w-full px-4 py-3 bg-[#0d1117] border border-[#30363d] rounded-md text-white placeholder-[#8b949e] focus:border-[#58a6ff] focus:ring-1 focus:ring-[#58a6ff] outline-none transition-colors">
      <textarea name="description" rows="3" placeholder="Deskripsi (opsional)"
        class="w-full px-4 py-3 bg-[#0d1117] border border-[#30363d] rounded-md text-white placeholder-[#8b949e] focus:border-[#58a6ff] focus:ring-1 focus:ring-[#58a6ff] outline-none transition-colors"></textarea>
      <input type="text" name="issued_by" required placeholder="Diterbitkan oleh"
        class="w-full px-4 py-3 bg-[#0d1117] border border-[#30363d] rounded-md text-white placeholder-[#8b949e] focus:border-[#58a6ff] focus:ring-1 focus:ring-[#58a6ff] outline-none transition-colors">
      <input type="date" name="issued_at" required
        class="w-full px-4 py-3 bg-[#0d1117] border border-[#30363d] rounded-md text-white focus:border-[#58a6ff] focus:ring-1 focus:ring-[#58a6ff] outline-none transition-colors">
      <input type="file" name="certificate_file" accept="image/*,.pdf" required
        class="block w-full text-sm text-[#8b949e] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#5865f2] file:text-white hover:file:bg-[#4752c4] file:transition-colors">
      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeUploadModal()"
          class="flex-1 px-4 py-3 bg-[#21262d] text-[#c9d1d9] rounded-md hover:bg-[#30363d] transition-colors font-medium border border-[#30363d]">
          Batal
        </button>
        <button type="submit"
          class="flex-1 px-4 py-3 bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] transition-colors font-medium shadow-lg">
          Upload
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal View -->
<div id="viewModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
  <div class="bg-[#161b22] border border-[#30363d] rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
    <div class="p-6 border-b border-[#30363d] flex items-center justify-between">
      <h2 class="text-xl font-semibold text-white">Detail Sertifikat</h2>
      <button onclick="closeViewModal()" class="text-[#8b949e] hover:text-white transition-colors">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="p-6">
      <div id="certificateContent" class="text-center"></div>
    </div>
  </div>
</div>

<script>
  function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'
  }

  function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.body.style.overflow = 'auto'
  }

  function openViewModal() {
    document.getElementById('viewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'
  }

  function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto'
  }

  function viewCertificate(id) {
    const card = document.querySelector(`[data-cert-id="${id}"]`);
    const img = card ? card.querySelector('img') : null;
    const src = img ? img.getAttribute('src') : null;
    const title = card ? card.getAttribute('data-cert-title') : 'Sertifikat';
    const by = card ? card.getAttribute('data-cert-by') : '';
    const at = card ? card.getAttribute('data-cert-at') : '';
    const html = src ?
      `<img src="${src}" alt="${title}" class="max-h-[65vh] w-auto mx-auto rounded-lg shadow-2xl mb-4 border border-[#30363d]">` :
      `<div class="text-[#8b949e]"><span class="material-symbols-outlined text-6xl mb-4">workspace_premium</span><p>Preview tidak tersedia</p></div>`;
    document.getElementById('certificateContent').innerHTML = `${html}<div class="text-left text-[#c9d1d9] mt-4"><div class="font-semibold text-lg">${title}</div><div class="text-sm text-[#8b949e]">${by}${at ? ' • ' + at : ''}</div></div>`;
    openViewModal();
  }

  function deleteCertificate(e, id) {
    if (e && e.stopPropagation) e.stopPropagation();

    if (confirm('Yakin ingin menghapus sertifikat ini?')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'index.php?page=delete_certificate';

      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = 'id';
      inp.value = id;
      form.appendChild(inp);

      document.body.appendChild(form);
      form.submit();
    }
  }

  document.addEventListener('click', e => {
    if (e.target.id === 'uploadModal') {
      closeUploadModal();
    } else if (e.target.id === 'viewModal') {
      closeViewModal();
    }
  });
</script>

<style>
  .line-clamp-2 {
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    display: -webkit-box
  }

  /* Custom scrollbar untuk tema gelap GitHub/Discord */
  ::-webkit-scrollbar {
    width: 12px;
    height: 12px;
  }

  ::-webkit-scrollbar-track {
    background: #0d1117;
  }

  ::-webkit-scrollbar-thumb {
    background: #30363d;
    border-radius: 6px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #484f58;
  }

  /* Firefox scrollbar */
  * {
    scrollbar-width: thin;
    scrollbar-color: #30363d #0d1117;
  }
</style>