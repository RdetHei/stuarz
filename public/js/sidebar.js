
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
        // Delay used for hiding the floating panel to allow cursor travel
        // Increased to 600ms to give more time to move cursor onto panel
        window.__PANEL_HIDE_DELAY_MS = window.__PANEL_HIDE_DELAY_MS || 600;
        const PANEL_HIDE_DELAY_MS = window.__PANEL_HIDE_DELAY_MS;

        // Delegated handlers: create panel on pointerenter to summary when collapsed.
        // Hide logic (timers) is handled by per-summary + panel handlers only.
        const delegatedEnter = (e) => {
            try {
                const summary = e.target.closest && e.target.closest('#sidebar details.sidebar-group > summary');
                if (!summary) return;
                const isCollapsed = sidebar.classList.contains('collapsed') || document.documentElement.classList.contains('sidebar-collapsed');
                if (!isCollapsed) return;
                const details = summary.parentElement;
                if (!details) return;
                // open lightweight popup when collapsed
                if (window.openSidebarPopup) window.openSidebarPopup(details);
            } catch (err) { }
        };

        // NOTE: delegatedLeave removed — hide/remove logic now solely managed by
        // per-summary handlers (addHoverHandlersToSummary) + panel pointerleave.
        // This prevents race conditions and auto-close bugs.

        document.addEventListener('pointerenter', delegatedEnter, true);
        document.addEventListener('focusin', delegatedEnter, true);



        if (sidebar) {
            let modalMoved = false;
            const profileModalBackdrop = document.getElementById('profileModalBackdrop');

            const ensureModalInBody = () => {
                if (!profileModal || modalMoved) return;
                document.body.appendChild(profileModal);
                if (profileModalBackdrop && !profileModalBackdrop.parentElement) {
                    document.body.appendChild(profileModalBackdrop);
                }
                profileModal.style.position = 'fixed';
                profileModal.style.zIndex = '99999';
                profileModal.style.pointerEvents = 'auto';
                if (profileModalBackdrop) {
                    profileModalBackdrop.style.position = 'fixed';
                    profileModalBackdrop.style.zIndex = '99998';
                    profileModalBackdrop.style.backdropFilter = 'none';
                    profileModalBackdrop.style.webkitBackdropFilter = 'none';
                }
                modalMoved = true;
            };

            const measureModal = () => {
                if (!profileModal) return { w: 288, h: 280 };
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
                return { w: w || 288, h: h || 280 };
            };

            const positionProfileModal = () => {
                if (!profileModal || !profileBtn) return;
                ensureModalInBody();
                const btnRect = profileBtn.getBoundingClientRect();
                const { w: modalWidth, h: modalHeight } = measureModal();
                const gap = 8;
                const isMobile = window.innerWidth <= 1023.98;

                if (isMobile) {
                    // Mobile: center at bottom
                    const maxWidth = Math.min(320, window.innerWidth - 32);
                    profileModal.style.width = maxWidth + 'px';
                    profileModal.style.left = '50%';
                    profileModal.style.transform = 'translateX(-50%) scale(1)';
                    profileModal.style.bottom = '1rem';
                    profileModal.style.top = 'auto';
                } else {
                    // Desktop: position relative to button
                    const spaceBelow = window.innerHeight - btnRect.bottom;
                    const spaceAbove = btnRect.top;
                    let top;
                    if (spaceBelow >= modalHeight + gap) {
                        top = Math.round(btnRect.bottom + gap);
                    } else if (spaceAbove >= modalHeight + gap) {
                        top = Math.round(btnRect.top - modalHeight - gap);
                    } else {
                        top = Math.round(Math.max(8, Math.min(window.innerHeight - modalHeight - 8, btnRect.top)));
                    }

                    let left = Math.round(btnRect.left);
                    // Ensure modal doesn't go off screen
                    if (left + modalWidth > window.innerWidth - 8) {
                        left = Math.max(8, window.innerWidth - modalWidth - 8);
                    }
                    if (left < 8) left = 8;

                    profileModal.style.top = top + 'px';
                    profileModal.style.left = left + 'px';
                    profileModal.style.transform = 'scale(1)';
                    profileModal.style.bottom = 'auto';
                }
            };

            // Use fixed widths to avoid expensive layout measurements and thrashing
            const COLLAPSED_WIDTH_REM = 4; // 4rem -> 64px
            const EXPANDED_WIDTH_REM = 16; // 16rem -> 256px
            const TRANSITION_MS = 220; // <=250ms for smooth header/content movement

            // Lightweight setCollapsed: toggle classes and rely on CSS for animation.
            const setCollapsed = (collapsed) => {
                try {
                    const isCollapsed = !!collapsed;

                    // update minimal state only — avoid inline styles or measurements
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                        try { document.documentElement.classList.add('sidebar-collapsed'); } catch (e) { }
                        try { localStorage.setItem('sidebarCollapsed', '1'); } catch (e) { }
                        if (window.__sidebarDebug) console.log('[sidebar] collapsed -> true');
                        window.__showSidebarDebug && window.__showSidebarDebug('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        try { document.documentElement.classList.remove('sidebar-collapsed'); } catch (e) { }
                        try { localStorage.setItem('sidebarCollapsed', '0'); } catch (e) { }
                        if (window.__sidebarDebug) console.log('[sidebar] collapsed -> false');
                        window.__showSidebarDebug && window.__showSidebarDebug('expanded');
                    }

                    // let CSS handle transitions for #dHeader and #content (no inline writes)

                    // Call floating children adjuster
                    try { adjustFloatingGroupChildren(isCollapsed); } catch (e) { }
                } catch (err) {
                    if (window.__sidebarDebug) console.error('setCollapsed error', err);
                } finally {
                    // ensure profile modal reposition in case layout changed
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

            // Flag to prevent modal from reopening immediately after closing
            let isClosingModal = false;
            let closeTimeout = null;

            // Function to close modal
            const closeProfileModal = (e) => {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                isClosingModal = true;
                if (profileModal) profileModal.classList.add('hidden');
                if (profileModalBackdrop) profileModalBackdrop.classList.add('hidden');
                if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');

                // Clear any existing timeout
                if (closeTimeout) clearTimeout(closeTimeout);

                // Reset flag after a short delay to prevent immediate reopening
                closeTimeout = setTimeout(() => {
                    isClosingModal = false;
                }, 100);
            };

            // Function to open modal
            const openProfileModal = (e) => {
                // Don't open if we're in the process of closing
                if (isClosingModal) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    return;
                }

                ensureModalInBody();
                if (profileModal) profileModal.classList.remove('hidden');
                if (profileModalBackdrop) profileModalBackdrop.classList.remove('hidden');
                positionProfileModal();
                if (profileBtn) profileBtn.setAttribute('aria-expanded', 'true');
            };

            // Toggle modal when clicking profile button
            if (profileBtn) {
                profileBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    // Don't toggle if we're closing
                    if (isClosingModal) {
                        e.preventDefault();
                        e.stopPropagation();
                        return;
                    }

                    const isHidden = profileModal.classList.contains('hidden');

                    if (isHidden) {
                        openProfileModal(e);
                    } else {
                        closeProfileModal(e);
                    }
                }, false); // Use bubble phase, not capture
            }

            // Close modal when clicking a link inside it
            if (profileModal) {
                const links = profileModal.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        // Don't prevent default navigation, just close modal
                        closeProfileModal();
                    });
                });
            }

            // Close modal when clicking backdrop
            if (profileModalBackdrop) {
                const handleBackdropClick = (e) => {
                    // Only close if clicking directly on backdrop, not on modal
                    if (e.target === profileModalBackdrop && !profileModal.contains(e.target)) {
                        closeProfileModal(e);
                    }
                };
                profileModalBackdrop.addEventListener('click', handleBackdropClick);
            }

            // Close modal when clicking outside
            const handleOutsideClick = (e) => {
                if (!profileModal || profileModal.classList.contains('hidden')) return;
                if (isClosingModal) {
                    e.preventDefault();
                    e.stopPropagation();
                    return; // Don't process if already closing
                }

                // Get the actual clicked element
                const target = e.target;

                // Check if click is on profile button
                if (profileBtn && (target === profileBtn || profileBtn.contains(target))) {
                    return; // Button has its own handler
                }

                // Check if click is on modal content
                if (profileModal && profileModal.contains(target)) {
                    return; // Don't close if clicking inside modal
                }

                // Check if click is on backdrop
                if (profileModalBackdrop && (target === profileModalBackdrop || profileModalBackdrop.contains(target))) {
                    return; // Backdrop has its own handler
                }

                // If we get here, click is outside modal - close it
                closeProfileModal(e);
            };

            // Use only click event with capture phase to catch early and prevent bubbling
            document.addEventListener('click', handleOutsideClick, true);

            // Handle escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && profileModal && !profileModal.classList.contains('hidden')) {
                    closeProfileModal();
                }
            });

            window.addEventListener('scroll', () => {
                if (profileModal && !profileModal.classList.contains('hidden')) {
                    setTimeout(positionProfileModal, 50);
                }
            }, true);
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
// Floating popup manager for collapsed sidebar
(function () {
    const COLLAPSED_CLASS = 'collapsed';
    const POPUP_SHOW_MS = 180; // animation duration
    const POPUP_HIDE_DELAY_MS = 200; // how long to wait before hiding after pointer leaves

    let popupState = {
        details: null,
        popupEl: null,
        hideTimer: null
    };

    // create popup DOM from a details element (do NOT clone event listeners)
    function buildPopup(details) {
        const children = details.querySelectorAll('.group-children > a');
        if (!children || children.length === 0) return null;

        const popup = document.createElement('div');
        popup.className = 'sidebar-popup-card';
        popup.setAttribute('role', 'menu');
        popup.setAttribute('data-for', details.__sidebarId || '');

        const list = document.createElement('div');
        list.className = 'sidebar-popup-list';

        // create shallow copies of anchors that forward to original nodes
        children.forEach((orig, idx) => {
            const item = document.createElement('a');
            item.className = 'sidebar-popup-item';
            // copy accessible attributes
            if (orig.getAttribute('href')) item.setAttribute('href', orig.getAttribute('href'));
            const title = (orig.getAttribute('title') || (orig.querySelector && (orig.querySelector('.menu-text') ? orig.querySelector('.menu-text').textContent : orig.textContent)) || '').trim();
            if (title) item.setAttribute('title', title);
            // copy inner icon + text structure for consistent visuals
            try { item.innerHTML = orig.innerHTML; } catch (e) { item.textContent = title || orig.textContent; }

            // forward clicks to original element if it exists (preserve JS handlers)
            item.addEventListener('click', function (ev) {
                ev.preventDefault();
                try {
                    if (typeof orig.click === 'function') orig.click();
                    else if (orig.getAttribute('href')) window.location.href = orig.getAttribute('href');
                } catch (e) { if (orig.getAttribute('href')) window.location.href = orig.getAttribute('href'); }
            });

            list.appendChild(item);
        });

        popup.appendChild(list);
        return popup;
    }

    function positionPopup(details, popup) {
        if (!details || !popup) return;
        const summary = details.querySelector('summary');
        const sidebar = document.getElementById('sidebar');
        if (!summary || !sidebar) return;
        const sRect = summary.getBoundingClientRect();
        const sbRect = sidebar.getBoundingClientRect();

        const left = Math.round(sbRect.right + 8); // gap
        // prefer aligning to summary top but clamp to viewport
        let top = Math.round(sRect.top);
        const maxH = window.innerHeight - 16;
        const popupH = Math.min(maxH, popup.offsetHeight || 200);
        if (top + popupH > window.innerHeight - 8) {
            top = Math.max(8, window.innerHeight - popupH - 8);
        }

        popup.style.left = left + 'px';
        popup.style.top = top + 'px';
        popup.style.minWidth = '180px';
        popup.style.maxWidth = Math.min(360, window.innerWidth - left - 24) + 'px';
    }

    function openPopup(details) {
        if (!details) return;
        // if already open for same details, refresh timer
        if (popupState.details === details && popupState.popupEl) {
            clearTimeout(popupState.hideTimer);
            popupState.hideTimer = null;
            return;
        }

        closePopup(true);

        const popup = buildPopup(details);
        if (!popup) return;

        document.body.appendChild(popup);
        popupState.details = details;
        popupState.popupEl = popup;

        // position after appended so offsetHeight is available (single read)
        requestAnimationFrame(() => positionPopup(details, popup));

        // entrance animation
        requestAnimationFrame(() => popup.classList.add('show'));

        // pointer handlers: don't hide while moving between summary and popup
        popup.addEventListener('pointerenter', () => {
            if (popupState.hideTimer) { clearTimeout(popupState.hideTimer); popupState.hideTimer = null; }
        });
        popup.addEventListener('pointerleave', (e) => {
            // start hide timer when pointer leaves popup
            if (popupState.hideTimer) clearTimeout(popupState.hideTimer);
            popupState.hideTimer = setTimeout(() => closePopup(), POPUP_HIDE_DELAY_MS);
        });

        // ensure popup remains on top and visible when window changes
        window.addEventListener('resize', onWindowChange);
        window.addEventListener('scroll', onWindowChange, true);
    }

    function closePopup(forceImmediate) {
        if (!popupState.popupEl) return;
        const el = popupState.popupEl;
        // remove listeners
        try { window.removeEventListener('resize', onWindowChange); window.removeEventListener('scroll', onWindowChange, true); } catch (e) { }
        if (popupState.hideTimer) { clearTimeout(popupState.hideTimer); popupState.hideTimer = null; }

        // hide animation
        if (forceImmediate) {
            if (el.parentNode) el.parentNode.removeChild(el);
        } else {
            el.classList.remove('show');
            // remove after animation
            setTimeout(() => { try { if (el.parentNode) el.parentNode.removeChild(el); } catch (e) { } }, POPUP_SHOW_MS + 30);
        }

        popupState.details = null;
        popupState.popupEl = null;
    }

    function onWindowChange() {
        if (!popupState.details || !popupState.popupEl) return;
        positionPopup(popupState.details, popupState.popupEl);
    }

    // attach summary handlers when collapsed, detach when expanded
    const summaryListeners = new Map();
    function attachSummaryHandlers(summary) {
        if (summaryListeners.has(summary)) return;
        const details = summary.parentElement;
        if (!details) return;

        const onPointerEnter = (e) => {
            const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed') || (document.getElementById('sidebar') && document.getElementById('sidebar').classList.contains('collapsed'));
            if (!isCollapsed) return;
            // prevent native details toggle when collapsed
            try { summary.addEventListener('click', preventDetailsToggle, { once: true }); } catch (e) { }
            openPopup(details);
        };

        const onPointerLeave = (e) => {
            // start hide timer after leaving the summary (but allow entering popup)
            if (popupState.hideTimer) clearTimeout(popupState.hideTimer);
            popupState.hideTimer = setTimeout(() => closePopup(), POPUP_HIDE_DELAY_MS);
        };

        const preventDetailsToggle = function (ev) {
            // only prevent when collapsed
            const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed') || (document.getElementById('sidebar') && document.getElementById('sidebar').classList.contains('collapsed'));
            if (isCollapsed) {
                ev.preventDefault();
                ev.stopPropagation();
            }
        };

        summary.addEventListener('pointerenter', onPointerEnter);
        summary.addEventListener('pointerleave', onPointerLeave);
        // keyboard access: open on Enter/Space when collapsed
        const keyHandler = (ev) => {
            if (ev.key === 'Enter' || ev.key === ' ') {
                const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed') || (document.getElementById('sidebar') && document.getElementById('sidebar').classList.contains('collapsed'));
                if (isCollapsed) {
                    ev.preventDefault();
                    openPopup(details);
                }
            }
        };
        summary.addEventListener('keydown', keyHandler);

        summaryListeners.set(summary, { onPointerEnter, onPointerLeave, keyHandler });
    }

    function detachSummaryHandlers(summary) {
        const entry = summaryListeners.get(summary);
        if (!entry) return;
        try {
            summary.removeEventListener('pointerenter', entry.onPointerEnter);
            summary.removeEventListener('pointerleave', entry.onPointerLeave);
            summary.removeEventListener('keydown', entry.keyHandler);
        } catch (e) { }
        summaryListeners.delete(summary);
    }

    function adjustFloatingGroupChildren(collapsed) {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        const summaries = Array.from(document.querySelectorAll('#sidebar details.sidebar-group > summary'));
        if (collapsed) {
            summaries.forEach(summary => attachSummaryHandlers(summary));
            // ensure any open details are closed so internal dropdowns don't fight popups
            summaries.forEach(summary => {
                const details = summary.parentElement;
                try { if (details && details.hasAttribute('open')) details.removeAttribute('open'); } catch (e) { }
            });
        } else {
            summaries.forEach(summary => detachSummaryHandlers(summary));
            // close any popup when expanding
            closePopup(true);
        }
    }

    // capture summary clicks when collapsed: prevent native toggle and open popup instead
    document.addEventListener('click', function (e) {
        const summary = e.target.closest && e.target.closest('#sidebar details.sidebar-group > summary');
        if (!summary) return;
        const sidebarEl = document.getElementById('sidebar');
        const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed') || (sidebarEl && sidebarEl.classList.contains('collapsed'));
        if (!isCollapsed) return;
        e.preventDefault();
        e.stopPropagation();
        try { openPopup(summary.parentElement); } catch (er) { }
    }, true);

    // close popup when clicking outside, or on blur/visibility change
    document.addEventListener('pointerdown', function (e) {
        const sidebar = document.getElementById('sidebar');
        if (!popupState.popupEl) return;
        const target = e.target;
        if (sidebar && sidebar.contains(target)) return;
        if (popupState.popupEl && popupState.popupEl.contains(target)) return;
        closePopup();
    }, true);
    document.addEventListener('visibilitychange', function () { if (document.hidden) closePopup(true); });
    window.addEventListener('blur', function () { closePopup(true); });

    // expose API
    window.adjustFloatingGroupChildren = adjustFloatingGroupChildren;
    window.openSidebarPopup = openPopup;
    window.closeSidebarPopup = closePopup;

})();