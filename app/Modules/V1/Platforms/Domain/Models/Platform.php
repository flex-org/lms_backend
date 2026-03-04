<?php

namespace App\Modules\V1\Platforms\Domain\Models;

use App\Models\V1\SellingSystem;
use App\Models\V1\User;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Themes\Domain\Models\Theme;
use App\Modules\V1\Utilities\Support\Enums\SellingSystemEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class Platform extends Model
{
    use HasRoles;

    protected $guard_name = 'sanctum';
    protected $fillable = [
        'user_id',
        'theme_id',
        'domain',
        'storage',
        'capacity',
        'selling_system',
        'has_mobile_app',
        'started_at',
        'renew_at',
        'cost',
        'status',
    ];

    public $casts = [
        'selling_system' => SellingSystemEnum::class,
        'status' => PLatformStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class);
    }


    public function features ()
    {
        return $this->belongsToMany(Feature::class, 'platform_features');
    }
    public function sellingSystems(): BelongsToMany
    {
        return $this->belongsToMany(
            SellingSystem::class,
            'platform_selling_systems',
            'platform_id',
            'selling_system_id'
        );
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($platform) {

            $platform->themes()->attach(
                Theme::whereNull('price')->pluck('id')
            );
        });
    }
}
