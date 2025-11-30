<?php
// Student: Submit Task
// Expects: $task (task data)
$task = $task ?? [];
$taskId = intval($_GET['task_id'] ?? $task['id'] ?? 0);
?>
<div class="max-w-2xl mx-auto p-6">
  <div class="mb-6">
    <h2 class="text-2xl font-semibold text-white">Submit Tugas</h2>
    <div class="text-sm text-gray-400"><?= htmlspecialchars($task['title'] ?? 'Tugas') ?> — Deadline: <span class="text-white"><?= htmlspecialchars($task['deadline'] ?? '-') ?></span></div>
  </div>

  <form id="submitForm" action="index.php?page=student/submit_action" method="post" enctype="multipart/form-data" class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <input type="hidden" name="task_id" value="<?= $taskId ?>">

    <div id="dropZone" class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center bg-gray-900 text-gray-300">
      <p class="mb-3">Tarik dan lepas file di sini, atau klik untuk memilih</p>
      <input type="file" name="file" id="fileInput" class="hidden">
      <div id="selectedFile" class="text-sm text-gray-200">Belum memilih file</div>
    </div>

    <div class="mt-4">
      <div class="w-full bg-gray-700 rounded h-2 overflow-hidden">
        <div id="uploadProgress" class="h-2 bg-indigo-500 w-0"></div>
      </div>
      <div id="uploadStatus" class="text-sm text-gray-400 mt-2">Belum mengunggah</div>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Mulai Upload</button>
      <a href="index.php?page=student/task_detail&id=<?= $taskId ?>" class="text-gray-300">Kembali</a>
    </div>
  </form>
</div>

<script>
const drop = document.getElementById('dropZone');
const input = document.getElementById('fileInput');
const selected = document.getElementById('selectedFile');
const form = document.getElementById('submitForm');
const progress = document.getElementById('uploadProgress');
const statusText = document.getElementById('uploadStatus');

drop.addEventListener('click', () => input.click());
drop.addEventListener('dragover', (e) => { e.preventDefault(); drop.classList.add('ring-2','ring-indigo-500'); });
drop.addEventListener('dragleave', () => { drop.classList.remove('ring-2','ring-indigo-500'); });
drop.addEventListener('drop', (e) => {
  e.preventDefault(); drop.classList.remove('ring-2','ring-indigo-500');
  if (e.dataTransfer.files.length) {
    input.files = e.dataTransfer.files;
    selected.textContent = input.files[0].name + ' (' + (input.files[0].size/1024/1024).toFixed(2) + 'MB)';
  }
});

input.addEventListener('change', () => {
  if (input.files.length) selected.textContent = input.files[0].name + ' (' + (input.files[0].size/1024/1024).toFixed(2) + 'MB)';
  else selected.textContent = 'Belum memilih file';
});

form.addEventListener('submit', function(e){
  e.preventDefault();
  if (!input.files.length) { alert('Pilih file terlebih dahulu'); return; }

  const fd = new FormData(form);
  const xhr = new XMLHttpRequest();
  xhr.open('POST', form.action);
  xhr.upload.addEventListener('progress', function(ev){
    if (ev.lengthComputable) {
      const p = Math.round((ev.loaded/ev.total)*100);
      progress.style.width = p + '%';
      statusText.textContent = 'Mengunggah: ' + p + '%';
    }
  });
  xhr.onreadystatechange = function(){
    if (xhr.readyState===4) {
      if (xhr.status>=200 && xhr.status<300) {
        statusText.textContent = 'Upload berhasil';
        window.location.href = 'index.php?page=student/task_detail&id=<?= $taskId ?>';
      } else {
        statusText.textContent = 'Upload gagal';
        alert('Upload gagal. Periksa koneksi atau ukuran file.');
      }
    }
  };
  xhr.send(fd);
});
</script>
