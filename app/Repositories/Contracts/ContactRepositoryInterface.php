<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    public function submit(array $data): Model;

    public function markAsHandled(int $id, ?string $notes, ?int $handledByUserId): Model;

    public function countByStatus(): array;
}
