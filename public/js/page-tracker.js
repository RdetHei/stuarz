// Page Tracking and Toast Notification System
class PageTracker {
    constructor() {
        this.createToastContainer();
        this.lastPath = window.location.pathname;
        this.setupEventListeners();
    }

    createToastContainer() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toastContainer')) {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none';
            document.body.appendChild(container);
        }

        // Add toast styles if not already present
        if (!document.getElementById('toastStyles')) {
            const style = document.createElement('style');
            style.id = 'toastStyles';
            style.textContent = `
                .page-toast {
                    background-color: #1a1a1a;
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.5rem;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    margin-bottom: 0.5rem;
                    opacity: 0;
                    transform: translateY(-1rem);
                    transition: all 0.3s ease;
                    border: 1px solid #333;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.875rem;
                }
                .page-toast.show {
                    opacity: 1;
                    transform: translateY(0);
                }
                .page-toast-icon {
                    font-size: 1.2em;
                }
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(1rem);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    getPageName(path) {
        // Remove leading and trailing slashes and split path
        const parts = path.split('/').filter(Boolean);
        if (parts.length === 0) return 'Home';

        // Get the last part of the path
        let pageName = parts[parts.length - 1];
        
        // Convert kebab-case to Title Case
        pageName = pageName
            .split('-')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');

        // Remove file extensions if any
        pageName = pageName.replace(/\.[^/.]+$/, '');

        return pageName;
    }

    showToast(pageName, icon = '📍') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'page-toast';
        toast.innerHTML = `
            <span class="page-toast-icon">${icon}</span>
            <span>Menuju halaman: ${pageName}</span>
        `;
        container.appendChild(toast);

        // Trigger reflow for animation
        toast.offsetHeight;
        toast.classList.add('show');

        // Remove toast after animation
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => container.removeChild(toast), 300);
        }, 3000);
    }

    handlePathChange() {
        const currentPath = window.location.pathname;
        if (currentPath !== this.lastPath) {
            const pageName = this.getPageName(currentPath);
            this.showToast(pageName);
            this.lastPath = currentPath;
        }
    }

    setupEventListeners() {
        // Listen for navigation events
        window.addEventListener('popstate', () => this.handlePathChange());
        
        // Intercept link clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && link.href.startsWith(window.location.origin)) {
                const newPath = new URL(link.href).pathname;
                if (newPath !== this.lastPath) {
                    const pageName = this.getPageName(newPath);
                    this.showToast(pageName);
                    this.lastPath = newPath;
                }
            }
        });

        // Handle initial page load
        document.addEventListener('DOMContentLoaded', () => {
            const pageName = this.getPageName(window.location.pathname);
            if (pageName !== 'Home') {
                this.showToast(pageName);
            }
        });
    }
}

// Initialize the page tracker
const pageTracker = new PageTracker();



