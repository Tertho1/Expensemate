
<header class="relative">
    <!-- Modern gradient background with glass effect -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700"></div>
    <div class="absolute inset-0 bg-black/10 backdrop-blur-sm"></div>
    
    <nav class="relative flex items-center justify-between p-2 max-w-7xl mx-auto">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center group">
                <!-- Enhanced logo with glow effect -->
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-3 group-hover:bg-white/30 transition-all duration-300 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">
                    Expense<span class="text-blue-200">Mate</span>
                </span>
            </a>
        </div>

        <!-- Navigation with modern glass buttons -->
        <div class="hidden md:flex space-x-2">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-all duration-300 font-medium backdrop-blur-sm {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('transactions.index') }}"
                class="px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-all duration-300 font-medium backdrop-blur-sm {{ request()->routeIs('transactions.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                Transactions
            </a>
            <a href="{{ route('categories.index') }}"
                class="px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-all duration-300 font-medium backdrop-blur-sm {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                Categories
            </a>
            <a href="{{ route('analytics') }}"
                class="px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-all duration-300 font-medium backdrop-blur-sm {{ request()->routeIs('analytics') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                Analytics
            </a>
            <a href="{{ route('export') }}" 
                class="px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-all duration-300 font-medium backdrop-blur-sm">
                Export
            </a>
        </div>

        <!-- Enhanced user dropdown -->
        <div class="flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center px-4 py-2 text-white bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 focus:outline-none transition-all duration-300 shadow-lg">
                        <div class="w-8 h-8 bg-white/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-blue-200">Account Settings</div>
                        </div>
                        <div class="ml-2">
                            <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-3 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('settings')" class="flex items-center px-4 py-3 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" 
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center px-4 py-3 hover:bg-red-50 text-red-600">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden">
            <button type="button" 
                class="text-white hover:text-blue-200 p-2 rounded-lg hover:bg-white/20 transition-all duration-300" 
                x-data="{ open: false }" @click="open = !open">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>
</header>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>