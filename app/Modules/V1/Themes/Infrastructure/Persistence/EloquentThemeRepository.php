<?php

namespace App\Modules\V1\Themes\Infrastructure\Persistence;

use App\Modules\V1\Themes\Domain\Models\Theme;
use App\Modules\V1\Themes\Domain\Repositories\ThemeRepositoryInterface;

class EloquentThemeRepository implements ThemeRepositoryInterface
{
    public function getDefaultFreeTheme(): ?Theme
    {
        return Theme::firstWhere('price', null);
    }
}
