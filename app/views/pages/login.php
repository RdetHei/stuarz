<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Login - Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
    <style>
        input:focus {
            outline: none;
            border-color: #5865f2;
            box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gray-800 p-12 flex-col justify-center items-center">
            <div class="max-w-md w-full">
                <div class="flex justify-center mb-8">
                    <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 293.538 293.538"
                        xml:space="preserve" stroke="#ffffff" class="h-24 w-24">
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
                
                <h1 class="text-4xl font-bold text-white mb-4 text-center">Welcome to Stuarz</h1>
                <p class="text-gray-400 text-center mb-8">Your gateway to excellence. Sign in to continue your journey.</p>
                
                <!-- Features -->
                <div class="space-y-4">
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Secure & Protected</h3>
                            <p class="text-sm text-gray-400">Your data is encrypted and safely stored</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Fast & Reliable</h3>
                            <p class="text-sm text-gray-400">Lightning-fast performance you can trust</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Community Driven</h3>
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
                    <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 293.538 293.538"
                        xml:space="preserve" stroke="#ffffff" class="h-16 w-16">
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

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Sign in to your account</h2>
                    <p class="text-gray-400">Welcome back! Please enter your details.</p>
                </div>

                <form action="index.php?page=login" method="POST" class="space-y-5">
                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-300">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="username" 
                            type="text" 
                            name="username" 
                            required 
                            autocomplete="username"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 transition-all duration-200 hover:border-gray-600 placeholder:text-gray-500" 
                            placeholder="Enter your username"
                        />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-300">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <a href="#" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 transition-all duration-200 hover:border-gray-600 placeholder:text-gray-500" 
                            placeholder="Enter your password"
                        />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            type="checkbox" 
                            class="w-4 h-4 rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-gray-900"
                        />
                        <label for="remember" class="ml-2 text-sm text-gray-400">
                            Remember me for 30 days
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <span>Sign in</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                <!-- Register Link -->
                <p class="mt-8 text-center text-sm text-gray-400">
                    Don't have an account?
                    <a href="index.php?page=register" class="font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                        Create an account
                    </a>
                </p>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-between text-xs text-gray-500">
                    <span>© 2024 Stuarz</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>All systems operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>