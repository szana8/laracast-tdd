<?php

namespace Tests\Unit;

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
    function an_issue_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->issue->replies);
    }

    /** @test */
    function an_issue_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->issue->creator);
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
}
