// Grades page enhancements: AJAX grading + quick grade buttons
(function(){
  function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
  function qsa(sel, ctx){ return Array.from((ctx||document).querySelectorAll(sel)); }

  // Intercept grade forms
  qsa('.grade-form').forEach(form => {
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const action = form.getAttribute('action');
      const data = new FormData(form);
      fetch(action, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(r => r.text())
        .then(txt => {
          alert('Nilai tersimpan');
          // Optionally refresh the row or whole page
          window.location.reload();
        })
        .catch(err => { alert('Error menyimpan nilai'); console.error(err); });
    });
  });

  // Quick grade buttons
  qsa('.quick-grade').forEach(btn => {
    btn.addEventListener('click', function(){
      const score = this.getAttribute('data-score');
      const form = this.closest('.grade-form');
      if (!form) return;
      const scoreInput = form.querySelector('input[name="score"]');
      if (scoreInput) scoreInput.value = score;
      // auto-submit
      form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    });
  });

})();
