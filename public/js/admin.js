(function () {
    'use strict';

    function getModalElements() {
        return {
            modal: document.getElementById('confirmDeleteModal'),
            modalContent: document.getElementById('confirmDeleteModalContent'),
            modalMessage: document.getElementById('modal-message'),
            cancelBtn: document.getElementById('confirmDeleteCancelBtn'),
            confirmBtn: document.getElementById('confirmDeleteConfirmBtn'),
            confirmBtnText: document.getElementById('confirmDeleteBtnText')
        };
    }

    function waitForModal() {
        const elements = getModalElements();

        if (!elements.modal) {
            if (typeof waitForModal.attempts === 'undefined') {
                waitForModal.attempts = 0;
            }
            waitForModal.attempts++;

            if (waitForModal.attempts < 10) {
                setTimeout(waitForModal, 100);
            } else {
                console.error('[Admin Delete] Modal not found after 10 attempts. Make sure confirm_delete_modal.php is included in layout.');
            }
            return;
        }

        if (!elements.cancelBtn || !elements.confirmBtn) {
            console.warn('[Admin Delete] Modal buttons not found');
            return;
        }

        console.log('[Admin Delete] Modal found, initializing...');
        initWithElements(elements);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(waitForModal, 50);
        });
    } else {
        setTimeout(waitForModal, 50);
    }

    function initWithElements(elements) {
        const { modal, modalContent, modalMessage, cancelBtn, confirmBtn, confirmBtnText } = elements;

        let currentDeleteData = null;
        let currentRowElement = null;

        function showModal() {
            modal.classList.remove('hidden');
            modal.classList.add('show');
            requestAnimationFrame(() => {
                modalContent.style.scale = '1';
                modalContent.style.opacity = '1';
            });
        }

        function hideModal() {
            modalContent.style.scale = '0.95';
            modalContent.style.opacity = '0';
            setTimeout(() => {
                modal.classList.remove('show');
                modal.classList.add('hidden');
                currentDeleteData = null;
                currentRowElement = null;
            }, 300);
        }

        function handleDeleteClick(e) {
            const btn = e.currentTarget || e.target.closest('.delete-btn');
            if (!btn) {
                console.error('[Admin Delete] Delete button not found');
                return;
            }

            const id = btn.getAttribute('data-id');
            const url = btn.getAttribute('data-url');
            const itemName = btn.getAttribute('data-item-name') || 'item ini';
            const rowSelector = btn.getAttribute('data-row-selector') || 'tr';
            const classId = btn.getAttribute('data-class-id');

            if (!id || !url) {
                console.error('[Admin Delete] Missing data-id or data-url attribute', { id, url, btn });
                if (window.showToast) {
                    window.showToast('Error: Data tidak lengkap untuk menghapus', 'error', 3000);
                }
                return;
            }

            currentRowElement = btn.closest(rowSelector);

            currentDeleteData = {
                id: id,
                url: url,
                itemName: itemName,
                classId: classId
            };

            if (modalMessage) {
                modalMessage.textContent = `Apakah Anda yakin ingin menghapus "${itemName}"?`;
            }

            showModal();
        }

        async function handleConfirmDelete() {
            if (!currentDeleteData) return;

            const { id, url } = currentDeleteData;

            confirmBtn.disabled = true;
            confirmBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menghapus...';

            try {
                const formData = new FormData();
                formData.append('id', id);

                if (currentDeleteData.classId) {
                    formData.append('class_id', currentDeleteData.classId);
                    formData.append('user_id', id);
                }

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    hideModal();

                    if (window.showToast) {
                        window.showToast(data.message || 'Data berhasil dihapus', 'success', 3000);
                    }

                    if (currentRowElement) {
                        currentRowElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        currentRowElement.style.opacity = '0';
                        currentRowElement.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                            if (currentRowElement.parentElement) {
                                currentRowElement.parentElement.removeChild(currentRowElement);
                            }
                        }, 300);
                    }
                } else {
                    hideModal();

                    if (window.showToast) {
                        window.showToast(data.message || 'Gagal menghapus data', 'error', 5000);
                    }
                }
            } catch (error) {
                console.error('[Admin Delete] Delete error:', error);

                hideModal();

                if (window.showToast) {
                    window.showToast('Terjadi kesalahan saat menghapus data', 'error', 5000);
                }
            } finally {
                confirmBtn.disabled = false;
                confirmBtnText.textContent = 'Konfirmasi Hapus';
            }
        }

        function init() {
            console.log('[Admin Delete] Initializing delete handlers...');

            const deleteButtons = document.querySelectorAll('.delete-btn');
            console.log('[Admin Delete] Found', deleteButtons.length, 'delete buttons');

            document.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.delete-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('[Admin Delete] Delete button clicked', deleteBtn);
                    handleDeleteClick({ currentTarget: deleteBtn });
                }
            }, true);

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    hideModal();
                });
            } else {
                console.warn('[Admin Delete] Cancel button not found');
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleConfirmDelete();
                });
            } else {
                console.warn('[Admin Delete] Confirm button not found');
            }

            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        hideModal();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });

            console.log('[Admin Delete] Delete handlers initialized');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            setTimeout(init, 100);
        }
    }
})();