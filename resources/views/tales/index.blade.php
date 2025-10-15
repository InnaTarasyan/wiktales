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
        
        @media (max-width: 640px) {
            .book-card:hover .book-cover {
                transform: translateY(-4px);
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
    
    @if($tales->isEmpty())
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-2">No tales available yet</h3>
            <p class="text-gray-500">Check back soon for amazing stories!</p>
        </div>
    @endif
</div>
</body>
</html>


