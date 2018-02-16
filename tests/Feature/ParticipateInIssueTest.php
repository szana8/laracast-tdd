<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInIssueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->post('/issues/categories/1/replies', [])
            ->assertRedirect('login');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_issues()
    {
        $this->signIn();

        $issue = create('App\Issue');
        $reply = factory('App\Reply')->create(['issue_id' => $issue->id]);

        $this->post($issue->path() . '/replies', $reply->toArray());

        $this->get($issue->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->signIn();

        $issue = create('App\Issue');
        $reply = make('App\Reply', ['issue_id' => $issue->id, 'body' => null]);

        $this->post($issue->path() . '/replies', $reply->toArray())->assertSessionHasErrors('body');
    }
}
