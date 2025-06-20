<header class="bg-white shadow-sm border-b">
    <nav class="flex items-center justify-between p-4 max-w-7xl mx-auto">
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-2xl font-bold">Expense<span class="text-blue-600">Mate</span></span>
            </a>
        </div>

        <div class="hidden md:flex space-x-8">
            <a href="{{ url('/') }}"
                class="text-gray-600 hover:text-blue-600 transition {{ Request::is('/') ? 'text-blue-600 font-medium' : '' }}">Home</a>
            <a href="{{ url('/#features') }}" class="text-gray-600 hover:text-blue-600 transition">Features</a>
            <a href="{{ url('/#about') }}" class="text-gray-600 hover:text-blue-600 transition">About</a>
            <a href="{{ url('/#contact') }}" class="text-gray-600 hover:text-blue-600 transition">Contact</a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition font-medium">Login</a>
            <a href="{{ route('register') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow-md">Sign
                Up</a>
        </div>
    </nav>
</header>