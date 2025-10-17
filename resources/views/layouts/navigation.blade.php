<nav x-data="{ dropdownOpen: false }" class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
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

            <!-- Mobile Right Side - Dark Mode Toggle and Profile -->
            <div class="flex items-center gap-3 sm:hidden">
                <!-- Dark Mode Toggle (Mobile) -->
                <button onclick="toggleDarkMode()" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
                    <span class="sr-only">Toggle dark mode</span>
                    <x-lucide-sun class="h-6 w-6 hidden dark:block" />
                    <x-lucide-moon class="h-6 w-6 block dark:hidden" />
                </button>

                <!-- Profile Icon (Mobile) -->
                <div class="relative" x-data="{ mobileDropdownOpen: false }">
                    <button @click="mobileDropdownOpen = !mobileDropdownOpen" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
                        <span class="sr-only">Open user menu</span>
                        <x-lucide-user class="h-6 w-6 border border-gray-300 rounded-full p-0.5 dark:border-gray-600" />
                    </button>
                    <!-- Mobile Dropdown Menu -->
                    <div x-show="mobileDropdownOpen" @click.away="mobileDropdownOpen = false" class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-md shadow-lg z-50 dark:bg-gray-700 dark:border-gray-600">
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

            <!-- Desktop Right Side -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
                    <span class="sr-only">Toggle dark mode</span>
                    <x-lucide-sun class="h-6 w-6 hidden dark:block" />
                    <x-lucide-moon class="h-6 w-6 block dark:hidden" />
                </button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300">
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
</nav>
