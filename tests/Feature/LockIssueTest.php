<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockIssueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function non_administrators_may_not_lock_issues()
    {
        $this->signIn();

        $issue = create('App\Issue', ['user_id' => auth()->id()]);

        $this->post(route('locked-issues.store', $issue))->assertStatus(403);

        $this->assertFalse($issue->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_issues()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $issue = create('App\Issue', ['user_id' => auth()->id()]);

        $this->post(route('locked-issues.store', $issue));

        $this->assertTrue($issue->fresh()->locked);
    }

    /** @test */
    function administrators_can_unlock_issues()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $issue = create('App\Issue', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-issues.destroy', $issue));

        $this->assertFalse($issue->fresh()->locked);
    }

    /** @test */
    function once_locked_an_issue_may_not_receive_new_replies()
    {
        $this->signIn();

        $issue = create('App\Issue', ['locked' => true]);

        $this->post($issue->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
