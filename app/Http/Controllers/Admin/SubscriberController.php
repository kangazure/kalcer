<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function index(): View
    {
        return view('admin.subscribers.index', ['subscribers' => Subscriber::query()->latest('subscribed_at')->paginate(30)]);
    }

    public function export(): Response
    {
        $subscribers = Subscriber::query()->whereNull('unsubscribed_at')->get();

        $csv = "Email,Nama,Status,Tanggal Daftar\n";
        foreach ($subscribers as $sub) {
            $csv .= "{$sub->email},{$sub->name},".($sub->is_verified ? 'Terverifikasi' : 'Belum').",{$sub->subscribed_at->format('Y-m-d')}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers-'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber berhasil dihapus.');
    }
}
