<?php

namespace Tests\Feature;

use App\Issue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateIssuesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_may_not_create_issues()
    {
        $this->get('/issues/create')
            ->assertRedirect(route('login'));

        $this->post(route('issues'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    function new_user_must_first_confirm_their_email_address_before_creating_issues()
    {
        $user = factory('App\User')->states('uncomfirmed')->create();

        $this->signIn($user);

        $issue = make('App\Issue');

        $this->post(route('issues'), $issue->toArray())
            ->assertRedirect(route('issues'))
            ->assertSessionHas('flash', 'You must first confirm your email address');
    }

    /** @test */
    function a_user_can_create_new_issue()
    {
        $this->signIn();

        $issue = make('App\Issue');

        $response = $this->post(route('issues'), $issue->toArray());

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
    function an_issue_requires_a_unique_slug()
    {
        $this->signIn();

        $issue = create('App\Issue', ['summary' => 'Foo Summary', 'slug' => 'foo-summary']);

        $this->assertEquals($issue->fresh()->slug, 'foo-summary');

        $this->post(route('issues'), $issue->toArray());

        $this->assertTrue(Issue::whereSlug('foo-summary-2')->exists());

        $this->post(route('issues'), $issue->toArray());

        $this->assertTrue(Issue::whereSlug('foo-summary-3')->exists());
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
            ->assertRedirect(route('login'));

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

        return $this->post(route('issues'), $issue->toArray());
    }
}
