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
                }
                try { updateBadge(); } catch (e) { }
            }
            return res;
        },
        markUnreadUI: async function (id) {
            const res = await NotificationsAPI.markUnread(id);
            if (res && res.success) {
                // simpler: reload to reflect unread state
                location.reload();
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

    // Auto update badge
    async function updateBadge() {
        try {
            const res = await NotificationsAPI.unreadCount();
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            const count = res && res.count ? Number(res.count) : 0;
            if (count > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = count > 99 ? '99+' : String(count);
            } else {
                badge.style.display = 'none';
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
        updateBadge();
        setInterval(updateBadge, 30000); // refresh every 30s
    });
})();
