<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Domain\Models\Values;

final readonly class UpdateSectionValuesUseCase
{
    /**
     * @param  array<int, array{structure_id: int, value: mixed}>  $items
     */
    public function execute(PlatformSection $section, string $locale, array $items): PlatformSection
    {
        foreach ($items as $item) {
            $value = Values::firstOrCreate([
                'platform_section_id' => $section->id,
                'structure_id' => $item['structure_id'],
            ]);

            $value->translateOrNew($locale)->value = $item['value'];
            $value->save();
        }

        return $section->load([
            'section.structures.translations',
            'sectionValues.translations',
        ]);
    }
}
