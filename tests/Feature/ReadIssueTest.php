<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadIssueTest extends TestCase
{
    use DatabaseMigrations;

    protected $issue;

    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->issue = factory('App\Issue')->create();
    }

    /** @test */
    function an_issue_can_make_a_string_path()
    {
        $issue = create('App\Issue');

        $this->assertEquals('/issues/' . $issue->category->slug . '/' . $issue->id, $issue->path());
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
        $reply = factory('App\Reply')->create(['issue_id' => $this->issue->id]);

        $this->get($this->issue->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_issues_according_to_a_category()
    {
        $category = create('App\Category');
        $issueInCategory = create('App\Issue', ['category_id' => $category->id]);
        $issueNotInCategory = create('App\Issue');

        $this->get('/issues/' . $category->slug)
            ->assertSee($issueInCategory->summary)
            ->assertDontSee($issueNotInCategory->summary);
    }

    /** @test */
    function a_user_can_filter_issues_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $issueByJohn = create('App\Issue', ['user_id' => auth()->id()]);
        $issueNotByJohn = create('App\Issue');

        $this->get('/issues?by=JohnDoe')
            ->assertSee($issueByJohn->summary)
            ->assertDontSee($issueNotByJohn->summary);
    }
}
