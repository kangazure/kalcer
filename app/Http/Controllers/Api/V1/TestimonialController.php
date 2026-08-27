<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\TestimonialResource;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TestimonialController extends ApiController
{
    public function __construct(protected TestimonialRepositoryInterface $testimonials)
    {
    }

    public function index(): JsonResponse
    {
        return $this->success(TestimonialResource::collection($this->testimonials->published(50)));
    }

    public function featured(): JsonResponse
    {
        return $this->success(TestimonialResource::collection($this->testimonials->featured(8)));
    }
}
