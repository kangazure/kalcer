<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('pages.faq.index', [
            'faqsByCategory' => Faq::query()->where('is_active', true)->orderBy('sort_order')->get()->groupBy('category'),
        ]);
    }
}
