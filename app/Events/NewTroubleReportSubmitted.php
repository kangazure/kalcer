<?php

namespace App\Events;

use App\Models\TroubleReport;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTroubleReportSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public TroubleReport $troubleReport)
    {
    }
}
