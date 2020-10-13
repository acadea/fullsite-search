<?php

namespace Acadea\FullSite\Facades;

use Acadea\FullSite\Controller\SiteSearchController;
use Acadea\FullSite\FullSiteSearch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

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

    public static function routes()
    {
        // register the api routes
        Route::get(config('fullsite-search.api.url'), [SiteSearchController::class, 'search']);

    }
}
