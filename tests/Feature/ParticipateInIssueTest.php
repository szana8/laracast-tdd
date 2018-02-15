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
        //$this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/issues/1/replies', []);
    }

    /** @test */
    function an_authenticated_user_may_participate_in_issues()
    {
        $this->be($user = factory('App\User')->create());

        $issue = factory('App\Issue')->create();

        $reply = factory('App\Reply')->create(['issue_id' => $issue->id]);

        $this->post($issue->path() . '/replies', $reply->toArray());
        $this->get($issue->path())->assertSee($reply->body);
    }
}
