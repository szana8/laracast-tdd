<?php

namespace Tests\Unit;


use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
   /** @test */
    function it_validate_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException(\Exception::class);

        $spam->detect('Yahoo Customer Support');
    }

    /** @test */
    function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect('Hello world aaaaaaaaaa');
    }
}
