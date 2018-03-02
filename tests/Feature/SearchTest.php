<?php

namespace Tests\Feature;


use App\Issue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_search_issues()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'foobar';

        create('App\Issue', [], 2);
        create('App\Issue', ['description' => "An issue with the {$search} term"], 2);

        do {
            sleep(.25);

            $result = $this->getJson('/issues/search?q=foobar')->json();
        } while (empty($result));

        $this->assertCount(2, $result['data']);

        Issue::latest()->take(4)->unsearchable();
    }
}
