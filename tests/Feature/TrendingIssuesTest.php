<?php

namespace Tests\Feature;

use App\Trending;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class TrendingIssuesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var
     */
    protected $trending;

    /**
     * Set up the test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }


    /** @test */
    function it_increments_an_issue_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $issue = create('App\Issue');

        $this->call('GET', $issue->path());

        $this->assertCount(1, $trending = $this->trending->get());

        $this->assertEquals($issue->summary, $trending[0]->summary);
    }
}