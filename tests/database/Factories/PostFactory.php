<?php


namespace Acadea\FullSite\Tests\Database\Factories;


use Acadea\FullSite\Tests\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;
    public function definition()
    {
        return [
            'title' => $this->faker->realText(20)
        ];
    }

}
