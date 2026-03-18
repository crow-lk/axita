<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class PopupBannerRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Core\Contracts\PopupBanner';
    }

    /**
     * Get active popup banners ordered by sort order
     */
    public function getActiveBanners()
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
