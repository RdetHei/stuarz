
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Register</title>
</head>
<body class="h-full">
    <div class="bg-gray-900 flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <!-- Logo + Judul -->
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="flex justify-center">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 293.538 293.538" stroke="#ffffff" class="h-12 w-12">
                        <g>
                            <polygon points="210.084,88.631 146.622,284.844 81.491,88.631"></polygon>
                            <polygon points="103.7,64.035 146.658,21.08 188.515,64.035"></polygon>
                            <polygon points="55.581,88.631 107.681,245.608 0,88.631"></polygon>
                            <polygon points="235.929,88.631 293.538,88.631 184.521,247.548"></polygon>
                            <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695"></polygon>
                            <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035"></polygon>
                        </g>
                    </svg>
                </a>
            </div>
            <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-white">
                Create a new account
            </h2>
        </div>

        <!-- Form Registrasi -->
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form action="index.php?page=register" method="POST" class="space-y-6">

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-100">Username</label>
                    <div class="mt-2">
                        <input id="username" name="username" type="text" required autocomplete="username"
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                   outline-1 -outline-offset-1 outline-white/10 
                                   placeholder:text-gray-500 focus:outline-2 
                                   focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-100">Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" required autocomplete="email"
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                   outline-1 -outline-offset-1 outline-white/10 
                                   placeholder:text-gray-500 focus:outline-2 
                                   focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-100">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                   outline-1 -outline-offset-1 outline-white/10 
                                   placeholder:text-gray-500 focus:outline-2 
                                   focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-100">Confirm Password</label>
                    <div class="mt-2">
                        <input id="confirm_password" name="confirm_password" type="password" required autocomplete="new-password"
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                   outline-1 -outline-offset-1 outline-white/10 
                                   placeholder:text-gray-500 focus:outline-2 
                                   focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 
                               text-sm font-semibold text-white hover:bg-indigo-400 
                               focus-visible:outline-2 focus-visible:outline-offset-2 
                               focus-visible:outline-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            <!-- Link balik ke Login -->
            <p class="mt-10 text-center text-sm text-gray-400">
                Already have an account?
                <a href="index.php?page=login" class="font-semibold text-indigo-400 hover:text-indigo-300">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
