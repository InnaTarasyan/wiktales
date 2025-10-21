<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .book-card {
            perspective: 1000px;
            transform-style: preserve-3d;
        }
        
        .book-cover {
            position: relative;
            transform-style: preserve-3d;
            transition: all 0.3s ease;
            box-shadow: 
                0 4px 8px rgba(0, 0, 0, 0.1),
                0 2px 4px rgba(0, 0, 0, 0.06);
        }
        
        .book-card:hover .book-cover {
            transform: translateY(-8px) rotateX(5deg);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.15),
                0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .book-spine {
            position: absolute;
            left: -8px;
            top: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(90deg, #8b5cf6, #a855f7);
            transform: rotateY(-90deg);
            transform-origin: right center;
            border-radius: 4px 0 0 4px;
        }
        
        .book-pages {
            position: absolute;
            left: -4px;
            top: 0;
            width: 4px;
            height: 100%;
            background: #f8fafc;
            transform: rotateY(-90deg);
            transform-origin: right center;
            border-radius: 2px 0 0 2px;
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
        }
        
        .book-placeholder::before {
            content: "📖";
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }
        
        .pagination a,
        .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .pagination a {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .pagination a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
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
        
        @media (max-width: 640px) {
            .book-card:hover .book-cover {
                transform: translateY(-4px);
            }
            
            .pagination {
                gap: 0.25rem;
                flex-wrap: wrap;
            }
            
            .pagination a,
            .pagination span {
                width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl sm:text-5xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-4">
            Tales Collection
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
            Discover amazing stories and adventures in our curated collection of tales
        </p>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-8">
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
                                No cover available
                            </div>
                        @endif
                        
                        <!-- Gradient overlay for better text readability -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <div class="mt-4 px-2">
                        <h2 class="book-title font-bold text-lg sm:text-xl text-center leading-tight line-clamp-2 group-hover:text-purple-700 transition-colors duration-300">
                            {{ $tale->title }}
                        </h2>
                        
                        <!-- Read more indicator -->
                        <div class="flex items-center justify-center mt-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <span class="text-sm text-purple-600 font-medium flex items-center gap-1">
                                Read more
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
            <h3 class="text-2xl font-semibold text-gray-700 mb-2">No tales available yet</h3>
            <p class="text-gray-500">Check back soon for amazing stories!</p>
        </div>
    @endif
</div>

<!-- Footer -->
<footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-200">
    <div class="text-center text-xs sm:text-sm text-gray-600">
        <p class="mb-2 sm:mb-0">&copy; {{ date('Y') }} Wiktales. All rights reserved.</p>
        <p class="text-xs sm:text-sm leading-relaxed max-w-lg mx-auto">Discover amazing stories and adventures in our curated collection of tales.</p>
    </div>
</footer>
</body>
</html>


