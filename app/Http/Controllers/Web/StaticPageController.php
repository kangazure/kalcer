<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function privacyPolicy(): View
    {
        return view('pages.legal.privacy-policy');
    }

    public function terms(): View
    {
        return view('pages.legal.terms');
    }
}
