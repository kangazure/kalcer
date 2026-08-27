<?php

namespace App\Facades;

use App\Services\Supabase\SupabaseClient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\PendingRequest asService()
 * @method static \Illuminate\Http\Client\PendingRequest asAnon()
 * @method static \Illuminate\Http\Client\PendingRequest asUser(string $userAccessToken)
 * @method static \Illuminate\Http\Client\Response invokeEdgeFunction(string $functionName, array $payload = [])
 *
 * @see \App\Services\Supabase\SupabaseClient
 */
class Supabase extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SupabaseClient::class;
    }
}
