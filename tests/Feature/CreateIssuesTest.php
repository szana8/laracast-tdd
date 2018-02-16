<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateIssuesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_may_not_create_issues()
    {
        $this->get('/issues/create')
            ->assertRedirect('login');

        $this->post('/issues')
            ->assertRedirect('login');
    }

   /** @test */
    public function an_authenticated_user_can_create_new_issue()
    {
        $this->signIn();

        $issue = make('App\Issue');

        $response = $this->post('/issues', $issue->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($issue->summary)
            ->assertSee($issue->description);
    }

    /** @test */
    function an_issue_requires_summary()
    {
        $this->publishIssue(['summary' => null])
            ->assertSessionHasErrors('summary');
    }

    /** @test */
    function an_issue_requires_description()
    {
        $this->publishIssue(['description' => null])
            ->assertSessionHasErrors('description');
    }

    /** @test */
    function an_issue_requires_a_valid_category()
    {
        factory('App\Category', 2)->create();

        $d = $this->publishIssue(['category_id' => null])
            ->assertSessionHasErrors('category_id');

        $this->publishIssue(['category_id' => 999])
            ->assertSessionHasErrors('category_id');
    }

    /**
     * @param $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function publishIssue($overrides)
    {
        $this->signIn();

        $issue = make('App\Issue',$overrides);

        return $this->post('/issues', $issue->toArray());
    }
}
