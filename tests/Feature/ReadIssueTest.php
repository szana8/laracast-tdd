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
    function a_user_can_browse_issues()
    {
        $this->get('/issues')
            ->assertSee($this->issue->summary);
    }

    /** @test */
    function a_user_can_read_a_single_issue()
    {
        $this->get($this->issue->path())
            ->assertSee($this->issue->summary);
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

    /** @test */
    function a_user_can_filter_issues_by_popularity()
    {
        $issueWithTwoReplies = create('App\Issue');
        create('App\Reply', ['issue_id' => $issueWithTwoReplies->id], 2);

        $issueWithThreeReplies = create('App\Issue');
        create('App\Reply', ['issue_id' => $issueWithThreeReplies->id], 3);

        $issueWithNoReplies = $this->issue;

        $response = $this->getJson('/issues?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_issues_by_those_that_are_unanswered()
    {
        $issue = create('App\Issue');
        create('App\Reply', ['issue_id' => $issue->id]);

        $response = $this->getJson('/issues?unanswered=1')->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_issue()
    {
        $issue = create('App\Issue');
        create('App\Reply', ['issue_id' => $issue->id]);

        $response = $this->getJson($issue->path() . '/replies')->json();

        $this->assertCount(1, $response['data']);
    }
}
