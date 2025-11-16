
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
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const logo = document.getElementById('sidebarLogo');
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

            const CSS_DURATION_MS = 520; // must match CSS transition duration

            // measure width for a given collapsed state using an off-screen clone to avoid flicker
            const measureWidthForState = (collapsedState) => {
                try {
                    const clone = sidebar.cloneNode(true);
                    clone.style.visibility = 'hidden';
                    clone.style.position = 'absolute';
                    clone.style.left = '-9999px';
                    clone.style.top = '0';
                    if (collapsedState) clone.classList.add('collapsed'); else clone.classList.remove('collapsed');
                    document.body.appendChild(clone);
                    const w = clone.getBoundingClientRect().width;
                    document.body.removeChild(clone);
                    return Math.round(w);
                } catch (e) {
                    // fallback to defaults
                    return collapsedState ? 64 : 256;
                }
            };

            const setCollapsed = (collapsed) => {
                try {
                    // measure the start width (current visible sidebar)
                    const startWidth = Math.round(sidebar.getBoundingClientRect().width);

                    // measure both states using clones to avoid layout jank
                    const expandedWidth = measureWidthForState(false);
                    const collapsedWidth = measureWidthForState(true);
                    const targetWidth = collapsed ? collapsedWidth : expandedWidth;

                    // Prepare menu-text elements: measure and set max-width for symmetric animation
                    const menuTextWidths = [];
                    menuTexts.forEach(el => {
                        const w = Math.round(el.getBoundingClientRect().width) || 0;
                        menuTextWidths.push(w);
                        // set measured width so animation expands from/to correct size
                        el.style.maxWidth = w + 'px';
                    });

                    // set explicit start width to make transition predictable
                    sidebar.style.width = startWidth + 'px';
                    // force reflow
                    // eslint-disable-next-line no-unused-expressions
                    sidebar.offsetHeight;

                    if (collapsed) {
                        // apply classes for target state so CSS rules for collapsed take effect
                        sidebar.classList.add('collapsed');
                        if (logo) logo.style.opacity = '0';
                        localStorage.setItem('sidebarCollapsed', '1');
                        try { document.documentElement.classList.add('sidebar-collapsed'); } catch (e) { }
                        if (window.__sidebarDebug) console.log('[sidebar] collapsed -> true');
                        showDebugOverlay && showDebugOverlay('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        if (logo) logo.style.opacity = '1';
                        localStorage.setItem('sidebarCollapsed', '0');
                        try { document.documentElement.classList.remove('sidebar-collapsed'); } catch (e) { }
                        if (window.__sidebarDebug) console.log('[sidebar] collapsed -> false');
                        showDebugOverlay && showDebugOverlay('expanded');
                    }

                    // animate to target width
                    sidebar.style.width = targetWidth + 'px';
                    // set page containers margin-left to match sidebar target
                    if (header) header.style.marginLeft = targetWidth + 'px';
                    if (content) content.style.marginLeft = targetWidth + 'px';

                    // animate menu-text max-width: to 0 when collapsed, to measured width when expanded
                    menuTexts.forEach((el, idx) => {
                        if (collapsed) {
                            el.style.maxWidth = '0px';
                            el.style.opacity = '0';
                            el.style.visibility = 'hidden';
                        } else {
                            el.style.maxWidth = (menuTextWidths[idx] || 0) + 'px';
                            el.style.opacity = '1';
                            el.style.visibility = 'visible';
                        }
                    });

                    // cleanup after CSS transition
                    setTimeout(() => {
                        // remove inline widths so responsive CSS works later
                        sidebar.style.width = '';
                        menuTexts.forEach(el => {
                            if (!collapsed) {
                                el.style.maxWidth = '';
                                el.style.opacity = '';
                                el.style.visibility = '';
                            } else {
                                el.style.maxWidth = '0px';
                                el.style.opacity = '0';
                                el.style.visibility = 'hidden';
                            }
                        });
                        // ensure collapsed class is in correct state
                        if (collapsed) {
                            sidebar.classList.add('collapsed');
                            try { document.documentElement.classList.add('sidebar-collapsed'); } catch (e) { }
                        } else {
                            sidebar.classList.remove('collapsed');
                            try { document.documentElement.classList.remove('sidebar-collapsed'); } catch (e) { }
                            // clear inline margins when expanded
                            if (header) header.style.marginLeft = '';
                            if (content) content.style.marginLeft = '';
                        }
                    }, CSS_DURATION_MS + 40);

                } catch (err) {
                    console.error('setCollapsed error', err);
                } finally {
                    setTimeout(positionProfileModal, 50);
                }
            };

            // initialize from storage
            const collapsed = localStorage.getItem('sidebarCollapsed') === '1';
            setCollapsed(collapsed);

            if (toggleInternal) toggleInternal.addEventListener('click', (e) => {
                const newState = !sidebar.classList.contains('collapsed');
                setCollapsed(newState);
                toggleInternal.setAttribute('aria-expanded', newState ? 'true' : 'false');
            });
            if (logoToggle) logoToggle.addEventListener('click', () => {
                const newState = !sidebar.classList.contains('collapsed');
                setCollapsed(newState);
                if (toggleInternal) toggleInternal.setAttribute('aria-expanded', newState ? 'true' : 'false');
            });

            // Mobile menu toggle
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('mobile-open');
                    const isOpen = sidebar.classList.contains('mobile-open');
                    mobileMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    try {
                        if (isOpen) document.documentElement.classList.add('sidebar-mobile-open');
                        else document.documentElement.classList.remove('sidebar-mobile-open');
                    } catch (e) { }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 1023.98 &&
                    sidebar.classList.contains('mobile-open') &&
                    !sidebar.contains(e.target) &&
                    !mobileMenuToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile-open');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    try { document.documentElement.classList.remove('sidebar-mobile-open'); } catch (e) { }
                }
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 1023.98) {
                    sidebar.classList.remove('mobile-open');
                    if (mobileMenuToggle) mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    try { document.documentElement.classList.remove('sidebar-mobile-open'); } catch (e) { }
                }
                setTimeout(positionProfileModal, 50);
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

            window.addEventListener('scroll', () => setTimeout(positionProfileModal, 50), true);
        }
    });
})();

// Debug overlay helper (shows small indicator when debug mode is ON)
(function () {
    const dbg = localStorage.getItem('sidebarDebug') === '1';
    window.__sidebarDebug = dbg;
    let overlayEl = null;
    function createOverlay() {
        if (overlayEl) return overlayEl;
        overlayEl = document.createElement('div');
        overlayEl.id = 'sidebar-debug-overlay';
        overlayEl.style.position = 'fixed';
        overlayEl.style.right = '12px';
        overlayEl.style.bottom = '12px';
        overlayEl.style.background = 'rgba(0,0,0,0.6)';
        overlayEl.style.color = '#fff';
        overlayEl.style.padding = '6px 10px';
        overlayEl.style.borderRadius = '6px';
        overlayEl.style.fontSize = '12px';
        overlayEl.style.zIndex = '999999';
        overlayEl.style.pointerEvents = 'none';
        overlayEl.textContent = dbg ? 'sidebar: ready' : '';
        document.body.appendChild(overlayEl);
        return overlayEl;
    }
    function showDebugOverlay(state) {
        if (!window.__sidebarDebug) return;
        const el = createOverlay();
        el.textContent = 'sidebar: ' + state;
        el.style.opacity = '1';
        setTimeout(() => { if (el) el.style.opacity = '0.85'; }, 200);
    }
    // expose helper so main code can call it
    window.__showSidebarDebug = showDebugOverlay;
    // quick keyboard toggle: Ctrl+Shift+S to toggle overlay/debug logs
    window.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 's') {
            const now = localStorage.getItem('sidebarDebug') === '1';
            localStorage.setItem('sidebarDebug', now ? '0' : '1');
            window.__sidebarDebug = !now;
            if (window.__sidebarDebug) createOverlay();
            else if (overlayEl) overlayEl.remove();
            console.log('[sidebar] debug toggled ->', window.__sidebarDebug);
        }
    });
    // ensure overlay created when debug active on load
    if (window.__sidebarDebug) createOverlay();
})();