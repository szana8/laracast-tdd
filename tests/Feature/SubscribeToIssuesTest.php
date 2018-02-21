<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToIssuesTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */
    public function a_user_can_subscribe_to_issues()
    {
        $this->signIn();

        $issue = create('App\Issue');

        $this->post($issue->path() . '/subscriptions');

        $issue->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

//        auth()->user()->notifications;

//        $this->assertDatabaseHas('', $issue->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_issues()
    {
        $this->signIn();

        $issue = create('App\Issue');

        $issue->subscribe();

        $this->delete($issue->path() . '/subscriptions');

        $this->assertCount(0, $issue->subscriptions);
    }
}
