// Tasks page enhancements: search/filter and AJAX submission upload with progress
(function(){
  function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
  function qsa(sel, ctx){ return Array.from((ctx||document).querySelectorAll(sel)); }

  // Filtering
  const searchInput = qs('#taskSearch');
  const statusSelect = qs('#filterStatus');
  const clearBtn = qs('#clearFilters');
  const tasksGrid = qs('#tasksGrid');

  function applyFilters(){
    const q = (searchInput?.value||'').trim().toLowerCase();
    const status = (statusSelect?.value||'all');
    if (!tasksGrid) return;
    qsa('[data-title],[data-class],[data-subject]', tasksGrid).forEach(card => {
      const title = (card.getAttribute('data-title')||'').toLowerCase();
      const cls = (card.getAttribute('data-class')||'').toLowerCase();
      const subj = (card.getAttribute('data-subject')||'').toLowerCase();
      const st = (card.getAttribute('data-status')||'').toLowerCase();
      const matchesQuery = q === '' || title.includes(q) || cls.includes(q) || subj.includes(q);
      const matchesStatus = status === 'all' || st === status;
      card.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
  }

  if (searchInput) searchInput.addEventListener('input', applyFilters);
  if (statusSelect) statusSelect.addEventListener('change', applyFilters);
  if (clearBtn) clearBtn.addEventListener('click', function(){ if(searchInput) searchInput.value=''; if(statusSelect) statusSelect.value='all'; applyFilters(); });

  // Submission modal and upload
  window.openSubmitModal = function(opts){
    const modal = document.getElementById('submitModal');
    if (!modal) return;
    document.getElementById('submit_task_id').value = opts.taskId || '';
    document.getElementById('submit_class_id').value = opts.classId || '';
    document.getElementById('submit_file').value = '';
    document.getElementById('filePreview').innerHTML = '';
    document.getElementById('uploadProgress').style.width = '0%';
    modal.classList.remove('hidden');
  };
  window.closeSubmitModal = function(){
    const modal = document.getElementById('submitModal'); if (!modal) return; modal.classList.add('hidden');
  };

  const fileInput = document.getElementById('submit_file');
  const preview = document.getElementById('filePreview');
  if (fileInput){
    fileInput.addEventListener('change', function(){
      const f = this.files[0];
      if (!f) { preview.innerHTML=''; return; }
      let out = `<div class="text-sm text-gray-200">Selected: ${f.name} • ${(f.size/1024).toFixed(1)} KB</div>`;
      if (f.type.startsWith('image/')){
        const url = URL.createObjectURL(f);
        out += `<div class="mt-2"><img src="${url}" class="max-h-40 rounded-md"/></div>`;
      }
      preview.innerHTML = out;
    });
  }

  const submitBtn = document.getElementById('submitBtn');
  if (submitBtn){
    submitBtn.addEventListener('click', function(){
      const form = document.getElementById('submitForm');
      const file = document.getElementById('submit_file').files[0];
      if (!file){ alert('Pilih file terlebih dahulu'); return; }
      const fd = new FormData(form);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', form.action, true);
      xhr.upload.addEventListener('progress', function(ev){
        if (ev.lengthComputable){
          const pct = Math.round((ev.loaded/ev.total)*100);
          document.getElementById('uploadProgress').style.width = pct + '%';
        }
      });
      xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300){
          // Try to parse JSON response
          let json = null;
          try { json = JSON.parse(xhr.responseText); } catch(e) { json = null; }
          if (json && typeof json.success !== 'undefined') {
            if (json.success) {
              // show success message, close modal and update progress bar to full
              alert(json.message || 'Upload berhasil');
              window.closeSubmitModal();
              document.getElementById('uploadProgress').style.width = '100%';
            } else {
              alert(json.message || 'Upload gagal');
            }
          } else {
            // Fallback for non-JSON responses
            alert('Upload selesai (server response).');
            window.closeSubmitModal();
          }
        } else {
          alert('Upload gagal: ' + xhr.statusText);
        }
      };
      xhr.onerror = function(){ alert('Network error during upload'); };
      xhr.send(fd);
    });
  }

  // Initialize filters on load
  document.addEventListener('DOMContentLoaded', applyFilters);
})();
