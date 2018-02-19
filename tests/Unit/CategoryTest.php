<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_category_consist_of_issues()
    {
        $category = create('App\Category');
        $issue = create('App\Issue', ['category_id' => $category->id]);

        $this->assertTrue($category->issues->contains($issue));
    }
}
