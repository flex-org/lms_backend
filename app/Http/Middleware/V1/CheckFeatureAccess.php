<?php

namespace App\Http\Middleware\V1;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\Shared\Support\Services\TenantFeatureAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckFeatureAccess
{
    public function __construct(
        private readonly TenantFeatureAccessService $featureAccessService,
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $platform = $this->tenantContext->getPlatform();
        if (! $this->featureAccessService->hasAccess($platform, $feature)) {
            abort(403, __('middleware.feature_not_enabled'));
        }

        return $next($request);
    }
}
