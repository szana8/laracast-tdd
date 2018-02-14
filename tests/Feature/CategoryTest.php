<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_categories()
    {
        $category = factory('App\Category')->create();

        $response = $this->get('/category');
        $response->assertSee($category->name);
    }

    /** @test */
    public function a_user_can_browse_project_category()
    {
        $category = factory('App\Category')->create();

        $response = $this->get('/category/' . $category->id);
        $response->assertSee($category->name);
    }
}
