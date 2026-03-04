<?php

namespace App\Modules\V1\Platforms\Domain\Repositories;

use App\Modules\V1\Themes\Models\Theme;

interface ThemeRepositoryInterface
{
    public function getDefaultFreeTheme(): ?Theme;
}
