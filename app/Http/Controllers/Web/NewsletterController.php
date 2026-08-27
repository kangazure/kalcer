<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\NewsletterSubscribeRequest;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request): JsonResponse
    {
        $subscriber = Subscriber::query()->firstOrCreate(
            ['email' => $request->validated('email')],
            [
                'name' => $request->validated('name'),
                'verification_token' => Str::random(40),
                'unsubscribe_token' => Str::random(40),
                'source' => 'website',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah berlangganan! Cek email Anda untuk konfirmasi.',
        ], 201);
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $subscriber = Subscriber::query()->where('unsubscribe_token', $token)->first();

        if ($subscriber) {
            $subscriber->update(['unsubscribed_at' => now()]);
        }

        return redirect()->route('home')->with('success', 'Anda telah berhenti berlangganan newsletter.');
    }
}
