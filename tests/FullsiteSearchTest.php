<?php

namespace Acadea\FullSite\Tests;

use Acadea\FullSite\FullSiteSearch;
use Acadea\FullSite\Tests\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class FullsiteSearchTest extends TestCase
{
    public function test_search_endpoint_can_be_reached()
    {
        $response = $this->json('get', config('fullsite-search.api.url'));

        $status = $response->status();

        // the service provider is dependent on the preset config file
        if (config('fullsite-search.api.disabled')) {
            $this->assertTrue($status === 404);
        } else {
            $this->assertTrue($status !== 404);
        }
    }

    public function test_can_search()
    {
        $posts = Post::factory()->count(10)->create();
        $results = FullSiteSearch::search('hey');
        $this->assertInstanceOf(Collection::class, $results);
    }

    public function test_model_view_link_is_generated_correctly()
    {
        $post = $this->createPost();

        $link = FullSiteSearch::resolveModelViewLink($post);

        $default = URL::to('/posts/' . $post->id);
        $this->assertEquals($default, $link);

        // testing customised url work
        config([
            'fullsite-search.view_mapping' => [
                Post::class => '/laksa/{id}',
            ],
        ]);

        $link = FullSiteSearch::resolveModelViewLink($post);

        $this->assertEquals(URL::to('/laksa/' . $post->id), $link);
    }

    private function createPost()
    {
        return Post::query()->create([
            'title' => 'heyy you babararawea we are on fire just test this one out is extremely long is there an end?',
        ]);
    }

    public function test_create_match_attribute()
    {
        $post = $this->createPost();
        $fields = ['title'];

        $mid = FullSiteSearch::createMatchAttribute($post, $fields, 'test');

        $first = FullSiteSearch::createMatchAttribute($post, $fields, 'you');

        $end = FullSiteSearch::createMatchAttribute($post, $fields, 'there');

        $this->assertEquals('...fire just test this one ...', $mid);
        $this->assertEquals('heyy you babararawea we...', $first);
        $this->assertEquals('...y long is there an end?', $end);

        // changing buffer
        config([
            'fullsite-search.buffer' => 20,
        ]);

        $mid = FullSiteSearch::createMatchAttribute($post, $fields,'test');

        $this->assertEquals('...we are on fire just test this one out is ext...', $mid);
    }
}
