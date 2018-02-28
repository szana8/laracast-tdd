<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'FooBar',
            'password_confirmation' => 'FooBar'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    function users_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'FooBar',
            'password_confirmation' => 'FooBar'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token'=> $user->confirmation_token]))
            ->assertRedirect('issues');

        $this->assertTrue($user->fresh()->confirmed);

        $this->assertNull($user->fresh()->confirmation_token);
    }

    /** @test */
    function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token'=> 'invalid']))
            ->assertRedirect(route('issues'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
