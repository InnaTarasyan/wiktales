<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Виктейлс'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        /* Custom navigation styles */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        .hamburger {
            transition: all 0.3s ease;
        }
        
        .hamburger.active .line1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active .line2 {
            opacity: 0;
        }
        
        .hamburger.active .line3 {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        .backdrop-blur {
            backdrop-filter: blur(8px);
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">W</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Виктейлс</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="{{ route('home') }}" 
                           class="nav-link {{ request()->routeIs('home') ? 'active text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium">
                            Главная
                        </a>
                        <a href="{{ route('welcome') }}" 
                           class="nav-link {{ request()->routeIs('welcome') ? 'active text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium">
                            О нас
                        </a>
                        <a href="#contact" 
                           class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Контакты
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="hamburger inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                        <span class="sr-only">Открыть главное меню</span>
                        <div class="w-6 h-6 flex flex-col justify-center items-center">
                            <span class="line1 block w-5 h-0.5 bg-current transform transition-all duration-300"></span>
                            <span class="line2 block w-5 h-0.5 bg-current mt-1 transform transition-all duration-300"></span>
                            <span class="line3 block w-5 h-0.5 bg-current mt-1 transform transition-all duration-300"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 right-0 z-50 w-full max-w-sm bg-white shadow-xl">
            <div class="flex flex-col h-full">
                <!-- Mobile menu header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">W</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Виктейлс</span>
                    </div>
                    <button id="close-mobile-menu" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile menu items -->
                <div class="flex-1 px-6 py-6">
                    <nav class="space-y-4">
                        <a href="{{ route('home') }}" 
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50 rounded-lg' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg' }}">
                            Главная
                        </a>
                        <a href="{{ route('welcome') }}" 
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('welcome') ? 'text-indigo-600 bg-indigo-50 rounded-lg' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg' }}">
                            О нас
                        </a>
                        <a href="#contact" 
                           class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                            Контакты
                        </a>
                    </nav>
                </div>

                <!-- Mobile menu footer -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500 text-center">
                        Откройте удивительные истории и приключения
                    </p>
                </div>
            </div>
        </div>

        <!-- Mobile menu backdrop -->
        <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">W</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Виктейлс</span>
                    </div>
                    <p class="text-gray-600 text-sm max-w-md">
                        Откройте удивительные истории и приключения в нашей тщательно отобранной коллекции сказок. 
                        От классических сказок до современных приключений, найдите свою следующую любимую историю.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Быстрые ссылки</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-indigo-600">Главная</a></li>
                        <li><a href="{{ route('welcome') }}" class="text-sm text-gray-600 hover:text-indigo-600">О нас</a></li>
                        <li><a href="#contact" class="text-sm text-gray-600 hover:text-indigo-600">Контакты</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Контакты</h3>
                    <ul class="space-y-2">
                        <li class="text-sm text-gray-600">hello@wiktales.com</li>
                        <li class="text-sm text-gray-600">+1 (555) 123-4567</li>
                        <li class="text-sm text-gray-600">Подписывайтесь на нас в социальных сетях</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} Виктейлс. Все права защищены.
                    </p>
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0110 4.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

            function openMobileMenu() {
                mobileMenu.classList.add('open');
                mobileMenuBackdrop.classList.remove('hidden');
                mobileMenuButton.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenuFn() {
                mobileMenu.classList.remove('open');
                mobileMenuBackdrop.classList.add('hidden');
                mobileMenuButton.classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            mobileMenuButton.addEventListener('click', openMobileMenu);
            closeMobileMenu.addEventListener('click', closeMobileMenuFn);
            mobileMenuBackdrop.addEventListener('click', closeMobileMenuFn);

            // Close mobile menu when clicking on a link
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenuFn);
            });

            // Close mobile menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                    closeMobileMenuFn();
                }
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
