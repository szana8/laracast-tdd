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
        $this->signIn();

        $user = create('App\User');

        $issue = create('App\Issue', ['user_id' => auth()->id()]);

        $this->get("/profiles/". auth()->user()->name)
            ->assertSee($issue->title)
            ->assertSee($issue->description);
    }
}
