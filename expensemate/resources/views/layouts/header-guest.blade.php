<header class="bg-white shadow-sm">
    <nav class="flex items-center justify-between p-4 max-w-7xl mx-auto">
        <div class="flex items-center">
            <a href="{{ url('/') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ url('/') }}" class="ml-2 text-2xl font-bold">Expense<span class="text-primary">Mate</span></a>
        </div>
        
        <!-- <div class="hidden md:flex space-x-8">
            <a href="#features" class="text-gray-600 hover:text-primary transition">Features</a>
            <a href="#" class="text-gray-600 hover:text-primary transition">Pricing</a>
            <a href="#" class="text-gray-600 hover:text-primary transition">Contact</a>
        </div> -->
        
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary transition">Login</a>
            <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-secondary transition">Sign Up</a>
        </div>
    </nav>
</header>