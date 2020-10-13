<?php

namespace Acadea\FullSite\Tests;

class FullsiteSearchTest extends TestCase
{
    public function test_search_endpoint_can_be_reached()
    {

        $this->json('get', config('fullsite-search.api.url'))
            ->dump();

    }
}
