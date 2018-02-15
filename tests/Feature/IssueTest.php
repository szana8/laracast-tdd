<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IssueTest extends TestCase
{
    use DatabaseMigrations;

    protected $issue;


    public function setUp()
    {
        parent::setUp();

        $this->issue = factory('App\Issue')->create();
    }

    /** @test */
    public function a_user_can_browse_issues()
    {
        $this->get('/issues')
            ->assertSee($this->issue->summary);
    }

    /** @test */
    public function a_user_can_read_a_single_issue()
    {
        $this->get($this->issue->path())
            ->assertSee($this->issue->summary);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_an_issue()
    {
        // Given we have an issue
        // And that issue includes replies
        $reply = factory('App\Reply')->create(['issue_id' => $this->issue->id]);

        // When we visit an issue page
        // Then we should see the replies
        $this->get($this->issue->path())
            ->assertSee($reply->body);
    }
}
