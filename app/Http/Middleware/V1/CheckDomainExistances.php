<?php

namespace App\Http\Middleware\V1;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckDomainExistances
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->header('domain');

        if (! $domain) {
            throw new NotFoundHttpException('Platform not found.');
        }

        $this->tenantContext->setDomain($domain);

        if (! $this->tenantContext->isResolved()) {
            throw new NotFoundHttpException('Platform not found.');
        }

        return $next($request);
    }
}
