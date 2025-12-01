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
            <div class="flex items-center gap-4 text-sm">
              <div class="flex items-center gap-2">
                <span class="text-gray-400">As Teacher:</span>
                <span class="font-bold text-purple-400">
                  <?= count(array_filter($classes, function($c) { return (intval($c['is_joined'] ?? 0) === 1) && ( ($c['member_role'] ?? '') === 'teacher' || ($c['member_role'] ?? '') === 'admin'); })) ?>
                </span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-gray-400">As Student:</span>
                <span class="font-bold text-blue-400">
                  <?= count(array_filter($classes, function($c) { return (intval($c['is_joined'] ?? 0) === 1) && (($c['member_role'] ?? '') === 'student'); })) ?>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-[#111827] border-b border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Anggota</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-[#1f2937] divide-y divide-gray-700">
              <?php foreach ($classes as $c): ?>
                <?php
                  $name = $c['name'] ?? 'Kelas';
                  $code = $c['code'] ?? '';
                  $description = $c['description'] ?? '-';
                  $members_count = isset($c['members_count']) ? intval($c['members_count']) : 0;
                  $is_joined = intval($c['is_joined'] ?? 0);
                  // FIXED: Use is_joined and member_role from query result, NOT from sessionUser.level
                  // member_role is NULL if user hasn't joined; populated only if user is member
                  $role = $c['member_role'] ?? null;
                  
                  // Only fallback to creator check if member_role is null and user is creator
                  if ($role === null && isset($c['created_by']) && isset($sessionUser['id']) && intval($c['created_by']) === intval($sessionUser['id'])) {
                    // User created the class, so they have creator access
                    $role = 'creator';
                  }
                  
                  // If still null, default to 'student' (for display only, not actual role)
                  if ($role === null) {
                    $role = 'not_joined';
                  }
                  
                  $link = 'index.php?page=class/detail/' . ($c['id'] ?? '');
                  
                  // Role colors - updated to include 'creator' and 'not_joined'
                  $roleColors = [
                    'admin' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
                    'creator' => ['bg' => 'bg-orange-500/10', 'border' => 'border-orange-500/20', 'text' => 'text-orange-400'],
                    'teacher' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
                    'student' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400'],
                    'not_joined' => ['bg' => 'bg-gray-500/10', 'border' => 'border-gray-500/20', 'text' => 'text-gray-400']
                  ];
                  $colors = $roleColors[$role] ?? $roleColors['student'];
                ?>
                <tr class="hover:bg-gray-700/50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-white">
                      <?= htmlspecialchars($name) ?>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 rounded text-xs font-mono font-medium bg-gray-700/50 text-gray-300 border border-gray-600">
                      <?= htmlspecialchars($code) ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                      </svg>
                      <span><?= $members_count ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php if ($is_joined === 1 && $c['member_role']): ?>
                    <!-- User is joined: show their actual role -->
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium <?= $colors['bg'] ?> <?= $colors['border'] ?> <?= $colors['text'] ?> border">
                      <?php if ($c['member_role'] === 'admin'): ?>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                      <?php elseif ($c['member_role'] === 'teacher'): ?>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                      <?php else: ?>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                      <?php endif; ?>
                      <span class="capitalize"><?= htmlspecialchars($c['member_role'] ?? 'member') ?></span>
                    </span>
                    <?php elseif ($role === 'creator'): ?>
                    <!-- User is creator but hasn't joined as member: show creator role -->
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-orange-500/10 border-orange-500/20 text-orange-400 border">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                      </svg>
                      <span>Creator</span>
                    </span>
                    <?php else: ?>
                    <!-- User is NOT a member and not creator: show not joined -->
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-500/10 border-gray-500/20 text-gray-400 border">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                      <span>Not Joined</span>
                    </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-400 max-w-xs truncate" title="<?= htmlspecialchars($description) ?>">
                      <?= htmlspecialchars($description) ?>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= htmlspecialchars($link) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 hover:border-blue-500/40 transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                      Masuk
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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

