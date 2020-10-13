<?php


\Illuminate\Support\Facades\Route::get(config('fullsite-search.api_endpoint'), [\Acadea\FullSite\Controller\SiteSearchController::class, 'search']);
