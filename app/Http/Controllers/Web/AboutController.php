<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('pages.about.index', [
            'team' => Team::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function vision(): View
    {
        return view('pages.about.vision');
    }

    public function history(): View
    {
        return view('pages.about.history');
    }
}
