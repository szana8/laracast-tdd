<?php

namespace Tests\Feature;


use App\Rules\Recaptcha;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateIssuesTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }


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

        $response = $this->publishIssue(['title' => 'Some title', 'description' => 'Foobar']);

        $this->get($response->headers->get('Location'))
            ->assertSee('Some title')
            ->assertSee('Foobar');
    }

    /** @test */
    function an_issue_requires_summary()
    {
        $this->publishIssue(['title' => null])
            ->assertSessionHasErrors('title');
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

        $issue = create('App\Issue', ['title' => 'Foo Title']);

        $this->assertEquals($issue->fresh()->slug, 'foo-title');

        $issue = $this->postJson(route('issues'), $issue->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("foo-title-{$issue['id']}", $issue['slug']);
    }

    /** @test */
    function an_issue_with_a_title_that_ends_in_a_number_should_generates_a_proper_slug()
    {
        $this->signIn();

        $issue = create('App\Issue', ['title' => 'Some Title 24']);

        $issue = $this->postJson(route('issues'), $issue->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("some-title-24-{$issue['id']}", $issue['slug']);
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

        return $this->post(route('issues'), $issue->toArray() + ['g-recaptcha-response' => 'token']);
    }

    /** @test */
    function an_issue_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishIssue(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }
}
