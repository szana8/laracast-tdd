<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */
    function it_records_activity_when_an_issue_is_created()
    {
        $this->signIn();

        $issue = create('App\Issue');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_issue',
            'user_id' => auth()->id(),
            'subject_id' => $issue->id,
            'subject_type' => 'App\Issue'
        ]);

        $activity = Activity::first();

       $this->assertEquals($activity->subject->id, $issue->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        create('App\Issue', ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
