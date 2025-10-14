<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tale->title }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio,line-clamp,forms"></script>
</head>
<body class="bg-gray-50">
<div class="max-w-5xl mx-auto p-6">
    <div class="mb-6">
        @if($tale->cover_url)
            <img src="{{ $tale->cover_url }}" alt="{{ $tale->title }} cover" class="w-full max-h-96 object-cover rounded">
        @endif
        <h1 class="mt-4 text-3xl font-bold">{{ $tale->title }}</h1>
    </div>
    <style>
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
            <section id="{{ $section->anchor }}" class="scroll-mt-24 mb-10">
                <div class="group bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-4 sm:p-6 md:p-8">
                    @if($section->title)
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <h2 class="text-xl sm:text-2xl font-semibold leading-tight tracking-tight text-gray-900">{{ $section->title }}</h2>
                            <a href="#{{ $section->anchor }}" aria-label="Permalink" class="ml-2 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">#</a>
                        </div>
                    @endif
                    <div class="prose prose-neutral max-w-none overflow-x-auto prose-img:rounded-lg prose-img:w-full prose-img:h-auto prose-a:text-blue-600 hover:prose-a:text-blue-700 prose-headings:scroll-mt-24">
                        {!! $section->body_html !!}
                    </div>
                </div>
            </section>
        @endforeach
    </article>
</div>
</body>
</html>


