<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        Dev Diaries
                    </a>
                </div>
                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex space-x-8 sm:-my-px sm:ms-10">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="dark:text-gray-300 dark:hover:text-gray-100">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:text-gray-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Right Side -->
            <div class="hidden sm:flex sm:items-center">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="inline-flex items-center mr-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
                    <span class="sr-only">Toggle dark mode</span>
                    <x-lucide-sun class="h-6 w-6 hidden dark:block" />
                    <x-lucide-moon class="h-6 w-6 block dark:hidden" />
                </button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
                        <span class="sr-only">Open user menu</span>
                        <x-lucide-user class="h-6 w-6 border border-gray-300 rounded-full p-0.5 dark:border-gray-600" />
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-md shadow-lg z-50 dark:bg-gray-700 dark:border-gray-600">
                        <div class="p-2">
                            @if (Auth::check())
                                <div class="font-medium text-gray-900 truncate dark:text-gray-100">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500 truncate dark:text-gray-300">{{ Auth::user()->email }}</div>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600">
                            <div class="p-2">
                                <x-responsive-nav-link :href="route('profile.show', ['id' => Auth::user()->id])" class="dark:text-gray-300 dark:hover:bg-gray-600">
                                    {{ __('Profile') }}
                                </x-responsive-nav-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-responsive-nav-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();" class="dark:text-gray-300 dark:hover:bg-gray-600">
                                        {{ __('Log Out') }}
                                    </x-responsive-nav-link>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="dark:text-gray-300 dark:hover:bg-gray-700">
                {{ __('Home') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Profile Dropdown -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            @if (Auth::check())
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            @endif
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.show', ['id' => Auth::user()->id])" class="dark:text-gray-300 dark:hover:bg-gray-700">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="dark:text-gray-300 dark:hover:bg-gray-700">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
