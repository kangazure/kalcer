<?php

namespace App\Events;

use App\Models\NetworkMonitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Dipicu setiap kali status node (POP/backbone) berubah, contoh:
 * online -> degraded, degraded -> offline, dst. Event ini di-broadcast
 * secara realtime ke channel publik agar dashboard NOC dan halaman
 * "Status Gangguan" dapat live-update tanpa polling.
 */
class NetworkNodeStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public NetworkMonitor $node,
        public string $previousStatus,
        public string $newStatus,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('network-monitor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'node.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'node_id' => $this->node->id,
            'node_name' => $this->node->node_name,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'changed_at' => now()->toIso8601String(),
        ];
    }
}
