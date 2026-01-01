(function() {
    'use strict';

    const modal = document.getElementById('userProfileModal');
    const modalContent = document.getElementById('userProfileModalContent');
    const closeBtn = document.getElementById('userProfileModalClose');
    
    if (!modal || !modalContent) {
        console.warn('[User Profile Modal] Modal elements not found. Make sure user_profile_modal.php is included.');
        return;
    }

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
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
            document.body.style.overflow = '';
        }, 200);
    }

    function getInitials(name) {
        if (!name) return 'U';
        const cleaned = name.replace(/[^A-Za-z]/g, '');
        return cleaned.substring(0, 2).toUpperCase() || 'U';
    }

    function formatLevel(level) {
        const levelMap = {
            'admin': { text: 'Admin', color: 'bg-red-500/20 text-red-400' },
            'guru': { text: 'Guru', color: 'bg-blue-500/20 text-blue-400' },
            'teacher': { text: 'Teacher', color: 'bg-blue-500/20 text-blue-400' },
            'user': { text: 'User', color: 'bg-gray-500/20 text-gray-400' },
            'siswa': { text: 'Siswa', color: 'bg-green-500/20 text-green-400' }
        };
        const config = levelMap[level?.toLowerCase()] || levelMap['user'];
        return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded ${config.color} text-xs font-medium">${config.text}</span>`;
    }

    async function loadUserProfile(userId) {
        try {
            
            const response = await fetch(`index.php?page=get_user_profile&id=${userId}`, {
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!data.success || !data.user) {
                if (window.showToast) {
                    window.showToast(data.message || 'Gagal memuat profile user', 'error', 3000);
                }
                return;
            }

            const user = data.user;

            const bannerDiv = document.getElementById('profileBanner');
            const bannerImg = document.getElementById('profileBannerImg');
            if (user.hasBanner && user.banner) {
                bannerImg.src = user.banner;
                bannerImg.classList.remove('hidden');
                bannerDiv.style.background = 'none';
            } else {
                bannerImg.classList.add('hidden');
                bannerDiv.style.background = 'linear-gradient(to bottom right, #4f46e5, #7c3aed, #db2777)';
            }

            const avatarImg = document.getElementById('profileAvatarImg');
            const avatarInitial = document.getElementById('profileAvatarInitial');
            if (user.hasAvatar && user.avatar) {
                avatarImg.src = user.avatar;
                avatarImg.classList.remove('hidden');
                avatarInitial.style.display = 'none';
            } else {
                avatarImg.classList.add('hidden');
                avatarInitial.textContent = user.initials || getInitials(user.name || user.username);
                avatarInitial.style.display = 'flex';
            }

            document.getElementById('profileUserName').textContent = user.name || user.username || 'User';
            document.getElementById('profileUserUsername').textContent = '@' + (user.username || '');
            document.getElementById('profileUserLevel').innerHTML = formatLevel(user.level);

            const bioEl = document.getElementById('profileBio');
            bioEl.classList.remove('text-gray-500');
            if (user.bio && user.bio.trim()) {
                bioEl.textContent = user.bio;
            } else {
                bioEl.textContent = 'No bio yet.';
                bioEl.classList.add('text-gray-500');
            }

            const detailsEl = document.getElementById('profileDetails');
            detailsEl.innerHTML = '';
            
            const details = [];
            if (user.email) {
                details.push({
                    icon: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
                    text: user.email
                });
            }
            if (user.phone) {
                details.push({
                    icon: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`,
                    text: user.phone
                });
            }
            if (user.address) {
                details.push({
                    icon: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
                    text: user.address
                });
            }
            if (user.class) {
                details.push({
                    icon: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>`,
                    text: user.class
                });
            }
            if (user.role) {
                details.push({
                    icon: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
                    text: user.role
                });
            }

            details.forEach(detail => {
                const detailEl = document.createElement('div');
                detailEl.className = 'flex items-center gap-2 text-sm text-gray-400';
                detailEl.innerHTML = detail.icon + '<span>' + detail.text + '</span>';
                detailsEl.appendChild(detailEl);
            });

            document.getElementById('profileJoinDate').textContent = user.joinDate || '';

            const actionsEl = document.getElementById('profileActions');
            actionsEl.innerHTML = '';
            
            const currentUserId = window.currentUserId || (document.body.dataset.userId ? parseInt(document.body.dataset.userId) : null);
            const isAdmin = window.isAdmin || (document.body.dataset.isAdmin === 'true');
            const isOwnProfile = currentUserId && currentUserId === user.id;

            if (isAdmin || isOwnProfile) {
                const editBtn = document.createElement('a');
                editBtn.href = `index.php?page=edit_user&id=${user.id}`;
                editBtn.className = 'px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs font-medium rounded-md transition-colors';
                editBtn.textContent = 'Edit';
                actionsEl.appendChild(editBtn);
            }

            showModal();

        } catch (error) {
            console.error('[User Profile Modal] Error loading profile:', error);
            if (window.showToast) {
                window.showToast('Terjadi kesalahan saat memuat profile', 'error', 3000);
            }
        }
    }

    function handleProfileClick(e) {
        const element = e.target.closest('[data-view-profile]');
        if (!element) return;

        e.preventDefault();
        e.stopPropagation();

        const userId = element.getAttribute('data-view-profile');
        if (userId) {
            loadUserProfile(userId);
        }
    }

    function init() {
        document.addEventListener('click', handleProfileClick, true);

        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                hideModal();
            });
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                hideModal();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.viewUserProfile = loadUserProfile;
})();