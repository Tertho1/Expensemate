<header class="bg-white shadow-sm">
    <nav class="flex items-center justify-between p-4 max-w-7xl mx-auto">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('dashboard') }}" class="ml-2 text-2xl font-bold">Expense<span class="text-primary">Mate</span></a>
        </div>
        
        <div class="hidden md:flex space-x-8">
            <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-primary transition {{ request()->routeIs('transactions.*') ? 'text-primary font-medium' : '' }}">Dashboard</a>
            <a href="{{ route('transactions.create') }}" class="text-gray-600 hover:text-primary transition">Add Expense</a>
            <a href="{{ route('analytics') }}" class="text-gray-600 hover:text-primary transition">Analytics</a>
            <a href="{{ route('export') }}" class="text-gray-600 hover:text-primary transition">Export</a>
        </div>
        
        <div class="flex items-center">
            <!-- User dropdown -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-dropdown-link href="{{ route('profile.edit') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <x-dropdown-link href="{{ route('settings') }}">
                        {{ __('Settings') }}
                    </x-dropdown-link>

                    <div class="border-t border-gray-200"></div>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</header>