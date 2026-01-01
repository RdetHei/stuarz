<?php


$user = $_SESSION['user'] ?? null;
$level = $user['level'] ?? 'user';
$hasClasses = $hasClasses ?? false;
?>
<div class="min-h-screen bg-gray-900 p-6">
  <div class="max-w-3xl mx-auto">
    <div class="mb-6">
      <a href="index.php?page=class" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Kelas Saya
      </a>
      <h1 class="text-3xl font-bold text-white mb-2">Gabung Kelas</h1>
      <p class="text-sm">Masukkan kode kelas untuk bergabung</p>
    </div>

    <?php if ($hasClasses): ?>
    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4 mb-6">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
          <h3 class="text-sm font-semibold text-yellow-300 mb-1">Anda Sudah Memiliki Kelas</h3>
          <p class="text-xs text-gray-400">Anda sudah bergabung dengan kelas. Untuk bergabung dengan kelas tambahan, hubungi guru atau admin kelas tersebut.</p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
      <h2 class="text-xl font-bold text-white mb-4">Masukkan Kode Kelas</h2>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-sm text-red-400">
          <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-lg text-sm text-green-400">
          <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form id="joinClassForm" method="POST" action="index.php?page=join_class" novalidate>
        <div class="mb-4">
          <label class="block text-sm font-medium text-white-100 mb-2">Kode Kelas</label>
          <div class="flex gap-2">
            <input 
              type="text" 
              name="class_code" 
              id="joinCode"
              placeholder="Masukkan kode kelas" 
              required
              <?= $hasClasses ? 'disabled' : '' ?>
              class="flex-1 px-4 py-2.5 bg-gray-900/50 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-colors <?= $hasClasses ? 'opacity-50 cursor-not-allowed' : '' ?>">
            <button 
              type="submit" 
              id="joinSubmit"
              <?= $hasClasses ? 'disabled' : '' ?>
              class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors <?= $hasClasses ? 'opacity-50 cursor-not-allowed' : '' ?>">
              Gabung
            </button>
          </div>
          <p id="joinError" class="mt-2 text-sm text-red-500 hidden"></p>
        </div>
      </form>

      <?php if ($level === 'admin' || $level === 'guru'): ?>
        <div class="mt-6 pt-6 border-t border-gray-700">
          <h3 class="text-sm font-semibold text-gray-300 mb-3">Masuk Kelas Tanpa Kode (Admin/Guru)</h3>
          <form method="POST" action="index.php?page=join_class">
            <div class="flex gap-2">
              <select name="class_id" class="flex-1 px-4 py-2.5 bg-gray-900/50 border border-gray-700 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-colors">
                <option value="">Pilih kelas...</option>
                <?php foreach ($classes as $c): ?>
                  <option value="<?= intval($c['id']) ?>"><?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['code'] ?? '') ?>)</option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">Masuk</button>
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function(){
  var joinForm = document.getElementById('joinClassForm');
  if (joinForm){
    joinForm.addEventListener('submit', function(e){
      e.preventDefault();
      var codeEl = document.getElementById('joinCode');
      var code = codeEl.value.trim();
      var errorEl = document.getElementById('joinError');
      if (!code){ 
        if (errorEl){ 
          errorEl.textContent = 'Kode kelas wajib diisi.'; 
          errorEl.classList.remove('hidden'); 
        } 
        return false; 
      }
      var submit = document.getElementById('joinSubmit');
      if (submit){ submit.disabled = true; submit.textContent = 'Memproses...'; }

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
          if (errorEl){ 
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
            window.location.href = 'index.php?page=class';
          }, 500);
        } else {
          if (errorEl){ 
            errorEl.textContent = json.message || 'Gagal bergabung'; 
            errorEl.classList.remove('hidden');
            errorEl.classList.remove('text-green-500');
            errorEl.classList.add('text-red-500');
          }
          if (submit){ submit.disabled = false; submit.textContent = 'Gabung'; }
        }
      }).catch(function(err){
        console.error('Join error:', err);
        if (errorEl){ 
          errorEl.textContent = 'Terjadi kesalahan jaringan.'; 
          errorEl.classList.remove('hidden');
          errorEl.classList.remove('text-green-500');
          errorEl.classList.add('text-red-500');
        }
        if (submit){ submit.disabled = false; submit.textContent = 'Gabung'; }
      });
    });
  }
});
</script>
