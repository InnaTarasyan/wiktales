@extends('layouts.app')

@section('title', $topic->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('forum.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">← Назад к списку тем</a>

    @if(session('success'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $topic->title }}</h1>
        <div class="mt-2 text-sm text-gray-500">Автор: {{ $topic->author_name }} • {{ $topic->created_at->format('d.m.Y H:i') }}</div>
        <div class="mt-4 text-gray-800 whitespace-pre-line">{{ $topic->description }}</div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ответы</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($topic->answers as $answer)
                <div class="px-6 py-4">
                    <div class="text-sm text-gray-500 mb-1">{{ $answer->author_name }} • {{ $answer->created_at->format('d.m.Y H:i') }}</div>
                    <div class="text-gray-800 whitespace-pre-line">{{ $answer->message }}</div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">Ответов пока нет.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Оставить ответ</h2>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('forum.storeAnswer', $topic) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Ваше имя</label>
                <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('author_name') border-red-500 @enderror">
                @error('author_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Сообщение</label>
                <textarea id="message" name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-300">Отправить</button>
        </form>
    </div>
</div>
@endsection


