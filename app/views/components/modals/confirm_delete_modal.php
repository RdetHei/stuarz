<?php
/**
 * Reusable Delete Confirmation Modal
 * Usage: Include this file in layout, then use JavaScript to show/hide it
 */
?>


<div id="confirmDeleteModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/80" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0" id="confirmDeleteModalContent">
        
        
        <div class="px-5 py-3 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 id="modal-title" class="text-base font-semibold text-gray-100">Konfirmasi Penghapusan</h3>
            </div>
        </div>

        
        <div class="px-5 py-4 bg-gray-900">
            <p id="modal-message" class="text-sm text-gray-300 mb-2">Apakah Anda yakin ingin menghapus item ini?</p>
            <p class="text-xs text-gray-500">Data yang dihapus tidak dapat dikembalikan.</p>
        </div>

        
        <div class="px-5 py-3 bg-gray-900 border-t border-gray-700 flex items-center justify-end gap-2">
            <button type="button" 
                    id="confirmDeleteCancelBtn"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 text-sm font-medium rounded-md border border-gray-600 transition-colors">
                Cancel
            </button>
            <button type="button" 
                    id="confirmDeleteConfirmBtn"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span id="confirmDeleteBtnText">Delete</span>
            </button>
        </div>
    </div>
</div>

<style>
#confirmDeleteModal.show #confirmDeleteModalContent {
    scale: 1;
    opacity: 1;
}
</style>