<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends ApiController
{
    public function index(): JsonResponse
    {
        $faqs = Faq::query()->active()->get(['id', 'category', 'question', 'answer'])->groupBy('category');

        return $this->success($faqs);
    }
}
