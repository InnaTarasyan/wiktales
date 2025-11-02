@extends('layouts.app')

@section('title', $tale->title)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="scroll-progress" id="scrollProgress"></div>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 reading-container">
        <main class="w-full">
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
                        <button id="toggleTheme" class="px-3 py-2 bg-gray-900 text-white hover:bg-black rounded-lg transition-colors duration-200" title="Переключить тему">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            Темная
                        </button>
                        <a href="{{ route('tales.download', $tale) }}" class="px-3 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg transition-colors duration-200" title="Скачать сказку">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Скачать
                        </a>
                    </div>
                </div>
            </div>

            <article class="prose bg-white rounded-2xl shadow-xl ring-1 ring-gray-100/50 backdrop-blur-sm p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12 mx-auto transition-all duration-300 ease-in-out" id="readerArticle">
                @foreach($tale->sections as $section)
                    {!! $section->body_html !!}
                @endforeach
            </article>
        </main>
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
    .reader-controls button {
        border-radius: 0.5rem;
    }
    /* Modern article container styling */
    #readerArticle {
        max-width: 100%;
        width: 100%;
    }
    
    @media (min-width: 640px) {
        #readerArticle {
            max-width: 42rem;
        }
    }
    
    @media (min-width: 1024px) {
        #readerArticle {
            max-width: 48rem;
        }
    }
    
    @media (min-width: 1280px) {
        #readerArticle {
            max-width: 56rem;
        }
    }
    
    /* Touch-friendly hover effects (disabled on touch devices) */
    @media (hover: hover) and (pointer: fine) {
        #readerArticle:hover {
            transform: translateY(-2px);
        }
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
    
    .bg-neutral-900 article {
        background: #1f2937 !important;
        border-color: #374151 !important;
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
            padding: 0.75rem;
        }
        
        #readerArticle {
            border-radius: 1rem;
            margin-left: 0;
            margin-right: 0;
        }
        
        #readerArticle.prose {
            font-size: 16px;
            line-height: 1.75;
        }
        
        .reader-controls {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .reader-controls button {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }
    
    /* Tablet optimization */
    @media (min-width: 641px) and (max-width: 1023px) {
        #readerArticle {
            border-radius: 1.25rem;
        }
    }
    
    /* Better touch targets for mobile */
    @media (max-width: 768px) {
        .reader-controls button {
            min-height: 44px;
            min-width: 44px;
        }
    }
    
    /* Improved focus states for accessibility */
    .reader-controls button:focus {
        outline: 2px solid #4f46e5;
        outline-offset: 2px;
    }
    
    /* Better scroll behavior */
    html {
        scroll-behavior: smooth;
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
        const container = document.querySelector('.max-w-7xl');
        const articleEl = document.getElementById('readerArticle');
        const controls = document.querySelector('.reader-controls');
        
        // Toggle body background and text
        body.classList.toggle('bg-gray-50', !enabled);
        body.classList.toggle('bg-neutral-900', enabled);
        body.classList.toggle('text-neutral-100', enabled);
        
        // Toggle main container
        if (container) {
            container.classList.toggle('text-gray-900', !enabled);
            container.classList.toggle('text-neutral-100', enabled);
        }
        
        // Toggle article
        if (articleEl) {
            articleEl.classList.toggle('bg-white', !enabled);
            articleEl.classList.toggle('bg-neutral-800', enabled);
            articleEl.classList.toggle('ring-gray-100', !enabled);
            articleEl.classList.toggle('ring-neutral-700', enabled);
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
})();
</script>
@endsection
