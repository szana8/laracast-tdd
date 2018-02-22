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

        $this->assertCount(1, $issue->fresh()->subscriptions);
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
