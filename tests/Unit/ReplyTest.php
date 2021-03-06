<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }
    
    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply', [
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }
    
    /** @test */
    function it_wraps_mentioned_usernames_in_tne_body_within_anchor_tags()
    {
        $reply = create('App\Reply', [
            'body' => 'Hello @JaneDoe.'
        ]);

        $this->assertEquals('Hello <a href="/profiles/JaneDoe">@JaneDoe</a>.', $reply->body);
    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->issue->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    function an_reply_body_is_sanitized_automatically()
    {
        $reply = make('App\Reply', ['body' => '<script>alert("bar");</script>']);

        $this->assertEmpty($reply->body);
    }
}
