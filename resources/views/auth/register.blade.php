<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev Diaries</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
</head>
<body>
    <div id="app-container" class="lg:flex">
        <!-- LEFT PANEL -->
        <div class="lg:w-1/2 p-6 sm:p-12 flex flex-col justify-between">
            <div class="mb-10 lg:mb-0">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Dev Diaries</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">An open platform for JIMS students to discuss tech-content</p>
                </div>

                <h1 class="text-4xl font-extrabold text-gray-900 mt-15 mb-2">
                    <span id="welcome-title">Welcome Back</span>
                </h1>
                <p class="text-gray-500 mb-8">Please enter your details</p>

                <div class="flex bg-gray-100 rounded-xl p-1 max-w-sm mb-8 font-semibold text-sm">
                    <button id="signin-tab" class="w-1/2 py-2 px-4 rounded-lg tab-active transition-colors duration-200" onclick="switchForm('login')">Sign In</button>
                    <button id="signup-tab" class="w-1/2 py-2 px-4 rounded-lg text-gray-500 hover:text-gray-900 transition-colors duration-200" onclick="switchForm('register')">Signup</button>
                </div>
            </div>

            <!-- Display Success Messages -->
            @if (session('status'))
                <div class="success-message w-full max-w-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- LOGIN FORM -->
            <div id="login-form-container" class="w-full max-w-sm transition-opacity duration-300">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="login_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <input id="login_email" class="custom-input block w-full pl-10 pr-12 py-2 border rounded-xl placeholder-gray-400 text-gray-900" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="login_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="login_password" class="custom-input block w-full pl-4 py-2 border rounded-xl placeholder-gray-400 text-gray-900 pr-10" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                            <button type="button" onclick="togglePasswordVisibility('login_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <!-- Heroicon name: eye -->
                                <svg class="h-5 w-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm mt-6">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-FF4B2B shadow-sm focus:ring-FF4B2B" name="remember">
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Forgot your password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full mt-6 py-3 px-4 button-primary text-white font-bold rounded-xl transition duration-150 shadow-lg">Continue</button>
                </form>
            </div>

            <!-- REGISTER FORM -->
            <div id="register-form-container" class="w-full max-w-sm transition-opacity duration-300 hidden">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="register_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="register_name" class="custom-input block w-full pl-4 py-2 border rounded-xl placeholder-gray-400 text-gray-900" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="register_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address (JIMS)</label>
                        <input id="register_email" class="custom-input block w-full pl-4 py-2 border rounded-xl placeholder-gray-400 text-gray-900" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="yourname@jimsindia.org" />
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="register_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="register_password" class="custom-input block w-full pl-4 py-2 border rounded-xl placeholder-gray-400 text-gray-900 pr-10" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            <button type="button" onclick="togglePasswordVisibility('register_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                 <svg class="h-5 w-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" class="custom-input block w-full pl-4 py-2 border rounded-xl placeholder-gray-400 text-gray-900 pr-10" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                 <svg class="h-5 w-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 button-primary text-white font-bold rounded-xl transition duration-150 shadow-lg">Register</button>
                </form>
            </div>

            <div class="w-full max-w-sm mt-6">
                <p class="text-xs text-center text-gray-400 mt-20 max-w-xs mx-auto">
                    Join thousands of curious minds on our campus Tech Forum. Log in to ask questions, share insights, and collaborate with fellow students to solve problems and grow together.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel-bg lg:w-1/2 hidden lg:flex">
            <div class="safe-icon">
                <div class="dial"></div>
                <div class="handle"></div>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form-container');
        const registerForm = document.getElementById('register-form-container');
        const signinTab = document.getElementById('signin-tab');
        const signupTab = document.getElementById('signup-tab');
        const welcomeTitle = document.getElementById('welcome-title');

        function switchForm(mode) {
           if (mode === 'login') {
               loginForm.classList.remove('hidden');
               registerForm.classList.add('hidden');
               signinTab.classList.add('tab-active');
               signupTab.classList.remove('tab-active');
               signupTab.classList.add('text-gray-500');
               welcomeTitle.textContent = 'Welcome Back';
           } else if (mode === 'register') {
               loginForm.classList.add('hidden');
               registerForm.classList.remove('hidden');
               signupTab.classList.add('tab-active');
               signinTab.classList.remove('tab-active');
               signinTab.classList.add('text-gray-500');
               welcomeTitle.textContent = 'Join Tech Forum';
           }
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('svg');

            if (input.type === 'password') {
                input.type = 'text';
                // Switch to eye-off icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                // Switch to eye icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        // Check if there are validation errors and switch to appropriate form
        window.onload = () => {
            @if ($errors->has('name') || ($errors->has('email') && old('email')) || ($errors->has('password') && old('name')))
                switchForm('register');
            @else
                switchForm('login');
            @endif
        };
    </script>
</body>
</html>
