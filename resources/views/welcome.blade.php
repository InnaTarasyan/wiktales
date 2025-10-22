@extends('layouts.app')

@section('title', 'Добро пожаловать в Виктейлс')

@section('content')
<div class="bg-gradient-to-br from-slate-50 via-amber-50 to-rose-50">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                Добро пожаловать в 
                <span class="bg-gradient-to-r from-amber-600 to-rose-600 bg-clip-text text-transparent">
                    Виктейлс
                </span>
            </h1>
            <p class="text-xl sm:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Погрузитесь в мир многоязычной литературы: от психологических повестей до китайской поэзии, 
                от шекспировских мотивов до философских размышлений. Каждая история — это путешествие через культуры и эпохи.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-600 to-rose-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Исследовать произведения
                </a>
                <a href="#about" 
                   class="inline-flex items-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-lg shadow-lg hover:shadow-xl border border-gray-200 hover:border-amber-300 transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Узнать больше
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Уникальная литературная коллекция
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Откройте для себя редкие произведения, объединяющие разные культуры, языки и литературные традиции.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Многоязычная литература</h3>
                <p class="text-gray-600">
                    Русская проза, немецкая философия, китайская поэзия и античные мотивы в одной коллекции.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Философская глубина</h3>
                <p class="text-gray-600">
                    Произведения, затрагивающие вечные темы: судьба, свобода, природа, человеческая душа.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-slate-500 to-slate-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Культурное наследие</h3>
                <p class="text-gray-600">
                    Сохранение и передача литературных традиций разных народов и эпох.
                </p>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">
                        О Виктейлс
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Виктейлс — это уникальная литературная платформа, объединяющая произведения разных культур и эпох. 
                        От психологических повестей до китайской поэзии, от шекспировских мотивов до философских размышлений — 
                        каждая работа в нашей коллекции открывает новые горизонты понимания.
                    </p>
                    <p class="text-lg text-gray-600 mb-8">
                        Наша миссия — сохранить и передать богатство мировой литературы, создавая мосты между культурами 
                        через силу слова. Здесь вы найдете редкие произведения, которые редко встречаются в обычных библиотеках.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors duration-300">
                            Начать чтение
                        </a>
                        <a href="#contact" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-300">
                            Связаться с нами
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-amber-100 to-rose-100 rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-amber-600">24+</div>
                                <div class="text-sm text-gray-600">Произведения</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-rose-600">4</div>
                                <div class="text-sm text-gray-600">Языка</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-slate-600">3</div>
                                <div class="text-sm text-gray-600">Культуры</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-emerald-600">∞</div>
                                <div class="text-sm text-gray-600">Вдохновение</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Связаться с нами
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Хотите поделиться редким произведением или обсудить литературные темы? Мы всегда открыты для диалога с ценителями литературы!
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Контактная информация</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-600">literature@wiktales.com</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600">+7 (495) 123-4567</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600">Москва, ул. Литературная, 15</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Отправить нам сообщение</h3>
                    <form class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                            <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Сообщение</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-amber-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-amber-700 transition-colors duration-300">
                            Отправить сообщение
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection