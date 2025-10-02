
        document.addEventListener('DOMContentLoaded', function() {
          const editBtn = document.getElementById('editBioBtn');
          const bioTextarea = document.getElementById('bioTextarea');
          const bioHint = document.getElementById('bioHint');
          const bioForm = document.getElementById('bioForm');
          
          let originalBio = bioTextarea.value;

          // Enable edit mode
          editBtn?.addEventListener('click', function() {
            bioTextarea.removeAttribute('readonly');
            bioTextarea.focus();
            bioHint.classList.remove('hidden');
            editBtn.classList.add('hidden');
            originalBio = bioTextarea.value;
          });

          // Handle keyboard shortcuts
          bioTextarea?.addEventListener('keydown', function(e) {
            // Press Enter to save (without Shift for new line)
            if (e.key === 'Enter' && !e.shiftKey) {
              e.preventDefault();
              bioForm.submit();
            }
            
            // Press Escape to cancel
            if (e.key === 'Escape') {
              bioTextarea.value = originalBio;
              bioTextarea.setAttribute('readonly', true);
              bioHint.classList.add('hidden');
              editBtn.classList.remove('hidden');
              bioTextarea.blur();
            }
          });

          // Cancel on blur (click outside)
          bioTextarea?.addEventListener('blur', function() {
            setTimeout(function() {
              if (!bioTextarea.matches(':focus')) {
                bioTextarea.value = originalBio;
                bioTextarea.setAttribute('readonly', true);
                bioHint.classList.add('hidden');
                editBtn.classList.remove('hidden');
              }
            }, 100);
          });

          // Handle form submission
          bioForm?.addEventListener('submit', function(e) {
            bioTextarea.setAttribute('readonly', true);
            bioHint.classList.add('hidden');
            editBtn.classList.remove('hidden');
          });
        });
       