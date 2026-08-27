<?php

namespace App\Repositories\Eloquent;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    protected array $searchableColumns = ['name', 'email', 'subject', 'message'];

    protected array $filterableColumns = ['status', 'source', 'assigned_to'];

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public function submit(array $data): Model
    {
        return $this->model->newQuery()->create(array_merge($data, [
            'status' => 'new',
        ]));
    }

    public function markAsHandled(int $id, ?string $notes, ?int $handledByUserId): Model
    {
        $contact = $this->findOrFail($id);

        $contact->update([
            'status' => 'resolved',
            'notes' => $notes,
            'assigned_to' => $handledByUserId,
            'handled_at' => now(),
        ]);

        return $contact->refresh();
    }

    public function countByStatus(): array
    {
        return $this->model->newQuery()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }
}
