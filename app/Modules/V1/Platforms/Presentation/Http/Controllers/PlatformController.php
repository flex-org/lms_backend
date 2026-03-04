<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformAction;
use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformData;
use App\Modules\V1\Platforms\Presentation\Http\Requests\PlatformCreateRequest;

class PlatformController extends Controller
{
    public function __construct(private readonly CreatePlatformAction $createPlatformAction)
    {
    }

    public function store(PlatformCreateRequest $request)
    {
        $platform = $this->createPlatformAction->execute(CreatePlatformData::fromArray($request->validated()));

        return ApiResponse::created(data: $platform);
    }

    public function show(string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
