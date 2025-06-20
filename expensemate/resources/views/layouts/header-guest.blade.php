<header class="relative z-50">
    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Expense<span class="text-blue-600">Mate</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @guest
                        <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Home</a>
                        <a href="{{ url('/#features') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">Features</a>
                        <a href="{{ url('/#about') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">About</a>
                        <a href="{{ route('contact') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">Contact</a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">Dashboard</a>
                        <a href="{{ route('transactions.index') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">Transactions</a>
                        <a href="{{ route('analytics') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium transition">Analytics</a>
                    @endguest
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">
                            Log In
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                            Get Started
                        </a>
                    @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-600 hover:text-blue-600 transition">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-blue-600" x-data="{ open: false }"
                        @click="open = !open">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>