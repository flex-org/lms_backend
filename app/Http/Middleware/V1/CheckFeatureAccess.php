<?php

namespace App\Http\Middleware\V1;

use App\Modules\Shared\Support\Services\TenantFeatureAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function __construct(private readonly TenantFeatureAccessService $featureAccessService)
    {
    }

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user || ! $this->featureAccessService->hasAccess($user, $feature)) {
            abort(403, 'Feature is not enabled for this tenant.');
        }

        return $next($request);
    }
}
