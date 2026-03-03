<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Platforms\Requests\PlatformCreateRequest;
use App\Modules\V1\Platforms\Services\PlatformService;

class PlatformController extends Controller
{
    public function __construct(public PlatformService $service)
    {
    }

    /**
     *
     * Store a newly created resource in storage.
     */
    public function store(PlatformCreateRequest $request)
    {
        $platform = $this->service->create($request->validated());
        return ApiResponse::created(data:$platform,);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

}
