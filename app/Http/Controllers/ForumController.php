<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Topic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        /** @var LengthAwarePaginator $topics */
        $topics = Topic::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('forum.index', [
            'topics' => $topics,
        ]);
    }

    public function show(Topic $topic): View
    {
        $topic->load(['answers' => function ($q) {
            $q->latest();
        }]);

        return view('forum.show', [
            'topic' => $topic,
        ]);
    }

    public function storeTopic(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:100'],
        ]);

        $topic = Topic::create($validated);

        return redirect()->route('forum.show', $topic)->with('success', 'Тема создана.');
    }

    public function storeAnswer(Request $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string'],
        ]);

        $validated['topic_id'] = $topic->id;
        Answer::create($validated);

        return redirect()->route('forum.show', $topic)->with('success', 'Ответ добавлен.');
    }
}


