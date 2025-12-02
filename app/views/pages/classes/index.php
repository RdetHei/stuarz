<?php
// Class Dashboard Index
// Expected variables from controller: $classes (array), $sessionUser, $hasClasses
$sessionUser = $_SESSION['user'] ?? [];
$classes = $classes ?? [];
$showAll = $showAll ?? false;
$hasClasses = $hasClasses ?? !empty($classes);
?>

<div class="min-h-screen bg-gray-900 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <header class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="p-2 rounded-lg bg-blue-500/10">
              <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            Kelas Saya
          </h1>
          <p class="text-sm text-gray-400">Kelola kelas dan kegiatan belajar Anda</p>
        </div>

        <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
        <div class="flex items-center gap-3">
          <button id="openCreateClassBtn" class="px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Create Class
          </button>
          </div>
        <?php endif; ?>
        
        <!-- Toggle: show all classes or only joined -->
        <div class="ml-4">
          <?php if ($showAll): ?>
            <a href="index.php?page=class" class="px-3 py-1 rounded-md bg-gray-700 hover:bg-gray-600 text-sm text-white">Tampilkan Kelas Saya</a>
          <?php else: ?>
            <a href="index.php?page=class&show=all" class="px-3 py-1 rounded-md bg-gray-700 hover:bg-gray-600 text-sm text-white">Tampilkan Semua</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <?php if (!$hasClasses): ?>
      <!-- Empty State with Join Form -->
      <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 shadow-lg mb-6">
          <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-700/50 flex items-center justify-center">
              <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Belum Ada Kelas</h3>
            <p class="text-sm text-gray-400 mb-6">Bergabunglah dengan kelas untuk memulai pembelajaran</p>
          </div>

          <!-- Join Class Form -->
          <div class="bg-gray-900/50 border border-gray-700 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-4">
              <div class="p-2 rounded-lg bg-purple-500/10">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
              </div>
              <h2 class="text-lg font-bold text-white">Gabung Kelas</h2>
            </div>
            
            <?php $action = 'index.php?page=join_class'; include __DIR__ . '/../../components/class/JoinClassForm.php'; ?>
          </div>

          <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-400 mb-3">atau</p>
            <button onclick="document.getElementById('openCreateClassBtn')?.click()" class="px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
              Buat Kelas Baru
            </button>
          </div>
          <?php endif; ?>
        </div>
      </div>
    <?php else: ?>
      <!-- Main Content with Classes Table -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
        <!-- Table Header with Stats -->
        <div class="px-6 py-4 border-b border-gray-700 bg-[#111827]">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-white">Daftar Kelas</h2>
              <p class="text-sm text-gray-400 mt-1">Total: <?= count($classes) ?> kelas</p>
            </div>
            <!-- Removed teacher/student quick stats per request; showing only total count above -->
          </div>
        </div>

        <!-- Cards Grid -->
        <div class="p-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($classes as $c): ?>
              <?php
                $name = $c['name'] ?? 'Kelas';
                $code = $c['code'] ?? '';
                $description = $c['description'] ?? '-';
                $members_count = isset($c['members_count']) ? intval($c['members_count']) : 0;
                $is_joined = intval($c['is_joined'] ?? 0);
                $role = $c['member_role'] ?? null;
                if ($role === null && isset($c['created_by']) && isset($sessionUser['id']) && intval($c['created_by']) === intval($sessionUser['id'])) {
                  $role = 'creator';
                }
                if ($role === null) { $role = 'not_joined'; }
                $link = 'index.php?page=class/detail/' . ($c['id'] ?? '');
                $roleColors = [
                  'admin' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
                  'creator' => ['bg' => 'bg-orange-500/10', 'border' => 'border-orange-500/20', 'text' => 'text-orange-400'],
                  'teacher' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
                  'student' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400'],
                  'not_joined' => ['bg' => 'bg-gray-500/10', 'border' => 'border-gray-500/20', 'text' => 'text-gray-400']
                ];
                $colors = $roleColors[$role] ?? $roleColors['student'];
              ?>
              <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 flex flex-col justify-between h-full">
                <div>
                  <div class="flex items-start justify-between gap-4 mb-3">
                    <div class="min-w-0">
                      <h3 class="text-lg font-semibold text-white truncate"><?= htmlspecialchars($name) ?></h3>
                      <div class="text-xs text-gray-400 mt-1">Kode: <span class="font-mono text-gray-300"><?= htmlspecialchars($code) ?></span></div>
                    </div>
                    <div class="text-sm text-gray-300 ml-2"><?= $members_count ?> anggota</div>
                  </div>
                  <p class="text-sm text-gray-400 mb-4 line-clamp-3"><?= htmlspecialchars($description) ?></p>
                </div>
                <div class="flex items-center justify-between mt-4">
                  <div>
                    <?php if ($is_joined === 1 && $c['member_role']): ?>
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium <?= $colors['bg'] ?> <?= $colors['border'] ?> <?= $colors['text'] ?> border">
                        <span class="capitalize"><?= htmlspecialchars($c['member_role'] ?? 'member') ?></span>
                      </span>
                    <?php elseif ($role === 'creator'): ?>
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-orange-500/10 border-orange-500/20 text-orange-400 border">Creator</span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-500/10 border-gray-500/20 text-gray-400 border">Not Joined</span>
                    <?php endif; ?>
                  </div>
                  <a href="<?= htmlspecialchars($link) ?>" class="px-3 py-1.5 rounded-md bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 transition-colors">Masuk</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <!-- Create Class Modal -->
  <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
    <?php $generated_code = strtoupper(substr(bin2hex(random_bytes(3)),0,6)); include __DIR__ . '/../../components/class/CreateClassModal.php'; ?>
  <?php endif; ?>

</div>

<script>
// Modal open/close and join form basic frontend
document.addEventListener('DOMContentLoaded', function(){
  var openCreate = document.getElementById('openCreateClassBtn');
  var createModal = document.getElementById('createClassModal');
  var closeCreate = document.getElementById('closeCreateClass');
  var cancelCreate = document.getElementById('cancelCreate');
  if (openCreate && createModal){ openCreate.addEventListener('click', function(e){ e.preventDefault(); createModal.classList.remove('hidden'); }); }
  if (closeCreate) closeCreate.addEventListener('click', function(){ createModal.classList.add('hidden'); });
  if (cancelCreate) cancelCreate.addEventListener('click', function(){ createModal.classList.add('hidden'); });

  // Join form handling (AJAX if available)
  var joinForm = document.getElementById('joinClassForm');
  if (joinForm){
    joinForm.addEventListener('submit', function(e){
      e.preventDefault();
      var codeEl = document.getElementById('joinCode');
      var code = codeEl.value.trim();
      var errorEl = document.getElementById('joinError');
      if (!code){ if (errorEl){ errorEl.textContent = 'Kode kelas wajib diisi.'; errorEl.classList.remove('hidden'); } return false; }
      var submit = document.getElementById('joinSubmit');
      if (submit){ submit.disabled = true; submit.textContent = 'Memproses...'; }

      // POST via fetch to server with ajax=1
      var action = joinForm.getAttribute('action') || 'index.php?page=join_class';
      var formData = new FormData();
      formData.append('class_code', code);

      fetch(action + (action.indexOf('?') === -1 ? '?ajax=1' : '&ajax=1'), {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData
      }).then(function(res){ 
        if (!res.ok) {
          throw new Error('Network response was not ok');
        }
        return res.json(); 
      }).then(function(json){
        if (json && json.ok) {
          if (errorEl){ errorEl.classList.add('hidden'); }
          // Show success message briefly before redirect
          if (errorEl) {
            errorEl.textContent = json.message || 'Berhasil bergabung!';
            errorEl.classList.remove('hidden');
            errorEl.classList.remove('text-red-500');
            errorEl.classList.add('text-green-500');
          }
          // redirect to class detail if provided
          if (json.class_id) {
            setTimeout(function() {
              window.location.href = 'index.php?page=class/detail/' + json.class_id;
            }, 500);
            return;
          }
          // fallback: reload
          setTimeout(function() {
            window.location.reload();
          }, 500);
        } else {
          if (errorEl){ 
            errorEl.textContent = json.message || 'Gagal bergabung'; 
            errorEl.classList.remove('hidden');
            errorEl.classList.remove('text-green-500');
            errorEl.classList.add('text-red-500');
          }
          if (submit){ submit.disabled = false; submit.textContent = 'Gabung Kelas'; }
        }
      }).catch(function(err){
        console.error('Join error:', err);
        if (errorEl){ 
          errorEl.textContent = 'Terjadi kesalahan jaringan.'; 
          errorEl.classList.remove('hidden');
          errorEl.classList.remove('text-green-500');
          errorEl.classList.add('text-red-500');
        }
        if (submit){ submit.disabled = false; submit.textContent = 'Gabung Kelas'; }
      });
    });
  }

  // Create class form via AJAX
  var createForm = document.getElementById('createClassForm');
  if (createForm){
    createForm.addEventListener('submit', function(e){
      e.preventDefault();
      var btn = createForm.querySelector('button[type=submit]');
      if (btn){ btn.disabled = true; btn.textContent = 'Membuat...'; }
      var action = createForm.getAttribute('action') || 'index.php?page=class_store';
      var fd = new FormData(createForm);
      fetch(action + (action.indexOf('?') === -1 ? '?ajax=1' : '&ajax=1'), {
        method: 'POST', headers: { 'Accept': 'application/json' }, body: fd
      }).then(function(r){
        if (!r.ok) {
          throw new Error('Network response was not ok');
        }
        return r.json();
      }).then(function(json){
        if (btn){ btn.disabled = false; btn.textContent = 'Create'; }
        if (json && json.ok) {
          // Show success message
          alert(json.message || 'Kelas berhasil dibuat!');
          // close modal and redirect to class detail
          if (createModal) createModal.classList.add('hidden');
          if (json.class_id) {
            window.location.href = 'index.php?page=class/detail/' + json.class_id;
          } else {
            window.location.reload();
          }
        } else {
          alert(json.message || 'Gagal membuat kelas');
        }
      }).catch(function(err){
        console.error('Create error:', err);
        if (btn){ btn.disabled = false; btn.textContent = 'Create'; }
        alert('Kesalahan jaringan');
      });
    });
  }
});
</script>

