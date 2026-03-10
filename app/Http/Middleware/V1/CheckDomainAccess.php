<?php

namespace App\Http\Middleware\V1;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDomainAccess
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $domain = $this->tenantContext->getDomain();
        $user = $request->user();
        if (! $user || ! $domain || ! $user->tokenCan($domain)) {
            abort(403, 'Unauthorized domain access');
        }

        return $next($request);
    }
}
