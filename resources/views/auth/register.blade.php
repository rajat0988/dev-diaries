<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev Diaries - Login</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
</head>
<body>
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <div id="app-container" class="lg:flex">
        <!-- LEFT PANEL -->
        <div class="lg:w-1/2 min-h-screen flex flex-col justify-center bg-white p-6 lg:p-12 xl:p-16 relative z-10">
            <div class="w-full max-w-md mx-auto flex flex-col justify-center">

                <!-- Brand -->
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="brand-title text-3xl mb-1">Dev Diaries<span class="accent-dot"></span></h2>
                    <p class="brand-subtitle">An open platform for JIMS students</p>
                </div>

                <!-- Welcome -->
                <div class="mb-6">
                    <h1 class="welcome-heading text-3xl lg:text-[2.5rem]">
                        <span id="welcome-title">Welcome Back</span>
                    </h1>
                    <p class="welcome-subtitle text-sm mt-1.5">Please enter your details to continue.</p>
                </div>

                <!-- Tab Switcher -->
                <div class="flex bg-gray-50 rounded-xl p-1 mb-6 text-sm shadow-sm border border-gray-100">
                    <button id="signin-tab" class="w-1/2 py-2.5 px-4 rounded-lg tab-active transition-all duration-300 font-semibold" onclick="switchForm('login')">Sign In</button>
                    <button id="signup-tab" class="w-1/2 py-2.5 px-4 rounded-lg text-gray-400 hover:text-gray-700 transition-all duration-300 font-semibold" onclick="switchForm('register')">Sign Up</button>
                </div>

                <!-- LOGIN FORM -->
                <div id="login-form-container" class="w-full transition-all duration-500 ease-in-out">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="login_email" class="input-label ml-0.5">Email Address</label>
                            <input id="login_email" class="custom-input block w-full" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="hello@example.com" />
                            @error('email')
                                <span class="error-message ml-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="login_password" class="input-label ml-0.5">Password</label>
                            <div class="relative">
                                <input id="login_password" class="custom-input block w-full pr-10" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                                <button type="button" onclick="togglePasswordVisibility('login_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#FF6B4A] cursor-pointer transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message ml-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mb-6 px-0.5">
                             <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#FF6B4A] shadow-sm focus:ring-[#FF6B4A] h-3.5 w-3.5" name="remember">
                                <span class="ms-2 text-gray-500 font-medium text-xs">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-3 px-6 button-primary text-white rounded-xl text-sm flex items-center justify-center group">
                            <span>Continue</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- REGISTER FORM -->
                <div id="register-form-container" class="w-full transition-all duration-500 ease-in-out hidden">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="register_name" class="input-label ml-0.5">Full Name</label>
                            <input id="register_name" class="custom-input block w-full" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
                            @error('name')
                                <span class="error-message ml-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register_email" class="input-label ml-0.5">Email Address</label>
                            <input id="register_email" class="custom-input block w-full" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="yourname@jimsindia.org" />
                            @error('email')
                                <span class="error-message ml-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register_password" class="input-label ml-0.5">Password</label>
                            <div class="relative">
                                <input id="register_password" class="custom-input block w-full pr-10" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                                <button type="button" onclick="togglePasswordVisibility('register_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#FF6B4A] cursor-pointer transition-colors duration-200">
                                     <svg class="h-4 w-4" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message ml-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="input-label ml-0.5">Confirm Password</label>
                            <div class="relative">
                                <input id="password_confirmation" class="custom-input block w-full pr-10" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#FF6B4A] cursor-pointer transition-colors duration-200">
                                     <svg class="h-4 w-4" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 px-6 button-primary text-white rounded-xl text-sm flex items-center justify-center group">
                            <span>Create Account</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="w-full mt-8">
                    <p class="footer-text text-center max-w-xs mx-auto">
                        Join thousands of curious minds on our campus Tech Forum. Ask, share, and collaborate.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel-bg lg:w-1/2 hidden lg:block sticky top-0 h-screen overflow-hidden">
            <img src="{{ asset('images/login-illustration.png') }}" alt="Dev Diaries Illustration" class="illustration-img" onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#f5a080';">
        </div>
    </div>

    <script>
        /* ── Toast System ── */
        function showToast(type, title, message, duration = 5000) {
            const container = document.getElementById('toast-container');

            const icons = {
                success: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                error: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                warning: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>',
                info: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            };

            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.style.position = 'relative';
            toast.innerHTML =
                icons[type] +
                '<div class="toast-body">' +
                    '<div class="toast-title">' + title + '</div>' +
                    '<div class="toast-text">' + message + '</div>' +
                '</div>' +
                '<button class="toast-close" onclick="dismissToast(this.parentElement)">' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>' +
                '<div class="toast-progress"></div>';

            container.appendChild(toast);

            // Auto-dismiss
            setTimeout(() => dismissToast(toast), duration);
        }

        function dismissToast(toast) {
            if (!toast || toast.classList.contains('toast-hiding')) return;
            toast.classList.add('toast-hiding');
            setTimeout(() => toast.remove(), 350);
        }

        /* ── Form Switching ── */
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
               signinTab.classList.remove('text-gray-400');
               signupTab.classList.remove('tab-active');
               signupTab.classList.add('text-gray-400');
               welcomeTitle.textContent = 'Welcome Back';
           } else if (mode === 'register') {
               loginForm.classList.add('hidden');
               registerForm.classList.remove('hidden');
               signupTab.classList.add('tab-active');
               signupTab.classList.remove('text-gray-400');
               signinTab.classList.remove('tab-active');
               signinTab.classList.add('text-gray-400');
               welcomeTitle.textContent = 'Join Dev Diaries';
           }
        }

        /* ── Password Visibility Toggle ── */
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('svg');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        /* ── Init ── */
        window.onload = () => {
            @if ($errors->has('name') || ($errors->has('email') && old('email')) || ($errors->has('password') && old('name')))
                switchForm('register');
            @else
                switchForm('login');
            @endif

            // Show toast notifications for session messages
            @if (session('status'))
                showToast('success', 'Success', @json(session('status')));
            @endif

            @if (session('warning'))
                showToast('warning', 'Warning', @json(session('warning')));
            @endif

            // Show toast for validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('error', 'Validation Error', @json($error));
                @endforeach
            @endif
        };
    </script>
</body>
</html>
