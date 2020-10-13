<?php

namespace Acadea\FullSite\Facades;

use Acadea\FullSite\FullSiteSearch;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Acadea\FullSite\FullSiteSearch
 */
class FullSite extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FullSiteSearch::class;
    }
}
