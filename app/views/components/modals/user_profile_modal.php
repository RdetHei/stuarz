<?php
?>

<div id="userProfileModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0" id="userProfileModalContent">
        
        <div id="profileBanner" class="relative h-32 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 overflow-hidden">
            <div class="absolute inset-0 bg-black/20"></div>
            <img id="profileBannerImg" src="" alt="Banner" class="hidden w-full h-full object-cover">
        </div>

        <div class="relative px-5 pb-5 pt-16 bg-gray-800">
            <div class="absolute -top-12 left-5">
                <div id="profileAvatarContainer" class="w-24 h-24 rounded-full bg-gray-700 border-4 border-gray-800 overflow-hidden flex items-center justify-center">
                    <img id="profileAvatarImg" src="" alt="Avatar" class="hidden w-full h-full object-cover">
                    <div id="profileAvatarInitial" class="w-full h-full flex items-center justify-center text-3xl font-bold text-white bg-indigo-600"></div>
                </div>
            </div>

            <div class="mt-2 mb-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 id="profileUserName" class="text-xl font-bold text-white mb-1 truncate"></h3>
                        <p id="profileUserUsername" class="text-sm text-gray-400 mb-2 truncate"></p>
                        <div id="profileUserLevel" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-gray-700 text-xs font-medium text-gray-300"></div>
                    </div>
                    <div id="profileActions" class="flex items-center gap-2"></div>
                </div>
            </div>

            <div class="h-px bg-gray-700 my-4"></div>

            <div id="profileAbout" class="mb-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">About</h4>
                <div id="profileBio" class="text-sm text-gray-300 whitespace-pre-wrap mb-3"></div>
                <div id="profileDetails" class="space-y-2"></div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-700">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Member since <span id="profileJoinDate"></span></span>
                </div>
            </div>
        </div>

        <button id="userProfileModalClose" 
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-black/60 hover:bg-black/80 text-gray-300 hover:text-white transition-colors"
                aria-label="Close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<style>
#userProfileModal.show #userProfileModalContent {
    scale: 1;
    opacity: 1;
}

#userProfileModal.show {
    display: flex;
}

#userProfileModal:not(.show) {
    display: none;
}
</style>