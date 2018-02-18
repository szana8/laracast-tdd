<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    function profiles_display_all_issues_created_by_the_associated_user()
    {
        $user = create('App\User');

        $issue = create('App\Issue', ['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($issue->summary)
            ->assertSee($issue->description);
    }
}
