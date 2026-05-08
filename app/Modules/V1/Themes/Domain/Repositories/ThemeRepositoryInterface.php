<?php

namespace App\Modules\V1\Themes\Domain\Repositories;

use App\Modules\V1\Themes\Domain\Models\Theme;

interface ThemeRepositoryInterface
{
    public function getDefaultFreeTheme(): ?Theme;
}
