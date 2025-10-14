<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tale->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="max-w-5xl mx-auto p-6">
    <div class="mb-6">
        @if($tale->cover_url)
            <img src="{{ $tale->cover_url }}" alt="{{ $tale->title }} cover" class="w-full max-h-96 object-cover rounded">
        @endif
        <h1 class="mt-4 text-3xl font-bold">{{ $tale->title }}</h1>
    </div>

    @if(!empty($tale->toc))
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-2">Contents</h2>
            <ul class="list-disc pl-6 space-y-1">
                @foreach($tale->toc as $entry)
                    @php $anchor = \Illuminate\Support\Str::slug($entry['title']); @endphp
                    <li>
                        <a href="#{{ $anchor }}" class="text-blue-600 hover:underline">{{ $entry['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <article class="prose max-w-none">
        @foreach($tale->sections as $section)
            <section id="{{ $section->anchor }}" class="scroll-mt-20 mb-8">
                @if($section->title)
                    <h2 class="text-2xl font-semibold mb-2">{{ $section->title }}</h2>
                @endif
                <div class="prose" >{!! $section->body_html !!}</div>
            </section>
        @endforeach
    </article>
</div>
</body>
</html>


