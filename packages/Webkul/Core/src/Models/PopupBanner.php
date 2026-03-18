<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\PopupBanner as PopupBannerContract;

class PopupBanner extends Model implements PopupBannerContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'popup_banners';

    /**
     * Fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'image_path',
        'link',
        'is_active',
        'sort_order',
    ];

    /**
     * Casts for the model.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
