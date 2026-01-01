<?php


$sessionUser = $_SESSION['user'] ?? [];
$classes = $classes ?? [];
$showAll = $showAll ?? false;
$hasClasses = $hasClasses ?? !empty($classes);
?>

<div class="min-h-screen bg-gray-900 p-6">
  <div class="max-w-7xl mx-auto">
    
    
    <header class="mb-8">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-semibold text-gray-100">Kelas Saya</h1>
              <p class="text-sm text-gray-400 mt-0.5">Kelola kelas dan kegiatan belajar Anda</p>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
          <div>
            <?php if ($showAll): ?>
              <a href="index.php?page=class" class="px-4 py-2 rounded-md bg-gray-800 hover:bg-gray-700 text-sm text-gray-300 border border-gray-700 transition-colors">
                Kelas Saya Saja
              </a>
            <?php else: ?>
              <a href="index.php?page=class&show=all" class="px-4 py-2 rounded-md bg-gray-800 hover:bg-gray-700 text-sm text-gray-300 border border-gray-700 transition-colors">
                Tampilkan Semua
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
          <button id="openCreateClassBtn" class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kelas
          </button>
          <?php endif; ?>
        </div>
      </div>
    </header>

    
    <div class="mb-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" id="classSearchForm" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="page" value="class">
        <?php if (isset($_GET['show']) && $_GET['show'] === 'all'): ?>
        <input type="hidden" name="show" value="all">
        <?php endif; ?>
        
        <div class="flex-1 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" 
                 name="q" 
                 id="classSearchInput"
                 placeholder="Cari kelas, kode, atau deskripsi..." 
                 value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-700 text-sm text-gray-200 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-colors placeholder-gray-500">
        </div>

        <select name="filter" 
                id="classFilterSelect"
                class="px-3 py-2 bg-gray-900 border border-gray-700 text-sm text-gray-200 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-colors">
          <option value="">Semua Kelas</option>
          <option value="joined" <?= (isset($_GET['filter']) && $_GET['filter'] === 'joined') ? 'selected' : '' ?>>Kelas yang Diikuti</option>
          <option value="not_joined" <?= (isset($_GET['filter']) && $_GET['filter'] === 'not_joined') ? 'selected' : '' ?>>Kelas yang Belum Diikuti</option>
        </select>

        <button type="submit" 
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap">
          Cari
        </button>
        
        <?php if (!empty($_GET['q']) || !empty($_GET['filter'])): ?>
        <a href="index.php?page=class<?= (isset($_GET['show']) && $_GET['show'] === 'all') ? '&show=all' : '' ?>" 
           class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap">
          Reset
        </a>
        <?php endif; ?>
      </form>
    </div>

    <?php if (!$hasClasses): ?>
      
      <div class="max-w-2xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
          <div class="p-8 text-center border-b border-gray-700">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-700 flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-100 mb-2">Belum Ada Kelas</h3>
            <p class="text-sm text-gray-400">Bergabunglah dengan kelas untuk memulai pembelajaran</p>
          </div>

          
          <div class="p-6 bg-gray-900">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
              </div>
              <h2 class="text-base font-semibold text-gray-100">Gabung Kelas</h2>
            </div>
            
            <?php 
              $joinAction = 'index.php?page=join_class'; 
              $action = $joinAction; // Set action for join form
              include __DIR__ . '/../../components/class/JoinClassForm.php'; 
              unset($action); // Clear action after include to avoid conflicts
            ?>
          </div>

          <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
          <div class="p-6 pt-4 bg-gray-900 border-t border-gray-700">
            <div class="text-center">
              <p class="text-sm text-gray-500 mb-3">atau</p>
              <button onclick="document.getElementById('openCreateClassBtn')?.click()" class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                Buat Kelas Baru
              </button>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    <?php else: ?>
      
      <div>
        
        <div class="bg-gray-800 border border-gray-700 rounded-lg px-5 py-3 mb-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
              <span class="text-sm text-gray-400">Total Kelas</span>
            </div>
            <span class="text-lg font-semibold text-gray-100"><?= count($classes) ?></span>
          </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
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
              
              $roleConfig = [
                'admin' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/30', 'text' => 'text-red-400', 'label' => 'Admin'],
                'creator' => ['bg' => 'bg-orange-500/10', 'border' => 'border-orange-500/30', 'text' => 'text-orange-400', 'label' => 'Creator'],
                'teacher' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/30', 'text' => 'text-purple-400', 'label' => 'Teacher'],
                'student' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/30', 'text' => 'text-blue-400', 'label' => 'Student'],
                'not_joined' => ['bg' => 'bg-gray-700', 'border' => 'border-gray-600', 'text' => 'text-gray-400', 'label' => 'Not Joined']
              ];
              $config = $roleConfig[$role] ?? $roleConfig['student'];
            ?>
            
            
            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors group">
              
              <div class="p-5 border-b border-gray-700">
                <div class="flex items-start justify-between gap-3 mb-3">
                  <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-100 truncate mb-1"><?= htmlspecialchars($name) ?></h3>
              <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                <span class="font-mono"><?= htmlspecialchars($code) ?></span>
                <?php 
                  $waliName = $c['creator'] ?? '';
                  $waliAvatar = $c['creator_avatar'] ?? '';
                  $creatorId = intval($c['created_by'] ?? 0);
                  $waliInitial = strtoupper(mb_substr($waliName !== '' ? $waliName : 'W', 0, 1, 'UTF-8'));
                  $waliAvatarUrl = '';
                  if (!empty($waliAvatar)) {
                    if (preg_match('#^https?://#i', $waliAvatar)) {
                      $waliAvatarUrl = $waliAvatar;
                    } else {
                      $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); if ($baseUrl === '/') $baseUrl = '';
                      $prefix = ($baseUrl ? $baseUrl . '/' : '');
                      $candidate = $prefix . ltrim($waliAvatar, '/\\');
                      $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
                      $candidateFs = $docRoot ? $docRoot . '/' . ltrim($candidate, '/\\') : '';
                      if ($candidateFs && is_file($candidateFs)) {
                        $waliAvatarUrl = $candidate;
                      } else {
                        $altFs = $docRoot ? $docRoot . '/' . ltrim($waliAvatar, '/\\') : '';
                        if ($altFs && is_file($altFs)) { $waliAvatarUrl = ltrim($waliAvatar, '/\\'); }
                        else { $waliAvatarUrl = $waliAvatar; }
                      }
                    }
                  }
                ?>
                <?php if ($waliName !== ''): ?>
                <a href="index.php?page=profile&user_id=<?= $creatorId ?>" class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-gray-700/40 text-gray-300 border border-gray-600 hover:bg-gray-700 transition-colors" title="Wali Kelas">
                  <?php if (!empty($waliAvatarUrl)): ?>
                    <img src="<?= htmlspecialchars($waliAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($waliName, ENT_QUOTES, 'UTF-8') ?>" class="w-4 h-4 rounded-full object-cover border border-gray-600" />
                  <?php else: ?>
                    <span class="w-4 h-4 rounded-full bg-indigo-600/40 text-white flex items-center justify-center text-[10px] font-bold border border-gray-600"><?= htmlspecialchars($waliInitial, ENT_QUOTES, 'UTF-8') ?></span>
                  <?php endif; ?>
                  <span class="text-[11px] truncate max-w-[8rem]"><?= htmlspecialchars($waliName, ENT_QUOTES, 'UTF-8') ?></span>
                </a>
                <?php endif; ?>
              </div>
                  </div>
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium <?= $config['bg'] ?> <?= $config['text'] ?> border <?= $config['border'] ?> whitespace-nowrap">
                    <?= $config['label'] ?>
                  </span>
                </div>
                
                <p class="text-sm text-gray-400 line-clamp-2 min-h-[2.5rem]"><?= htmlspecialchars($description) ?></p>
              </div>

              
              <div class="p-5 bg-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                  <span><?= $members_count ?> anggota</span>
                </div>
                <div class="flex items-center gap-2">
                  <?php 
                    $canDelete = false;
                    $userLevel = $sessionUser['level'] ?? '';
                    $userId = intval($sessionUser['id'] ?? 0);

                    if ($userLevel === 'admin') {
                      $canDelete = true;
                    }

                    if (!$canDelete && isset($c['created_by']) && intval($c['created_by']) === $userId) {
                      $canDelete = true;
                    }
                  ?>
                  <?php if ($canDelete): ?>
                  <button type="button" 
                          class="delete-btn px-3 py-1.5 rounded-md bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 text-sm font-medium transition-colors flex items-center gap-1.5" 
                          title="Hapus Kelas"
                          data-id="<?= intval($c['id'] ?? 0) ?>"
                          data-url="index.php?page=class_delete"
                          data-item-name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                          data-row-selector="tr">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                  </button>
                  <?php endif; ?>
                  <a href="<?= htmlspecialchars($link) ?>" class="px-3 py-1.5 rounded-md bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 text-sm font-medium transition-colors flex items-center gap-1.5">
                    <span>Buka</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>

  
  <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru'): ?>
    <?php 
      $generated_code = strtoupper(substr(bin2hex(random_bytes(3)),0,6)); 
      $createAction = 'index.php?page=class_store'; // Explicitly set action for create form
      $action = $createAction; // Override any existing $action variable
      include __DIR__ . '/../../components/class/CreateClassModal.php'; 
    ?>
  <?php endif; ?>

</div>

<script>

document.addEventListener('DOMContentLoaded', function(){
  var openCreate = document.getElementById('openCreateClassBtn');
  var createModal = document.getElementById('createClassModal');
  var closeCreate = document.getElementById('closeCreateClass');
  var cancelCreate = document.getElementById('cancelCreate');
  
  // Function to setup create form handler
  function setupCreateForm() {
    var createForm = document.getElementById('createClassForm');
    if (createForm && !createForm.hasAttribute('data-handler-attached')) {
      console.log('Create form found, attaching event listener');
      createForm.setAttribute('data-handler-attached', 'true');
      createForm.addEventListener('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        console.log('Create form submitted');
        var btn = createForm.querySelector('button[type=submit]');
        if (btn){ btn.disabled = true; btn.textContent = 'Membuat...'; }
        var action = createForm.getAttribute('action') || 'index.php?page=class_store';
        console.log('Create form action:', action);
        var fd = new FormData(createForm);
        console.log('FormData entries:', Array.from(fd.entries()));
        var url = action + (action.indexOf('?') === -1 ? '?ajax=1' : '&ajax=1');
        console.log('Fetching URL:', url);
        fetch(url, {
          method: 'POST', headers: { 'Accept': 'application/json' }, body: fd
        }).then(function(r){
          return r.text().then(function(text) {
            try {
              var json = JSON.parse(text);
              if (!r.ok) {
                throw new Error(json.message || 'Network response was not ok');
              }
              return json;
            } catch (e) {
              if (!r.ok) {
                throw new Error('Server error: ' + r.status + ' ' + r.statusText + '. Response: ' + text.substring(0, 100));
              }
              throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }
          });
        }).then(function(json){
          if (btn){ btn.disabled = false; btn.textContent = 'Create'; }
          if (json && json.ok) {
            alert(json.message || 'Kelas berhasil dibuat!');
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
          alert('Error: ' + (err.message || 'Kesalahan jaringan. Silakan coba lagi.'));
        });
      });
    }
  }
  
  // Setup form handler immediately
  setupCreateForm();
  
  // Also setup when modal is opened
  if (openCreate && createModal){ 
    openCreate.addEventListener('click', function(e){ 
      e.preventDefault(); 
      createModal.classList.remove('hidden'); 
      // Re-setup form handler in case form was added dynamically
      setTimeout(setupCreateForm, 100);
    }); 
  }
  if (closeCreate) closeCreate.addEventListener('click', function(){ createModal.classList.add('hidden'); });
  if (cancelCreate) cancelCreate.addEventListener('click', function(){ createModal.classList.add('hidden'); });

  var joinForm = document.getElementById('joinClassForm');
  if (joinForm){
    console.log('Join form found, attaching event listener');
    joinForm.addEventListener('submit', function(e){
      e.preventDefault();
      e.stopPropagation();
      console.log('Join form submitted');
      var codeEl = document.getElementById('joinCode');
      var code = codeEl.value.trim();
      var errorEl = document.getElementById('joinError');
      if (!code){ if (errorEl){ errorEl.textContent = 'Kode kelas wajib diisi.'; errorEl.classList.remove('hidden'); } return false; }
      var submit = document.getElementById('joinSubmit');
      if (submit){ submit.disabled = true; submit.textContent = 'Memproses...'; }

      var action = joinForm.getAttribute('action') || 'index.php?page=join_class';
      console.log('Join form action:', action);
      var formData = new FormData();
      formData.append('class_code', code);
      var url = action + (action.indexOf('?') === -1 ? '?ajax=1' : '&ajax=1');
      console.log('Fetching URL:', url);

      fetch(url, {
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

          if (errorEl) {
            errorEl.textContent = json.message || 'Berhasil bergabung!';
            errorEl.classList.remove('hidden');
            errorEl.classList.remove('text-red-500');
            errorEl.classList.add('text-green-500');
          }

          if (json.class_id) {
            setTimeout(function() {
              window.location.href = 'index.php?page=class/detail/' + json.class_id;
            }, 500);
            return;
          }

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

  // Create form handler is now set up above in setupCreateForm() function

  const classSearchForm = document.getElementById('classSearchForm');
  const classSearchInput = document.getElementById('classSearchInput');
  const classFilterSelect = document.getElementById('classFilterSelect');
  let searchTimer = null;

  if (classSearchForm && classSearchInput) {
    function performSearch() {
      const formData = new FormData(classSearchForm);
      const params = new URLSearchParams();
      params.set('page', 'class');
      
      if (formData.get('q')) params.set('q', formData.get('q'));
      if (formData.get('filter')) params.set('filter', formData.get('filter'));
      if (formData.get('show')) params.set('show', formData.get('show'));
      params.set('ajax', '1');

      fetch('index.php?' + params.toString(), { 
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('.max-w-7xl');
        if (newContent) {
          const currentContent = document.querySelector('.max-w-7xl');
          if (currentContent) {
            currentContent.innerHTML = newContent.innerHTML;

            const scripts = doc.querySelectorAll('script');
            scripts.forEach(s => {
              const ns = document.createElement('script');
              if (s.src) ns.src = s.src;
              else ns.textContent = s.textContent;
              document.body.appendChild(ns);
            });
          }
        }

        const friendlyUrl = 'index.php?page=class' + 
          (params.get('q') ? '&q=' + encodeURIComponent(params.get('q')) : '') +
          (params.get('filter') ? '&filter=' + encodeURIComponent(params.get('filter')) : '') +
          (params.get('show') ? '&show=' + encodeURIComponent(params.get('show')) : '');
        try { 
          history.pushState({}, '', friendlyUrl); 
        } catch (e) {}
      })
      .catch(err => console.error('Search error:', err));
    }

    classSearchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      clearTimeout(searchTimer);
      performSearch();
    });

    if (classSearchInput) {
      classSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(performSearch, 300);
      }, { passive: true });
    }

    if (classFilterSelect) {
      classFilterSelect.addEventListener('change', function() {
        clearTimeout(searchTimer);
        performSearch();
      });
    }
  }
});
</script>

