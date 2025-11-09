@extends('layouts.app')

@section('title', 'Мифы и метаморфозы: Литературное собрание')

@section('content')
<div class="main-page-wrapper">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="gradient-text">Мифы и метаморфозы</span>
            </h1>
            <p class="hero-subtitle">
                Глубокое погружение в мифологическое сознание, где древние архетипы встречаются с современной экзистенциальной реальностью. Литературные произведения, исследующие границы между сном и явью, памятью и забвением, мифом и историей
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Tales Grid -->
            <div class="tales-grid">
                @foreach($tales as $tale)
                    <article class="tale-card">
                        <a href="{{ route('tales.show', $tale) }}" class="tale-card-link" aria-label="Читать {{ $tale->title }}">
                            <div class="tale-cover-wrapper">
                                @if($tale->cover_url)
                                    <img 
                                        src="{{ $tale->cover_url }}" 
                                        alt="{{ $tale->title }} cover" 
                                        class="tale-cover-image"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="tale-cover-placeholder">
                                        <svg class="book-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="placeholder-text">Обложка недоступна</span>
                                    </div>
                                @endif
                                <div class="tale-overlay"></div>
                            </div>
                            
                            <div class="tale-content">
                                <h2 class="tale-title">
                                    {{ $tale->title }}
                                </h2>
                                
                                <p class="tale-description">
                                    @if(isset($tale->meta['description']))
                                        {{ $tale->meta['description'] }}
                                    @else
                                        Литературное исследование мифологических архетипов и экзистенциальных границ человеческого сознания
                                    @endif
                                </p>
                                
                                <div class="tale-footer">
                                    <span class="read-more">
                                        Читать далее
                                        <svg class="arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($tales->hasPages())
                <nav class="pagination-wrapper" aria-label="Навигация по страницам">
                    <div class="pagination">
                        @if ($tales->onFirstPage())
                            <span class="pagination-btn disabled" aria-disabled="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $tales->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="Предыдущая страница">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        <div class="pagination-numbers">
                            @foreach ($tales->getUrlRange(1, $tales->lastPage()) as $page => $url)
                                @if ($page == $tales->currentPage())
                                    <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if ($tales->hasMorePages())
                            <a href="{{ $tales->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="Следующая страница">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="pagination-btn disabled" aria-disabled="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </nav>
            @endif
            
            <!-- Empty State -->
            @if($tales->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <h3 class="empty-title">Хранилище мифов пустует</h3>
                    <p class="empty-description">Архетипы дремлют в ожидании пробуждения. Скоро здесь появятся произведения, исследующие глубины мифологического сознания</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* ====================
       Main Page Wrapper
       ==================== */
    .main-page-wrapper {
        min-height: 100vh;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        position: relative;
        overflow-x: hidden;
    }

    .main-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(168, 85, 247, 0.06) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    /* ====================
       Hero Section
       ==================== */
    .hero-section {
        position: relative;
        padding: 3rem 1rem 2rem;
        text-align: center;
        z-index: 1;
    }

    @media (min-width: 640px) {
        .hero-section {
            padding: 4rem 1.5rem 3rem;
        }
    }

    @media (min-width: 1024px) {
        .hero-section {
            padding: 5rem 2rem 4rem;
        }
    }

    .hero-content {
        max-width: 48rem;
        margin: 0 auto;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    @media (min-width: 640px) {
        .hero-title {
            font-size: 2.5rem;
        }
    }

    @media (min-width: 1024px) {
        .hero-title {
            font-size: 3.5rem;
        }
    }

    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }

    .hero-subtitle {
        font-size: 1rem;
        color: #475569;
        line-height: 1.6;
        max-width: 42rem;
        margin: 0 auto;
    }

    @media (min-width: 640px) {
        .hero-subtitle {
            font-size: 1.125rem;
        }
    }

    /* ====================
       Content Wrapper
       ==================== */
    .content-wrapper {
        position: relative;
        z-index: 1;
        padding-bottom: 3rem;
    }

    .container {
        max-width: 90rem;
        margin: 0 auto;
        padding: 0 1rem;
    }

    @media (min-width: 640px) {
        .container {
            padding: 0 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .container {
            padding: 0 2rem;
        }
    }

    /* ====================
       Tales Grid
       ==================== */
    .tales-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    @media (min-width: 640px) {
        .tales-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (min-width: 768px) {
        .tales-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
    }

    @media (min-width: 1024px) {
        .tales-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
    }

    @media (min-width: 1280px) {
        .tales-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
    }

    @media (min-width: 1536px) {
        .tales-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 2rem;
        }
    }

    /* ====================
       Tale Card
       ==================== */
    .tale-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .tale-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 640px) {
        .tale-card:hover {
            transform: translateY(-4px);
        }
    }

    .tale-card-link {
        display: flex;
        flex-direction: column;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }

    /* ====================
       Tale Cover
       ==================== */
    .tale-cover-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .tale-cover-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tale-card:hover .tale-cover-image {
        transform: scale(1.05);
    }

    .tale-cover-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: #64748b;
    }

    .book-icon {
        width: 3rem;
        height: 3rem;
        margin-bottom: 0.5rem;
        color: #94a3b8;
    }

    .placeholder-text {
        font-size: 0.875rem;
        text-align: center;
        padding: 0 1rem;
    }

    .tale-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .tale-card:hover .tale-overlay {
        opacity: 1;
    }

    /* ====================
       Tale Content
       ==================== */
    .tale-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    @media (min-width: 640px) {
        .tale-content {
            padding: 1.5rem;
        }
    }

    .tale-title {
        font-size: 1.125rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 0.75rem;
        color: #1e293b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    @media (min-width: 640px) {
        .tale-title {
            font-size: 1.25rem;
        }
    }

    .tale-card:hover .tale-title {
        color: #6366f1;
    }

    .tale-description {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (min-width: 640px) {
        .tale-description {
            font-size: 0.9375rem;
            -webkit-line-clamp: 4;
        }
    }

    .tale-footer {
        margin-top: auto;
        padding-top: 0.5rem;
    }

    .read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6366f1;
        transition: all 0.3s ease;
    }

    .tale-card:hover .read-more {
        gap: 0.75rem;
        color: #4f46e5;
    }

    .arrow-icon {
        width: 1.25rem;
        height: 1.25rem;
        transition: transform 0.3s ease;
    }

    .tale-card:hover .arrow-icon {
        transform: translateX(4px);
    }

    /* ====================
       Pagination
       ==================== */
    .pagination-wrapper {
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 640px) {
        .pagination {
            gap: 0.375rem;
        }
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: white;
        border: 1px solid #e2e8f0;
        color: #6366f1;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        text-decoration: none;
    }

    @media (max-width: 640px) {
        .pagination-btn {
            width: 2.25rem;
            height: 2.25rem;
        }
    }

    .pagination-btn:hover:not(.disabled) {
        background: #f8fafc;
        border-color: #c7d2fe;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .pagination-btn.disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-btn svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .pagination-numbers {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    @media (max-width: 640px) {
        .pagination-numbers {
            gap: 0.375rem;
        }
    }

    .pagination-number {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.5rem;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        text-decoration: none;
    }

    @media (max-width: 640px) {
        .pagination-number {
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0 0.5rem;
            font-size: 0.8125rem;
        }
    }

    .pagination-number:hover {
        background: #f8fafc;
        border-color: #c7d2fe;
        color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .pagination-number.active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        cursor: default;
    }

    .pagination-number.active:hover {
        transform: none;
    }

    /* ====================
       Empty State
       ==================== */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .empty-description {
        font-size: 1rem;
        color: #64748b;
    }

    /* ====================
       Touch Device Optimizations
       ==================== */
    @media (hover: none) and (pointer: coarse) {
        .tale-card:active {
            transform: translateY(-2px);
        }

        .pagination-btn:active:not(.disabled),
        .pagination-number:active {
            transform: scale(0.95);
        }
    }

    /* ====================
       Accessibility Improvements
       ==================== */
    .tale-card-link:focus {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
        border-radius: 0.5rem;
    }

    .pagination-btn:focus,
    .pagination-number:focus {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
    }

    /* ====================
       Print Styles
       ==================== */
    @media print {
        .main-page-wrapper::before {
            display: none;
        }

        .tale-card {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #e2e8f0;
        }

        .pagination-wrapper {
            display: none;
        }
    }
</style>
@endsection