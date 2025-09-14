// ...new file...
(() => {
    'use strict';

    // Kelas popup (dipindahkan ke body saat diperlukan)
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('kelasBtn');
        const popup = document.getElementById('kelasPopup');
        if (btn && popup) {
            let moved = false;
            let hideTimeout = null;

            const ensureInBody = () => {
                if (!moved) {
                    document.body.appendChild(popup);
                    moved = true;
                    popup.style.position = 'fixed';
                    popup.setAttribute('aria-hidden', 'true');
                }
            };

            const positionPopup = () => {
                ensureInBody();
                const rect = btn.getBoundingClientRect();
                const gap = 6;
                let top = Math.max(8, Math.min(window.innerHeight - popup.offsetHeight - 8, rect.top));
                let left = Math.round(rect.right + gap);
                if (left + popup.offsetWidth > window.innerWidth - 8) {
                    left = Math.max(8, rect.left - gap - popup.offsetWidth);
                }
                popup.style.top = top + 'px';
                popup.style.left = left + 'px';
            };

            const showPopup = () => {
                clearTimeout(hideTimeout);
                ensureInBody();
                popup.classList.remove('hidden');
                popup.setAttribute('aria-hidden', 'false');
                btn.setAttribute('aria-expanded', 'true');
                positionPopup();
            };

            const hidePopupSoon = (delay = 120) => {
                clearTimeout(hideTimeout);
                hideTimeout = setTimeout(() => {
                    popup.classList.add('hidden');
                    popup.setAttribute('aria-hidden', 'true');
                    btn.setAttribute('aria-expanded', 'false');
                }, delay);
            };

            btn.addEventListener('pointerenter', showPopup);
            btn.addEventListener('pointerleave', (e) => {
                const to = e.relatedTarget;
                if (popup.contains(to)) return;
                hidePopupSoon();
            });

            popup.addEventListener('pointerenter', () => clearTimeout(hideTimeout));
            popup.addEventListener('pointerleave', (e) => {
                const to = e.relatedTarget;
                if (btn.contains(to)) return;
                hidePopupSoon();
            });

            btn.addEventListener('focus', showPopup);
            btn.addEventListener('blur', () => hidePopupSoon(120));
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (popup.classList.contains('hidden')) showPopup();
                else hidePopupSoon(0);
            });

            document.addEventListener('click', (e) => {
                if (!popup.contains(e.target) && !btn.contains(e.target)) {
                    popup.classList.add('hidden');
                    popup.setAttribute('aria-hidden', 'true');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });

            window.addEventListener('resize', () => { if (!popup.classList.contains('hidden')) positionPopup(); });
            window.addEventListener('scroll', () => { if (!popup.classList.contains('hidden')) positionPopup(); }, true);
        }

        // Sidebar/profile modal & collapse behavior
        const sidebar = document.getElementById('sidebar');
        const toggleInternal = document.getElementById('sidebarToggle');
        const logoToggle = document.getElementById('sidebarLogoToggle');
        const logo = document.getElementById('sidebarLogo');
        const menuIcon = document.getElementById('sidebarMenuIcon');
        const menuTexts = document.querySelectorAll('.menu-text');
        const profileBtn = document.getElementById('profileBtn');
        const profileModal = document.getElementById('profileModal');
        const header = document.getElementById('dHeader');
        const content = document.getElementById('content');

        if (sidebar) {
            let modalMoved = false;

            const ensureModalInBody = () => {
                if (!profileModal || modalMoved) return;
                document.body.appendChild(profileModal);
                profileModal.style.position = 'fixed';
                profileModal.style.zIndex = '99999';
                profileModal.style.pointerEvents = 'auto';
                modalMoved = true;
            };

            const measureModal = () => {
                if (!profileModal) return { w: 260, h: 180 };
                const wasHidden = profileModal.classList.contains('hidden');
                let w, h;
                if (wasHidden) {
                    profileModal.style.visibility = 'hidden';
                    profileModal.classList.remove('hidden');
                    w = profileModal.offsetWidth;
                    h = profileModal.offsetHeight;
                    profileModal.classList.add('hidden');
                    profileModal.style.visibility = '';
                } else {
                    w = profileModal.offsetWidth;
                    h = profileModal.offsetHeight;
                }
                return { w: w || 260, h: h || 180 };
            };

            const positionProfileModal = () => {
                if (!profileModal || !profileBtn) return;
                ensureModalInBody();
                const btnRect = profileBtn.getBoundingClientRect();
                const { w: modalWidth, h: modalHeight } = measureModal();
                const gap = 8;
                const spaceBelow = window.innerHeight - btnRect.bottom;
                const spaceAbove = btnRect.top;
                let top;
                if (spaceBelow >= modalHeight + gap) top = Math.round(btnRect.bottom + gap);
                else if (spaceAbove >= modalHeight + gap) top = Math.round(btnRect.top - modalHeight - gap);
                else top = Math.round(Math.max(8, Math.min(window.innerHeight - modalHeight - 8, btnRect.top)));
                let left = Math.round(btnRect.left);
                left = Math.min(Math.max(8, left), Math.max(8, window.innerWidth - modalWidth - 8));
                profileModal.style.top = top + 'px';
                profileModal.style.left = left + 'px';
            };

            const setCollapsed = (collapsed) => {
                try {
                    if (collapsed) {
                        sidebar.classList.remove('w-64'); sidebar.classList.add('w-16');
                        menuTexts.forEach(el => el.classList.add('hidden'));
                        if (header) { header.classList.remove('ml-64'); header.classList.add('ml-16'); }
                        if (content) { content.classList.remove('ml-64'); content.classList.add('ml-16'); }
                        if (logo) logo.classList.add('hidden');
                        if (menuIcon) menuIcon.classList.remove('hidden');
                        localStorage.setItem('sidebarCollapsed', '1');
                    } else {
                        sidebar.classList.remove('w-16'); sidebar.classList.add('w-64');
                        menuTexts.forEach(el => el.classList.remove('hidden'));
                        if (header) { header.classList.remove('ml-16'); header.classList.add('ml-64'); }
                        if (content) { content.classList.remove('ml-16'); content.classList.add('ml-64'); }
                        if (logo) logo.classList.remove('hidden');
                        if (menuIcon) menuIcon.classList.add('hidden');
                        localStorage.setItem('sidebarCollapsed', '0');
                    }
                } catch (err) {
                    console.error('setCollapsed error', err);
                } finally {
                    setTimeout(positionProfileModal, 50);
                }
            };

            // initialize from storage
            const collapsed = localStorage.getItem('sidebarCollapsed') === '1';
            setCollapsed(collapsed);

            if (toggleInternal) toggleInternal.addEventListener('click', () => setCollapsed(!document.querySelector('.w-16')));
            if (logoToggle) logoToggle.addEventListener('click', () => {
                if (logo) logo.classList.toggle('hidden');
                if (menuIcon) menuIcon.classList.toggle('hidden');
            });

            if (profileBtn) {
                profileBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (profileModal.classList.contains('hidden')) {
                        profileModal.classList.remove('hidden');
                        positionProfileModal();
                    } else {
                        profileModal.classList.add('hidden');
                    }
                });
            }

            window.addEventListener('resize', () => setTimeout(positionProfileModal, 50));
            window.addEventListener('scroll', () => setTimeout(positionProfileModal, 50), true);
        }
    });
})();