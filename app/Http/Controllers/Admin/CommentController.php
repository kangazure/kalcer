<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.comments.index', [
            'comments' => Comment::query()
                ->with(['user:id,name', 'post:id,title,slug'])
                ->when($request->status, fn ($q, $s) => $q->where('status', $s), fn ($q) => $q->where('status', 'pending'))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Komentar disetujui.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Komentar ditolak.');
    }

    public function markSpam(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'spam']);

        return back()->with('success', 'Komentar ditandai sebagai spam.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Komentar dihapus.');
    }
}
