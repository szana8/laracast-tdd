<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_issue_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $issue = create('App\Issue', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['issue_id' => $issue->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    function only_the_issue_creator_may_mark_a_reply_as_best()
    {
        $this->signIn();

        $issue = create('App\Issue', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['issue_id' => $issue->id], 2);

        $this->signIn(create('App\User'));

        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
