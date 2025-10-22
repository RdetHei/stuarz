<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Register - Stuarz</title>
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
                    <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 293.538 293.538" stroke="#ffffff" class="h-24 w-24">
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
                
                <h1 class="text-4xl font-bold text-white mb-4 text-center">Join Stuarz Today</h1>
                <p class="text-gray-400 text-center mb-8">Create your account and start your journey with us.</p>
                
                <!-- Benefits -->
                <div class="space-y-4">
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Free Forever</h3>
                            <p class="text-sm text-gray-400">No credit card required, start for free</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Privacy First</h3>
                            <p class="text-sm text-gray-400">Your data is encrypted and protected</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <svg class="w-6 h-6 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Easy Setup</h3>
                            <p class="text-sm text-gray-400">Get started in less than 2 minutes</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial -->
                <div class="mt-8 bg-gray-900 border border-gray-700 rounded-lg p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-sm font-bold text-white">
                            JD
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">John Doe</p>
                            <p class="text-gray-400 text-xs">Early Member</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm italic">"Best decision I made this year. The platform is intuitive and the community is amazing!"</p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Register Form -->
        <div class="w-full lg:w-1/2 bg-gray-900 p-8 lg:p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <!-- Mobile Logo -->
                <div class="flex justify-center mb-8 lg:hidden">
                    <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 293.538 293.538" stroke="#ffffff" class="h-16 w-16">
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
                    <h2 class="text-3xl font-bold text-white mb-2">Create a new account</h2>
                    <p class="text-gray-400">Join thousands of users already on Stuarz.</p>
                </div>

                <form action="index.php?page=register" method="POST" class="space-y-5">
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
                            placeholder="Choose a username"
                        />
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-300">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            required 
                            autocomplete="email"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 transition-all duration-200 hover:border-gray-600 placeholder:text-gray-500" 
                            placeholder="your.email@example.com"
                        />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 transition-all duration-200 hover:border-gray-600 placeholder:text-gray-500" 
                            placeholder="Create a strong password"
                        />
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-300">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="confirm_password" 
                            type="password" 
                            name="confirm_password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 transition-all duration-200 hover:border-gray-600 placeholder:text-gray-500" 
                            placeholder="Confirm your password"
                        />
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start">
                        <input 
                            id="terms" 
                            type="checkbox" 
                            required
                            class="w-4 h-4 rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-gray-900 mt-1"
                        />
                        <label for="terms" class="ml-2 text-sm text-gray-400">
                            I agree to the <a href="#" class="text-indigo-400 hover:text-indigo-300">Terms of Service</a> and <a href="#" class="text-indigo-400 hover:text-indigo-300">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <span>Create Account</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                <!-- Login Link -->
                <p class="mt-8 text-center text-sm text-gray-400">
                    Already have an account?
                    <a href="index.php?page=login" class="font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                        Sign in
                    </a>
                </p>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-between text-xs text-gray-500">
                    <span>© 2024 Stuarz</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Secure registration</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>