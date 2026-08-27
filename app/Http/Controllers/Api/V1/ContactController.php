<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Public\ContactFormRequest;
use App\Services\Public\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends ApiController
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function store(ContactFormRequest $request): JsonResponse
    {
        $this->contactService->submit($request->validated(), $request);

        return $this->created(message: 'Pesan Anda berhasil terkirim.');
    }
}
