<?php


namespace Acadea\FullSite\Controller;

use Acadea\FullSite\FullSiteSearch;
use Acadea\FullSite\Resources\SiteSearchResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SiteSearchController extends Controller
{
    public function search(Request $request)
    {
        $results = FullSiteSearch::search($request->search);

        return SiteSearchResource::collection($results);
    }
}
