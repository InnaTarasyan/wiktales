<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tale;

class TaleController extends Controller
{
    public function index()
    {
        $tales = Tale::query()->orderBy('title')->paginate(12);
        return view('tales.index', compact('tales'));
    }

    public function show(Tale $tale)
    {
        $tale->load('sections');
        return view('tales.show', compact('tale'));
    }
}
