<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateIssuesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_may_not_create_issues()
    {
        $this->get('/issues/create')
            ->assertRedirect('login');

        $this->post('/issues')
            ->assertRedirect('login');
    }

    /** @test */
    function authenticated_user_must_first_confirm_their_email_address_before_createing_issues()
    {
        $this->publishIssue()
            ->assertRedirect('/issues')
            ->assertSessionHas('flash', 'You must first confirm your email address');
    }

    /** @test */
    function an_authenticated_user_can_create_new_issue()
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

    /** @test */
    function authorized_user_can_delete_issues()
    {
        $this->signIn();

        $issue = create('App\Issue', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['issue_id' => $issue->id]);

        $response = $this->json('DELETE', $issue->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('issues', ['id' => $issue->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $issue->id,
            'subject_type' => get_class($issue)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    /** @test */
    function unauthorized_users_may_not_delete_issues()
    {
        $issue = create('App\Issue');

        $this->delete($issue->path())
            ->assertRedirect('login');

        $this->signIn();

        $this->delete($issue->path())
            ->assertStatus(403);
    }

    /**
     * @param $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    function publishIssue($overrides = [])
    {
        $this->signIn();

        $issue = make('App\Issue', $overrides);

        return $this->post('/issues', $issue->toArray());
    }
}
