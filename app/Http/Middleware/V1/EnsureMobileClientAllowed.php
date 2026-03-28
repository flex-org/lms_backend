<?php

namespace App\Http\Middleware\V1;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileClientAllowed
{
    public const HEADER = 'X-Client-Type';

    public const CHANNEL_WEB = 'web';

    public const CHANNEL_MOBILE = 'mobile';

    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $raw = $request->header(self::HEADER);
        $trimmed = $raw === null ? '' : trim($raw);

        if ($trimmed === '') {
            abort(422, 'Invalid X-Client-Type. Allowed values: mobile, web.');
        } else {
            $channel = strtolower($trimmed);
            if (! in_array($channel, [self::CHANNEL_WEB, self::CHANNEL_MOBILE], true)) {
                abort(422, 'Invalid X-Client-Type. Allowed values: mobile, web.');
            }
        }

        $request->attributes->set('client_channel', $channel);

        if (
            $channel === self::CHANNEL_MOBILE
            && $this->tenantContext->isResolved()
            && ! (bool) $this->tenantContext->getPlatform()?->has_mobile_app
        ) {
            abort(403, 'apiMessages.mobile_app_disabled');
        }

        return $next($request);
    }
}
