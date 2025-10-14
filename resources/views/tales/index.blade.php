<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tales</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Tales</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($tales as $tale)
            <a href="{{ route('tales.show', $tale) }}" class="block bg-white rounded shadow hover:shadow-md transition overflow-hidden">
                @if($tale->cover_url)
                    <img src="{{ $tale->cover_url }}" alt="{{ $tale->title }} cover" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No cover</div>
                @endif
                <div class="p-4">
                    <h2 class="font-semibold text-lg">{{ $tale->title }}</h2>
                </div>
            </a>
        @endforeach
    </div>
</div>
</body>
</html>


