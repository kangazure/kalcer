<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactFormRequest;
use App\Services\Public\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function index(): View
    {
        return view('pages.contact.index');
    }

    public function submit(ContactFormRequest $request): JsonResponse
    {
        $this->contactService->submit($request->validated(), $request);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Pesan Anda telah kami terima dan akan segera ditindaklanjuti.',
        ], 201);
    }
}
