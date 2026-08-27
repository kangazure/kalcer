<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.contact.index', [
            'contacts' => Contact::query()
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function show(Contact $contact): View
    {
        return view('admin.contact.show', ['contact' => $contact]);
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,in_progress,resolved,closed,spam'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['assigned_to'] = $request->user()->id;
        if ($validated['status'] === 'resolved') {
            $validated['handled_at'] = now();
        }

        $contact->update($validated);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contact.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
