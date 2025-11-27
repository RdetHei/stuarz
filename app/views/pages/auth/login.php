<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Login - Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
</head>
<body class="bg-gray-900 min-h-screen">
    <div class="flex min-h-screen">
        <!--left pannel branding -->
<div class="hidden lg:flex lg:w-1/2 bg-[#1f2937] p-12 flex-col justify-center relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(88, 101, 242, 0.1) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>


            <div class="max-w-md mx-auto relative z-10">
                <!-- Logo -->
                <div class="flex justify-center mb-10">
                    <div class="w-20 h-20 rounded-2xl bg-[#5865F2] flex items-center justify-center shadow-2xl">
                        <svg fill="#ffffff" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="h-12 w-12">
                            <g>
                                <polygon points="210.084,88.631 146.622,284.844 81.491,88.631"></polygon>
                                <polygon points="103.7,64.035 146.658,21.08 188.515,64.035"></polygon>
                                <polygon points="55.581,88.631 107.681,245.608 0,88.631"></polygon>
                                <polygon points="235.929,88.631 293.538,88.631 184.521,247.548"></polygon>
                                <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695"></polygon>
                                <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035"></polygon>
                            </g>
                        </svg>
                    </div>
                </div>
                
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-100 mb-3">Welcome to Stuarz</h1>
                    <p class="text-gray-400 text-lg">Your gateway to educational excellence</p>
                </div>
                
                <!-- Features -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 bg-[#111827] rounded-lg border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20 flex-shrink-0">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-100 font-semibold mb-1">Secure & Protected</h3>
                            <p class="text-sm text-gray-400">Your data is encrypted and safely stored</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-[#111827] rounded-lg border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-100 font-semibold mb-1">Fast & Reliable</h3>
                            <p class="text-sm text-gray-400">Lightning-fast performance you can trust</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-[#111827] rounded-lg border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center border border-amber-500/20 flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-100 font-semibold mb-1">Community Driven</h3>
                            <p class="text-sm text-gray-400">Join thousands of active members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 bg-gray-900 p-8 lg:p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <!-- Mobile Logo -->
                <div class="flex justify-center mb-8 lg:hidden">
                    <div class="w-16 h-16 rounded-xl bg-[#5865F2] flex items-center justify-center shadow-lg">
                        <svg fill="#ffffff" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="h-10 w-10">
                            <g>
                                <polygon points="210.084,88.631 146.622,284.844 81.491,88.631"></polygon>
                                <polygon points="103.7,64.035 146.658,21.08 188.515,64.035"></polygon>
                                <polygon points="55.581,88.631 107.681,245.608 0,88.631"></polygon>
                                <polygon points="235.929,88.631 293.538,88.631 184.521,247.548"></polygon>
                                <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695"></polygon>
                                <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035"></polygon>
                            </g>
                        </svg>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-100">Sign in</h2>
                        <p class="text-sm text-gray-400 mt-1">Enter your credentials to access your account</p>
                    </div>

                    <!-- Form -->
                    <form action="index.php?page=login" method="POST" class="px-6 py-6 space-y-5">
                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <input 
                                id="username" 
                                type="text" 
                                name="username" 
                                required 
                                autocomplete="username"
                                placeholder="Enter your username"
                                class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors" 
                            />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Password <span class="text-red-400">*</span>
                                </label>
                                <a href="#" class="text-xs text-[#5865F2] hover:text-[#4752C4] transition-colors">
                                    Forgot password?
                                </a>
                            </div>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="w-full px-3 py-2.5 pr-10 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors" 
                                />
                                <button 
                                    type="button"
                                    id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition-colors focus:outline-none"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                type="checkbox" 
                                class="w-4 h-4 rounded border-gray-700 bg-[#111827] text-[#5865F2] focus:ring-[#5865F2] focus:ring-offset-0"
                            />
                            <label for="remember" class="ml-2 text-sm text-gray-400">
                                Remember me for 30 days
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full px-4 py-2.5 bg-[#5865F2] hover:bg-[#4752C4] text-white font-medium rounded-md transition-colors flex items-center justify-center gap-2"
                        >
                            <span>Sign in</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 border-t border-gray-700 bg-[#111827]">
                        <p class="text-center text-sm text-gray-400">
                            Don't have an account?
                            <a href="index.php?page=register" class="text-[#5865F2] hover:text-[#4752C4] font-medium transition-colors ml-1">
                                Create account
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 flex items-center justify-between text-xs text-gray-500">
                    <span>© 2024 Stuarz</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span>All systems operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Password visibility toggle
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    </script>
</body>
</html>