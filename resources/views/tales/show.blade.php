@extends('layouts.app')

@section('title', $tale->title)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="scroll-progress" id="scrollProgress"></div>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 reading-container">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            <aside class="hidden lg:block w-72 shrink-0">
                <div class="toc-sticky bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">СОДЕРЖАНИЕ</h2>
                    </div>
                    <nav>
                        <div class="space-y-2 text-sm">
                            @foreach($tale->sections as $index => $section)
                                <div class="flex items-center justify-between">
                                    <a href="#{{ $section->anchor }}" 
                                       class="toc-link text-gray-700 hover:text-indigo-600 transition-colors duration-200 flex-1" 
                                       data-anchor="{{ $section->anchor }}">
                                        {{ $index + 1 }}. {{ $section->title ?? 'Раздел' }}
                                    </a>
                                    <span class="text-xs text-gray-400 ml-2">{{ $index + 1 }}</span>
                                </div>
                                @if($tale->slug === 'anadyr-2' && $index === 0)
                                    <div class="ml-4 mt-2 mb-2 text-xs text-gray-600 leading-relaxed">
                                        <a href="#poslanie-gollandtsa" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ПОСЛАНИЕ ГОЛЛАНДЦА</a>
                                        <a href="#drevnyaya-pritcha" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ДРЕВНЯЯ ПРИТЧА</a>
                                        <a href="#smert-pod-parusom" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">СМЕРТЬ ПОД ПАРУСОМ</a>
                                        <a href="#istoriya-metamorfoz-ogon" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ИСТОРИЯ МЕТАМОРФОЗ. ОГОНЬ</a>
                                        <a href="#za-sozhzhennym-mostom" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЗА СОЖЖЕННЫМ МОСТОМ</a>
                                        <a href="#zhyoltaya-reka-vremena-goda" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЖЁЛТАЯ РЕКА. ВРЕМЕНА ГОДА</a>
                                        <a href="#pered-velikim-obledenieniem" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ПЕРЕД ВЕЛИКИМ ОБЛЕДЕНЕНИЕМ</a>
                                        <a href="#zimnee-okno" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЗИМНЕЕ ОКНО</a>
                                        <a href="#ispoved-monstra" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ИСПОВЕДЬ МОНСТРА</a>
                                        <a href="#mezhdu-tremya-ya" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">МЕЖДУ ТРЕМЯ Я</a>
                                        <a href="#luna-zimoy" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЛУНА ЗИМОЙ</a>
                                        <a href="#sobaka-o-trekh-nogakh" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">СОБАКА О ТРЕХ НОГАХ</a>
                                        <a href="#na-allee-dialog-s-lunoy" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">НА АЛЛЕЕ. ДИАЛОГ С ЛУНОЙ</a>
                                        <a href="#v-ozhidanii-poslednego-avtobusa" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">В ОЖИДАНИИ ПОСЛЕДНЕГО АВТОБУСА</a>
                                        <a href="#chelovek-ten" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЧЕЛОВЕК-ТЕНЬ</a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </nav>
                </div>
            </aside>
            <main class="min-w-0 flex-1">
                <div class="mb-6">
                    @if($tale->cover_url)
                        <img src="{{ $tale->cover_url }}" alt="{{ $tale->title }} cover" class="w-full max-h-96 object-cover rounded">
                    @endif
                    <div class="mt-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">{{ $tale->title }}</h1>
                        <div class="reader-controls flex flex-wrap items-center gap-2 text-sm">
                            <div class="flex items-center gap-1 bg-white rounded-lg ring-1 ring-gray-200 p-1">
                                <button id="decreaseFont" class="px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200" title="Уменьшить шрифт">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <button id="increaseFont" class="px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200" title="Увеличить шрифт">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <button id="toggleWidth" class="px-3 py-2 bg-white ring-1 ring-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200" title="Изменить ширину">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Ширина
                            </button>
                            <button id="toggleTheme" class="px-3 py-2 bg-gray-900 text-white hover:bg-black rounded-lg transition-colors duration-200" title="Переключить тему">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                Темная
                            </button>
                            <button id="openToc" class="px-3 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg transition-colors duration-200 lg:hidden" title="Открыть содержание">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Содержание
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="lg:hidden mb-6">
                    <div class="flex items-center justify-between bg-white rounded-xl ring-1 ring-gray-100 p-4 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-800">Содержание</div>
                                <div class="text-xs text-gray-500">{{ $tale->sections->count() }} разделов</div>
                            </div>
                        </div>
                        <button id="openToc2" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">Открыть</button>
                    </div>
                </div>

                <article class="prose max-w-none" id="readerArticle">
                    @foreach($tale->sections as $section)
                        <section id="{{ $section->anchor }}" class="scroll-mt-24 mb-12">
                            <div class="group bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-6 sm:p-8 md:p-10">
                                @if($section->title)
                                    <div class="flex items-start justify-between gap-3 mb-6">
                                        <h2 class="text-2xl sm:text-3xl font-bold leading-tight tracking-tight text-gray-900">{{ $section->title }}</h2>
                                        <a href="#{{ $section->anchor }}" aria-label="Permalink" class="ml-2 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                <div class="prose prose-neutral max-w-none overflow-x-auto prose-img:rounded-lg prose-img:w-full prose-img:h-auto prose-a:text-blue-600 hover:prose-a:text-blue-700 prose-headings:scroll-mt-24 content-body">
                                    {!! $section->body_html !!}
                                </div>
                                <div class="mt-6 hidden" data-expand-container>
                                    <button class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium" data-expand-btn>
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        Показать больше
                                    </button>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </article>
            </main>
        </div>
    </div>
    
    <!-- Mobile TOC Drawer -->
    <div class="mobile-drawer" id="mobileDrawer" aria-hidden="true">
        <div class="mobile-drawer-panel">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">СОДЕРЖАНИЕ</h3>
                        <p class="text-sm text-gray-500">{{ $tale->sections->count() }} разделов</p>
                    </div>
                </div>
                <button id="closeDrawer" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav>
                <div class="space-y-2">
                    @foreach($tale->sections as $index => $section)
                        <div class="flex items-center justify-between py-2">
                            <a href="#{{ $section->anchor }}" 
                               class="toc-link text-gray-700 hover:text-indigo-600 transition-colors duration-200 flex-1" 
                               data-anchor="{{ $section->anchor }}">
                                {{ $index + 1 }}. {{ $section->title ?? 'Раздел' }}
                            </a>
                            <span class="text-xs text-gray-400 ml-2">{{ $index + 1 }}</span>
                        </div>
                        @if($tale->slug === 'anadyr-2' && $index === 0)
                            <div class="ml-4 mt-2 mb-2 text-xs text-gray-600 leading-relaxed">
                                <a href="#poslanie-gollandtsa" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ПОСЛАНИЕ ГОЛЛАНДЦА</a>
                                <a href="#drevnyaya-pritcha" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ДРЕВНЯЯ ПРИТЧА</a>
                                <a href="#smert-pod-parusom" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">СМЕРТЬ ПОД ПАРУСОМ</a>
                                <a href="#istoriya-metamorfoz-ogon" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ИСТОРИЯ МЕТАМОРФОЗ. ОГОНЬ</a>
                                <a href="#za-sozhzhennym-mostom" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЗА СОЖЖЕННЫМ МОСТОМ</a>
                                <a href="#zhyoltaya-reka-vremena-goda" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЖЁЛТАЯ РЕКА. ВРЕМЕНА ГОДА</a>
                                <a href="#pered-velikim-obledenieniem" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ПЕРЕД ВЕЛИКИМ ОБЛЕДЕНЕНИЕМ</a>
                                <a href="#zimnee-okno" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЗИМНЕЕ ОКНО</a>
                                <a href="#ispoved-monstra" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ИСПОВЕДЬ МОНСТРА</a>
                                <a href="#mezhdu-tremya-ya" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">МЕЖДУ ТРЕМЯ Я</a>
                                <a href="#luna-zimoy" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЛУНА ЗИМОЙ</a>
                                <a href="#sobaka-o-trekh-nogakh" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">СОБАКА О ТРЕХ НОГАХ</a>
                                <a href="#na-allee-dialog-s-lunoy" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">НА АЛЛЕЕ. ДИАЛОГ С ЛУНОЙ</a>
                                <a href="#v-ozhidanii-poslednego-avtobusa" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">В ОЖИДАНИИ ПОСЛЕДНЕГО АВТОБУСА</a>
                                <a href="#chelovek-ten" class="toc-link text-gray-600 hover:text-indigo-600 transition-colors duration-200 block py-1">ЧЕЛОВЕК-ТЕНЬ</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </nav>
        </div>
        <button id="drawerBackdrop" class="absolute inset-0" aria-label="Close"></button>
    </div>

    <!-- Back to top button -->
    <button id="backToTop" class="px-3 py-2 rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700">Вверх</button>
</div>
@endsection

@section('scripts')
<style>
    :root {
        --reader-font-size: 18px;
    }
    .reading-container {
        scroll-behavior: smooth;
    }
    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #22d3ee);
        width: 0%;
        z-index: 50;
        transition: width 0.1s linear;
    }
    .toc-sticky {
        position: sticky;
        top: 5rem;
        max-height: calc(100vh - 6rem);
        overflow: auto;
    }
    .mobile-drawer {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        z-index: 50;
    }
    .mobile-drawer.open { display: block; }
    .mobile-drawer-panel {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
        max-height: 80vh;
        overflow: auto;
        padding: 1.5rem;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Dark mode mobile drawer */
    .bg-neutral-900 .mobile-drawer-panel {
        background: #1f2937;
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
    }
    
    .bg-neutral-900 .mobile-drawer-panel h3 {
        color: #f3f4f6;
    }
    
    .bg-neutral-900 .mobile-drawer-panel p {
        color: #9ca3af;
    }
    
    .bg-neutral-900 .mobile-drawer-panel .toc-link {
        color: #d1d5db;
    }
    
    .bg-neutral-900 .mobile-drawer-panel .toc-link:hover {
        color: #60a5fa;
        background: #374151;
    }
    .content-collapsed {
        max-height: 28rem;
        overflow: hidden;
        mask-image: linear-gradient(#000 70%, transparent);
        -webkit-mask-image: linear-gradient(#000 70%, transparent);
    }
    .reader-controls button {
        border-radius: 0.5rem;
    }
    /* Improve long text readability */
    #readerArticle.prose {
        font-size: var(--reader-font-size);
        line-height: 1.85;
        text-wrap: pretty;
        -webkit-hyphens: auto;
        -ms-hyphens: auto;
        hyphens: auto;
        hanging-punctuation: first last;
        color: #374151;
        font-family: 'Georgia', 'Times New Roman', serif;
    }
    
    /* Dark mode text styling */
    .bg-neutral-900 #readerArticle.prose {
        color: #e5e7eb;
    }
    
    #readerArticle.prose p { 
        margin-top: 1.2em; 
        margin-bottom: 1.2em; 
        text-align: justify;
        text-indent: 1.5em;
    }
    
    #readerArticle.prose h1, 
    #readerArticle.prose h2, 
    #readerArticle.prose h3, 
    #readerArticle.prose h4, 
    #readerArticle.prose h5, 
    #readerArticle.prose h6 {
        text-indent: 0;
        margin-top: 2em;
        margin-bottom: 1em;
        font-weight: 600;
        line-height: 1.3;
    }
    
    #readerArticle.prose img { 
        margin: 1.5rem auto; 
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    #readerArticle.prose blockquote { 
        font-style: italic; 
        border-left: 4px solid #d1d5db; 
        padding-left: 1.5rem; 
        margin: 1.5rem 0;
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
    }
    
    .bg-neutral-900 #readerArticle.prose blockquote {
        background: #374151;
        border-left-color: #6b7280;
    }
    
    #readerArticle.prose a { 
        text-underline-offset: 3px; 
        color: #2563eb;
        transition: color 0.2s ease;
    }
    
    #readerArticle.prose a:hover {
        color: #1d4ed8;
    }
    
    .bg-neutral-900 #readerArticle.prose a {
        color: #60a5fa;
    }
    
    .bg-neutral-900 #readerArticle.prose a:hover {
        color: #93c5fd;
    }
    
    /* Improve list styling */
    #readerArticle.prose ul, 
    #readerArticle.prose ol {
        margin-top: 1em;
        margin-bottom: 1em;
        padding-left: 2rem;
    }
    
    #readerArticle.prose li {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
    
    /* Table styling */
    #readerArticle.prose table {
        border-collapse: collapse;
        margin: 1.5rem 0;
        width: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    
    #readerArticle.prose th,
    #readerArticle.prose td {
        border: 1px solid #e5e7eb;
        padding: 0.75rem;
        text-align: left;
    }
    
    #readerArticle.prose th {
        background: #f9fafb;
        font-weight: 600;
    }
    
    .bg-neutral-900 #readerArticle.prose th {
        background: #374151;
        border-color: #4b5563;
    }
    
    .bg-neutral-900 #readerArticle.prose td {
        border-color: #4b5563;
    }
    /* Back to top */
    #backToTop { position: fixed; right: 1rem; bottom: 1rem; display: none; z-index: 50; }
    #backToTop.show { display: inline-flex; }
    /* Scrollspy active link */
    .toc-link.active { color: #4f46e5; font-weight: 600; }
    /* Ensure tables inside rich content are scrollable on small screens */
    .prose table {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
    /* Improve spacing for lists in dense mobile layouts */
    .prose ul, .prose ol {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* Enhanced mobile responsiveness */
    @media (max-width: 640px) {
        .reading-container {
            padding: 1rem;
        }
        
        #readerArticle.prose {
            font-size: 16px;
            line-height: 1.7;
        }
        
        .reader-controls {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .reader-controls button {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .toc-sticky {
            position: static;
            max-height: none;
        }
    }
    
    /* Better touch targets for mobile */
    @media (max-width: 768px) {
        .toc-link {
            min-height: 44px;
            display: flex;
            align-items: center;
        }
        
        .reader-controls button {
            min-height: 44px;
            min-width: 44px;
        }
    }
    
    /* Improved focus states for accessibility */
    .toc-link:focus,
    .reader-controls button:focus {
        outline: 2px solid #4f46e5;
        outline-offset: 2px;
    }
    
    /* Better scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Enhanced section spacing */
    section {
        scroll-margin-top: 2rem;
    }
    
    /* Improved dark mode transitions */
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
</style>

<script>
(function() {
    const root = document.documentElement;
    const article = document.getElementById('readerArticle');
    const progress = document.getElementById('scrollProgress');
    const inc = document.getElementById('increaseFont');
    const dec = document.getElementById('decreaseFont');
    const togTheme = document.getElementById('toggleTheme');
    const togWidth = document.getElementById('toggleWidth');
    const openToc = document.getElementById('openToc');
    const openToc2 = document.getElementById('openToc2');
    const drawer = document.getElementById('mobileDrawer');
    const closeDrawer = document.getElementById('closeDrawer');
    const backdrop = document.getElementById('drawerBackdrop');

    // Font size control via CSS variable ensures it overrides Tailwind prose
    const defaultFont = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--reader-font-size')) || 18;
    let base = defaultFont;
    function applyFont(size) {
        root.style.setProperty('--reader-font-size', size + 'px');
        localStorage.setItem('readerFont', String(size));
    }
    inc && inc.addEventListener('click', () => { base = Math.min(base + 2, 26); applyFont(base); });
    dec && dec.addEventListener('click', () => { base = Math.max(base - 2, 12); applyFont(base); });
    const savedFont = parseFloat(localStorage.getItem('readerFont'));
    if (!isNaN(savedFont)) { base = savedFont; applyFont(base); }

    // Theme toggle
    function setDark(enabled) {
        const body = document.body;
        const container = document.querySelector('.max-w-6xl');
        const sections = document.querySelectorAll('section');
        const toc = document.querySelector('.toc-sticky');
        const mobileToc = document.querySelector('.mobile-drawer-panel');
        const controls = document.querySelector('.reader-controls');
        
        // Toggle body background and text
        body.classList.toggle('bg-gray-50', !enabled);
        body.classList.toggle('bg-neutral-900', enabled);
        body.classList.toggle('text-neutral-100', enabled);
        
        // Toggle main container
        container.classList.toggle('text-gray-900', !enabled);
        container.classList.toggle('text-neutral-100', enabled);
        
        // Toggle sections
        sections.forEach(section => {
            const sectionDiv = section.querySelector('div');
            if (sectionDiv) {
                sectionDiv.classList.toggle('bg-white', !enabled);
                sectionDiv.classList.toggle('bg-neutral-800', enabled);
                sectionDiv.classList.toggle('ring-gray-100', !enabled);
                sectionDiv.classList.toggle('ring-neutral-700', enabled);
            }
            
            // Toggle section titles
            const sectionTitle = section.querySelector('h2');
            if (sectionTitle) {
                sectionTitle.classList.toggle('text-gray-900', !enabled);
                sectionTitle.classList.toggle('text-neutral-100', enabled);
            }
        });
        
        // Toggle TOC
        if (toc) {
            toc.classList.toggle('bg-white', !enabled);
            toc.classList.toggle('bg-neutral-800', enabled);
            toc.classList.toggle('ring-gray-100', !enabled);
            toc.classList.toggle('ring-neutral-700', enabled);
            
            // Toggle TOC title
            const tocTitle = toc.querySelector('h2');
            if (tocTitle) {
                tocTitle.classList.toggle('text-gray-800', !enabled);
                tocTitle.classList.toggle('text-neutral-100', enabled);
            }
            
            // Toggle TOC links
            const tocLinks = toc.querySelectorAll('.toc-link');
            tocLinks.forEach(link => {
                link.classList.toggle('text-gray-700', !enabled);
                link.classList.toggle('text-neutral-300', enabled);
                link.classList.toggle('hover:text-indigo-600', !enabled);
                link.classList.toggle('hover:text-indigo-400', enabled);
                link.classList.toggle('hover:bg-indigo-50', !enabled);
                link.classList.toggle('hover:bg-neutral-700', enabled);
            });
        }
        
        // Toggle mobile TOC
        if (mobileToc) {
            mobileToc.classList.toggle('bg-white', !enabled);
            mobileToc.classList.toggle('bg-neutral-800', enabled);
        }
        
        // Toggle controls
        if (controls) {
            controls.querySelectorAll('button').forEach(btn => {
                if (btn.id !== 'toggleTheme') {
                    btn.classList.toggle('bg-white', !enabled);
                    btn.classList.toggle('bg-neutral-800', enabled);
                    btn.classList.toggle('text-gray-700', !enabled);
                    btn.classList.toggle('text-neutral-200', enabled);
                    btn.classList.toggle('ring-gray-200', !enabled);
                    btn.classList.toggle('ring-neutral-600', enabled);
                }
            });
        }
        
        // Update theme button
        togTheme.textContent = enabled ? 'Светлая' : 'Темная';
        togTheme.classList.toggle('bg-gray-900', !enabled);
        togTheme.classList.toggle('bg-neutral-700', enabled);
        togTheme.classList.toggle('hover:bg-black', !enabled);
        togTheme.classList.toggle('hover:bg-neutral-600', enabled);
        
        localStorage.setItem('readerDark', enabled ? '1' : '0');
    }
    togTheme && togTheme.addEventListener('click', () => setDark(!(localStorage.getItem('readerDark') === '1')));
    if (localStorage.getItem('readerDark') === '1') setDark(true);

    // Width toggle with better user feedback
    const container = document.querySelector('.max-w-6xl');
    let isNarrow = false;
    
    function updateWidthToggle() {
        if (isNarrow) {
            container.classList.remove('max-w-6xl');
            container.classList.add('max-w-4xl');
            togWidth.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>Широкая';
            togWidth.title = 'Переключить на широкий режим';
        } else {
            container.classList.remove('max-w-4xl');
            container.classList.add('max-w-6xl');
            togWidth.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.5 3.5M15 9h4.5M15 9V4.5M15 9l5.5-5.5M9 15v4.5M9 15H4.5M9 15l-5.5 5.5M15 15h4.5M15 15v4.5m0-4.5l5.5 5.5"></path></svg>Узкая';
            togWidth.title = 'Переключить на узкий режим';
        }
    }
    
    togWidth && togWidth.addEventListener('click', () => {
        isNarrow = !isNarrow;
        updateWidthToggle();
        localStorage.setItem('readerNarrow', isNarrow ? '1' : '0');
    });
    
    // Initialize width toggle state
    if (localStorage.getItem('readerNarrow') === '1') {
        isNarrow = true;
    }
    updateWidthToggle();

    // Scroll progress + back to top visibility
    const backToTop = document.getElementById('backToTop');
    function updateProgress() {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const width = Math.min(100, Math.max(0, (scrollTop / docHeight) * 100));
        progress.style.width = width + '%';
        if (scrollTop > 600) backToTop.classList.add('show'); else backToTop.classList.remove('show');
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
    backToTop && backToTop.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // Mobile TOC drawer
    function openDrawer() { 
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawerFn() { 
        drawer.classList.remove('open');
        document.body.style.overflow = '';
    }
    openToc && openToc.addEventListener('click', openDrawer);
    openToc2 && openToc2.addEventListener('click', openDrawer);
    closeDrawer && closeDrawer.addEventListener('click', closeDrawerFn);
    backdrop && backdrop.addEventListener('click', closeDrawerFn);
    
    // Close drawer when clicking on TOC links
    document.querySelectorAll('.toc-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeDrawerFn();
            }
        });
    });

    // Collapsible long content within sections
    document.querySelectorAll('.content-body').forEach(function(block) {
        const textLength = block.innerText.trim().length;
        if (textLength > 4000) {
            block.classList.add('content-collapsed');
            const container = block.parentElement.querySelector('[data-expand-container]');
            if (container) {
                container.classList.remove('hidden');
                const btn = container.querySelector('[data-expand-btn]');
                btn.addEventListener('click', function() {
                    block.classList.remove('content-collapsed');
                    container.remove();
                });
            }
        }
    });

    // Improve anchor scrolling offset for fixed progress and headers
    function adjustHashScroll() {
        if (location.hash) {
            const el = document.querySelector(location.hash);
            if (el) {
                const y = el.getBoundingClientRect().top + window.scrollY - 96;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        }
    }
    window.addEventListener('load', adjustHashScroll);

    // Scrollspy: highlight current section in TOC
    const tocLinks = Array.from(document.querySelectorAll('.toc-link'));
    const anchors = Array.from(document.querySelectorAll('main section[id]'));
    const linkById = new Map(tocLinks.map(a => [a.getAttribute('data-anchor'), a]));
    let activeId = null;
    const spy = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activeId = entry.target.id;
                tocLinks.forEach(a => a.classList.remove('active'));
                const link = linkById.get(activeId);
                if (link) link.classList.add('active');
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px', threshold: 0.01 });
    anchors.forEach(sec => spy.observe(sec));
})();
</script>
@endsection