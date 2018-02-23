<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create('App\User', ['name' => 'JaneDoe']);

        $issue = create('App\Issue');

        $reply = make('App\Reply', [
            'issue_id' => $issue->id,
            'body' => '@JaneDoe look at this'
        ]);

        $d = $this->json('post', $issue->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function it_can_fetch_all_mentioned_user_starting_with_a_givven_characters()
    {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JohnDoe2']);
        create('App\User', ['name' => 'janeDoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }

}
