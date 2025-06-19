<header class="bg-white shadow-sm border-b">
    <nav class="flex items-center justify-between p-4 max-w-7xl mx-auto">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-xl font-bold">Expense<span class="text-primary">Mate</span></span>
            </a>
        </div>

        <div class="hidden md:flex space-x-8">
            <a href="{{ route('dashboard') }}"
                class="text-gray-600 hover:text-primary transition {{ request()->routeIs('dashboard') ? 'text-primary font-medium border-b-2 border-primary' : '' }}">Dashboard</a>
            <a href="{{ route('transactions.index') }}"
                class="text-gray-600 hover:text-primary transition {{ request()->routeIs('transactions.*') ? 'text-primary font-medium border-b-2 border-primary' : '' }}">Transactions</a>
            <a href="{{ route('categories.index') }}"
                class="text-gray-600 hover:text-primary transition {{ request()->routeIs('categories.*') ? 'text-primary font-medium border-b-2 border-primary' : '' }}">Categories</a>
            <a href="{{ route('transactions.create') }}" class="text-gray-600 hover:text-primary transition">Add
                Transaction</a>
            <a href="{{ route('analytics') }}"
                class="text-gray-600 hover:text-primary transition {{ request()->routeIs('analytics') ? 'text-primary font-medium border-b-2 border-primary' : '' }}">Analytics</a>
            <a href="{{ route('export') }}" class="text-gray-600 hover:text-primary transition">Export</a>
        </div>

        <div class="flex items-center">
            <!-- User dropdown -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('settings')">
                        {{ __('Settings') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</header>