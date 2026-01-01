(function() {
    'use strict';

    let toastContainer = null;

    function initToastContainer() {
        if (toastContainer) return toastContainer;
        
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-3 pointer-events-none';
        document.body.appendChild(toastContainer);
        return toastContainer;
    }

    function showToast(message, type = 'info', duration = 5000) {
        if (!message) return;

        const container = initToastContainer();

        const toast = document.createElement('div');
        toast.className = 'toast-item pointer-events-auto';
        
        const typeConfig = {
            success: {
                bg: 'bg-emerald-500/10',
                border: 'border-emerald-500/30',
                text: 'text-emerald-400',
                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
            },
            error: {
                bg: 'bg-red-500/10',
                border: 'border-red-500/30',
                text: 'text-red-400',
                icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
            },
            warning: {
                bg: 'bg-amber-500/10',
                border: 'border-amber-500/30',
                text: 'text-amber-400',
                icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
            },
            info: {
                bg: 'bg-blue-500/10',
                border: 'border-blue-500/30',
                text: 'text-blue-400',
                icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }
        };

        const config = typeConfig[type] || typeConfig.info;

        toast.className += ` ${config.bg} ${config.border} ${config.text} border rounded-lg shadow-lg p-4 min-w-[320px] max-w-md transform transition-all duration-300 ease-out opacity-0 translate-x-full`;
        
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${config.icon}"/>
                </svg>
                <p class="text-sm font-medium flex-1">${escapeHtml(message)}</p>
                <button class="toast-close ml-2 text-current opacity-60 hover:opacity-100 transition-opacity" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            removeToast(toast);
        });

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-x-full');
            toast.classList.add('opacity-100', 'translate-x-0');
        });

        if (duration > 0) {
            setTimeout(() => {
                removeToast(toast);
            }, duration);
        }

        return toast;
    }

    function removeToast(toast) {
        if (!toast || !toast.parentElement) return;
        
        toast.classList.remove('opacity-100', 'translate-x-0');
        toast.classList.add('opacity-0', 'translate-x-full');
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    window.showToast = showToast;
    window.Toast = {
        success: (message, duration) => showToast(message, 'success', duration),
        error: (message, duration) => showToast(message, 'error', duration),
        warning: (message, duration) => showToast(message, 'warning', duration),
        info: (message, duration) => showToast(message, 'info', duration)
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initToastContainer);
    } else {
        initToastContainer();
    }
})();