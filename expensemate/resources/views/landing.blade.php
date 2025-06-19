<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate - Smart Expense Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                        accent: '#4cc9f0',
                        dark: '#1e1e2c',
                        light: '#f8f9fa'
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background: radial-gradient(circle at 10% 20%, rgba(67, 97, 238, 0.1) 0%, rgba(255, 255, 255, 0) 25%),
                        radial-gradient(circle at 90% 80%, rgba(79, 55, 201, 0.1) 0%, rgba(255, 255, 255, 0) 25%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-inter text-dark bg-light">
    @include('layouts.header-guest')

    <!-- Hero Section -->
    <section class="hero-pattern py-20">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-12 md:mb-0">
                <h1 class="text-5xl font-bold leading-tight mb-6">
                    Take Control of Your 
                    <span class="text-primary">Finances</span> 
                    with Ease
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    ExpenseMate helps you track spending, analyze habits, and achieve financial goals with intuitive tools and beautiful visualizations.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-primary text-white text-lg font-semibold px-8 py-4 rounded-lg shadow-lg hover:bg-secondary transform hover:-translate-y-1 transition duration-300">
                    Get Started - It's Free
                </a>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <div class="relative">
                    <div class="w-80 h-80 bg-primary rounded-2xl shadow-2xl transform rotate-6"></div>
                    <div class="absolute top-10 left-10 w-80 h-80 bg-white rounded-2xl shadow-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg">Recent Transactions</h3>
                            <span class="text-sm text-gray-500">Today</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium">Salary</h4>
                                    <p class="text-sm text-gray-500">Work</p>
                                </div>
                                <div class="ml-auto text-green-600 font-medium">+$2,850</div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium">Groceries</h4>
                                    <p class="text-sm text-gray-500">Food</p>
                                </div>
                                <div class="ml-auto text-red-600 font-medium">-$86.50</div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium">Netflix</h4>
                                    <p class="text-sm text-gray-500">Entertainment</p>
                                </div>
                                <div class="ml-auto text-red-600 font-medium">-$15.99</div>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex justify-between font-medium">
                                <span>Total Balance</span>
                                <span class="text-green-600">$3,247.51</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Everything you need to master your personal finances</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="feature-card bg-light rounded-2xl p-8 shadow-md transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Expense Tracking</h3>
                    <p class="text-gray-600 mb-4">Categorize and monitor every transaction. Know exactly where your money is going with detailed insights.</p>
                </div>
                
                <div class="feature-card bg-light rounded-2xl p-8 shadow-md transition duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Visual Analytics</h3>
                    <p class="text-gray-600 mb-4">Beautiful charts and graphs that transform your financial data into actionable insights at a glance.</p>
                </div>
                
                <div class="feature-card bg-light rounded-2xl p-8 shadow-md transition duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Budget Planning</h3>
                    <p class="text-gray-600 mb-4">Set realistic budgets and get alerts when you're approaching limits. Achieve your financial goals faster.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Transform Your Financial Life?</h2>
            <p class="text-xl text-blue-100 mb-10">Join thousands of users who have taken control of their finances with ExpenseMate</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-primary text-lg font-bold px-10 py-4 rounded-lg shadow-xl hover:bg-gray-100 transition transform hover:scale-105">
                Get Started For Free
            </a>
            <p class="text-blue-100 mt-6">No credit card required • Cancel anytime</p>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>