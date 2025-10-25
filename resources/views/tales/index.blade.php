@extends('layouts.app')

@section('title', 'Коллекция сказок')

@section('content')
<div class="bookshelf-background min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-12 relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-4 drop-shadow-sm">
                Коллекция сказок
            </h1>
            <p class="text-gray-700 text-base sm:text-lg max-w-2xl mx-auto drop-shadow-sm">
                Откройте удивительные истории и приключения в нашей тщательно отобранной коллекции сказок
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6 md:gap-8 relative z-10">
            @foreach($tales as $tale)
                <div class="book-card group">
                    <a href="{{ route('tales.show', $tale) }}" class="block">
                        <div class="book-cover bg-white rounded-lg overflow-hidden relative">
                            <!-- Book spine effect -->
                            <div class="book-spine"></div>
                            <div class="book-pages"></div>
                            
                            @if($tale->cover_url)
                                <img src="{{ $tale->cover_url }}" 
                                     alt="{{ $tale->title }} cover" 
                                     class="w-full h-64 sm:h-72 object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-full h-64 sm:h-72 book-placeholder">
                                    Обложка недоступна
                                </div>
                            @endif
                            
                            <!-- Gradient overlay for better text readability -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        
                        <div class="mt-4 px-2">
                            <h2 class="book-title font-bold text-lg sm:text-xl text-center leading-tight line-clamp-2 group-hover:text-purple-700 transition-colors duration-300">
                                {{ $tale->title }}
                            </h2>
                            
                            <!-- Tale description -->
                            <div class="mt-2 sm:mt-3 px-2">
                                <p class="text-gray-700 text-[10px] xs:text-[11px] sm:text-xs leading-snug sm:leading-relaxed line-clamp-4 text-center">
                                    @if(isset($tale->meta['description']))
                                        {{ $tale->meta['description'] }}
                                    @else
                                        Уникальное произведение для настоящих ценителей литературы
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Read more indicator -->
                            <div class="flex items-center justify-center mt-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <span class="text-sm text-purple-600 font-medium flex items-center gap-1">
                                    Читать далее
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($tales->hasPages())
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($tales->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $tales->previousPageUrl() }}" rel="prev">‹</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($tales->getUrlRange(1, $tales->lastPage()) as $page => $url)
                    @if ($page == $tales->currentPage())
                        <span class="current">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($tales->hasMorePages())
                    <a href="{{ $tales->nextPageUrl() }}" rel="next">›</a>
                @else
                    <span class="disabled">›</span>
                @endif
            </div>
        @endif
        
        @if($tales->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Пока нет доступных сказок</h3>
                <p class="text-gray-500">Заходите скоро за удивительными историями!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Enhanced book card with realistic 3D shadows */
    .book-card {
        perspective: 1200px;
        transform-style: preserve-3d;
        position: relative;
    }
    
    .book-cover {
        position: relative;
        transform-style: preserve-3d;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 
            /* Main book shadow */
            0 8px 16px rgba(0, 0, 0, 0.12),
            0 4px 8px rgba(0, 0, 0, 0.08),
            /* Inner shadow for depth */
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            /* Book binding shadow */
            -2px 0 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .book-card:hover .book-cover {
        transform: translateY(-12px) rotateX(8deg) rotateY(-2deg);
        box-shadow: 
            /* Enhanced hover shadows */
            0 25px 50px rgba(0, 0, 0, 0.2),
            0 15px 30px rgba(0, 0, 0, 0.15),
            0 8px 16px rgba(0, 0, 0, 0.1),
            /* Inner glow */
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            /* Enhanced binding shadow */
            -4px 0 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Enhanced book spine with gradient and depth */
    .book-spine {
        position: absolute;
        left: -12px;
        top: 0;
        width: 12px;
        height: 100%;
        background: linear-gradient(90deg, 
            #6b46c1 0%, 
            #8b5cf6 25%, 
            #a855f7 50%, 
            #8b5cf6 75%, 
            #6b46c1 100%);
        transform: rotateY(-90deg);
        transform-origin: right center;
        border-radius: 6px 0 0 6px;
        box-shadow: 
            inset 2px 0 4px rgba(0, 0, 0, 0.2),
            -2px 0 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Enhanced book pages with layered effect */
    .book-pages {
        position: absolute;
        left: -8px;
        top: 0;
        width: 8px;
        height: 100%;
        background: linear-gradient(90deg, 
            #f8fafc 0%, 
            #f1f5f9 25%, 
            #e2e8f0 50%, 
            #f1f5f9 75%, 
            #f8fafc 100%);
        transform: rotateY(-90deg);
        transform-origin: right center;
        border-radius: 4px 0 0 4px;
        box-shadow: 
            inset 1px 0 2px rgba(0, 0, 0, 0.1),
            -1px 0 2px rgba(0, 0, 0, 0.05);
    }
    
    /* Additional page layers for realism */
    .book-pages::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 0;
        width: 4px;
        height: 100%;
        background: #f8fafc;
        transform: rotateY(-90deg);
        transform-origin: right center;
        border-radius: 2px 0 0 2px;
        box-shadow: inset 1px 0 1px rgba(0, 0, 0, 0.05);
    }
    
    .book-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .book-placeholder {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 0.875rem;
        box-shadow: 
            inset 0 2px 4px rgba(0, 0, 0, 0.1),
            0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .book-placeholder::before {
        content: "📖";
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    /* Bookshelf-like background with subtle wood texture */
    .bookshelf-background {
        background: 
            linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%),
            repeating-linear-gradient(
                90deg,
                transparent 0px,
                transparent 98px,
                rgba(139, 92, 246, 0.03) 100px
            );
        position: relative;
    }
    
    .bookshelf-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(168, 85, 247, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 3rem;
        position: relative;
        z-index: 10;
    }
    
    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(139, 92, 246, 0.2);
        color: #6b46c1;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .pagination a:hover {
        background: rgba(139, 92, 246, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    .pagination .active {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    /* Mobile responsive improvements */
    @media (max-width: 640px) {
        .book-card {
            perspective: 800px;
        }
        
        .book-cover {
            box-shadow: 
                0 6px 12px rgba(0, 0, 0, 0.1),
                0 3px 6px rgba(0, 0, 0, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                -1px 0 3px rgba(0, 0, 0, 0.08);
        }
        
        .book-card:hover .book-cover {
            transform: translateY(-8px) rotateX(5deg) rotateY(-1deg);
            box-shadow: 
                0 15px 30px rgba(0, 0, 0, 0.15),
                0 8px 16px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                -2px 0 6px rgba(0, 0, 0, 0.1);
        }
        
        .book-spine {
            left: -8px;
            width: 8px;
        }
        
        .book-pages {
            left: -6px;
            width: 6px;
        }
        
        .book-pages::before {
            left: -3px;
            width: 3px;
        }
        
        .pagination {
            margin-top: 2rem;
            gap: 0.25rem;
        }
        
        .pagination a,
        .pagination span {
            width: 2rem;
            height: 2rem;
            font-size: 0.875rem;
        }
    }
    
    /* Tablet responsive improvements */
    @media (min-width: 641px) and (max-width: 1024px) {
        .book-card:hover .book-cover {
            transform: translateY(-10px) rotateX(6deg) rotateY(-1.5deg);
        }
    }
    
    /* Large screen enhancements */
    @media (min-width: 1280px) {
        .book-card:hover .book-cover {
            transform: translateY(-15px) rotateX(10deg) rotateY(-3deg);
        }
    }
    
    .pagination .current {
        background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(76, 29, 149, 0.4);
    }
    
    .pagination .disabled {
        background: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
    }
    
    .pagination .disabled:hover {
        transform: none;
        box-shadow: none;
    }
</style>
@endsection