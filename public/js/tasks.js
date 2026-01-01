(function () {
  'use strict';

  const taskForm = document.getElementById('taskForm');
  if (taskForm) {
    taskForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const submitBtn = taskForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      const formErrors = document.getElementById('formErrors');

      submitBtn.disabled = true;
      submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';

      if (formErrors) {
        formErrors.classList.add('hidden');
        formErrors.textContent = '';
      }

      try {
        const formData = new FormData(taskForm);
        const action = taskForm.getAttribute('action');

        const response = await fetch(action, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await response.json();

        if (data.success) {
          if (window.showToast) {
            window.showToast(data.message || 'Tugas berhasil disimpan.', 'success', 3000);
          }
          setTimeout(() => {
            window.location.href = 'index.php?page=tasks';
          }, 500);
        } else {
          const errorMsg = data.message || 'Gagal menyimpan tugas.';
          if (formErrors) {
            formErrors.textContent = errorMsg;
            formErrors.classList.remove('hidden');
          }
          if (window.showToast) {
            window.showToast(errorMsg, 'error', 5000);
          }
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      } catch (error) {
        console.error('Error submitting task form:', error);
        const errorMsg = 'Terjadi kesalahan saat menyimpan tugas.';
        if (formErrors) {
          formErrors.textContent = errorMsg;
          formErrors.classList.remove('hidden');
        }
        if (window.showToast) {
          window.showToast(errorMsg, 'error', 5000);
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    });
  }

  const submitForm = document.getElementById('submitForm');
  if (submitForm && submitForm.closest('#submitModal')) {
    submitForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const submitBtn = submitForm.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.innerHTML : 'Submit';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
      }

      try {
        const formData = new FormData(submitForm);
        const response = await fetch(submitForm.getAttribute('action'), {
          method: 'POST',
          body: formData,
          credentials: 'same-origin',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await response.json();

        if (data.success) {
          if (window.showToast) {
            window.showToast(data.message || 'Pengumpulan berhasil disimpan.', 'success', 3000);
          }
          if (typeof closeSubmitModal === 'function') {
            closeSubmitModal();
          }
          setTimeout(() => {
            window.location.href = 'index.php?page=student/tasks';
          }, 500);
        } else {
          if (window.showToast) {
            window.showToast(data.message || 'Gagal menyimpan pengumpulan.', 'error', 5000);
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        }
      } catch (error) {
        console.error('Error submitting task:', error);
        if (window.showToast) {
          window.showToast('Terjadi kesalahan saat mengirim tugas.', 'error', 5000);
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      }
    });
  }

  const studentSubmitForm = document.getElementById('submitForm');
  if (studentSubmitForm && !studentSubmitForm.closest('#submitModal')) {
    studentSubmitForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const submitBtn = studentSubmitForm.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.innerHTML : 'Submit';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
      }

      try {
        const formData = new FormData(studentSubmitForm);
        const response = await fetch(studentSubmitForm.getAttribute('action'), {
          method: 'POST',
          body: formData,
          credentials: 'same-origin',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await response.json();

        if (data.success) {
          if (window.showToast) {
            window.showToast(data.message || 'Pengumpulan berhasil disimpan.', 'success', 3000);
          }
          const taskIdInput = studentSubmitForm.querySelector('input[name="task_id"]');
          const taskId = taskIdInput ? taskIdInput.value : '';
          setTimeout(() => {
            window.location.href = taskId ? `index.php?page=student/task_detail&id=${taskId}` : 'index.php?page=student/tasks';
          }, 500);
        } else {
          if (window.showToast) {
            window.showToast(data.message || 'Gagal menyimpan pengumpulan.', 'error', 5000);
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        }
      } catch (error) {
        console.error('Error submitting task:', error);
        if (window.showToast) {
          window.showToast('Terjadi kesalahan saat mengirim tugas.', 'error', 5000);
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      }
    });
  }

  async function handleReviewFormSubmit(e) {
    let form = e.target;
    if (!form || form.tagName !== 'FORM') {
      const clickedBtn = e.target.closest('button[type="submit"]');
      if (clickedBtn) {
        form = clickedBtn.closest('form');
      }
    }

    if (!form || form.tagName !== 'FORM') return;

    const action = (form.getAttribute('action') || form.action || '').toLowerCase();
    const hasReviewClass = form.classList.contains('review-form');
    const hasReviewAction = action.includes('tasks/review') ||
      action.includes('page=tasks/review') ||
      action.includes('tasks%2Freview') ||
      (action.includes('review') && action.includes('task'));
    const isReviewForm = hasReviewClass || hasReviewAction;

    if (!isReviewForm) return;

    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    console.log('[Tasks.js] Review form intercepted:', { action, hasReviewClass, hasReviewAction, form });

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn ? submitBtn.innerHTML : 'Simpan';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
    }

    try {
      const formData = new FormData(form);
      const formAction = form.getAttribute('action') || form.action || '';
      console.log('[Tasks.js] Submitting review form to:', formAction);
      const response = await fetch(formAction, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Response is not JSON');
      }

      const data = await response.json();

      if (data.success) {
        if (window.showToast) {
          window.showToast(data.message || 'Review berhasil disimpan.', 'success', 3000);
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersimpan';
          submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
          submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        }
      } else {
        if (window.showToast) {
          window.showToast(data.message || 'Gagal menyimpan review.', 'error', 5000);
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      }
    } catch (error) {
      console.error('Error submitting review:', error);
      if (window.showToast) {
        window.showToast('Terjadi kesalahan saat menyimpan review.', 'error', 5000);
      }
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    }
  }

  document.addEventListener('submit', handleReviewFormSubmit, true);

  document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.delete-btn');
    if (!deleteBtn) return;

    const deleteUrl = deleteBtn.getAttribute('data-url');
    if (!deleteUrl || !deleteUrl.includes('tasks/delete')) return;

    e.preventDefault();
    e.stopPropagation();

    const itemId = deleteBtn.getAttribute('data-id');
    const itemName = deleteBtn.getAttribute('data-item-name') || 'tugas ini';

    if (!itemId || !deleteUrl) {
      console.error('[Tasks Delete] Missing data-id or data-url attribute');
      if (window.showToast) {
        window.showToast('Error: Data tidak lengkap untuk menghapus', 'error', 3000);
      }
      return;
    }

    const modal = document.getElementById('confirmDeleteModal');
    const modalMessage = document.getElementById('modal-message');
    const confirmBtn = document.getElementById('confirmDeleteConfirmBtn');
    const confirmBtnText = document.getElementById('confirmDeleteBtnText');

    if (modal && modalMessage && confirmBtn) {
      const taskDeleteData = {
        url: deleteUrl,
        id: itemId,
        name: itemName
      };

      modalMessage.textContent = `Apakah Anda yakin ingin menghapus "${itemName}"?`;

      modal.classList.remove('hidden');
      modal.classList.add('show');
      const modalContent = document.getElementById('confirmDeleteModalContent');
      if (modalContent) {
        requestAnimationFrame(() => {
          modalContent.style.scale = '1';
          modalContent.style.opacity = '1';
        });
      }

      const handleTaskDelete = async function (e) {
        e.preventDefault();
        e.stopPropagation();

        confirmBtn.disabled = true;
        if (confirmBtnText) {
          confirmBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menghapus...';
        }

        try {
          const response = await fetch(taskDeleteData.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: taskDeleteData.id })
          });

          if (!response.ok) {
            throw new Error('Network response was not ok.');
          }

          const data = await response.json();

          if (data.success) {
            if (window.showToast) {
              window.showToast(data.message || 'Tugas berhasil dihapus.', 'success');
            }

            const elementToRemove = document.getElementById('task-row-' + taskDeleteData.id);

            if (elementToRemove) {
              elementToRemove.style.transition = 'opacity 0.5s ease';
              elementToRemove.style.opacity = '0';

              setTimeout(() => {
                elementToRemove.remove();
              }, 500); 
            }

            if (modal) {
              const modalContent = document.getElementById('confirmDeleteModalContent');
              if (modalContent) {
                modalContent.style.scale = '0.95';
                modalContent.style.opacity = '0';
              }
              setTimeout(() => {
                modal.classList.remove('show');
                modal.classList.add('hidden');
              }, 300);
            }
          } else {
            if (window.showToast) {
              window.showToast(data.message || 'Terjadi kesalahan.', 'error');
            }
            if (modal) {
              const modalContent = document.getElementById('confirmDeleteModalContent');
              if (modalContent) {
                modalContent.style.scale = '0.95';
                modalContent.style.opacity = '0';
              }
              setTimeout(() => {
                modal.classList.remove('show');
                modal.classList.add('hidden');
              }, 300);
            }
          }
        } catch (error) {
          console.error('Fetch error:', error);
          if (window.showToast) {
            window.showToast('Tidak dapat terhubung ke server.', 'error');
          }
          if (modal) {
            const modalContent = document.getElementById('confirmDeleteModalContent');
            if (modalContent) {
              modalContent.style.scale = '0.95';
              modalContent.style.opacity = '0';
            }
            setTimeout(() => {
              modal.classList.remove('show');
              modal.classList.add('hidden');
            }, 300);
          }
        } finally {
          confirmBtn.disabled = false;
          if (confirmBtnText) {
            confirmBtnText.textContent = 'Konfirmasi Hapus';
          }
          confirmBtn.removeEventListener('click', handleTaskDelete);
        }
      };

      confirmBtn.addEventListener('click', handleTaskDelete, { once: false });
    } else {
      if (confirm(`Apakah Anda yakin ingin menghapus "${itemName}"?`)) {
        fetch(deleteUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ id: itemId })
        })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok.');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              if (window.showToast) {
                window.showToast(data.message || 'Tugas berhasil dihapus.', 'success');
              }
              const elementToRemove = document.getElementById('task-row-' + itemId);
              if (elementToRemove) {
                elementToRemove.style.transition = 'opacity 0.5s ease';
                elementToRemove.style.opacity = '0';
                setTimeout(() => {
                  elementToRemove.remove();
                }, 500);
              }
            } else {
              if (window.showToast) {
                window.showToast(data.message || 'Terjadi kesalahan.', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Fetch error:', error);
            if (window.showToast) {
              window.showToast('Tidak dapat terhubung ke server.', 'error');
            }
          });
      }
    }
  }, true); 
})();