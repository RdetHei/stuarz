<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$me = $_SESSION['user'] ?? null;
$canAdmin = isset($me['level']) && $me['level'] === 'admin';

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
  <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-2xl shadow-lg flex items-center justify-between">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
        <span class="material-symbols-outlined text-yellow-300 text-4xl">workspace_premium</span>
        Sertifikat
      </h1>
      <p class="text-gray-200 mt-2">Kelola dan lihat semua sertifikat yang telah diupload</p>
    </div>
    <button onclick="openUploadModal()" 
            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl">
      <span class="material-symbols-outlined">upload</span>
      Upload Sertifikat
    </button>
  </div>

  <!-- Alert -->
  <?php if ($successMsg): ?>
    <div class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded-lg flex items-center gap-2">
      <span class="material-symbols-outlined">check_circle</span>
      <?= htmlspecialchars($successMsg) ?>
    </div>
  <?php endif; ?>

  <?php if ($errorMsg): ?>
    <div class="bg-red-900/50 border border-red-500 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
      <span class="material-symbols-outlined">error</span>
      <?= htmlspecialchars($errorMsg) ?>
    </div>
  <?php endif; ?>

  <!-- Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gray-800/50 border border-gray-700/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-blue-400 text-sm font-medium">Total Sertifikat</p>
          <p class="text-3xl font-bold text-white mt-2"><?= count($certificates) ?></p>
        </div>
        <div class="p-3 bg-blue-600/30 rounded-lg">
          <span class="material-symbols-outlined text-blue-400 text-2xl">workspace_premium</span>
        </div>
      </div>
    </div>

    <div class="bg-gray-800/50 border border-gray-700/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-green-400 text-sm font-medium">Sertifikat Saya</p>
          <p class="text-3xl font-bold text-white mt-2">
            <?= array_sum(array_map(fn($c) => $c['user_id'] == $me['id'] ? 1 : 0, $certificates)) ?>
          </p>
        </div>
        <div class="p-3 bg-green-600/30 rounded-lg">
          <span class="material-symbols-outlined text-green-400 text-2xl">person</span>
        </div>
      </div>
    </div>

    <div class="bg-gray-800/50 border border-gray-700/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-purple-400 text-sm font-medium">Bulan Ini</p>
          <p class="text-3xl font-bold text-white mt-2">
            <?= array_sum(array_map(fn($c) => date('Y-m', strtotime($c['created_at'])) === date('Y-m') ? 1 : 0, $certificates)) ?>
          </p>
        </div>
        <div class="p-3 bg-purple-600/30 rounded-lg">
          <span class="material-symbols-outlined text-purple-400 text-2xl">calendar_month</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Grid Sertifikat -->
  <?php if (count($certificates) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($certificates as $cert): ?>
        <div class="bg-gray-800/50 rounded-2xl border border-gray-700/50 hover:border-yellow-500/50 transition-all duration-300 group shadow-md overflow-hidden">
          <!-- Preview -->
          <div class="relative h-48 bg-gradient-to-br from-yellow-400/20 to-orange-500/20 flex items-center justify-center">
            <?php if ($cert['file_path'] && file_exists(__DIR__ . '/../../public/' . $cert['file_path'])): ?>
              <img src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($cert['file_path'], '/')); ?>" 
                   alt="<?= htmlspecialchars($cert['title']) ?>"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <?php else: ?>
              <div class="text-center">
                <span class="material-symbols-outlined text-6xl text-yellow-400/50">workspace_premium</span>
                <p class="text-gray-400 mt-2">Preview tidak tersedia</p>
              </div>
            <?php endif; ?>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
              <button onclick="viewCertificate(<?= $cert['id'] ?>)" 
                      class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-colors">
                <span class="material-symbols-outlined mr-1">visibility</span>
                Lihat
              </button>
            </div>
          </div>

          <!-- Info -->
          <div class="p-6">
            <div class="flex items-start justify-between mb-3">
              <h3 class="text-lg font-semibold text-white line-clamp-2 group-hover:text-yellow-400 transition-colors">
                <?= htmlspecialchars($cert['title']) ?>
              </h3>
              <?php if ($cert['user_id'] == $me['id'] || $canAdmin): ?>
                <button onclick="deleteCertificate(<?= $cert['id'] ?>)" 
                        class="p-1 text-red-400 hover:text-red-300 hover:bg-red-900/30 rounded transition-colors"
                        title="Hapus">
                  <span class="material-symbols-outlined text-sm">delete</span>
                </button>
              <?php endif; ?>
            </div>

            <?php if ($cert['description']): ?>
              <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($cert['description']) ?></p>
            <?php endif; ?>

            <div class="space-y-2 text-sm text-gray-300">
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-yellow-400 text-base">business</span><?= htmlspecialchars($cert['issued_by'] ?: 'Tidak diketahui') ?></div>
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-400 text-base">calendar_today</span><?= $cert['issued_date'] ? date('d M Y', strtotime($cert['issued_date'])) : 'Tidak diketahui' ?></div>
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-green-400 text-base">person</span><?= htmlspecialchars($cert['username'] ?: 'User') ?></div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-700 flex items-center justify-between text-xs text-gray-500">
              <span><?= date('d M Y', strtotime($cert['created_at'])) ?></span>
              <a href="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($cert['file_path'], '/')); ?>" target="_blank"
                 class="px-3 py-1 bg-yellow-600/20 text-yellow-400 rounded-lg hover:bg-yellow-600/30 transition-colors">
                Download
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <!-- Empty -->
    <div class="text-center py-16 bg-gray-800/30 rounded-2xl border border-gray-700/50">
      <div class="w-32 h-32 mx-auto mb-6 bg-gray-800/70 rounded-full flex items-center justify-center">
        <span class="material-symbols-outlined text-6xl text-gray-600">workspace_premium</span>
      </div>
      <h3 class="text-xl font-semibold text-gray-300 mb-2">Belum ada sertifikat</h3>
      <p class="text-gray-500 mb-6">Upload sertifikat pertama Anda untuk memulai</p>
      <button onclick="openUploadModal()" 
              class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300">
        Upload Sertifikat Pertama
      </button>
    </div>
  <?php endif; ?>
</div>

<!-- Modal Upload -->
<div id="uploadModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
  <div class="bg-gray-800 rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto shadow-xl">
    <div class="p-6 border-b border-gray-700 flex items-center justify-between">
      <h2 class="text-xl font-semibold text-white">Upload Sertifikat</h2>
      <button onclick="closeUploadModal()" class="text-gray-400 hover:text-white">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <form action="index.php?page=upload_certificate" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
      <input type="text" name="title" required placeholder="Judul Sertifikat"
             class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
      <textarea name="description" rows="3" placeholder="Deskripsi (opsional)"
                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500"></textarea>
      <input type="text" name="issued_by" required placeholder="Diterbitkan oleh"
             class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
      <input type="date" name="issued_date" required
             class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
      <input type="file" name="certificate_file" accept="image/*,.pdf" required
             class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-600/30 file:text-yellow-300 hover:file:bg-yellow-600/50">
      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeUploadModal()" 
                class="flex-1 px-4 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600">Batal</button>
        <button type="submit" 
                class="flex-1 px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600">Upload</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal View -->
<div id="viewModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
  <div class="bg-gray-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-xl">
    <div class="p-6 border-b border-gray-700 flex items-center justify-between">
      <h2 class="text-xl font-semibold text-white">Detail Sertifikat</h2>
      <button onclick="closeViewModal()" class="text-gray-400 hover:text-white">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="p-6">
      <div id="certificateContent" class="text-center"></div>
    </div>
  </div>
</div>

<script>
function openUploadModal(){document.getElementById('uploadModal').classList.remove('hidden');document.body.style.overflow='hidden'}
function closeUploadModal(){document.getElementById('uploadModal').classList.add('hidden');document.body.style.overflow='auto'}
function openViewModal(){document.getElementById('viewModal').classList.remove('hidden');document.body.style.overflow='hidden'}
function closeViewModal(){document.getElementById('viewModal').classList.add('hidden');document.body.style.overflow='auto'}

function viewCertificate(id){
  document.getElementById('certificateContent').innerHTML=`<div class="text-gray-400"><span class="material-symbols-outlined text-6xl mb-4">workspace_premium</span><p>Loading certificate details...</p></div>`;
  openViewModal();
}
function deleteCertificate(id){if(confirm('Yakin ingin menghapus sertifikat ini?')){window.location.href='index.php?page=delete_certificate&id='+id}}

document.addEventListener('click',e=>{if(e.target.id==='uploadModal'||e.target.id==='viewModal'){closeUploadModal();closeViewModal();}});
</script>

<style>
.line-clamp-2{-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;display:-webkit-box}
.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none}
.scrollbar-hide::-webkit-scrollbar{display:none}
</style>
