<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(protected TestimonialRepositoryInterface $testimonials)
    {
    }

    public function index(): View
    {
        return view('pages.testimonial.index', [
            'testimonials' => $this->testimonials->published(50),
        ]);
    }
}
