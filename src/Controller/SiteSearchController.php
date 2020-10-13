<?php


namespace Acadea\FullSite\Controller;

use Acadea\FullSite\Facades\FullSite;
use Acadea\FullSite\Resources\SiteSearchResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SiteSearchController extends Controller
{
    public function search(Request $request)
    {
        $results = FullSite::search($request->search);

        return SiteSearchResource::collection($results);
    }
}
