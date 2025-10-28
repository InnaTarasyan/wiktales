@extends('layouts.app')

@section('title', 'Форум')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Форум</h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Topics List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Темы</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topics as $topic)
                        <a href="{{ route('forum.show', $topic) }}" class="block px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $topic->title }}</h3>
                                    <p class="mt-1 text-gray-600 line-clamp-2">{{ Str::limit($topic->description, 180) }}</p>
                                    <div class="mt-2 text-sm text-gray-500">Автор: {{ $topic->author_name }} • {{ $topic->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">Тем пока нет. Будьте первым!</div>
                    @endforelse
                </div>
                <div class="px-6 py-4">{{ $topics->links() }}</div>
            </div>
        </div>

        <!-- New Topic Form -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Новая тема</h2>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('forum.storeTopic') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Ваше имя</label>
                        <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('author_name') border-red-500 @enderror">
                        @error('author_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Заголовок</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                        <textarea id="description" name="description" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-300">Создать тему</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


