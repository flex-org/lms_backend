<?php

namespace App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Platforms\Domain\Repositories\ThemeRepositoryInterface;
use App\Modules\V1\Themes\Domain\Models\Theme;

class EloquentThemeRepository implements ThemeRepositoryInterface
{
    public function getDefaultFreeTheme(): ?Theme
    {
        return Theme::firstWhere('price', null);
    }
}
