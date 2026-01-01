<?php
 
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

 
<div class="fixed right-4 bottom-4 z-50">
 
    <div id="shorekeeper-page-notification" 
         class="absolute bottom-full right-0 mb-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg shadow-xl border border-gray-700 opacity-0 transform translate-y-2 transition-all duration-300 pointer-events-none whitespace-nowrap z-50">
        <span id="shorekeeper-page-text"></span>
        <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
    </div>
    
    <button id="ai-helper-toggle" 
            class="w-12 h-12 rounded-full shadow-lg transition-all duration-200 overflow-hidden border-2 border-indigo-500 hover:border-indigo-400 hover:scale-110">
        <img src="<?= dirname($_SERVER['PHP_SELF']) ?>/assets/Shorekeeper.png" 
             alt="Shorekeeper AI" 
             class="w-full h-full object-cover"
             loading="lazy">
    </button>
</div>

 
<div id="ai-helper-modal" 
     class="fixed inset-0 bg-gray-900/75 z-50 hidden"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
 
                <div class="flex items-center justify-between px-4 py-3 bg-gray-750 border-b border-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">💬</span>
                        <h3 class="text-lg font-semibold text-white">
                            Shorekeeper AI Helper
                        </h3>
                    </div>
                    <button type="button" 
                            class="p-2 text-gray-400 hover:text-gray-300 rounded-lg hover:bg-gray-700/50"
                            onclick="closeAIHelper()">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

 
                <div class="bg-gray-800">
 
                    <div id="chat-messages" class="h-[400px] overflow-y-auto p-4 space-y-4 scrollbar-hide">
 
                        <div class="flex items-start gap-2.5">
                            <div class="flex flex-col gap-1 w-full max-w-[320px]">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="text-sm font-semibold text-gray-300">Shorekeeper</span>
                                </div>
                                <div class="flex flex-col leading-1.5 p-4 border-gray-700 bg-gray-700 rounded-e-xl rounded-es-xl">
                                    <p class="text-sm font-normal text-gray-200">
                                        Hi! I'm Shorekeeper, your AI assistant for Stuarz. How can I help you today?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

 
                    <div class="p-4 bg-gray-750 border-t border-gray-700">
                        <form id="chat-form" class="flex gap-2">
                            <input type="text" 
                                   id="user-input" 
                                   class="flex-1 bg-gray-700 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                   placeholder="Type your question here..."
                                   required>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

 
<script>
const isAdmin = <?= isset($_SESSION['level']) && $_SESSION['level'] === 'admin' ? 'true' : 'false' ?>;

let currentPage = null;
let notificationTimeout = null;

function getPageNameFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || 'home';
    return page;
}

function formatPageName(page) {
    if (!page || page === 'home') return 'Home';
    return page.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

function showPageNotification(pageName) {
    const notification = document.getElementById('shorekeeper-page-notification');
    const pageText = document.getElementById('shorekeeper-page-text');
    
    if (!notification || !pageText) return;
    
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
    }
    
    pageText.textContent = pageName;
    
    notification.classList.remove('opacity-0', 'translate-y-2');
    notification.classList.add('opacity-100', 'translate-y-0');
    
    notificationTimeout = setTimeout(() => {
        notification.classList.remove('opacity-100', 'translate-y-0');
        notification.classList.add('opacity-0', 'translate-y-2');
    }, 3000);
}

function checkPageChange(isInitialLoad = false) {
    const newPage = getPageNameFromURL();
    
    if (isInitialLoad) {
        const pageName = formatPageName(newPage);
        showPageNotification(pageName);
    } else if (currentPage !== null && currentPage !== newPage) {
        const pageName = formatPageName(newPage);
        showPageNotification(pageName);
    }
    
    currentPage = newPage;
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('ai-helper-modal');
    const toggle = document.getElementById('ai-helper-toggle');
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const userInput = document.getElementById('user-input');

    
    checkPageChange(true);

    
    window.addEventListener('popstate', function() {
        checkPageChange(false);
    });

    
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            const url = new URL(link.href, window.location.origin);
            const pageParam = url.searchParams.get('page');
            if (pageParam) {
                setTimeout(() => {
                    checkPageChange(false);
                }, 100);
            }
        }
    });

    
    toggle.addEventListener('click', openAIHelper);
    
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeAIHelper();
        }
    });

    
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const question = userInput.value.trim();
        if (!question) return;

        
        addMessage(question, true);
        
        
        userInput.value = '';

        try {
            
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'flex items-start gap-2.5';
            typingDiv.innerHTML = `
                <div class="flex flex-col gap-1 w-full max-w-[320px]">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="text-sm font-semibold text-gray-300">Shorekeeper</span>
                    </div>
                    <div class="flex flex-col leading-1.5 p-4 border-gray-700 bg-gray-700 rounded-e-xl rounded-es-xl">
                        <p class="text-sm font-normal text-gray-200">Typing...</p>
                    </div>
                </div>
            `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            
            const response = await fetch('/stuarz/ai-helper/ask.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `question=${encodeURIComponent(question)}`
            });

            document.getElementById('typing-indicator')?.remove();

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server response:', errorText);
                throw new Error('Network response was not ok: ' + errorText);
            }

            const data = await response.json();
            if (data.error) {
                throw new Error(data.message || 'Server error occurred');
            }
            addMessage(data.answer);
        } catch (error) {
            console.error('Error details:', error);
            const errorMessage = error.message || 'An unknown error occurred';
            addMessage('Maaf, terjadi kesalahan: ' + errorMessage);
        }
    });

    function addMessage(content, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start gap-2.5 ' + (isUser ? 'justify-end' : '');
        
        const messageContent = `
            <div class="flex flex-col gap-1 w-full max-w-[320px]">
                <div class="flex items-center space-x-2 rtl:space-x-reverse ${isUser ? 'justify-end' : ''}">
                    <span class="text-sm font-semibold text-gray-300">
                        ${isUser ? 'You' : 'Shorekeeper'}
                    </span>
                </div>
                <div class="flex flex-col leading-1.5 p-4 ${
                    isUser 
                    ? 'bg-blue-600 rounded-s-xl rounded-ee-xl' 
                    : 'border-gray-700 bg-gray-700 rounded-e-xl rounded-es-xl'
                }">
                    <p class="text-sm font-normal text-gray-200">${content}</p>
                </div>
            </div>
        `;
        
        messageDiv.innerHTML = messageContent;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

function openAIHelper() {
    const modal = document.getElementById('ai-helper-modal');
    modal.classList.remove('hidden');
    document.getElementById('user-input')?.focus();
}

function closeAIHelper() {
    const modal = document.getElementById('ai-helper-modal');
    modal.classList.add('hidden');
}
</script>