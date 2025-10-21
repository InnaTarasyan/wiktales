@extends('layouts.app')

@section('title', $tale->title)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="scroll-progress" id="scrollProgress"></div>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 reading-container">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="hidden lg:block w-64 shrink-0">
                <div class="toc-sticky bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-700">Содержание</h2>
                    </div>
                    <nav>
                        <ul class="space-y-2 text-sm">
                            @foreach($tale->sections as $section)
                                <li>
                                    <a href="#{{ $section->anchor }}" class="toc-link text-gray-700 hover:text-indigo-600" data-anchor="{{ $section->anchor }}">{{ $section->title ?? 'Раздел' }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </aside>
            <main class="min-w-0 flex-1">
                <div class="mb-6">
                    @if($tale->cover_url)
                        <img src="{{ $tale->cover_url }}" alt="{{ $tale->title }} cover" class="w-full max-h-96 object-cover rounded">
                    @endif
                    <div class="mt-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                        <h1 class="text-3xl font-bold">{{ $tale->title }}</h1>
                        <div class="reader-controls flex items-center gap-2 text-sm">
                            <button id="decreaseFont" class="px-3 py-2 bg-white ring-1 ring-gray-200 text-gray-700 hover:bg-gray-50">A-</button>
                            <button id="increaseFont" class="px-3 py-2 bg-white ring-1 ring-gray-200 text-gray-700 hover:bg-gray-50">A+</button>
                            <button id="toggleWidth" class="px-3 py-2 bg-white ring-1 ring-gray-200 text-gray-700 hover:bg-gray-50">Ширина</button>
                            <button id="toggleTheme" class="px-3 py-2 bg-gray-900 text-white hover:bg-black">Темная</button>
                            <button id="openToc" class="px-3 py-2 bg-indigo-600 text-white lg:hidden">Содержание</button>
                        </div>
                    </div>
                </div>
                
                <div class="lg:hidden">
                    <div class="flex items-center justify-between bg-white rounded-xl ring-1 ring-gray-100 p-4 shadow-sm">
                        <div class="text-sm text-gray-600">Содержание</div>
                        <button id="openToc2" class="px-3 py-2 bg-indigo-600 text-white rounded">Открыть</button>
                    </div>
                </div>

                <article class="prose max-w-none" id="readerArticle">
                    @foreach($tale->sections as $section)
                        <section id="{{ $section->anchor }}" class="scroll-mt-24 mb-10">
                            <div class="group bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-4 sm:p-6 md:p-8">
                                @if($section->title)
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <h2 class="text-xl sm:text-2xl font-semibold leading-tight tracking-tight text-gray-900">{{ $section->title }}</h2>
                                        <a href="#{{ $section->anchor }}" aria-label="Permalink" class="ml-2 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">#</a>
                                    </div>
                                @endif
                                <div class="prose prose-neutral max-w-none overflow-x-auto prose-img:rounded-lg prose-img:w-full prose-img:h-auto prose-a:text-blue-600 hover:prose-a:text-blue-700 prose-headings:scroll-mt-24 content-body">
                                    {!! $section->body_html !!}
                                </div>
                                <div class="mt-4 hidden" data-expand-container>
                                    <button class="px-3 py-2 text-sm bg-white ring-1 ring-gray-200 rounded hover:bg-gray-50" data-expand-btn>Показать больше</button>
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
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold">Содержание</h3>
                <button id="closeDrawer" class="px-3 py-1.5 bg-gray-100 rounded">Закрыть</button>
            </div>
            <nav>
                <ul class="divide-y divide-gray-100">
                    @foreach($tale->sections as $section)
                        <li>
                            <a href="#{{ $section->anchor }}" class="block py-2 text-gray-800" data-anchor="{{ $section->anchor }}">{{ $section->title ?? 'Раздел' }}</a>
                        </li>
                    @endforeach
                </ul>
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
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        max-height: 70vh;
        overflow: auto;
        padding: 1rem 1.25rem;
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
    }
    #readerArticle.prose p { margin-top: 0.9em; margin-bottom: 0.9em; }
    #readerArticle.prose img { margin: 1.25rem auto; }
    #readerArticle.prose blockquote { font-style: italic; border-left: 4px solid #e5e7eb; padding-left: 1rem; }
    #readerArticle.prose a { text-underline-offset: 3px; }
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
        document.body.classList.toggle('bg-gray-50', !enabled);
        document.body.classList.toggle('bg-neutral-900', enabled);
        document.body.classList.toggle('text-neutral-100', enabled);
        togTheme.textContent = enabled ? 'Светлая' : 'Темная';
        localStorage.setItem('readerDark', enabled ? '1' : '0');
    }
    togTheme && togTheme.addEventListener('click', () => setDark(!(localStorage.getItem('readerDark') === '1')));
    if (localStorage.getItem('readerDark') === '1') setDark(true);

    // Width toggle
    const container = document.querySelector('.max-w-6xl');
    togWidth && togWidth.addEventListener('click', () => {
        container.classList.toggle('max-w-6xl');
        container.classList.toggle('max-w-3xl');
        localStorage.setItem('readerNarrow', container.classList.contains('max-w-3xl') ? '1' : '0');
    });
    if (localStorage.getItem('readerNarrow') === '1') {
        container.classList.remove('max-w-6xl');
        container.classList.add('max-w-3xl');
    }

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
    function openDrawer() { drawer.classList.add('open'); }
    function closeDrawerFn() { drawer.classList.remove('open'); }
    openToc && openToc.addEventListener('click', openDrawer);
    openToc2 && openToc2.addEventListener('click', openDrawer);
    closeDrawer && closeDrawer.addEventListener('click', closeDrawerFn);
    backdrop && backdrop.addEventListener('click', closeDrawerFn);

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