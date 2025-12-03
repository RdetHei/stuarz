// notifications.js - AJAX helpers for notification actions
(function () {
    async function fetchJson(url, opts = {}) {
        opts.headers = Object.assign({ 'Content-Type': 'application/json' }, opts.headers || {});
        const res = await fetch(url, opts);
        try { return await res.json(); } catch (e) { return null; }
    }

    window.NotificationsAPI = {
        markRead: async function (id) { return fetchJson('index.php?page=notifications/mark-read', { method: 'POST', body: JSON.stringify({ id }) }); },
        markUnread: async function (id) { return fetchJson('index.php?page=notifications/mark-unread', { method: 'POST', body: JSON.stringify({ id }) }); },
        delete: async function (id) { return fetchJson('index.php?page=notifications/delete', { method: 'POST', body: JSON.stringify({ id }) }); },
        markAllRead: async function () { return fetchJson('index.php?page=notifications/mark-all-read', { method: 'POST' }); },
        clearAll: async function () { return fetchJson('index.php?page=notifications/clear', { method: 'POST' }); },
        unreadCount: async function () { return fetchJson('index.php?page=notifications/unread-count', { method: 'GET' }); }
    };

    // UI helpers that update DOM after API calls
    window.NotificationsUI = {
        markReadUI: async function (id) {
            const res = await NotificationsAPI.markRead(id);
            if (res && res.success) {
                const el = document.getElementById('notif-' + id);
                if (el) {
                    // remove any classes that begin with 'ring'
                    Array.from(el.classList).forEach(c => {
                        if (c && c.indexOf('ring') === 0) el.classList.remove(c);
                    });
                    // remove the small unread dot (common class 'rounded-full') inside this notification
                    const dot = el.querySelector('.rounded-full');
                    if (dot) dot.remove();

                    // toggle action button to "mark-unread"
                    const btn = el.querySelector('[data-action="mark-read"][data-id="' + id + '"]') || el.querySelector('[data-action="mark-read"]');
                    if (btn) {
                        btn.setAttribute('data-action', 'mark-unread');
                        btn.textContent = 'Tandai Belum Dibaca';
                    }
                }
                try { updateBadge(); } catch (e) { }
            }
            return res;
        },
        markUnreadUI: async function (id) {
            const res = await NotificationsAPI.markUnread(id);
            if (res && res.success) {
                const el = document.getElementById('notif-' + id);
                if (el) {
                    // add unread ring styles
                    el.classList.add('ring-1');
                    el.classList.add('ring-[#5865F2]/20');

                    // ensure unread dot exists
                    const infoRow = el.querySelector('.flex.items-center.gap-2');
                    const hasDot = infoRow && infoRow.querySelector('.rounded-full');
                    if (infoRow && !hasDot) {
                        const dot = document.createElement('span');
                        dot.className = 'flex-shrink-0 w-2 h-2 bg-[#5865F2] rounded-full';
                        infoRow.appendChild(dot);
                    }

                    // toggle action button to "mark-read"
                    const btn = el.querySelector('[data-action="mark-unread"][data-id="' + id + '"]') || el.querySelector('[data-action="mark-unread"]');
                    if (btn) {
                        btn.setAttribute('data-action', 'mark-read');
                        btn.textContent = 'Tandai Dibaca';
                    }
                }
                try { updateBadge(); } catch (e) { }
            }
            return res;
        },
        deleteUI: async function (id) {
            const res = await NotificationsAPI.delete(id);
            if (res && res.success) {
                const el = document.getElementById('notif-' + id);
                if (el) el.remove();
                try { updateBadge(); } catch (e) { }
            }
            return res;
        }
    };

    // Additional UI helpers
    window.NotificationsUI.markAllReadUI = async function () {
        const res = await NotificationsAPI.markAllRead();
        if (res && res.success) location.reload();
        return res;
    };

    window.NotificationsUI.clearAllUI = async function () {
        if (!confirm('Yakin ingin menghapus semua notifikasi?')) return null;
        const res = await NotificationsAPI.clearAll();
        if (res && res.success) location.reload();
        return res;
    };

    // Simple modal for custom notification link handling
    function showNotificationModal(opts) {
        // opts: { id, url, title, message }
        const existing = document.getElementById('custom-notif-modal');
        if (existing) existing.remove();
        const overlay = document.createElement('div');
        overlay.id = 'custom-notif-modal';
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.5)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = 9999;
        const box = document.createElement('div');
        box.style.background = '#0f1724';
        box.style.color = '#fff';
        box.style.padding = '20px';
        box.style.borderRadius = '8px';
        box.style.maxWidth = '720px';
        box.style.width = '90%';
        box.innerHTML = `
            <div style="font-weight:700;font-size:16px;margin-bottom:8px">${opts.title || 'Notifikasi'}</div>
            <div style="margin-bottom:12px;font-size:14px">${opts.message || ''}</div>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button id="custom-notif-open" style="background:#2563eb;color:#fff;padding:8px 12px;border-radius:6px;border:none;cursor:pointer">Buka</button>
                <button id="custom-notif-mark" style="background:#111827;color:#fff;padding:8px 12px;border-radius:6px;border:none;cursor:pointer">Tandai Dibaca</button>
                <button id="custom-notif-close" style="background:transparent;border:1px solid #374151;color:#fff;padding:8px 12px;border-radius:6px;cursor:pointer">Tutup</button>
            </div>`;
        overlay.appendChild(box);
        document.body.appendChild(overlay);

        document.getElementById('custom-notif-close').addEventListener('click', function () { overlay.remove(); });
        document.getElementById('custom-notif-open').addEventListener('click', function () {
            if (opts.url) window.open(opts.url, '_blank');
            overlay.remove();
        });
        document.getElementById('custom-notif-mark').addEventListener('click', function () {
            if (opts.id) { try { NotificationsUI.markReadUI(Number(opts.id)); } catch (e) { } }
            overlay.remove();
        });
    }

    // Delegated click handler for notification actions and links (scoped)
    function handleActionClick(e) {
        const btn = e.target.closest && e.target.closest('[data-action]');
        if (btn) {
            const action = btn.getAttribute('data-action');
            const id = btn.getAttribute('data-id');
            if (action === 'mark-read') {
                e.preventDefault();
                NotificationsUI.markReadUI(Number(id));
                return true;
            }
            if (action === 'mark-unread') {
                e.preventDefault();
                NotificationsUI.markUnreadUI(Number(id));
                return true;
            }
            if (action === 'delete') {
                e.preventDefault();
                if (!confirm('Hapus notifikasi ini?')) return true;
                NotificationsUI.deleteUI(Number(id));
                return true;
            }
            if (action === 'mark-all') {
                e.preventDefault();
                NotificationsUI.markAllReadUI();
                return true;
            }
            if (action === 'clear-all') {
                e.preventDefault();
                NotificationsUI.clearAllUI();
                return true;
            }
        }

        // Intercept notification links with class 'notif-link' to show custom modal
        const link = e.target.closest && e.target.closest('.notif-link');
        if (link) {
            e.preventDefault();
            const url = link.getAttribute('data-notif-url') || link.href;
            const id = link.getAttribute('data-notif-id');
            const title = link.textContent || 'Notifikasi';
            // Find nearby message text if available
            let message = '';
            const parent = link.closest && link.closest('.p-4');
            if (parent) {
                const p = parent.querySelector('p');
                if (p) message = p.textContent || '';
            }
            showNotificationModal({ id: id, url: url, title: title, message: message });
            return true;
        }
        return false;
    }

    // Scope event delegation to notifications page/container only
    function attachDelegation() {
        const root = document.getElementById('notificationsPage');
        if (!root) return;
        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) return; // ignore clicks outside notifications page
            try { handleActionClick(e); } catch (err) { console.error('Notif handler error', err); }
        });
    }

    // Fallback: also attach direct listeners to existing elements on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Buttons/links with data-action
            const root = document.getElementById('notificationsPage');
            const scope = root || document;
            scope.querySelectorAll('[data-action]').forEach(function (el) {
                el.addEventListener('click', function (ev) { handleActionClick(ev); });
            });
            // Links with notif-link class
            scope.querySelectorAll('.notif-link').forEach(function (a) {
                a.addEventListener('click', function (ev) { handleActionClick(ev); });
            });
        } catch (e) { console.error('Notif fallback attach failed', e); }
    });

    // Auto update badge
    async function updateBadge() {
        try {
            const res = await NotificationsAPI.unreadCount();
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            const count = res && res.count ? Number(res.count) : 0;
            if (count > 0) {
                badge.classList.remove('hidden');
                badge.textContent = count > 99 ? '99+' : String(count);
            } else {
                badge.classList.add('hidden');
            }
        } catch (e) { console.error('Notif badge update failed', e); }
    }

    // expose for inline handlers in views
    window.updateBadge = updateBadge;

    // Hook to notif button click to navigate to notifications page
    function attachButton() {
        const btn = document.getElementById('notifBtn');
        if (!btn) return;
        btn.addEventListener('click', function () {
            window.location.href = 'index.php?page=notifications';
        });
    }

    // Init
    document.addEventListener('DOMContentLoaded', function () {
        attachButton();
        attachDelegation();
        updateBadge();
        setInterval(updateBadge, 30000); // refresh every 30s
    });
})();
