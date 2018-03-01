<?php

namespace Tests\Unit;

use App\Notifications\IssueWasUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class IssueTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var
     */
    protected $issue;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->issue = factory('App\Issue')->create();
    }
    
    /** @test */
    function an_issue_has_a_path()
    {
        $issue = create('App\Issue');

        $this->assertEquals("/issues/{$issue->category->slug}/{$issue->slug}", $issue->path());
    }
    
    /** @test */
    function an_issue_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->issue->creator);
    }

    /** @test */
    function an_issue_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->issue->replies);
    }

    /** @test */
    function an_issue_can_add_a_reply()
    {
        $this->issue->addReply([
            'body' => 'FooBar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->issue->replies);
    }

    /** @test */
    function an_issue_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->issue
            ->subscribe()
            ->addReply([
                'body' => 'FooBar',
                'user_id' => 1
            ]);

        Notification::assertSentTo(auth()->user(), IssueWasUpdated::class);
    }

    /** @test */
    function an_issue_belongs_to_a_category()
    {
        $issue = create('App\Issue');

        $this->assertInstanceOf('App\Category', $issue->category);
    }

    /** @test */
    function an_issue_can_be_subscribed_to()
    {
        $issue = create('App\Issue');

        $issue->subscribe($userId = 1);

        $this->assertEquals(1, $issue->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function an_issue_can_be_unsubscribed_from()
    {
        $issue = create('App\Issue');

        $issue->subscribe($userId = 1);

        $issue->unSubscribe($userId);

        $this->assertEquals(0, $issue->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $issue = create('App\Issue');

        $this->signIn();

        $issue->subscribe();

        $this->assertTrue($issue->isSubscribedTo);
    }

    /** @test */
    function an_issue_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $issue = create('App\Issue');

        tap(auth()->user(), function($user) use ($issue) {
            $this->assertTrue($issue->hasUpdateFor($user));

            $user->read($issue);

            $this->assertFalse($issue->hasUpdateFor($user));
        });
    }

}
