<?php
// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shorekeeper AI Helper - Stuarz</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom styles for chat bubbles and toast notifications -->
    <style>
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        /* Handle long content */
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        code {
            word-break: break-all;
            white-space: pre-wrap;
        }
        /* Custom scrollbar for code blocks */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        /* Toast notification styles */
        .toast-container {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            pointer-events: none;
        }
        .toast {
            background-color: #1a1a1a;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 8px;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .toast-icon {
            font-size: 1.2em;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <!-- Toast container -->
    <div id="toastContainer" class="toast-container"></div>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">💬 Shorekeeper AI Helper</h1>
            <p class="text-gray-400">Ask me anything — I will use Stuarz documentation when relevant.</p>
        </div>

        <!-- Chat Container -->
        <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden">
            <!-- Chat Messages Area -->
            <div id="chat-messages" class="h-[500px] overflow-y-auto p-4 space-y-4 scrollbar-hide" style="max-width: 100%; word-wrap: break-word;">
                <!-- Welcome Message -->
                <div class="flex items-start gap-2.5">
                    <div class="flex flex-col gap-1 w-full max-w-[640px]">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="text-sm font-semibold text-gray-300">Shorekeeper</span>
                        </div>
                        <div class="flex flex-col leading-1.5 p-4 border-gray-700 bg-gray-700 rounded-e-xl rounded-es-xl">
                            <p class="text-sm font-normal text-gray-200">
                                Halo! Aku Shorekeeper — AI assistant untuk Stuarz. Tanyakan apa saja tentang fitur atau dokumentasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
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

    <!-- Chat JavaScript -->
    <script>
        // Toast notification function
        function showToast(pageName, icon = '📍') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span>Menuju halaman: ${pageName}</span>
            `;
            container.appendChild(toast);
            
            // Trigger reflow for animation
            toast.offsetHeight;
            toast.classList.add('show');
            
            // Remove toast after animation
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Function to extract page name from URL
        function getPageNameFromUrl(url) {
            const path = new URL(url, window.location.origin).pathname;
            const parts = path.split('/').filter(Boolean);
            if (parts.length > 0) {
                // Convert to title case and replace hyphens
                return parts[parts.length - 1]
                    .split('-')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }
            return 'Home';
        }

        // Intercept link clicks
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.target) {
                const pageName = getPageNameFromUrl(link.href);
                showToast(pageName);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('chat-form');
            const chatMessages = document.getElementById('chat-messages');
            const userInput = document.getElementById('user-input');

            // Function to add a message to the chat
            function addMessage(content, isUser = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex items-start gap-2.5 ' + (isUser ? 'justify-end' : '');
                
                // Convert URLs in content to clickable links
                if (!isUser) {
                    content = content.replace(
                        /(🔗 Halaman: )(\/stuarz\/[^\n]+)/g,
                        (match, prefix, url) => {
                            const pageName = getPageNameFromUrl(url);
                            return `${prefix}<a href="${url}" class="text-blue-400 hover:text-blue-300 underline" onclick="event.preventDefault(); showToast('${pageName}'); setTimeout(() => window.location.href = '${url}', 500);">${url}</a>`;
                        }
                    );
                }
                
                const messageContent = `
                    <div class="flex flex-col gap-1 w-full max-w-[640px]">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse ${isUser ? 'justify-end' : ''}">
                            <span class="text-sm font-semibold text-gray-300">
                                ${isUser ? 'You' : 'Shorekeeper'}
                            </span>
                        </div>
                        <div class="flex flex-col leading-1.5 p-4 max-w-[85%] ${
                            isUser 
                            ? 'bg-blue-600 rounded-s-xl rounded-ee-xl' 
                            : 'border-gray-700 bg-gray-700 rounded-e-xl rounded-es-xl'
                        }">
                            <div class="text-sm font-normal text-gray-200 whitespace-pre-wrap break-words overflow-x-auto">${content}</div>
                        </div>
                    </div>
                `;
                
                messageDiv.innerHTML = messageContent;
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Handle form submission
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const question = userInput.value.trim();
                if (!question) return;

                // Add user's question to chat
                addMessage(question, true);
                
                // Clear input
                userInput.value = '';

                try {
                    // Show typing indicator
                    const typingDiv = document.createElement('div');
                    typingDiv.id = 'typing-indicator';
                    typingDiv.className = 'flex items-start gap-2.5';
                    typingDiv.innerHTML = `
                        <div class="flex flex-col gap-1 w-full max-w-[640px]">
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

                    // Search documentation through server endpoint
                    const response = await fetch('ask.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `question=${encodeURIComponent(question)}`
                    });

                    // Remove typing indicator
                    document.getElementById('typing-indicator')?.remove();

                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error('Network response was not ok: ' + text);
                    }

                    const data = await response.json();
                    answerText = data.answer;

                    // Remove typing indicator (if not already removed)
                    document.getElementById('typing-indicator')?.remove();
                    addMessage(answerText);
                } catch (error) {
                    console.error('Error:', error);
                    addMessage('Sorry, I encountered an error. Please try again later.');
                }
            });

            // Focus input on load
            userInput.focus();
        });
    </script>
</body>
</html>