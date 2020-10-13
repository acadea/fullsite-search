<?php

namespace Acadea\FullSite\Facades;

use Acadea\FullSite\FullSiteSearch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method Collection search(string $keyword)
 * @see \Acadea\FullSite\FullSiteSearch
 */
class FullSite extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FullSiteSearch::class;
    }
}
