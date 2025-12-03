
                <?php
                session_start();
                require_once __DIR__ . '/config.php';
                ?>
                
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width,initial-scale=1">
                    <title>AI Helper — Stuarz</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <style>
                        .scrollbar-hide::-webkit-scrollbar { display: none; }
                        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
                        pre { white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; }
                        .toast-container { position: fixed; top:16px; left:50%; transform:translateX(-50%); z-index:9999; pointer-events:none }
                        .toast { background:#111; color:#fff; padding:10px 16px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.3); margin-bottom:8px; opacity:0; transform:translateY(-8px); transition:all .25s }
                        .toast.show { opacity:1; transform:translateY(0) }
                    </style>
                </head>
                <body class="bg-gray-900 text-white min-h-screen">
                    <div id="toastContainer" class="toast-container"></div>
                    <div class="container mx-auto px-4 py-8 max-w-4xl">
                        <?php if (!defined('GOOGLE_API_KEY') || empty(GOOGLE_API_KEY)): ?>
                        <div class="mb-4 p-3 rounded-lg bg-yellow-600/10 border border-yellow-600 text-yellow-200">
                            <strong>Google Generative API not configured:</strong>
                            Set your key in `ai-helper/config.local.php` or the `GOOGLE_API_KEY` environment variable.
                        </div>
                        <?php endif; ?>

                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold">💬 Shorekeeper — AI Helper</h1>
                            <p class="text-gray-400">I'll use Stuarz documentation when relevant.</p>
                        </div>

                        <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div id="chat-messages" class="h-80 overflow-y-auto p-4 space-y-4 scrollbar-hide"></div>
                            <div class="p-4 border-t border-gray-700 bg-gray-900">
                                <form id="chat-form" class="flex gap-2">
                                    <input id="user-input" type="text" required placeholder="Ask a question..." class="flex-1 p-3 rounded-lg bg-gray-700 border border-gray-600" />
                                    <button class="px-4 py-2 bg-blue-600 rounded-lg">Send</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                    function showToast(text) {
                        const c = document.getElementById('toastContainer');
                        const t = document.createElement('div');
                        t.className = 'toast'; t.textContent = text; c.appendChild(t);
                        requestAnimationFrame(()=>t.classList.add('show'));
                        setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>c.removeChild(t),250); }, 3000);
                    }

                    function addMessage(html, isUser=false) {
                        const wrap = document.getElementById('chat-messages');
                        const d = document.createElement('div');
                        d.className = isUser ? 'text-right' : '';
                        const bubble = document.createElement('div');
                        bubble.className = isUser ? 'inline-block bg-blue-600 text-white p-3 rounded-lg' : 'inline-block bg-gray-700 text-gray-200 p-3 rounded-lg';
                        bubble.innerHTML = html;
                        d.appendChild(bubble);
                        wrap.appendChild(d);
                        wrap.scrollTop = wrap.scrollHeight;
                    }

                    document.getElementById('chat-form').addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const input = document.getElementById('user-input');
                        const q = input.value.trim(); if (!q) return;
                        addMessage(q, true); input.value = '';
                        addMessage('Typing...', false);
                        try {
                            const res = await fetch('ask.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`question=${encodeURIComponent(q)}` });
                            const text = await res.text();
                            // try parse JSON
                            let ans = text;
                            try { const j = JSON.parse(text); ans = j.answer || text; } catch(_) {}
                            // replace last 'Typing...' message
                            const msgs = document.getElementById('chat-messages'); msgs.removeChild(msgs.lastChild);
                            addMessage(ans, false);
                        } catch (err) {
                            console.error(err); showToast('Request failed');
                        }
                    });
                    </script>